<div class="modal-body px-4 py-5 c-scrollbar-light">
<style>
    .bundle-item-card {
        transition: all 0.2s ease;
        background: #f8f9fa !important;
    }
    .bundle-item-card:hover {
        background: #e9ecef !important;
        transform: translateX(2px);
    }
    .bundle-items-list .bundle-item-card:last-child {
        margin-bottom: 0 !important;
    }
    /* Mobile: Minimize all content sizes in added to cart modal */
    @media (max-width: 767px) {
        #addToCart .modal-body {
            padding: 0.75rem !important;
        }
        #addToCart .modal-body .text-success svg {
            width: 24px !important;
            height: 24px !important;
        }
        #addToCart .modal-body .text-success h3 {
            font-size: 16px !important;
            margin-bottom: 0.75rem !important;
        }
        #addToCart .modal-body .mb-4 {
            margin-bottom: 0.75rem !important;
        }
        #addToCart .modal-body .media {
            margin-bottom: 0.5rem !important;
        }
        #addToCart .modal-body .size-90px {
            width: 60px !important;
            height: 60px !important;
        }
        #addToCart .modal-body .mr-4 {
            margin-right: 0.75rem !important;
        }
        #addToCart .modal-body .fs-14 {
            font-size: 11px !important;
        }
        #addToCart .modal-body .fs-16 {
            font-size: 12px !important;
        }
        #addToCart .modal-body .fs-28 {
            font-size: 16px !important;
        }
        #addToCart .modal-body .fs-15 {
            font-size: 12px !important;
        }
        #addToCart .modal-body .fs-12 {
            font-size: 10px !important;
        }
        #addToCart .modal-body .fs-11 {
            font-size: 9px !important;
        }
        #addToCart .modal-body .mt-2 {
            margin-top: 0.4rem !important;
        }
        #addToCart .modal-body .mt-1 {
            margin-top: 0.25rem !important;
        }
        #addToCart .modal-body .mt-4 {
            margin-top: 0.75rem !important;
        }
        #addToCart .modal-body .mb-1 {
            margin-bottom: 0.4rem !important;
        }
        #addToCart .modal-body .mb-2 {
            margin-bottom: 0.4rem !important;
        }
        #addToCart .modal-body .mb-3 {
            margin-bottom: 0.5rem !important;
        }
        #addToCart .modal-body .p-3 {
            padding: 0.5rem !important;
        }
        #addToCart .modal-body .bundle-item-card img {
            width: 50px !important;
            height: 50px !important;
        }
        #addToCart .modal-body .mr-3 {
            margin-right: 0.5rem !important;
        }
        #addToCart .modal-body .ml-2 {
            margin-left: 0.3rem !important;
        }
        #addToCart .modal-body .ml-3 {
            margin-left: 0.5rem !important;
        }
        #addToCart .modal-body .btn {
            padding: 0.75rem 1rem !important;
            font-size: 14px !important;
        }
        #addToCart .modal-body .mb-sm-0 {
            margin-bottom: 0 !important;
        }
        #addToCart .modal-body .gutters-5 {
            margin-left: -5px !important;
            margin-right: -5px !important;
            margin-top: 1rem !important;
        }
        #addToCart .modal-body .gutters-5 > [class*="col"] {
            padding-left: 5px !important;
            padding-right: 5px !important;
            margin-bottom: 0.5rem !important;
        }
    }
</style>
    <!-- Item added to your cart -->
    <div class="text-center text-success mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36">
            <g id="Group_23957" data-name="Group 23957" transform="translate(-6269 7766)">
              <path id="Path_28713" data-name="Path 28713" d="M12.8,32.8a3.6,3.6,0,1,0,3.6,3.6A3.584,3.584,0,0,0,12.8,32.8ZM2,4V7.6H5.6l6.471,13.653-2.43,4.41A3.659,3.659,0,0,0,9.2,27.4,3.6,3.6,0,0,0,12.8,31H34.4V27.4H13.565a.446.446,0,0,1-.45-.45.428.428,0,0,1,.054-.216L14.78,23.8H28.19a3.612,3.612,0,0,0,3.15-1.854l6.435-11.682A1.74,1.74,0,0,0,38,9.4a1.8,1.8,0,0,0-1.8-1.8H9.587L7.877,4H2ZM30.8,32.8a3.6,3.6,0,1,0,3.6,3.6A3.584,3.584,0,0,0,30.8,32.8Z" transform="translate(6267 -7770)" fill="#85b567"/>
              <rect id="Rectangle_18068" data-name="Rectangle 18068" width="9" height="3" rx="1.5" transform="translate(6284.343 -7757.879) rotate(45)" fill="#fff"/>
              <rect id="Rectangle_18069" data-name="Rectangle 18069" width="3" height="13" rx="1.5" transform="translate(6295.657 -7760.707) rotate(45)" fill="#fff"/>
            </g>
        </svg>
        <h3 class="fs-28 fw-500">{{ translate('Bundle added to your cart!')}}</h3>
    </div>

    <!-- Bundle Info -->
    <div class="media mb-1">
        <img src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ uploaded_asset($groupProduct->thumbnail_img) }}"
            class="mr-4 lazyload size-90px img-fit rounded-0" alt="Bundle Image" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        <div class="media-body mt-2 text-left d-flex flex-column justify-content-between">
            <h6 class="fs-14 fw-700 text-truncate-2">
                {{ $groupProduct->name }}
            </h6>
            <div class="row mt-2">
                <div class="col-sm-3 fs-14 fw-400 text-secondary">
                    <div>{{ translate('Total Price')}}</div>
                </div>
                <div class="col-sm-9">
                    <div class="fs-16 fw-700 text-primary">
                        <strong>
                            {{ single_price($totalPrice) }}
                        </strong>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-sm-12 fs-12 fw-400 text-secondary">
                    <div>{{ translate('Quantity')}}: {{ $quantity }} × {{ translate('Bundle') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bundle Items Summary -->
    @if(!empty($cartItems))
    <div class="mt-4">
        <div class="d-flex align-items-center mb-3">
            <h3 class="fs-16 fw-700 mb-0 text-dark">
                <i class="las la-gift text-primary mr-2"></i>{{ translate('Bundle Items')}}
            </h3>
            <span style="width: 60px;" class="badge badge-primary badge-sm ml-2">{{ count($cartItems) }} {{ translate('items') }}</span>
        </div>
        <div class="bundle-items-list">
            @foreach($cartItems as $index => $cartItem)
                @php
                    $itemProduct = $cartItem->product;
                    $itemSlot = $cartItem->groupProductSlot;
                @endphp
                <div class="bundle-item-card d-flex align-items-start p-3 mb-2 bg-light rounded" style="border-left: 3px solid #007bff;">
                    <div class="flex-shrink-0 mr-3">
                        <img src="{{ static_asset('assets/img/placeholder.jpg') }}" 
                             data-src="{{ uploaded_asset($itemProduct->thumbnail_img) }}"
                             class="lazyload img-fit rounded" 
                             style="width: 70px; height: 70px; object-fit: cover;"
                             alt="{{ $itemProduct->getTranslation('name') }}" 
                             onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <h6 class="fs-14 fw-600 mb-1 text-dark" style="line-height: 1.4;">
                                    {{ $itemProduct->getTranslation('name') }}
                                </h6>
                                <div class="align-items-center mb-1" style="gap: 8px;">
                                    @if($itemSlot)
                                        <span class="fs-12 d-flex align-items-center" style="font-size: 11px; border-radius: 4px;">
                                            <i class="las la-tag mr-1"></i>{{ $itemSlot->name }}
                                            <br>
                                        </span>
                                    @endif
                                    @if($cartItem->variation)
                                        <span class="text-muted fs-12 d-flex align-items-center">
                                            <i class="las la-palette mr-1"></i>{{ $cartItem->variation }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right ml-3">
                                <div class="fs-15 fw-700 text-primary mb-1">
                                    {{ single_price($cartItem->price * $cartItem->quantity) }}
                                </div>
                                <div class="fs-11 text-muted">
                                    {{ $cartItem->quantity }} × {{ single_price($cartItem->price) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Back to shopping & Checkout buttons -->
    <div class="row gutters-5 mt-4">
        <div class="col-sm-6">
            <button class="btn btn-danger mb-3 mb-sm-0 btn-block rounded-5 text-white" data-dismiss="modal">{{ translate('Back to shopping')}}</button>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('cart') }}" class="btn btn-primary mb-3 mb-sm-0 btn-block rounded-5">{{ translate('Proceed to Checkout')}}</a>
        </div>
    </div>
</div>

