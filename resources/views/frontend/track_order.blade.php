@extends('frontend.layouts.app')

@section('content')
<style>
    /* Mobile Responsive Styles for Track Order */
    @media (max-width: 767px) {
        /* Compact order summary */
        .track-order-summary {
            padding: 12px !important;
        }
        
        .track-order-summary table {
            font-size: 13px !important;
        }
        
        .track-order-summary td {
            padding: 8px 4px !important;
        }
        
        /* Compact product cards */
        .track-product-card {
            padding: 12px !important;
        }
        
        /* Smaller product images */
        .track-product-img {
            width: 60px !important;
            height: 60px !important;
        }
        
        .track-bundle-img {
            width: 60px !important;
            height: 60px !important;
        }
        
        .track-bundle-badge {
            width: 20px !important;
            height: 20px !important;
            font-size: 9px !important;
        }
        
        .track-bundle-item-img {
            width: 40px !important;
            height: 40px !important;
        }
        
        /* Smaller font sizes */
        .track-product-title {
            font-size: 13px !important;
        }
        
        .track-price-text {
            font-size: 14px !important;
        }
        
        /* Compact bundle contents */
        .track-bundle-contents {
            padding: 10px !important;
            margin: 0 -12px -12px -12px !important;
        }
        
        @media (min-width: 768px) {
            .track-bundle-contents {
                margin: 0 -16px -16px -16px !important;
                padding: 16px 16px 0 16px !important;
            }
        }
        
        .track-bundle-item {
            padding: 8px !important;
        }
        
        /* Bundle item product name - allow wrapping on mobile */
        .track-bundle-item h6.text-truncate,
        .track-bundle-item h6 {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: clip !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            display: block !important;
            line-height: 1.4 !important;
            margin-bottom: 8px !important;
            max-width: 100% !important;
        }
        
        @media (min-width: 768px) {
            .track-bundle-item h6.text-truncate {
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }
        }
        
        /* Bundle item layout - stack vertically on mobile */
        .track-bundle-item .d-flex.justify-content-between {
            flex-direction: column !important;
        }
        
        .track-bundle-item .text-right,
        .track-bundle-item-price-section {
            margin-top: 8px !important;
            text-align: left !important;
            width: 100% !important;
        }
        
        @media (min-width: 768px) {
            .track-bundle-item-price-section {
                width: auto !important;
                text-align: right !important;
            }
        }
        
        /* Ensure bundle item container doesn't overflow */
        .track-bundle-item {
            width: 100% !important;
            max-width: 100% !important;
            overflow: visible !important;
        }
        
        .track-bundle-item .flex-grow-1 {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
        }
        
        /* Responsive table */
        .track-order-summary .table {
            font-size: 12px !important;
        }
        
        .track-order-summary td {
            padding: 6px 4px !important;
            font-size: 12px !important;
        }
        
        .track-order-summary .fw-600 {
            font-size: 12px !important;
        }
        
        /* Compact badges */
        .track-badge {
            font-size: 9px !important;
            padding: 2px 6px !important;
        }
    }
</style>
    <section class="pt-4 mb-4">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-700 fs-20 fs-md-24 text-dark">{{ translate('Track Order') }}</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            "{{ translate('Track Order') }}"
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="mb-5">
        <div class="container text-left">
            <div class="row">
                <div class="col-xxl-5 col-xl-6 col-lg-8 mx-auto">
                    <form class="" action="{{ route('orders.track') }}" method="GET" enctype="multipart/form-data">
                        <div class="bg-white border rounded-0">
                            <div class="fs-15 fw-600 p-3 border-bottom text-center">
                                {{ translate('Check Your Order Status')}}
                            </div>
                            <div class="form-box-content p-3">
                                <div class="form-group">
                                    <input type="text" class="form-control rounded-0 mb-3" placeholder="{{ translate('Order Code')}}" name="order_code" required>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary rounded-0 w-150px">{{ translate('Track Order')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @isset($order)
                <div class="bg-white border rounded-0 mt-5">
                    <div class="fs-15 fw-600 p-3">
                        {{ translate('Order Summary')}}
                    </div>
                    <div class="p-3 track-order-summary">
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Order Code')}}:</td>
                                        <td>{{ $order->code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Customer')}}:</td>
                                        <td>{{ json_decode($order->shipping_address)->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Email')}}:</td>
                                        @if ($order->user_id != null)
                                            <td>{{ $order->user->email }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Shipping address')}}:</td>
                                        <td>
                                            @php
                                                $shipping_address = json_decode($order->shipping_address);
                                            @endphp
                                            @if($shipping_address)
                                                {{ $shipping_address->address ?? '' }}
                                                @if(isset($shipping_address->city) && $shipping_address->city)
                                                    , {{ $shipping_address->city }}
                                                @endif
                                                @if(isset($shipping_address->state) && $shipping_address->state)
                                                    , {{ $shipping_address->state }}
                                                @endif
                                                @if(isset($shipping_address->postal_code) && $shipping_address->postal_code)
                                                    , {{ $shipping_address->postal_code }}
                                                @endif
                                                @if(isset($shipping_address->country) && $shipping_address->country)
                                                    , {{ $shipping_address->country }}
                                                @endif
                                            @else
                                                {{ translate('N/A') }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Order date')}}:</td>
                                        <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Total order amount')}}:</td>
                                        <td>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Shipping method')}}:</td>
                                        <td>{{ translate('Flat shipping rate')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Payment method')}}:</td>
                                        <td>{{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Delivery Status')}}:</td>
                                        <td>{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</td>
                                    </tr>
                                    @if ($order->tracking_code)
                                        <tr>
                                            <td class="w-50 fw-600">{{ translate('Tracking code')}}:</td>
                                            <td>{{ $order->tracking_code }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                @php
                    // Group order details: First group by combo_id, then by combination_hash within each combo
                    $groupedDetails = [];
                    $ungroupedDetails = [];
                    
                    if ($order->orderDetails && $order->orderDetails->count() > 0) {
                        // First pass: collect all items with combo_id
                        $itemsByCombo = [];
                        foreach ($order->orderDetails as $orderDetail) {
                            $comboId = isset($orderDetail->combo_id) ? $orderDetail->combo_id : null;
                            
                            if ($comboId != null) {
                                if (!isset($itemsByCombo[$comboId])) {
                                    $itemsByCombo[$comboId] = [];
                                }
                                $itemsByCombo[$comboId][] = $orderDetail;
                            } else {
                                $ungroupedDetails[] = $orderDetail;
                            }
                        }
                        
                        // Second pass: within each combo_id, group by hash
                        foreach ($itemsByCombo as $comboId => $items) {
                            $groupProduct = null;
                            try {
                                $groupProduct = \App\Models\GroupProduct::find($comboId);
                            } catch (\Exception $e) {
                                // Group product not found
                            }
                            
                            // Group items by combination hash
                            $itemsByHash = [];
                            foreach ($items as $item) {
                                $hash = isset($item->group_product_slot_combination_hash) && $item->group_product_slot_combination_hash != null 
                                    ? $item->group_product_slot_combination_hash 
                                    : null;
                                
                                $hashKey = $hash ? $hash : 'no_hash';
                                
                                if (!isset($itemsByHash[$hashKey])) {
                                    $itemsByHash[$hashKey] = [];
                                }
                                $itemsByHash[$hashKey][] = $item;
                            }
                            
                            // Create grouped details for each hash group within this combo
                            foreach ($itemsByHash as $hashKey => $hashItems) {
                                $groupKey = $comboId . '_' . $hashKey;
                                
                                $groupedDetails[$groupKey] = [
                                    'group_product' => $groupProduct,
                                    'items' => $hashItems
                                ];
                            }
                        }
                    }
                @endphp

                <!-- Group Products (Bundles) -->
                @if (!empty($groupedDetails))
                    @foreach ($groupedDetails as $groupKey => $groupData)
                        @php
                            $groupProduct = $groupData['group_product'];
                            $groupItems = $groupData['items'];
                            $groupItemsCollection = collect($groupItems);
                            $groupTotalPrice = $groupItemsCollection->sum(function($item) { return $item->price * $item->quantity; });
                            $groupTotalQuantity = $groupItemsCollection->sum('quantity');
                        @endphp
                        <div class="bg-white border rounded-0 mt-4 track-product-card" style="border-radius: 12px !important; box-shadow: 0 2px 12px rgba(0,0,0,0.06); overflow: hidden;">
                            <div class="p-3 p-md-4">
                                <!-- Bundle Header -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-6 d-flex align-items-center mb-3 mb-md-0">
                                        <div class="position-relative mr-3 flex-shrink-0">
                                            <img src="{{ $groupProduct ? uploaded_asset($groupProduct->thumbnail_img ?? 'assets/img/placeholder.jpg') : static_asset('assets/img/placeholder.jpg') }}"
                                                class="img-fit rounded-lg track-bundle-img"
                                                style="width: 90px; height: 90px; object-fit: cover; border: 3px solid #f8f9fa;"
                                                alt="{{ $groupProduct ? $groupProduct->name : translate('Bundle') }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            <span class="badge badge-primary position-absolute rounded-circle d-flex align-items-center justify-content-center track-bundle-badge" 
                                                style="top: -5px; right: -5px; width: 28px; height: 28px; padding: 0; font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                                <i class="las la-gift"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <h6 class="track-product-title fs-16 fw-700 text-dark mb-1 text-truncate-2" style="line-height: 1.3;">
                                                {{ $groupProduct ? $groupProduct->name : translate('Bundle') }}
                                            </h6>
                                            <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                                                <span style="width: auto;" class="badge badge-soft-primary track-badge fs-11 px-2 py-1">
                                                    <i class="las la-box mr-1"></i>{{ count($groupItems) }} {{ translate('items') }}
                                                </span>
                                                <span style="width: auto;" class="badge badge-soft-info track-badge fs-11 px-2 py-1">
                                                    <i class="las la-layer-group mr-1"></i>{{ translate('Bundle') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 text-md-right">
                                        <div class="fs-12 text-muted mb-1">{{ translate('Total') }}</div>
                                        <span class="fw-700 track-price-text fs-18 text-primary">{{ single_price($groupTotalPrice) }}</span>
                                    </div>
                                </div>
                                
                                <!-- Bundle Items List -->
                                <div class="px-3 px-md-4 pb-0 track-bundle-contents" style="background: #f8f9fa; border-top: 1px solid #e9ecef; margin: 0 -12px -12px -12px; padding: 12px 12px 0 12px;">
                                    <div class="d-flex align-items-center justify-content-between mb-2 mb-md-3">
                                        <h6 class="fs-13 fw-700 text-dark mb-0">
                                            <i class="las la-list text-primary mr-1"></i>{{ translate('Bundle Contents') }} ({{ count($groupItems) }})
                                        </h6>
                                    </div>
                                    <div class="row gutters-10">
                                        @foreach ($groupItems as $index => $orderDetail)
                                            @php
                                                $product = $orderDetail->product;
                                                $slot = $orderDetail->groupProductSlot ?? null;
                                            @endphp
                                            @if ($product)
                                                <div class="col-12 mb-2">
                                                    <div class="d-flex align-items-start p-3 rounded-lg track-bundle-item" 
                                                        style="background: #ffffff; border: 1px solid #e9ecef; transition: all 0.2s;">
                                                        <!-- Product Image -->
                                                        <div class="flex-shrink-0 mr-3">
                                                            <img src="{{ static_asset('assets/img/placeholder.jpg') }}" 
                                                                 data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                                 class="lazyload img-fit rounded track-bundle-item-img" 
                                                                 style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #f8f9fa;"
                                                                 alt="{{ $product->getTranslation('name') }}" 
                                                                 onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                        </div>
                                                        
                                                        <!-- Product Details -->
                                                        <div class="flex-grow-1 min-w-0" style="width: 100%; max-width: 100%;">
                                                            <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-start">
                                                                <div class="flex-grow-1 min-w-0 pr-0 pr-md-2 mb-2 mb-md-0" style="width: 100%;">
                                                                    <h6 class="fs-12 fs-md-13 fw-600 text-dark mb-1 text-truncate" style="line-height: 1.4; word-wrap: break-word; overflow-wrap: break-word;">
                                                                        {{ $product->getTranslation('name') }}
                                                                    </h6>
                                                                    <div class="d-flex flex-wrap" style="gap: 4px;">
                                                                        @if ($slot)
                                                                            <span style="width: auto;" class="badge badge-soft-info track-badge fs-9 fs-md-10 px-2 py-1" style="background: #d1ecf1; color: #0c5460; border: none;">
                                                                                <i class="las la-tag mr-1"></i>{{ translate('Slot') }}: {{ $slot->name }}
                                                                            </span>
                                                                        @endif
                                                                        @if ($orderDetail->variation)
                                                                            <span style="width: auto;" class="badge badge-soft-secondary track-badge fs-9 fs-md-10 px-2 py-1" style="background: #e2e3e5; color: #383d41; border: none;">
                                                                                <i class="las la-palette mr-1"></i>{{ translate('Variation') }}: {{ $orderDetail->variation }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Item Price & Quantity -->
                                                                <div class="text-left text-md-right flex-shrink-0 track-bundle-item-price-section">
                                                                    <div class="fs-11 fs-md-12 text-muted mb-1">{{ translate('Qty') }}: <span class="fw-600 text-dark">{{ $orderDetail->quantity }}</span></div>
                                                                    <div class="fs-12 fs-md-13 fw-600 text-primary">{{ single_price($orderDetail->price) }}</div>
                                                                    @if ($orderDetail->quantity > 1)
                                                                        <div class="fs-10 fs-md-11 text-muted">× {{ $orderDetail->quantity }} = {{ single_price($orderDetail->price * $orderDetail->quantity) }}</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    <!-- Shipped By Info -->
                                    @if (count($groupItems) > 0 && $groupItems[0]->product && $groupItems[0]->product->user)
                                        <div class="d-flex justify-content-end mt-2 mb-2 pt-2" style="border-top: 1px solid #dee2e6;">
                                            <span class="fs-12 text-muted">
                                                <i class="las la-shipping-fast mr-1"></i>{{ translate('Shipped By') }}: <strong>{{ $groupItems[0]->product->user->name }}</strong>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Non-Group Products -->
                @if (!empty($ungroupedDetails))
                    @foreach ($ungroupedDetails as $orderDetail)
                        @if($orderDetail->product != null)
                            <div class="bg-white border rounded-0 mt-4 track-product-card" style="border-radius: 10px !important; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                <div class="p-3 p-md-4">
                                    <div class="row align-items-center">
                                        <!-- Product Image & Name -->
                                        <div class="col-12 col-md-6 d-flex align-items-center mb-3 mb-md-0">
                                            <div class="mr-3 flex-shrink-0">
                                                <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                                    class="img-fit rounded-lg track-product-img"
                                                    style="width: 85px; height: 85px; object-fit: cover; border: 3px solid #f8f9fa;"
                                                    alt="{{ $orderDetail->product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <h6 class="track-product-title fs-15 fw-600 text-dark text-truncate-2 mb-2" style="line-height: 1.4;">
                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                </h6>
                                                @if ($orderDetail->variation)
                                                    <span style="width: auto;" class="badge badge-soft-secondary track-badge fs-11 px-2 py-1" style="background: #e2e3e5; color: #383d41; border: none;">
                                                        <i class="las la-palette mr-1"></i>{{ $orderDetail->variation }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Product Details -->
                                        <div class="col-12 col-md-6">
                                            <div class="row text-md-right">
                                                <div class="col-6 col-md-12 mb-2 mb-md-0">
                                                    <div class="fs-12 text-muted mb-1">{{ translate('Quantity') }}</div>
                                                    <span class="fw-700 fs-16 text-dark">{{ $orderDetail->quantity }}</span>
                                                </div>
                                                <div class="col-6 col-md-12 mb-2 mb-md-0">
                                                    <div class="fs-12 text-muted mb-1">{{ translate('Price') }}</div>
                                                    <span class="fw-700 track-price-text fs-17 text-primary">{{ single_price($orderDetail->price) }}</span>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <div class="fs-12 text-muted mb-1">{{ translate('Shipped By') }}</div>
                                                    <span class="fw-600 fs-14 text-dark">{{ $orderDetail->product->user->name ?? translate('N/A') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

            @endisset
        </div>
    </section>
@endsection
