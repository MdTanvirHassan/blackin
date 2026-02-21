@extends('frontend.layouts.app')

@section('content')

    <!-- Steps -->
    <section class="pt-5 mb-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32.001" viewBox="0 0 32 32.001" class="cart-rotate mb-3 mt-1">
                                    <g id="Group_23976" data-name="Group 23976" transform="translate(-282 -404.889)">
                                      <path class="cart-ok has-transition" id="Path_28723" data-name="Path 28723" d="M313.283,409.469a1,1,0,0,0-1.414,0l-14.85,14.85-5.657-5.657a1,1,0,1,0-1.414,1.414l6.364,6.364a1,1,0,0,0,1.414,0l.707-.707,14.85-14.849A1,1,0,0,0,313.283,409.469Z" fill="#ffffff"/>
                                      <g id="LWPOLYLINE">
                                        <path id="Path_28724" data-name="Path 28724" d="M313.372,416.451,311.72,418.1a14,14,0,1,1-5.556-8.586l1.431-1.431a16,16,0,1,0,5.777,8.365Z" fill="#d43533"/>
                                      </g>
                                    </g>
                                </svg>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Confirmation -->
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    @php
                        $first_order = $combined_order->orders->first();
                        $shipping_address = [];
                        
                        if ($first_order) {
                            // Handle shipping_address - it might be JSON string, array, or object
                            $shipping_address = $first_order->shipping_address;
                            if (is_string($shipping_address)) {
                                $shipping_address = json_decode($shipping_address, true);
                            }
                            // If it's still an object, convert to array
                            if (is_object($shipping_address)) {
                                $shipping_address = (array) $shipping_address;
                            }
                            // Ensure it's an array
                            if (!is_array($shipping_address)) {
                                $shipping_address = [];
                            }
                        }
                    @endphp
                    <!-- Order Confirmation Text-->
                    <div class="text-center py-4 mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" class=" mb-3">
                            <g id="Group_23983" data-name="Group 23983" transform="translate(-978 -481)">
                              <circle id="Ellipse_44" data-name="Ellipse 44" cx="18" cy="18" r="18" transform="translate(978 481)" fill="#85b567"/>
                              <g id="Group_23982" data-name="Group 23982" transform="translate(32.439 8.975)">
                                <rect id="Rectangle_18135" data-name="Rectangle 18135" width="11" height="3" rx="1.5" transform="translate(955.43 487.707) rotate(45)" fill="#fff"/>
                                <rect id="Rectangle_18136" data-name="Rectangle 18136" width="3" height="18" rx="1.5" transform="translate(971.692 482.757) rotate(45)" fill="#fff"/>
                              </g>
                            </g>
                        </svg>
                        <h1 class="mb-2 fs-28 fw-500 text-success">{{ translate('Thank You for Your Order!')}}</h1>
                        <p class="fs-13 text-soft-dark">{{  translate('A copy or your order summary has been sent to') }} <strong>{{ $shipping_address['email'] ?? ($first_order->user->email ?? ($combined_order->user->email ?? '')) }}</strong></p>
                    </div>
                    <!-- Order Summary -->
                    <div class="mb-4 bg-white p-4 border">
                        <h5 class="fw-600 mb-3 fs-16 text-soft-dark pb-2 border-bottom">{{ translate('Order Summary')}}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table fs-14 text-soft-dark">
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Order date')}}:</td>
                                        <td class="border-top-0 py-2">{{ $first_order ? date('d-m-Y H:i A', $first_order->date) : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Name')}}:</td>
                                        <td class="border-top-0 py-2">{{ $shipping_address['name'] ?? ($first_order && $first_order->user ? $first_order->user->name : ($combined_order->user ? $combined_order->user->name : '')) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Email')}}:</td>
                                        <td class="border-top-0 py-2">{{ $shipping_address['email'] ?? ($first_order && $first_order->user ? $first_order->user->email : ($combined_order->user ? $combined_order->user->email : '')) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Shipping address')}}:</td>
                                        <td class="border-top-0 py-2">
                                            {{ $shipping_address['address'] ?? '' }}
                                            @if(isset($shipping_address['city']))
                                                , {{ $shipping_address['city'] }}
                                            @endif
                                            @if(isset($shipping_address['country']))
                                                , {{ $shipping_address['country'] }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Order status')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ $first_order ? translate(ucfirst(str_replace('_', ' ', $first_order->delivery_status))) : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Total order amount')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ single_price($combined_order->grand_total ?? 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Shipping')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ translate('Flat shipping rate')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Payment method')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ $first_order ? translate(ucfirst(str_replace('_', ' ', $first_order->payment_type))) : '' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Info -->
                    @if($combined_order->orders && $combined_order->orders->count() > 0)
                    @php
                        // Ensure unique orders by ID to prevent duplicates
                        $uniqueOrders = $combined_order->orders->unique('id');
                    @endphp
                    @foreach ($uniqueOrders as $order)
                        <div class="card shadow-none border rounded-0">
                            <div class="card-body">
                                <!-- Order Code -->
                                <div class="text-center py-1 mb-4">
                                    <h2 class="h5 fs-20">{{ translate('Order Code:')}} <span class="fw-700 text-primary">{{ $order->code }}</span></h2>
                                </div>
                                <!-- Order Details -->
                                <div>
                                    <h5 class="fw-600 text-soft-dark mb-3 fs-16 pb-2">{{ translate('Order Details')}}</h5>
                                    <!-- Product Details -->
                                    <div>
                                        @php
                                            // Group order details: First group by combo_id, then by combination_hash within each combo
                                            // This ensures all items from the same bundle are together
                                            $groupedDetails = [];
                                            $ungroupedDetails = [];
                                            
                                            // Check if orderDetails exists and is not empty
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
                                                        
                                                        // Use hash if available, otherwise use 'default' to group items without hash together
                                                        $hashKey = $hash ? $hash : 'no_hash';
                                                        
                                                        if (!isset($itemsByHash[$hashKey])) {
                                                            $itemsByHash[$hashKey] = [];
                                                        }
                                                        $itemsByHash[$hashKey][] = $item;
                                                    }
                                                    
                                                    // Create grouped details for each hash group within this combo
                                                    foreach ($itemsByHash as $hashKey => $hashItems) {
                                                        // Create unique group key: combo_id + hash (or combo_id + 'no_hash')
                                                        $groupKey = $comboId . '_' . $hashKey;
                                                        
                                                        $groupedDetails[$groupKey] = [
                                                            'group_product' => $groupProduct,
                                                            'items' => $hashItems
                                                        ];
                                                    }
                                                }
                                            }
                                            
                                            $itemCounter = 0;
                                        @endphp
                                        
                                        <table class="table table-responsive-md text-soft-dark fs-14">
                                            <thead>
                                                <tr>
                                                    <th class="opacity-60 border-top-0 pl-0">#</th>
                                                    <th class="opacity-60 border-top-0" width="30%">{{ translate('Product')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Variation')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Quantity')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Delivery Type')}}</th>
                                                    <th class="text-right opacity-60 border-top-0 pr-0">{{ translate('Price')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- Display grouped products (bundles) --}}
                                                @foreach ($groupedDetails as $hash => $group)
                                                    @php
                                                        $groupProduct = $group['group_product'];
                                                        $groupItems = $group['items'];
                                                        // Convert to collection or use array functions
                                                        $groupItemsCollection = collect($groupItems);
                                                        $groupTotalPrice = $groupItemsCollection->sum('price');
                                                        $groupTotalQuantity = $groupItemsCollection->sum('quantity');
                                                    @endphp
                                                    
                                                    {{-- Group Product Header --}}
                                                    <tr class="bg-light">
                                                        <td class="border-top-0 border-bottom pl-0 fw-600">{{ ++$itemCounter }}</td>
                                                        <td class="border-top-0 border-bottom fw-600" colspan="4">
                                                            <i class="las la-box text-primary"></i>
                                                            <strong>{{ $groupProduct ? $groupProduct->name : translate('Bundle') }}</strong>
                                                            <span class="text-muted fs-12">({{ translate('Bundle') }})</span>
                                                        </td>
                                                        <td class="border-top-0 border-bottom pr-0 text-right fw-600">{{ single_price($groupTotalPrice) }}</td>
                                                    </tr>
                                                    
                                                    {{-- Individual products in the bundle --}}
                                                    @foreach ($groupItems as $orderDetail)
                                                        <tr class="bundle-item">
                                                            <td class="border-top-0 border-bottom pl-4">
                                                                <span class="text-muted">└─</span>
                                                            </td>
                                                            <td class="border-top-0 border-bottom pl-0">
                                                                @if ($orderDetail->product != null)
                                                                    <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-reset">
                                                                        {{ $orderDetail->product->getTranslation('name') }}
                                                                    </a>
                                                                @else
                                                                    <strong>{{ translate('Product Unavailable') }}</strong>
                                                                @endif
                                                            </td>
                                                            <td class="border-top-0 border-bottom">
                                                                {{ $orderDetail->variation }}
                                                            </td>
                                                            <td class="border-top-0 border-bottom">
                                                                {{ $orderDetail->quantity }}
                                                            </td>
                                                            <td class="border-top-0 border-bottom">
                                                                @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                                                    {{ translate('Home Delivery') }}
                                                                @elseif ($order->shipping_type != null && $order->shipping_type == 'carrier')
                                                                    {{ translate('Carrier') }}
                                                                @elseif ($order->shipping_type == 'pickup_point')
                                                                    @if ($order->pickup_point != null)
                                                                        {{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickup Point') }})
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->price) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                                
                                                {{-- Display ungrouped products (regular products) --}}
                                                @foreach ($ungroupedDetails as $orderDetail)
                                                    <tr>
                                                        <td class="border-top-0 border-bottom pl-0">{{ ++$itemCounter }}</td>
                                                        <td class="border-top-0 border-bottom">
                                                            @if ($orderDetail->product != null)
                                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-reset">
                                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                                </a>
                                                            @else
                                                                <strong>{{ translate('Product Unavailable') }}</strong>
                                                            @endif
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ $orderDetail->variation }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ $orderDetail->quantity }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                                                {{ translate('Home Delivery') }}
                                                            @elseif ($order->shipping_type != null && $order->shipping_type == 'carrier')
                                                                {{ translate('Carrier') }}
                                                            @elseif ($order->shipping_type == 'pickup_point')
                                                                @if ($order->pickup_point != null)
                                                                    {{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickup Point') }})
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->price) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Order Amounts -->
                                    <div class="row">
                                        <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                            <table class="table ">
                                                <tbody>
                                                    <!-- Subtotal -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Subtotal')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span class="fw-600">{{ single_price($order->orderDetails->sum('price')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Shipping -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Shipping')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Tax -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Tax')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Coupon Discount -->
                                                    @if($order->coupon_discount > 0)
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Coupon Discount')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->coupon_discount ?? 0) }}</span>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <!-- Total -->
                                                    <tr>
                                                        <th class="py-2"><span class="fw-600">{{ translate('Total')}}</span></th>
                                                        <td class="text-right pr-0">
                                                            <strong><span>{{ single_price($order->grand_total) }}</span></strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                    @else
                        <div class="card shadow-none border rounded-0">
                            <div class="card-body text-center py-5">
                                <p class="text-muted">{{ translate('No orders found.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@section('style')
    <style>
        .bundle-item {
            background-color: #f8f9fa;
        }
        .bundle-item td {
            border-top: 1px solid #e9ecef !important;
        }
        .bg-light {
            background-color: #e9ecef !important;
        }
    </style>
@endsection

@section('script')
    @if (get_setting('facebook_pixel') == 1)
    <!-- Facebook Pixel purchase Event -->
    <script>
        $(document).ready(function(){
            var currend_code = '{{ get_system_currency()->code }}';
            var amount = {{ $combined_order->grand_total ?? 0 }};
            fbq('track', 'Purchase',
                {
                    value: amount,
                    currency: currend_code,
                    content_type: 'product'
                }
            );
        });
    </script>
    <!-- Facebook Pixel purchase Event -->
    @endif
@endsection
        
