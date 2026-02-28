<style>
    /* Mobile: Minimize all content sizes to fit without scrolling */
    @media (max-width: 767px) {
        #addToCart .modal-body {
            padding: 0.5rem !important;
            max-height: 95vh;
            overflow-y: auto;
        }
        #addToCart .modal-body .row {
            margin-left: -5px !important;
            margin-right: -5px !important;
        }
        #addToCart .modal-body .row > [class*="col"] {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }
        #addToCart .modal-body h2 {
            font-size: 12px !important;
            margin-bottom: 0.3rem !important;
            line-height: 1.2 !important;
        }
        /* Image gallery - larger size, centered */
        #addToCart .modal-body .product-gallery {
            max-height: 200px !important;
            margin: 0 auto !important;
        }
        #addToCart .modal-body .product-gallery img {
            max-height: 200px !important;
            object-fit: contain !important;
        }
        #addToCart .modal-body .row.gutters-10 {
            justify-content: center !important;
            align-items: flex-start !important;
            max-width: 100% !important;
        }
        #addToCart .modal-body .row.gutters-10 > .col-auto.order-2 {
            flex: 1 1 auto !important;
            max-width: calc(100% - 60px) !important;
        }
        /* Thumbnail gallery - scrollable */
        #addToCart .modal-body .product-gallery-thumb {
            width: 50px !important;
            max-height: 200px !important;
            position: relative !important;
        }
        #addToCart .modal-body .product-gallery-thumb .slick-list {
            max-height: 200px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            -webkit-overflow-scrolling: touch !important;
            scroll-behavior: smooth !important;
        }
        #addToCart .modal-body .product-gallery-thumb .slick-track {
            display: block !important;
            transform: none !important;
            width: 100% !important;
            position: relative !important;
        }
        #addToCart .modal-body .product-gallery-thumb .slick-slide {
            display: block !important;
            height: auto !important;
            margin-bottom: 5px !important;
            float: none !important;
            width: 100% !important;
        }
        #addToCart .modal-body .product-gallery-thumb .slick-slide > div {
            display: block !important;
            width: 100% !important;
        }
        #addToCart .modal-body .product-gallery-thumb .size-60px,
        #addToCart .modal-body .product-gallery-thumb .size-50px {
            width: 40px !important;
            height: 40px !important;
        }
        #addToCart .modal-body .w-90px {
            width: 50px !important;
        }
        #addToCart .modal-body .product-gallery-thumb .carousel-box {
            margin-bottom: 5px !important;
        }
        /* Minimize spacing */
        #addToCart .modal-body .row.no-gutters {
            margin-top: 0.3rem !important;
            margin-bottom: 0.2rem !important;
        }
        #addToCart .modal-body .mt-3 {
            margin-top: 0.4rem !important;
        }
        #addToCart .modal-body .mt-2 {
            margin-top: 0.25rem !important;
        }
        #addToCart .modal-body .mb-2 {
            margin-bottom: 0.25rem !important;
        }
        /* Minimize text sizes */
        #addToCart .modal-body .text-secondary {
            font-size: 10px !important;
        }
        #addToCart .modal-body .fs-16 {
            font-size: 12px !important;
        }
        #addToCart .modal-body .fs-14 {
            font-size: 10px !important;
        }
        #addToCart .modal-body .fs-11 {
            font-size: 9px !important;
        }
        #addToCart .modal-body .fs-20 {
            font-size: 14px !important;
        }
        /* Minimize price section */
        #addToCart .modal-body .col-3 {
            flex: 0 0 25% !important;
            max-width: 25% !important;
        }
        #addToCart .modal-body .col-9 {
            flex: 0 0 75% !important;
            max-width: 75% !important;
        }
        /* Minimize attribute/color buttons */
        #addToCart .modal-body .aiz-megabox-elem {
            padding: 0.2rem 0.4rem !important;
            font-size: 10px !important;
            min-height: auto !important;
        }
        #addToCart .modal-body .aiz-megabox {
            margin-right: 0.3rem !important;
            margin-bottom: 0.2rem !important;
        }
        #addToCart .modal-body .size-25px {
            width: 18px !important;
            height: 18px !important;
        }
        /* Minimize quantity selector */
        #addToCart .modal-body .product-quantity {
            margin-top: 0.3rem !important;
        }
        #addToCart .modal-body .aiz-plus-minus {
            width: 90px !important;
        }
        #addToCart .modal-body .aiz-plus-minus .btn {
            padding: 0.2rem 0.3rem !important;
            font-size: 10px !important;
        }
        #addToCart .modal-body .aiz-plus-minus .input-number {
            font-size: 11px !important;
            padding: 0.2rem !important;
        }
        #addToCart .modal-body .avialable-amount {
            font-size: 9px !important;
            margin-left: 0.3rem !important;
        }
        /* Minimize buttons */
        #addToCart .modal-body .btn {
            padding: 0.4rem 0.6rem !important;
            font-size: 11px !important;
        }
        #addToCart .modal-body .btn i {
            font-size: 12px !important;
        }
        /* Center and enlarge add to cart button on mobile */
        #addToCart .modal-body .mt-3 {
            display: flex !important;
            justify-content: center !important;
        }
        #addToCart .modal-body .mt-3 button.add-to-cart,
        #addToCart .modal-body .mt-3 a.add-to-cart {
            padding: 0.75rem 2rem !important;
            font-size: 14px !important;
            min-width: 200px !important;
        }
        #addToCart .modal-body .mt-3 button.add-to-cart i,
        #addToCart .modal-body .mt-3 a.add-to-cart i {
            font-size: 16px !important;
        }
        /* Minimize club point section */
        #addToCart .modal-body .bg-secondary-base {
            padding: 0.2rem 0.4rem !important;
            margin-top: 0.3rem !important;
        }
        #addToCart .modal-body .bg-secondary-base svg {
            width: 8px !important;
            height: 8px !important;
        }
        #addToCart .modal-body .bg-secondary-base small {
            font-size: 9px !important;
            margin-left: 0.3rem !important;
        }
        /* Minimize padding */
        #addToCart .modal-body .py-1 {
            padding-top: 0.2rem !important;
            padding-bottom: 0.2rem !important;
        }
        #addToCart .modal-body .px-3 {
            padding-left: 0.4rem !important;
            padding-right: 0.4rem !important;
        }
        #addToCart .modal-body svg {
            width: 8px !important;
            height: 8px !important;
        }
        /* Minimize gutters */
        #addToCart .modal-body .gutters-10 {
            margin-left: -3px !important;
            margin-right: -3px !important;
        }
        #addToCart .modal-body .gutters-10 > [class*="col"] {
            padding-left: 3px !important;
            padding-right: 3px !important;
        }
    }
    /* Desktop: Larger images with scrollable thumbnails, centered */
    @media (min-width: 768px) {
        #addToCart .modal-body .product-gallery {
            max-height: 400px !important;
            margin: 0 auto !important;
        }
        #addToCart .modal-body .product-gallery img {
            max-height: 400px !important;
            object-fit: contain !important;
        }
        #addToCart .modal-body .row.gutters-10 {
            justify-content: center !important;
            align-items: flex-start !important;
            max-width: 800px !important;
            margin: 0 auto !important;
        }
        #addToCart .modal-body .row.gutters-10 > .col-auto.order-2 {
            flex: 1 1 auto !important;
            max-width: calc(100% - 100px) !important;
        }
        #addToCart .modal-body .product-gallery {
            text-align: center !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }
        #addToCart .modal-body .product-gallery .carousel-box {
            text-align: center !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }
        #addToCart .modal-body .product-gallery img {
            margin: 0 auto !important;
            display: block !important;
        }
        #addToCart .modal-body .product-gallery .slick-slide {
            text-align: center !important;
        }
        #addToCart .modal-body .product-gallery .slick-slide img {
            margin: 0 auto !important;
        }
        #addToCart .modal-body .product-gallery-thumb {
            width: 80px !important;
            max-height: 400px !important;
            position: relative !important;
        }
        #addToCart .modal-body .product-gallery-thumb .slick-list {
            max-height: 400px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            -webkit-overflow-scrolling: touch !important;
            scroll-behavior: smooth !important;
        }
        #addToCart .modal-body .product-gallery-thumb .slick-track {
            display: block !important;
            transform: none !important;
            width: 100% !important;
            position: relative !important;
            top: 0 !important;
            left: 0 !important;
        }
        #addToCart .modal-body .product-gallery-thumb .slick-slide {
            display: block !important;
            height: auto !important;
            margin-bottom: 8px !important;
            float: none !important;
            width: 100% !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        #addToCart .modal-body .product-gallery-thumb .slick-slide > div {
            display: block !important;
            width: 100% !important;
        }
        /* Disable slick transforms that interfere with scrolling */
        #addToCart .modal-body .product-gallery-thumb .slick-track[style*="transform"] {
            transform: none !important;
        }
        #addToCart .modal-body .product-gallery-thumb .size-60px,
        #addToCart .modal-body .product-gallery-thumb .size-50px {
            width: 60px !important;
            height: 60px !important;
        }
        #addToCart .modal-body .w-90px {
            width: 80px !important;
        }
        #addToCart .modal-body .product-gallery-thumb .carousel-box {
            margin-bottom: 8px !important;
        }
    }
    /* Center add to cart button for all views */
    #addToCart .modal-body .mt-3 {
        display: flex !important;
        justify-content: center !important;
    }
    /* Ensure thumbnails are scrollable on all devices */
    #addToCart .modal-body .product-gallery-thumb .slick-track {
        display: block !important;
        transform: none !important;
        width: 100% !important;
    }
    #addToCart .modal-body .product-gallery-thumb .slick-list {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        -webkit-overflow-scrolling: touch !important;
        scroll-behavior: smooth !important;
        height: auto !important;
    }
    #addToCart .modal-body .product-gallery-thumb .slick-slide {
        display: block !important;
        height: auto !important;
        float: none !important;
    }
    #addToCart .modal-body .product-gallery-thumb .slick-slide > div {
        display: block !important;
    }
</style>
<div class="modal-body px-4 py-5 c-scrollbar-light">
    <div class="row">
        <!-- Product Image gallery - Main image center, thumbnails right -->
        <div class="col-12 col-lg-12 mb-3 mb-lg-3">
            <div class="row gutters-10 justify-content-center">
                @php
                    $photos = explode(',',$product->photos);
                @endphp
                <!-- Main Image - Center -->
                <div class="col-auto order-2 order-lg-2">
                    <div class="aiz-carousel product-gallery" data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='true' style="max-width: 100%; text-align: center;">
                        @foreach ($photos as $key => $photo)
                        <div class="carousel-box img-zoom rounded-0">
                            <img class="img-fluid lazyload"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($photo) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </div>
                        @endforeach
                        @foreach ($product->stocks as $key => $stock)
                            @if ($stock->image != null)
                                <div class="carousel-box img-zoom rounded-0">
                                    <img class="img-fluid lazyload"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($stock->image) }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <!-- Thumbnails - Right -->
                <div class="col-auto w-90px order-1 order-lg-3">
                    <div class="aiz-carousel carousel-thumb product-gallery-thumb" data-items='5' data-nav-for='.product-gallery' data-vertical='true' data-focus-select='true' data-infinite='false'>
                        @foreach ($photos as $key => $photo)
                        <div class="carousel-box c-pointer border rounded-0">
                            <img class="lazyload mw-100 size-60px mx-auto"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($photo) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </div>
                        @endforeach
                        @foreach ($product->stocks as $key => $stock)
                            @if ($stock->image != null)
                                <div class="carousel-box c-pointer border rounded-0" data-variation="{{ $stock->variant }}">
                                    <img class="lazyload mw-100 size-50px mx-auto"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($stock->image) }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-12 col-lg-12">
            <div class="text-left">
                <!-- Product name -->
                <h2 class="mb-2 fs-16 fw-700 text-dark">
                    {{  $product->getTranslation('name')  }}
                </h2>

                <!-- Product Price & Club Point -->
                @if(home_price($product) != home_discounted_price($product))
                    <div class="row no-gutters mt-3">
                        <div class="col-3">
                            <div class="text-secondary fs-14 fw-400">{{ translate('Price')}}</div>
                        </div>
                        <div class="col-9">
                            <div class="">
                                <strong class="fs-16 fw-700 text-primary">
                                    {{ home_discounted_price($product) }}
                                </strong>
                                <del class="fs-14 opacity-60 ml-2">
                                    {{ home_price($product) }}
                                </del>
                                @if($product->unit != null)
                                    <span class="opacity-70 ml-1">/{{ $product->getTranslation('unit') }}</span>
                                @endif
                                @if(discount_in_percentage($product) > 0)
                                    <span class="bg-primary ml-2 fs-11 fw-700 text-white w-35px text-center px-2" style="padding-top:2px;padding-bottom:2px;">-{{discount_in_percentage($product)}}%</span>
                                @endif
                            </div>

                            <!-- Club Point -->
                            @if (addon_is_activated('club_point') && $product->earn_point > 0)
                            <div class="mt-2 bg-secondary-base d-flex justify-content-center align-items-center px-3 py-1" style="width: fit-content;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                    <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                                      <circle id="Ellipse_39" data-name="Ellipse 39" cx="6" cy="6" r="6" transform="translate(973 633)" fill="#fff"/>
                                      <g id="Group_23920" data-name="Group 23920" transform="translate(973 633)">
                                        <path id="Path_28698" data-name="Path 28698" d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)" fill="#f3af3d"/>
                                        <path id="Path_28699" data-name="Path 28699" d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)" fill="#f3af3d" opacity="0.5"/>
                                        <path id="Path_28700" data-name="Path 28700" d="M12.666,3h1L15,5,12,9l1.664-4Z" transform="translate(-5.995 0)" fill="#f3af3d"/>
                                      </g>
                                    </g>
                                </svg>
                                <small class="fs-11 fw-500 text-white ml-2">{{  translate('Club Point') }}: {{ $product->earn_point }}</small>
                            </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="row no-gutters mt-3">
                        <div class="col-3">
                            <div class="text-secondary fs-14 fw-400">{{ translate('Price')}}</div>
                        </div>
                        <div class="col-9">
                            <div class="">
                                <strong class="fs-16 fw-700 text-primary">
                                    {{ home_discounted_price($product) }}
                                </strong>
                                @if ($product->unit != null)
                                    <span class="opacity-70">/{{ $product->unit }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @php
                    $qty = 0;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                @endphp

                <!-- Product Choice options form -->
                <form id="option-choice-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    
                    @if($product->digital !=1)
                        <!-- Product Choice options -->
                        @if ($product->choice_options != null)
                            @foreach (json_decode($product->choice_options) as $key => $choice)

                                <div class="row no-gutters mt-3">
                                    <div class="col-3">
                                        <div class="text-secondary fs-14 fw-400 mt-2 ">{{ get_single_attribute_name($choice->attribute_id) }}</div>
                                    </div>
                                    <div class="col-9">
                                        <div class="aiz-radio-inline">
                                            @php
                                                $valuesCount = count($choice->values);
                                            @endphp
                                            @foreach ($choice->values as $key => $value)
                                            <label class="aiz-megabox pl-0 mr-2 mb-0">
                                                <input
                                                    type="radio"
                                                    name="attribute_id_{{ $choice->attribute_id }}"
                                                    value="{{ $value }}"
                                                    @if($valuesCount == 1) checked @endif
                                                    required
                                                >
                                                <span class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center py-1 px-3">
                                                    {{ $value }}
                                                </span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        @endif

                        <!-- Color -->
                        @if ($product->colors && count(json_decode($product->colors)) > 0)
                            @php
                                $colors = json_decode($product->colors);
                                $colorsCount = count($colors);
                            @endphp
                            <div class="row no-gutters mt-3">
                                <div class="col-3">
                                    <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Color')}}</div>
                                </div>
                                <div class="col-9">
                                    <div class="aiz-radio-inline">
                                        @foreach ($colors as $key => $color)
                                        <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip" data-title="{{ get_single_color_name($color) }}">
                                            <input
                                                type="radio"
                                                name="color"
                                                value="{{ get_single_color_name($color) }}"
                                                @if($colorsCount == 1) checked @endif
                                                required
                                            >
                                            <span class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center p-1">
                                                <span class="size-25px d-inline-block rounded" style="background: {{ $color }};"></span>
                                            </span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Quantity -->
                        <div class="row no-gutters mt-3">
                            <div class="col-3">
                                <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Quantity')}}</div>
                            </div>
                            <div class="col-9">
                                <div class="product-quantity d-flex align-items-center">
                                    <div class="row no-gutters align-items-center aiz-plus-minus mr-3" style="width: 130px;">
                                        <button class="btn col-auto btn-icon btn-sm btn-light rounded-0" type="button" data-type="minus" data-field="quantity" disabled="">
                                            <i class="las la-minus"></i>
                                        </button>
                                        <input type="number" name="quantity" class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1" value="{{ $product->min_qty }}" min="{{ $product->min_qty }}" max="10" lang="en">
                                        <button class="btn col-auto btn-icon btn-sm btn-light rounded-0" type="button" data-type="plus" data-field="quantity">
                                            <i class="las la-plus"></i>
                                        </button>
                                    </div>
                                    <div class="avialable-amount opacity-60">
                                        @if($product->stock_visibility_state == 'quantity')
                                        (<span id="available-quantity">{{ $qty }}</span> {{ translate('available')}})
                                        @elseif($product->stock_visibility_state == 'text' && $qty >= 1)
                                            (<span id="available-quantity">{{ translate('In Stock') }}</span>)
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Quantity -->
                        <input type="hidden" name="quantity" value="1">
                    @endif
                    
                    <!-- Total Price -->
                    <div class="row no-gutters mt-3 pb-3 d-none" id="chosen_price_div">
                        <div class="col-3">
                            <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Total Price')}}</div>
                        </div>
                        <div class="col-9">
                            <div class="product-price">
                                <strong id="chosen_price" class="fs-20 fw-700 text-primary">

                                </strong>
                            </div>
                        </div>
                    </div>

                </form>

                <!-- Add to cart -->
                <div class="mt-3">
                    @if ($product->digital == 1)
                        <button type="button" class="btn btn-primary rounded-5 fw-600 add-to-cart"
                            @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif
                        >
                            <i class="la la-shopping-cart"></i>
                            <span>{{ translate('Add to cart')}}</span>
                        </button>
                        <button type="button" class="btn btn-success rounded-5 fw-600 ml-2 buy-now"
                            @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="buyNow()" @else onclick="showLoginModal()" @endif
                        >
                            <i class="la la-bolt"></i>
                            <span>{{ translate('Buy Now')}}</span>
                        </button>
                    @elseif($qty > 0)
                        @if ($product->external_link != null)
                            <a type="button" class="btn btn-soft-primary rounded-5 mr-2 add-to-cart fw-600" href="{{ $product->external_link }}">
                                <i class="las la-share"></i>
                                <span class="d-none d-md-inline-block">{{ translate($product->external_link_btn)}}</span>
                            </a>
                        @else
                            <button type="button" class="btn btn-primary rounded-5 fw-600 add-to-cart"
                                @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif
                            >
                                <i class="la la-shopping-cart"></i>
                                <span>{{ translate('Add to cart')}}</span>
                            </button>
                            <button type="button" class="btn btn-success rounded-5 fw-600 ml-2 buy-now"
                                @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="buyNow()" @else onclick="showLoginModal()" @endif
                            >
                                <i class="la la-bolt"></i>
                                <span>{{ translate('Buy Now')}}</span>
                            </button>
                        @endif
                    @endif
                    <button type="button" class="btn btn-secondary rounded-5 out-of-stock fw-600 d-none" disabled>
                        <i class="la la-cart-arrow-down"></i>{{ translate('Out of Stock')}}
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Variant price update handler is attached globally in the frontend layout --}}
