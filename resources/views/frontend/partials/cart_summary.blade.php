<style>
    /* Responsive product images in cart summary */
    @media (min-width: 768px) {
        .cart-summary-product-img {
            width: 50px !important;
            height: 50px !important;
        }
        .cart-summary-bundle-icon {
            width: 32px !important;
            height: 32px !important;
        }
        .cart-summary-bundle-icon i {
            font-size: 14px !important;
        }
    }
    
    @media (max-width: 767px) {
        .cart-summary-product-img {
            width: 40px !important;
            height: 40px !important;
        }
        .cart-summary-bundle-icon {
            width: 28px !important;
            height: 28px !important;
        }
        .cart-summary-bundle-icon i {
            font-size: 12px !important;
        }
    }
</style>

<div class="card border shadow-none p-0" style="overflow: hidden; border-radius: 0.75rem;">

    <!-- Summary Header -->
    <div class="summary-header" style="background: {{ get_setting('base_color', '#d43533') }}; padding: 24px 20px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); width: 100%;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center flex-grow-1">
                <div class="header-icon-wrapper" style="width: 50px; height: 50px; background: rgba(255, 255, 255, 0.25); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; backdrop-filter: blur(10px); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <i class="las la-shopping-cart" style="font-size: 28px; color: #ffffff;"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="mb-0" style="color: #ffffff; font-size: 18px; font-weight: 700; letter-spacing: 0.5px; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
                        {{ translate('Summary') }}
                    </h3>
                    <p class="mb-0 mt-1" style="color: rgba(255, 255, 255, 0.95); font-size: 12px; font-weight: 400; opacity: 0.9;">
                        {{ translate('Order summary and totals') }}
                    </p>
                </div>
            </div>
            <div class="text-right ml-3">
                <!-- Items Count -->
                <span class="badge badge-inline badge-light fs-11 rounded-pill px-3 py-1" style="background: rgba(255, 255, 255, 0.25); color: #ffffff; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3);">
                    {{ count($carts) }}
                    {{ translate('Items') }}
                </span>

                <!-- Minimum Order Amount -->
                @php
                    $coupon_discount = 0;
                @endphp
                @if (get_setting('coupon_system') == 1)
                    @php
                        $coupon_code = null;
                    @endphp

                    @foreach ($carts as $key => $cartItem)
                        @if ($cartItem->coupon_applied == 1)
                            @php
                                $coupon_code = $cartItem->coupon_code;
                                break;
                            @endphp
                        @endif
                    @endforeach

                    @php
                        $coupon_discount = $carts->sum('discount');
                    @endphp
                @endif

                @php $subtotal_for_min_order_amount = 0; @endphp
                @foreach ($carts as $key => $cartItem)
                    @php $subtotal_for_min_order_amount += (float)cart_product_price($cartItem, $cartItem->product, false, false) * (int)$cartItem['quantity']; @endphp
                @endforeach
                @if (get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
                    <span class="badge badge-inline badge-light fs-11 rounded-pill px-3 py-1 mt-1 d-block" style="background: rgba(255, 255, 255, 0.25); color: #ffffff; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3);">
                        {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
                    </span>
                @endif
            </div>
        </div>
    </div>
    
    <div class="p-3">
        <!-- Club point -->
        @if (addon_is_activated('club_point'))
        <div class="px-4 pt-1 w-100 d-flex align-items-center justify-content-between">
            <h3 class="fs-12 fw-700 mb-0">{{ translate('Total Clubpoint') }}</h3>
            <div class="text-right">
                <span class="badge badge-inline badge-secondary-base fs-11 rounded-0 px-2 text-white">
                    @php
                        $total_point = 0;
                    @endphp
                    @foreach ($carts as $key => $cartItem)
                        @php
                            $product = get_single_product($cartItem['product_id']);
                            $total_point += (int)$product->earn_point * (int)$cartItem['quantity'];
                        @endphp
                    @endforeach

                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" class="mr-2">
                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                          <circle id="Ellipse_39" data-name="Ellipse 39" cx="6" cy="6" r="6" transform="translate(973 633)" fill="#fff"/>
                          <g id="Group_23920" data-name="Group 23920" transform="translate(973 633)">
                            <path id="Path_28698" data-name="Path 28698" d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)" fill="#f3af3d"/>
                            <path id="Path_28699" data-name="Path 28699" d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)" fill="#f3af3d" opacity="0.5"/>
                            <path id="Path_28700" data-name="Path 28700" d="M12.666,3h1L15,5,12,9l1.664-4Z" transform="translate(-5.995 0)" fill="#f3af3d"/>
                          </g>
                        </g>
                    </svg>
                    {{ $total_point }}
                </span>
            </div>
        </div>
        @endif

        <div class="card-body">
        <!-- Products Info -->
        @php
            // Group carts into bundles and normal products (same logic as cart_details)
            $groupedCarts = [];
            $ungroupedCarts = [];
            
            foreach ($carts as $cart) {
                $cartObj = is_object($cart) ? $cart : (object)$cart;
                if (isset($cartObj->group_product_id) && $cartObj->group_product_id) {
                    $groupId = $cartObj->group_product_id;
                    $slotHash = isset($cartObj->group_product_slot_combination_hash) ? $cartObj->group_product_slot_combination_hash : null;
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
            
            // Process grouped products
            $groupedItems = [];
            if (!empty($groupedCarts)) {
                foreach ($groupedCarts as $groupKey => $groupData) {
                    $groupedItems[$groupKey] = [
                        'groupProduct' => $groupData['groupProduct'],
                        'group_product_id' => $groupData['group_product_id'],
                        'slot_combination_hash' => $groupData['slot_combination_hash'],
                        'items' => $groupData['items']
                    ];
                }
            }
            
            // Process ungrouped products (admin and seller)
            $admin_products = array();
            $seller_products = array();
            $admin_product_variation = array();
            $seller_product_variation = array();
            
            foreach ($ungroupedCarts as $key => $cartItem) {
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
            
            // Calculate totals
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $product_shipping_cost = 0;
            
            // Calculate from all carts for totals
            foreach ($carts as $key => $cartItem) {
                $cartItemObj = is_object($cartItem) ? $cartItem : (object)$cartItem;
                $productId = $cartItemObj->product_id ?? $cartItemObj['product_id'] ?? null;
                if (!$productId) continue;
                
                $product = get_single_product($productId);
                if (!$product) continue;
                
                // Check if it's a group product
                $isGroupProduct = false;
                if (isset($cartItemObj->group_product_id) && $cartItemObj->group_product_id !== null && $cartItemObj->group_product_id !== '') {
                    $isGroupProduct = true;
                } elseif (isset($cartItemObj['group_product_id']) && $cartItemObj['group_product_id'] !== null && $cartItemObj['group_product_id'] !== '') {
                    $isGroupProduct = true;
                }
                
                if ($isGroupProduct) {
                    // For group products, use stored price
                    $itemPrice = isset($cartItemObj->price) ? (float)$cartItemObj->price : (isset($cartItemObj['price']) ? (float)$cartItemObj['price'] : 0);
                    $quantity = isset($cartItemObj->quantity) ? (int)$cartItemObj->quantity : (isset($cartItemObj['quantity']) ? (int)$cartItemObj['quantity'] : 0);
                    $subtotal += $itemPrice * $quantity;
                } else {
                    $itemPrice = (float)cart_product_price($cartItemObj, $product, false, false);
                    $quantity = isset($cartItemObj->quantity) ? (int)$cartItemObj->quantity : (isset($cartItemObj['quantity']) ? (int)$cartItemObj['quantity'] : 0);
                    $subtotal += $itemPrice * $quantity;
                }
                
                $taxAmount = (float)cart_product_tax($cartItemObj, $product, false);
                $quantity = isset($cartItemObj->quantity) ? (int)$cartItemObj->quantity : (isset($cartItemObj['quantity']) ? (int)$cartItemObj['quantity'] : 0);
                $tax += $taxAmount * $quantity;
                $product_shipping_cost = isset($cartItemObj->shipping_cost) ? (float)$cartItemObj->shipping_cost : (isset($cartItemObj['shipping_cost']) ? (float)$cartItemObj['shipping_cost'] : 0);
                $shipping += $product_shipping_cost;
            }
        @endphp

        <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th class="product-name border-top-0 border-bottom-1 pl-0 fs-10 fs-md-11 fw-400 opacity-60">{{ translate('Product') }}</th>
                    <th class="product-total text-right border-top-0 border-bottom-1 pr-0 fs-10 fs-md-11 fw-400 opacity-60">{{ translate('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                <!-- Group Products (Bundles) -->
                @if (!empty($groupedItems))
                    @foreach ($groupedItems as $groupKey => $groupData)
                        @php
                            $groupProduct = $groupData['groupProduct'];
                            $groupCartItems = $groupData['items'];
                            $bundleQuantity = $groupCartItems[0]->quantity ?? 1;
                            $groupTotal = 0;
                            foreach ($groupCartItems as $cartItem) {
                                $itemPrice = isset($cartItem->price) ? (float)$cartItem->price : (isset($cartItem['price']) ? (float)$cartItem['price'] : 0);
                                $itemQty = isset($cartItem->quantity) ? (int)$cartItem->quantity : (isset($cartItem['quantity']) ? (int)$cartItem['quantity'] : 0);
                                $groupTotal += $itemPrice * $itemQty;
                            }
                            $pricePerBundle = $groupTotal / $bundleQuantity;
                        @endphp
                        <!-- Bundle Header -->
                        <tr class="cart_item">
                            <td colspan="2" class="pl-0 pr-0 border-top-0 border-bottom-0 p-2 p-md-3" style="background: linear-gradient(135deg, #e8e3f5 0%, #f3f0fa 100%); border-radius: 8px 8px 0 0;">
                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                                    <div class="d-flex align-items-center flex-grow-1 w-100 w-md-auto mb-2 mb-md-0">
                                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mr-2 mr-md-3 cart-summary-bundle-icon" style="min-width: 28px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); flex-shrink: 0;">
                                            <i class="las la-gift text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fs-12 fs-md-14 fw-700 text-dark mb-1">{{ $groupProduct->name ?? translate('Bundle') }}</div>
                                            <div class="fs-10 fs-md-11 text-muted">
                                                <span class="d-block d-md-inline">{{ translate('Quantity') }}: {{ $bundleQuantity }}</span>
                                                <span class="d-none d-md-inline"> | </span>
                                                <span class="d-block d-md-inline">{{ count($groupCartItems) }} {{ translate('items') }}</span>
                                                <span class="d-none d-md-inline"> | </span>
                                                <span class="d-block d-md-inline">{{ translate('Per Bundle') }}: {{ single_price($pricePerBundle) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-left text-md-right ml-auto ml-md-3 mt-2 mt-md-0">
                                        <div class="fs-14 fs-md-16 fw-700 text-primary">{{ single_price($groupTotal) }}</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Bundle Items -->
                        @foreach ($groupCartItems as $index => $cartItem)
                            @php
                                $product = get_single_product($cartItem->product_id);
                                $itemPrice = isset($cartItem->price) ? (float)$cartItem->price : (isset($cartItem['price']) ? (float)$cartItem['price'] : 0);
                                $itemQty = isset($cartItem->quantity) ? (int)$cartItem->quantity : (isset($cartItem['quantity']) ? (int)$cartItem['quantity'] : 0);
                                $itemSubtotal = $itemPrice * $itemQty;
                                
                                // Parse variation to format as "Color: Darkcyan Size: L"
                                $variationDisplay = '';
                                $variation = isset($cartItem->variation) ? $cartItem->variation : (isset($cartItem['variation']) ? $cartItem['variation'] : '');
                                if ($variation && $product) {
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
                                <tr class="cart_item" style="background: #ffffff;">
                                    <td class="product-name pl-0 fs-11 fs-md-12 text-dark fw-400 border-top-0 p-2 p-md-3" style="padding-left: 40px !important; padding-left-md: 50px !important;">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0 mr-2 mr-md-3">
                                                <img src="{{ uploaded_asset($product->thumbnail_img) }}" 
                                                     class="img-fit rounded-lg cart-summary-product-img" 
                                                     style="object-fit: cover; border: 2px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
                                                     alt="{{ $product->getTranslation('name') }}" 
                                                     onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fs-11 fs-md-12 fw-600 text-dark mb-1" style="line-height: 1.3;">{{ $product->getTranslation('name') }}</div>
                                                <div class="d-flex flex-wrap align-items-center" style="gap: 4px; margin-bottom: 4px;">
                                                    @if ($variationDisplay)
                                                        <span style="width: auto;" class="badge badge-soft-secondary fs-9 fs-md-10 px-1 py-0" style="background: #e2e3e5; color: #383d41; border: none;">
                                                            {{ $variationDisplay }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="fs-10 fs-md-11 text-muted">
                                                    <strong>{{ translate('QTY') }}:</strong> {{ $itemQty }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="product-total text-right pr-0 fs-11 fs-md-12 border-top-0 p-2 p-md-3" style="vertical-align: middle;">
                                        <div class="text-right">
                                            <div class="fs-12 fs-md-13 fw-700 text-primary mb-1">{{ single_price($itemPrice) }}</div>
                                            @if ($itemQty > 1)
                                                <div class="fs-10 fs-md-11 text-muted">× {{ $itemQty }} = {{ single_price($itemSubtotal) }}</div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        <!-- Bundle Total Row -->
                        <tr class="cart_item">
                            <td colspan="2" class="pl-0 pr-0 border-top-0 border-bottom p-2 p-md-3" style="background: linear-gradient(135deg, #e8e3f5 0%, #f3f0fa 100%); padding-left: 40px !important; padding-left-md: 50px !important; border-radius: 0 0 8px 8px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-11 fs-md-12 fw-700 text-dark">{{ translate('Bundle Total') }}</span>
                                    <span class="fs-13 fs-md-14 fw-700 text-primary">{{ single_price($groupTotal) }}</span>
                                </div>
                            </td>
                        </tr>
                        <!-- Spacer row -->
                        <tr>
                            <td colspan="2" style="height: 15px; border: none; background: transparent;"></td>
                        </tr>
                    @endforeach
                @endif

                <!-- Inhouse Products -->
                @if (!empty($admin_products))
                    @foreach ($admin_products as $key => $product_id)
                        @php
                            $product = get_single_product($product_id);
                            $cartItem = collect($carts)->first(function($item) use ($product_id, $admin_product_variation, $key) {
                                $itemObj = is_object($item) ? $item : (object)$item;
                                return ($itemObj->product_id ?? $itemObj['product_id']) == $product_id && 
                                       ($itemObj->variation ?? $itemObj['variation'] ?? '') == $admin_product_variation[$key];
                            });
                            if (!$cartItem) continue;
                            
                            $quantity = isset($cartItem->quantity) ? (int)$cartItem->quantity : (isset($cartItem['quantity']) ? (int)$cartItem['quantity'] : 0);
                            $unitPrice = (float)cart_product_price($cartItem, $product, false, false);
                            $unitDiscountedPrice = (float)cart_product_price($cartItem, $product, true, false);
                            $itemTotal = $unitPrice * $quantity;
                            $itemDiscountedTotal = $unitDiscountedPrice * $quantity;
                            $itemDiscount = $itemTotal - $itemDiscountedTotal;
                            // Only show discount if there's a meaningful discount AND the discounted price is greater than 0
                            // This prevents showing discount breakdown when item is free (0.00)
                            $showDiscount = ($itemDiscount > 0.01) && ($itemDiscountedTotal > 0.01);
                            
                            $product_name_with_choice = $product->getTranslation('name');
                            if (isset($cartItem->variation) && $cartItem->variation != null) {
                                $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem->variation;
                            } elseif (isset($cartItem['variation']) && $cartItem['variation'] != null) {
                                $product_name_with_choice = $product->getTranslation('name') . ' - ' . $cartItem['variation'];
                            }
                        @endphp
                        <tr class="cart_item">
                            <td class="product-name pl-0 fs-11 fs-md-12 text-dark fw-400 border-top-0 border-bottom p-2 p-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 mr-2 mr-md-3">
                                        <img src="{{ uploaded_asset($product->thumbnail_img) }}" 
                                             class="img-fit rounded-lg cart-summary-product-img" 
                                             style="object-fit: cover; border: 2px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
                                             alt="{{ $product->getTranslation('name') }}" 
                                             onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fs-11 fs-md-12 fw-600 text-dark mb-1" style="line-height: 1.3;">{{ $product_name_with_choice }}</div>
                                        <div class="fs-10 fs-md-11 text-muted">
                                            <strong>{{ translate('QTY') }}:</strong> {{ $quantity }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="product-total text-right pr-0 fs-11 fs-md-12 border-top-0 border-bottom p-2 p-md-3" style="vertical-align: middle;">
                                @if(isset($showDiscount) && $showDiscount)
                                    <div class="text-right">
                                        <div class="mb-1">
                                            <span class="text-muted text-decoration-line-through fs-10 fs-md-11">{{ single_price($itemTotal) }}</span>
                                            <span class="text-primary fw-700 ml-1 ml-md-2 fs-12 fs-md-13">{{ single_price($itemDiscountedTotal) }}</span>
                                        </div>
                                        <div class="fs-10 fs-md-11 text-success fw-600">-{{ single_price($itemDiscount) }}</div>
                                    </div>
                                @else
                                    <span class="text-primary fw-700 fs-12 fs-md-13">{{ single_price($itemTotal) }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif

                <!-- Seller Products -->
                @if (!empty($seller_products))
                    @foreach ($seller_products as $seller_id => $product_ids)
                        @foreach ($product_ids as $key => $product_id)
                            @php
                                $product = get_single_product($product_id);
                                $variation = $seller_product_variation[$seller_id][$key] ?? '';
                                $cartItem = collect($carts)->first(function($item) use ($product_id, $variation) {
                                    $itemObj = is_object($item) ? $item : (object)$item;
                                    return ($itemObj->product_id ?? $itemObj['product_id']) == $product_id && 
                                           ($itemObj->variation ?? $itemObj['variation'] ?? '') == $variation;
                                });
                                if (!$cartItem) continue;
                                
                                $quantity = isset($cartItem->quantity) ? (int)$cartItem->quantity : (isset($cartItem['quantity']) ? (int)$cartItem['quantity'] : 0);
                                $unitPrice = (float)cart_product_price($cartItem, $product, false, false);
                                $unitDiscountedPrice = (float)cart_product_price($cartItem, $product, true, false);
                                $itemTotal = $unitPrice * $quantity;
                                $itemDiscountedTotal = $unitDiscountedPrice * $quantity;
                                $itemDiscount = $itemTotal - $itemDiscountedTotal;
                                // Only show discount if there's a meaningful discount AND the discounted price is greater than 0
                                // This prevents showing discount breakdown when item is free (0.00)
                                $showDiscount = ($itemDiscount > 0.01) && ($itemDiscountedTotal > 0.01);
                                
                                $product_name_with_choice = $product->getTranslation('name');
                                if ($variation != '') {
                                    $product_name_with_choice = $product->getTranslation('name') . ' - ' . $variation;
                                }
                            @endphp
                            <tr class="cart_item">
                                <td class="product-name pl-0 fs-11 fs-md-12 text-dark fw-400 border-top-0 border-bottom p-2 p-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 mr-2 mr-md-3">
                                            <img src="{{ uploaded_asset($product->thumbnail_img) }}" 
                                                 class="img-fit rounded-lg cart-summary-product-img" 
                                                 style="object-fit: cover; border: 2px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"
                                                 alt="{{ $product->getTranslation('name') }}" 
                                                 onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="fs-11 fs-md-12 fw-600 text-dark mb-1" style="line-height: 1.3;">{{ $product_name_with_choice }}</div>
                                            <div class="fs-10 fs-md-11 text-muted">
                                                <strong>{{ translate('QTY') }}:</strong> {{ $quantity }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="product-total text-right pr-0 fs-11 fs-md-12 border-top-0 border-bottom p-2 p-md-3" style="vertical-align: middle;">
                                    @if(isset($showDiscount) && $showDiscount)
                                        <div class="text-right">
                                            <div class="mb-1">
                                                <span class="text-muted text-decoration-line-through fs-10 fs-md-11">{{ single_price($itemTotal) }}</span>
                                                <span class="text-primary fw-700 ml-1 ml-md-2 fs-12 fs-md-13">{{ single_price($itemDiscountedTotal) }}</span>
                                            </div>
                                            <div class="fs-10 fs-md-11 text-success fw-600">-{{ single_price($itemDiscount) }}</div>
                                        </div>
                                    @else
                                        <span class="text-primary fw-700 fs-12 fs-md-13">{{ single_price($itemTotal) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
        </div>

        <input type="hidden" id="sub_total" value="{{ $subtotal }}">

        <table class="table" style="margin-top: 2rem!important;">
            <tfoot>
                <!-- Subtotal -->
                <tr class="cart-subtotal">
                    <th class="pl-0 fs-12 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Subtotal') }}</th>
                    <td class="text-right pr-0 fs-12 pt-0 pb-2 fw-600 text-primary border-top-0">
                        <span class="fw-600">{{ single_price($subtotal) }}</span>
                    </td>
                </tr>
                <!-- Tax -->
                <tr class="cart-shipping">
                    <th class="pl-0 fs-12 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Tax') }}</th>
                    <td class="text-right pr-0 fs-12 pt-0 pb-2 fw-600 text-primary border-top-0">
                        <span class="fw-600">{{ single_price($tax) }}</span>
                    </td>
                </tr>
                <!-- Total Shipping -->
                <tr class="cart-shipping">
                    <th class="pl-0 fs-12 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Total Shipping') }}</th>
                    <td class="text-right pr-0 fs-12 pt-0 pb-2 fw-600 text-primary border-top-0">
                        <span class="fw-600">{{ single_price($shipping) }}</span>
                    </td>
                </tr>
                <!-- Redeem point -->
                @if (Session::has('club_point'))
                    <tr class="cart-shipping">
                        <th class="pl-0 fs-12 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Redeem point') }}</th>
                        <td class="text-right pr-0 fs-12 pt-0 pb-2 fw-600 text-primary border-top-0">
                            <span class="fw-600">{{ single_price(Session::get('club_point')) }}</span>
                        </td>
                    </tr>
                @endif
                <!-- Coupon Discount -->
                @if ($coupon_discount > 0)
                    <tr class="cart-shipping">
                        <th class="pl-0 fs-12 pt-0 pb-2 text-dark fw-600 border-top-0">{{ translate('Coupon Discount') }}</th>
                        <td class="text-right pr-0 fs-12 pt-0 pb-2 fw-600 text-primary border-top-0">
                            <span class="fw-600">{{ single_price($coupon_discount) }}</span>
                        </td>
                    </tr>
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
                <tr class="cart-total">
                    <th class="pl-0 fs-13 text-dark fw-600"><span class="strong-600">{{ translate('Total') }}</span></th>
                    <td class="text-right pr-0 fs-13 fw-600 text-primary">
                        <strong><span>{{ single_price($total) }}</span></strong>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Coupon System -->
        @if (get_setting('coupon_system') == 1)
            @if ($coupon_discount > 0 && $coupon_code)
                <div class="mt-3">
                    <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <div class="form-control">{{ $coupon_code }}</div>
                            <div class="input-group-append">
                                <button type="button" id="coupon-remove"
                                    class="btn btn-primary">{{ translate('Change Coupon') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-3">
                    <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                        <div class="input-group">
                            <input type="text" class="form-control rounded-0" name="code"
                                onkeydown="return event.key != 'Enter';"
                                placeholder="{{ translate('Have coupon code? Apply here') }}" required>
                            <div class="input-group-append">
                                <button type="button" id="coupon-apply"
                                    class="btn btn-primary rounded-0">{{ translate('Apply') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        @endif

    </div> <!-- End card-body -->
    </div> <!-- End p-3 -->
</div>
