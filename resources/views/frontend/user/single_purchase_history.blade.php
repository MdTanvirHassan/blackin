  @foreach($orders as $order)
  <div class="mb-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
          <div>
              <p class="mb-2 fs-16 fw-700 deep-blue mb-0"><a class="deep-blue" href="{{route('purchase_history.details', encrypt($order->id))}}">{{ translate('Order ID')}} - {{ $order->code }}</a></p>
              <span class="text-muted d-block d-md-none fs-12">{{ translate('Date')}}: {{ date('d-m-Y', $order->date) }}</span>
          </div>

          <!-- Mobile-only buttons -->
          <div class="d-flex gap-2 d-md-none">
              <a type="button" href="{{ route('re_order', encrypt($order->id)) }}" class="btn btn-sm border  rounded px-4 py-1 text-muted reorder-btn">
                  {{ translate('Reorder') }}
              </a>

              <div class="dropdown">
                  <button type="button"
                      class="btn btn-sm dropdown-toggle text-white px-4 py-1 rounded btn-options ml-2"
                      data-toggle="dropdown">
                      {{ translate('Options') }}
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                      <a class="dropdown-item text-secondary dropdown-bg-hover" href="{{route('purchase_history.details', encrypt($order->id))}}"><i class="las la-eye mr-2"></i>{{ translate('View') }}</a>
                      <a class="dropdown-item text-secondary dropdown-bg-hover" href="{{ route('invoice.download', $order->id) }}"><i class="las la-download mr-2"></i>{{ translate('Invoice') }}</a>
                      @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                        <a href="javascript:void(0)"  class="dropdown-item text-secondary dropdown-bg-hover confirm-delete" data-href="{{route('purchase_history.destroy', $order->id)}}"><i class="las la-trash mr-2"></i> {{ translate('Cancel') }}</a>
                      @endif
                  </div>
              </div>
          </div>

      </div>

      <!-- Desktop-only buttons (original position) -->
      <div class="row align-items-center mb-2 d-none d-md-flex">
          <div class="col-md-6">
              <div class="row fs-12">
                  <div class="col-auto w-200px">
                      <span class="font-weight-bold light-blue">{{ get_shop_by_user_id($order->seller_id)->name??"Inhouse Products" }}</span>
                  </div>
                  <div class="col">
                      <span class="text-muted">{{ translate('Date')}}: {{ date('d-m-Y', $order->date) }}</span>
                  </div>
              </div>
          </div>
          <div class="col-md-6 text-right">
              <a type="button" class="btn btn-sm border rounded px-4 py-1 text-muted reorder-btn" href="{{ route('re_order', encrypt($order->id)) }}">
                  {{ translate('Reorder') }}
              </a>

              <div class="d-inline-block dropdown ml-1">
                  <button type="button"
                      class="btn btn-sm dropdown-toggle text-white px-4 py-1 rounded btn-options"
                      data-toggle="dropdown">
                      {{ translate('Options') }}
                  </button>

                  <div class="dropdown-menu dropdown-menu-right ">
                      <a class="dropdown-item text-secondary dropdown-bg-hover" href="{{route('purchase_history.details', encrypt($order->id))}}"><i class="las la-eye mr-2"></i>{{ translate('View') }}</a>
                      <a class="dropdown-item text-secondary dropdown-bg-hover" href="{{ route('invoice.download', $order->id) }}"><i class="las la-download mr-2"></i>{{ translate('Invoice') }}</a>
                      @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                      <a href="javascript:void(0)"  class="dropdown-item text-secondary dropdown-bg-hover confirm-delete" data-href="{{route('purchase_history.destroy', $order->id)}}"><i class="las la-trash mr-2"></i> {{ translate('Cancel') }}</a>
                      @endif
                  </div>
              </div>
          </div>
      </div>
      <!-- Mobile-only on the way and paid buttons -->
      <div class="d-flex d-md-none text-start mb-2 mt-3">
          <span class="btn btn-sm rounded-pill btn-on-the-way">
              {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
          </span>
          @if ($order->payment_status == 'paid')
          <span class="btn btn-sm rounded-pill btn-paid ml-2">
              {{ translate('Paid') }}
          </span>
          @else
          <span class="btn btn-sm rounded-pill btn-unpaid">
              {{ translate('Unpaid') }}
          </span>
          @endif
      </div>

      <hr class="border-dashed">

      <!-- image,product name,price,on the way,paid button row -->
      <!-- image,product name,price,on the way,paid button row -->


      <div class="row align-items-center mb-3">


          <div class="col-md-9">
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
                  
                  @if ($itemCounter > 0)
                  <hr class="hr-split">
                  @endif
                  
                  {{-- Group Product Header --}}
                  <div class="row mb-2 bg-light p-2 rounded">
                      <div class="col-md-8 d-flex align-items-center">
                          <i class="las la-box text-primary mr-2 fs-18"></i>
                          <div class="w-300px text-wrap">
                              <div class="font-weight-semibold fs-14 product-name-color mobile-title-shift">
                                  <strong>{{ $groupProduct ? $groupProduct->name : translate('Bundle') }}</strong>
                                  <span class="text-muted fs-12">({{ translate('Bundle') }})</span>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="font-weight-bold">{{ single_price($groupTotalPrice) }}</div>
                      </div>
                  </div>
                  
                  {{-- Individual products in the bundle --}}
                  @foreach ($groupItems as $orderDetail)
                      <div class="row bundle-item pl-4">
                          <div class="col-md-8 d-flex align-items-center">
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

                          <div class="col-md-4">
                              <div class="font-weight-bold">{{ single_price($orderDetail->price) }}</div>
                              <div class="text-muted small">{{ translate('Qty')}} {{ $orderDetail->quantity }}</div>
                          </div>
                      </div>
                  @endforeach
                  
                  @php $itemCounter++; @endphp
              @endforeach
              
              {{-- Display ungrouped products (regular products) --}}
              @foreach ($ungroupedDetails as $orderDetail)
                  @if ($itemCounter > 0)
                  <hr class="hr-split">
                  @endif
                  <div class="row">
                      <div class="col-md-8 d-flex align-items-center">
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

                      <div class="col-md-4">
                          <div class="font-weight-bold">{{ single_price($orderDetail->price) }}</div>
                          <div class="text-muted small">{{ translate('Qty')}} {{ $orderDetail->quantity }}</div>
                      </div>
                  </div>
                  @php $itemCounter++; @endphp
              @endforeach
          </div>


          <!-- Desktop-only buttons in right column -->
          <div class="col-md-3 text-right d-none d-md-block align-self-start">
              <div>
                  <span class="btn btn-sm rounded-pill btn-on-the-way">
                      {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                  </span>
              </div>
              @if ($order->payment_status == 'paid')
              <div class="mt-2">
                  <span class="btn btn-sm rounded-pill btn-paid">
                      {{ translate('Paid') }}
                  </span>
              </div>
              @else
              <div class="mt-2">
                  <span class="btn btn-sm rounded-pill btn-unpaid">
                      {{ translate('Unpaid') }}
                  </span>    
              </div>
              @endif
          </div>
      </div>


      <hr class="hr-split">
      <hr>

  </div>

  @endforeach

  <div class="aiz-pagination mt-4" id="pagination">
      {{ $orders->links() }}
  </div>