<!DOCTYPE html>

@php
    $rtl = get_session_language()->rtl;
@endphp

@if ($rtl == 1)
    <html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <!-- Google Tag Manager -->
    @if (get_setting('google_tag_manager') == 1 && env('GOOGLE_TAG_MANAGER_ID'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ env('GOOGLE_TAG_MANAGER_ID') }}');</script>
    <!-- End Google Tag Manager -->
    @endif

    <title>@yield('meta_title', get_setting('website_name') . ' | ' . get_setting('site_motto'))</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('meta_description', get_setting('meta_description'))" />
    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords'))">

    @yield('meta')

    @if (!isset($detailedProduct) && !isset($customer_product) && !isset($shop) && !isset($page) && !isset($blog))
        @php
            $meta_image = uploaded_asset(get_setting('meta_image'));
        @endphp
        <!-- Schema.org markup for Google+ -->
        <meta itemprop="name" content="{{ get_setting('meta_title') }}">
        <meta itemprop="description" content="{{ get_setting('meta_description') }}">
        <meta itemprop="image" content="{{ $meta_image }}">

        <!-- Twitter Card data -->
        <meta name="twitter:card" content="product">
        <meta name="twitter:site" content="@publisher_handle">
        <meta name="twitter:title" content="{{ get_setting('meta_title') }}">
        <meta name="twitter:description" content="{{ get_setting('meta_description') }}">
        <meta name="twitter:creator" content="@author_handle">
        <meta name="twitter:image" content="{{ $meta_image }}">

        <!-- Open Graph data -->
        <meta property="og:title" content="{{ get_setting('meta_title') }}" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ route('home') }}" />
        <meta property="og:image" content="{{ $meta_image }}" />
        <meta property="og:description" content="{{ get_setting('meta_description') }}" />
        <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
        <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    @endif

    <!-- Favicon -->
    @php
        $site_icon = uploaded_asset(get_setting('site_icon'));
    @endphp
    <link rel="icon" href="{{ $site_icon }}">
    <link rel="apple-touch-icon" href="{{ $site_icon }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    @if ($rtl == 1)
        <link rel="stylesheet" href="{{ static_asset('assets/css/bootstrap-rtl.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css?v=') }}{{ rand(1000, 9999) }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css') }}">


    <script>
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
        }
    </script>

    <style>
        :root{
            --blue: #3490f3;
            --hov-blue: #2e7fd6;
            --soft-blue: rgba(0, 123, 255, 0.15);
            --secondary-base: {{ get_setting('secondary_base_color', '#ffc519') }};
            --hov-secondary-base: {{ get_setting('secondary_base_hov_color', '#dbaa17') }};
            --soft-secondary-base: {{ hex2rgba(get_setting('secondary_base_color', '#ffc519'), 0.15) }};
            --gray: #9d9da6;
            --gray-dark: #8d8d8d;
            --secondary: #919199;
            --soft-secondary: rgba(145, 145, 153, 0.15);
            --success: #85b567;
            --soft-success: rgba(133, 181, 103, 0.15);
            --warning: #f3af3d;
            --soft-warning: rgba(243, 175, 61, 0.15);
            --light: #f5f5f5;
            --soft-light: #dfdfe6;
            --soft-white: #b5b5bf;
            --dark: #292933;
            --soft-dark: #1b1b28;
            --primary: {{ get_setting('base_color', '#d43533') }};
            --hov-primary: {{ get_setting('base_hov_color', '#9d1b1a') }};
            --soft-primary: {{ hex2rgba(get_setting('base_color', '#d43533'), 0.15) }};
        }
        body{
            font-family: 'Public Sans', sans-serif;
            font-weight: 400;
        }

        .pagination .page-link,
        .page-item.disabled .page-link {
            min-width: 32px;
            min-height: 32px;
            line-height: 32px;
            text-align: center;
            padding: 0;
            border: 1px solid var(--soft-light);
            font-size: 0.875rem;
            border-radius: 0 !important;
            color: var(--dark);
        }
        .pagination .page-item {
            margin: 0 5px;
        }

        .form-control:focus {
            border-width: 2px !important;
        }
        .iti__flag-container {
            padding: 2px;
        }
        .modal-content {
            border: 0 !important;
            border-radius: 0 !important;
        }

        .tagify.tagify--focus{
            border-width: 2px;
            border-color: var(--primary);
        }

        #map{
            width: 100%;
            height: 250px;
        }
        #edit_map{
            width: 100%;
            height: 250px;
        }

        .pac-container { z-index: 100000; }
    </style>

@if (get_setting('google_analytics') == 1 && env('TRACKING_ID'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('TRACKING_ID') }}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('TRACKING_ID') }}');
    </script>
@endif

@if (get_setting('facebook_pixel') == 1 && env('FACEBOOK_PIXEL_ID'))
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
@endif

@php
    echo get_setting('header_script');
@endphp

</head>
<body>
    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column bg-white">
        @php
            $user = auth()->user();
            $user_avatar = null;
            $carts = [];
            if ($user && $user->avatar_original != null) {
                $user_avatar = uploaded_asset($user->avatar_original);
            }

            $system_language = get_system_language();
        @endphp
        <!-- Header -->
        @include('frontend.inc.nav')

        @yield('content')

        <!-- footer -->
        @include('frontend.inc.footer')

    </div>

    @if(get_setting('use_floating_buttons') == 1)
        <!-- Floating Buttons -->
        @include('frontend.inc.floating_buttons')
    @endif

    <div class="aiz-refresh">
        <div class="aiz-refresh-content"><div></div><div></div><div></div></div>
    </div>


    @if (env("DEMO_MODE") == "On")
        <!-- demo nav -->
        @include('frontend.inc.demo_nav')
    @endif

    <!-- cookies agreement -->
    @php
        $alert_location = get_setting('custom_alert_location');
        $order = in_array($alert_location, ['top-left', 'top-right']) ? 'asc' : 'desc';
        $custom_alerts = App\Models\CustomAlert::where('status', 1)->orderBy('id', $order)->get();
    @endphp

    <div class="aiz-custom-alert {{ get_setting('custom_alert_location') }}">
        @foreach ($custom_alerts as $custom_alert)
            @if($custom_alert->id == 1)
                <div class="aiz-cookie-alert mb-3" style="box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.24);">
                    <div class="p-3 px-lg-2rem rounded-0" style="background: {{ $custom_alert->background_color }};">
                        <div class="text-{{ $custom_alert->text_color }} mb-3">
                            {!! $custom_alert->description !!}
                        </div>
                        <button class="btn btn-block btn-primary rounded-0 aiz-cookie-accept">
                            {{ translate('Ok. I Understood') }}
                        </button>
                    </div>
                </div>
            @else
                <div class="mb-3 custom-alert-box removable-session d-none" data-key="custom-alert-box-{{ $custom_alert->id }}" data-value="removed" style="box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.24);">
                    <div class="rounded-0 position-relative" style="background: {{ $custom_alert->background_color }};">
                        <a href="{{ $custom_alert->link }}" class="d-block h-100 w-100">
                            <div class="@if ($custom_alert->type == 'small') d-flex @endif">
                                <img class="@if ($custom_alert->type == 'small') h-140px w-120px img-fit @else w-100 @endif" src="{{ uploaded_asset($custom_alert->banner) }}" alt="custom_alert">
                                <div class="text-{{ $custom_alert->text_color }} p-2rem">
                                    {!! $custom_alert->description !!}
                                </div>
                            </div>
                        </a>
                        <button class="absolute-top-right bg-transparent btn btn-circle btn-icon d-flex align-items-center justify-content-center text-{{ $custom_alert->text_color }} hov-text-primary set-session" data-key="custom-alert-box-{{ $custom_alert->id }}" data-value="removed" data-toggle="remove-parent" data-parent=".custom-alert-box">
                            <i class="la la-close fs-20"></i>
                        </button>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- website popup -->
    @php
        $dynamic_popups = App\Models\DynamicPopup::where('status', 1)->orderBy('id', 'asc')->get();
        use App\Models\Order;
        use App\Models\OrderDetail;
    @endphp
    @foreach ($dynamic_popups as $key => $dynamic_popup)
        @php
        $showPopup = true;
        if ($dynamic_popup->id == 100 ) {
            if(auth()->user()){
            $userOrderIds = Order::where('user_id', auth()->user()->id)->pluck('id');
            $hasUnreviewed = OrderDetail::whereIn('order_id', $userOrderIds)
            ->where('delivery_status', 'delivered')
                            ->where('reviewed', 0)
                            ->exists();
            $showPopup = $hasUnreviewed;
            }else{
              $showPopup= false;  
            }
        }
        @endphp

        @if($dynamic_popup->id == 1)
            <style>
                .modern-popup-wrapper {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: rgba(0, 0, 0, 0.5);
                    backdrop-filter: blur(4px);
                }
                .modern-popup-content {
                    background: #ffffff;
                    border-radius: 16px;
                    max-width: 500px;
                    width: 90%;
                    max-height: 90vh;
                    overflow: hidden;
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                }
                .modern-popup-image-section {
                    flex: 0 0 250px;
                    background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 0;
                    position: relative;
                    overflow: hidden;
                }
                .modern-popup-image-section img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    object-position: center;
                    border-radius: 0;
                    display: block;
                }
                .modern-popup-content-section {
                    flex: 1;
                    padding: 30px 25px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }
                .modern-popup-close-btn {
                    position: absolute;
                    top: 15px;
                    right: 15px;
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    background: #000000;
                    color: #ffffff;
                    border: none;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    z-index: 10;
                    transition: all 0.3s ease;
                    font-size: 18px;
                }
                .modern-popup-close-btn:hover {
                    background: #333333;
                    transform: rotate(90deg);
                }
                .modern-popup-title {
                    font-size: 28px;
                    font-weight: 700;
                    color: #000000;
                    line-height: 1.1;
                    margin-bottom: 6px;
                    text-align: left;
                }
                .modern-popup-subtitle {
                    font-size: 16px;
                    font-weight: 600;
                    color: #000000;
                    margin-bottom: 8px;
                    text-align: left;
                }
                .modern-popup-summary {
                    font-size: 13px;
                    color: #000000;
                    text-align: center;
                    margin-bottom: 20px;
                    font-weight: 400;
                }
                .modern-popup-email-form {
                    display: flex;
                    gap: 0;
                    margin-bottom: 12px;
                }
                .modern-popup-email-input {
                    flex: 1;
                    padding: 12px 16px;
                    border: 1px solid #000000;
                    border-right: none;
                    border-radius: 0;
                    font-size: 13px;
                    outline: none;
                }
                .modern-popup-email-input:focus {
                    border-color: #000000;
                }
                .modern-popup-submit-btn {
                    width: 46px;
                    height: 46px;
                    background: {{ $dynamic_popup->btn_background_color ?? '#000000' }};
                    color: {{ $dynamic_popup->btn_text_color ?? '#ffffff' }};
                    border: 1px solid #000000;
                    border-left: none;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    font-size: 22px;
                    transition: all 0.3s ease;
                }
                .modern-popup-submit-btn:hover {
                    background: #333333;
                }
                .modern-popup-email-note {
                    font-size: 11px;
                    color: #666666;
                    margin-bottom: 15px;
                }
                .modern-popup-checkbox-wrapper {
                    display: flex;
                    align-items: flex-start;
                    gap: 10px;
                }
                .modern-popup-checkbox {
                    width: 17px;
                    height: 17px;
                    margin-top: 1px;
                    cursor: pointer;
                    accent-color: #000000;
                }
                .modern-popup-terms-text {
                    font-size: 10px;
                    color: #000000;
                    line-height: 1.4;
                }
                .modern-popup-terms-text a {
                    color: #000000;
                    text-decoration: underline;
                    font-weight: 600;
                }
                .modern-popup-terms-text a:hover {
                    text-decoration: none;
                }
                @media (max-width: 768px) {
                    .modern-popup-content {
                        flex-direction: column;
                        max-width: 95%;
                        max-height: 85vh;
                    }
                    .modern-popup-image-section {
                        flex: 0 0 150px;
                        padding: 0;
                    }
                    .modern-popup-image-section img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        object-position: center;
                    }
                    .modern-popup-content-section {
                        padding: 20px 18px;
                    }
                    .modern-popup-title {
                        font-size: 22px;
                        margin-bottom: 6px;
                    }
                    .modern-popup-subtitle {
                        font-size: 14px;
                        margin-bottom: 8px;
                    }
                    .modern-popup-summary {
                        font-size: 11px;
                        margin-bottom: 20px;
                    }
                    .modern-popup-email-input {
                        padding: 10px 14px;
                        font-size: 12px;
                    }
                    .modern-popup-submit-btn {
                        width: 42px;
                        height: 42px;
                        font-size: 20px;
                    }
                    .modern-popup-email-note {
                        font-size: 10px;
                        margin-bottom: 15px;
                    }
                    .modern-popup-checkbox {
                        width: 16px;
                        height: 16px;
                        margin-top: 1px;
                    }
                    .modern-popup-terms-text {
                        font-size: 9px;
                        line-height: 1.4;
                    }
                    .modern-popup-close-btn {
                        width: 30px;
                        height: 30px;
                        top: 10px;
                        right: 10px;
                        font-size: 16px;
                    }
                }
            </style>
            <div class="modal website-popup removable-session d-none" data-key="website-popup" data-value="removed">
                <div class="modern-popup-wrapper">
                    <div class="modern-popup-content">
                        <button class="modern-popup-close-btn set-session" data-key="website-popup" data-value="removed" data-toggle="remove-parent" data-parent=".website-popup">
                            ×
                        </button>
                        <div class="modern-popup-image-section">
                            @if($dynamic_popup->banner)
                                <img src="{{ uploaded_asset($dynamic_popup->banner) }}" alt="dynamic_popup" style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="las la-gift" style="font-size: 80px; color: #9fa8da;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="modern-popup-content-section">
                            <h1 class="modern-popup-title">{{ $dynamic_popup->title }}</h1>
                            @if($dynamic_popup->summary)
                                <p class="modern-popup-summary">{{ $dynamic_popup->summary }}</p>
                            @endif
                            @if ($dynamic_popup->show_subscribe_form == 'on')
                                <form method="POST" action="{{ route('subscribers.store') }}" id="popup-subscribe-form">
                                    @csrf
                                    <div class="modern-popup-email-form">
                                        <input type="email" class="modern-popup-email-input" placeholder="{{ translate('Enter Your Email Address') }}" name="email" required>
                                        <button type="submit" class="modern-popup-submit-btn">
                                            +
                                        </button>
                                    </div>
                                    <p class="modern-popup-email-note">{{ translate('You will receive an email with the offer code.') }}</p>
                                    <div class="modern-popup-checkbox-wrapper">
                                        <input type="checkbox" class="modern-popup-checkbox" id="popup-terms-checkbox" required>
                                        <label for="popup-terms-checkbox" class="modern-popup-terms-text">
                                            {{ translate('By entering your email, you agree to our') }} 
                                            <a href="{{ route('terms') }}" target="_blank">{{ translate('Terms & Conditions') }}</a> 
                                            {{ translate('and') }} 
                                            <a href="{{ route('privacypolicy') }}" target="_blank">{{ translate('Privacy Policy') }}</a>, 
                                            {{ translate('including emails and promotions. You can unsubscribe at any time.') }}
                                        </label>
                                    </div>
                                </form>
                                <script>
                                    $(document).ready(function() {
                                        // Handle form submission
                                        $('#popup-subscribe-form').on('submit', function(e) {
                                            e.preventDefault();
                                            var form = $(this);
                                            var email = form.find('input[name="email"]').val();
                                            var checkbox = form.find('#popup-terms-checkbox');
                                            
                                            if (!checkbox.is(':checked')) {
                                                AIZ.plugins.notify('warning', '{{ translate('Please agree to the terms and conditions') }}');
                                                return false;
                                            }
                                            
                                            $.ajax({
                                                url: form.attr('action'),
                                                type: 'POST',
                                                data: form.serialize(),
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                                success: function(response) {
                                                    AIZ.plugins.notify('success', '{{ translate('You have subscribed successfully') }}');
                                                    // Close popup after successful subscription
                                                    $('.website-popup[data-key="website-popup"]').addClass('d-none');
                                                    if (typeof setSession === 'function') {
                                                        setSession('website-popup', 'removed');
                                                    }
                                                },
                                                error: function(xhr) {
                                                    if (xhr.status === 422) {
                                                        AIZ.plugins.notify('warning', '{{ translate('You are already a subscriber') }}');
                                                    } else {
                                                        AIZ.plugins.notify('error', '{{ translate('Something went wrong. Please try again.') }}');
                                                    }
                                                }
                                            });
                                        });
                                    });
                                </script>
                            @else
                                <a href="{{ $dynamic_popup->btn_link }}" class="btn btn-block mt-3 rounded-0 text-{{ $dynamic_popup->btn_text_color }} set-session" style="background: {{ $dynamic_popup->btn_background_color }}; padding: 14px; font-size: 16px; font-weight: 600;" data-key="website-popup" data-value="removed" data-toggle="remove-parent" data-parent=".website-popup">
                                    {{ $dynamic_popup->btn_text }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            @if($showPopup)
            <div class="modal website-popup removable-session d-none" data-key="website-popup-{{ $dynamic_popup->id }}" data-value="removed">
                <div class="absolute-full bg-black opacity-60"></div>
                <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-md mx-4 mx-md-auto">
                    <div class="modal-content position-relative border-0 rounded-0">
                        <div class="aiz-editor-data">
                            <div class="d-block">
                                <img class="w-100" src="{{ uploaded_asset($dynamic_popup->banner) }}" alt="dynamic_popup">
                            </div>
                        </div>
                        <div class="pb-5 pt-4 px-3 px-md-2rem">
                            <h1 class="fs-30 fw-700 text-dark">{{ $dynamic_popup->title }}</h1>
                            <p class="fs-14 fw-400 mt-3 mb-4">{{ $dynamic_popup->summary }}</p>
                            <a href="{{ $dynamic_popup->btn_link }}" class="btn btn-block mt-3 rounded-0 text-{{ $dynamic_popup->btn_text_color }} set-session" style="background: {{ $dynamic_popup->btn_background_color }};"data-key="website-popup-{{ $dynamic_popup->id }}" data-value="removed" data-toggle="remove-parent" data-parent=".website-popup">
                                {{ $dynamic_popup->btn_text }}
                            </a>
                        </div>
                        <button class="absolute-top-right bg-white shadow-lg btn btn-circle btn-icon mr-n3 mt-n3 set-session" data-key="website-popup-{{ $dynamic_popup->id }}" data-value="removed" data-toggle="remove-parent" data-parent=".website-popup">
                            <i class="la la-close fs-20"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        @endif
    @endforeach

    @include('frontend.partials.modal')

    @include('frontend.partials.account_delete_modal')

    <div class="modal fade" id="addToCart">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader text-center p-3">
                    <i class="las la-spinner la-spin la-3x"></i>
                </div>
                <button type="button" class="close absolute-top-right btn-icon close z-1 btn-circle bg-gray mr-2 mt-2 d-flex justify-content-center align-items-center" data-dismiss="modal" aria-label="Close" style="background: #ededf2; width: calc(2rem + 2px); height: calc(2rem + 2px);">
                    <span aria-hidden="true" class="fs-24 fw-700" style="margin-left: 2px;">&times;</span>
                </button>
                <div id="addToCart-modal-body">

                </div>
            </div>
        </div>
    </div>

    @yield('modal')

    <div id="videoModal" class="video-modal">
        <div class="modal-video-wrapper">
            <video id="popupVideo" style="width: 100%; height: 100%" controls disablePictureInPicture></video>
            <span id="closeModalBtn" class="close-modal">✖</span>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js?v=') }}{{ rand(1000, 9999) }}"></script>

    {{-- WhatsaApp Chat --}}
    @if (get_setting('whatsapp_chat') == 1)
        <script type="text/javascript">
            (function () {
                var options = {
                    whatsapp: "{{ env('WHATSAPP_NUMBER') }}",
                    call_to_action: "{{ translate('Message us') }}",
                    position: "right", // Position may be 'right' or 'left'
                };
                var proto = document.location.protocol, host = "getbutton.io", url = proto + "//static." + host;
                var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
                s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
                var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
            })();
        </script>
    @endif

    <style>
    .sc-q8c6tt-3 {
        bottom: 54px !important;
    }

    a[aria-label="Go to GetButton.io website"] {
        display: none !important;
    }
    
    .floating-buttons-section {
        position: fixed !important;
        bottom: 20px !important;
        left: 10px !important;
        right: auto !important;
        top: auto !important;
        z-index: 999 !important;
    }
    
</style>

    <script>
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach
    </script>

    <script>
        @if (Route::currentRouteName() == 'home' || Route::currentRouteName() == '/')

            $.post('{{ route('home.section.featured') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_featured').html(data);
                AIZ.plugins.slickCarousel();
            });

            $.post('{{ route('home.section.todays_deal') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#todays_deal').html(data);
                AIZ.plugins.slickCarousel();
            });

            $.post('{{ route('home.section.best_selling') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_best_selling').html(data);
                AIZ.plugins.slickCarousel();
            });

            $.post('{{ route('home.section.newest_products') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_newest').html(data);
                AIZ.plugins.slickCarousel();
            });

            $.post('{{ route('home.section.auction_products') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#auction_products').html(data);
                AIZ.plugins.slickCarousel();
            });

            var isPreorderEnabled = @json(addon_is_activated('preorder'));

            if (isPreorderEnabled) {
                $.post('{{ route('home.section.preorder_products') }}', {
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    $('#section_featured_preorder_products').html(data);
                    AIZ.plugins.slickCarousel();
                });
            }

            $.post('{{ route('home.section.home_categories') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });

        @endif

        $(document).ready(function() {
            $('.category-nav-element').each(function(i, el) {

                $(el).on('mouseover', function(){
                    if(!$(el).find('.sub-cat-menu').hasClass('loaded')){
                        $.post('{{ route('category.elements') }}', {
                            _token: AIZ.data.csrf,
                            id:$(el).data('id'
                            )}, function(data){
                            $(el).find('.sub-cat-menu').addClass('loaded').html(data);
                        });
                    }
                });
            });

            if ($('#lang-change').length > 0) {
                $('#lang-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e){
                        e.preventDefault();
                        var $this = $(this);
                        var locale = $this.data('flag');
                        $.post('{{ route('language.change') }}',{_token: AIZ.data.csrf, locale:locale}, function(data){
                            location.reload();
                        });

                    });
                });
            }

            if ($('#currency-change').length > 0) {
                $('#currency-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e){
                        e.preventDefault();
                        var $this = $(this);
                        var currency_code = $this.data('currency');
                        $.post('{{ route('currency.change') }}',{_token: AIZ.data.csrf, currency_code:currency_code}, function(data){
                            location.reload();
                        });

                    });
                });
            }
        });

        $('#search').on('keyup', function(){
            search();
        });

        $('#search').on('focus', function(){
            search();
        });

        function search(){
            var searchKey = $('#search').val();
            if(searchKey.length > 0){
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', { _token: AIZ.data.csrf, search:searchKey}, function(data){
                    if(data == '0'){
                        // $('.typed-search-box').addClass('d-none');
                        $('#search-content').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html('{{ translate('Sorry, nothing found for') }} <strong>"'+searchKey+'"</strong>');
                        $('.search-preloader').addClass('d-none');

                    }
                    else{
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            }
            else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }

        $(".aiz-user-top-menu").on("mouseover", function (event) {
            $(".hover-user-top-menu").addClass('active');
        })
        .on("mouseout", function (event) {
            $(".hover-user-top-menu").removeClass('active');
        });

        $(document).on("click", function(event){
            var $trigger = $("#category-menu-bar");
            if($trigger !== event.target && !$trigger.has(event.target).length){
                $("#click-category-menu").slideUp("fast");;
                $("#category-menu-bar-icon").removeClass('show');
            }
        });

        function updateNavCart(view,count){
            if (view && typeof view === 'string') {
                $('.cart-count').html(count || 0);
                
                // Extract only the dropdown menu content from the view
                // The view includes both the button and dropdown, we only need the dropdown
                var $tempDiv = $('<div>').html(view);
                
                // Find the dropdown menu (it has class 'dropdown-menu' and id 'cart_items')
                // Look for the div with both id="cart_items" and class="dropdown-menu"
                var $dropdownMenu = $tempDiv.find('div#cart_items.dropdown-menu');
                
                if ($dropdownMenu.length > 0) {
                    // Get the HTML content of the dropdown menu
                    var cartItemsContent = $dropdownMenu.html();
                    // Update the dropdown menu in the DOM
                    // Use a more specific selector: find dropdown-menu inside nav-cart-box
                    $('.nav-cart-box').find('div.dropdown-menu#cart_items').html(cartItemsContent);
                } else {
                    // Fallback: try to find any dropdown menu with id cart_items
                    var $anyDropdown = $tempDiv.find('.dropdown-menu#cart_items, #cart_items.dropdown-menu');
                    if ($anyDropdown.length > 0) {
                        $('.nav-cart-box').find('div.dropdown-menu#cart_items').html($anyDropdown.html());
                    } else {
                        // Last fallback: find any dropdown-menu
                        var $fallbackDropdown = $tempDiv.find('.dropdown-menu').first();
                        if ($fallbackDropdown.length > 0) {
                            $('.nav-cart-box').find('div.dropdown-menu').first().html($fallbackDropdown.html());
                        } else {
                            console.warn('Could not find cart dropdown content in view');
                        }
                    }
                }
                
                // Trigger lazy load for any new images
                if (typeof lazyload !== 'undefined') {
                    lazyload();
                } else if (typeof LazyLoad !== 'undefined') {
                    new LazyLoad();
                }
            }
        }

        function removeFromCart(key){
            $.post('{{ route('cart.removeFromCart') }}', {
                _token  : AIZ.data.csrf,
                id      :  key
            }, function(data){
                // Handle both JSON and array responses
                if (typeof data === 'string') {
                    try {
                        data = JSON.parse(data);
                    } catch(e) {
                        console.error('Error parsing removeFromCart response:', e);
                        return;
                    }
                }
                
                // Update cart dropdown and count
                if (data.nav_cart_view && data.cart_count !== undefined) {
                    updateNavCart(data.nav_cart_view, data.cart_count);
                }
                
                // Update cart details if provided
                if (data.cart_view) {
                    $('#cart-details').html(data.cart_view);
                }
                
                AIZ.plugins.notify('success', "{{ translate('Item has been removed from cart') }}");
                
                // Update sidenav cart count if element exists
                var sidenavCount = $('#cart_items_sidenav');
                if (sidenavCount.length > 0) {
                    var currentCount = parseInt(sidenavCount.html()) || 0;
                    sidenavCount.html(Math.max(0, currentCount - 1));
                }
            }).fail(function(xhr) {
                console.error('Error removing item from cart:', xhr);
                AIZ.plugins.notify('danger', "{{ translate('An error occurred while removing the item') }}");
            });
        }

        // Accepts either composite key (group_product_id_hash) or just group_product_id
        function removeGroupProductFromCart(groupKey){
            $.post('{{ route('cart.removeGroupProductFromCart') }}', {
                _token  : AIZ.data.csrf,
                group_key  :  groupKey
            }, function(data){
                // Handle both JSON and string responses
                if (typeof data === 'string') {
                    try {
                        data = JSON.parse(data);
                    } catch(e) {
                        console.error('Error parsing removeGroupProductFromCart response:', e);
                        // If parsing fails, try to update with empty cart
                        updateNavCart('', 0);
                        return;
                    }
                }
                
                // Update cart dropdown and count
                if (data.nav_cart_view && data.cart_count !== undefined) {
                    updateNavCart(data.nav_cart_view, data.cart_count);
                } else if (data.nav_cart_view) {
                    // If only nav_cart_view is provided, extract count from it
                    var $tempDiv = $('<div>').html(data.nav_cart_view);
                    var cartCount = $tempDiv.find('.cart-count').text() || 0;
                    updateNavCart(data.nav_cart_view, parseInt(cartCount) || 0);
                }
                
                // Update cart details if provided
                if (data.cart_view) {
                    $('#cart-details').html(data.cart_view);
                }
                
                AIZ.plugins.notify('success', "{{ translate('Bundle has been removed from cart') }}");
            }).fail(function(xhr) {
                console.error('Error removing group product:', xhr);
                AIZ.plugins.notify('danger', "{{ translate('An error occurred while removing the bundle') }}");
            });
        }

        function showLoginModal() {
            $('#login_modal').modal();
        }

        function addToCompare(id){
            $.post('{{ route('compare.addToCompare') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                $('#compare').html(data);
                AIZ.plugins.notify('success', "{{ translate('Item has been added to compare list') }}");
                $('#compare_items_sidenav').html(parseInt($('#compare_items_sidenav').html())+1);
            });
        }

        function addToWishList(id){
            @if (Auth::check() && Auth::user()->user_type == 'customer')
                $.post('{{ route('wishlists.store') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                    if(data != 0){
                        $('#wishlist').html(data);
                        AIZ.plugins.notify('success', "{{ translate('Item has been added to wishlist') }}");
                    }
                    else{
                        AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
                    }
                });
            @elseif(Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the WishList.') }}");
            @else
                AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
            @endif
        }

        function showAddToCartModal(id){
            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }
            $('#addToCart-modal-body').html(null);
                $('#addToCart').modal();
            $('.c-preloader').show();
            $.post('{{ route('cart.showCartModal') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                $('.c-preloader').hide();
                $('#addToCart-modal-body').html(data);
                AIZ.plugins.slickCarousel();
                AIZ.plugins.zoom();
                AIZ.extra.plusMinus();
                getVariantPrice();
            });
        }

       

        function showReviewImageModal(imageUrl, imagesJson) {
            try {
                var images = JSON.parse(imagesJson);
                var currentIndex = images.indexOf(imageUrl);

                $('#modalReviewImage').attr('src', imageUrl);
                $('#reviewImageModal').modal('show');

                $('#prevImageBtn').off('click').on('click', function() {
                    currentIndex = (currentIndex - 1 + images.length) % images.length;
                    $('#modalReviewImage').attr('src', images[currentIndex]);
                });

                $('#nextImageBtn').off('click').on('click', function() {
                    currentIndex = (currentIndex + 1) % images.length;
                    $('#modalReviewImage').attr('src', images[currentIndex]);
                });
            } catch (error) {
                console.error("Error parsing JSON:", error);
            }
        }

        $('#option-choice-form input').on('change', function(){
            getVariantPrice();
        });

        function getVariantPrice(){
            if($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()){
                $.ajax({
                    type:"POST",
                    url: '{{ route('products.variant_price') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                        $('.product-gallery-thumb .carousel-box').each(function (i) {
                            if($(this).data('variation') && data.variation == $(this).data('variation')){
                                $('.product-gallery-thumb').slick('slickGoTo', i);
                            }
                        })

                        $('#option-choice-form #chosen_price_div').removeClass('d-none');
                        $('#option-choice-form #chosen_price_div #chosen_price').html(data.price);
                        $('#available-quantity').html(data.quantity);
                        $('.input-number').prop('max', data.max_limit);
                        if(parseInt(data.in_stock) == 0 && data.digital  == 0){
                           $('.buy-now').addClass('d-none');
                           $('.add-to-cart').addClass('d-none');
                           $('.out-of-stock').removeClass('d-none');
                        }
                        else{
                           $('.buy-now').removeClass('d-none');
                           $('.add-to-cart').removeClass('d-none');
                           $('.out-of-stock').addClass('d-none');
                        }

                        AIZ.extra.plusMinus();
                    }
                });
            }
        }

        function checkAddToCartValidity(){
            var names = {};
            $('#option-choice-form input:radio').each(function() { // find unique names
                names[$(this).attr('name')] = true;
            });
            var count = 0;
            $.each(names, function() { // then count them
                count++;
            });

            if($('#option-choice-form input:radio:checked').length == count){
                return true;
            }

            return false;
        }

        function addToCart(){
            @if (Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false;
            @endif

            if(checkAddToCartValidity()) {
                $('#addToCart').modal();
                $('.c-preloader').show();
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                       $('#addToCart-modal-body').html(null);
                       $('.c-preloader').hide();
                       $('#modal-size').removeClass('modal-lg');
                       $('#addToCart-modal-body').html(data.modal_view);
                       AIZ.extra.plusMinus();
                       AIZ.plugins.slickCarousel();
                       updateNavCart(data.nav_cart_view,data.cart_count);
                    }
                });

                if ("{{ get_setting('facebook_pixel') }}" == 1){
                    // Facebook Pixel AddToCart Event
                    fbq('track', 'AddToCart', {content_type: 'product'});
                    // Facebook Pixel AddToCart Event
                }
            }
            else{
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function buyNow(){
            @if (Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false;
            @endif

            if(checkAddToCartValidity()) {
                $('#addToCart-modal-body').html(null);
                $('#addToCart').modal();
                $('.c-preloader').show();
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                        if(data.status == 1){
                            $('#addToCart-modal-body').html(data.modal_view);
                            updateNavCart(data.nav_cart_view,data.cart_count);
                            window.location.replace("{{ route('cart') }}");
                        }
                        else{
                            $('#addToCart-modal-body').html(null);
                            $('.c-preloader').hide();
                            $('#modal-size').removeClass('modal-lg');
                            $('#addToCart-modal-body').html(data.modal_view);
                        }
                    }
               });
            }
            else{
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function bid_single_modal(bid_product_id, min_bid_amount){
            @if (Auth::check() && (isCustomer() || isSeller()))
                var min_bid_amount_text = "({{ translate('Min Bid Amount: ') }}"+min_bid_amount+")";
                $('#min_bid_amount').text(min_bid_amount_text);
                $('#bid_product_id').val(bid_product_id);
                $('#bid_amount').attr('min', min_bid_amount);
                $('#bid_for_product').modal('show');
            @elseif (Auth::check() && isAdmin())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function clickToSlide(btn,id){
            $('#'+id+' .aiz-carousel').find('.'+btn).trigger('click');
            $('#'+id+' .slide-arrow').removeClass('link-disable');
            var arrow = btn=='slick-prev' ? 'arrow-prev' : 'arrow-next';
            if ($('#'+id+' .aiz-carousel').find('.'+btn).hasClass('slick-disabled')) {
                $('#'+id).find('.'+arrow).addClass('link-disable');
            }
        }

        function goToView(params) {
            document.getElementById(params).scrollIntoView({behavior: "smooth", block: "center"});
        }

        function copyCouponCode(code){
            navigator.clipboard.writeText(code);
            AIZ.plugins.notify('success', "{{ translate('Coupon Code Copied') }}");
        }

        $(document).ready(function(){
            $('.cart-animate').animate({margin : 0}, "slow");

            $({deg: 0}).animate({deg: 360}, {
                duration: 2000,
                step: function(now) {
                    $('.cart-rotate').css({
                        transform: 'rotate(' + now + 'deg)'
                    });
                }
            });

            setTimeout(function(){
                $('.cart-ok').css({ fill: '#d43533' });
            }, 2000);

        });

        function nonLinkableNotificationRead(){
            $.get('{{ route('non-linkable-notification-read') }}',function(data){
                $('.unread-notification-count').html(data);
            });
        }
    </script>


    <script type="text/javascript">
        if ($('input[name=country_code]').length > 0){
            // Country Code
            var isPhoneShown = true,
                countryData = window.intlTelInputGlobals.getCountryData(),
                input = document.querySelector("#phone-code");

            for (var i = 0; i < countryData.length; i++) {
                var country = countryData[i];
                if (country.iso2 == 'bd') {
                    country.dialCode = '88';
                }
            }

            var iti = intlTelInput(input, {
                separateDialCode: true,
                utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
                onlyCountries: @php echo get_active_countries()->pluck('code') @endphp,
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                    if (selectedCountryData.iso2 == 'bd') {
                        return "01xxxxxxxxx";
                    }
                    return selectedCountryPlaceholder;
                }
            });

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

            input.addEventListener("countrychange", function(e) {
                // var currentMask = e.currentTarget.placeholder;
                var country = iti.getSelectedCountryData();
                $('input[name=country_code]').val(country.dialCode);

            });

            function toggleEmailPhone(el) {
                if (isPhoneShown) {
                    $('.phone-form-group').addClass('d-none');
                    $('.email-form-group').removeClass('d-none');
                    $('input[name=phone]').val(null);
                    isPhoneShown = false;
                    $(el).html('*{{ translate('Use Phone Number Instead') }}');
                } else {
                    $('.phone-form-group').removeClass('d-none');
                    $('.email-form-group').addClass('d-none');
                    $('input[name=email]').val(null);
                    isPhoneShown = true;
                    $(el).html('<i>*{{ translate('Use Email Instead') }}</i>');
                }
            }
        }
    </script>

    <script>
        var acc = document.getElementsByClassName("aiz-accordion-heading");
        var i;
        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }
    </script>

    <script>
        function showFloatingButtons() {
            document.querySelector('.floating-buttons-section').classList.toggle('show');;
        }
    </script>

    @if (env("DEMO_MODE") == "On")
        <script>
            var demoNav = document.querySelector('.aiz-demo-nav');
            var menuBtn = document.querySelector('.aiz-demo-nav-toggler');
            var lineOne = document.querySelector('.aiz-demo-nav-toggler .aiz-demo-nav-btn .line--1');
            var lineTwo = document.querySelector('.aiz-demo-nav-toggler .aiz-demo-nav-btn .line--2');
            var lineThree = document.querySelector('.aiz-demo-nav-toggler .aiz-demo-nav-btn .line--3');
            menuBtn.addEventListener('click', () => {
                toggleDemoNav();
            });

            function toggleDemoNav() {
                // demoNav.classList.toggle('show');
                demoNav.classList.toggle('shadow-none');
                lineOne.classList.toggle('line-cross');
                lineTwo.classList.toggle('line-fade-out');
                lineThree.classList.toggle('line-cross');
                if ($('.aiz-demo-nav-toggler').hasClass('show')) {
                    $('.aiz-demo-nav-toggler').removeClass('show');
                    demoHideOverlay();
                }else{
                    $('.aiz-demo-nav-toggler').addClass('show');
                    demoShowOverlay();
                }
            }

            $('.aiz-demos').click(function(e){
                if (!e.target.closest('.aiz-demos .aiz-demo-content')) {
                    toggleDemoNav();
                }
            });

            function demoShowOverlay(){
                $('.top-banner').removeClass('z-1035').addClass('z-1');
                $('.top-navbar').removeClass('z-1035').addClass('z-1');
                $('header').removeClass('z-1020').addClass('z-1');
                $('.aiz-demos').addClass('show');
            }

            function demoHideOverlay(cls=null){
                if($('.aiz-demos').hasClass('show')){
                    $('.aiz-demos').removeClass('show');
                    $('.top-banner').delay(800).removeClass('z-1').addClass('z-1035');
                    $('.top-navbar').delay(800).removeClass('z-1').addClass('z-1035');
                    $('header').delay(800).removeClass('z-1').addClass('z-1020');
                }
            }
        </script>
        
    @endif

    @if (get_setting('header_element') == 5)
        <script>
            // Language switcher
            function changeLanguage(code) {
                $.post('{{ route('language.change') }}', {
                    _token: '{{ csrf_token() }}',
                    locale: code
                }, function () {
                    location.reload();
                });
            }

            // Currency switcher
            function changeCurrency(code) {
                $.post('{{ route('currency.change') }}', {
                    _token: '{{ csrf_token() }}',
                    currency_code: code
                }, function () {
                    location.reload();
                });
            }
        </script>
    @endif

    <script>
function fixSlickVisibility() {
    $('.slick-slide').css('visibility', 'visible');
    $('.slick-track').css('opacity', '1');
}

// Call after fullscreen exit
$(window).on('resize', function() {
    setTimeout(function() {
        $('.product-gallery').slick('setPosition');
        $('.product-gallery-thumb').slick('setPosition');
        fixSlickVisibility();
    }, 300);
});
</script>

<script>
$(window).on('resize', function() {
    setTimeout(function() {
        $('.product-gallery').slick('setPosition');
        $('.product-gallery-thumb').slick('setPosition');
    }, 300); // delay gives time for fullscreen exit to finish
});
</script>

    @yield('script')

    @php
        echo get_setting('footer_script');
    @endphp

</body>
</html>
