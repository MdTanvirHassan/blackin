<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Carrier;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Country;
use Auth;
use App\Utility\CartUtility;
use Session;
use Cookie;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    /**
     * Group carts by group_product_id and slot_combination_hash (if available)
     * Bundles with same group_product_id but different slot combinations are separate
     */
    protected function groupCartsBySlotCombination($carts)
    {
        $groupedCarts = [];
        $ungroupedCarts = [];
        
        $hasCombinationHash = Schema::hasColumn('carts', 'group_product_slot_combination_hash');
        
        foreach ($carts as $cart) {
            if ($cart->group_product_id) {
                // Use combination hash if available, otherwise just group_product_id
                if ($hasCombinationHash && $cart->group_product_slot_combination_hash) {
                    $groupKey = $cart->group_product_id . '_' . $cart->group_product_slot_combination_hash;
                } else {
                    $groupKey = $cart->group_product_id;
                }
                
                if (!isset($groupedCarts[$groupKey])) {
                    $groupedCarts[$groupKey] = [
                        'groupProduct' => $cart->groupProduct,
                        'items' => [],
                        'slotCombinationHash' => $hasCombinationHash ? $cart->group_product_slot_combination_hash : null
                    ];
                }
                $groupedCarts[$groupKey]['items'][] = $cart;
            } else {
                $ungroupedCarts[] = $cart;
            }
        }
        
        return ['groupedCarts' => $groupedCarts, 'ungroupedCarts' => $ungroupedCarts];
    }

    public function index(Request $request)
    {
        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            if ($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                    ->update(
                        [
                            'user_id' => $user_id,
                            'temp_user_id' => null
                        ]
                    );

                Session::forget('temp_user_id');
            }
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->get() : [];
        }
        if (count($carts) > 0) {
            $carts->toQuery()->update(['shipping_cost' => 0]);
            $carts = $carts->fresh()->load(['groupProduct', 'groupProductSlot']);
        }

        // Group carts by group_product_id and slot_combination_hash (if available)
        // Bundles with same group_product_id but different slot combinations are separate
        $grouped = $this->groupCartsBySlotCombination($carts);
        $groupedCarts = $grouped['groupedCarts'];
        $ungroupedCarts = $grouped['ungroupedCarts'];

        return view('frontend.view_cart', compact('carts', 'groupedCarts', 'ungroupedCarts'));
    }

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        
        // If no product found, return empty response or error
        if (!$product) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Product not found'
                ], 404);
            }
            return response('Product not found', 404);
        }
        
        return view('frontend.partials.cart.addToCart', compact('product'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    public function addToCart(Request $request)
    {
        $authUser = auth()->user();
        if($authUser != null) {
            $user_id = $authUser->id;
            $data['user_id'] = $user_id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            } else {
                $temp_user_id = bin2hex(random_bytes(10));
                $request->session()->put('temp_user_id', $temp_user_id);
            }
            $data['temp_user_id'] = $temp_user_id;
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);
        $product = Product::find($request->id);
        $carts = array();

        if($check_auction_in_cart && $product->auction_product == 0) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.cart.removeAuctionProductFromCart')->render(),
                'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
            );
        }

        $quantity = $request['quantity'];

        if ($quantity < $product->min_qty) {
            return array(
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.partials.minQtyNotSatisfied', ['min_qty' => $product->min_qty])->render(),
                'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
            );
        }

        //check the color enabled or disabled for the product
        $str = CartUtility::create_cart_variant($product, $request->all());
        $product_stock = $product->stocks->where('variant', $str)->first();

        if($authUser != null) {
            $user_id = $authUser->id;
            $cart = Cart::firstOrNew([
                'variation' => $str,
                'user_id' => $user_id,
                'product_id' => $request['id'],
                'group_product_id' => null, // Regular products have null group_product_id
                'group_product_slot_id' => null // Regular products have null group_product_slot_id
            ]);
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $cart = Cart::firstOrNew([
                'variation' => $str,
                'temp_user_id' => $temp_user_id,
                'product_id' => $request['id'],
                'group_product_id' => null, // Regular products have null group_product_id
                'group_product_slot_id' => null // Regular products have null group_product_slot_id
            ]);
        }

        if ($cart->exists && $product->digital == 0) {
            if ($product->auction_product == 1 && ($cart->product_id == $product->id)) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.partials.cart.auctionProductAlredayAddedCart')->render(),
                    'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
                );
            }
            if ($product_stock->qty < $cart->quantity + $request['quantity']) {
                return array(
                    'status' => 0,
                    'cart_count' => count($carts),
                    'modal_view' => view('frontend.partials.outOfStockCart')->render(),
                    'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
                );
            }
            $quantity = $cart->quantity + $request['quantity'];
        }

        $price = CartUtility::get_price($product, $product_stock, $request->quantity);
        $tax = CartUtility::tax_calculation($product, $price);

        CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);
        
        // Ensure regular products have null group_product_id and group_product_slot_id
        if ($cart->group_product_id !== null) {
            $cart->group_product_id = null;
        }
        if ($cart->group_product_slot_id !== null) {
            $cart->group_product_slot_id = null;
        }
        $cart->save();

        // Refresh cart data to ensure we have the latest
        if($authUser != null) {
            $user_id = $authUser->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        // Get fresh cart count
        $cartCount = count($carts);

        return array(
            'status' => 1,
            'cart_count' => $cartCount,
            'modal_view' => view('frontend.partials.cart.addedToCart', compact('product', 'cart'))->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        $cartItem = Cart::find($request->id);
        
        // If this is a group product item, remove the entire bundle
        // But only items with the same slot combination hash (if available)
        if ($cartItem && $cartItem->group_product_id) {
            $groupProductId = $cartItem->group_product_id;
            $hasCombinationHash = Schema::hasColumn('carts', 'group_product_slot_combination_hash');
            $authUser = auth()->user();
            
            $query = $authUser != null 
                ? Cart::where('user_id', $authUser->id)
                : Cart::where('temp_user_id', $request->session()->get('temp_user_id'));
            
            $query->where('group_product_id', $groupProductId);
            
            // If hash column exists and cart item has a hash, only remove items with same hash
            if ($hasCombinationHash && $cartItem->group_product_slot_combination_hash) {
                $query->where('group_product_slot_combination_hash', $cartItem->group_product_slot_combination_hash);
            }
            
            $query->delete();
        } else {
            // Regular product - remove single item
            Cart::destroy($request->id);
        }
        
        $authUser = auth()->user();
        if ($authUser != null) {
            $user_id = $authUser->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        
        // Load relationships and group carts
        if (count($carts) > 0) {
            $carts = $carts->load(['groupProduct', 'groupProductSlot']);
        }
        
        // Group carts for cart_details view
        $grouped = $this->groupCartsBySlotCombination($carts);
        $groupedCarts = $grouped['groupedCarts'];
        $ungroupedCarts = $grouped['ungroupedCarts'];

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart.cart_details', compact('carts', 'groupedCarts', 'ungroupedCarts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }

    //removes group product (all items with same group_product_id and slot combination hash) from Cart
    public function removeGroupProductFromCart(Request $request)
    {
        // Accept either composite key (group_product_id_hash) or separate parameters
        $groupKey = $request->group_key ?? null;
        $groupProductId = $request->group_product_id ?? null;
        $slotCombinationHash = $request->slot_combination_hash ?? null;
        
        // Parse composite key if provided (format: group_product_id_hash)
        if ($groupKey && strpos($groupKey, '_') !== false) {
            $parts = explode('_', $groupKey, 2);
            $groupProductId = $parts[0];
            $slotCombinationHash = $parts[1];
        }
        
        // If no group_product_id, use group_key as fallback
        if (!$groupProductId && $request->group_key) {
            $groupProductId = $request->group_key;
        }
        
        if (!$groupProductId) {
            return array(
                'cart_count' => 0,
                'cart_view' => '',
                'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
            );
        }
        
        $hasCombinationHash = Schema::hasColumn('carts', 'group_product_slot_combination_hash');
        $authUser = auth()->user();
        
        $query = $authUser != null 
            ? Cart::where('user_id', $authUser->id)
            : Cart::where('temp_user_id', $request->session()->get('temp_user_id'));
        
        $query = $query->where('group_product_id', $groupProductId);
        
        // If hash column exists and we have a hash, only remove items with same hash
        if ($hasCombinationHash && $slotCombinationHash) {
            $query = $query->where('group_product_slot_combination_hash', $slotCombinationHash);
        }
        
        $query->delete();

        // Get updated carts
        if ($authUser != null) {
            $user_id = $authUser->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        
        // Load relationships and group carts
        if (count($carts) > 0) {
            $carts = $carts->load(['groupProduct', 'groupProductSlot']);
        }
        
        // Group carts for cart_details view
        $grouped = $this->groupCartsBySlotCombination($carts);
        $groupedCarts = $grouped['groupedCarts'];
        $ungroupedCarts = $grouped['ungroupedCarts'];

        return array(
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart.cart_details', compact('carts', 'groupedCarts', 'ungroupedCarts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);
        
        // Check if this is a group product - if so, update all items in the bundle
        if ($cartItem->group_product_id) {
            return $this->updateBundleQuantity($request);
        }

        // Regular product update
        if ($cartItem['id'] == $request->id) {
            $product = Product::find($cartItem['product_id']);
            $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
            $quantity = $product_stock->qty;
            $price = $product_stock->price;

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $price -= ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $price -= $product->discount;
                }
            }

            if ($quantity >= $request->quantity) {
                if ($request->quantity >= $product->min_qty) {
                    $cartItem['quantity'] = $request->quantity;
                }
            }

            if ($product->wholesale_product) {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $request->quantity)->where('max_qty', '>=', $request->quantity)->first();
                if ($wholesalePrice) {
                    $price = $wholesalePrice->price;
                }
            }

            $cartItem['price'] = $price;
            $cartItem->save();
        }

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        
        // Load relationships and group carts
        if (count($carts) > 0) {
            $carts = $carts->load(['groupProduct', 'groupProductSlot']);
        }
        
        // Group carts for cart_details view
        $grouped = $this->groupCartsBySlotCombination($carts);
        $groupedCarts = $grouped['groupedCarts'];
        $ungroupedCarts = $grouped['ungroupedCarts'];

        return array(
            'status' => 1,
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart.cart_details', compact('carts', 'groupedCarts', 'ungroupedCarts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }
    
    // Update quantity for all items in a bundle (group product)
    private function updateBundleQuantity(Request $request)
    {
        $cartItem = Cart::findOrFail($request->id);
        $groupProductId = $cartItem->group_product_id;
        $newQuantity = (int)$request->quantity;
        
        if ($newQuantity < 1) {
            $newQuantity = 1;
        }
        
        $authUser = auth()->user();
        $hasCombinationHash = Schema::hasColumn('carts', 'group_product_slot_combination_hash');
        
        $query = $authUser != null
            ? Cart::where('user_id', $authUser->id)
            : Cart::where('temp_user_id', $request->session()->get('temp_user_id'));
        
        $query->where('group_product_id', $groupProductId);
        
        // Priority: Use slot_combination_hash from request if provided, otherwise use from cart item
        $slotCombinationHash = null;
        if ($request->has('slot_combination_hash') && !empty($request->slot_combination_hash)) {
            $slotCombinationHash = $request->slot_combination_hash;
        } elseif ($hasCombinationHash && $cartItem->group_product_slot_combination_hash) {
            $slotCombinationHash = $cartItem->group_product_slot_combination_hash;
        }
        
        // If hash column exists and we have a hash, only update items with same hash
        // This ensures we only update the specific bundle configuration, not all bundles with same group_product_id
        if ($hasCombinationHash && $slotCombinationHash) {
            $query->where('group_product_slot_combination_hash', $slotCombinationHash);
        }
        
        $bundleItems = $query->get();
        
        // Check stock availability for all items in the bundle
        foreach ($bundleItems as $item) {
            $product = Product::find($item->product_id);
            if ($product && $product->digital == 0) {
                $product_stock = $product->stocks->where('variant', $item->variation)->first();
                if ($product_stock && $product_stock->qty < $newQuantity) {
                    // Return error response
                    $carts = get_user_cart();
                    if (count($carts) > 0) {
                        $carts = $carts->load(['groupProduct', 'groupProductSlot']);
                    }
                    $grouped = $this->groupCartsBySlotCombination($carts);
                    $groupedCarts = $grouped['groupedCarts'];
                    $ungroupedCarts = $grouped['ungroupedCarts'];
                    return array(
                        'status' => 0,
                        'message' => translate('Insufficient stock for one or more items in the bundle'),
                        'cart_count' => count($carts),
                        'cart_view' => view('frontend.partials.cart.cart_details', compact('carts', 'groupedCarts', 'ungroupedCarts'))->render(),
                        'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
                    );
                }
            }
        }
        
        // Update quantity for all items in the bundle
        foreach ($bundleItems as $item) {
            $item->quantity = $newQuantity;
            $item->save();
        }
        
        // Get updated carts
        if ($authUser != null) {
            $user_id = $authUser->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }
        
        // Load relationships and group carts
        if (count($carts) > 0) {
            $carts = $carts->load(['groupProduct', 'groupProductSlot']);
        }
        
        // Group carts for cart_details view
        $grouped = $this->groupCartsBySlotCombination($carts);
        $groupedCarts = $grouped['groupedCarts'];
        $ungroupedCarts = $grouped['ungroupedCarts'];
        
        return array(
            'status' => 1,
            'cart_count' => count($carts),
            'cart_view' => view('frontend.partials.cart.cart_details', compact('carts', 'groupedCarts', 'ungroupedCarts'))->render(),
            'nav_cart_view' => view('frontend.partials.cart.cart')->render(),
        );
    }

    public function updateCartStatus(Request $request)
    {
        $product_ids = $request->product_id;

        if (auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->get();
        }

        $coupon_applied = $carts->toQuery()->where('coupon_applied', 1)->first();
        if($coupon_applied != null){
            $owner_id = $coupon_applied->owner_id;
            $coupon_code = $coupon_applied->coupon_code;
            $user_carts = $carts->toQuery()->where('owner_id', $owner_id)->get();
            $coupon_discount = $user_carts->toQuery()->sum('discount');
            $user_carts->toQuery()->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );
        }

        $carts->toQuery()->update(['status' => 0]);
        if($product_ids != null){
            if($coupon_applied != null){
                $active_user_carts = $user_carts->toQuery()->whereIn('product_id', $product_ids)->get();
                if (count($active_user_carts) > 0) {
                    $active_user_carts->toQuery()->update(
                        [
                            'discount' => $coupon_discount / count($active_user_carts),
                            'coupon_code' => $coupon_code,
                            'coupon_applied' => 1
                        ]
                    );
                }
            }

            $carts->toQuery()->whereIn('product_id', $product_ids)->update(['status' => 1]);
        }
        $carts = $carts->fresh();
        
        // Load relationships and group carts
        if (count($carts) > 0) {
            $carts = $carts->load(['groupProduct', 'groupProductSlot']);
        }
        
        // Group carts for cart_details view
        $grouped = $this->groupCartsBySlotCombination($carts);
        $groupedCarts = $grouped['groupedCarts'];
        $ungroupedCarts = $grouped['ungroupedCarts'];

        return view('frontend.partials.cart.cart_details', compact('carts', 'groupedCarts', 'ungroupedCarts'))->render();
    }
}
