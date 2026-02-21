@extends('frontend.layouts.app')

@section('content')
    <style>
          #section_featured .slick-slider .slick-list{
            background: #fff;
        }
        #section_featured .slick-slider .slick-list .slick-slide {
            margin-bottom: -5px;
        }
        @media (max-width: 575px){
            #section_featured .slick-slider .slick-list .slick-slide {
                margin-bottom: -4px;
            }
        }
        
        /* Flash Deal Products Mobile Fix */
        @media (max-width: 767px) {
            #flash_deal .carousel-box {
                flex: 0 0 50% !important;
            }
            
            #flash_deal .carousel-box.px-2 {
                padding-left: 8px !important;
                padding-right: 8px !important;
            }
        }
        
        /* Flash Sale Badge Animation */
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }
        
        @keyframes shine {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }
        
        @keyframes titlePulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.9;
            }
        }
        
        @keyframes titleFade {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .featured-categories-title {
            animation: titleFade 1s ease-out, titlePulse 3s ease-in-out infinite;
            display: inline-block;
        }
        
        .flash-sale-badge {
            position: relative;
            overflow: hidden;
        }
        
        .flash-sale-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shine 2s infinite;
        }
        
        /* Mobile Header Layout for Flash Deals */
        @media (max-width: 767px) {
            .flash-deal-badge-mobile {
                width: 100% !important;
                text-align: center !important;
            }
            
            .flash-deal-title-mobile {
                width: 100% !important;
                position: static !important;
                transform: none !important;
                margin-bottom: 1rem !important;
            }
            
            .flash-deal-buttons-mobile {
                width: 100% !important;
                justify-content: center !important;
            }
            
            #flash_deal .ascolour-press-hero > div {
                flex-direction: column !important;
                align-items: center !important;
            }
            
            #flash_deal .ascolour-press-hero > div > div {
                text-align: center !important;
            }
            
            .flash-sale-badge {
                padding: 8px 16px !important;
            }
            .flash-sale-badge span {
                font-size: 12px !important;
            }
            .flash-sale-badge i {
                font-size: 16px !important;
            }
            .flash-deal-title {
                font-size: 18px !important;
            }
        }
        
        @media (min-width: 768px) {
            .flash-deal-badge-mobile {
                width: auto !important;
                text-align: left !important;
            }
            
            .flash-deal-title-mobile {
                position: absolute !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                width: auto !important;
            }
            
            .flash-deal-buttons-mobile {
                width: auto !important;
                justify-content: flex-end !important;
                margin-left: auto !important;
            }
        }
        
        @media (max-width: 991px) and (min-width: 768px) {
            .flash-deal-title {
                font-size: 20px !important;
                letter-spacing: 1px !important;
                white-space: normal !important;
            }
        }
        
        .ascolour-hero-section {
            position: relative;
            margin-bottom: 2rem;
        }
        .ascolour-hero-banner {
            display: flex;
            min-height: 460px;
            /* border-radius: clamp(16px, 3vw, 26px); */
            overflow: hidden;
            background: linear-gradient(135deg, #fdfbf7 0%, #f0ece6 100%);
            gap: 1px;
            isolation: isolate;
            border: 1px solid #e5dfd4;
        }
        .ascolour-hero-card {
            flex: 1 1 50%;
            position: relative;
            display: flex;
            align-items: flex-end;
            padding: clamp(2.5rem, 4vw, 4rem);
            color: #fefefe;
            text-decoration: none;
            transition: transform .5s ease, filter .5s ease;
            overflow: hidden;
            isolation: isolate;
        }
        .ascolour-hero-card-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            z-index: -1;
        }
        .ascolour-hero-card::before{
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(145deg, rgba(4,4,4,.15) 0%, rgba(4,4,4,.78) 90%);
            transition: opacity .4s ease;
            z-index: 0;
        }
        .ascolour-hero-card:hover{
            transform: translateY(-8px) scale(1.01);
        }
        .ascolour-hero-card:hover::before{
            opacity: .95;
        }
        .ascolour-hero-card-content{
            position: relative;
            z-index: 1;
            max-width: 360px;
            max-height: 140px;
        }
        
        @media (max-width: 767px) {
            .ascolour-hero-card-content {
                max-height: none !important;
            }
            
            .ascolour-hero-cta {
                opacity: 1 !important;
                transform: translateY(0) !important;
                filter: blur(0) !important;
                pointer-events: auto !important;
            }
        }
        
        /* Fade Up Animation */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .ascolour-hero-pill,
        .ascolour-hero-meta,
        .ascolour-hero-copy {
            animation: fadeUp 0.8s ease-out forwards;
            opacity: 0;
        }
        
        .ascolour-hero-pill {
            animation-delay: 0.1s;
        }
        
        .ascolour-hero-meta {
            animation-delay: 0.3s;
        }
        
        .ascolour-hero-copy {
            animation-delay: 0.5s;
        }
        .ascolour-hero-eyebrow{
            text-transform: uppercase;
            letter-spacing: .35rem;
            font-size: .8rem;
            font-weight: 600;
            display: block;
            margin-bottom: 1rem;
            opacity: .75;
        }
        .ascolour-hero-pill{
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .9rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.35);
            background: rgba(6,6,6,.25);
            font-size: 1rem;
            letter-spacing: .18rem;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }
        .ascolour-hero-heading{
            font-size: clamp(1.85rem, 1.4vw, 3rem);
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: .75rem;
            letter-spacing: .04em;
            text-transform: capitalize;
            text-shadow: 0 12px 36px rgba(0,0,0,.5);
            transition: transform .45s ease, color .45s ease;
        }
        .ascolour-hero-card:hover .ascolour-hero-heading{
            transform: translateY(-4px);
            color: #ffffff;
        }
        .ascolour-hero-meta{
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.25rem;
            font-size: .85rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            opacity: .8;
        }
        .ascolour-hero-meta span{
            display: inline-flex;
            align-items: center;
            gap: .35rem;
        }
        .ascolour-hero-meta svg{
            width: 16px;
            height: 16px;
        }
        .ascolour-hero-copy{
            /* margin-bottom: 1.5rem; */
            color: rgba(255,255,255,.9);
            font-size: .95rem;
            line-height: 1.55;
            font-family: 'Playfair Display', 'Times New Roman', serif;
        }
        .ascolour-hero-cta-wrap{
            display: inline-flex;
            position: relative;
            overflow: hidden;
        }
        .ascolour-hero-cta{
            font-size: .95rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            /* padding: .85rem 1.35rem; */
            padding: 5px;
            border-radius: 999px;
            background: linear-gradient(135deg, #fef1d2, #f4c27d);
            color: #342a17;
            box-shadow: 0 10px 30px rgba(0,0,0,.35);
            opacity: 0;
            transform: translateY(-30px);
            filter: blur(6px);
            transition: opacity .45s ease, transform .45s ease, filter .45s ease;
            pointer-events: none;
        }
        .ascolour-hero-card:hover .ascolour-hero-cta{
            opacity: 1;
            transform: translateY(0);
            filter: blur(0);
            pointer-events: auto;
        }
        .ascolour-hero-cta svg{
            width: 18px;
            height: 18px;
        }
        .ascolour-hero-floating-number{
            position: absolute;
            top: clamp(1.5rem, 3vw, 2.25rem);
            right: clamp(1.5rem, 3vw, 2.25rem);
            font-size: clamp(2.75rem, 6vw, 4.5rem);
            font-weight: 600;
            letter-spacing: .3rem;
            color: rgba(255,255,255,.35);
            z-index: 0;
        }
        .ascolour-hero-initial{
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(7rem, 12vw, 10rem);
            font-weight: 700;
            opacity: .2;
        }
        @media (max-width: 991px){
            .ascolour-hero-banner{
                flex-direction: column;
                min-height: unset;
                border-radius: 0;
            }
            .ascolour-hero-card{
                min-height: 320px;
                padding: 2.25rem;
            }
        }

        .ascolour-press-hero{
            padding: 3rem 0 2rem;
        }
        .ascolour-press-copy{
            text-align: center;
            margin-bottom: 2rem;
        }
        .ascolour-press-eyebrow{
            font-size: .85rem;
            letter-spacing: .35rem;
            text-transform: uppercase;
            color: #9b9b9b;
            display: block;
            margin-bottom: 1rem;
        }
        .ascolour-press-title{
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 700;
            color: #111;
            margin-bottom: .75rem;
        }
        .ascolour-press-subtitle{
            font-size: .95rem;
            letter-spacing: .25rem;
            text-transform: uppercase;
            color: #a1a1a1;
        }
        .ascolour-press-slider{
            /* border-radius: 28px; */
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0,0,0,.12);
        }
        .ascolour-press-slide img{
            width: 100%;
            min-height: 280px;
            max-height: 560px;
            object-fit: cover;
            display: block;
        }
        @media (max-width: 767px){
            .ascolour-press-hero{
                padding-top: 2rem;
            }
            .ascolour-press-subtitle{
                letter-spacing: .15rem;
            }
        }
    </style>

    <div class="custom-container bg-light">

    @php $lang = get_system_language()->code;  @endphp

    @php
        $heroCategories = get_level_zero_categories()->take(2);
    @endphp
    @if($heroCategories->count())
        <section class="ascolour-hero-section custom-container">
            <div class="ascolour-hero-banner">
                @foreach($heroCategories as $index => $category)
                    @php
                        $bannerImage = $category->banner ? uploaded_asset($category->banner) : null;
                        $initial = mb_substr($category->getTranslation('name'), 0, 1);
                        $collectionNumber = str_pad($loop->iteration, 2, '0', STR_PAD_LEFT);
                        $tagline = $category->meta_description ?: ($category->description ?? null);
                        $tagline = $tagline ? \Illuminate\Support\Str::limit(strip_tags($tagline), 120) : translate('Thoughtful palettes, considered fits and premium bases crafted for everyday wardrobes.');
                        $subCategoryCount = optional($category->children)->count() ?? 0;
                    @endphp
                    <a href="{{ route('products.category', $category->slug) }}" class="ascolour-hero-card" style="{{ !$bannerImage ? 'background: linear-gradient(135deg, #3f3f3f, #171717);' : '' }}">
                        @if($bannerImage)
                            <img class="ascolour-hero-card-image lazyload" 
                                src="{{ static_asset('assets/img/placeholder.jpg') }}" 
                                data-src="{{ $bannerImage }}" 
                                alt="{{ $category->getTranslation('name') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        @endif
                        <div class="ascolour-hero-card-content">
                            <!-- <span class="ascolour-hero-eyebrow">{{ translate('Since 2005') }}</span>
                            <span class="ascolour-hero-pill">
                                {{ translate('Drop') }} {{ date('y') }}
                                <span style="opacity:.5;">•</span>
                                {{ $category->getTranslation('name') }}
                            </span> -->
                            <div class="ascolour-hero-pill">{{ $category->getTranslation('name') }}</div>
                            <div class="ascolour-hero-meta">
                                <span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09A1.65 1.65 0 0 0 15 4.6a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09A1.65 1.65 0 0 0 19.4 15z"></path>
                                    </svg>
                                    {{ translate('Styles') }} {{ max($subCategoryCount, 4) }}
                                </span>
                                <span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M4 7h16"></path>
                                        <path d="M10 11h10"></path>
                                        <path d="M4 15h16"></path>
                                    </svg>
                                    {{ translate('Season') }} {{ now()->format('M') }}
                                </span>
                            </div>
                            <p class="ascolour-hero-copy">{{ $tagline }}</p>
                            <span class="ascolour-hero-cta">
                                {{ translate('Shop now') }}
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14"></path>
                                    <path d="m13 6 6 6-6 6"></path>
                                </svg>
                            </span>
                        </div>
                        <!-- <span class="ascolour-hero-floating-number">{{ $collectionNumber }}</span>
                        @unless($bannerImage)
                            <span class="ascolour-hero-initial">{{ $initial }}</span>
                        @endunless -->
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Flash Deal -->
    @php
        $flash_deal = get_featured_flash_deal();
        $flash_deal_bg = get_setting('flash_deal_bg_color');
        $flash_deal_bg_full_width = (get_setting('flash_deal_bg_full_width') == 1) ? true : false;
        $flash_deal_banner_menu_text = ((get_setting('flash_deal_banner_menu_text') == 'dark') ||  (get_setting('flash_deal_banner_menu_text') == null)) ? 'text-dark' : 'text-white';

    @endphp
    @if ($flash_deal != null)
        @php
            $flash_deal_products = get_flash_deal_products($flash_deal->id);
        @endphp
        <!-- Header Section -->
        <div class="bg-white">
            <!-- Top Section -->
            <div class="ascolour-press-hero mb-3 mb-md-4" style="padding-top: 1rem; padding-bottom: 1rem;">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between position-relative">
                    <!-- Flash Sale Badge - Left Side -->
                    <div class="mb-2 mb-md-0 flash-deal-badge-mobile" style="flex: 0 0 auto; order: 1; width: 100%; text-align: center;">
                        <span style="width: auto;" class="flash-sale-badge badge badge-danger px-4 px-md-5 py-2 py-md-3" 
                            style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%); border-radius: 12px; box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4); display: inline-block;">
                            <i class="las la-bolt" style="font-size: 20px; vertical-align: middle; animation: pulse 1.5s infinite;"></i> 
                            <span style="font-size: 16px; font-weight: 700; letter-spacing: 1px; vertical-align: middle;">{{ translate('FLASH SALE') }}</span>
                        </span>
                    </div>
                    
                    <!-- Title - Center -->
                    <div class="flex-grow-1 text-center mb-2 mb-md-0 flash-deal-title-mobile" style="order: 2; width: 100%; position: static; transform: none;">
                        <h3 class="ascolour-press-title mb-0" 
                            style="{{ ($flash_deal_bg_full_width && $flash_deal_bg != null && get_setting('flash_deal_banner_menu_text') == 'light') ? 'color: #fff;' : '' }}">
                            {{ $flash_deal->getTranslation('title') }}
                        </h3>
                    </div>
                    
                    <!-- Action Links - Right Side -->
                    <div class="d-flex align-items-center justify-content-center flash-deal-buttons-mobile" style="flex: 0 0 auto; order: 3; width: 100%;">
                        <a href="{{ route('flash-deals') }}"
                            class="btn btn-sm btn-secondary mr-2 mr-md-3 has-transition @if ((get_setting('flash_deal_banner_menu_text') == 'light') && $flash_deal_bg_full_width && $flash_deal_bg != null) btn-outline-light @endif" style="border-radius: 5px;">
                            <span class="d-none d-md-inline">{{ translate('View All') }}</span>
                            <span class="d-md-none">{{ translate('All') }}</span>
                        </a>
                        <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                            class="btn btn-sm btn-secondary has-transition @if ((get_setting('flash_deal_banner_menu_text') == 'light') && $flash_deal_bg_full_width && $flash_deal_bg != null) btn-outline-light @endif" style="border-radius: 5px;">
                            {{ translate('Shop Now') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <section class="mb-4 mb-md-5 mt-4 mt-md-5 ascolour-flash-deal ascolour-flash-deal-bg border border-shadow-lg" style="background: white;" id="flash_deal">
            <div class="container-fluid px-3 px-md-4">
                <div class="bg-white rounded-lg overflow-hidden shadow-sm" style="border-radius: 12px; @if($flash_deal_bg) border: 2px solid {{ $flash_deal_bg }}; border-bottom: 3px solid {{ $flash_deal_bg }}; @else border: 2px solid #ff6b6b; border-bottom: 3px solid #ff6b6b; @endif">
                    <div class="row no-gutters" style="min-height: 450px;">
                        <!-- Banner & Countdown Section -->
                        <div class="col-12 col-lg-4 d-flex align-items-stretch">
                            <a href="{{ route('flash-deal-details', $flash_deal->slug) }}" class="d-block position-relative overflow-hidden w-100 h-100" style="min-height: 450px;">
                                <div class="h-100 w-100 position-relative" 
                                    style="background-image: url('{{ uploaded_asset($flash_deal->banner) }}'); background-size: cover; background-position: center center; background-repeat: no-repeat; background-attachment: local;">
                                    <!-- Gradient Overlay -->
                                    <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(255,107,107,0.15) 0%, rgba(238,90,111,0.15) 100%); pointer-events: none;"></div>
                                    <!-- Countdown Timer - Bottom -->
                                    <div class="position-absolute w-100 bottom-0 left-0 d-flex align-items-center justify-content-center" style="background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 70%, transparent 100%); padding: 20px 15px; backdrop-filter: blur(2px);">
                                        <div class="text-center w-100">
                                            <p class="text-white fs-11 fs-md-12 fw-600 mb-2" style="letter-spacing: 1px; text-transform: uppercase;">{{ translate('Hurry Up! Limited Time') }}</p>
                                            <div class="aiz-count-down-circle" end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Products Section -->
                        <div class="col-12 col-lg-8 d-flex align-items-stretch" style="@if($flash_deal_bg && !$flash_deal_bg_full_width) background: {{ $flash_deal_bg }}; @endif">
                            <div class="p-3 p-md-4 w-100 d-flex flex-column">
                                @if(count($flash_deal_products) > 0)
                                    <div class="aiz-carousel arrow-inactive-none arrow-x-0"
                                        data-rows="2" data-items="4" data-xxl-items="4" data-xl-items="4" data-lg-items="3" data-md-items="3"
                                        data-sm-items="2" data-xs-items="2" data-arrows="true" data-dots="false">
                                        @foreach ($flash_deal_products as $key => $flash_deal_product)
                                            @if ($flash_deal_product->product != null && $flash_deal_product->product->published != 0)
                                                @php
                                                    $product_url = route('product', $flash_deal_product->product->slug);
                                                    if ($flash_deal_product->product->auction_product == 1) {
                                                        $product_url = route('auction-product', $flash_deal_product->product->slug);
                                                    }
                                                @endphp
                                                <div class="carousel-box px-2">
                                                    <a href="{{ $product_url }}" 
                                                        class="d-block bg-white rounded-lg p-3 text-center h-100 has-transition hov-shadow-lg"
                                                        style="border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.04); transition: all 0.3s ease; border-radius: 10px;"
                                                        title="{{ $flash_deal_product->product->getTranslation('name') }}"
                                                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(255,107,107,0.25), 0 0 0 1px rgba(255,107,107,0.3)';"
                                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.04)';">
                                                        <!-- Product Image -->
                                                        <div class="position-relative mb-3" style="height: 140px; display: flex; align-items: center; justify-content: center;">
                                                            <img src="{{ get_image($flash_deal_product->product->thumbnail) }}"
                                                                class="lazyload mw-100 mh-100 mx-auto has-transition"
                                                                style="max-height: 140px; object-fit: contain; transition: transform 0.3s ease;"
                                                                alt="{{ $flash_deal_product->product->getTranslation('name') }}"
                                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                                                onmouseover="this.style.transform='scale(1.1)';"
                                                                onmouseout="this.style.transform='scale(1)';">
                                                            <!-- Discount Badge -->
                                                            @if (home_base_price($flash_deal_product->product) != home_discounted_base_price($flash_deal_product->product))
                                                                @php
                                                                    $discount = discount_in_percentage($flash_deal_product->product);
                                                                @endphp
                                                                @if($discount > 0)
                                                                    <span style="width: auto;" class="position-absolute top-0 right-0 badge badge-danger fs-11 px-2 py-1" style="border-radius: 6px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);">
                                                                        -{{ number_format($discount, 0) }}%
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <!-- Product Name -->
                                                        <h6 class="fs-13 fs-md-14 fw-600 text-dark mb-2 text-truncate" style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                            {{ $flash_deal_product->product->getTranslation('name') }}
                                                        </h6>
                                                        <!-- Price -->
                                                        <div class="text-center">
                                                            <span class="d-block mb-1" style="font-size: 20px; font-weight: 900; color: #111; line-height: 1.2;">
                                                                {{ home_discounted_base_price($flash_deal_product->product) }}
                                                            </span>
                                                            @if (home_base_price($flash_deal_product->product) != home_discounted_base_price($flash_deal_product->product))
                                                                <del class="d-block" style="font-size: 13px; color: #999; font-weight: 400; margin-top: 2px;">
                                                                    {{ home_base_price($flash_deal_product->product) }}
                                                                </del>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <p class="text-muted mb-0">{{ translate('No products available in this flash deal.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Today's deal -->
    @php
        $todays_deal_section_bg = get_setting('todays_deal_section_bg_color');
    @endphp
    <div id="todays_deal" class="mb-2rem mt-2 mt-md-3" @if(get_setting('todays_deal_section_bg') == 1) style="background: {{ $todays_deal_section_bg }};" @endif>

    </div>

    <!-- Featured Categories -->
    @if (count($featured_categories) > 0)
        <section class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="">
                <div class="bg-white">
                    <!-- Top Section -->
                    <div class="mt-2 mt-md-3 mb-2 p-2 mb-md-3 d-flex justify-content-center align-items-center">
                        <!-- Title -->
                        <h3 class=" mb-2 mb-sm-0 text-center">
                            <span style="font-size: 22px;" class="ascolour-press-title featured-categories-titl">{{ translate('Featured Categories') }}</span>
                        </h3>
                    </div>
                </div>
                <!-- Categories -->
                <div class="bg-white px-sm-3">
                    <div class="aiz-carousel sm-gutters-17" data-items="4" data-xxl-items="4" data-xl-items="3.5"
                        data-lg-items="3" data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows="true"
                        data-dots="false" data-autoplay="false" data-infinite="true">
                        @foreach ($featured_categories as $key => $category)
                            @php
                                $category_name = $category->getTranslation('name');
                            @endphp
                            <div class="carousel-box position-relative p-0 has-transition border-right border-top border-bottom @if ($key == 0) border-left @endif">
                                <div class="h-200px h-sm-250px h-md-340px">
                                    <div class="h-100 w-100 w-xl-auto position-relative hov-scale-img overflow-hidden">
                                        <div class="position-absolute h-100 w-100 overflow-hidden">
                                            <img src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                alt="{{ $category_name }}"
                                                class="img-fit h-100 has-transition"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </div>
                                        <div class="pb-4 px-4 absolute-bottom-left has-transition h-50 w-100 d-flex flex-column align-items-center justify-content-end"
                                            style="background: linear-gradient(to top, rgba(0,0,0,0.5) 50%,rgba(0,0,0,0) 100%) !important;">
                                            <div class="w-100">
                                                <a class="fs-16 fw-700 text-white animate-underline-white home-category-name d-flex align-items-center hov-column-gap-1"
                                                    href="{{ route('products.category', $category->slug) }}"
                                                    style="width: max-content;">
                                                    {{ $category_name }}&nbsp;
                                                    <i class="las la-angle-right"></i>
                                                </a>
                                                <div class="d-flex flex-wrap h-50px overflow-hidden mt-2">
                                                    @foreach ($category->childrenCategories->take(6) as $key => $child_category)
                                                    <a href="{{ route('products.category', $child_category->slug) }}" class="fs-13 fw-300 text-soft-light hov-text-white pr-3 pt-1">
                                                        {{ $child_category->getTranslation('name') }}
                                                    </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Grouped Product Categories Section -->
    @php
        use App\Models\GroupProductCategoryStandalone;
        $group_product_categories = GroupProductCategoryStandalone::with(['coverImage', 'bannerImage', 'childrenCategories'])
            ->where('parent_id', 0)
            ->where('active', 1)
            ->orderBy('order_level', 'desc')
            ->limit(6)
            ->get();
        
    @endphp
    @if(count($group_product_categories) > 0)
        <section class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="">
                <div class="bg-white">
                    <!-- Top Section -->
                    <div class="mt-2 mt-md-3 mb-2 p-2 mb-md-3 d-flex justify-content-center align-items-center">
                        <!-- Title -->
                        <h3 class=" mb-2 mb-sm-0 text-center">
                            <span style="font-size: 22px;" class="ascolour-press-title featured-categories-titl">{{ translate('Group Product Categories') }}</span>
                        </h3>
                    </div>
                </div>
                <!-- Categories -->
                <div class="bg-white px-sm-3">
                    <div class="aiz-carousel sm-gutters-17" data-items="4" data-xxl-items="4" data-xl-items="3.5"
                        data-lg-items="3" data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows="true"
                        data-dots="false" data-autoplay="false" data-infinite="true">
                        @foreach ($group_product_categories as $key => $category)
                            @php
                                $category_name = $category->getTranslation('name');
                                $category_slug = $category->slug ?? \Illuminate\Support\Str::slug($category_name);
                                $cover_image = null;
                                if (isset($category->coverImage->file_name)) {
                                    $cover_image = my_asset($category->coverImage->file_name);
                                } elseif (isset($category->bannerImage->file_name)) {
                                    $cover_image = my_asset($category->bannerImage->file_name);
                                } else {
                                    $cover_image = static_asset('assets/img/placeholder.jpg');
                                }
                            @endphp
                            <div class="carousel-box position-relative p-0 has-transition border-right border-top border-bottom @if ($key == 0) border-left @endif">
                                <div class="h-200px h-sm-250px h-md-340px">
                                    <div class="h-100 w-100 w-xl-auto position-relative hov-scale-img overflow-hidden">
                                        <div class="position-absolute h-100 w-100 overflow-hidden">
                                            <img src="{{ $cover_image }}"
                                                alt="{{ $category_name }}"
                                                class="img-fit h-100 has-transition"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        </div>
                                        <div class="pb-4 px-4 absolute-bottom-left has-transition h-50 w-100 d-flex flex-column align-items-center justify-content-end"
                                            style="background: linear-gradient(to top, rgba(0,0,0,0.5) 50%,rgba(0,0,0,0) 100%) !important;">
                                            <div class="w-100">
                                                <a class="fs-16 fw-700 text-white animate-underline-white home-category-name d-flex align-items-center hov-column-gap-1"
                                                    href="{{ route('group_products.category', ['category_slug' => $category_slug]) }}"
                                                    style="width: max-content;">
                                                    {{ $category_name }}&nbsp;
                                                    <i class="las la-angle-right"></i>
                                                </a>
                                                @if($category->childrenCategories && $category->childrenCategories->count() > 0)
                                                    <div class="d-flex flex-wrap h-50px overflow-hidden mt-2">
                                                        @foreach ($category->childrenCategories->take(6) as $key => $child_category)
                                                        @php
                                                            $child_category_slug = $child_category->slug ?? \Illuminate\Support\Str::slug($child_category->getTranslation('name'));
                                                        @endphp
                                                        <a href="{{ route('group_products.category', ['category_slug' => $child_category_slug]) }}" class="fs-13 fw-300 text-soft-light hov-text-white pr-3 pt-1">
                                                            {{ $child_category->getTranslation('name') }}
                                                        </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Banner section 1 -->
    @php $homeBanner1Images = get_setting('home_banner1_images', null, $lang);   @endphp
    @if ($homeBanner1Images != null)
        <div class="pb-2 pb-md-3 pt-2 pt-md-3" style="background: #f5f5fa;">
            <div class=" mb-2 mb-md-3">
                @php
                    $banner_1_imags = json_decode($homeBanner1Images);
                    $data_md = count($banner_1_imags) >= 2 ? 2 : 1;
                    $home_banner1_links = get_setting('home_banner1_links', null, $lang);
                @endphp
                <div class="w-100">
                    <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                        data-items="{{ count($banner_1_imags) }}" data-xxl-items="{{ count($banner_1_imags) }}"
                        data-xl-items="{{ count($banner_1_imags) }}" data-lg-items="{{ $data_md }}"
                        data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                        data-dots="false">
                        @foreach ($banner_1_imags as $key => $value)
                            <div class="carousel-box overflow-hidden hov-scale-img">
                                <a href="{{ isset(json_decode($home_banner1_links, true)[$key]) ? json_decode($home_banner1_links, true)[$key] : '' }}"
                                    class="d-block text-reset overflow-hidden">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                        class="img-fluid lazyload w-100 has-transition"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    
    <section class="ascolour-press-hero">
        <div class="ascolour-press-copy">
            <span class="ascolour-press-eyebrow">{{ translate('Established in Bangladesh, 2005') }}</span>
            <h2 class="ascolour-press-title">{{ translate('Vibes | Premium Blanks | Built to Last') }}</h2>
            <div class="ascolour-press-subtitle">{{ translate('The global leader in premium blank apparel') }}</div>
        </div>
        @if (get_setting('home_slider_images', null, $lang) != null)
            @php
                $decoded_slider_images = json_decode(get_setting('home_slider_images', null, $lang), true);
                $sliders = get_slider_images($decoded_slider_images);
                $home_slider_links = get_setting('home_slider_links', null, $lang);
            @endphp
            @if(count($sliders))
                <div class="ascolour-press-slider">
                    <div class="aiz-carousel dots-inside-bottom" data-items="1" data-fade="true" data-autoplay="true" data-infinite="true">
                        @foreach ($sliders as $key => $slider)
                            @php
                                $slideLink = isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '';
                            @endphp
                            <!-- <div class="ascolour-press-slide">
                                <a href="{{ $slideLink }}">
                                    <img src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        alt="{{ env('APP_NAME') }} press hero"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                </a>
                            </div> -->
                            <div class="carousel-box">
                                <a href="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}">
                                    <!-- Image -->
                                    <div class="d-block mw-100 img-fit overflow-hidden h-180px h-md-320px h-lg-460px h-xl-553px overflow-hidden">
                                        <img class="img-fit h-100 m-auto has-transition ls-is-cached lazyloaded"
                                        src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        alt="{{ env('APP_NAME') }} promo"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </section>

    <!-- Featured Products -->
    <div id="section_featured" class="pt-2 pt-md-3" style="background: #f5f5fa;">

    </div>

    @if (addon_is_activated('preorder'))

        <!-- Preorder Banner 1 -->
        @php $homepreorder_banner_1Images = get_setting('home_preorder_banner_1_images', null, $lang);   @endphp
        @if ($homepreorder_banner_1Images != null)
            <div class="mb-2 mb-md-3 mt-2 mt-md-3">
                <div class="">
                    @php
                        $banner_2_imags = json_decode($homepreorder_banner_1Images);
                        $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
                        $home_preorder_banner_1_links = get_setting('home_preorder_banner_1_links', null, $lang);
                    @endphp
                    <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                        data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
                        data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}"
                        data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                        data-dots="false">
                        @foreach ($banner_2_imags as $key => $value)
                            <div class="carousel-box overflow-hidden hov-scale-img">
                                <a href="{{ isset(json_decode($home_preorder_banner_1_links, true)[$key]) ? json_decode($home_preorder_banner_1_links, true)[$key] : '' }}"
                                    class="d-block text-reset overflow-hidden">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                        class="img-fluid lazyload w-100 has-transition"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


        <!-- Featured Preorder Products -->
        <div id="section_featured_preorder_products">

        </div>
    @endif

    <!-- Banner Section 2 -->
    @php $homeBanner2Images = get_setting('home_banner2_images', null, $lang);   @endphp
    @if ($homeBanner2Images != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="">
                @php
                    $banner_2_imags = json_decode($homeBanner2Images);
                    $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
                    $home_banner2_links = get_setting('home_banner2_links', null, $lang);
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
                    data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_2_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img">
                            <a href="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}"
                                class="d-block text-reset overflow-hidden">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    class="img-fluid lazyload w-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Best Selling  -->
    <div id="section_best_selling">

    </div>

    <!-- New Products -->
    <div id="section_newest">

    </div>

    <!-- Banner Section 3 -->
    @php $homeBanner3Images = get_setting('home_banner3_images', null, $lang);   @endphp
    @if ($homeBanner3Images != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="">
                @php
                    $banner_3_imags = json_decode($homeBanner3Images);
                    $data_md = count($banner_3_imags) >= 2 ? 2 : 1;
                    $home_banner3_links = get_setting('home_banner3_links', null, $lang);
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_3_imags) }}" data-xxl-items="{{ count($banner_3_imags) }}"
                    data-xl-items="{{ count($banner_3_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_3_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img">
                            <a href="{{ isset(json_decode($home_banner3_links, true)[$key]) ? json_decode($home_banner3_links, true)[$key] : '' }}"
                                class="d-block text-reset overflow-hidden">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    class="img-fluid lazyload w-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Auction Product -->
    @if (addon_is_activated('auction'))
        <div id="auction_products">

        </div>
    @endif

    <!-- Cupon -->
    @if (get_setting('coupon_system') == 1)
        <div class=" mt-2 mt-md-3"
            style="background-color: {{ get_setting('cupon_background_color', '#292933') }}">
            <div class="">
                <div class="position-relative py-5">
                    <div class="text-center text-xl-left position-relative z-5">
                        <div class="d-lg-flex">
                            <div class="mb-3 mb-lg-0">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="109.602" height="93.34" viewBox="0 0 109.602 93.34">
                                    <defs>
                                        <clipPath id="clip-pathcup">
                                            <path id="Union_10" data-name="Union 10" d="M12263,13778v-15h64v-41h12v56Z"
                                                transform="translate(-11966 -8442.865)" fill="none" stroke="#fff"
                                                stroke-width="2" />
                                        </clipPath>
                                    </defs>
                                    <g id="Group_24326" data-name="Group 24326"
                                        transform="translate(-274.201 -5254.611)">
                                        <g id="Mask_Group_23" data-name="Mask Group 23"
                                            transform="translate(-3652.459 1785.452) rotate(-45)"
                                            clip-path="url(#clip-pathcup)">
                                            <g id="Group_24322" data-name="Group 24322"
                                                transform="translate(207 18.136)">
                                                <g id="Subtraction_167" data-name="Subtraction 167"
                                                    transform="translate(-12177 -8458)" fill="none">
                                                    <path
                                                        d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                        stroke="none" />
                                                    <path
                                                        d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                        stroke="none" fill="#fff" />
                                                </g>
                                            </g>
                                        </g>
                                        <g id="Group_24321" data-name="Group 24321"
                                            transform="translate(-3514.477 1653.317) rotate(-45)">
                                            <g id="Subtraction_167-2" data-name="Subtraction 167"
                                                transform="translate(-12177 -8458)" fill="none">
                                                <path
                                                    d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                    stroke="none" />
                                                <path
                                                    d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                    stroke="none" fill="#fff" />
                                            </g>
                                            <g id="Group_24325" data-name="Group 24325">
                                                <rect id="Rectangle_18578" data-name="Rectangle 18578" width="8"
                                                    height="2" transform="translate(120 5287)" fill="#fff" />
                                                <rect id="Rectangle_18579" data-name="Rectangle 18579" width="8"
                                                    height="2" transform="translate(132 5287)" fill="#fff" />
                                                <rect id="Rectangle_18581" data-name="Rectangle 18581" width="8"
                                                    height="2" transform="translate(144 5287)" fill="#fff" />
                                                <rect id="Rectangle_18580" data-name="Rectangle 18580" width="8"
                                                    height="2" transform="translate(108 5287)" fill="#fff" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="ml-lg-3">
                                <h5 class="fs-36 fw-400 text-white mb-3">{{ translate(get_setting('cupon_title')) }}</h5>
                                <h5 class="fs-20 fw-400 text-gray">{{ translate(get_setting('cupon_subtitle')) }}</h5>
                                <div class="mt-5 pt-5">
                                    <a href="{{ route('coupons.all') }}"
                                        class="btn text-white hov-bg-white hov-text-dark border border-width-2 fs-16 px-5"
                                        style="border-radius: 28px;background: rgba(255, 255, 255, 0.2);box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.16);">{{ translate('View All Coupons') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute right-0 bottom-0 h-100">
                        <img class="img-fit h-100" src="{{ uploaded_asset(get_setting('coupon_background_image', null, $lang)) }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/coupon.svg') }}';"
                            alt="{{ env('APP_NAME') }} promo">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Category wise Products -->
    <div id="section_home_categories" style="background: #f5f5fa;">

    </div>

    @if (addon_is_activated('preorder'))
        <!-- Newest Preorder Products -->
        @include('preorder.frontend.home_page.newest_preorder')
    @endif

    <!-- Classified Product -->
    @if (get_setting('classified_product') == 1)
        @php
            $classified_products = get_home_page_classified_products(6);
        @endphp
        @if (count($classified_products) > 0)
            <section class="mb-2 mb-md-3 mt-3 mt-md-5">
                <div class="">
                    <!-- Top Section -->
                    <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                        <!-- Title -->
                        <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                            <span class="">{{ translate('Classified Ads') }}</span>
                        </h3>
                        <!-- Links -->
                        <div class="d-flex">
                            <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                href="{{ route('customer.products') }}">{{ translate('View All Products') }}</a>
                        </div>
                    </div>
                    <!-- Banner -->
                    @php
                        $classifiedBannerImage = get_setting('classified_banner_image', null, $lang);
                        $classifiedBannerImageSmall = get_setting('classified_banner_image_small', null, $lang);
                    @endphp
                    @if ($classifiedBannerImage != null || $classifiedBannerImageSmall != null)
                        <div class="mb-3 overflow-hidden hov-scale-img d-none d-md-block">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($classifiedBannerImage) }}"
                                alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                        <div class="mb-3 overflow-hidden hov-scale-img d-md-none">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ $classifiedBannerImageSmall != null ? uploaded_asset($classifiedBannerImageSmall) : uploaded_asset($classifiedBannerImage) }}"
                                alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                    @endif
                    <!-- Products Section -->
                    <div class="bg-white pt-3">
                        <div class="row no-gutters border-top border-left">
                            @foreach ($classified_products as $key => $classified_product)
                                <div
                                    class="col-xl-4 col-md-6 border-right border-bottom has-transition hov-shadow-out z-1">
                                    <div class="aiz-card-box p-2 has-transition bg-white">
                                        <div class="row hov-scale-img">
                                            <div class="col-4 col-md-5 mb-3 mb-md-0">
                                                <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                    class="d-block overflow-hidden h-auto h-md-150px text-center">
                                                    <img class="img-fluid lazyload mx-auto has-transition"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ isset($classified_product->thumbnail->file_name) ? my_asset($classified_product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                        alt="{{ $classified_product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                            </div>
                                            <div class="col">
                                                <h3
                                                    class="fw-400 fs-14 text-dark text-truncate-2 lh-1-4 mb-3 h-35px d-none d-sm-block">
                                                    <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                        class="d-block text-reset hov-text-primary">{{ $classified_product->getTranslation('name') }}</a>
                                                </h3>
                                                <div class="fs-14 mb-3">
                                                    <span
                                                        class="text-secondary">{{ $classified_product->user ? $classified_product->user->name : '' }}</span><br>
                                                    <span
                                                        class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                                </div>
                                                @if ($classified_product->conditon == 'new')
                                                    <span
                                                        class="badge badge-inline badge-soft-info fs-13 fw-700 px-3 py-2 text-info"
                                                        style="border-radius: 20px;">{{ translate('New') }}</span>
                                                @elseif($classified_product->conditon == 'used')
                                                    <span
                                                        class="badge badge-inline badge-soft-secondary-base fs-13 fw-700 px-3 py-2 text-danger"
                                                        style="border-radius: 20px;">{{ translate('Used') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- Top Sellers -->
    @if (get_setting('vendor_system_activation') == 1)
        @php
            $best_selers = get_best_sellers(10);
        @endphp
        @if (count($best_selers) > 0)
        <section class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="">
                <!-- Top Section -->
                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                        <span class="pb-3">{{ translate('Top Sellers') }}</span>
                    </h3>
                    <!-- Links -->
                    <div class="d-flex">
                        <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                            href="{{ route('sellers') }}">{{ translate('View All Sellers') }}</a>
                    </div>
                </div>
                <!-- Sellers Section -->
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="5" data-xxl-items="5"
                    data-xl-items="4" data-lg-items="3.4" data-md-items="2.5" data-sm-items="2" data-xs-items="1.4"
                    data-arrows="true" data-dots="false">
                    @foreach ($best_selers as $key => $seller)
                        @if ($seller->user != null)
                            <div
                                class="carousel-box h-100 position-relative text-center border-right border-top border-bottom @if ($key == 0) border-left @endif has-transition hov-animate-outline">
                                <div class="position-relative px-3" style="padding-top: 2rem; padding-bottom:2rem;">
                                    <!-- Shop logo & Verification Status -->
                                    <div class="mx-auto size-100px size-md-120px">
                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                            class="d-flex mx-auto justify-content-center align-item-center size-100px size-md-120px border overflow-hidden hov-scale-img"
                                            tabindex="0"
                                            style="border: 1px solid #e5e5e5; border-radius: 50%; box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);">
                                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                data-src="{{ uploaded_asset($seller->logo) }}" alt="{{ $seller->name }}"
                                                class="img-fit lazyload has-transition"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </a>
                                    </div>
                                    <!-- Shop name -->
                                    <h2 class="fs-14 fw-700 text-dark text-truncate-2 h-40px mt-3 mt-md-4 mb-0 mb-md-3">
                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                            class="text-reset hov-text-primary" tabindex="0">{{ $seller->name }}</a>
                                    </h2>
                                    <!-- Shop Rating -->
                                    <div class="rating rating-mr-2 text-dark mb-3">
                                        {{ renderStarRating($seller->rating) }}
                                        <span class="opacity-60 fs-14">({{ $seller->num_of_reviews }}
                                            {{ translate('Reviews') }})</span>
                                    </div>
                                    <!-- Visit Button -->
                                    <a href="{{ route('shop.visit', $seller->slug) }}" class="btn-visit">
                                        <span class="circle" aria-hidden="true">
                                            <span class="icon arrow"></span>
                                        </span>
                                        <span class="button-text">{{ translate('Visit Store') }}</span>
                                    </a>
                                    @if ($seller->verification_status == 1)
                                        <span class="absolute-top-right mr-2rem">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="31.999" height="48.001" viewBox="0 0 31.999 48.001">
                                                <g id="Group_25062" data-name="Group 25062" transform="translate(-532 -1033.999)">
                                                <path id="Union_3" data-name="Union 3" d="M1937,12304h16v14Zm-16,0h16l-16,14Zm0,0v-34h32v34Z" transform="translate(-1389 -11236)" fill="#85b567"/>
                                                <path id="Union_5" data-name="Union 5" d="M1921,12280a10,10,0,1,1,10,10A10,10,0,0,1,1921,12280Zm1,0a9,9,0,1,0,9-9A9.011,9.011,0,0,0,1922,12280Zm1,0a8,8,0,1,1,8,8A8.009,8.009,0,0,1,1923,12280Zm4.26-1.033a.891.891,0,0,0-.262.636.877.877,0,0,0,.262.632l2.551,2.551a.9.9,0,0,0,.635.266.894.894,0,0,0,.639-.266l4.247-4.244a.9.9,0,0,0-.639-1.542.893.893,0,0,0-.635.266l-3.612,3.608-1.912-1.906a.89.89,0,0,0-1.274,0Z" transform="translate(-1383 -11226)" fill="#fff"/>
                                                </g>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    @endif



@endsection

