<style>
    /* Mobile Sticky Proceed to Checkout Button */
    @media (max-width: 767px) {
        .cart-sticky-checkout {
            position: fixed !important;
            bottom: 60px !important; /* Position above mobile nav (aiz-mobile-bottom-nav is ~60px) */
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            background: linear-gradient(180deg, rgba(255,255,255,0.98) 0%, rgba(255,255,255,1) 100%) !important;
            box-shadow: 0 -6px 30px rgba(0, 0, 0, 0.25) !important;
            z-index: 999 !important; /* Lower than mobile nav (which typically has higher z-index) */
            padding: 16px !important;
            border-top: 3px solid #007bff !important;
            animation: pulse-glow 2s infinite ease-in-out !important;
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 -6px 30px rgba(0, 0, 0, 0.25), 0 0 20px rgba(0, 123, 255, 0.3) !important;
            }
            50% {
                box-shadow: 0 -8px 35px rgba(0, 0, 0, 0.3), 0 0 30px rgba(0, 123, 255, 0.5) !important;
            }
        }
        
        .cart-sticky-checkout .btn {
            width: 100% !important;
            font-size: 15px !important;
            font-weight: 800 !important;
            border-radius: 12px !important;
            padding: 18px 20px !important;
            min-height: 56px !important;
            box-shadow: 0 6px 20px rgba(0,123,255,0.4) !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            position: relative !important;
            overflow: hidden !important;
            transition: all 0.3s ease !important;
        }
        
        .cart-sticky-checkout .btn::before {
            content: '' !important;
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            width: 0 !important;
            height: 0 !important;
            border-radius: 50% !important;
            background: rgba(255,255,255,0.3) !important;
            transform: translate(-50%, -50%) !important;
            transition: width 0.6s, height 0.6s !important;
        }
        
        .cart-sticky-checkout .btn:active::before {
            width: 300px !important;
            height: 300px !important;
        }
        
        .cart-sticky-checkout .btn i {
            font-size: 20px !important;
        }
        
        .cart-sticky-checkout .btn .badge {
            font-size: 14px !important;
            padding: 4px 10px !important;
            font-weight: 700 !important;
        }
        
        /* Add bottom padding to prevent content from being hidden behind sticky button and mobile nav */
        .cart-page-content {
            padding-bottom: 140px !important; /* Button height + mobile nav height + spacing */
        }
    }
    
    @media (min-width: 768px) {
        .cart-sticky-checkout {
            display: none !important;
        }
    }
</style>

<div class="z-3 sticky-top-lg">
    <div class="card border-0 shadow-sm" style="background: #ffffff; border-radius: 12px; border: 1px solid #e9ecef !important; overflow: hidden;">

        @php
            $subtotal_for_min_order_amount = 0;
            $subtotal = 0;
            $tax = 0;
            $product_shipping_cost = 0;
            $shipping = 0;
            $coupon_code = null;
            $coupon_discount = 0;
            $total_point = 0;
        @endphp
        @foreach ($carts as $key => $cartItem)
            @php
                $product = get_single_product($cartItem['product_id']);
                
                // Check if this is a group product (has group_product_id)
                $isGroupProduct = false;
                if (isset($cartItem->group_product_id) && $cartItem->group_product_id !== null && $cartItem->group_product_id !== '') {
                    $isGroupProduct = true;
                } elseif (isset($cartItem['group_product_id']) && $cartItem['group_product_id'] !== null && $cartItem['group_product_id'] !== '') {
                    $isGroupProduct = true;
                }
                
                // For group products, use the stored price (already includes slot discounts)
                // For regular products, calculate the price
                if ($isGroupProduct) {
                    $itemPrice = isset($cartItem->price) ? $cartItem->price : (isset($cartItem['price']) ? $cartItem['price'] : 0);
                    $subtotal_for_min_order_amount += $itemPrice * (isset($cartItem->quantity) ? $cartItem->quantity : (isset($cartItem['quantity']) ? $cartItem['quantity'] : 0));
                    $subtotal += $itemPrice * (isset($cartItem->quantity) ? $cartItem->quantity : (isset($cartItem['quantity']) ? $cartItem['quantity'] : 0));
                } else {
                    $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product ?? $product, false, false) * (isset($cartItem->quantity) ? $cartItem->quantity : (isset($cartItem['quantity']) ? $cartItem['quantity'] : 0));
                    $subtotal += cart_product_price($cartItem, $product, false, false) * (isset($cartItem->quantity) ? $cartItem->quantity : (isset($cartItem['quantity']) ? $cartItem['quantity'] : 0));
                }
                
                $tax += cart_product_tax($cartItem, $product, false) * (isset($cartItem->quantity) ? $cartItem->quantity : (isset($cartItem['quantity']) ? $cartItem['quantity'] : 0));
                $product_shipping_cost = isset($cartItem->shipping_cost) ? $cartItem->shipping_cost : (isset($cartItem['shipping_cost']) ? $cartItem['shipping_cost'] : 0);
                $shipping += $product_shipping_cost;
                if ((get_setting('coupon_system') == 1) && (isset($cartItem->coupon_applied) ? $cartItem->coupon_applied : (isset($cartItem['coupon_applied']) ? $cartItem['coupon_applied'] : 0)) == 1) {
                    $coupon_code = isset($cartItem->coupon_code) ? $cartItem->coupon_code : (isset($cartItem['coupon_code']) ? $cartItem['coupon_code'] : null);
                    $coupon_discount = $carts->sum('discount');
                }
                if (addon_is_activated('club_point')) {
                    $total_point += $product->earn_point * (isset($cartItem->quantity) ? $cartItem->quantity : (isset($cartItem['quantity']) ? $cartItem['quantity'] : 0));
                }
            @endphp
        @endforeach

        <div class="card-header pt-2 pt-md-4 pb-2 pb-md-3 px-2 px-md-4" style="background: #000000; border-bottom: 2px solid #333 !important;">
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="fs-14 fs-md-18 fw-700 mb-0 text-white">
                    <i class="las la-receipt text-white mr-1 mr-md-2"></i>{{ translate('Order Summary') }}
                </h3>
                <!-- Minimum Order Amount -->
                @if (get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
                    <span class="badge badge-warning fs-10 fs-md-11 px-2 px-md-3 py-1 py-md-2" style="border-radius: 20px; background: #ffc107; color: #212529;">
                        <i class="las la-exclamation-triangle mr-1"></i><span class="d-none d-sm-inline">{{ translate('Min') }}: </span>{{ single_price(get_setting('minimum_order_amount')) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body pt-2 pt-md-4 px-2 px-md-4 pb-2 pb-md-4">

            <!-- Summary Cards -->
            <div class="row gutters-5 mb-2 mb-md-4">
                <!-- Total Products -->
                <div class="@if (addon_is_activated('club_point')) col-6 @else col-12 @endif">
                    <div class="d-flex align-items-center justify-content-between p-2 p-md-3 rounded-lg border bg-primary" 
                        style="border-color: #0056b3 !important; box-shadow: 0 3px 8px rgba(0,123,255,0.25);">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center mr-2 mr-md-3" style="width: 32px; height: 32px;">
                                <i class="las la-shopping-cart text-white fs-14 fs-md-18"></i>
                            </div>
                            <div>
                                <div class="fs-10 fs-md-11 text-white opacity-90">{{ translate('Total Products') }}</div>
                                <div class="fs-14 fs-md-18 fw-700 text-white">{{ sprintf("%02d", count($carts)) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (addon_is_activated('club_point'))
                    <!-- Total Clubpoint -->
                    <div class="col-6">
                        <div class="d-flex align-items-center justify-content-between p-2 p-md-3 rounded-lg border" 
                            style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); border-color: #495057 !important; box-shadow: 0 3px 8px rgba(108,117,125,0.25);">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center mr-2 mr-md-3" style="width: 32px; height: 32px;">
                                    <i class="las la-coins text-white fs-14 fs-md-18"></i>
                                </div>
                                <div>
                                    <div class="fs-10 fs-md-11 text-white opacity-90">{{ translate('Total Clubpoint') }}</div>
                                    <div class="fs-14 fs-md-18 fw-700 text-white">{{ sprintf("%02d", $total_point) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <input type="hidden" id="sub_total" value="{{ $subtotal }}">

            <!-- Price Breakdown -->
            <div class="bg-white rounded-lg border mb-2 mb-md-4" style="border-color: #e9ecef !important; overflow: hidden;">
                <!-- Subtotal -->
                <div class="d-flex justify-content-between align-items-center px-2 px-md-4 py-2 py-md-3 border-bottom" style="border-color: #e9ecef !important;">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mr-2 mr-md-3" style="width: 28px; height: 28px;">
                            <i class="las la-list-ul text-primary fs-12 fs-md-14"></i>
                        </div>
                        <div>
                            <span class="fs-12 fs-md-14 fw-600 text-dark d-block">{{ translate('Subtotal') }}</span>
                            <span class="fs-10 fs-md-11 text-muted d-none d-sm-block">{{ sprintf("%02d", count($carts)) }} {{ translate('Products') }}</span>
                        </div>
                    </div>
                    <span class="fs-14 fs-md-16 fw-700 text-dark">{{ single_price($subtotal) }}</span>
                </div>
                <!-- Tax -->
                <div class="d-flex justify-content-between align-items-center px-2 px-md-4 py-2 py-md-3 border-bottom" style="border-color: #e9ecef !important;">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mr-2 mr-md-3" style="width: 28px; height: 28px;">
                            <i class="las la-percent text-info fs-12 fs-md-14"></i>
                        </div>
                        <span class="fs-12 fs-md-14 fw-600 text-dark">{{ translate('Tax') }}</span>
                    </div>
                    <span class="fs-13 fs-md-15 fw-600 text-dark">{{ single_price($tax) }}</span>
                </div>
                @if ($proceed != 1)
                <!-- Total Shipping -->
                <div class="d-flex justify-content-between align-items-center px-2 px-md-4 py-2 py-md-3 border-bottom" style="border-color: #e9ecef !important;">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mr-2 mr-md-3" style="width: 28px; height: 28px;">
                            <i class="las la-shipping-fast text-warning fs-12 fs-md-14"></i>
                        </div>
                        <span class="fs-12 fs-md-14 fw-600 text-dark">{{ translate('Total Shipping') }}</span>
                    </div>
                    <span class="fs-13 fs-md-15 fw-600 text-dark">{{ single_price($shipping) }}</span>
                </div>
                @endif
                <!-- Redeem point -->
                @if (Session::has('club_point'))
                    <div class="d-flex justify-content-between align-items-center px-2 px-md-4 py-2 py-md-3 border-bottom" style="border-color: #e9ecef !important;">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mr-2 mr-md-3" style="width: 28px; height: 28px;">
                                <i class="las la-gift text-success fs-12 fs-md-14"></i>
                            </div>
                            <span class="fs-12 fs-md-14 fw-600 text-dark">{{ translate('Redeem point') }}</span>
                        </div>
                        <span class="fs-13 fs-md-15 fw-700 text-success">-{{ single_price(Session::get('club_point')) }}</span>
                    </div>
                @endif
                <!-- Coupon Discount -->
                @if ($coupon_discount > 0)
                    <div class="d-flex justify-content-between align-items-center px-2 px-md-4 py-2 py-md-3 border-bottom" style="border-color: #e9ecef !important;">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mr-2 mr-md-3" style="width: 28px; height: 28px;">
                                <i class="las la-tag text-danger fs-12 fs-md-14"></i>
                            </div>
                            <span class="fs-12 fs-md-14 fw-600 text-dark">{{ translate('Coupon Discount') }}</span>
                        </div>
                        <span class="fs-13 fs-md-15 fw-700 text-danger">-{{ single_price($coupon_discount) }}</span>
                    </div>
                @endif

                @php
                    $total = $subtotal + $tax + $shipping;
                    if (Session::has('club_point')) {
                        $total -= Session::get('club_point');
                    }
                    if ($coupon_discount > 0) {
                        $total -= $coupon_discount;
                    }
                @endphp
                <!-- Total -->
                <div class="d-flex justify-content-between align-items-center px-2 px-md-4 py-2 py-md-4" 
                    style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-top: 2px solid #007bff;">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2 mr-md-3" style="width: 32px; height: 32px;">
                            <i class="las la-calculator text-white fs-14 fs-md-16"></i>
                        </div>
                        <span class="fs-14 fs-md-16 fw-700 text-dark text-uppercase">{{ translate('Total') }}</span>
                    </div>
                    <span class="fs-18 fs-md-22 fw-700 text-primary">{{ single_price($total) }}</span>
                </div>
            </div>

            <!-- Coupon System -->
            @if (get_setting('coupon_system') == 1)
                @if ($coupon_discount > 0 && $coupon_code)
                    <div class="mb-2 mb-md-4 p-2 p-md-3 rounded-lg border" 
                        style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-color: #20c997 !important; box-shadow: 0 3px 8px rgba(40,167,69,0.25);">
                        <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="proceed" value="{{ $proceed }}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center mr-2 mr-md-3" style="width: 32px; height: 32px;">
                                        <i class="las la-check-circle text-white fs-14 fs-md-18"></i>
                                    </div>
                                    <div>
                                        <div class="text-white fw-700 fs-12 fs-md-14 mb-1">{{ $coupon_code }}</div>
                                        <div class="text-white fs-10 fs-md-11 opacity-90">{{ translate('Coupon Applied') }}</div>
                                    </div>
                                </div>
                                <button type="button" id="coupon-remove"
                                    class="btn btn-light btn-sm rounded-pill px-2 px-md-3 border-0" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <i class="las la-times mr-1"></i><span class="d-none d-sm-inline">{{ translate('Change') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="mb-2 mb-md-4">
                        <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="proceed" value="{{ $proceed }}">
                            <label class="fs-12 fs-md-13 fw-600 text-dark mb-1 mb-md-2 d-block">
                                <i class="las la-tag text-primary mr-1"></i>{{ translate('Coupon Code') }}
                            </label>
                            <div class="input-group rounded-lg border" style="border-color: #dee2e6 !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <input type="text" class="form-control border-0" name="code"
                                    onkeydown="return event.key != 'Enter';"
                                    placeholder="{{ translate('Enter coupon code') }}" 
                                    required
                                    style="padding: 8px 12px; font-size: 13px;">
                                <div class="input-group-append">
                                    <button type="button" id="coupon-apply"
                                        class="btn btn-primary border-0 px-3 px-md-4" style="border-radius: 0 8px 8px 0 !important; font-weight: 600;">
                                        <i class="las la-check mr-1"></i><span class="d-none d-sm-inline">{{ translate('Apply') }}</span>
                                    </button>
                                </div>
                            </div>
                            @if (!auth()->check())
                                <small class="text-muted d-block mt-1 mt-md-2 fs-11 fs-md-12">
                                    <i class="las la-info-circle mr-1"></i>{{ translate('You must Login as customer to apply coupon') }}
                                </small>
                            @endif
                        </form>
                    </div>
                @endif
            @endif

            @if ($proceed == 1)
            <!-- Continue to Checkout (Desktop) -->
            <div class="mt-2 mt-md-4 d-none d-md-block">
                <a href="{{ route('checkout.single_page_checkout') }}" 
                    class="btn btn-primary btn-block fs-14 fs-md-16 fw-700 rounded-lg px-3 px-md-4 py-2 py-md-3 border-0 bg-primary" 
                    style="box-shadow: 0 4px 12px rgba(0,123,255,0.35); transition: all 0.3s;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0,123,255,0.45)'" 
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,123,255,0.35)'">
                    <i class="las la-arrow-right mr-1 mr-md-2"></i>{{ translate('Proceed to Checkout')}} 
                    <span class="badge badge-light text-primary ml-1 ml-md-2 px-1 px-md-2 py-1" style="background: rgba(255,255,255,0.9) !important;">{{ sprintf("%02d", count($carts)) }}</span>
                </a>
            </div>
            @endif

        </div>
    </div>
</div>

@if ($proceed == 1)
<!-- Mobile Sticky Proceed to Checkout Button -->
<div class="cart-sticky-checkout d-block d-md-none">
    <a href="{{ route('checkout.single_page_checkout') }}" 
        class="btn btn-primary btn-block fw-700 border-0" 
        style="background-color: {{ get_setting('base_color', '#d43533') }} !important; color: #ffffff !important;">
        <i class="las la-arrow-right mr-1"></i>{{ translate('Proceed to Checkout')}} 
        <span class="badge badge-light ml-2 px-2 py-1" style="background: rgba(255,255,255,0.9) !important; color: {{ get_setting('base_color', '#d43533') }} !important;">{{ sprintf("%02d", count($carts)) }}</span>
    </a>
</div>
@endif
