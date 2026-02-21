 @foreach($orders as $order)

 <div class="mb-4">


     <div class="row align-items-center mb-3">


         <div class="col-md-12">
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
             
             {{-- Display grouped products (bundles) --}}
             @foreach ($groupedDetails as $hash => $group)
                 @php
                     $groupProduct = $group['group_product'];
                     $groupItems = collect($group['items']); // Convert to collection
                     $groupTotalPrice = $groupItems->sum('price');
                 @endphp
                 
                 @foreach ($groupItems as $orderDetail)
                     @if($orderDetail->reviewed==0)
                         @if ($itemCounter > 0)
                         <hr class="hr-split">
                         @endif
                         
                         {{-- Group Product Header (only show once per bundle) --}}
                         @if ($loop->first)
                         <div class="row mb-2 bg-light p-2 rounded">
                             <div class="col-md-7 d-flex align-items-center">
                                 <i class="las la-box text-primary mr-2 fs-18"></i>
                                 <div class="w-300px text-wrap">
                                     <div class="font-weight-semibold fs-14 product-name-color mobile-title-shift">
                                         <strong>{{ $groupProduct ? $groupProduct->name : translate('Bundle') }}</strong>
                                         <span class="text-muted fs-12">({{ translate('Bundle') }})</span>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-md-3">
                                 <div class="font-weight-bold">{{ single_price($groupTotalPrice) }}</div>
                             </div>
                             <div class="col-md-2"></div>
                         </div>
                         @endif
                         
                         <div class="row bundle-item pl-4">
                             <div class="col-md-7 d-flex align-items-center">
                                 <span class="text-muted mr-2">└─</span>
                                 <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                     class="img-fluid mr-3 product-history-img" style="max-width: 50px;">

                                 <div class="w-300px text-wrap">
                                     <div class="font-weight-semibold fs-14 product-name-color mobile-title-shift text-truncate-2"
                                         title="{{ $orderDetail->product->getTranslation('name') }}">
                                         {{ $orderDetail->product->getTranslation('name') }}
                                     </div>
                                     <div class="text-muted small mb-2 mobile-title-shift">{{ $orderDetail->variation }}</div>
                                 </div>
                             </div>

                             <div class="col-md-3">
                                 <div class="font-weight-bold">{{ single_price($orderDetail->price) }}</div>
                                 <div class="text-muted small">Qty {{ $orderDetail->quantity }}</div>
                             </div>

                             <div class="col-md-2">
                                 <a href="javascript:void(0);"
                                     onclick="product_review('{{ $orderDetail->product_id }}')"
                                     class="btn btn-primary btn-sm rounded-pill"> {{ translate('Review') }} </a>
                             </div>
                         </div>

                         <hr class="hr-split">
                         <hr>
                         @php $itemCounter++; @endphp
                     @endif
                 @endforeach
             @endforeach
             
             {{-- Display ungrouped products (regular products) --}}
             @foreach ($ungroupedDetails as $orderDetail)
                 @if($orderDetail->reviewed==0)
                     @if ($itemCounter > 0)
                     <hr class="hr-split">
                     @endif
                     <div class="row">
                         <div class="col-md-7 d-flex align-items-center">
                             <img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"
                                 class="img-fluid mr-3 product-history-img">

                             <div class="w-300px text-wrap">
                                 <div class="font-weight-semibold fs-14 product-name-color mobile-title-shift text-truncate-2"
                                     title="{{ $orderDetail->product->getTranslation('name') }}">
                                     {{ $orderDetail->product->getTranslation('name') }}
                                 </div>
                                 <div class="text-muted small mb-2 mobile-title-shift">{{ $orderDetail->variation }}</div>
                             </div>
                         </div>

                         <div class="col-md-3">
                             <div class="font-weight-bold">{{ single_price($orderDetail->price) }}</div>
                             <div class="text-muted small">Qty {{ $orderDetail->quantity }}</div>
                         </div>

                         <div class="col-md-2">
                             <a href="javascript:void(0);"
                                 onclick="product_review('{{ $orderDetail->product_id }}')"
                                 class="btn btn-primary btn-sm rounded-pill"> {{ translate('Review') }} </a>
                         </div>
                     </div>

                     <hr class="hr-split">
                     <hr>
                     @php $itemCounter++; @endphp
                 @endif
             @endforeach
         </div>
     </div>


 </div>

 @endforeach