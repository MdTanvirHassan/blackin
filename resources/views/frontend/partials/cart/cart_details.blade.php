<style>
    /* Mobile Responsive Styles for Cart Details Bundle Items */
    @media (max-width: 767px) {
        /* Reduce overall container padding */
        .container {
            padding-left: 10px !important;
            padding-right: 10px !important;
            padding-bottom: 140px !important;
        }
        
        /* Reduce card padding */
        .bg-white.p-3.p-lg-4 {
            padding: 0.75rem !important;
        }
        
        /* Reduce list group item padding and margins */
        .list-group-item {
            padding: 0.5rem !important;
            margin-bottom: 0.75rem !important;
            border-radius: 8px !important;
        }
        
        .row.gutters-5 {
            margin: 0 !important;
        }
        
        .row.gutters-5 > * {
            padding: 0.5rem !important;
        }
        
        /* Smaller bundle/product images */
        .img-fit.rounded-lg {
            width: 60px !important;
            height: 60px !important;
        }
        
        /* Smaller bundle badge icon */
        .badge.position-absolute {
            width: 22px !important;
            height: 22px !important;
            font-size: 10px !important;
            top: -3px !important;
            right: -3px !important;
        }
        
        /* Smaller product titles */
        h6.fs-16, h6.fs-15 {
            font-size: 13px !important;
            line-height: 1.3 !important;
            margin-bottom: 0.4rem !important;
        }
        
        /* Smaller badges */
        .badge {
            font-size: 9px !important;
            padding: 0.2rem 0.4rem !important;
            line-height: 1.2 !important;
        }
        
        .badge i {
            font-size: 9px !important;
        }
        
        /* Bundle item product name - allow wrapping on mobile */
        .cart-bundle-item h6.text-truncate,
        .cart-bundle-item h6 {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: clip !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            display: block !important;
            line-height: 1.3 !important;
            margin-bottom: 0.4rem !important;
            max-width: 100% !important;
            font-size: 11px !important;
        }
        
        /* Bundle item layout - stack vertically on mobile */
        .cart-bundle-item .d-flex.justify-content-between {
            flex-direction: column !important;
        }
        
        .cart-bundle-item .text-right {
            margin-top: 0.4rem !important;
            text-align: left !important;
            width: 100% !important;
        }
        
        /* Ensure bundle item container doesn't overflow */
        .cart-bundle-item {
            width: 100% !important;
            max-width: 100% !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
            padding: 0.5rem !important;
        }
        
        .cart-bundle-item .flex-grow-1 {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* Bundle item details section */
        .cart-bundle-item .pr-2,
        .cart-bundle-item .pr-0 {
            padding-right: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
        }
        
        /* Product name container */
        .cart-bundle-item h6 {
            max-width: 100% !important;
            box-sizing: border-box !important;
            word-break: break-word !important;
            font-size: 11px !important;
        }
        
        /* Badge container - ensure wrapping */
        .cart-bundle-item .d-flex.flex-wrap {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
            gap: 3px !important;
        }
        
        /* Badges - ensure they wrap and don't overflow */
        .cart-bundle-item .badge {
            max-width: 100% !important;
            box-sizing: border-box !important;
            word-break: break-word !important;
            white-space: normal !important;
            font-size: 8px !important;
            padding: 0.15rem 0.3rem !important;
        }
        
        /* Smaller bundle item images on mobile */
        .cart-bundle-item-img {
            width: 40px !important;
            height: 40px !important;
            flex-shrink: 0 !important;
        }
        
        /* Price section on mobile - single line display */
        .cart-bundle-item-price-section {
            width: 100% !important;
            text-align: left !important;
            margin-top: 0.4rem !important;
            box-sizing: border-box !important;
            font-size: 9px !important;
            line-height: 1.5 !important;
        }
        
        .cart-bundle-item-price-section > span,
        .cart-bundle-item-price-section > div {
            display: inline !important;
            margin: 0 !important;
            padding: 0 !important;
            margin-right: 0.4rem !important;
        }
        
        .cart-bundle-item-price-section .fs-11,
        .cart-bundle-item-price-section .fs-12 {
            display: inline !important;
            font-size: 9px !important;
            margin: 0 !important;
        }
        
        .cart-bundle-item-price-section .fs-12.fw-600 {
            display: inline !important;
            font-size: 9px !important;
            margin: 0 !important;
        }
        
        .cart-bundle-item-price-section .fs-10 {
            display: inline !important;
            font-size: 9px !important;
            margin: 0 !important;
        }
        
        .cart-bundle-item-price-section .text-muted {
            color: #6c757d !important;
        }
        
        .cart-bundle-item-price-section .text-primary {
            color: var(--primary) !important;
        }
        
        .cart-bundle-item-price-section .d-inline {
            display: inline !important;
        }
        
        /* Bundle contents container */
        .cart-bundle-contents-container {
            overflow: hidden !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* Bundle contents row */
        .cart-bundle-contents-container .row {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .cart-bundle-contents-container .col-12 {
            padding: 0 !important;
            margin-bottom: 0.4rem !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* Ensure parent bundle contents div doesn't overflow */
        .cart-bundle-contents-wrapper {
            overflow: hidden !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            padding: 0.75rem !important;
        }
        
        /* Bundle contents header */
        .cart-bundle-contents-wrapper h6 {
            font-size: 11px !important;
            margin-bottom: 0.5rem !important;
            margin-top: 0.5rem !important;
        }
        
        /* Bundle header mobile layout - price and quantity side by side */
        .cart-bundle-header-mobile {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            gap: 8px !important;
            margin-bottom: 0.5rem !important;
        }
        
        .cart-bundle-price-mobile {
            flex: 1 !important;
            min-width: 0 !important;
        }
        
        .cart-bundle-quantity-mobile {
            flex-shrink: 0 !important;
        }
        
        .cart-bundle-total-mobile {
            width: 100% !important;
            margin-top: 0.5rem !important;
            padding-top: 0.5rem !important;
            border-top: 1px solid #e9ecef !important;
        }
        
        /* Minimize font sizes on mobile */
        .cart-bundle-price-label {
            font-size: 8px !important;
        }
        
        .cart-bundle-price-value {
            font-size: 11px !important;
        }
        
        .cart-bundle-total-label {
            font-size: 8px !important;
        }
        
        .cart-bundle-total-value {
            font-size: 12px !important;
        }
        
        /* Smaller quantity controls on mobile */
        .cart-bundle-quantity-control {
            min-width: 24px !important;
            height: 26px !important;
        }
        
        .cart-bundle-quantity-control i {
            font-size: 9px !important;
        }
        
        .cart-bundle-quantity-input {
            width: 38px !important;
            height: 26px !important;
            font-size: 10px !important;
        }
        
        /* Desktop price section - hide on mobile */
        .col-lg-2.col-md-3.d-none.d-md-flex {
            display: none !important;
        }
        
        .col-lg-3.col-md-3.d-none.d-md-flex {
            display: none !important;
        }
        
        /* Non-group products mobile layout - price and quantity side by side */
        .cart-product-header-mobile {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            gap: 8px !important;
            margin-bottom: 0.5rem !important;
        }
        
        .cart-product-price-mobile {
            flex: 1 !important;
            min-width: 0 !important;
        }
        
        .cart-product-quantity-mobile {
            flex-shrink: 0 !important;
        }
        
        .cart-product-total-mobile {
            width: 100% !important;
            margin-top: 0.5rem !important;
            padding-top: 0.5rem !important;
            border-top: 1px solid #e9ecef !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }
        
        .cart-product-total-mobile-content {
            flex: 1 !important;
        }
        
        .cart-product-remove-mobile {
            flex-shrink: 0 !important;
            margin-left: 8px !important;
        }
        
        .cart-product-remove-mobile button {
            width: 30px !important;
            height: 30px !important;
        }
        
        .cart-product-remove-mobile button i {
            font-size: 12px !important;
        }
        
        /* Minimize font sizes for non-group products on mobile */
        .cart-product-price-label {
            font-size: 8px !important;
        }
        
        .cart-product-price-value {
            font-size: 11px !important;
        }
        
        .cart-product-tax-label {
            font-size: 7px !important;
        }
        
        .cart-product-total-label {
            font-size: 8px !important;
        }
        
        .cart-product-total-value {
            font-size: 12px !important;
        }
        
        /* Smaller quantity controls for non-group products on mobile */
        .cart-product-quantity-control {
            min-width: 24px !important;
            height: 26px !important;
        }
        
        .cart-product-quantity-control i {
            font-size: 9px !important;
        }
        
        .cart-product-quantity-input {
            width: 38px !important;
            height: 26px !important;
            font-size: 10px !important;
        }
        
        /* Remove bundle button */
        .cart-bundle-contents-wrapper .btn {
            font-size: 10px !important;
            padding: 0.3rem 0.6rem !important;
        }
        
        .cart-bundle-contents-wrapper .btn i {
            font-size: 12px !important;
        }
        
        /* Reduce spacing in bundle contents */
        .cart-bundle-contents-wrapper .d-flex.justify-content-end {
            margin-top: 0.5rem !important;
            padding-top: 0.5rem !important;
        }
    }
    
    /* Desktop - ensure proper truncation */
    @media (min-width: 768px) {
        .cart-bundle-item h6.text-truncate {
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }
        
        .cart-bundle-item-price-section {
            width: auto !important;
            text-align: right !important;
            margin-top: 0 !important;
        }
        
        .cart-bundle-item .badge {
            white-space: nowrap !important;
        }
    }
    
    /* Ensure all bundle content stays within container - applies to all screen sizes */
    .cart-bundle-item {
        box-sizing: border-box !important;
    }
    
    .cart-bundle-item * {
        box-sizing: border-box !important;
    }
    
    .cart-bundle-item .flex-grow-1 {
        overflow: hidden !important;
    }
    
    .cart-bundle-item h6 {
        overflow-wrap: break-word !important;
        word-wrap: break-word !important;
        hyphens: auto !important;
    }
    
    .cart-bundle-item .badge {
        max-width: calc(100% - 8px) !important;
        overflow-wrap: break-word !important;
        word-wrap: break-word !important;
    }
    
    @media (min-width: 768px) {
        .cart-bundle-item h6.text-truncate {
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }
        
        .cart-bundle-item-price-section {
            width: auto !important;
            text-align: right !important;
            margin-top: 0 !important;
        }
    }
</style>

<div class="container">
    @php
        $cart_count = count($carts);
    @endphp
    @if( $cart_count > 0 )
        <div class="row">
            <div class="col-lg-8">
                @if(auth()->check())
                    @php
                        $welcomeCoupon = ifUserHasWelcomeCouponAndNotUsed();
                    @endphp
                    @if($welcomeCoupon)
                        <div class="alert alert-primary align-items-center border d-flex flex-wrap justify-content-between rounded-0" style="border-color: #3490F3 !important;">
                            @php
                                $discount = $welcomeCoupon->discount_type == 'amount' ? single_price($welcomeCoupon->discount) : $welcomeCoupon->discount.'%';
                            @endphp
                            <div class="fw-400 fs-14" style="color: #3490F3 !important;">
                                {{ translate('Welcome Coupon') }} <strong>{{ $discount }}</strong> {{ translate('Discount on your Purchase Within') }} <strong>{{ $welcomeCoupon->validation_days }}</strong> {{ translate('days of Registration') }}
                            </div>
                            <button class="btn btn-sm mt-3 mt-lg-0 rounded-4" onclick="copyCouponCode('{{ $welcomeCoupon->coupon_code }}')" style="background-color: #3490F3; color: white;" >{{ translate('Copy coupon Code') }}</button>
                        </div>
                    @endif
                @endif
                <div class="bg-white p-3 p-lg-4 text-left">
                    <div class="mb-4">
                        <!-- Cart Items -->
                        <ul class="list-group list-group-flush">
                        @php
                            $total = 0;
                            
                            // If groupedCarts and ungroupedCarts are not provided, calculate them from $carts
                            if (!isset($groupedCarts) || !isset($ungroupedCarts)) {
                                $groupedCarts = [];
                                $ungroupedCarts = [];
                                
                                foreach ($carts as $cart) {
                                    $cartObj = is_object($cart) ? $cart : (object)$cart;
                                    if (isset($cartObj->group_product_id) && $cartObj->group_product_id) {
                                        $groupId = $cartObj->group_product_id;
                                        $slotHash = isset($cartObj->group_product_slot_combination_hash) ? $cartObj->group_product_slot_combination_hash : null;
                                        
                                        // Use combination hash if available, otherwise just group_product_id
                                        $groupKey = $slotHash ? ($groupId . '_' . $slotHash) : $groupId;
                                        
                                        if (!isset($groupedCarts[$groupKey])) {
                                            $groupedCarts[$groupKey] = [
                                                'groupProduct' => $cartObj->groupProduct ?? \App\Models\GroupProduct::find($groupId),
                                                'group_product_id' => $groupId,
                                                'slot_combination_hash' => $slotHash,
                                                'items' => []
                                            ];
                                        }
                                        $groupedCarts[$groupKey]['items'][] = $cartObj;
                                    } else {
                                        $ungroupedCarts[] = $cartObj;
                                    }
                                }
                            }
                            
                            // Process grouped products (bundles)
                            $groupedItems = [];
                            if (!empty($groupedCarts)) {
                                foreach ($groupedCarts as $groupKey => $groupData) {
                                    $groupedItems[$groupKey] = [
                                        'groupProduct' => $groupData['groupProduct'] ?? $groupData['groupProduct'],
                                        'group_product_id' => $groupData['group_product_id'] ?? $groupData['groupProduct']->id ?? null,
                                        'slot_combination_hash' => $groupData['slot_combination_hash'] ?? $groupData['slotCombinationHash'] ?? null,
                                        'items' => $groupData['items']
                                    ];
                                }
                            }
                            
                            // Process ungrouped products (regular products)
                            $admin_products = array();
                            $seller_products = array();
                            $admin_product_variation = array();
                            $seller_product_variation = array();
                            
                            foreach ($ungroupedCarts as $key => $cartItem){
                                $cartItemObj = is_object($cartItem) ? $cartItem : (object)$cartItem;
                                $productId = $cartItemObj->product_id ?? $cartItemObj['product_id'] ?? null;
                                if (!$productId) continue;
                                
                                $product = get_single_product($productId);
                                if (!$product) continue;

                                $variation = $cartItemObj->variation ?? $cartItemObj['variation'] ?? '';
                                
                                if($product->added_by == 'admin'){
                                    array_push($admin_products, $productId);
                                    $admin_product_variation[] = $variation;
                                }
                                else{
                                    $product_ids = array();
                                    if(isset($seller_products[$product->user_id])){
                                        $product_ids = $seller_products[$product->user_id];
                                    }
                                    array_push($product_ids, $productId);
                                    $seller_products[$product->user_id] = $product_ids;
                                    $seller_product_variation[$product->user_id][] = $variation;
                                }
                            }
                        @endphp

                            <!-- Group Products (Bundles) - Treated as Single Unit -->
                            @if (!empty($groupedItems))
                                @foreach ($groupedItems as $groupKey => $groupData)
                                    @php
                                        $groupId = $groupData['group_product_id'] ?? $groupData['groupProduct']->id ?? null;
                                        $groupProduct = $groupData['groupProduct'];
                                        $groupCartItems = $groupData['items'];
                                        $bundleQuantity = $groupCartItems[0]->quantity ?? 1;
                                        $firstCartItemId = $groupCartItems[0]->id ?? 0;
                                        $slotHash = $groupData['slot_combination_hash'] ?? ($groupCartItems[0]->group_product_slot_combination_hash ?? null);
                                        // Use groupKey for unique identification (includes slot hash)
                                        $groupKeyForInput = $groupId . ($slotHash ? '_' . $slotHash : '');
                                        $groupTotal = 0;
                                        
                                        // Calculate total for the bundle
                                        foreach ($groupCartItems as $cartItem) {
                                            $groupTotal += $cartItem->price * $cartItem->quantity;
                                        }
                                        $total += $groupTotal;
                                        
                                        // Calculate min and max quantity based on stock
                                        $minQuantity = 1;
                                        $maxQuantity = PHP_INT_MAX;
                                        foreach ($groupCartItems as $item) {
                                            $prod = get_single_product($item->product_id);
                                            if ($prod && $prod->digital == 0) {
                                                $stock = $prod->stocks->where('variant', $item->variation)->first();
                                                if ($stock) {
                                                    if ($stock->qty < $maxQuantity) {
                                                        $maxQuantity = $stock->qty;
                                                    }
                                                    $minQty = max($prod->min_qty ?? 1, 1);
                                                    if ($minQty > $minQuantity) {
                                                        $minQuantity = $minQty;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                    <!-- Bundle as Single Item -->
                                    <li class="list-group-item px-0 border-md-0 mb-4" style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #e9ecef !important; overflow: hidden;">
                                        <div class="row gutters-5 align-items-center p-3 p-md-4">
                                            <!-- Bundle Image & Name -->
                                            <div class="col-lg-5 col-md-6 col-12 d-flex align-items-center mb-3 mb-md-0">
                                                <div class="position-relative mr-3 flex-shrink-0">
                                                    <img src="{{ uploaded_asset($groupProduct->thumbnail_img ?? 'assets/img/placeholder.jpg') }}"
                                                        class="img-fit rounded-lg"
                                                        style="width: 90px; height: 90px; object-fit: cover; border: 3px solid #f8f9fa;"
                                                        alt="{{ $groupProduct->name ?? translate('Bundle') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    <span class="badge badge-primary position-absolute rounded-circle d-flex align-items-center justify-content-center" 
                                                        style="top: -5px; right: -5px; width: 28px; height: 28px; padding: 0; font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                                        <i class="las la-gift"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1 min-w-0">
                                                    <h6 class="fs-16 fw-700 text-dark mb-1 text-truncate-2" style="line-height: 1.3;">
                                                        {{ $groupProduct->name ?? translate('Bundle') }}
                                                    </h6>
                                                    <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                                                        <span style="width: auto;" class="badge badge-soft-primary fs-11 px-2 py-1">
                                                            <i class="las la-box mr-1"></i>{{ count($groupCartItems) }} {{ translate('items') }}
                                                        </span>
                                                        <span style="width: auto;" class="badge badge-soft-info fs-11 px-2 py-1">
                                                            <i class="las la-layer-group mr-1"></i>{{ translate('Bundle') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Bundle Price (Desktop) -->
                                            <div class="col-lg-2 col-md-3 d-none d-md-flex flex-column align-items-start mb-3 mb-md-0">
                                                <span class="fs-12 text-muted mb-1">{{ translate('Price per Bundle')}}</span>
                                                <span class="fw-700 fs-18 text-primary mb-1">{{ single_price($groupTotal / $bundleQuantity) }}</span>
                                            </div>
                                            <!-- Bundle Price & Quantity (Mobile: Side by Side) -->
                                            <div class="col-12 d-flex d-md-none cart-bundle-header-mobile mb-2">
                                                <div class="cart-bundle-price-mobile">
                                                    <div class="cart-bundle-price-label fs-10 text-muted mb-1">{{ translate('Price per Bundle')}}</div>
                                                    <div class="cart-bundle-price-value fw-700 fs-13 text-primary">{{ single_price($groupTotal / $bundleQuantity) }}</div>
                                                </div>
                                                <div class="cart-bundle-quantity-mobile">
                                                    <div class="d-flex align-items-center aiz-plus-minus" 
                                                        style="background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; padding: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                                        <button
                                                            class="btn btn-sm border-0 bg-white cart-bundle-quantity-control"
                                                            type="button" data-type="minus"
                                                            data-field="bundle-quantity-{{ $groupKeyForInput }}"
                                                            onclick="updateBundleQuantity({{ $firstCartItemId }}, '{{ $groupKeyForInput }}', -1)"
                                                            style="min-width: 28px; height: 30px; border-radius: 5px 0 0 5px; transition: all 0.2s;"
                                                            onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                            <i class="las la-minus text-dark" style="font-size: 11px;"></i>
                                                        </button>
                                                        <input type="number" 
                                                            id="bundle-quantity-{{ $groupKeyForInput }}"
                                                            class="form-control border-0 text-center px-1 bg-white cart-bundle-quantity-input"
                                                            value="{{ $bundleQuantity }}"
                                                            min="{{ $minQuantity }}"
                                                            max="{{ $maxQuantity }}"
                                                            onchange="syncBundleQuantity('{{ $groupKeyForInput }}', this.value); updateBundleQuantity({{ $firstCartItemId }}, '{{ $groupKeyForInput }}', 0, this.value)" 
                                                            style="width: 45px; height: 30px; padding: 0; border-left: 1px solid #dee2e6; border-right: 1px solid #dee2e6; font-size: 12px; font-weight: 600;">
                                                        <button
                                                            class="btn btn-sm border-0 bg-white cart-bundle-quantity-control"
                                                            type="button" data-type="plus"
                                                            data-field="bundle-quantity-{{ $groupKeyForInput }}"
                                                            onclick="updateBundleQuantity({{ $firstCartItemId }}, '{{ $groupKeyForInput }}', 1)"
                                                            style="min-width: 28px; height: 30px; border-radius: 0 5px 5px 0; transition: all 0.2s;"
                                                            onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                            <i class="las la-plus text-dark" style="font-size: 11px;"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Bundle Quantity Control & Total (Desktop) -->
                                            <div class="col-lg-3 col-md-3 d-none d-md-flex flex-column flex-md-row justify-content-md-between align-items-md-center mb-3 mb-md-0">
                                                <div class="mb-2 mb-md-0">
                                                    <div class="d-flex align-items-center aiz-plus-minus" 
                                                        style="background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6; padding: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                                        <button
                                                            class="btn btn-sm border-0 bg-white"
                                                            type="button" data-type="minus"
                                                            data-field="bundle-quantity-{{ $groupKeyForInput }}-desktop"
                                                            onclick="updateBundleQuantity({{ $firstCartItemId }}, '{{ $groupKeyForInput }}', -1)"
                                                            style="min-width: 36px; height: 36px; border-radius: 6px 0 0 6px; transition: all 0.2s;"
                                                            onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                            <i class="las la-minus text-dark"></i>
                                                        </button>
                                                        <input type="number" 
                                                            id="bundle-quantity-{{ $groupKeyForInput }}-desktop"
                                                            class="form-control border-0 text-center px-1 bg-white fs-15 fw-600"
                                                            value="{{ $bundleQuantity }}"
                                                            min="{{ $minQuantity }}"
                                                            max="{{ $maxQuantity }}"
                                                            onchange="syncBundleQuantity('{{ $groupKeyForInput }}', this.value); updateBundleQuantity({{ $firstCartItemId }}, '{{ $groupKeyForInput }}', 0, this.value)" 
                                                            style="width: 55px; height: 36px; padding: 0; border-left: 1px solid #dee2e6; border-right: 1px solid #dee2e6;">
                                                        <button
                                                            class="btn btn-sm border-0 bg-white"
                                                            type="button" data-type="plus"
                                                            data-field="bundle-quantity-{{ $groupKeyForInput }}-desktop"
                                                            onclick="updateBundleQuantity({{ $firstCartItemId }}, '{{ $groupKeyForInput }}', 1)"
                                                            style="min-width: 36px; height: 36px; border-radius: 0 6px 6px 0; transition: all 0.2s;"
                                                            onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                            <i class="las la-plus text-dark"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- Bundle Total -->
                                                <div class="text-md-right p-2 m-2 mt-2 mt-md-0">
                                                    <div class="fs-12 text-muted mb-1">{{ translate('Total') }}</div>
                                                    <span class="fw-700 fs-18 text-primary">{{ single_price($groupTotal) }}</span>
                                                </div>
                                            </div>
                                            <!-- Bundle Total (Mobile: New Line) -->
                                            <div class="col-12 d-block d-md-none cart-bundle-total-mobile">
                                                <div class="cart-bundle-total-label fs-10 text-muted mb-1">{{ translate('Total') }}</div>
                                                <span class="cart-bundle-total-value fw-700 fs-14 text-primary">{{ single_price($groupTotal) }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Bundle Items List - Enhanced with More Details -->
                                        <div class="px-4 pb-4 cart-bundle-contents-wrapper" style="background: #f8f9fa; border-top: 1px solid #e9ecef; width: 100%; max-width: 100%; box-sizing: border-box; overflow: hidden;">
                                            <div class="d-flex align-items-center justify-content-between mb-3 mt-3">
                                                <h6 class="fs-13 fw-700 text-dark mb-0">
                                                    <i class="las la-list text-primary mr-1"></i>{{ translate('Bundle Contents') }} ({{ count($groupCartItems) }})
                                                </h6>
                                            </div>
                                            <div class="row gutters-10 cart-bundle-contents-container" style="width: 100%; max-width: 100%; margin: 0;">
                                                @foreach ($groupCartItems as $index => $cartItem)
                                                    @php
                                                        $product = get_single_product($cartItem->product_id);
                                                        $slot = $cartItem->groupProductSlot;
                                                        $itemSubtotal = $cartItem->price * $cartItem->quantity;
                                                        
                                                        // Parse variation to format as "Color: Darkcyan Size: L"
                                                        $variationDisplay = '';
                                                        if ($cartItem->variation) {
                                                            $variation = $cartItem->variation;
                                                            $choiceOptions = json_decode($product->choice_options ?? '[]', true) ?? [];
                                                            
                                                            // Split variation by common separators
                                                            $variationParts = preg_split('/[\s\-\/|,·]+/', $variation);
                                                            $variationParts = array_filter(array_map('trim', $variationParts));
                                                            
                                                            $colorValue = '';
                                                            $sizeValue = '';
                                                            
                                                            // Try to identify color and size from choice options
                                                            foreach ($choiceOptions as $idx => $choice) {
                                                                $label = strtolower($choice['title'] ?? '');
                                                                $value = trim($variationParts[$idx] ?? '');
                                                                
                                                                if ($value && (stripos($label, 'color') !== false || stripos($label, 'colour') !== false)) {
                                                                    $colorValue = $value;
                                                                } elseif ($value && (stripos($label, 'size') !== false)) {
                                                                    $sizeValue = $value;
                                                                }
                                                            }
                                                            
                                                            // If not found in choice options, try to identify by common patterns
                                                            if (!$colorValue && !$sizeValue && count($variationParts) >= 2) {
                                                                // Assume first is color, second is size
                                                                $colorValue = $variationParts[0] ?? '';
                                                                $sizeValue = $variationParts[1] ?? '';
                                                            } elseif (!$colorValue && !$sizeValue && count($variationParts) == 1) {
                                                                // Single value - check if it looks like a size (contains numbers or common size patterns)
                                                                $singleValue = $variationParts[0];
                                                                if (preg_match('/^(XS|S|M|L|XL|XXL|XXXL|\d+)$/i', $singleValue)) {
                                                                    $sizeValue = $singleValue;
                                                                } else {
                                                                    $colorValue = $singleValue;
                                                                }
                                                            }
                                                            
                                                            // Build display string
                                                            $parts = [];
                                                            if ($colorValue) {
                                                                $parts[] = translate('Color') . ': ' . $colorValue;
                                                            }
                                                            if ($sizeValue) {
                                                                $parts[] = translate('Size') . ': ' . $sizeValue;
                                                            }
                                                            
                                                            // If parsing failed, show original variation
                                                            if (empty($parts)) {
                                                                $variationDisplay = $variation;
                                                            } else {
                                                                $variationDisplay = implode(' ', $parts);
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($product)
                                                        <div class="col-12 mb-2">
                                                            <div class="d-flex align-items-start p-3 rounded-lg cart-bundle-item" 
                                                                style="background: #ffffff; border: 1px solid #e9ecef; transition: all 0.2s; width: 100%; max-width: 100%; box-sizing: border-box; overflow: hidden;"
                                                                onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.08)'; this.style.borderColor='#dee2e6';" 
                                                                onmouseout="this.style.boxShadow='none'; this.style.borderColor='#e9ecef';">
                                                                <!-- Product Image -->
                                                                <div class="flex-shrink-0 mr-3">
                                                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" 
                                                                         data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                                         class="lazyload img-fit rounded cart-bundle-item-img" 
                                                                         style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #f8f9fa;"
                                                                         alt="{{ $product->getTranslation('name') }}" 
                                                                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                                </div>
                                                                
                                                                <!-- Product Details -->
                                                                <div class="flex-grow-1 min-w-0" style="width: 100%; max-width: 100%; box-sizing: border-box; overflow: hidden;">
                                                                    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-start" style="width: 100%; max-width: 100%;">
                                                                        <div class="flex-grow-1 min-w-0 pr-0 pr-md-2 mb-2 mb-md-0" style="width: 100%; max-width: 100%; box-sizing: border-box; overflow: hidden;">
                                                                            <h6 class="fs-12 fs-md-13 fw-600 text-dark mb-1 text-truncate" style="line-height: 1.4; word-wrap: break-word; overflow-wrap: break-word; max-width: 100%; box-sizing: border-box;">
                                                                                {{ $product->getTranslation('name') }}
                                                                            </h6>
                                                                            <div class="d-flex flex-wrap" style="gap: 4px; width: 100%; max-width: 100%; box-sizing: border-box;">
                                                                                <!-- Variation formatted as "Color: Darkcyan Size: L" -->
                                                                                @if ($variationDisplay)
                                                                                    <span style="width: auto; max-width: 100%; box-sizing: border-box;" class="badge badge-soft-secondary fs-9 fs-md-10 px-2 py-1" style="background: #e2e3e5; color: #383d41; border: none; word-break: break-word; white-space: normal;">
                                                                                        {{ $variationDisplay }}
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <!-- Item Price & Quantity -->
                                                                        <div class="text-left text-md-right flex-shrink-0 cart-bundle-item-price-section" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                                                                            <span class="fs-11 fs-md-12 text-muted d-inline">{{ translate('Qty') }}: <span class="fw-600 text-dark">{{ $cartItem->quantity }}</span></span>
                                                                            <span class="fs-12 fs-md-13 fw-600 text-primary d-inline">{{ translate('Price') }}: {{ single_price($cartItem->price) }}</span>
                                                                            @if ($cartItem->quantity > 1)
                                                                                <span class="fs-10 fs-md-11 text-muted d-inline">× {{ $cartItem->quantity }} = {{ single_price($itemSubtotal) }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            
                                            <!-- Remove Bundle Button at the end -->
                                            <div class="d-flex justify-content-end mt-3 pt-3" style="border-top: 1px solid #dee2e6;">
                                                <button onclick="removeGroupProductFromCart('{{ $groupKey }}')" 
                                                    class="btn btn-sm border-0 d-flex align-items-center" 
                                                    title="{{ translate('Remove Bundle') }}"
                                                    style="background: #fff; color: #dc3545; box-shadow: 0 2px 6px rgba(220,53,69,0.2); transition: all 0.3s; border-radius: 8px; padding: 8px 16px;"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(220,53,69,0.3)'" 
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(220,53,69,0.2)'">
                                                    <i class="las la-trash text-danger fs-16 mr-2"></i>
                                                    <span class="fw-600">{{ translate('Remove Bundle') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif

                            <!-- Inhouse Products -->
                            @if (!empty($admin_products))
                                @foreach ($admin_products as $key => $product_id)
                                    @php
                                        $product = get_single_product($product_id);
                                        if (!$product) continue;
                                        
                                        $variationValue = $admin_product_variation[$key] ?? '';
                                        // Handle products with and without variations
                                        $query = $carts->toQuery()->where('product_id', $product_id);
                                        if ($variationValue == '' || $variationValue == null) {
                                            $query->where(function($q) {
                                                $q->whereNull('variation')->orWhere('variation', '');
                                            });
                                        } else {
                                            $query->where('variation', $variationValue);
                                        }
                                        $cartItem = $query->first();
                                        if (!$cartItem) continue;
                                        
                                        $variation = $cartItem->variation ?? $cartItem['variation'] ?? '';
                                        $product_stock = $product->stocks->where('variant', $variation)->first();
                                        $total = $total + cart_product_price($cartItem, $product, false) * $cartItem->quantity;
                                    @endphp
                                    <li class="list-group-item px-0 border-md-0 mb-3" style="background: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #e9ecef !important; transition: all 0.3s ease;">
                                        <div class="row gutters-5 align-items-center p-3 p-md-4">
                                            <!-- Product Image & name -->
                                            <div class="col-lg-5 col-md-6 col-12 d-flex align-items-center mb-3 mb-md-0">
                                                <div class="mr-3 flex-shrink-0">
                                                    <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                        class="img-fit rounded-lg"
                                                        style="width: 85px; height: 85px; object-fit: cover; border: 3px solid #f8f9fa;"
                                                        alt="{{ $product->getTranslation('name')  }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </div>
                                                <div class="flex-grow-1 min-w-0">
                                                    <h6 class="fs-15 fw-600 text-dark text-truncate-2 mb-2" style="line-height: 1.4;">{{ $product->getTranslation('name') }}</h6>
                                                    @if ($admin_product_variation[$key] != '')
                                                        <span style="width: auto;" class="badge badge-soft-secondary fs-11 px-2 py-1" style="background: #e2e3e5; color: #383d41; border: none;">
                                                            <i class="las la-palette mr-1"></i>{{ $admin_product_variation[$key] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- Price & Tax (Desktop) -->
                                            <div class="col-lg-2 col-md-3 d-none d-md-flex flex-column align-items-start mb-3 mb-md-0">
                                                <span class="fs-12 text-muted mb-1">{{ translate('Price')}}</span>
                                                <span class="fw-700 fs-17 text-primary mb-1">{{ cart_product_price($cartItem, $product, true, false) }}</span>
                                                <span class="fs-11 text-muted">{{ translate('Tax')}}: {{ cart_product_tax($cartItem, $product) }}</span>
                                            </div>
                                            <!-- Price & Quantity (Mobile: Side by Side) -->
                                            <div class="col-12 d-flex d-md-none cart-product-header-mobile mb-2">
                                                <div class="cart-product-price-mobile">
                                                    <div class="cart-product-price-label fs-10 text-muted mb-1">{{ translate('Price')}}</div>
                                                    <div class="cart-product-price-value fw-700 fs-13 text-primary mb-1">{{ cart_product_price($cartItem, $product, true, false) }}</div>
                                                    <div class="cart-product-tax-label fs-9 text-muted">{{ translate('Tax')}}: {{ cart_product_tax($cartItem, $product) }}</div>
                                                </div>
                                                <div class="cart-product-quantity-mobile">
                                                    @if ($product->digital != 1 && $product->auction_product == 0)
                                                        <div class="d-flex align-items-center aiz-plus-minus" 
                                                            style="background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; padding: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                                            <button
                                                                class="btn btn-sm border-0 bg-white cart-product-quantity-control"
                                                                type="button" data-type="minus"
                                                                data-field="quantity[{{ $cartItem->id }}]"
                                                                style="min-width: 28px; height: 30px; border-radius: 5px 0 0 5px; transition: all 0.2s;"
                                                                onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                                <i class="las la-minus text-dark" style="font-size: 11px;"></i>
                                                            </button>
                                                            <input type="number" name="quantity[{{ $cartItem->id }}]"
                                                                class="form-control border-0 text-center px-1 bg-white cart-product-quantity-input"
                                                                placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                                min="{{ $product->min_qty }}"
                                                                max="{{ $product_stock ? $product_stock->qty : 999 }}"
                                                                onchange="updateQuantity({{ $cartItem->id }}, this)" 
                                                                style="width: 45px; height: 30px; padding: 0; border-left: 1px solid #dee2e6; border-right: 1px solid #dee2e6; font-size: 12px; font-weight: 600;">
                                                            <button
                                                                class="btn btn-sm border-0 bg-white cart-product-quantity-control"
                                                                type="button" data-type="plus"
                                                                data-field="quantity[{{ $cartItem->id }}]"
                                                                style="min-width: 28px; height: 30px; border-radius: 0 5px 5px 0; transition: all 0.2s;"
                                                                onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                                <i class="las la-plus text-dark" style="font-size: 11px;"></i>
                                                            </button>
                                                        </div>
                                                    @elseif($product->auction_product == 1)
                                                        <span class="badge badge-soft-info fs-10 px-2 py-1" style="background: #d1ecf1; color: #0c5460;">1</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- Quantity & Total (Desktop) -->
                                            <div class="col-lg-3 col-md-3 d-none d-md-flex flex-column flex-md-row justify-content-md-between align-items-md-center mb-3 mb-md-0">
                                                <!-- Quantity -->
                                                <div class="mb-2 mb-md-0">
                                                    @if ($product->digital != 1 && $product->auction_product == 0)
                                                        <div class="d-flex align-items-center aiz-plus-minus" 
                                                            style="background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6; padding: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                                            <button
                                                                class="btn btn-sm border-0 bg-white"
                                                                type="button" data-type="minus"
                                                                data-field="quantity[{{ $cartItem->id }}]"
                                                                style="min-width: 36px; height: 36px; border-radius: 6px 0 0 6px; transition: all 0.2s;"
                                                                onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                                <i class="las la-minus text-dark"></i>
                                                            </button>
                                                            <input type="number" name="quantity[{{ $cartItem->id }}]"
                                                                class="form-control border-0 text-center px-1 bg-white fs-15 fw-600"
                                                                placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                                min="{{ $product->min_qty }}"
                                                                max="{{ $product_stock ? $product_stock->qty : 999 }}"
                                                                onchange="updateQuantity({{ $cartItem->id }}, this)" 
                                                                style="width: 55px; height: 36px; padding: 0; border-left: 1px solid #dee2e6; border-right: 1px solid #dee2e6;">
                                                            <button
                                                                class="btn btn-sm border-0 bg-white"
                                                                type="button" data-type="plus"
                                                                data-field="quantity[{{ $cartItem->id }}]"
                                                                style="min-width: 36px; height: 36px; border-radius: 0 6px 6px 0; transition: all 0.2s;"
                                                                onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                                <i class="las la-plus text-dark"></i>
                                                            </button>
                                                        </div>
                                                    @elseif($product->auction_product == 1)
                                                        <span class="badge badge-soft-info fs-12 px-3 py-2" style="background: #d1ecf1; color: #0c5460;">1</span>
                                                    @endif
                                                </div>
                                                <!-- Total -->
                                                <div class="text-md-right p-2 m-2 mt-2 mt-md-0">
                                                    <div class="fs-12 text-muted mb-1">{{ translate('Total') }}</div>
                                                    <span class="fw-700 fs-17 text-primary">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem->quantity) }}</span>
                                                </div>
                                            </div>
                                            <!-- Total & Remove (Mobile: Side by Side) -->
                                            <div class="col-12 d-block d-md-none cart-product-total-mobile">
                                                <div class="cart-product-total-mobile-content">
                                                    <div class="cart-product-total-label fs-10 text-muted mb-1">{{ translate('Total') }}</div>
                                                    <span class="cart-product-total-value fw-700 fs-14 text-primary">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem->quantity) }}</span>
                                                </div>
                                                <div class="cart-product-remove-mobile">
                                                    <button onclick="removeFromCartView(event, {{ $cartItem->id }})" 
                                                        class="btn btn-icon btn-sm border-0" 
                                                        title="{{ translate('Remove') }}"
                                                        style="width: 36px; height: 36px; border-radius: 50%; background: #fff; box-shadow: 0 2px 6px rgba(220,53,69,0.2); transition: all 0.3s;"
                                                        onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 8px rgba(220,53,69,0.3)'" 
                                                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 6px rgba(220,53,69,0.2)'">
                                                        <i class="las la-trash text-danger" style="font-size: 14px;"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Remove From Cart (Desktop) -->
                                            <div class="col-lg-2 col-md-12 d-none d-md-flex justify-content-lg-end justify-content-start mt-2 mt-md-0">
                                                <button onclick="removeFromCartView(event, {{ $cartItem->id }})" 
                                                    class="btn btn-icon btn-sm border-0" 
                                                    title="{{ translate('Remove') }}"
                                                    style="width: 40px; height: 40px; border-radius: 50%; background: #fff; box-shadow: 0 2px 6px rgba(220,53,69,0.2); transition: all 0.3s;"
                                                    onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 8px rgba(220,53,69,0.3)'" 
                                                    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 6px rgba(220,53,69,0.2)'">
                                                    <i class="las la-trash text-danger fs-16"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif

                            <!-- Seller Products -->
                            @if (!empty($seller_products))
                                @foreach ($seller_products as $key => $seller_product)
                                    @foreach ($seller_product as $key2 => $product_id)
                                        @php
                                            $product = get_single_product($product_id);
                                            if (!$product) continue;
                                            
                                            $variationValue = $seller_product_variation[$key][$key2] ?? '';
                                            // Handle products with and without variations
                                            $query = $carts->toQuery()->where('product_id', $product_id);
                                            if ($variationValue == '' || $variationValue == null) {
                                                $query->where(function($q) {
                                                    $q->whereNull('variation')->orWhere('variation', '');
                                                });
                                            } else {
                                                $query->where('variation', $variationValue);
                                            }
                                            $cartItem = $query->first();
                                            if (!$cartItem) continue;
                                            
                                            $variation = $cartItem->variation ?? $cartItem['variation'] ?? '';
                                            $product_stock = $product->stocks->where('variant', $variation)->first();
                                            $total = $total + cart_product_price($cartItem, $product, false) * $cartItem->quantity;
                                        @endphp
                                        <li class="list-group-item px-0 border-md-0 mb-3" style="background: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #e9ecef !important; transition: all 0.3s ease;">
                                            <div class="row gutters-5 align-items-center p-3 p-md-4">
                                                <!-- Product Image & name -->
                                                <div class="col-lg-5 col-md-6 col-12 d-flex align-items-center mb-3 mb-md-0">
                                                    <div class="mr-3 flex-shrink-0">
                                                        <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                            class="img-fit rounded-lg"
                                                            style="width: 85px; height: 85px; object-fit: cover; border: 3px solid #f8f9fa;"
                                                            alt="{{ $product->getTranslation('name')  }}"
                                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    </div>
                                                    <div class="flex-grow-1 min-w-0">
                                                        <h6 class="fs-15 fw-600 text-dark text-truncate-2 mb-2" style="line-height: 1.4;">{{ $product->getTranslation('name') }}</h6>
                                                        @if ($seller_product_variation[$key][$key2] != '')
                                                            <span class="badge badge-soft-secondary fs-11 px-2 py-1" style="background: #e2e3e5; color: #383d41; border: none;">
                                                                <i class="las la-palette mr-1"></i>{{ $seller_product_variation[$key][$key2] }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- Price & Tax (Desktop) -->
                                                <div class="col-lg-2 col-md-3 d-none d-md-flex flex-column align-items-start mb-3 mb-md-0">
                                                    <span class="fs-12 text-muted mb-1">{{ translate('Price')}}</span>
                                                    <span class="fw-700 fs-17 text-primary mb-1">{{ cart_product_price($cartItem, $product, true, false) }}</span>
                                                    <span class="fs-11 text-muted">{{ translate('Tax')}}: {{ cart_product_tax($cartItem, $product) }}</span>
                                                </div>
                                                <!-- Price & Quantity (Mobile: Side by Side) -->
                                                <div class="col-12 d-flex d-md-none cart-product-header-mobile mb-2">
                                                    <div class="cart-product-price-mobile">
                                                        <div class="cart-product-price-label fs-10 text-muted mb-1">{{ translate('Price')}}</div>
                                                        <div class="cart-product-price-value fw-700 fs-13 text-primary mb-1">{{ cart_product_price($cartItem, $product, true, false) }}</div>
                                                        <div class="cart-product-tax-label fs-9 text-muted">{{ translate('Tax')}}: {{ cart_product_tax($cartItem, $product) }}</div>
                                                    </div>
                                                    <div class="cart-product-quantity-mobile">
                                                        @if ($product->digital != 1 && $product->auction_product == 0)
                                                            <div class="d-flex align-items-center aiz-plus-minus" 
                                                                style="background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; padding: 2px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                                                <button
                                                                    class="btn btn-sm border-0 bg-white cart-product-quantity-control"
                                                                    type="button" data-type="minus"
                                                                    data-field="quantity[{{ $cartItem->id }}]"
                                                                    style="min-width: 28px; height: 30px; border-radius: 5px 0 0 5px; transition: all 0.2s;"
                                                                    onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                                    <i class="las la-minus text-dark" style="font-size: 11px;"></i>
                                                                </button>
                                                                <input type="number" name="quantity[{{ $cartItem->id }}]"
                                                                    class="form-control border-0 text-center px-1 bg-white cart-product-quantity-input"
                                                                    placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                                    min="{{ $product->min_qty }}"
                                                                    max="{{ $product_stock ? $product_stock->qty : 999 }}"
                                                                    onchange="updateQuantity({{ $cartItem->id }}, this)" 
                                                                    style="width: 45px; height: 30px; padding: 0; border-left: 1px solid #dee2e6; border-right: 1px solid #dee2e6; font-size: 12px; font-weight: 600;">
                                                                <button
                                                                    class="btn btn-sm border-0 bg-white cart-product-quantity-control"
                                                                    type="button" data-type="plus"
                                                                    data-field="quantity[{{ $cartItem->id }}]"
                                                                    style="min-width: 28px; height: 30px; border-radius: 0 5px 5px 0; transition: all 0.2s;"
                                                                    onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                                    <i class="las la-plus text-dark" style="font-size: 11px;"></i>
                                                                </button>
                                                            </div>
                                                        @elseif($product->auction_product == 1)
                                                            <span class="badge badge-soft-info fs-10 px-2 py-1" style="background: #d1ecf1; color: #0c5460;">1</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- Quantity & Total (Desktop) -->
                                                <div class="col-lg-3 col-md-3 d-none d-md-flex flex-column flex-md-row justify-content-md-between align-items-md-center mb-3 mb-md-0">
                                                    <!-- Quantity -->
                                                    <div class="mb-2 mb-md-0">
                                                        @if ($product->digital != 1 && $product->auction_product == 0)
                                                            <div class="d-flex align-items-center aiz-plus-minus" 
                                                                style="background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6; padding: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                                                <button
                                                                    class="btn btn-sm border-0 bg-white"
                                                                    type="button" data-type="minus"
                                                                    data-field="quantity[{{ $cartItem->id }}]"
                                                                    style="min-width: 36px; height: 36px; border-radius: 6px 0 0 6px; transition: all 0.2s;"
                                                                    onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                                    <i class="las la-minus text-dark"></i>
                                                                </button>
                                                                <input type="number" name="quantity[{{ $cartItem->id }}]"
                                                                    class="form-control border-0 text-center px-1 bg-white fs-15 fw-600"
                                                                    placeholder="1" value="{{ $cartItem['quantity'] }}"
                                                                    min="{{ $product->min_qty }}"
                                                                    max="{{ $product_stock ? $product_stock->qty : 999 }}"
                                                                    onchange="updateQuantity({{ $cartItem->id }}, this)" 
                                                                    style="width: 55px; height: 36px; padding: 0; border-left: 1px solid #dee2e6; border-right: 1px solid #dee2e6;">
                                                                <button
                                                                    class="btn btn-sm border-0 bg-white"
                                                                    type="button" data-type="plus"
                                                                    data-field="quantity[{{ $cartItem->id }}]"
                                                                    style="min-width: 36px; height: 36px; border-radius: 0 6px 6px 0; transition: all 0.2s;"
                                                                    onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='#fff'">
                                                                    <i class="las la-plus text-dark"></i>
                                                                </button>
                                                            </div>
                                                        @elseif($product->auction_product == 1)
                                                            <span class="badge badge-soft-info fs-12 px-3 py-2" style="background: #d1ecf1; color: #0c5460;">1</span>
                                                        @endif
                                                    </div>
                                                    <!-- Total -->
                                                    <div class="text-md-right mt-2 mt-md-0">
                                                        <div class="fs-12 text-muted mb-1">{{ translate('Total') }}</div>
                                                        <span class="fw-700 fs-17 text-primary">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem->quantity) }}</span>
                                                    </div>
                                                </div>
                                                <!-- Total & Remove (Mobile: Side by Side) -->
                                                <div class="col-12 d-block d-md-none cart-product-total-mobile">
                                                    <div class="cart-product-total-mobile-content">
                                                        <div class="cart-product-total-label fs-10 text-muted mb-1">{{ translate('Total') }}</div>
                                                        <span class="cart-product-total-value fw-700 fs-14 text-primary">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem->quantity) }}</span>
                                                    </div>
                                                    <div class="cart-product-remove-mobile">
                                                        <button onclick="removeFromCartView(event, {{ $cartItem->id }})" 
                                                            class="btn btn-icon btn-sm border-0" 
                                                            title="{{ translate('Remove') }}"
                                                            style="width: 36px; height: 36px; border-radius: 50%; background: #fff; box-shadow: 0 2px 6px rgba(220,53,69,0.2); transition: all 0.3s;"
                                                            onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 8px rgba(220,53,69,0.3)'" 
                                                            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 6px rgba(220,53,69,0.2)'">
                                                            <i class="las la-trash text-danger" style="font-size: 14px;"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- Remove From Cart (Desktop) -->
                                                <div class="col-lg-2 col-md-12 d-none d-md-flex justify-content-lg-end justify-content-start mt-2 mt-md-0">
                                                    <button onclick="removeFromCartView(event, {{ $cartItem->id }})" 
                                                        class="btn btn-icon btn-sm border-0" 
                                                        title="{{ translate('Remove') }}"
                                                        style="width: 40px; height: 40px; border-radius: 50%; background: #fff; box-shadow: 0 2px 6px rgba(220,53,69,0.2); transition: all 0.3s;"
                                                        onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 8px rgba(220,53,69,0.3)'" 
                                                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 6px rgba(220,53,69,0.2)'">
                                                        <i class="las la-trash text-danger fs-16"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4 mt-lg-0 mt-4" id="cart_summary">
                @php
                    // Calculate active_carts for cart summary (all carts with status = 1)
                    $active_carts = [];
                    if ($cart_count > 0) {
                        if (is_object($carts) && method_exists($carts, 'toQuery')) {
                            $active_carts = $carts->toQuery()->active()->get();
                        } else {
                            foreach ($carts as $cartItem) {
                                $status = is_object($cartItem) ? ($cartItem->status ?? 1) : (isset($cartItem['status']) ? $cartItem['status'] : 1);
                                if ($status == 1) {
                                    $active_carts[] = $cartItem;
                                }
                            }
                        }
                    }
                @endphp
                @include('frontend.partials.cart.cart_summary', ['proceed' => 1, 'carts' => $active_carts])
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="border bg-white p-4">
                    <!-- Empty cart -->
                    <div class="text-center p-3">
                        <i class="las la-frown la-3x opacity-60 mb-3"></i>
                        <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Sync bundle quantity inputs between mobile and desktop
    function syncBundleQuantity(groupKey, value) {
        var mobileInput = document.getElementById('bundle-quantity-' + groupKey);
        var desktopInput = document.getElementById('bundle-quantity-' + groupKey + '-desktop');
        
        if (mobileInput && mobileInput.value != value) {
            mobileInput.value = value;
        }
        if (desktopInput && desktopInput.value != value) {
            desktopInput.value = value;
        }
    }
    
    // Sync on button clicks as well
    $(document).ready(function() {
        $('[data-field^="bundle-quantity-"]').on('click', function() {
            var field = $(this).data('field');
            var groupKey = field.replace('bundle-quantity-', '').replace('-desktop', '');
            setTimeout(function() {
                var mobileInput = $('#bundle-quantity-' + groupKey);
                var desktopInput = $('#bundle-quantity-' + groupKey + '-desktop');
                if (mobileInput.length && desktopInput.length) {
                    var value = mobileInput.is(':visible') ? mobileInput.val() : desktopInput.val();
                    mobileInput.val(value);
                    desktopInput.val(value);
                }
            }, 100);
        });
    });
</script>

