@extends('frontend.layouts.app')



@section('content')
    <style>
        :root {
            --primary-color: {{ get_setting('base_color', '#d43533') }};
            --primary-rgb: {{ hex2rgba(get_setting('base_color', '#d43533'), 1) }};
        }
        
        /* Mobile Optimizations */
        @media (max-width: 767px) {
            /* Reduce section padding */
            .payment-section {
                padding: 8px !important;
            }
            
            /* Compact card headers */
            .card-header {
                padding: 12px 16px !important;
            }
            
            .card-header-icon {
                width: 36px !important;
                height: 36px !important;
                margin-right: 10px !important;
            }
            
            .card-header-icon i {
                font-size: 20px !important;
            }
            
            .card-header h3 {
                font-size: 15px !important;
            }
            
            .card-header p {
                font-size: 11px !important;
            }
            
            /* Compact card body */
            .card-body {
                padding: 12px 16px !important;
            }
            
            /* Compact form groups */
            .form-group {
                margin-bottom: 16px !important;
            }
            
            .form-group label {
                font-size: 13px !important;
                margin-bottom: 6px !important;
            }
            
            .input-group-text {
                padding: 8px 12px !important;
            }
            
            .input-group-text i {
                font-size: 14px !important;
            }
            
            .form-control, textarea {
                padding: 10px 12px !important;
                font-size: 14px !important;
            }
            
            /* Compact address cards */
            .address-option-card {
                padding: 12px !important;
            }
            
            .address-option-card .icon-wrapper {
                width: 40px !important;
                height: 40px !important;
            }
            
            .address-option-card .icon-wrapper i {
                font-size: 18px !important;
            }
            
            .address-option-card h5 {
                font-size: 13px !important;
            }
            
            .address-option-card p {
                font-size: 11px !important;
            }
            
            /* Compact delivery cards */
            .delivery-option-card {
                padding: 14px !important;
            }
            
            .delivery-option-card .zone-icon {
                width: 44px !important;
                height: 44px !important;
            }
            
            .delivery-option-card .zone-icon i {
                font-size: 20px !important;
            }
            
            .delivery-option-card h5 {
                font-size: 14px !important;
            }
            
            /* Compact payment cards */
            .payment-option-card {
                padding: 14px !important;
            }
            
            .payment-option-card .payment-icon {
                width: 44px !important;
                height: 44px !important;
            }
            
            .payment-option-card .payment-icon i {
                font-size: 22px !important;
            }
            
            .payment-option-card .payment-logo-wrapper {
                width: 40px !important;
                height: 40px !important;
            }
            
            .payment-option-card h5, .payment-option-card h6 {
                font-size: 14px !important;
            }
            
            .payment-option-card p {
                font-size: 11px !important;
            }
            
            /* Compact sub-zone cards */
            .sub-zone-card > div {
                padding: 14px 5px 14px 18px !important;
            }
            
            .sub-zone-card .sub-zone-icon {
                width: 44px !important;
                height: 44px !important;
            }
            
            .sub-zone-card .sub-zone-icon i {
                font-size: 20px !important;
            }
            
            /* Sticky action buttons on mobile */
            .mobile-sticky-actions {
                position: fixed !important;
                bottom: 60px !important; /* Position above mobile nav (aiz-mobile-bottom-nav is ~60px) */
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                background: #ffffff !important;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15) !important;
                z-index: 999 !important; /* Lower than mobile nav to not cover it */
                padding: 12px !important;
                border-top: 1px solid #e0e0e0 !important;
            }
            
            .mobile-sticky-actions .btn {
                min-height: 44px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            
            /* Add bottom padding to prevent content from being hidden behind sticky buttons and mobile nav */
            .payment-section {
                padding-bottom: 150px !important; /* Button height + mobile nav height + spacing */
            }
            
            /* Ensure form doesn't overlap sticky buttons and mobile nav */
            #checkout-form {
                padding-bottom: 140px !important; /* Button height + mobile nav height + spacing */
            }
            
            /* Hide cart summary on mobile initially or make it collapsible */
            .cart-summary-mobile {
                margin-bottom: 16px;
            }
            
            /* Compact terms card */
            .terms-card {
                padding: 12px !important;
            }
            
            .terms-card label {
                font-size: 12px !important;
            }
            
            /* Compact buttons */
            .btn {
                padding: 10px 16px !important;
                font-size: 13px !important;
            }
            
            .btn i {
                font-size: 14px !important;
            }
        }
        
        /* Hide by default - show only on mobile */
        .mobile-sticky-actions {
            display: none !important;
        }
        
        /* Show only on mobile screens (max-width: 767px) */
        @media (max-width: 767px) {
            .mobile-sticky-actions {
                display: block !important;
                visibility: visible !important;
            }
        }
        
        /* Ensure hidden on desktop (min-width: 768px) */
        @media (min-width: 768px) {
            .mobile-sticky-actions {
                display: none !important;
                visibility: hidden !important;
            }
        }
        
        /* Form Field Focus States */
        #checkout-form .form-control:focus,
        #checkout-form textarea:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15) !important;
            outline: none;
        }
        
        #checkout-form .input-group:focus-within {
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15) !important;
            border-radius: 8px;
        }
        
        #checkout-form .input-group:focus-within .input-group-text {
            border-color: #667eea !important;
        }
        
        #checkout-form .input-group:focus-within .form-control {
            border-color: #667eea !important;
        }
        
        /* Elegant Card Hover Effects */
        .address-option-card:hover,
        .delivery-option-card:hover,
        .payment-option-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
        }
        
        /* Selected State for Delivery Options */
        .delivery-option-card.selected {
            border-color: {{ get_setting('base_color', '#d43533') }} !important;
            background: #fafafa !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important;
        }
        
        .delivery-option-card.selected .radio-indicator {
            border-color: {{ get_setting('base_color', '#d43533') }} !important;
        }
        
        .delivery-option-card.selected .radio-dot {
            background: {{ get_setting('base_color', '#d43533') }} !important;
        }
        
        /* Selected State for Payment Options */
        .payment-option-card.selected {
            border-color: {{ get_setting('base_color', '#d43533') }} !important;
            background: #fafafa !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important;
        }
        
        .payment-option-card.selected .radio-indicator {
            border-color: {{ get_setting('base_color', '#d43533') }} !important;
        }
        
        .payment-option-card.selected .radio-dot {
            background: {{ get_setting('base_color', '#d43533') }} !important;
        }
        
        /* Smooth Transitions */
        .form-control, textarea, .input-group-text,
        .delivery-option-card, .payment-option-card, .address-option-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Return to Shop Button */
        .btn-return-shop {
            background-color: #dc3545 !important;
            color: #ffffff !important;
            border: none !important;
            transition: all 0.3s ease !important;
        }
        
        .btn-return-shop:hover {
            background-color: {{ get_setting('base_color', '#d43533') }} !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* Elegant Scrollbar for Areas List */
        .areas-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .areas-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .areas-list::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .areas-list::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Sub Zone Card Elegant Design */
        .sub-zone-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        
        .sub-zone-card:hover:not(.selected) {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%) !important;
            transform: translateY(-2px) !important;
        }
        
        /* Area Tag Hover Effect */
        .area-tag {
            transition: all 0.2s ease;
        }
        
        .area-tag:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Upcoming/Disabled Payment Card */
        .payment-option-card.upcoming {
            position: relative;
            opacity: 0.65;
            cursor: not-allowed !important;
            pointer-events: none;
        }
        
        .payment-option-card.upcoming::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, rgba(248, 249, 250, 0.8) 100%);
            border-radius: 16px;
            z-index: 1;
            pointer-events: none;
        }
        
        .payment-option-card.upcoming > * {
            position: relative;
            z-index: 0;
        }
        
        .payment-option-card.upcoming .upcoming-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 2;
            background: linear-gradient(135deg, {{ get_setting('base_color', '#d43533') }} 0%, {{ get_setting('base_hov_color', '#9d1b1a') }} 100%);
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.85;
                transform: scale(1.02);
            }
        }
        
        .payment-option-card.upcoming img {
            filter: grayscale(100%);
            opacity: 0.6;
        }
    </style>

    <!-- Payment Info -->
    <section class="mb-4 payment-section" style="font-family: 'Poppins', sans-serif;">
        <div class="container-fluid text-left py-2 py-md-3 px-2 px-md-3" style="max-width: 1600px; margin: 0 auto;">
            <form action="{{ route('payment.checkout') }}" class="form-default" role="form" method="POST" id="checkout-form">
                @csrf
                <input type="hidden" name="owner_id" value="{{ $carts[0]['owner_id'] }}">
                <div class="row">
                    <!-- Left Column: Customer Info, Delivery & Payment -->
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <!-- Customer Information Section -->
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
                            <!-- Customer Information Header -->
                            <div class="card-header border-0" style="background: {{ get_setting('base_color', '#d43533') }}; padding: 20px 24px;">
                                <div class="d-flex align-items-center">
                                    <div class="card-header-icon" style="background: rgba(255, 255, 255, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px; backdrop-filter: blur(10px);">
                                        <i class="las la-user-circle" style="font-size: 26px; color: #ffffff;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="mb-0 text-white" style="font-size: 18px; font-weight: 700;">
                                            {{ translate('Customer Information') }}
                                        </h3>
                                        <p class="mb-0 mt-1 text-white d-none d-sm-block" style="font-size: 13px; opacity: 0.9;">
                                            {{ translate('Enter your delivery details') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">

                            @if(Auth::check() && $defaultAddress)
                            <!-- Address Selection Cards -->
                            <div class="row mb-3 mb-md-4">
                                <div class="col-md-6 mb-2 mb-md-3 mb-md-0">
                                    <div class="address-option-card" data-option="default" style="border: 2px solid #e0e0e0; border-radius: 12px; padding: 18px; cursor: pointer; transition: all 0.3s ease; background: #ffffff; position: relative; height: 100%;">
                                        <input type="radio" name="address_option" id="useDefaultAddress" value="default" checked style="position: absolute; opacity: 0;">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 mr-3 icon-wrapper" style="width: 48px; height: 48px; background: {{ get_setting('base_color', '#d43533') }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">
                                                <i class="las la-home" style="font-size: 22px; color: #ffffff;"></i>
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <h5 class="mb-1 fw-700 fs-14">{{ translate('Use Default Address') }}</h5>
                                                <p class="mb-0 text-muted fs-12 d-none d-sm-block">{{ translate('Use your saved address') }}</p>
                                            </div>
                                            <div class="flex-shrink-0 ml-2">
                                                <div class="radio-indicator" style="width: 20px; height: 20px; border: 2px solid {{ get_setting('base_color', '#d43533') }}; border-radius: 50%; position: relative;">
                                                    <div class="radio-dot" style="width: 12px; height: 12px; background: {{ get_setting('base_color', '#d43533') }}; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="address-option-card" data-option="new" style="border: 2px solid #e0e0e0; border-radius: 12px; padding: 18px; cursor: pointer; transition: all 0.3s ease; background: #ffffff; position: relative; height: 100%;">
                                        <input type="radio" name="address_option" id="newAddress" value="new" style="position: absolute; opacity: 0;">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 mr-3 icon-wrapper" style="width: 48px; height: 48px; background: {{ get_setting('base_color', '#d43533') }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);">
                                                <i class="las la-map-marker-alt" style="font-size: 22px; color: #ffffff;"></i>
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <h5 class="mb-1 fw-700 fs-14">{{ translate('New Address') }}</h5>
                                                <p class="mb-0 text-muted fs-12 d-none d-sm-block">{{ translate('Enter a new address') }}</p>
                                            </div>
                                            <div class="flex-shrink-0 ml-2">
                                                <div class="radio-indicator" style="width: 20px; height: 20px; border: 2px solid #e0e0e0; border-radius: 50%; position: relative;">
                                                    <div class="radio-dot" style="width: 12px; height: 12px; background: transparent; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div id="addressFields">
                                <!-- Full Name -->
                                <div class="form-group mb-4">
                                    <label for="name" class="fw-600 mb-2 fs-14 d-block">
                                        {{ translate('Full Name') }}<span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="input-group" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0" style="padding: 12px 15px; border-color: #dee2e6;"><i class="las la-user text-primary fs-16"></i></span>
                                        </div>
                                        <input type="text" class="form-control border-left-0" name="name" id="name" placeholder="{{ translate('Enter Your Name') }}" value="{{ $defaultAddress->name ?? Auth::user()->name ?? '' }}" required style="padding: 12px 15px; border-color: #dee2e6; transition: all 0.3s;">
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="form-group mb-4">
                                    <label for="address" class="fw-600 mb-2 fs-14 d-block">
                                        {{ translate('Address') }}<span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="input-group" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <div class="input-group-prepend align-items-start" style="height: auto;">
                                            <span class="input-group-text bg-white border-right-0" style="padding: 12px 15px; border-color: #dee2e6; border-top-left-radius: 8px;"><i class="las la-map-marker-alt text-primary fs-16"></i></span>
                                        </div>
                                        <textarea class="form-control border-left-0" name="address" id="address" rows="3" placeholder="{{ translate('Enter your address') }}" required style="padding: 12px 15px; resize: vertical; border-color: #dee2e6; transition: all 0.3s;">{{ $defaultAddress->address ?? '' }}</textarea>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="form-group mb-4">
                                    <label for="phone" class="fw-600 mb-2 fs-14 d-block">
                                        {{ translate('Phone') }}<span class="text-danger ml-1">*</span>
                                    </label>
                                    <div class="input-group" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0" style="padding: 12px 15px; border-color: #dee2e6;"><i class="las la-phone text-primary fs-16"></i></span>
                                        </div>
                                        <input type="text" class="form-control border-left-0" placeholder="{{ translate('+880') }}" name="phone" id="phone" value="{{ $defaultAddress->phone ?? '' }}" required style="padding: 12px 15px; border-color: #dee2e6; transition: all 0.3s;">
                                    </div>
                                </div>

                                <!-- Order Note -->
                                <div class="form-group mb-0">
                                    <label class="fw-600 mb-2 fs-14 d-block">
                                        {{ translate('Order Note') }} 
                                        <span class="text-muted fs-12 font-weight-normal">({{ translate('Optional') }})</span>
                                    </label>
                                    <textarea name="additional_info" rows="4" class="form-control" placeholder="{{ translate('Enter Order Note (Optional)') }}" style="padding: 12px 15px; resize: vertical; border-radius: 8px; border-color: #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: all 0.3s;">{{ $defaultAddress->additional_info ?? '' }}</textarea>
                                </div>
                            </div>
                            </div> <!-- End card-body -->
                        </div> <!-- End Customer Information Card -->

                        <!-- Delivery Options and Payment Method Side by Side -->
                        <div class="row">
                            <!-- Delivery Options Section -->
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">                        
                            <!-- Delivery Options Header -->
                            <div class="card-header border-0" style="background: {{ get_setting('base_color', '#d43533') }}; padding: 12px 16px; padding-md: 20px 24px;">
                                <div class="d-flex align-items-center">
                                    <div class="card-header-icon" style="background: rgba(255, 255, 255, 0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px; margin-right-md: 15px; backdrop-filter: blur(10px);">
                                        <i class="las la-truck" style="font-size: 20px; font-size-md: 26px; color: #ffffff;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="mb-0 text-white" style="font-size: 15px; font-size-md: 18px; font-weight: 700;">
                                            {{ translate('Delivery Options') }}
                                        </h3>
                                        <p class="mb-0 mt-1 text-white d-none d-sm-block" style="font-size: 11px; font-size-md: 13px; opacity: 0.9;">
                                            {{ translate('Choose your delivery location') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3 p-md-4">
                                <div class="row">
                                    @php
                                        $regularZones = $zones->whereNull('parent_zone_id');
                                        $subZones = $zones->whereNotNull('parent_zone_id');
                                    @endphp
                                    
                                    {{-- Display all regular zones first --}}
                                    @foreach($regularZones as $index => $zone)
                                        <div class="col-12 mb-2 mb-md-3">
                                            <div class="delivery-option-card regular-zone-card {{ $index === 0 ? 'selected' : '' }}" data-delivery="zone_{{ $zone->id }}" data-zone-type="regular" style="border: 2px solid {{ $index === 0 ? get_setting('base_color', '#d43533') : '#e8e8e8' }}; border-radius: 12px; padding: 14px; padding-md: 20px; cursor: pointer; background: {{ $index === 0 ? '#fafafa' : '#ffffff' }}; position: relative; box-shadow: {{ $index === 0 ? '0 4px 16px rgba(0, 0, 0, 0.08)' : '0 2px 8px rgba(0, 0, 0, 0.04)' }};">
                                                <input type="radio" name="deliveryOption" id="zone_{{ $zone->id }}" value="zone_{{ $zone->id }}" {{ $index === 0 ? 'checked required' : '' }} style="position: absolute; opacity: 0;">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 mr-2 mr-md-3 zone-icon" style="width: 44px; width-md: 56px; height: 44px; height-md: 56px; background: {{ get_setting('base_color', '#d43533') }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
                                                        <i class="las la-city" style="font-size: 20px; font-size-md: 24px; color: #ffffff;"></i>
                                                    </div>
                                                    <div class="flex-grow-1 min-w-0">
                                                        <h5 class="mb-0 fw-700" style="color: #1a1a1a; font-size: 14px; font-size-md: 16px; letter-spacing: -0.2px;">{{ $zone->name }}</h5>
                                                    </div>
                                                    <div class="flex-shrink-0 ml-2 ml-md-3">
                                                        <div class="radio-indicator" style="width: 20px; width-md: 24px; height: 20px; height-md: 24px; border: 2px solid {{ $index === 0 ? get_setting('base_color', '#d43533') : '#d0d0d0' }}; border-radius: 50%; position: relative; transition: all 0.3s ease;">
                                                            <div class="radio-dot" style="width: 12px; width-md: 14px; height: 12px; height-md: 14px; background: {{ $index === 0 ? get_setting('base_color', '#d43533') : 'transparent' }}; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: all 0.3s ease;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    {{-- Display all sub-zones at the end --}}
                                    @if($subZones->count() > 0)
                                        <div class="col-12 mt-3 mt-md-4 mb-2 mb-md-3">
                                            <div style="border-top: 2px dashed #e0e0e0; margin: 16px 0 12px 0; margin-md: 24px 0 20px 0; position: relative;">
                                                <span style="background: #ffffff; padding: 0 12px; padding-md: 0 16px; position: absolute; top: -10px; top-md: -11px; left: 50%; transform: translateX(-50%); color: #666; font-size: 10px; font-size-md: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; letter-spacing-md: 1px;">{{ translate('Sub Areas') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @foreach($subZones as $subZone)
                                        <div class="col-12 mb-2 mb-md-3">
                                            <div class="delivery-option-card sub-zone-card" data-delivery="zone_{{ $subZone->id }}" data-zone-type="sub" style="border: 2px solid #e8e8e8; border-radius: 12px; padding: 0; cursor: pointer; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); position: relative; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
                                                <input type="radio" name="deliveryOption" id="zone_{{ $subZone->id }}" value="zone_{{ $subZone->id }}" style="position: absolute; opacity: 0;">
                                                
                                                {{-- Elegant left border accent --}}
                                                <div style="position: absolute; top: 0; left: 0; width: 3px; width-md: 4px; height: 100%; background: linear-gradient(180deg, {{ get_setting('base_color', '#d43533') }} 0%, {{ get_setting('base_hov_color', '#9d1b1a') }} 100%);"></div>
                                                
                                                <div style="padding: 14px 14px 14px 18px; padding-md: 20px 20px 20px 24px;">
                                                    {{-- Header Section --}}
                                                    <div class="d-flex align-items-start mb-2 mb-md-3">
                                                        <div class="flex-shrink-0 mr-2 mr-md-3 sub-zone-icon" style="width: 44px; width-md: 56px; height: 44px; height-md: 56px; background: {{ get_setting('base_color', '#d43533') }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
                                                            <i class="las la-map-marked-alt" style="font-size: 20px; font-size-md: 24px; color: #ffffff;"></i>
                                                        </div>
                                                        <div class="flex-grow-1" style="min-width: 0;">
                                                            <div class="d-flex align-items-center justify-content-between mb-1 mb-md-2">
                                                                <h5 class="mb-0 fw-700" style="color: #1a1a1a; font-size: 14px; font-size-md: 16px; line-height: 1.3; letter-spacing: -0.2px;">
                                                                    {{ $subZone->name }}
                                                                </h5>
                                                                <div class="flex-shrink-0 ml-2 ml-md-3">
                                                                    <div class="radio-indicator" style="width: 20px; width-md: 24px; height: 20px; height-md: 24px; border: 2px solid #d0d0d0; border-radius: 50%; position: relative; flex-shrink: 0; transition: all 0.3s ease;">
                                                                        <div class="radio-dot" style="width: 12px; width-md: 14px; height: 12px; height-md: 14px; background: transparent; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: all 0.3s ease;"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span style="display: inline-block; background: #f0f0f0; color: #666; font-size: 9px; font-size-md: 10px; font-weight: 700; padding: 3px 8px; padding-md: 4px 10px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                                {{ translate('Sub Zone') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Areas List Section --}}
                                                    @if($subZone->areas->count() > 0)
                                                        <div style="background: #ffffff; border-radius: 10px; padding: 10px; padding-md: 14px; border: 1px solid #e8e8e8; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);">
                                                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; margin-bottom-md: 12px;">
                                                                <span style="color: #666; font-size: 10px; font-size-md: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; letter-spacing-md: 0.8px;">
                                                                    <i class="las la-list-ul" style="font-size: 10px; font-size-md: 12px; margin-right: 4px;"></i>{{ translate('Covered Areas') }}
                                                                </span>
                                                                <span style="background: #f5f5f5; color: #333; font-size: 9px; font-size-md: 10px; font-weight: 700; padding: 2px 8px; padding-md: 3px 10px; border-radius: 10px;">
                                                                    {{ $subZone->areas->count() }} {{ translate('areas') }}
                                                                </span>
                                                            </div>
                                                            <div class="areas-list" style="max-height: 100px; max-height-md: 140px; overflow-y: auto; overflow-x: hidden; padding-right: 6px; padding-right-md: 8px;">
                                                                <div style="display: flex; flex-wrap: wrap; gap: 6px; gap-md: 8px;">
                                                                    @foreach($subZone->areas as $area)
                                                                        <div class="area-tag" style="background: #f8f9fa; border: 1px solid #e8e8e8; border-radius: 8px; padding: 6px 10px; padding-md: 8px 12px; display: inline-flex; align-items: center;">
                                                                            <i class="las la-map-marker" style="font-size: 10px; font-size-md: 11px; color: #666; margin-right: 4px; margin-right-md: 6px;"></i>
                                                                            <span style="color: #333; font-size: 11px; font-size-md: 12px; font-weight: 500; white-space: nowrap;">{{ $area->name }}</span>
                                                                            @if($area->city)
                                                                                <span class="d-none d-sm-inline" style="color: #999; font-size: 10px; font-size-md: 11px; margin-left: 4px; margin-left-md: 6px; font-weight: 400;">({{ $area->city->name }})</span>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div style="background: #f8f9fa; border-radius: 10px; padding: 12px; padding-md: 16px; text-align: center; border: 1px dashed #d0d0d0;">
                                                            <p class="mb-0" style="color: #999; font-size: 12px; font-size-md: 13px;">
                                                                <i class="las la-info-circle" style="font-size: 12px; font-size-md: 14px; margin-right: 6px;"></i>{{ translate('No areas assigned') }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($regularZones->isEmpty() && $subZones->isEmpty())
                                        <div class="col-12">
                                            <div class="alert alert-info">{{ translate('No delivery zones available') }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div> <!-- End card-body -->
                                </div> <!-- End card -->
                            </div> <!-- End Delivery Options Column -->

                            <!-- Payment Method Section -->
                            <div class="col-lg-6 mb-4">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                            <!-- Payment Method Header -->
                            <div class="card-header border-0" style="background: {{ get_setting('base_color', '#d43533') }}; padding: 12px 16px; padding-md: 20px 24px;">
                                <div class="d-flex align-items-center">
                                    <div class="card-header-icon" style="background: rgba(255, 255, 255, 0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 10px; margin-right-md: 15px; backdrop-filter: blur(10px);">
                                        <i class="las la-credit-card" style="font-size: 20px; font-size-md: 26px; color: #ffffff;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="mb-0 text-white" style="font-size: 15px; font-size-md: 18px; font-weight: 700;">
                                            {{ translate('Select a Payment Method') }}
                                        </h3>
                                        <p class="mb-0 mt-1 text-white d-none d-sm-block" style="font-size: 11px; font-size-md: 13px; opacity: 0.9;">
                                            {{ translate('Choose your preferred payment method') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Payment Options -->
                            <div class="card-body p-3 p-md-4">
                                    @php
                                        $payment_checked = false; // Track if we've set a checked option
                                        $digital = 0;
                                        $cod_on = 1;
                                        if (get_setting('cash_payment') == 1) {
                                            foreach ($carts as $cartItem) {
                                                $product = \App\Models\Product::find($cartItem['product_id']);
                                                if ($product && $product['digital'] == 1) {
                                                    $digital = 1;
                                                }
                                                if ($product && $product['cash_on_delivery'] == 0) {
                                                    $cod_on = 0;
                                                }
                                            }
                                        }
                                    @endphp

                                <!-- Cash on Delivery -->
                                @if (get_setting('cash_payment') == 1 && $digital != 1 && $cod_on == 1)
                                    <div class="payment-option-card cod-card mb-2 mb-md-3 {{ !$payment_checked ? 'selected' : '' }}" data-payment="cash_on_delivery" style="border: 2px solid {{ !$payment_checked ? get_setting('base_color', '#d43533') : '#e8e8e8' }}; border-radius: 12px; padding: 14px; padding-md: 20px; cursor: pointer; background: {{ !$payment_checked ? '#fafafa' : 'linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%)' }}; position: relative; box-shadow: {{ !$payment_checked ? '0 4px 16px rgba(0, 0, 0, 0.08)' : '0 2px 8px rgba(0, 0, 0, 0.04)' }};">
                                        <input type="radio" name="payment_option" id="cash_on_delivery" value="cash_on_delivery" {{ !$payment_checked ? 'checked' : '' }} style="position: absolute; opacity: 0;">
                                        @php $payment_checked = true; @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 mr-2 mr-md-3 payment-icon" style="width: 44px; width-md: 60px; height: 44px; height-md: 60px; background: {{ get_setting('base_color', '#d43533') }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);">
                                                <i class="las la-money-bill-wave" style="font-size: 22px; font-size-md: 28px; color: #ffffff;"></i>
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <h5 class="mb-1 fw-700" style="font-size: 14px; font-size-md: 17px; color: #1a1a1a; letter-spacing: -0.2px;">{{ translate('Cash on Delivery') }}</h5>
                                                <p class="mb-0 text-muted d-none d-sm-block" style="font-size: 11px; font-size-md: 13px; color: #666;">{{ translate('Pay when you receive your order') }}</p>
                                            </div>
                                            <div class="flex-shrink-0 ml-2 ml-md-3">
                                                <div class="radio-indicator" style="width: 20px; width-md: 24px; height: 20px; height-md: 24px; border: 2px solid {{ !$payment_checked ? get_setting('base_color', '#d43533') : '#d0d0d0' }}; border-radius: 50%; position: relative; transition: all 0.3s ease;">
                                                    <div class="radio-dot" style="width: 12px; width-md: 14px; height: 12px; height-md: 14px; background: {{ !$payment_checked ? get_setting('base_color', '#d43533') : 'transparent' }}; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: all 0.3s ease;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Online Payment Methods -->
                                @if((get_setting('cash_payment') == 1 && $digital != 1 && $cod_on == 1) || (get_setting('cash_payment') != 1 || $digital == 1 || $cod_on != 1))
                                    <div class="online-payment-section">
                                        @if(get_setting('cash_payment') == 1 && $digital != 1 && $cod_on == 1)
                                            <h6 class="mb-2 mb-md-3 fw-600 text-muted" style="font-size: 11px; font-size-md: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                <i class="las la-credit-card mr-1 mr-md-2"></i>{{ translate('Online Payment Methods') }}
                                            </h6>
                                        @endif
                                        <div class="row">
                                            <!-- Bkash - Upcoming Feature -->
                                            <div class="col-12 mb-2 mb-md-3">
                                                <div class="payment-option-card online-card upcoming" data-payment="bkash" style="border: 2px solid #e8e8e8; border-radius: 12px; padding: 14px; padding-md: 18px; background: #ffffff; position: relative; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
                                                    <span class="upcoming-badge" style="top: 8px; top-md: 12px; right: 8px; right-md: 12px; font-size: 9px; font-size-md: 10px; padding: 3px 8px; padding-md: 4px 10px;">{{ translate('Coming Soon') }}</span>
                                                    <input type="radio" name="payment_option" id="bkash" value="bkash" disabled style="position: absolute; opacity: 0;">
                                                    @php $payment_checked = true; @endphp
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 mr-2 mr-md-3 payment-logo-wrapper" style="width: 40px; width-md: 50px; height: 40px; height-md: 50px; background: #ffffff; border: 1px solid #e8e8e8; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06); padding: 4px; padding-md: 6px;">
                                                            <img src="{{ static_asset('assets/img/cards/bkash.png') }}" class="img-fit" style="height: 100%; width: auto; border-radius: 6px;">
                                                        </div>
                                                        <div class="flex-grow-1 min-w-0">
                                                            <h6 class="mb-0 fw-700" style="font-size: 14px; font-size-md: 15px; color: #1a1a1a; letter-spacing: -0.2px;">{{ translate('Bkash') }}</h6>
                                                            <p class="mb-0 text-muted d-none d-sm-block" style="font-size: 11px; font-size-md: 12px; color: #666; margin-top: 2px;">{{ translate('Mobile banking') }}</p>
                                                        </div>
                                                        <div class="flex-shrink-0 ml-2 ml-md-3">
                                                            <div class="radio-indicator" style="width: 20px; width-md: 24px; height: 20px; height-md: 24px; border: 2px solid #d0d0d0; border-radius: 50%; position: relative; transition: all 0.3s ease; opacity: 0.5;">
                                                                <div class="radio-dot" style="width: 12px; width-md: 14px; height: 12px; height-md: 14px; background: transparent; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: all 0.3s ease;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Aamarpay - Upcoming Feature -->
                                            <div class="col-12 mb-2 mb-md-3">
                                                <div class="payment-option-card online-card upcoming" data-payment="aamarpay" style="border: 2px solid #e8e8e8; border-radius: 12px; padding: 14px; padding-md: 18px; background: #ffffff; position: relative; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
                                                    <span class="upcoming-badge" style="top: 8px; top-md: 12px; right: 8px; right-md: 12px; font-size: 9px; font-size-md: 10px; padding: 3px 8px; padding-md: 4px 10px;">{{ translate('Coming Soon') }}</span>
                                                    <input type="radio" name="payment_option" id="aamarpay" value="aamarpay" disabled style="position: absolute; opacity: 0;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 mr-2 mr-md-3 payment-logo-wrapper" style="width: 40px; width-md: 50px; height: 40px; height-md: 50px; background: #ffffff; border: 1px solid #e8e8e8; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06); padding: 4px; padding-md: 6px;">
                                                            <img src="{{ static_asset('assets/img/cards/aamarpay.png') }}" class="img-fit" style="height: 100%; width: auto; border-radius: 6px;">
                                                        </div>
                                                        <div class="flex-grow-1 min-w-0">
                                                            <h6 class="mb-0 fw-700" style="font-size: 14px; font-size-md: 15px; color: #1a1a1a; letter-spacing: -0.2px;">{{ translate('Aamarpay') }}</h6>
                                                            <p class="mb-0 text-muted d-none d-sm-block" style="font-size: 11px; font-size-md: 12px; color: #666; margin-top: 2px;">{{ translate('Online payment gateway') }}</p>
                                                        </div>
                                                        <div class="flex-shrink-0 ml-2 ml-md-3">
                                                            <div class="radio-indicator" style="width: 20px; width-md: 24px; height: 20px; height-md: 24px; border: 2px solid #d0d0d0; border-radius: 50%; position: relative; transition: all 0.3s ease; opacity: 0.5;">
                                                                <div class="radio-dot" style="width: 12px; width-md: 14px; height: 12px; height-md: 14px; background: transparent; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: all 0.3s ease;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Nagad - Upcoming Feature -->
                                            <div class="col-12 mb-2 mb-md-3">
                                                <div class="payment-option-card online-card upcoming" data-payment="nagad" style="border: 2px solid #e8e8e8; border-radius: 12px; padding: 14px; padding-md: 18px; background: #ffffff; position: relative; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);">
                                                    <span class="upcoming-badge" style="top: 8px; top-md: 12px; right: 8px; right-md: 12px; font-size: 9px; font-size-md: 10px; padding: 3px 8px; padding-md: 4px 10px;">{{ translate('Coming Soon') }}</span>
                                                    <input type="radio" name="payment_option" id="nagad" value="nagad" disabled style="position: absolute; opacity: 0;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 mr-2 mr-md-3 payment-logo-wrapper" style="width: 40px; width-md: 50px; height: 40px; height-md: 50px; background: #ffffff; border: 1px solid #e8e8e8; border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06); padding: 4px; padding-md: 6px;">
                                                            <img src="{{ static_asset('assets/img/cards/nagad.png') }}" class="img-fit" style="height: 100%; width: auto; border-radius: 6px;">
                                                        </div>
                                                        <div class="flex-grow-1 min-w-0">
                                                            <h6 class="mb-0 fw-700" style="font-size: 14px; font-size-md: 15px; color: #1a1a1a; letter-spacing: -0.2px;">{{ translate('Nagad') }}</h6>
                                                            <p class="mb-0 text-muted d-none d-sm-block" style="font-size: 11px; font-size-md: 12px; color: #666; margin-top: 2px;">{{ translate('Mobile banking') }}</p>
                                                        </div>
                                                        <div class="flex-shrink-0 ml-2 ml-md-3">
                                                            <div class="radio-indicator" style="width: 20px; width-md: 24px; height: 20px; height-md: 24px; border: 2px solid #d0d0d0; border-radius: 50%; position: relative; transition: all 0.3s ease; opacity: 0.5;">
                                                                <div class="radio-dot" style="width: 12px; width-md: 14px; height: 12px; height-md: 14px; background: transparent; border-radius: 50%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: all 0.3s ease;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                            </div> <!-- End card-body -->
                                </div> <!-- End card -->
                            </div> <!-- End Payment Method Column -->
                        </div> <!-- End Delivery and Payment Row -->
                    </div> <!-- End Left Column -->

                    <!-- Right Column: Cart Summary -->
                    <div class="col-lg-4">
                        <div class="sticky-top-lg" style="top: 20px;">
                            <div id="cart_summary" class="cart-summary-mobile">@include('frontend.partials.cart_summary')</div>
                            
                            <!-- Terms & Conditions -->
                            <div class="card border-0 shadow-sm mt-2 mt-md-3 terms-card" style="border-radius: 10px; border-radius-md: 12px;">
                                <div class="card-body p-3 p-md-4">
                                    <label class="aiz-checkbox d-flex align-items-start mb-0">
                                        <input type="checkbox" required id="agree_checkbox" checked style="margin-top: 3px;">
                                        <span class="aiz-square-check"></span>
                                        <span class="ml-2" style="font-size: 12px; font-size-md: 14px;">
                                            {{ translate('I agree to the') }}
                                            <a href="{{ route('terms') }}" class="fw-700 text-primary" target="_blank">{{ translate('terms and conditions') }}</a>,
                                            <a href="{{ route('returnpolicy') }}" class="fw-700 text-primary" target="_blank">{{ translate('return policy') }}</a> &
                                            <a href="{{ route('privacypolicy') }}" class="fw-700 text-primary" target="_blank">{{ translate('privacy policy') }}</a>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- Action Buttons - Desktop -->
                            <div class="card border-0 shadow-sm mt-2 mt-md-3 d-none d-md-block" style="border-radius: 10px; border-radius-md: 12px;">
                                <div class="card-body p-3 p-md-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <!-- Return to shop -->
                                        <a href="{{ route('home') }}" class="btn btn-return-shop fw-600 px-3" style="font-size: 13px; font-size-md: 14px; border-radius: 8px; padding: 10px 16px; padding-md: 12px 20px;">
                                            <i class="las la-arrow-left mr-1"></i>
                                            {{ translate('Return to shop') }}
                                        </a>
                                        <!-- Complete Order -->
                                        <button type="button" onclick="submitOrder(this)"
                                            class="btn btn-primary fw-700 px-4 py-2" style="font-size: 14px; font-size-md: 15px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,123,255,0.3);">
                                            <i class="las la-check-circle mr-2"></i>{{ translate('Order Now') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End Right Column -->
                    
                    <!-- Mobile Sticky Action Buttons -->
                    <div class="mobile-sticky-actions d-block d-md-none" style="position: fixed; bottom: 60px; left: 0; right: 0; width: 100%; background: #ffffff; box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15); z-index: 999; padding: 12px; border-top: 2px solid #e0e0e0;">
                        <div class="container-fluid px-3">
                            <div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
                                <!-- Return to shop -->
                                <a href="{{ route('home') }}" class="btn btn-return-shop fw-600" style="font-size: 13px !important; border-radius: 8px !important; padding: 12px 16px !important; min-width: auto !important; white-space: nowrap !important; flex: 0 0 auto !important; background-color: #dc3545 !important; color: #ffffff !important; border: none !important;">
                                    <i class="las la-arrow-left mr-1"></i>
                                    <span>{{ translate('Return') }}</span>
                                </a>
                                <!-- Complete Order -->
                                <button type="button" onclick="submitOrder(this)"
                                    class="btn btn-primary fw-700 flex-grow-1" style="font-size: 14px !important; border-radius: 8px !important; box-shadow: 0 4px 12px rgba(0,123,255,0.3) !important; padding: 12px 16px !important; min-height: 44px !important; white-space: nowrap !important; background-color: {{ get_setting('base_color', '#d43533') }} !important; color: #ffffff !important; border: none !important;">
                                    <i class="las la-check-circle mr-1"></i>{{ translate('Order Now') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div> <!-- End Row -->
            </form>
        </div>
    </section>
@endsection


@section('script')
<script type="text/javascript">

        
        function add_new_address(){
            $('#new-address-modal').modal('show');
        }

        function edit_address(address) {
            var url = '{{ route("addresses.edit", ":id") }}';
            url = url.replace(':id', address);
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#edit_modal_body').html(response.html);
                    $('#edit-address-modal').modal('show');
                    AIZ.plugins.bootstrapSelect('refresh');

                    @if (get_setting('google_map') == 1)
                        var lat     = -33.8688;
                        var long    = 151.2195;

                        if(response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat     = parseFloat(response.data.address_data.latitude);
                            long    = parseFloat(response.data.address_data.longitude);
                        }

                        initialize(lat, long, 'edit_');
                    @endif
                }
            });
        }
        
        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            // get_city(state_id);
        });
        
        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        // $('#district_id').on('change', function () {
        //     var selectedDistrictId = $(this).val();

        //     if (selectedDistrictId == 47) {
        //         $('#insideDhaka').prop('checked', true);
        //     } else {
        //         $('#outsideDhaka').prop('checked', true);
        //     }

        //     $.ajax({
        //         url: '{{ route("checkout.shipment") }}', 
        //         type: 'POST', 
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
        //         },
        //         data: {
        //             district_id: selectedDistrictId 
        //         },
        //         success: function(data) {
        //             $("#cart_summary").html(data.html);
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error calling route:', error);
        //         }
        //     });
        // });

        $('#district_id, #division_id').on('change', function () {
            var selectedDistrictId = $('#district_id').val();
            var selectedDivisionId = $('#division_id').val();

            $.ajax({
                url: '{{ route("checkout.shipment") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    district_id: selectedDistrictId,
                    division_id: selectedDivisionId
                },
                success: function(data) {
                    // Automatically check based on server zone logic
                    if (data.is_inside_dhaka) {
                        $('#insideDhaka').prop('checked', true);
                    } else {
                        $('#outsideDhaka').prop('checked', true);
                    }

                    // Update cart summary
                    $("#cart_summary").html(data.html);
                },
                error: function(xhr, status, error) {
                    console.error('Error calling route:', error);
                }
            });
        });

    

        $(document).ready(function() {
            $(".online_payment").click(function() {
                $('#manual_payment_description').parent().addClass('d-none');
            });
            toggleManualPaymentData($('input[name=payment_option]:checked').data('id'));
        });

        var minimum_order_amount_check = {{ get_setting('minimum_order_amount_check') == 1 ? 1 : 0 }};
        var minimum_order_amount =
            {{ get_setting('minimum_order_amount_check') == 1 ? get_setting('minimum_order_amount') : 0 }};

        function use_wallet() {
            $('input[name=payment_option]').val('wallet');
            if ($('#agree_checkbox').is(":checked")) {
                ;
                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('You order amount is less then the minimum order amount') }}');
                } else {
                    $('#checkout-form').submit();
                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
            }
        }

        function submitOrder(el) {
            $(el).prop('disabled', true);

            if ($('#agree_checkbox').is(":checked")) {
 
                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger', '{{ translate('You order amount is less than the minimum order amount') }}');
                    $(el).prop('disabled', false); 
                } else {
                    var offline_payment_active = '{{ addon_is_activated('offline_payment') }}';
   
                    if (offline_payment_active == '1' && $('.offline_payment_option').is(":checked") && $('#trx_id').val() == '') {
                        AIZ.plugins.notify('danger', '{{ translate('You need to put Transaction id') }}');
                        $(el).prop('disabled', false); 
                    } else {

                        if (checkRequiredFields()) {
                            $('#checkout-form').submit(); 
                        } else {
                            $(el).prop('disabled', false); 
                        }
                    }
                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
                $(el).prop('disabled', false); 
            }
        }

        function checkRequiredFields() {
            var allFilled = true;
          
            $('#checkout-form [required]').each(function() {
                if ($(this).val() === '') {
                    allFilled = false;
                    return false;
                }
            });
            if (!allFilled) {
                AIZ.plugins.notify('danger', '{{ translate('Please fill in all required fields') }}');
            }
            return allFilled;
        }


        function toggleManualPaymentData(id) {
            if (typeof id != 'undefined') {
                $('#manual_payment_description').parent().removeClass('d-none');
                $('#manual_payment_description').html($('#manual_payment_info_' + id).html());
            }
        }

        $(document).on("click", "#coupon-apply", function() {
            var data = new FormData($('#apply-coupon-form')[0]);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('checkout.apply_coupon_code') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    AIZ.plugins.notify(data.response_message.response, data.response_message.message);
                    $("#cart_summary").html(data.html);
                }
            })
        });

        $(document).on("click", "#coupon-remove", function() {
            var data = new FormData($('#remove-coupon-form')[0]);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('checkout.remove_coupon_code') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    $("#cart_summary").html(data);
                }
            })
        });




        $('#division_id').on('change', function () {

        var selectedDivisionId = $(this).val();
            $('#district_id').empty();
            // $('#upazila_id').empty();
            // $('#union_id').empty();
            $.ajax({
                url: '{{ route("get-district") }}', 
                type: 'POST', 
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                },
                data: {
                    division_id: selectedDivisionId 
                },
                success: function(response) {
                    $('#district_id').append('<option value="">Select District</option>');
                    response.forEach(function (district) {
                        $('#district_id').append('<option value="' + district.id + '">' + district.name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error calling route:', error);
                }
            });
        });

        AIZ.extra.plusMinus();

        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key);
        }

        function updateQuantity(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart_summary').html(data.cart_summary_view);
                AIZ.extra.plusMinus();
            });
        }

        $(document).ready(function() {
            // Address Option Card Click Handler
            $('.address-option-card').on('click', function(e) {
                if (!$(e.target).is('input[type=radio]')) {
                    var option = $(this).data('option');
                    $('input[name="address_option"][value="' + option + '"]').prop('checked', true).trigger('change');
                }
            });

            // Update address card styles on change
            $('input[name="address_option"]').on('change', function() {
                var primaryColor = '{{ get_setting('base_color', '#d43533') }}';
                $('.address-option-card').css({
                    'border-color': '#e0e0e0',
                    'background': '#ffffff'
                });
                $('.address-option-card .radio-indicator').css('border-color', '#e0e0e0');
                $('.address-option-card .radio-dot').css('background', 'transparent');
                
                var selectedCard = $(this).closest('.address-option-card');
                selectedCard.css({
                    'border-color': primaryColor,
                    'background': '#f8f9ff'
                });
                selectedCard.find('.radio-indicator').css('border-color', primaryColor);
                selectedCard.find('.radio-dot').css('background', primaryColor);
            });

            // Delivery Option Card Click Handler
            $('.delivery-option-card').on('click', function(e) {
                if (!$(e.target).is('input[type=radio]')) {
                    var delivery = $(this).data('delivery');
                    var radioInput = $(this).find('input[type="radio"]');
                    if (radioInput.length) {
                        radioInput.prop('checked', true).trigger('change');
                    } else {
                        $('input[name="deliveryOption"][value="' + delivery + '"]').prop('checked', true).trigger('change');
                    }
                }
            });

            // Update delivery card styles on change
            $('input[name="deliveryOption"]').on('change', function() {
                // Remove selected class from all cards
                $('.delivery-option-card').removeClass('selected');
                
                // Reset all regular zone cards
                $('.regular-zone-card').css({
                    'border-color': '#e8e8e8',
                    'background': '#ffffff',
                    'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.04)'
                });
                
                // Reset all sub-zone cards
                $('.sub-zone-card').css({
                    'border-color': '#e8e8e8',
                    'background': 'linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%)',
                    'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.04)'
                });
                
                // Reset all radio indicators and dots
                $('.delivery-option-card .radio-indicator').css('border-color', '#d0d0d0');
                $('.delivery-option-card .radio-dot').css('background', 'transparent');
                
                // Apply selected styles to the selected card
                var selectedCard = $(this).closest('.delivery-option-card');
                selectedCard.addClass('selected');
                
                var isSubZone = selectedCard.hasClass('sub-zone-card') || selectedCard.data('zone-type') === 'sub';
                
                var primaryColor = '{{ get_setting('base_color', '#d43533') }}';
                if (isSubZone) {
                    selectedCard.css({
                        'border-color': primaryColor,
                        'background': '#fafafa',
                        'box-shadow': '0 4px 16px rgba(0, 0, 0, 0.1)'
                    });
                } else {
                    selectedCard.css({
                        'border-color': primaryColor,
                        'background': '#fafafa',
                        'box-shadow': '0 4px 16px rgba(0, 0, 0, 0.1)'
                    });
                }
                selectedCard.find('.radio-indicator').css('border-color', primaryColor);
                selectedCard.find('.radio-dot').css('background', primaryColor);
            });
            
            // Add hover effects for delivery option cards
            var primaryColor = '{{ get_setting('base_color', '#d43533') }}';
            
            // Helper function to convert hex to rgba
            function hexToRgba(hex, alpha) {
                var r = parseInt(hex.slice(1, 3), 16);
                var g = parseInt(hex.slice(3, 5), 16);
                var b = parseInt(hex.slice(5, 7), 16);
                return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
            }
            
            $(document).on('mouseenter', '.delivery-option-card', function() {
                if (!$(this).find('input[type="radio"]').is(':checked')) {
                    var isSubZone = $(this).hasClass('sub-zone-card') || $(this).data('zone-type') === 'sub';
                    var shadowColor = hexToRgba(primaryColor, 0.2);
                    
                    if (isSubZone) {
                        $(this).css({
                            'border-color': primaryColor,
                            'background': 'linear-gradient(135deg, #ffffff 0%, #fafafa 100%)',
                            'box-shadow': '0 4px 16px ' + shadowColor,
                            'transform': 'translateY(-2px)'
                        });
                    } else {
                        $(this).css({
                            'border-color': primaryColor,
                            'background': '#fafafa',
                            'box-shadow': '0 4px 12px ' + shadowColor,
                            'transform': 'translateY(-2px)'
                        });
                    }
                }
            }).on('mouseleave', '.delivery-option-card', function() {
                if (!$(this).find('input[type="radio"]').is(':checked')) {
                    var isSubZone = $(this).hasClass('sub-zone-card') || $(this).data('zone-type') === 'sub';
                    if (isSubZone) {
                        $(this).css({
                            'border-color': '#e8e8e8',
                            'background': 'linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%)',
                            'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.04)',
                            'transform': 'translateY(0)'
                        });
                    } else {
                        $(this).css({
                            'border-color': '#e8e8e8',
                            'background': '#ffffff',
                            'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.04)',
                            'transform': 'translateY(0)'
                        });
                    }
                }
            });

            // Payment Option Card Click Handler
            $('.payment-option-card').on('click', function(e) {
                // Prevent clicks on upcoming/disabled cards
                if ($(this).hasClass('upcoming')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                
                if (!$(e.target).is('input[type=radio]')) {
                    var payment = $(this).data('payment');
                    var radioInput = $('input[name="payment_option"][value="' + payment + '"]');
                    
                    // Only proceed if the input is not disabled
                    if (!radioInput.prop('disabled')) {
                        radioInput.prop('checked', true).trigger('change');
                    }
                }
            });

            // Update payment card styles on change
            $('input[name="payment_option"]').on('change', function() {
                // Skip if disabled
                if ($(this).prop('disabled')) {
                    return;
                }
                
                // Remove selected class from all cards
                $('.payment-option-card').removeClass('selected');
                
                // Reset all payment cards (excluding upcoming)
                $('.payment-option-card:not(.upcoming)').css({
                    'border-color': '#e8e8e8',
                    'background': '#ffffff',
                    'box-shadow': '0 2px 8px rgba(0, 0, 0, 0.04)'
                });
                $('.cod-card:not(.upcoming)').css('background', 'linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%)');
                $('.payment-option-card:not(.upcoming) .radio-indicator').css('border-color', '#d0d0d0');
                $('.payment-option-card:not(.upcoming) .radio-dot').css('background', 'transparent');
                
                // Highlight selected card
                var selectedCard = $(this).closest('.payment-option-card');
                if (!selectedCard.hasClass('upcoming')) {
                    selectedCard.addClass('selected');
                    
                    var isCod = selectedCard.hasClass('cod-card');
                    var primaryColor = '{{ get_setting('base_color', '#d43533') }}';
                    selectedCard.css({
                        'border-color': primaryColor,
                        'background': '#fafafa',
                        'box-shadow': '0 4px 16px rgba(0, 0, 0, 0.1)'
                    });
                    selectedCard.find('.radio-indicator').css('border-color', primaryColor);
                    selectedCard.find('.radio-dot').css('background', primaryColor);
                }
            });

            // Initialize selected states
            $('input[name="address_option"]:checked').trigger('change');
            $('input[name="deliveryOption"]:checked').trigger('change');
            $('input[name="payment_option"]:checked').trigger('change');

            @if(Auth::check() && $defaultAddress)
            // Default address data
            var defaultAddress = {
                name: '{{ $defaultAddress->name ?? Auth::user()->name ?? "" }}',
                address: '{{ addslashes($defaultAddress->address ?? "") }}',
                phone: '{{ $defaultAddress->phone ?? "" }}',
                additional_info: '{{ addslashes($defaultAddress->additional_info ?? "") }}'
            };

            // Handle address option selection
            $('input[name="address_option"]').on('change', function() {
                if ($(this).val() === 'default') {
                    fillDefaultAddress();
                } else {
                    clearAddressFields();
                }
            });

            // Auto-fill default address on page load if default is selected
            if ($('#useDefaultAddress').is(':checked')) {
                fillDefaultAddress();
            }

            function fillDefaultAddress() {
                $('#name').val(defaultAddress.name);
                $('#address').val(defaultAddress.address);
                $('#phone').val(defaultAddress.phone);
                $('textarea[name="additional_info"]').val(defaultAddress.additional_info);
            }

            function clearAddressFields() {
                $('#name').val('');
                $('#address').val('');
                $('#phone').val('');
                $('textarea[name="additional_info"]').val('');
            }
            @endif

            // Update cart summary when delivery option changes manually
            $('input[name="deliveryOption"]').on('change', function() {
                var deliveryOption = $(this).val();
                var districtId = null;
                var divisionId = null;
                
                // Call shipment route to update cart summary
                $.ajax({
                    url: '{{ route("checkout.shipment") }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        delivery_option: deliveryOption,
                        district_id: districtId,
                        division_id: divisionId
                    },
                    success: function(data) {
                        $("#cart_summary").html(data.html);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating shipment:', error);
                    }
                });
            });
        });

    </script>
@endsection