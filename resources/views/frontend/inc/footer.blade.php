<div class="bg-light">
<!-- Modern Footer Design -->
<section class="text-light footer-widget pt-md-5 pb-md-0" style="background-color: #000000 !important;">
    <div class="container py-5">
        <!-- footer logo -->
        <div class="mb-5 p-4 d-md-none d-block">
            <a href="{{ route('home') }}" class="d-inline-block">
                @if(get_setting('footer_logo') != null)
                    <img class="lazyload h-45px" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="{{ env('APP_NAME') }}" height="45">
                @else
                    <img class="lazyload h-45px" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" height="45">
                @endif
            </a>
        </div>

        <!-- Main Footer Grid -->
        <div class="row pb-4 d-none d-lg-flex">
            <!-- Column 1: Company -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fs-15 fw-700 text-white mb-4">{{ get_setting('widget_one',null,App::getLocale()) ?: translate('Company') }}</h5>
                <ul class="list-unstyled footer-links">
                    @if ( get_setting('widget_one_labels',null,App::getLocale()) !=  null )
                        @foreach (json_decode( get_setting('widget_one_labels',null,App::getLocale()), true) as $key => $value)
                        @php
                            $widget_one_links = '';
                            if(isset(json_decode(get_setting('widget_one_links'), true)[$key])) {
                                $widget_one_links = json_decode(get_setting('widget_one_links'), true)[$key];
                            }
                        @endphp
                        <li class="mb-2">
                            <a href="{{ $widget_one_links }}" class="fs-14 text-white opacity-75 hover-opacity-100 d-inline-block">
                                {{ $value }}
                            </a>
                        </li>
                        @endforeach
                    @endif
                </ul>
                <!-- Contact Info -->
                <div class="mt-4">
                    <p class="fs-14 text-white mb-2">{{ get_setting('contact_phone') }}</p>
                    <a href="mailto:{{ get_setting('contact_email') }}" class="fs-14 text-white opacity-75 hover-opacity-100">{{ get_setting('contact_email')  }}</a>
                </div>
            </div>

            <!-- Column 2: Customer Service -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fs-15 fw-700 text-white mb-4">{{ translate('Customer Service') }}</h5>
                <ul class="list-unstyled footer-links">
                    <li class="mb-2">
                        <a href="{{ route('terms') }}" class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center">
                            <i class="las la-angle-double-right footer-arrow-icon"></i>
                            <span class="flex-grow-1">{{ translate('Terms & Conditions') }}</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('returnpolicy') }}" class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center">
                            <i class="las la-angle-double-right footer-arrow-icon"></i>
                            <span class="flex-grow-1">{{ translate('Return Policy') }}</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('supportpolicy') }}" class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center">
                            <i class="las la-angle-double-right footer-arrow-icon"></i>
                            <span class="flex-grow-1">{{ translate('Support Policy') }}</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('privacypolicy') }}" class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center">
                            <i class="las la-angle-double-right footer-arrow-icon"></i>
                            <span class="flex-grow-1">{{ translate('Privacy Policy') }}</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('orders.track') }}" class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center">
                            <i class="las la-angle-double-right footer-arrow-icon"></i>
                            <span class="flex-grow-1">{{ translate('Track Order') }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 3: My Account & Resources -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fs-15 fw-700 text-white mb-4">{{ translate('My Account') }}</h5>
                <ul class="list-unstyled footer-links">
                    @if (Auth::check())
                        <li class="mb-2">
                            <a class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center" href="{{ route('logout') }}">
                                <i class="las la-angle-double-right footer-arrow-icon"></i>
                                <span class="flex-grow-1">{{ translate('Logout') }}</span>
                            </a>
                        </li>
                    @else
                        <li class="mb-2">
                            <a class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center" href="{{ route('user.login') }}">
                                <i class="las la-angle-double-right footer-arrow-icon"></i>
                                <span class="flex-grow-1">{{ translate('Login') }}</span>
                            </a>
                        </li>
                    @endif
                    <li class="mb-2">
                        <a class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center" href="{{ route('purchase_history.index') }}">
                            <i class="las la-angle-double-right footer-arrow-icon"></i>
                            <span class="flex-grow-1">{{ translate('Order History') }}</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center" href="{{ route('wishlists.index') }}">
                            <i class="las la-angle-double-right footer-arrow-icon"></i>
                            <span class="flex-grow-1">{{ translate('My Wishlist') }}</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center" href="{{ route('wallet.index') }}">
                            <i class="las la-angle-double-right footer-arrow-icon"></i>
                            <span class="flex-grow-1">{{ translate('Transaction') }}</span>
                        </a>
                    </li>
                    @if (get_setting('vendor_system_activation') == 1)
                        <li class="mb-2">
                            <a class="fs-14 text-white opacity-75 footer-list-link d-flex align-items-center" href="{{ route(get_setting('seller_registration_verify') === '1' ? 'shop-reg.verification' : 'shops.create')  }}">
                                <i class="las la-angle-double-right footer-arrow-icon"></i>
                                <span class="flex-grow-1">{{ translate('Become A Seller') }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Column 4: Stay Connected -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="fs-15 fw-700 text-white mb-4">{{ translate('Stay Connected') }}</h5>
                
                <!-- Social Icons -->
                @if ( get_setting('show_social_links') )
                    <div class="mb-4">
                        <ul class="list-inline mb-0">
                            @if (!empty(get_setting('instagram_link')))
                                <li class="list-inline-item mr-3">
                                    <a href="{{ get_setting('instagram_link') }}" target="_blank" class="social-icon-link social-instagram text-white d-inline-block" style="font-size: 24px;">
                                        <i class="lab la-instagram"></i>
                                    </a>
                                </li>
                            @endif
                            @if (!empty(get_setting('facebook_link')))
                                <li class="list-inline-item mr-3">
                                    <a href="{{ get_setting('facebook_link') }}" target="_blank" class="social-icon-link social-facebook text-white d-inline-block" style="font-size: 24px;">
                                        <i class="lab la-facebook-f"></i>
                                    </a>
                                </li>
                            @endif
                            @if (!empty(get_setting('twitter_link')))
                                <li class="list-inline-item mr-3">
                                    <a href="{{ get_setting('twitter_link') }}" target="_blank" class="social-icon-link social-twitter text-white d-inline-block" style="font-size: 24px;">
                                        <i class="lab la-twitter"></i>
                                    </a>
                                </li>
                            @endif
                            @if (!empty(get_setting('youtube_link')))
                                <li class="list-inline-item mr-3">
                                    <a href="{{ get_setting('youtube_link') }}" target="_blank" class="social-icon-link social-youtube text-white d-inline-block" style="font-size: 24px;">
                                        <i class="lab la-youtube"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif

                <p class="fs-13 text-white opacity-75 mb-3">{{ translate('Be one of the first to receive new product launches, sale offers, collabs & more.') }}</p>
                
                <!-- Newsletter Signup -->
                @if(get_setting('newsletter_activation'))
                    <form method="POST" action="{{ route('subscribers.store') }}">
                        @csrf
                        <div class="position-relative">
                            <input type="email" name="email" class="form-control bg-transparent text-white px-3 py-2" style="border: 1px solid #fff; border-radius: 25px; padding-right: 45px !important;" placeholder="{{ translate('Your email') }}" required>
                            <button type="submit" class="btn btn-link text-white position-absolute" style="right: 5px; top: 50%; transform: translateY(-50%); padding: 0; width: 35px; height: 35px;">
                                <i class="las la-arrow-right fs-18"></i>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Mobile Apps & Brand Section -->
        <div class="row py-4 border-top d-none d-lg-flex" style="border-color: #333 !important;">
            <!-- Language/Currency Selector Area -->
            <div class="col-lg-6">
                <div class="d-flex align-items-center flex-wrap">
                    <!-- You can add country flags here if needed -->
                    <span class="text-white fs-13 opacity-75">{{ translate('Bangladesh | BDT') }}</span>
                </div>
            </div>
            
            <!-- Mobile Apps -->
            <div class="col-lg-6 text-lg-right">
            <div class="mb-0 d-sm-none d-md-block">
            <a href="{{ route('home') }}" class="d-inline-block">
                @if(get_setting('footer_logo') != null)
                    <img class="lazyload h-45px" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset(get_setting('footer_logo')) }}" alt="{{ env('APP_NAME') }}" height="45">
                @else
                    <img class="lazyload h-45px" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" height="45">
                @endif
            </a>
        </div>
                <!-- @if((get_setting('play_store_link') != null) || (get_setting('app_store_link') != null))
                    <div class="d-inline-flex align-items-center">
                        @if(get_setting('play_store_link') != null)
                            <a href="{{ get_setting('play_store_link') }}" target="_blank" class="mr-3">
                                <img class="lazyload" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ static_asset('assets/img/play.png') }}" alt="Play Store" height="40">
                            </a>
                        @endif
                        @if(get_setting('app_store_link') != null)
                            <a href="{{ get_setting('app_store_link') }}" target="_blank">
                                <img class="lazyload" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ static_asset('assets/img/app.png') }}" alt="App Store" height="40">
                            </a>
                        @endif
                    </div>
                @endif -->
            </div>
        </div>
    </div>
</section>

<!-- Mobile Accordion Footer Widgets -->
<section class="d-lg-none text-light p-4" style="background-color: #000000 !important;">
    <div class="container py-4">
        <!-- Company -->
        <div class="aiz-accordion-wrap" style="background-color: #000000;">
            <div class="aiz-accordion-heading" style="background-color: #000000; border-bottom: 1px solid #333;">
                <button class="aiz-accordion fs-14 text-white bg-transparent py-3">{{ get_setting('widget_one',null,App::getLocale()) ?: translate('Company') }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #000000 !important;">
                <ul class="list-unstyled py-3">
                    @if ( get_setting('widget_one_labels',null,App::getLocale()) !=  null )
                        @foreach (json_decode( get_setting('widget_one_labels',null,App::getLocale()), true) as $key => $value)
                        @php
                            $widget_one_links = '';
                            if(isset(json_decode(get_setting('widget_one_links'), true)[$key])) {
                                $widget_one_links = json_decode(get_setting('widget_one_links'), true)[$key];
                            }
                        @endphp
                        <li class="mb-2">
                            <a href="{{ $widget_one_links }}" class="fs-14 text-white opacity-75">
                                {{ $value }}
                            </a>
                        </li>
                        @endforeach
                    @endif
                    <li class="mt-3">
                        <p class="fs-14 text-white mb-2">{{ get_setting('contact_phone') }}</p>
                        <a href="mailto:{{ get_setting('contact_email') }}" class="fs-14 text-white opacity-75">{{ get_setting('contact_email')  }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Customer Service -->
        <div class="aiz-accordion-wrap" style="background-color: #000000;">
            <div class="aiz-accordion-heading" style="background-color: #000000; border-bottom: 1px solid #333;">
                <button class="aiz-accordion fs-14 text-white bg-transparent py-3">{{ translate('Customer Service') }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #000000 !important;">
                <ul class="list-unstyled py-3">
                    <li class="mb-2">
                        <a href="{{ route('terms') }}" class="fs-14 text-white opacity-75">
                            {{ translate('Terms & Conditions') }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('returnpolicy') }}" class="fs-14 text-white opacity-75">
                            {{ translate('Return Policy') }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('supportpolicy') }}" class="fs-14 text-white opacity-75">
                            {{ translate('Support Policy') }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('privacypolicy') }}" class="fs-14 text-white opacity-75">
                            {{ translate('Privacy Policy') }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('orders.track') }}" class="fs-14 text-white opacity-75">
                            {{ translate('Track Order') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- My Account -->
        <div class="aiz-accordion-wrap" style="background-color: #000000;">
            <div class="aiz-accordion-heading" style="background-color: #000000; border-bottom: 1px solid #333;">
                <button class="aiz-accordion fs-14 text-white bg-transparent py-3">{{ translate('My Account') }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #000000 !important;">
                <ul class="list-unstyled py-3">
                    @if (Auth::check())
                        <li class="mb-2">
                            <a class="fs-14 text-white opacity-75" href="{{ route('logout') }}">
                                {{ translate('Logout') }}
                            </a>
                        </li>
                    @else
                        <li class="mb-2">
                            <a class="fs-14 text-white opacity-75" href="{{ route('user.login') }}">
                                {{ translate('Login') }}
                            </a>
                        </li>
                    @endif
                    <li class="mb-2">
                        <a class="fs-14 text-white opacity-75" href="{{ route('purchase_history.index') }}">
                            {{ translate('Order History') }}
                        </a>
                    </li>
                    <li class="mb-2">
                        <a class="fs-14 text-white opacity-75" href="{{ route('wishlists.index') }}">
                            {{ translate('My Wishlist') }}
                        </a>
                    </li>
                    @if (get_setting('vendor_system_activation') == 1)
                        <li class="mb-2">
                            <a class="fs-14 text-white opacity-75" href="{{ route(get_setting('seller_registration_verify') === '1' ? 'shop-reg.verification' : 'shops.create')  }}">
                                {{ translate('Become A Seller') }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Stay Connected -->
        <div class="aiz-accordion-wrap" style="background-color: #000000;">
            <div class="aiz-accordion-heading" style="background-color: #000000; border-bottom: 1px solid #333;">
                <button class="aiz-accordion fs-14 text-white bg-transparent py-3">{{ translate('Stay Connected') }}</button>
            </div>
            <div class="aiz-accordion-panel bg-transparent" style="background-color: #000000 !important;">
                <div class="py-3">
                    <!-- Social Icons -->
                    @if ( get_setting('show_social_links') )
                        <div class="mb-3">
                            <ul class="list-inline mb-0">
                                @if (!empty(get_setting('instagram_link')))
                                    <li class="list-inline-item mr-3">
                                        <a href="{{ get_setting('instagram_link') }}" target="_blank" class="social-icon-link social-instagram text-white" style="font-size: 24px;">
                                            <i class="lab la-instagram"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (!empty(get_setting('facebook_link')))
                                    <li class="list-inline-item mr-3">
                                        <a href="{{ get_setting('facebook_link') }}" target="_blank" class="social-icon-link social-facebook text-white" style="font-size: 24px;">
                                            <i class="lab la-facebook-f"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (!empty(get_setting('twitter_link')))
                                    <li class="list-inline-item mr-3">
                                        <a href="{{ get_setting('twitter_link') }}" target="_blank" class="social-icon-link social-twitter text-white" style="font-size: 24px;">
                                            <i class="lab la-twitter"></i>
                                        </a>
                                    </li>
                                @endif
                                @if (!empty(get_setting('youtube_link')))
                                    <li class="list-inline-item mr-3">
                                        <a href="{{ get_setting('youtube_link') }}" target="_blank" class="social-icon-link social-youtube text-white" style="font-size: 24px;">
                                            <i class="lab la-youtube"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <p class="fs-13 text-white opacity-75 mb-3">{{ translate('Be one of the first to receive new product launches, sale offers, collabs & more.') }}</p>
                    
                    <!-- Newsletter Signup -->
                    @if(get_setting('newsletter_activation'))
                        <form method="POST" action="{{ route('subscribers.store') }}">
                            @csrf
                            <div class="position-relative">
                                <input type="email" name="email" class="form-control bg-transparent text-white px-3 py-2" style="border: 1px solid #fff; border-radius: 25px; padding-right: 45px !important;" placeholder="{{ translate('Your email') }}" required>
                                <button type="submit" class="btn btn-link text-white position-absolute" style="right: 5px; top: 50%; transform: translateY(-50%); padding: 0; width: 35px; height: 35px;">
                                    <i class="las la-arrow-right fs-18"></i>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mobile Apps -->
        @if((get_setting('play_store_link') != null) || (get_setting('app_store_link') != null))
            <div class="py-4 border-top" style="border-color: #333 !important;">
                <div class="d-flex align-items-center">
                    @if(get_setting('play_store_link') != null)
                        <a href="{{ get_setting('play_store_link') }}" target="_blank" class="mr-3">
                            <img class="lazyload" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ static_asset('assets/img/play.png') }}" alt="Play Store" height="40">
                        </a>
                    @endif
                    @if(get_setting('app_store_link') != null)
                        <a href="{{ get_setting('app_store_link') }}" target="_blank">
                            <img class="lazyload" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ static_asset('assets/img/app.png') }}" alt="App Store" height="40">
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>

<!-- FOOTER COPYRIGHT BAR -->
<footer class="pt-1 pb-1 pb-xl-1 text-white" style="background-color: #4a3f35;">
    <div class="container">
        <div class="row align-items-center py-3">
            <!-- Copyright -->
            <div class="col-lg-6 col-md-6 order-1 order-md-0 mb-3 mb-md-0">
                <div class="text-center text-md-left fs-13" current-verison="{{get_setting("current_version")}}">
                    {!! get_setting('frontend_copyright_text', null, App::getLocale()) !!}
                </div>
            </div>

            <!-- Back to Top & Payment Methods -->
            <div class="col-lg-6 col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center justify-content-center justify-content-md-end">
                    <!-- Payment Method Images -->
                    @if ( get_setting('payment_method_images') !=  null )
                        <ul class="list-inline mb-0 mr-4">
                            @foreach (explode(',', get_setting('payment_method_images')) as $key => $value)
                                <li class="list-inline-item mr-2">
                                    <img src="{{ uploaded_asset($value) }}" height="20" class="mw-100 h-auto" style="max-height: 20px" alt="{{ translate('payment_method') }}">
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    
                    <!-- Back to Top -->
                    <a href="#" onclick="scrollToTop(); return false;" class="text-white text-decoration-none fs-13 d-flex align-items-center hover-opacity-75">
                        <span class="mr-2">{{ translate('Back to top') }}</span>
                        <i class="las la-arrow-up arrow-up-animated"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
</script>

<style>
    /* Footer Link Hover Effects */
    .footer-links a:hover,
    .opacity-75:hover {
        opacity: 1 !important;
        transition: opacity 0.3s ease;
    }
    
    .hover-opacity-100:hover {
        opacity: 1 !important;
        transition: opacity 0.3s ease;
    }
    
    .hover-opacity-75:hover {
        opacity: 0.75 !important;
        transition: opacity 0.3s ease;
    }
    
    /* Animated Arrow Up Icon */
    .arrow-up-animated {
        animation: bounceUpDown 1.5s ease-in-out infinite;
        display: inline-block;
    }
    
    @keyframes bounceUpDown {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-8px);
        }
    }
    
    /* Footer List Link Hover Effects */
    .footer-list-link {
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 4px 0;
        position: relative;
    }
    
    .footer-list-link .footer-arrow-icon {
        font-size: 14px;
        margin-right: 8px;
        opacity: 0.5;
        transform: translateX(0);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .footer-list-link span {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .footer-list-link:hover {
        color: #667eea !important;
        opacity: 1 !important;
        text-decoration: none;
        transform: translateX(5px);
    }
    
    .footer-list-link:hover span {
        font-size: 15px !important;
        font-weight: 500;
    }
    
    .footer-list-link:hover .footer-arrow-icon {
        opacity: 1;
        transform: translateX(5px);
        color: #667eea;
        animation: arrowPulse 0.6s ease-in-out;
    }
    
    @keyframes arrowPulse {
        0%, 100% {
            transform: translateX(5px) scale(1);
        }
        50% {
            transform: translateX(8px) scale(1.1);
        }
    }
    
    /* Social Icon Hover Effects */
    .social-icon-link {
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .social-icon-link:hover {
        transform: translateY(-3px) scale(1.1);
        text-decoration: none;
    }
    
    /* Instagram - Gradient Pink to Purple */
    .social-instagram:hover {
        background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Facebook - Blue */
    .social-facebook:hover {
        color: #1877F2 !important;
    }
    
    /* Twitter - Light Blue */
    .social-twitter:hover {
        color: #1DA1F2 !important;
    }
    
    /* YouTube - Red */
    .social-youtube:hover {
        color: #FF0000 !important;
    }
</style>

<!-- Mobile bottom nav -->
<div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom border-top border-sm-bottom border-sm-left border-sm-right mx-auto mb-sm-2" style="background-color: rgb(255 255 255 / 90%)!important;">
    <div class="row align-items-center gutters-5">
        <!-- Home -->
        <div class="col">
            <a href="{{ route('home') }}" class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['home'],'svg-active')}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_24768" data-name="Group 24768" transform="translate(3495.144 -602)">
                      <path id="Path_2916" data-name="Path 2916" d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z" transform="translate(-3495.144 602)" fill="#b5b5bf"/>
                    </g>
                </svg>
                <span class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['home'],'text-primary')}}">{{ translate('Home') }}</span>
            </a>
        </div>

        <!-- Categories -->
        <div class="col">
            <a href="{{ route('categories.all') }}" class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['categories.all'],'svg-active')}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_25497" data-name="Group 25497" transform="translate(3373.432 -602)">
                      <path id="Path_2917" data-name="Path 2917" d="M126.713,0h-5V5a2,2,0,0,0,2,2h3a2,2,0,0,0,2-2V2a2,2,0,0,0-2-2m1,5a1,1,0,0,1-1,1h-3a1,1,0,0,1-1-1V1h4a1,1,0,0,1,1,1Z" transform="translate(-3495.144 602)" fill="#91919c"/>
                      <path id="Path_2918" data-name="Path 2918" d="M144.713,18h-3a2,2,0,0,0-2,2v3a2,2,0,0,0,2,2h5V20a2,2,0,0,0-2-2m1,6h-4a1,1,0,0,1-1-1V20a1,1,0,0,1,1-1h3a1,1,0,0,1,1,1Z" transform="translate(-3504.144 593)" fill="#91919c"/>
                      <path id="Path_2919" data-name="Path 2919" d="M143.213,0a3.5,3.5,0,1,0,3.5,3.5,3.5,3.5,0,0,0-3.5-3.5m0,6a2.5,2.5,0,1,1,2.5-2.5,2.5,2.5,0,0,1-2.5,2.5" transform="translate(-3504.144 602)" fill="#91919c"/>
                      <path id="Path_2920" data-name="Path 2920" d="M125.213,18a3.5,3.5,0,1,0,3.5,3.5,3.5,3.5,0,0,0-3.5-3.5m0,6a2.5,2.5,0,1,1,2.5-2.5,2.5,2.5,0,0,1-2.5,2.5" transform="translate(-3495.144 593)" fill="#91919c"/>
                    </g>
                </svg>
                <span class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['categories.all'],'text-primary')}}">{{ translate('Categories') }}</span>
            </a>
        </div>

        <!-- Cart -->
        @php
            $count = count(get_user_cart());
        @endphp
        <div class="col-auto">
            <a href="{{ route('cart') }}" class="text-secondary d-block text-center pb-2 pt-3 px-3 {{ areActiveRoutes(['cart'],'svg-active')}}">
                <span class="d-inline-block position-relative px-2">
                    <svg id="Group_25499" data-name="Group 25499" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16.001" height="16" viewBox="0 0 16.001 16">
                        <defs>
                        <clipPath id="clip-pathw">
                            <rect id="Rectangle_1383" data-name="Rectangle 1383" width="16" height="16" fill="#91919c"/>
                        </clipPath>
                        </defs>
                        <g id="Group_8095" data-name="Group 8095" transform="translate(0 0)" clip-path="url(#clip-pathw)">
                        <path id="Path_2926" data-name="Path 2926" d="M8,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1" transform="translate(-3 -11.999)" fill="#91919c"/>
                        <path id="Path_2927" data-name="Path 2927" d="M24,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1" transform="translate(-10.999 -11.999)" fill="#91919c"/>
                        <path id="Path_2928" data-name="Path 2928" d="M15.923,3.975A1.5,1.5,0,0,0,14.5,2h-9a.5.5,0,1,0,0,1h9a.507.507,0,0,1,.129.017.5.5,0,0,1,.355.612l-1.581,6a.5.5,0,0,1-.483.372H5.456a.5.5,0,0,1-.489-.392L3.1,1.176A1.5,1.5,0,0,0,1.632,0H.5a.5.5,0,1,0,0,1H1.544a.5.5,0,0,1,.489.392L3.9,9.826A1.5,1.5,0,0,0,5.368,11h7.551a1.5,1.5,0,0,0,1.423-1.026Z" transform="translate(0 -0.001)" fill="#91919c"/>
                        </g>
                    </svg>
                    @if($count > 0)
                        <span class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right" style="right: 5px;top: -2px;"></span>
                    @endif
                </span>
                <span class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['cart'],'text-primary')}}">
                    {{ translate('Cart') }}
                    (<span class="cart-count">{{$count}}</span>)
                </span>
            </a>
        </div>

        @if (Auth::check() && auth()->user()->user_type == 'customer')
            <!-- Notifications -->
            <div class="col">
                <a href="{{ route('customer.all-notifications') }}" class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['customer.all-notifications'],'svg-active')}}">
                    <span class="d-inline-block position-relative px-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                            <path id="ecf3cc267cd87627e58c1954dc6fbcc2" d="M5.488,14.056a.617.617,0,0,0-.8-.016.6.6,0,0,0-.082.855A2.847,2.847,0,0,0,6.835,16h0l.174-.007a2.846,2.846,0,0,0,2.048-1.1h0l.053-.073a.6.6,0,0,0-.134-.782.616.616,0,0,0-.862.081,1.647,1.647,0,0,1-.334.331,1.591,1.591,0,0,1-2.222-.331H5.55ZM6.828,0C4.372,0,1.618,1.732,1.306,4.512h0v1.45A3,3,0,0,1,.6,7.37a.535.535,0,0,0-.057.077A3.248,3.248,0,0,0,0,9.088H0l.021.148a3.312,3.312,0,0,0,.752,2.2,3.909,3.909,0,0,0,2.5,1.232,32.525,32.525,0,0,0,7.1,0,3.865,3.865,0,0,0,2.456-1.232A3.264,3.264,0,0,0,13.6,9.249h0v-.1a3.361,3.361,0,0,0-.582-1.682h0L12.96,7.4a3.067,3.067,0,0,1-.71-1.408h0V4.54l-.039-.081a.612.612,0,0,0-1.132.208h0v1.45a.363.363,0,0,0,0,.077,4.21,4.21,0,0,0,.979,1.957,2.022,2.022,0,0,1,.312,1h0v.155a2.059,2.059,0,0,1-.468,1.373,2.656,2.656,0,0,1-1.661.788,32.024,32.024,0,0,1-6.87,0,2.663,2.663,0,0,1-1.7-.824,2.037,2.037,0,0,1-.447-1.33h0V9.151a2.1,2.1,0,0,1,.305-1.007A4.212,4.212,0,0,0,2.569,6.187a.363.363,0,0,0,0-.077h0V4.653a4.157,4.157,0,0,1,4.2-3.442,4.608,4.608,0,0,1,2.257.584h0l.084.042A.615.615,0,0,0,9.649,1.8.6.6,0,0,0,9.624.739,5.8,5.8,0,0,0,6.828,0Z" fill="#91919b"/>
                        </svg>
                        @if(Auth::check() && count(Auth::user()->unreadNotifications) > 0)
                            <span class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right" style="right: 5px;top: -2px;"></span>
                        @endif
                    </span>
                    <span class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['customer.all-notifications'],'text-primary')}}">{{ translate('Notifications') }}</span>
                </a>
            </div>
        @endif

        <!-- Account -->
        <div class="col">
            @if (Auth::check())
                @if(isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-secondary d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @elseif(isSeller())
                    <a href="{{ route('dashboard') }}" class="text-secondary d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @else
                    <a href="javascript:void(0)" class="text-secondary d-block text-center pb-2 pt-3 mobile-side-nav-thumb" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                        <span class="d-block mx-auto">
                            @if($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}" alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @endif
            @else
                <a href="{{ route('user.login') }}" class="text-secondary d-block text-center pb-2 pt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <g id="Group_8094" data-name="Group 8094" transform="translate(3176 -602)">
                          <path id="Path_2924" data-name="Path 2924" d="M331.144,0a4,4,0,1,0,4,4,4,4,0,0,0-4-4m0,7a3,3,0,1,1,3-3,3,3,0,0,1-3,3" transform="translate(-3499.144 602)" fill="#b5b5bf"/>
                          <path id="Path_2925" data-name="Path 2925" d="M332.144,20h-10a3,3,0,0,0,0,6h10a3,3,0,0,0,0-6m0,5h-10a2,2,0,0,1,0-4h10a2,2,0,0,1,0,4" transform="translate(-3495.144 592)" fill="#b5b5bf"/>
                        </g>
                    </svg>
                    <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                </a>
            @endif
        </div>

    </div>
</div>

@if (Auth::check() && auth()->user()->user_type == 'customer')
    <!-- User Side nav -->
    <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
        <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
        <div class="collapse-sidebar bg-white">
            @include('frontend.inc.user_side_nav')
        </div>
    </div>
@endif
</div>