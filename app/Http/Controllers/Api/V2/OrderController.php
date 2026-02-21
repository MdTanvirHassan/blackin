<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\BusinessSetting;
use App\Models\User;
use DB;
use \App\Utility\NotificationUtility;
use App\Models\CombinedOrder;
use App\Http\Controllers\AffiliateController;

class OrderController extends Controller
{
    public function store(Request $request, $set_paid = false)
    {
        if (get_setting('minimum_order_amount_check') == 1) {
            $subtotal = 0;
            foreach (Cart::where('user_id', auth()->user()->id)->active()->get() as $key => $cartItem) {
                $product = Product::find($cartItem['product_id']);
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
            }
            if ($subtotal < get_setting('minimum_order_amount')) {
                return $this->failed(translate("You order amount is less then the minimum order amount"));
            }
        }

        $cartItems = Cart::where('user_id', auth()->user()->id)->active()->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'combined_order_id' => 0,
                'result' => false,
                'message' => translate('Cart is Empty')
            ]);
        }

        $user = User::find(auth()->user()->id);

        $address = Address::where('id', $cartItems->first()->address_id)->first();
        $shippingAddress = [];
        if ($address != null) {
            $shippingAddress['name']        = $user->name;
            $shippingAddress['email']       = $user->email;
            $shippingAddress['address']     = $address->address. (isset($address->area) ? ', ' . $address->area->name : '');
            $shippingAddress['country']     = $address->country->name;
            $shippingAddress['state']       = $address->state->name;
            $shippingAddress['city']        = $address->city->name;
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone']       = $address->phone;
            if ($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
            }
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = $user->id;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();

        // Generate a single order code for all orders in this combined order
        $shared_order_code = date('Ymd-His') . rand(10, 99);

        // Group all cart items by seller_id (regardless of whether they're group products or normal products)
        // This ensures all products from the same seller go into ONE order
        $seller_products = array();
        foreach ($cartItems as $cartItem) {
            // Handle both array and object access for cart items
            $product_id = isset($cartItem['product_id']) ? $cartItem['product_id'] : (isset($cartItem->product_id) ? $cartItem->product_id : null);
            
            if (!$product_id) {
                continue; // Skip if product_id is not found
            }
            
            $product = Product::find($product_id);
            if (!$product) {
                continue; // Skip if product not found
            }
            
            // Use product's seller_id to group items
            // All products (group product items and normal products) from the same seller will be grouped together
            $seller_id = $product->user_id;
            
            if (!isset($seller_products[$seller_id])) {
                $seller_products[$seller_id] = [];
            }
            
            // Convert cart item to array if it's an object for consistency
            $cartItemArray = is_array($cartItem) ? $cartItem : (array) $cartItem;
            if (is_object($cartItem)) {
                // Preserve object properties when converting
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
            
            // Get seller_id from the key (all products in this array are from the same seller)
            $order = new Order;
            $order->combined_order_id = $combined_order->id;
            $order->user_id = $user->id;
            $order->seller_id = $seller_id; // Set seller_id upfront
            $order->shipping_address = $combined_order->shipping_address;

            $order->order_from = 'app';
            $order->payment_type = $request->payment_type;
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = $shared_order_code; // Use same code for all orders in combined order
            $order->date = strtotime('now');
            if ($set_paid) {
                $order->payment_status = 'paid';
            } else {
                $order->payment_status = 'unpaid';
            }
            
            // Save order early to get ID for order details (we'll update totals later)
            $order->save();

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;
            $shipping_type = 'home_delivery';
            $pickup_point_id = null;
            $carrier_id = null;

            //Order Details Storing
            foreach ($seller_product as $cartItem) {
                $product = Product::find($cartItem['product_id']);
                if (!$product) continue;

                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $coupon_discount += $cartItem['discount'];

                $product_variation = $cartItem['variation'];

                $product_stock = $product->stocks->where('variant', $product_variation)->first();
                if ($product->digital != 1 && $cartItem['quantity'] > $product_stock->qty) {
                    return response()->json([
                        'combined_order_id' => 0,
                        'result' => false,
                        'message' => translate('The requested quantity is not available for ') . $product->name
                    ]);
                } elseif ($product->digital != 1) {
                    $product_stock->qty -= $cartItem['quantity'];
                    $product_stock->save();
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
                $order_detail->price = cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $order_detail->tax = cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];
                $order_detail->shipping_cost = $cartItem['shipping_cost'];

                // Store group product info if applicable
                $groupProductId = isset($cartItem['group_product_id']) ? $cartItem['group_product_id'] : (isset($cartItem->group_product_id) ? $cartItem->group_product_id : null);
                $isGroupProduct = $groupProductId != null;
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

                $shipping += $order_detail->shipping_cost;

                //End of storing shipping cost
                if (addon_is_activated('club_point')) {
                    $order_detail->earn_point = $product->earn_point;
                }

                $order_detail->quantity = $cartItem['quantity'];
                $order_detail->save();

                $product->num_of_sale = $product->num_of_sale + $cartItem['quantity'];
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

            $order->grand_total = $subtotal + $tax + $shipping;

            if (isset($seller_product[0]) && isset($seller_product[0]['coupon_code']) && $seller_product[0]['coupon_code'] != null) {
                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;

                $coupon = Coupon::where('code', $seller_product[0]['coupon_code'])->first();
                if ($coupon) {
                    $coupon_usage = new CouponUsage;
                    $coupon_usage->user_id = $user->id;
                    $coupon_usage->coupon_id = $coupon->id;
                    $coupon_usage->save();
                }
            }

            if (strpos($request->payment_type, "manual_payment_") !== false) { // if payment type like  manual_payment_1 or  manual_payment_25 etc)
                $order->manual_payment = 1;
            }

            // Update the order once with all final details
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

        Cart::where('user_id', auth()->user()->id)->active()->delete();

        if (
            $request->payment_type == 'cash_on_delivery'
            || $request->payment_type == 'wallet'
            || strpos($request->payment_type, "manual_payment_") !== false // if payment type like  manual_payment_1 or  manual_payment_25 etc
        ) {
            NotificationUtility::sendOrderPlacedNotification($order);
        }

        return response()->json([
            'combined_order_id' => $combined_order->id,
            'result' => true,
            'message' => translate('Your order has been placed successfully')
        ]);
    }

    public function order_cancel($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($order && ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')) {
            $order->delivery_status = 'cancelled';
            $order->save();

            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->delivery_status = 'cancelled';
                $orderDetail->save();
                product_restock($orderDetail);
            }

            return $this->success(translate('Order has been canceled successfully'));
        } else {
            return  $this->failed(translate('Something went wrong'));
        }
    }
}
