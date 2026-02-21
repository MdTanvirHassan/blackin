@extends('frontend.layouts.app')

@section('content')
    <style>
        #section_featured .slick-slider .slick-list{
            background: #fff;
        }
        #flash_deal .slick-slider .slick-list .slick-slide,
        #section_featured .slick-slider .slick-list .slick-slide,
        #section_best_selling .slick-slider .slick-list .slick-slide,
        #section_newest .slick-slider .slick-list .slick-slide {
            margin-bottom: -5px;
        }
        .slider-card-section{
            filter: drop-shadow(0px 10px 30px rgba(0, 0, 0, 0.16));
        }
        .featured-category-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .featured-category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .featured-category-image {
            transition: transform 0.5s ease;
        }
        .featured-category-card:hover .featured-category-image {
            transform: scale(1.05);
        }
        .featured-category-card .btn {
            transition: all 0.3s ease;
        }
        .featured-category-card .btn:hover {
            background-color: #000 !important;
            color: #fff !important;
            border-color: #000 !important;
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

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-80px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(80px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInBottom {
            from {
                opacity: 0;
                transform: translateY(80px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInTop {
            from {
                opacity: 0;
                transform: translateY(-80px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .featured-categories-title {
            animation: titleFade 1s ease-out, titlePulse 3s ease-in-out infinite;
            display: inline-block;
        }

        /* Home Slider Animation */
        .home-slider .carousel-box {
            animation: scaleIn 0.8s ease-out;
        }

        .home-slider img {
            transition: transform 0.5s ease;
        }

        .home-slider .carousel-box:hover img {
            transform: scale(1.05);
        }

        /* Featured & Bundle Categories Animation */
        .featured-category-card,
        .carousel-box {
            animation: fadeUp 0.8s ease-out;
            animation-fill-mode: both;
        }

        .featured-category-card:nth-child(1),
        .carousel-box:nth-child(1) {
            animation-delay: 0.1s;
        }

        .featured-category-card:nth-child(2),
        .carousel-box:nth-child(2) {
            animation-delay: 0.2s;
        }

        .featured-category-card:nth-child(3),
        .carousel-box:nth-child(3) {
            animation-delay: 0.3s;
        }

        .featured-category-card:nth-child(4),
        .carousel-box:nth-child(4) {
            animation-delay: 0.4s;
        }

        .featured-category-card:nth-child(5),
        .carousel-box:nth-child(5) {
            animation-delay: 0.5s;
        }

        .featured-category-card:nth-child(6),
        .carousel-box:nth-child(6) {
            animation-delay: 0.6s;
        }

        /* Banner Hover Effect */
        .banner-hover-effect {
            overflow: hidden;
            position: relative;
        }

        .banner-hover-effect img {
            transition: transform 0.5s ease;
        }

        .banner-hover-effect:hover img {
            transform: scale(1.1);
        }

        .banner-hover-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shine 2s infinite;
            z-index: 1;
        }

        /* Stagger Animation for Sections */
        .section-animate {
            animation: fadeUp 0.8s ease-out;
        }

        .section-card-gradient {
            position: relative;
            overflow: hidden;
        }

        .section-card-gradient::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .section-card-gradient:hover::after {
            left: 100%;
        }

        @media (max-width: 991px){
            #flash_deal .slick-slider .slick-list .slick-slide{
                margin-bottom: 0px;
            }
            .slider-card-section .container{
                min-width: auto !important;
            }
            .featured-category-image {
                height: 300px !important;
            }
        }
        @media (max-width: 575px){
            .featured-category-image {
                height: 250px !important;
            }
            .featured-category-card h3 {
                font-size: 1.25rem !important;
            }
        }
        @media (max-width: 575px){
            #section_featured .slick-slider .slick-list .slick-slide {
                margin-bottom: -4px;
            }
        }
        @media (min-width: 576px){
            #section_featured .sm-gutters-15 {
                margin-left: -16px;
            }
        }
        @media (min-width: 768px){
            .slider-card-section{
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                z-index: 1;
            }
        }

        /* Scroll Animation - Bottom to Top */
        .scroll-animate-section {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .scroll-animate-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .scroll-animate-section.is-visible > * {
            animation: slideUpContent 0.6s ease-out;
        }

        /* Featured Category Directional Animations */
        .featured-category-card {
            opacity: 0;
            transform: translateX(-80px);
            transition: none;
        }

        .scroll-animate-section.is-visible .featured-category-card {
            opacity: 1;
            transform: translateX(0);
        }

        /* Left card - slide from left */
        .scroll-animate-section.is-visible .featured-category-card:nth-child(1) {
            animation: slideInLeft 0.8s ease-out forwards;
        }

        /* Middle card - slide from bottom */
        .scroll-animate-section.is-visible .featured-category-card:nth-child(2) {
            animation: slideInBottom 0.8s ease-out 0.2s forwards;
        }

        /* Right card - slide from right */
        .scroll-animate-section.is-visible .featured-category-card:nth-child(3) {
            animation: slideInRight 0.8s ease-out 0.4s forwards;
        }

        /* Additional cards pattern repeat */
        .scroll-animate-section.is-visible .featured-category-card:nth-child(4) {
            animation: slideInLeft 0.8s ease-out 0.1s forwards;
        }

        .scroll-animate-section.is-visible .featured-category-card:nth-child(5) {
            animation: slideInBottom 0.8s ease-out 0.3s forwards;
        }

        .scroll-animate-section.is-visible .featured-category-card:nth-child(6) {
            animation: slideInRight 0.8s ease-out 0.5s forwards;
        }

        @keyframes slideUpContent {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stagger child elements */
        .scroll-animate-section.is-visible > *:nth-child(1) {
            animation-delay: 0.1s;
        }
        .scroll-animate-section.is-visible > *:nth-child(2) {
            animation-delay: 0.2s;
        }
        .scroll-animate-section.is-visible > *:nth-child(3) {
            animation-delay: 0.3s;
        }
        .scroll-animate-section.is-visible > *:nth-child(4) {
            animation-delay: 0.4s;
        }
        .scroll-animate-section.is-visible > *:nth-child(5) {
            animation-delay: 0.5s;
        }

        /* Modern Section Header Design */
        .modern-section-header {
            position: relative;
            padding: 3rem 0 2rem;
            text-align: center;
        }

        .modern-section-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #000 0%, #666 100%);
            border-radius: 2px;
        }

        .section-header-title {
            font-size: clamp(24px, 4vw, 42px);
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #111;
            margin: 0;
            position: relative;
            display: inline-block;
        }

        .section-header-subtitle {
            font-size: 14px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #888;
            margin-top: 0.5rem;
            font-weight: 400;
        }

        .section-header-line {
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent 0%, #ddd 50%, transparent 100%);
            margin-top: 1.5rem;
        }

        /* Decorative Elements */
        .section-header-accent {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #000;
            border-radius: 50%;
            margin: 0 15px;
            vertical-align: middle;
            animation: pulse 2s infinite;
        }

        @media (max-width: 768px) {
            .modern-section-header {
                padding: 2rem 0 1.5rem;
            }
            .section-header-accent {
                width: 6px;
                height: 6px;
                margin: 0 10px;
            }
        }
        
        /* Mobile Home Slider - Cover Image */
        @media (max-width: 767px) {
            .home-slider .carousel-box img {
                object-fit: cover !important;
                width: 100% !important;
            }
            
            .home-slider .carousel-box > a > div {
                height: 50vh !important;
                min-height: 400px !important;
            }
            
            /* Reduce gap between home banner and featured categories */
            .home-banner-area {
                margin-bottom: 0.5rem !important;
            }
            
            .scroll-animate-section:first-of-type {
                margin-top: 0.5rem !important;
            }
        }
    </style>

    <!-- Sliders -->
    <div class="home-banner-area mb-3 section-animate">
        <div class="p-0 position-relative">
            <!-- Sliders -->
            <div class="home-slider slider-full">
                @if (get_setting('home_slider_images', null, $lang) != null)
                    <div class="aiz-carousel dots-inside-bottom mobile-img-auto-height" data-autoplay="true" data-infinite="true" data-fade="true" data-autoplay-speed="3000">
                        @php
                            $decoded_slider_images = json_decode(get_setting('home_slider_images', null, $lang), true);
                            $sliders = get_slider_images($decoded_slider_images);
                            $home_slider_links = get_setting('home_slider_links', null, $lang);
                        @endphp
                        @foreach ($sliders as $key => $slider)
                            <div class="carousel-box">
                                <a href="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}" class="banner-hover-effect d-block">
                                    <!-- Image -->
                                    <div class="d-block mw-100 img-fit overflow-hidden h-180px h-md-320px h-lg-460px h-xl-553px h-xxl-800px overflow-hidden">
                                        <img class="img-fit h-100 m-auto has-transition ls-is-cached lazyloaded"
                                        src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        alt="{{ env('APP_NAME') }} promo"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="slider-card-section">
                <div class="pt-4 pb-lg-4">
                    <div class="container">
                        <div class="w-100 px-3 px-lg-0">
                            <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                                data-items="3" data-xxl-items="3"
                                data-xl-items="3" data-lg-items="2"
                                data-md-items="2" data-sm-items="1.5" data-xs-items="1" data-arrows="true"
                                data-dots="false">
                                <!-- New Products -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Static Header for Featured Categories & Section Card -->
    <section class="mb-2 mb-md-3 mt-2 mt-md-3 section-animate scroll-animate-section">
        <div class="container">
            <div class="rounded shadow-sm p-4 mb-4 section-card-gradient" style="background: linear-gradient(135deg, #f8fafc 80%, #e0e7ef 100%);">
                <div class="modern-section-header">
                    <h2 class="section-header-title featured-categories-title">
                        <span class="section-header-accent"></span>
                        Featured Categories
                        <span class="section-header-accent"></span>
                    </h2>
                    <p class="section-header-subtitle">Discover Our Premium Collections</p>
                    <div class="section-header-line"></div>
                </div>
                <!-- Featured Categories -->
                @if (count($featured_categories) > 0)
                    <div class="row g-3 g-md-4">
                        @foreach ($featured_categories as $key => $category)
                            @php
                                $category_name = $category->getTranslation('name');
                            @endphp
                            <div class="col-12 col-md-4">
                                <div class="featured-category-card bg-white border overflow-hidden h-100" style="border: 1px solid #e0e0e0 !important;">
                                    <!-- Image Section -->
                                    <div class="featured-category-image overflow-hidden" style="height: 400px;">
                                        <img src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                            alt="{{ $category_name }}"
                                            class="w-100 h-100" style="object-fit: cover;"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </div>
                                    <!-- Content Section -->
                                    <div class="p-4 text-center">
                                        <h3 class="fw-700 text-uppercase mb-3" style="font-size: 1.5rem; letter-spacing: 1px; color: #000;">
                                            {{ $category_name }}
                                        </h3>
                                        <a href="{{ route('products.category', $category->slug) }}"
                                            class="btn btn-outline-dark px-4 py-2 fw-600 text-uppercase"
                                            style="border-radius: 4px; font-size: 0.9rem; letter-spacing: 1px; border-width: 2px;">
                                            VIEW COLLECTION
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Section Card for Bundle Product Categories -->
    <section class="mb-2 mb-md-3 mt-2 mt-md-3 section-animate scroll-animate-section" style="animation-delay: 0.2s;">
        <div class="container">
            <div class="rounded shadow-sm p-4 mb-4 section-card-gradient" style="background: linear-gradient(135deg, #f3f4f6 80%, #e0e7ef 100%);">
                <div class="modern-section-header">
                    <h2 class="section-header-title featured-categories-title">
                        <span class="section-header-accent"></span>
                        Bundle Product Categories
                        <span class="section-header-accent"></span>
                    </h2>
                    <p class="section-header-subtitle">Curated Bundles For Every Need</p>
                    <div class="section-header-line"></div>
                </div>
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
                    <div class="row g-3 g-md-4">
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
                            <div class="col-12 col-md-4">
                                <div class="featured-category-card bg-white border overflow-hidden h-100" style="border: 1px solid #e0e0e0 !important;">
                                    <!-- Image Section -->
                                    <div class="featured-category-image overflow-hidden" style="height: 400px;">
                                        <img src="{{ $cover_image }}"
                                            alt="{{ $category_name }}"
                                            class="w-100 h-100" style="object-fit: cover;"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </div>
                                    <!-- Content Section -->
                                    <div class="p-4 text-center">
                                        <h3 class="fw-700 text-uppercase mb-3" style="font-size: 1.5rem; letter-spacing: 1px; color: #000;">
                                            {{ $category_name }}
                                        </h3>
                                        <a href="{{ route('group_products.category', ['category_slug' => $category_slug]) }}"
                                            class="btn btn-outline-dark px-4 py-2 fw-600 text-uppercase"
                                            style="border-radius: 4px; font-size: 0.9rem; letter-spacing: 1px; border-width: 2px;">
                                            VIEW COLLECTION
                                        </a>
                                        @if($category->childrenCategories && $category->childrenCategories->count() > 0)
                                            <div class="mt-3 d-flex flex-wrap justify-content-center">
                                                @foreach ($category->childrenCategories->take(6) as $key => $child_category)
                                                    @php
                                                        $child_category_slug = $child_category->slug ?? \Illuminate\Support\Str::slug($child_category->getTranslation('name'));
                                                    @endphp
                                                    <a href="{{ route('group_products.category', ['category_slug' => $child_category_slug]) }}" class="fs-13 fw-300 text-dark hov-text-dark pr-3 pt-1" style="text-decoration: underline;">
                                                        {{ $child_category->getTranslation('name') }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Banner section 1 -->
    @php $homeBanner1Images = get_setting('home_banner1_images', null, $lang);   @endphp
    @if ($homeBanner1Images != null)
        <div class="pb-2 pb-md-3 pt-2 pt-md-3 section-animate scroll-animate-section" style="animation-delay: 0.3s;">
            <div class="container mb-2 mb-md-3">
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
                            <div class="carousel-box overflow-hidden hov-scale-img banner-hover-effect">
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



    <!-- Banner Section 2 -->
    @php $homeBanner2Images = get_setting('home_banner2_images', null, $lang);   @endphp
    @if ($homeBanner2Images != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3 section-animate scroll-animate-section" style="animation-delay: 0.4s;">
            <div class="container">
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
                        <div class="carousel-box overflow-hidden hov-scale-img banner-hover-effect">
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


    <!-- Banner Section 3 -->
    @php $homeBanner3Images = get_setting('home_banner3_images', null, $lang);   @endphp
    @if (get_setting('home_banner3_images') != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3 section-animate scroll-animate-section" style="animation-delay: 0.5s;">
            <div class="container">
                @php
                    $banner_3_imags = json_decode(get_setting('home_banner3_images', null, $lang));
                    $data_md = count($banner_3_imags) >= 2 ? 2 : 1;
                    $home_banner3_links = get_setting('home_banner3_links', null, $lang);
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_3_imags) }}" data-xxl-items="{{ count($banner_3_imags) }}"
                    data-xl-items="{{ count($banner_3_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_3_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img banner-hover-effect">
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




        



    <!-- Cupon -->
    @if (get_setting('coupon_system') == 1)
        <div class="mt-2 mt-md-3 section-animate scroll-animate-section" style="animation-delay: 0.6s;">
            <div class="container">
                <div class="position-relative py-5 px-3 px-sm-4 px-lg-5 banner-hover-effect" style="background-color: {{ get_setting('cupon_background_color', '#292933') }}">
                    <div class="text-center text-xl-left position-relative z-5">
                        <div class="d-lg-flex justify-content-lg-between">
                            <div class="order-lg-1 mb-3 mb-lg-0">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="206.12" height="175.997" viewBox="0 0 206.12 175.997">
                                    <defs>
                                      <clipPath id="clip-path">
                                        <path id="Union_10" data-name="Union 10" d="M-.008,77.361l142.979-.327-22.578.051.176-77.132L143.148-.1l-.177,77.132-.064,28.218L-.072,105.58Z" transform="translate(0 0)" fill="none" stroke="#fff" stroke-width="2"/>
                                      </clipPath>
                                    </defs>
                                    <g id="Group_24326" data-name="Group 24326" transform="translate(-274.202 -5254.612)" opacity="0.5">
                                      <g id="Mask_Group_23" data-name="Mask Group 23" transform="translate(304.445 5355.902) rotate(-45)" clip-path="url(#clip-path)">
                                        <g id="Group_24322" data-name="Group 24322" transform="translate(7.681 5.856)">
                                          <g id="Subtraction_167" data-name="Subtraction 167" transform="translate(0 0)" fill="none">
                                            <path d="M127.451,90.3H8a8.009,8.009,0,0,1-8-8V60.2a14.953,14.953,0,0,0,10.642-4.408A14.951,14.951,0,0,0,15.05,45.15a14.953,14.953,0,0,0-4.408-10.643A14.951,14.951,0,0,0,0,30.1V8A8.009,8.009,0,0,1,8,0H127.451a8.009,8.009,0,0,1,8,8V29.79a15.05,15.05,0,1,0,0,30.1V82.3A8.009,8.009,0,0,1,127.451,90.3Z" stroke="none"/>
                                            <path d="M 127.450813293457 88.30060577392578 C 130.75927734375 88.30060577392578 133.4509124755859 85.60896301269531 133.4509124755859 82.30050659179688 L 133.4508972167969 61.77521514892578 C 129.6533966064453 61.33430480957031 126.1383361816406 59.64068222045898 123.394172668457 56.89652252197266 C 120.1737594604492 53.67610168457031 118.4001998901367 49.39426422119141 118.4001998901367 44.83980178833008 C 118.4001998901367 40.28572463989258 120.1737747192383 36.0041618347168 123.3942184448242 32.78384399414062 C 126.1376495361328 30.04052734375 129.6527099609375 28.34706115722656 133.4509124755859 27.9056282043457 L 133.4509124755859 8.000102996826172 C 133.4509124755859 4.691642761230469 130.75927734375 2.000002861022949 127.450813293457 2.000002861022949 L 8.000096321105957 2.000002861022949 C 4.691636085510254 2.000002861022949 1.999996185302734 4.691642761230469 1.999996185302734 8.000102996826172 L 1.999996185302734 28.21491050720215 C 5.797210216522217 28.65582466125488 9.31190013885498 30.34944725036621 12.05595588684082 33.09362411499023 C 15.27627658843994 36.31408309936523 17.04979705810547 40.59588241577148 17.04979705810547 45.15030288696289 C 17.04979705810547 49.70434188842773 15.27627658843994 53.98588180541992 12.05591583251953 57.20624160766602 C 9.312583923339844 59.94955825805664 5.797909259796143 61.64302062988281 1.999996185302734 62.08445739746094 L 1.999996185302734 82.30050659179688 C 1.999996185302734 85.60896301269531 4.691636085510254 88.30060577392578 8.000096321105957 88.30060577392578 L 127.450813293457 88.30060577392578 M 127.450813293457 90.30060577392578 L 8.000096321105957 90.30060577392578 C 3.588836193084717 90.30060577392578 -3.762207143154228e-06 86.71176147460938 -3.762207143154228e-06 82.30050659179688 L -3.762207143154228e-06 60.20010375976562 C 4.022176265716553 60.19910430908203 7.799756050109863 58.63396453857422 10.64171600341797 55.79202270507812 C 13.48431587219238 52.94942474365234 15.04979610443115 49.17012405395508 15.04979610443115 45.15030288696289 C 15.04979610443115 41.13010406494141 13.48431587219238 37.35052108764648 10.64171600341797 34.5078010559082 C 7.799176216125488 31.66514205932617 4.019876003265381 30.0996036529541 -3.762207143154228e-06 30.0996036529541 L -3.762207143154228e-06 8.000102996826172 C -3.762207143154228e-06 3.588842868804932 3.588836193084717 2.886962874981691e-06 8.000096321105957 2.886962874981691e-06 L 127.450813293457 2.886962874981691e-06 C 131.8620758056641 2.886962874981691e-06 135.4509124755859 3.588842868804932 135.4509124755859 8.000102996826172 L 135.4509124755859 29.79000282287598 C 131.4283294677734 29.79100227355957 127.6504745483398 31.35614204406738 124.8083953857422 34.19808197021484 C 121.9657363891602 37.04064178466797 120.4001998901367 40.81994247436523 120.4001998901367 44.83980178833008 C 120.4001998901367 48.86006164550781 121.9657363891602 52.63964462280273 124.8083953857422 55.48230361938477 C 127.6510543823242 58.3249626159668 131.4306488037109 59.8905029296875 135.4508972167969 59.8905029296875 L 135.4509124755859 82.30050659179688 C 135.4509124755859 86.71176147460938 131.8620758056641 90.30060577392578 127.450813293457 90.30060577392578 Z" stroke="none" fill="#fff"/>
                                          </g>
                                        </g>
                                      </g>
                                      <g id="Group_24321" data-name="Group 24321" transform="translate(274.202 5357.276) rotate(-45)">
                                        <g id="Subtraction_167-2" data-name="Subtraction 167" transform="translate(0 0)" fill="none">
                                          <path d="M127.451,90.3H8a8.009,8.009,0,0,1-8-8V60.2a14.953,14.953,0,0,0,10.642-4.408A14.951,14.951,0,0,0,15.05,45.15a14.953,14.953,0,0,0-4.408-10.643A14.951,14.951,0,0,0,0,30.1V8A8.009,8.009,0,0,1,8,0H127.451a8.009,8.009,0,0,1,8,8V29.79a15.05,15.05,0,1,0,0,30.1V82.3A8.009,8.009,0,0,1,127.451,90.3Z" stroke="none"/>
                                          <path d="M 127.450813293457 88.30060577392578 C 130.75927734375 88.30060577392578 133.4509124755859 85.60896301269531 133.4509124755859 82.30050659179688 L 133.4508972167969 61.77521514892578 C 129.6533966064453 61.33430480957031 126.1383361816406 59.64068222045898 123.394172668457 56.89652252197266 C 120.1737594604492 53.67610168457031 118.4001998901367 49.39426422119141 118.4001998901367 44.83980178833008 C 118.4001998901367 40.28572463989258 120.1737747192383 36.0041618347168 123.3942184448242 32.78384399414062 C 126.1376495361328 30.04052734375 129.6527099609375 28.34706115722656 133.4509124755859 27.9056282043457 L 133.4509124755859 8.000102996826172 C 133.4509124755859 4.691642761230469 130.75927734375 2.000002861022949 127.450813293457 2.000002861022949 L 8.000096321105957 2.000002861022949 C 4.691636085510254 2.000002861022949 1.999996185302734 4.691642761230469 1.999996185302734 8.000102996826172 L 1.999996185302734 28.21491050720215 C 5.797210216522217 28.65582466125488 9.31190013885498 30.34944725036621 12.05595588684082 33.09362411499023 C 15.27627658843994 36.31408309936523 17.04979705810547 40.59588241577148 17.04979705810547 45.15030288696289 C 17.04979705810547 49.70434188842773 15.27627658843994 53.98588180541992 12.05591583251953 57.20624160766602 C 9.312583923339844 59.94955825805664 5.797909259796143 61.64302062988281 1.999996185302734 62.08445739746094 L 1.999996185302734 82.30050659179688 C 1.999996185302734 85.60896301269531 4.691636085510254 88.30060577392578 8.000096321105957 88.30060577392578 L 127.450813293457 88.30060577392578 M 127.450813293457 90.30060577392578 L 8.000096321105957 90.30060577392578 C 3.588836193084717 90.30060577392578 -3.762207143154228e-06 86.71176147460938 -3.762207143154228e-06 82.30050659179688 L -3.762207143154228e-06 60.20010375976562 C 4.022176265716553 60.19910430908203 7.799756050109863 58.63396453857422 10.64171600341797 55.79202270507812 C 13.48431587219238 52.94942474365234 15.04979610443115 49.17012405395508 15.04979610443115 45.15030288696289 C 15.04979610443115 41.13010406494141 13.48431587219238 37.35052108764648 10.64171600341797 34.5078010559082 C 7.799176216125488 31.66514205932617 4.019876003265381 30.0996036529541 -3.762207143154228e-06 30.0996036529541 L -3.762207143154228e-06 8.000102996826172 C -3.762207143154228e-06 3.588842868804932 3.588836193084717 2.886962874981691e-06 8.000096321105957 2.886962874981691e-06 L 127.450813293457 2.886962874981691e-06 C 131.8620758056641 2.886962874981691e-06 135.4509124755859 3.588842868804932 135.4509124755859 8.000102996826172 L 135.4509124755859 29.79000282287598 C 131.4283294677734 29.79100227355957 127.6504745483398 31.35614204406738 124.8083953857422 34.19808197021484 C 121.9657363891602 37.04064178466797 120.4001998901367 40.81994247436523 120.4001998901367 44.83980178833008 C 120.4001998901367 48.86006164550781 121.9657363891602 52.63964462280273 124.8083953857422 55.48230361938477 C 127.6510543823242 58.3249626159668 131.4306488037109 59.8905029296875 135.4508972167969 59.8905029296875 L 135.4509124755859 82.30050659179688 C 135.4509124755859 86.71176147460938 131.8620758056641 90.30060577392578 127.450813293457 90.30060577392578 Z" stroke="none" fill="#fff"/>
                                        </g>
                                        <g id="Group_24325" data-name="Group 24325" transform="translate(26.233 43.075)">
                                          <path id="Path_41600" data-name="Path 41600" d="M.006.024,15.056-.01l-.009,3.763L0,3.787Z" transform="translate(22.575 0.058)" fill="#fff"/>
                                          <path id="Path_41601" data-name="Path 41601" d="M.006.024,15.056-.01l-.009,3.763L0,3.787Z" transform="translate(45.151 0.006)" fill="#fff"/>
                                          <path id="Path_41602" data-name="Path 41602" d="M.006.024,15.056-.01l-.009,3.763L0,3.787Z" transform="translate(67.725 -0.046)" fill="#fff"/>
                                          <path id="Path_41603" data-name="Path 41603" d="M.006.024,15.056-.01l-.009,3.763L0,3.787Z" transform="translate(0 0.11)" fill="#fff"/>
                                        </g>
                                      </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="">
                                <h5 class="fs-36 fw-400 text-white mb-3">{{ translate(get_setting('cupon_title')) }}</h5>
                                <h5 class="fs-20 fw-400 text-white">{{ translate(get_setting('cupon_subtitle')) }}</h5>
                                <div class="mt-5">
                                    <a href="{{ route('coupons.all') }}"
                                        class="btn text-white hov-bg-white hov-text-dark fs-16 px-5"
                                        style="border-radius: 28px;background: rgba(255, 255, 255, 0.2);box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.16);">{{ translate('View All Coupons') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



@endsection

@section('script')
<script>
    // Intersection Observer for scroll animations
    document.addEventListener('DOMContentLoaded', function() {
        const isMobile = window.innerWidth <= 767;
        const mobilePermanentSections = new Set();
        
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Add visible class when section enters viewport
                    entry.target.classList.add('is-visible');
                } else {
                    // Don't remove class from mobile permanent sections (Featured Categories)
                    if (!mobilePermanentSections.has(entry.target)) {
                        entry.target.classList.remove('is-visible');
                    }
                }
            });
        }, observerOptions);

        // Observe all scroll-animate-section elements
        const animatedSections = document.querySelectorAll('.scroll-animate-section');
        animatedSections.forEach((section, index) => {
            // On mobile, make first section (Featured Categories) always visible
            if (isMobile && index === 0) {
                // Immediately add visible class for Featured Categories on mobile and keep it permanent
                section.classList.add('is-visible');
                mobilePermanentSections.add(section);
            } else {
                // Desktop or other sections - check if in viewport
                const rect = section.getBoundingClientRect();
                const isInViewport = rect.top < window.innerHeight && rect.bottom > 0;
                
                if (isInViewport) {
                    section.classList.add('is-visible');
                }
            }
            
            observer.observe(section);
        });
    });
</script>
@endsection

