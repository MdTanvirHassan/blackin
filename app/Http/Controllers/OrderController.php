<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AffiliateController;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\CouponUsage;
use App\Models\Coupon;
use App\Models\User;
use App\Models\CombinedOrder;
use App\Models\SmsTemplate;
use Auth;
use Mail;
use App\Mail\InvoiceEmailManager;
use App\Models\OrdersExport;
use App\Utility\NotificationUtility;
use CoreComponentRepository;
use App\Utility\SmsUtility;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderNotification;
use App\Utility\EmailUtility;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_orders|view_inhouse_orders|view_seller_orders|view_pickup_point_orders|view_all_offline_payment_orders'])->only('all_orders');
        $this->middleware(['permission:view_order_details'])->only('show');
        $this->middleware(['permission:delete_order'])->only('destroy','bulk_order_delete');
    }

    // All Orders
    public function all_orders(Request $request)
    {
        CoreComponentRepository::instantiateShopRepository();

        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';
        $order_type = '';

        $orders = Order::orderBy('id', 'desc');
        $admin_user_id = get_admin()->id;

        if (Route::currentRouteName() == 'inhouse_orders.index' && Auth::user()->can('view_inhouse_orders')) {
            $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
        }
        elseif (Route::currentRouteName() == 'seller_orders.index' && Auth::user()->can('view_seller_orders')) {
            $orders = $orders->where('orders.seller_id', '!=', $admin_user_id);
        }
        elseif (Route::currentRouteName() == 'pick_up_point.index' && Auth::user()->can('view_pickup_point_orders')) {
            if (get_setting('vendor_system_activation') != 1) {
                $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
            }
            $orders->where('shipping_type', 'pickup_point')->orderBy('code', 'desc');
            if (
                Auth::user()->user_type == 'staff' &&
                Auth::user()->staff->pick_up_point != null
            ) {
                $orders->where('shipping_type', 'pickup_point')
                    ->where('pickup_point_id', Auth::user()->staff->pick_up_point->id);
            }
        }
        elseif (Route::currentRouteName() == 'all_orders.index' && Auth::user()->can('view_all_orders')) {
            if (get_setting('vendor_system_activation') != 1) {
                $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
            }
        }
        elseif (Route::currentRouteName() == 'offline_payment_orders.index' && Auth::user()->can('view_all_offline_payment_orders')) {
            $orders = $orders->where('orders.manual_payment', 1);
            if($request->order_type != null){
                $order_type = $request->order_type;
                $orders = $order_type =='inhouse_orders' ? 
                            $orders->where('orders.seller_id', '=', $admin_user_id) : 
                            $orders->where('orders.seller_id', '!=', $admin_user_id);
            }
        }
        elseif (Route::currentRouteName() == 'unpaid_orders.index' && Auth::user()->can('view_all_unpaid_orders')) {
            $orders = $orders->where('orders.payment_status', 'unpaid');
        }
        else {
            abort(403);
        }

        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }
        $orders = $orders->paginate(15);
        $unpaid_order_payment_notification = get_notification_type('complete_unpaid_order_payment', 'type');
        return view('backend.sales.index', compact('orders', 'sort_search', 'order_type', 'payment_status', 'delivery_status', 'date', 'unpaid_order_payment_notification'));
    }

    public function show($id)
    {
        $order = Order::with(['orderDetails.product', 'orderDetails.product.stocks', 'user', 'shop', 'pickup_point', 'carrier'])
            ->findOrFail(decrypt($id));
        
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = collect();
        
        // Only query delivery boys if city exists in shipping address
        if ($order_shipping_address && isset($order_shipping_address->city) && $order_shipping_address->city) {
            $delivery_boys = User::where('city', $order_shipping_address->city)
                ->where('user_type', 'delivery_boy')
                ->get();
        }
                
        if(env('DEMO_MODE') != 'On') {
            $order->viewed = 1;
            $order->save();
        }

        return view('backend.sales.show', compact('order', 'delivery_boys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Handle both authenticated and guest users
        if (auth()->check()) {
            $carts = Cart::where('user_id', Auth::user()->id)->active()->get();
        } else {
            // Guest checkout - get carts by temp_user_id
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->active()->get() : collect();
        }

        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        $address = null;
        if (isset($carts[0]['address_id']) && $carts[0]['address_id'] != null) {
            $address = Address::where('id', $carts[0]['address_id'])->first();
        }

        $shippingAddress = [];
        if ($address != null) {
            $shippingAddress['name']        = Auth::user() ? Auth::user()->name : '';
            $shippingAddress['email']       = Auth::user() ? Auth::user()->email : '';
            $shippingAddress['address']     = $address->address. (isset($address->area) ? ', ' . $address->area->name : '');
            $shippingAddress['country']     = $address->country ? $address->country->name : '';
            if(get_setting('has_state') == 1 && $address->state){
            $shippingAddress['state']       = $address->state->name;
            }
            $shippingAddress['city']        = $address->city ? $address->city->name : '';
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone']       = $address->phone;
            if ($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
            }
        } else {
            // Handle form fields directly (for new checkout flow)
            $shippingAddress['name']        = $request->name ?? (Auth::user() ? Auth::user()->name : '');
            $shippingAddress['email']       = $request->email ?? (Auth::user() ? Auth::user()->email : '');
            $shippingAddress['address']     = $request->address ?? '';
            $shippingAddress['phone']       = $request->phone ?? '';
            // Try to get country/division/district from address if available
            if ($request->has('country_id') && $request->country_id) {
                $country = \App\Models\Country::find($request->country_id);
                $shippingAddress['country'] = $country ? $country->name : '';
            }
            if ($request->has('division_id') && $request->division_id) {
                $division = \App\Models\Division::find($request->division_id);
                $shippingAddress['division'] = $division ? $division->getTranslation('name') : '';
            }
            if ($request->has('district_id') && $request->district_id) {
                $district = \App\Models\District::find($request->district_id);
                $shippingAddress['district'] = $district ? $district->getTranslation('name') : '';
            }
            if ($request->has('city_id') && $request->city_id) {
                $city = \App\Models\City::find($request->city_id);
                $shippingAddress['city'] = $city ? $city->name : '';
            }
            if ($request->has('area_id') && $request->area_id) {
                $area = \App\Models\Area::find($request->area_id);
                $shippingAddress['area'] = $area ? $area->name : '';
            }
            if ($request->has('postal_code')) {
                $shippingAddress['postal_code'] = $request->postal_code;
            }
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = Auth::user() ? Auth::user()->id : null;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();

        // Generate a single order code for all orders in this combined order
        $shared_order_code = date('Ymd-His') . rand(10, 99);

        // Group all cart items by seller_id (regardless of whether they're group products or normal products)
        // This ensures all products from the same seller go into ONE order
        // IMPORTANT: We group by product->user_id (seller_id), NOT by group_product_id
        // This means all products owned by the same seller will be in the same order,
        // whether they're part of a bundle or standalone products
        $seller_products = array();
        
        // Process ALL cart items in a single pass to ensure proper grouping
        foreach ($carts as $cartItem) {
            // Handle both array and object access for cart items
            $product_id = isset($cartItem['product_id']) ? $cartItem['product_id'] : (isset($cartItem->product_id) ? $cartItem->product_id : null);
            
            if (!$product_id) {
                continue; // Skip if product_id is not found
            }
            
            $product = Product::find($product_id);
            if (!$product) {
                continue; // Skip if product not found
            }
            
            // Use product's seller_id (user_id) to group items
            // All products (group product items and normal products) from the same seller will be grouped together
            // This is the KEY: we group by the actual product owner, not by bundle/group_product_id
            $seller_id = $product->user_id;
            
            if (!isset($seller_products[$seller_id])) {
                $seller_products[$seller_id] = [];
            }
            
            // Convert cart item to array if it's an object for consistency
            $cartItemArray = is_array($cartItem) ? $cartItem : (array) $cartItem;
            if (is_object($cartItem)) {
                // Preserve object properties when converting - ensure ALL fields are preserved
                $cartItemArray = [
                    'product_id' => $cartItem->product_id ?? null,
                    'variation' => $cartItem->variation ?? '',
                    'quantity' => $cartItem->quantity ?? 1,
                    'price' => $cartItem->price ?? 0,
                    'tax' => $cartItem->tax ?? 0,
                    'shipping_cost' => $cartItem->shipping_cost ?? 0,
                    'shipping_type' => $cartItem->shipping_type ?? 'home_delivery',
                    'discount' => $cartItem->discount ?? 0,
                    'product_referral_code' => $cartItem->product_referral_code ?? null,
                    'group_product_id' => $cartItem->group_product_id ?? null,
                    'group_product_slot_id' => $cartItem->group_product_slot_id ?? null,
                    'group_product_slot_combination_hash' => $cartItem->group_product_slot_combination_hash ?? null,
                    'coupon_code' => $cartItem->coupon_code ?? null,
                    'pickup_point' => $cartItem->pickup_point ?? null,
                    'carrier_id' => $cartItem->carrier_id ?? null,
                ];
            }
            
            // Add to seller's product array (both group products and normal products from same seller)
            // This ensures ALL items from the same seller go into ONE order
            // No separation based on group_product_id - only by seller_id
            // ALL items (including ALL bundle slots) from the same seller will be in the same order
            $seller_products[$seller_id][] = $cartItemArray;
        }

        // Create ONE order per seller (containing both group products and normal products)
        // Use database transaction to ensure all items from same seller are processed together
        DB::beginTransaction();
        try {
            foreach ($seller_products as $seller_id => $seller_product) {
                // CRITICAL: Process ALL items from this seller in ONE order
                // Do NOT check for existing orders here - we're creating fresh orders in this transaction
                // All items (group products AND normal products) from this seller go into ONE order
                
                // Create new order for this seller (will contain ALL items: group products AND normal products from this seller)
                $order = new Order;
                $order->combined_order_id = $combined_order->id;
                $order->user_id = Auth::user() ? Auth::user()->id : null; // Nullable for guest orders
                $order->seller_id = $seller_id; // Set seller_id upfront
                $order->shipping_address = $combined_order->shipping_address;
                $order->additional_info = $request->additional_info;
                $order->payment_type = $request->payment_option;
                $order->delivery_viewed = '0';
                $order->payment_status_viewed = '0';
                $order->code = $shared_order_code; // Use same code for all orders in combined order
                $order->date = strtotime('now');
                
                // Save order early to get ID for order details (we'll update totals later)
                $order->save();

                // Initialize totals for this order
                $subtotal = 0;
                $tax = 0;
                $shipping = 0;
                $coupon_discount = 0;
                $shipping_type = 'home_delivery';
                $pickup_point_id = null;
                $carrier_id = null;

                //Order Details Storing - Process ALL items from this seller together
                // This includes both group product items AND normal products
                foreach ($seller_product as $cartItem) {
                $product = Product::find($cartItem['product_id']);
                if (!$product) continue;

                // Check if this is a group product (bundle)
                $groupProductId = isset($cartItem['group_product_id']) ? $cartItem['group_product_id'] : (isset($cartItem->group_product_id) ? $cartItem->group_product_id : null);
                $isGroupProduct = $groupProductId != null;
                
                // For group products, use the stored price (which already includes product discounts)
                // For regular products, calculate price with product discounts
                if ($isGroupProduct && isset($cartItem['price'])) {
                    // Group product: price is already calculated and stored in cart (includes product discounts)
                    $unitPrice = (float)$cartItem['price'];
                    $itemSubtotal = $unitPrice * (int)$cartItem['quantity'];
                } else {
                    // Regular product: calculate price with product discounts
                    $unitPrice = (float)cart_product_price($cartItem, $product, false, false);
                    $itemSubtotal = $unitPrice * (int)$cartItem['quantity'];
                }

                // Get coupon discount for this item
                $itemCouponDiscount = isset($cartItem['discount']) ? (float)$cartItem['discount'] : 0;
                
                // Calculate final price after coupon discount (for order detail)
                $finalPrice = $itemSubtotal - $itemCouponDiscount;
                
                // Ensure price doesn't go negative
                if ($finalPrice < 0) {
                    $finalPrice = 0;
                }

                // Subtotal is before coupon discount (for proper calculation)
                $subtotal += $itemSubtotal;
                $tax += cart_product_tax($cartItem, $product, false) * (int)$cartItem['quantity'];
                $coupon_discount += $itemCouponDiscount;

                $product_variation = $cartItem['variation'] ?? '';

                // Stock check - only for non-digital products
                if ($product->digital != 1) {
                    $product_stock = $product->stocks->where('variant', $product_variation)->first();
                    if ($product_stock && (int)$cartItem['quantity'] > $product_stock->qty) {
                        flash(translate('The requested quantity is not available for ') . $product->getTranslation('name'))->warning();
                        return redirect()->route('cart')->send();
                    } elseif ($product_stock) {
                        $product_stock->qty -= (int)$cartItem['quantity'];
                        $product_stock->save();
                    }
                }

                // Set shipping type from first cart item (all items from same seller should have same shipping)
                if ($shipping_type == 'home_delivery' && isset($cartItem['shipping_type'])) {
                    $shipping_type = $cartItem['shipping_type'];
                    if ($shipping_type == 'pickup_point' && isset($cartItem['pickup_point'])) {
                        $pickup_point_id = $cartItem['pickup_point'];
                    }
                    if ($shipping_type == 'carrier' && isset($cartItem['carrier_id'])) {
                        $carrier_id = $cartItem['carrier_id'];
                    }
                }

                    $order_detail = new OrderDetail;
                    $order_detail->order_id = $order->id;
                    $order_detail->seller_id = $product->user_id;
                    $order_detail->product_id = $product->id;
                    $order_detail->variation = $product_variation;
                    // Store final price after all discounts
                    $order_detail->price = $finalPrice;
                    $order_detail->tax = cart_product_tax($cartItem, $product, false) * (int)$cartItem['quantity'];
                    $order_detail->shipping_type = $cartItem['shipping_type'] ?? 'home_delivery';
                    $order_detail->product_referral_code = $cartItem['product_referral_code'] ?? null;
                    $order_detail->shipping_cost = isset($cartItem['shipping_cost']) ? (float)$cartItem['shipping_cost'] : 0;
                    
                    // Store group product info if applicable
                    // This ensures group product items are properly identified in order details
                    if ($isGroupProduct) {
                        $order_detail->combo_id = $groupProductId;
                        // Store slot ID and combination hash to differentiate between group product items
                        $slotId = isset($cartItem['group_product_slot_id']) ? $cartItem['group_product_slot_id'] : (isset($cartItem->group_product_slot_id) ? $cartItem->group_product_slot_id : null);
                        $combinationHash = isset($cartItem['group_product_slot_combination_hash']) ? $cartItem['group_product_slot_combination_hash'] : (isset($cartItem->group_product_slot_combination_hash) ? $cartItem->group_product_slot_combination_hash : null);
                        
                        if ($slotId) {
                            $order_detail->group_product_slot_id = $slotId;
                        }
                        if ($combinationHash) {
                            $order_detail->group_product_slot_combination_hash = $combinationHash;
                        }
                    }

                    
                    if (addon_is_activated('refund_request')) {

                        $refund_type = get_setting('refund_type');

                        if($refund_type == 'global_refund' && $product->refundable != 0){

                            $refund_days = get_setting('refund_request_time');
                            $order_detail->refund_days = (int) $refund_days;

                        }elseif($refund_type == 'category_based_refund' && $product->refundable != 0){

                            $refund_days = $product->main_category->refund_request_time;
                            $order_detail->refund_days = (int) $refund_days;

                        }
                    }
                    
                    $shipping += $order_detail->shipping_cost;
                    //End of storing shipping cost

                    $order_detail->quantity = $cartItem['quantity'];

                    if (addon_is_activated('club_point')) {
                        $order_detail->earn_point = $product->earn_point;
                    }

                    $order_detail->save();

                    $product->num_of_sale += $cartItem['quantity'];
                    $product->save();

                    if ($product->added_by == 'seller' && $product->user->seller != null) {
                        $seller = $product->user->seller;
                        $seller->num_of_sale += $cartItem['quantity'];
                        $seller->save();
                    }

                    if (addon_is_activated('affiliate_system')) {
                        if ($order_detail->product_referral_code) {
                            $referred_by_user = User::where('referral_code', $order_detail->product_referral_code)->first();

                            $affiliateController = new AffiliateController;
                            $affiliateController->processAffiliateStats($referred_by_user->id, 0, $order_detail->quantity, 0, 0);
                        }
                    }
                }

                // Set order shipping details
                $order->shipping_type = $shipping_type;
                $order->pickup_point_id = $pickup_point_id;
                $order->carrier_id = $carrier_id;

                // Calculate grand total: subtotal (after product discounts) + tax + shipping - coupon discount
                $order->grand_total = $subtotal + $tax + $shipping - $coupon_discount;
                
                // Ensure grand total doesn't go negative
                if ($order->grand_total < 0) {
                    $order->grand_total = 0;
                }

                // Store coupon discount if applicable
                if (isset($seller_product[0]) && isset($seller_product[0]['coupon_code']) && $seller_product[0]['coupon_code'] != null && $coupon_discount > 0) {
                    $order->coupon_discount = $coupon_discount;
                    
                    $coupon = Coupon::where('code', $seller_product[0]['coupon_code'])->first();
                    if ($coupon) {
                        $coupon_usage = new CouponUsage;
                        $coupon_usage->user_id = Auth::user() ? Auth::user()->id : null;
                        $coupon_usage->coupon_id = $coupon->id;
                        $coupon_usage->save();
                    }
                } else {
                    $order->coupon_discount = 0;
                }

                // Update the order once with all final details
                // All order details (group products AND normal products) are now saved
                $order->save();

                $combined_order->grand_total += $order->grand_total;
            }

            // Commit transaction - all orders and order details are now saved together
            DB::commit();
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            throw $e;
        }

        $combined_order->save();

        $request->session()->put('combined_order_id', $combined_order->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->single_order_delete($id);
        if ($result) {
            flash(translate('Order has been deleted successfully'))->success();
        } else {
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function single_order_delete($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            $order->commissionHistory()->delete();
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {
                    product_restock($orderDetail);
                } catch (\Exception $e) {
                }

                $orderDetail->delete();
            }
            $order->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->single_order_delete($order_id);
            }
        }

        return 1;
    } 

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('seller.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        if($request->status == 'delivered'){
            $order->delivered_date = date("Y-m-d H:i:s");
            $order->save();
        }
        
        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        // If the order is cancelled and the seller commission is calculated, deduct seller earning
        if($request->status == 'cancelled' && $order->user->user_type == 'seller' && $order->payment_status == 'paid' && $order->commission_calculated == 1){
            $sellerEarning = $order->commissionHistory->seller_earning;
            $shop = $order->shop;
            $shop->admin_to_pay -= $sellerEarning;
            $shop->save();
        }

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    product_restock($orderDetail);
                }
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {

                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    product_restock($orderDetail);
                }

                if (addon_is_activated('affiliate_system')) {
                    if (($request->status == 'delivered' || $request->status == 'cancelled') &&
                        $orderDetail->product_referral_code
                    ) {

                        $no_of_delivered = 0;
                        $no_of_canceled = 0;

                        if ($request->status == 'delivered') {
                            $no_of_delivered = $orderDetail->quantity;
                        }
                        if ($request->status == 'cancelled') {
                            $no_of_canceled = $orderDetail->quantity;
                        }

                        $referred_by_user = User::where('referral_code', $orderDetail->product_referral_code)->first();

                        $affiliateController = new AffiliateController;
                        $affiliateController->processAffiliateStats($referred_by_user->id, 0, 0, $no_of_delivered, $no_of_canceled);
                    }
                }
            }
        }
        // Delivery Status change email notification to Admin, seller, Customer
        // Skip email notifications for guest orders
        if ($order->user_id != null) {
            EmailUtility::order_email($order, $request->status);  
        }

        // Delivery Status change SMS notification (skip for guest orders)
        if ($order->user_id != null && addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'delivery_status_change')->first()->status == 1) {
            try {
                SmsUtility::delivery_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {}
        }

        //Send web Notifications to user
        // Skip notifications for guest orders
        if ($order->user_id != null) {
            NotificationUtility::sendNotification($order, $request->status);
        }

        //Sends Firebase Notifications to user (skip for guest orders)
        if ($order->user_id != null && get_setting('google_firebase') == 1 && $order->user && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('delivery_boy')) {
            if (Auth::user()->user_type == 'delivery_boy') {
                $deliveryBoyController = new DeliveryBoyController;
                $deliveryBoyController->store_delivery_history($order);
            }
        }

        return 1;
    }

    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();


        if (
            $order->payment_status == 'paid' &&
            $order->commission_calculated == 0
        ) {
            calculateCommissionAffilationClubPoint($order);
        }

        // Payment Status change email notification to Admin, seller, Customer (skip for guest orders)
        if($request->status == 'paid' && $order->user_id != null){
            EmailUtility::order_email($order, $request->status);  
        }

        //Sends Web Notifications to Admin, seller, Customer (skip for guest orders)
        if ($order->user_id != null) {
            NotificationUtility::sendNotification($order, $request->status);
        }

        //Sends Firebase Notifications to Admin, seller, Customer (skip for guest orders)
        if ($order->user_id != null && get_setting('google_firebase') == 1 && $order->user && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->payment_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'payment_status_change')->first()->status == 1) {
            try {
                SmsUtility::payment_status_change(json_decode($order->shipping_address)->phone, $order);
            } catch (\Exception $e) {
            }
        }
        return 1;
    }

    public function assign_delivery_boy(Request $request)
    {
        if (addon_is_activated('delivery_boy')) {

            $order = Order::findOrFail($request->order_id);
            $order->assign_delivery_boy = $request->delivery_boy;
            $order->delivery_history_date = date("Y-m-d H:i:s");
            $order->save();

            $delivery_history = \App\Models\DeliveryHistory::where('order_id', $order->id)
                ->where('delivery_status', $order->delivery_status)
                ->first();

            if (empty($delivery_history)) {
                $delivery_history = new \App\Models\DeliveryHistory;

                $delivery_history->order_id = $order->id;
                $delivery_history->delivery_status = $order->delivery_status;
                $delivery_history->payment_type = $order->payment_type;
            }
            $delivery_history->delivery_boy_id = $request->delivery_boy;

            $delivery_history->save();

            if (env('MAIL_USERNAME') != null && get_setting('delivery_boy_mail_notification') == '1') {
                $array['view'] = 'emails.invoice';
                $array['subject'] = translate('You are assigned to delivery an order. Order code') . ' - ' . $order->code;
                $array['from'] = env('MAIL_FROM_ADDRESS');
                $array['order'] = $order;

                try {
                    Mail::to($order->delivery_boy->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {
                }
            }

            if (addon_is_activated('otp_system') && SmsTemplate::where('identifier', 'assign_delivery_boy')->first()->status == 1) {
                try {
                    SmsUtility::assign_delivery_boy($order->delivery_boy->phone, $order->code);
                } catch (\Exception $e) {
                }
            }
        }

        return 1;
    }

    public function orderBulkExport(Request $request)
    {
        if($request->id){
          return Excel::download(new OrdersExport($request->id), 'orders.xlsx');
        }
        return back();
    }

    public function unpaid_order_payment_notification_send(Request $request){
        if($request->order_ids != null){
            $notificationType = get_notification_type('complete_unpaid_order_payment', 'type');
            foreach (explode(",",$request->order_ids) as $order_id) {
                $order = Order::where('id', $order_id)->first();
                $user = $order->user;
                if($notificationType->status == 1 && $order->payment_status == 'unpaid'){
                    $order_notification['order_id']     = $order->id;
                    $order_notification['order_code']   = $order->code;
                    $order_notification['user_id']      = $order->user_id;
                    $order_notification['seller_id']    = $order->seller_id;
                    $order_notification['status']       = $order->payment_status;
                    $order_notification['notification_type_id'] = $notificationType->id;
                    Notification::send($user, new OrderNotification($order_notification));
                }
            }
            flash(translate('Notification Sent Successfully.'))->success();
        }
        else{
            flash(translate('Something went wrong!.'))->warning();
        }
        return back();
    }
}
