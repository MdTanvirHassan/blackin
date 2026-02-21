@if(count($todays_deal_products) > 0)
    <section class="">
        <div class="custom-container">
            @php
                $lang = get_system_language()->code;
                $todays_deal_banner = get_setting('todays_deal_banner', null, $lang);
                $todays_deal_banner_small = get_setting('todays_deal_banner_small', null, $lang);
            @endphp
            <!-- Banner -->
            @if ($todays_deal_banner != null || $todays_deal_banner_small != null)
                <div class="overflow-hidden d-none d-md-block">
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
                        data-src="{{ uploaded_asset($todays_deal_banner) }}" 
                        alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition" 
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </div>
                <div class="overflow-hidden d-md-none">
                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" 
                        data-src="{{ $todays_deal_banner_small != null ? uploaded_asset($todays_deal_banner_small) : uploaded_asset($todays_deal_banner) }}" 
                        alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition" 
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                </div>
            @endif
            <!-- Products -->
            @php
                $todays_deal_banner_text_color =  ((get_setting('todays_deal_banner_text_color') == 'light') ||  (get_setting('todays_deal_banner_text_color') == null)) ? 'text-white' : 'text-dark';
            @endphp
            <div class="todays-deal-section" style="background-color: {{ get_setting('todays_deal_bg_color', '#3d4666') }}; padding: 2rem 0;">
                <div class="container-fluid px-3 px-md-4">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                        <h3 class="mb-0 {{ $todays_deal_banner_text_color }}" style="font-size: 22px; font-weight: 700;">
                            <i class="las la-bolt mr-2"></i>{{ translate("Today's Deal") }}
                        </h3>
                        <a href="{{ route('todays-deal') }}" class="fs-14 fw-600 {{ $todays_deal_banner_text_color }} has-transition hov-text-secondary-base d-flex align-items-center">
                            {{ translate('View All') }}
                            <i class="las la-angle-right ml-1"></i>
                        </a>
                    </div>
                    
                    <!-- Products Carousel -->
                    <div class="todays-deal-carousel-wrapper">
                        <div class="todays-deal aiz-carousel" 
                             data-items="5" 
                             data-xxl-items="5" 
                             data-xl-items="4" 
                             data-lg-items="4" 
                             data-md-items="3" 
                             data-sm-items="2" 
                             data-xs-items="2" 
                             data-arrows="true" 
                             data-dots="false" 
                             data-autoplay="true" 
                             data-infinite="true">
                            @foreach ($todays_deal_products as $key => $product)
                                @php
                                    $product_url = route('product', $product->slug);
                                    if ($product->auction_product == 1) {
                                        $product_url = route('auction-product', $product->slug);
                                    }
                                    $discount = discount_in_percentage($product);
                                @endphp
                                <div class="carousel-box px-2">
                                    <div class="todays-deal-card bg-white rounded-lg overflow-hidden h-100 d-flex flex-column shadow-sm has-transition">
                                        <a href="{{ $product_url }}" class="text-reset text-decoration-none">
                                            <!-- Image Container -->
                                            <div class="position-relative todays-deal-image-wrapper bg-light" style="height: 200px; overflow: hidden;">
                                                <img class="lazyload img-fit w-100 h-100 has-transition"
                                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ get_image($product->thumbnail) }}"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    style="object-fit: contain; padding: 10px;"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                
                                                <!-- Discount Badge -->
                                                @if($discount > 0)
                                                    <span class="position-absolute top-0 left-0 m-2 badge badge-danger fs-11 fw-700 px-2 py-1" 
                                                          style="width:auto; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); border-radius: 6px; z-index: 2;">
                                                        -{{ number_format($discount, 0) }}%
                                                    </span>
                                                @endif
                                                
                                                <!-- Hot Deal Badge -->
                                                <span class="position-absolute top-0 right-0 m-2 badge fs-10 fw-700 px-2 py-1" 
                                                      style="width:auto; background: rgba(255, 193, 7, 0.95); color: #000; border-radius: 6px; z-index: 2;">
                                                    <i class="las la-fire"></i> {{ translate('Hot') }}
                                                </span>
                                            </div>
                                            
                                            <!-- Product Info -->
                                            <div class="p-3 flex-grow-1 d-flex flex-column">
                                                <!-- Product Name -->
                                                <h4 class="fs-13 fs-md-14 fw-600 text-dark mb-2 text-truncate-2 lh-1-4" 
                                                    style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                    {{ $product->getTranslation('name') }}
                                                </h4>
                                                
                                                <!-- Price -->
                                                <div class="mt-auto">
                                                    <div class="d-flex align-items-baseline flex-wrap">
                                                        <span class="fs-18 fs-md-20 fw-700 text-primary mr-2">
                                                            {{ home_discounted_base_price($product) }}
                                                        </span>
                                                        @if(home_base_price($product) != home_discounted_base_price($product))
                                                            <del class="fs-12 text-secondary fw-400">
                                                                {{ home_base_price($product) }}
                                                            </del>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        
                                        <!-- Quick Add Button -->
                                        <div class="px-3 pb-3">
                                            <a href="javascript:void(0)" 
                                               onclick="showAddToCartModal({{ $product->id }})"
                                               class="btn btn-sm btn-block btn-primary w-100 rounded-lg fw-600 has-transition"
                                               style="font-size: 12px; padding: 8px;">
                                                <i class="las la-shopping-cart mr-1"></i>
                                                {{ translate('Add to Cart') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <style>
                .todays-deal-section {
                    position: relative;
                }
                
                .todays-deal-card {
                    transition: all 0.3s ease;
                    border: 1px solid rgba(255, 255, 255, 0.1);
                }
                
                .todays-deal-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
                }
                
                .todays-deal-image-wrapper {
                    position: relative;
                    background: #f8f9fa;
                }
                
                .todays-deal-card:hover .todays-deal-image-wrapper img {
                    transform: scale(1.05);
                }
                
                .todays-deal-carousel-wrapper {
                    position: relative;
                    padding: 0 50px;
                    overflow: visible !important;
                }
                
                /* Arrow Styling - Always Visible */
                .todays-deal .slick-arrow {
                    background: rgba(255, 255, 255, 0.9) !important;
                    width: 40px !important;
                    height: 40px !important;
                    border-radius: 50% !important;
                    z-index: 10 !important;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    opacity: 1 !important;
                    visibility: visible !important;
                    display: block !important;
                }
                
                /* Override any hover-only visibility */
                .todays-deal-carousel-wrapper .slick-arrow,
                .todays-deal-carousel-wrapper:hover .slick-arrow,
                .todays-deal .slick-arrow,
                .todays-deal:hover .slick-arrow {
                    opacity: 1 !important;
                    visibility: visible !important;
                    display: block !important;
                }
                
                .todays-deal .slick-arrow:hover {
                    background: #fff !important;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                }
                
                .todays-deal .slick-arrow:before {
                    color: #333;
                    font-size: 20px;
                }
                
                .todays-deal .slick-prev {
                    left: -50px !important;
                }
                
                .todays-deal .slick-next {
                    right: -50px !important;
                }
                
                /* Disable any arrow-inactive classes */
                .todays-deal.arrow-inactive-none .slick-arrow,
                .todays-deal.arrow-none .slick-arrow {
                    opacity: 1 !important;
                    visibility: visible !important;
                    display: block !important;
                }
                
                /* Ensure container doesn't clip arrows */
                .todays-deal-section .container-fluid {
                    overflow: visible !important;
                }
                
                .todays-deal-section {
                    overflow: visible !important;
                }
                
                .todays-deal .slick-slider {
                    position: relative;
                    overflow: visible !important;
                }
                
                /* Keep list overflow hidden for slider functionality */
                .todays-deal .slick-list {
                    margin: 0 -15px;
                }
                
                @media (max-width: 767px) {
                    .todays-deal-section {
                        padding: 1.5rem 0 !important;
                    }
                    
                    .todays-deal-image-wrapper {
                        height: 160px !important;
                    }
                    
                    .todays-deal-carousel-wrapper {
                        padding: 0 40px;
                    }
                    
                    .todays-deal .slick-arrow {
                        width: 35px !important;
                        height: 35px !important;
                    }
                    
                    .todays-deal .slick-prev {
                        left: -40px !important;
                    }
                    
                    .todays-deal .slick-next {
                        right: -40px !important;
                    }
                }
            </style>
        </div>
    </section>
@endif