@php
    $topHeaderTextColor = get_setting('top_header_text_color');
    $middleHeaderTextColor = get_setting('middle_header_text_color');
    $bottomHeaderTextColor = get_setting('bottom_header_text_color');
@endphp
<!-- Link to External CSS -->
<link rel="stylesheet" href="{{ static_asset('assets/css/ascolour-dark-nav.css') }}">

<!-- ASColour Navigation - Replaces Default Header -->


<!-- Search Modal -->
<div class="ascolour-search-modal" id="searchModal" style="display: none;">
    <div class="ascolour-search-overlay" onclick="toggleSearch()"></div>
    <div class="ascolour-search-content">
        <button class="ascolour-search-close" onclick="toggleSearch()">&times;</button>
        <div class="ascolour-search-box">
            <form action="{{ route('search') }}" method="GET">
                <input type="text" name="keyword" placeholder="Search for products..." autocomplete="off" autofocus>
                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z" transform="translate(-1.854 -1.854)" fill="#fff"/>
                        <path d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z" transform="translate(-5.2 -5.2)" fill="#fff"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSearch() {
    const modal = document.getElementById('searchModal');
    if (modal.style.display === 'none' || modal.style.display === '') {
        modal.style.display = 'block';
        setTimeout(() => {
            modal.querySelector('input').focus();
        }, 100);
    } else {
        modal.style.display = 'none';
    }
}

// Close search on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('searchModal');
        if (modal.style.display === 'block') {
            toggleSearch();
        }
    }
});
</script>

<!-- Mega Menu Backdrop -->
<div class="ascolour-backdrop"></div>

<!-- ASColour Navigation Styles -->
<style>
    .ascolour-header-wrapper {
        position: relative;
        z-index: 10000 !important;
    }
    
    /* Top Banner */
    .ascolour-top-banner {
        background: #333;
        color: #b8860b;
        text-align: center;
        padding: 10px 20px;
        font-size: 12px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        position: relative;
    }
    
    .ascolour-top-banner .container {
        position: relative;
    }
    
    .banner-close {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        line-height: 1;
    }
    
    /* Main Navigation */
    .ascolour-dark-nav {
        background: #2b2b2b;
        border-bottom: 1px solid #404040;
        position: relative;
        z-index: 10000 !important;
    }
    
    .ascolour-nav-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .ascolour-logo {
        color: #fff;
        font-size: 18px;
        font-weight: 300;
        text-decoration: none;
        letter-spacing: 0.5px;
        padding: 16px 0;
    }
    
    .ascolour-logo:hover {
        color: #fff;
        text-decoration: none;
    }
    
    /* Center Navigation */
    .ascolour-main-nav {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        flex: 1;
        justify-content: center;
    }
    
    .ascolour-nav-item {
        position: relative;
    }
    
    .ascolour-nav-item > a {
        display: block;
        padding: 18px 20px;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 400;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        transition: color 0.2s ease;
        position: relative;
    }
    
    .ascolour-nav-item > a:hover,
    .ascolour-nav-item.active > a {
        color: #b8860b;
        text-decoration: none;
    }
    
    .ascolour-nav-item > a:hover::after,
    .ascolour-nav-item.active > a::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 20px;
        right: 20px;
        height: 2px;
        background: #b8860b;
    }
    
    /* Utility Navigation */
    .ascolour-utility-nav {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 24px;
    }
    
    .ascolour-utility-nav a {
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: color 0.2s ease;
    }
    
    .ascolour-utility-nav a:hover {
        color: #b8860b;
        text-decoration: none;
    }
    
    /* User Dropdown */
    .ascolour-user-dropdown {
        position: relative;
    }
    
    .ascolour-user-dropdown > a {
        cursor: pointer;
    }
    
    .ascolour-user-menu {
        position: absolute;
        top: 100%;
        right: 0;
        min-width: 220px;
        background: #fff;
        border: 1px solid #e5e5e5;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s ease;
        z-index: 10001 !important;
        margin-top: 10px;
    }
    
    .ascolour-user-dropdown:hover .ascolour-user-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .ascolour-user-menu ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .ascolour-user-menu li {
        border-bottom: 1px solid #f0f0f0;
    }
    
    .ascolour-user-menu li:last-child {
        border-bottom: none;
    }
    
    .ascolour-user-menu li.border-top {
        border-top: 1px solid #e5e5e5;
        margin-top: 5px;
        padding-top: 5px;
    }
    
    .ascolour-user-menu a {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        font-size: 14px;
        text-transform: none;
        letter-spacing: normal;
        transition: background 0.2s ease;
    }
    
    .ascolour-user-menu a:hover {
        background: #f8f9fa;
        color: #333;
    }
    
    .ascolour-user-menu a.text-danger:hover {
        background: #fff5f5;
        color: #d43533;
    }
    
    .ascolour-user-menu svg {
        margin-right: 10px;
        flex-shrink: 0;
    }
    
    /* Mega Menu */
    .ascolour-mega-menu {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(10px);
        min-width: 700px;
        max-width: 95vw;
        width: max-content;
        background: #2b2b2b;
        border: 1px solid #404040;
        border-top: 3px solid #b8860b;
        border-radius: 0 0 4px 4px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1), visibility 0s linear 0.3s;
        z-index: 9999 !important;
        margin-top: 0;
        pointer-events: none;
    }
    
    .ascolour-nav-item:hover .ascolour-mega-menu {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
        transition-delay: 0.15s, 0s;
        pointer-events: auto;
    }
    
    /* Keep menu open when hovering the menu itself */
    .ascolour-mega-menu:hover {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }
    
    .ascolour-mega-content {
        padding: 40px 30px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 35px;
        max-height: 500px;
        overflow-y: auto;
    }
    
    /* Custom Scrollbar for Mega Menu */
    .ascolour-mega-content::-webkit-scrollbar {
        width: 8px;
    }
    
    .ascolour-mega-content::-webkit-scrollbar-track {
        background: #1a1a1a;
        border-radius: 4px;
    }
    
    .ascolour-mega-content::-webkit-scrollbar-thumb {
        background: #404040;
        border-radius: 4px;
    }
    
    .ascolour-mega-content::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .ascolour-mega-column {
        min-width: 0;
    }
    
    .ascolour-column-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #fff;
        margin-bottom: 20px;
    }
    
    .ascolour-column-title a {
        color: #fff;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .ascolour-column-title a:hover {
        color: #b8860b;
    }
    
    .ascolour-column-title::after {
        content: '.';
        color: #b8860b;
    }
    
    .ascolour-column-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .ascolour-column-links li {
        margin-bottom: 10px;
    }
    
    .ascolour-column-links a {
        color: #999;
        text-decoration: none;
        font-size: 14px;
        display: block;
        padding: 4px 0;
        transition: all 0.2s ease;
    }
    
    .ascolour-column-links a:hover {
        color: #fff;
        padding-left: 8px;
        text-decoration: none;
    }
    
    .ascolour-column-links a.text-warning {
        color: #b8860b !important;
        font-weight: 600;
    }
    
    .ascolour-column-links a.text-warning:hover {
        color: #d4a574 !important;
        padding-left: 12px;
    }
    
    /* Backdrop */
    .ascolour-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        z-index: 9998 !important;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.4s ease, visibility 0.4s ease;
        pointer-events: none;
    }
    
    .ascolour-backdrop.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    
    /* Search Modal */
    .ascolour-search-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10002 !important;
    }
    
    .ascolour-search-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        backdrop-filter: blur(5px);
    }
    
    .ascolour-search-content {
        position: relative;
        max-width: 800px;
        margin: 100px auto;
        padding: 20px;
        z-index: 10003 !important;
    }
    
    .ascolour-search-close {
        position: absolute;
        top: -50px;
        right: 0;
        background: none;
        border: none;
        color: #fff;
        font-size: 40px;
        cursor: pointer;
        padding: 0;
        width: 40px;
        height: 40px;
        line-height: 1;
        opacity: 0.7;
        transition: opacity 0.2s ease;
    }
    
    .ascolour-search-close:hover {
        opacity: 1;
    }
    
    .ascolour-search-box {
        background: #fff;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .ascolour-search-box form {
        display: flex;
        align-items: center;
    }
    
    .ascolour-search-box input {
        flex: 1;
        padding: 20px 30px;
        border: none;
        font-size: 18px;
        outline: none;
        background: transparent;
    }
    
    .ascolour-search-box button {
        padding: 20px 30px;
        background: #2b2b2b;
        border: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    
    .ascolour-search-box button:hover {
        background: #1a1a1a;
    }
    
    .ascolour-search-box button svg {
        display: block;
    }
    
    /* Responsive */
    @media (max-width: 991px) {
        .ascolour-header-wrapper {
            display: none;
        }
    }
    
    @media (min-width: 992px) and (max-width: 1200px) {
        .ascolour-nav-item > a {
            padding: 18px 15px;
            font-size: 12px;
        }
        
        .ascolour-mega-menu {
            min-width: 600px;
            max-width: 90vw;
        }
        
        .ascolour-mega-content {
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 25px;
            padding: 30px 20px;
        }
    }
    
    @media (min-width: 1200px) and (max-width: 1400px) {
        .ascolour-mega-menu {
            max-width: 1100px;
        }
    }
    
    @media (min-width: 1400px) {
        .ascolour-mega-menu {
            max-width: 1200px;
        }
    }
    
    @media (max-width: 768px) {
        .ascolour-search-content {
            margin: 50px 20px;
            padding: 10px;
        }
        
        .ascolour-search-box input {
            padding: 15px 20px;
            font-size: 16px;
        }
        
        .ascolour-search-box button {
            padding: 15px 20px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.ascolour-nav-item');
    const backdrop = document.querySelector('.ascolour-backdrop');
    const bannerClose = document.querySelector('.banner-close');
    
    let backdropTimeout;
    let menuTimeout;
    
    // Mega menu hover with smooth transitions
    navItems.forEach(item => {
        const megaMenu = item.querySelector('.ascolour-mega-menu');
        
        if (megaMenu) {
            // Show mega menu on nav item hover
            item.addEventListener('mouseenter', function() {
                clearTimeout(menuTimeout);
                clearTimeout(backdropTimeout);
                
                // Small delay before showing backdrop
                backdropTimeout = setTimeout(() => {
                    backdrop.classList.add('show');
                }, 150);
            });
            
            // Hide mega menu when leaving nav item
            item.addEventListener('mouseleave', function(e) {
                // Only hide if not moving to the mega menu
                const rect = megaMenu.getBoundingClientRect();
                const buffer = 10; // 10px buffer zone
                
                if (e.clientY < rect.top - buffer || 
                    e.clientY > rect.bottom + buffer ||
                    e.clientX < rect.left - buffer ||
                    e.clientX > rect.right + buffer) {
                    
                    menuTimeout = setTimeout(() => {
                        clearTimeout(backdropTimeout);
                        backdrop.classList.remove('show');
                    }, 100);
                }
            });
            
            // Keep menu open when hovering the menu itself
            megaMenu.addEventListener('mouseenter', function() {
                clearTimeout(menuTimeout);
                clearTimeout(backdropTimeout);
                backdrop.classList.add('show');
            });
            
            megaMenu.addEventListener('mouseleave', function() {
                clearTimeout(menuTimeout);
                clearTimeout(backdropTimeout);
                
                menuTimeout = setTimeout(() => {
                    backdrop.classList.remove('show');
                }, 100);
            });
        }
    });
    
    // Close backdrop on click
    if (backdrop) {
        backdrop.addEventListener('click', function() {
            clearTimeout(backdropTimeout);
            clearTimeout(menuTimeout);
            this.classList.remove('show');
        });
    }
    
    // Close banner
    if (bannerClose) {
        bannerClose.addEventListener('click', function() {
            document.querySelector('.ascolour-top-banner').style.display = 'none';
        });
    }
});
</script>


<header
    class="@if (get_setting('header_stikcy') == 'on') sticky-top @endif z-1020 middle-background-color-visibility stikcy-header-visibility"
    style="background-color: {{ get_setting('middle_header_bg_color') }}">
    <!-- Search Bar -->
    <div class="position-relative logo-bar-area border-bottom border-md-nonea z-1025 d-md-none">
        <div class="container">
            <div class="d-flex align-items-center">
                <!-- top menu sidebar button -->
                <button type="button" class="btn d-lg-none mr-3 mr-sm-4 p-0 active" data-toggle="class-toggle"
                    data-target=".aiz-top-menu-sidebar">
                    <svg id="Component_43_1" data-name="Component 43 – 1" xmlns="http://www.w3.org/2000/svg" width="16"
                        height="16" viewBox="0 0 16 16">
                        <rect id="Rectangle_19062" data-name="Rectangle 19062" width="16" height="2"
                            transform="translate(0 7)" fill="#919199" />
                        <rect id="Rectangle_19063" data-name="Rectangle 19063" width="16" height="2" fill="#919199" />
                        <rect id="Rectangle_19064" data-name="Rectangle 19064" width="16" height="2"
                            transform="translate(0 14)" fill="#919199" />
                    </svg>

                </button>
                <!-- Header Logo -->
                <div class="col-auto pl-0 pr-3 d-flex align-items-center d-md-none">
                    <a class="d-block d-md-none py-20px mr-3 ml-0" href="{{ route('home') }}">
                        @php
                            $header_logo = get_setting('header_logo');
                        @endphp
                        @if ($header_logo != null)
                            <img id="header-logo-preview" src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-30px h-md-40px" height="40">
                        @else
                            <img id="header-logo-preview" src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                                class="mw-100 h-30px h-md-40px" height="40">
                        @endif
                    </a>
                </div>
                <!-- Search Icon for small device -->
                <div class="d-lg-none ml-auto mr-0">
                    <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle"
                        data-target=".front-header-search">
                        <i class="las la-search la-flip-horizontal la-2x"></i>
                    </a>
                </div>
                <!-- Search field -->
                <div class="flex-grow-1 front-header-search d-flex align-items-center bg-white mx-xl-5">
                    <div class="position-relative flex-grow-1 px-3 px-lg-0">
                        <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                            <div class="d-flex position-relative align-items-center">
                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                    <button class="btn px-2" type="button"><i
                                            class="la la-2x la-long-arrow-left"></i></button>
                                </div>
                                <div class="search-input-box">
                                    <input type="text"
                                        class="border border-soft-light form-control fs-14 hov-animate-outline"
                                        id="search" name="keyword" @isset($query) value="{{ $query }}" @endisset
                                        placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">

                                    <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                        width="20.001" height="20" viewBox="0 0 20.001 20">
                                        <path id="Path_3090" data-name="Path 3090"
                                            d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                            transform="translate(-1.854 -1.854)" fill="#b5b5bf" />
                                        <path id="Path_3091" data-name="Path 3091"
                                            d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                            transform="translate(-5.2 -5.2)" fill="#b5b5bf" />
                                    </svg>
                                </div>
                            </div>
                        </form>
                        <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                            style="min-height: 200px">
                            <div class="search-preloader absolute-top-center">
                                <div class="dot-loader">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="search-nothing d-none p-3 text-center fs-16">

                            </div>
                            <div id="search-content" class="text-left">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Search box -->
                <div class="d-none d-lg-none ml-3 mr-0">
                    <div class="nav-search-box">
                        <a href="#" class="nav-box-link">
                            <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                        </a>
                    </div>
                </div>

                @if (Auth::check() && auth()->user()->user_type == 'customer')
                    <!-- Compare -->
                    <div class="d-none d-lg-block ml-3 mr-0">
                        <div class="" id="compare">
                            @include('frontend.partials.compare')
                        </div>
                    </div>
                    <!-- Wishlist -->
                    <div class="d-none d-lg-block mr-3" style="margin-left: 36px;">
                        <div class="" id="wishlist">
                            @include('frontend.partials.wishlist')
                        </div>
                    </div>
                    <!-- Notifications -->
                    <ul class="list-inline mb-0 h-100 d-none d-xl-flex justify-content-end align-items-center">
                        <li class="list-inline-item ml-3 mr-3 pr-3 pl-0 dropdown">
                            <a class="dropdown-toggle no-arrow text-secondary fs-12" data-toggle="dropdown"
                                href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false"
                                onclick="nonLinkableNotificationRead()">
                                <span class="position-relative d-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14.668" height="16"
                                        viewBox="0 0 14.668 16">
                                        <path id="_26._Notification" data-name="26. Notification"
                                            d="M8.333,16A3.34,3.34,0,0,0,11,14.667H5.666A3.34,3.34,0,0,0,8.333,16ZM15.06,9.78a2.457,2.457,0,0,1-.727-1.747V6a6,6,0,1,0-12,0V8.033A2.457,2.457,0,0,1,1.606,9.78,2.083,2.083,0,0,0,3.08,13.333H13.586A2.083,2.083,0,0,0,15.06,9.78Z"
                                            transform="translate(-0.999)" fill="#91919b" />
                                    </svg>
                                    @if (Auth::check() && count($user->unreadNotifications) > 0)
                                        <span
                                            class="badge badge-primary badge-inline badge-pill absolute-top-right--10px unread-notification-count">{{ count($user->unreadNotifications) }}</span>
                                    @endif
                                </span>
                            </a>
                            @auth
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 rounded-0">
                                    <div class="p-3 bg-light border-bottom">
                                        <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                    </div>
                                    <div class="c-scrollbar-light overflow-auto" style="max-height:300px;">
                                        <ul class="list-group list-group-flush">
                                            @forelse($user->unreadNotifications as $notification)
                                                @php
                                                    $showNotification = true;
                                                    if (
                                                        $notification->type ==
                                                        'App\Notifications\PreorderNotification' &&
                                                        !addon_is_activated('preorder')
                                                    ) {
                                                        $showNotification = false;
                                                    }
                                                @endphp
                                                @if ($showNotification)
                                                    @php
                                                        $isLinkable = true;
                                                        $notificationType = get_notification_type(
                                                            $notification->notification_type_id,
                                                            'id',
                                                        );
                                                        $notifyContent = $notificationType->getTranslation(
                                                            'default_text',
                                                        );
                                                        $notificationShowDesign = get_setting(
                                                            'notification_show_type',
                                                        );
                                                        if (
                                                            $notification->type ==
                                                            'App\Notifications\customNotification' &&
                                                            $notification->data['link'] == null
                                                        ) {
                                                            $isLinkable = false;
                                                        }
                                                    @endphp
                                                    <li class="list-group-item">
                                                        <div class="d-flex">
                                                            @if ($notificationShowDesign != 'only_text')
                                                                <div class="size-35px mr-2">
                                                                    @php
                                                                        $notifyImageDesign = '';
                                                                        if ($notificationShowDesign == 'design_2') {
                                                                            $notifyImageDesign = 'rounded-1';
                                                                        } elseif (
                                                                            $notificationShowDesign == 'design_3'
                                                                        ) {
                                                                            $notifyImageDesign = 'rounded-circle';
                                                                        }
                                                                    @endphp
                                                                    <img src="{{ uploaded_asset($notificationType->image) }}"
                                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/notification.png') }}';"
                                                                        class="img-fit h-100 {{ $notifyImageDesign }}">
                                                                </div>
                                                            @endif
                                                            <div>
                                                                @if ($notification->type == 'App\Notifications\OrderNotification')
                                                                    @php
                                                                        $orderCode =
                                                                            $notification->data['order_code'];
                                                                        $route = route(
                                                                            'purchase_history.details',
                                                                            encrypt(
                                                                                $notification->data['order_id'],
                                                                            ),
                                                                        );
                                                                        $orderCode =
                                                                            "<span class='text-blue'>" .
                                                                            $orderCode .
                                                                            '</span>';
                                                                        $notifyContent = str_replace(
                                                                            '[[order_code]]',
                                                                            $orderCode,
                                                                            $notifyContent,
                                                                        );
                                                                    @endphp
                                                                @elseif($notification->type == 'App\Notifications\PreorderNotification')
                                                                    @php
                                                                        $orderCode =
                                                                            $notification->data['order_code'];
                                                                        $route = route(
                                                                            'preorder.order_details',
                                                                            encrypt(
                                                                                $notification->data['preorder_id'],
                                                                            ),
                                                                        );
                                                                        $orderCode =
                                                                            "<span class='text-blue'>" .
                                                                            $orderCode .
                                                                            '</span>';
                                                                        $notifyContent = str_replace(
                                                                            '[[order_code]]',
                                                                            $orderCode,
                                                                            $notifyContent,
                                                                        );
                                                                    @endphp
                                                                @endif

                                                                @if ($isLinkable = true)
                                                                    <a
                                                                        href="{{ route('notification.read-and-redirect', encrypt($notification->id)) }}">
                                                                @endif
                                                                    <span
                                                                        class="fs-12 text-dark text-truncate-2">{!! $notifyContent !!}</span>
                                                                    @if ($isLinkable = true)
                                                                        </a>
                                                                    @endif
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="py-4 text-center fs-16">
                                                        {{ translate('No notification found') }}
                                                    </div>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div class="text-center border-top">
                                        <a href="{{ route('customer.all-notifications') }}"
                                            class="text-secondary fs-12 d-block py-2">
                                            {{ translate('View All Notifications') }}
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </li>
                    </ul>
                @endif

                <div class="d-none d-xl-block ml-auto mr-0">
                    @auth
                        <span class="d-flex align-items-center nav-user-info py-20px @if (isAdmin()) ml-5 @endif"
                            id="nav-user-info">
                            <!-- Image -->
                            <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                                @if ($user->avatar_original != null)
                                    <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}" class="img-fit h-100" alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @else
                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @endif
                            </span>
                            <!-- Name -->
                            <h4 class="h5 fs-14 fw-700 ml-2 mb-0 middle-text-color-visibility" style="color: {{ $middleHeaderTextColor }}">{{ $user->name }}</h4>
                        </span>
                    @else
                        <!--Login & Registration -->
                        <span class="d-flex align-items-center nav-user-info ml-3">
                            <!-- Image -->
                            <span
                                class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img middle-text-color-visibility" style="color: {{ $middleHeaderTextColor }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012"
                                    viewBox="0 0 19.902 20.012">
                                    <path id="fe2df171891038b33e9624c27e96e367"
                                        d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                                        transform="translate(-2.064 -1.995)" fill="currentColor"/>
                                </svg>
                            </span>
                            <a href="{{ route('user.login') }}"
                                class="opacity-60 hov-opacity-100 fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3 middle-text-color-visibility" style="color: {{ $middleHeaderTextColor }}">{{ translate('Login') }}</a>
                            <a href="{{ route(get_setting('customer_registration_verify') === '1' ? 'registration.verification' : 'user.registration') }}"
                                {{-- <a href="{{ route('user.registration') }}" --}}
                                class="opacity-60 hov-opacity-100 fs-12 d-inline-block py-2 pl-2 middle-text-color-visibility" style="color: {{ $middleHeaderTextColor }}">{{ translate('Registration') }}</a>
                        </span>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Loged in user Menus -->
        <div class="hover-user-top-menu position-absolute top-100 left-0 right-0 z-3">
            <div class="container">
                <div class="position-static float-right">
                    <div class="aiz-user-top-menu bg-white rounded-0 border-top shadow-sm" style="width:220px;">
                        <ul class="list-unstyled no-scrollbar mb-0 text-left">
                            @if (isAdmin())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @else
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('dashboard') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                            <path id="Path_2916" data-name="Path 2916"
                                                d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                fill="#b5b5c0" />
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                    </a>
                                </li>
                            @endif

                            @if (isCustomer())
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                            <g id="Group_25261" data-name="Group 25261"
                                                transform="translate(-27.466 -542.963)">
                                                <path id="Path_2953" data-name="Path 2953"
                                                    d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                    transform="translate(22.966 537)" fill="#b5b5bf" />
                                                <path id="Path_2954" data-name="Path 2954"
                                                    d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                    transform="translate(27.466 536)" fill="#b5b5bf" />
                                                <path id="Path_2955" data-name="Path 2955"
                                                    d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 532)" fill="#b5b5bf" />
                                                <path id="Path_2956" data-name="Path 2956"
                                                    d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 529)" fill="#b5b5bf" />
                                                <path id="Path_2957" data-name="Path 2957"
                                                    d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 526)" fill="#b5b5bf" />
                                                <path id="Path_2958" data-name="Path 2958"
                                                    d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 531.5)" fill="#b5b5bf" />
                                                <path id="Path_2959" data-name="Path 2959"
                                                    d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 528.5)" fill="#b5b5bf" />
                                                <path id="Path_2960" data-name="Path 2960"
                                                    d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 525.5)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Purchase History') }}</span>
                                    </a>
                                </li>

                                @if (addon_is_activated('preorder'))
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('preorder.order_list') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.002"
                                                viewBox="0 0 16 16.002">
                                                <path id="Union_63" data-name="Union 63"
                                                    d="M14072,894a8,8,0,1,1,8,8A8.011,8.011,0,0,1,14072,894Zm1,0a7,7,0,1,0,7-7A7.007,7.007,0,0,0,14073,894Zm10.652,3.674-3.2-2.781a1,1,0,0,1-.953-1.756V889.5a.5.5,0,1,1,1,0v3.634a1,1,0,0,1,.5.863c0,.015,0,.029,0,.044l3.311,2.876a.5.5,0,0,1,.05.7.5.5,0,0,1-.708.049Z"
                                                    transform="translate(-14072 -885.998)" fill="#b5b5bf" />
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Preorder List') }}</span>
                                        </a>
                                    </li>
                                @endif

                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('digital_purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                            viewBox="0 0 16.001 16">
                                            <g id="Group_25262" data-name="Group 25262"
                                                transform="translate(-1388.154 -562.604)">
                                                <path id="Path_2963" data-name="Path 2963"
                                                    d="M77.864,98.69V92.1a.5.5,0,1,0-1,0V98.69l-1.437-1.437a.5.5,0,0,0-.707.707l1.851,1.852a1,1,0,0,0,.707.293h.172a1,1,0,0,0,.707-.293l1.851-1.852a.5.5,0,0,0-.7-.713Z"
                                                    transform="translate(1318.79 478.5)" fill="#b5b5bf" />
                                                <path id="Path_2964" data-name="Path 2964"
                                                    d="M67.155,88.6a3,3,0,0,1-.474-5.963q-.009-.089-.015-.179a5.5,5.5,0,0,1,10.977-.718,3.5,3.5,0,0,1-.989,6.859h-1.5a.5.5,0,0,1,0-1l1.5,0a2.5,2.5,0,0,0,.417-4.967.5.5,0,0,1-.417-.5,4.5,4.5,0,1,0-8.908.866.512.512,0,0,1,.009.121.5.5,0,0,1-.52.479,2,2,0,1,0-.162,4l.081,0h2a.5.5,0,0,1,0,1Z"
                                                    transform="translate(1324 486)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Downloads') }}</span>
                                    </a>
                                </li>
                                @if (get_setting('conversation_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('conversations.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                                <g id="Group_25263" data-name="Group 25263"
                                                    transform="translate(1053.151 256.688)">
                                                    <path id="Path_3012" data-name="Path 3012"
                                                        d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                                        transform="translate(-1178 -341)" fill="#b5b5bf" />
                                                    <path id="Path_3013" data-name="Path 3013"
                                                        d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                                        transform="translate(-1182 -337)" fill="#b5b5bf" />
                                                    <path id="Path_3014" data-name="Path 3014"
                                                        d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                        transform="translate(-1181 -343.5)" fill="#b5b5bf" />
                                                    <path id="Path_3015" data-name="Path 3015"
                                                        d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                                        transform="translate(-1181 -346.5)" fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Conversations') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (get_setting('wallet_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('wallet.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="16" height="16" viewBox="0 0 16 16">
                                                <defs>
                                                    <clipPath id="clip-path1">
                                                        <rect id="Rectangle_1386" data-name="Rectangle 1386" width="16"
                                                            height="16" fill="#b5b5bf" />
                                                    </clipPath>
                                                </defs>
                                                <g id="Group_8102" data-name="Group 8102" clip-path="url(#clip-path1)">
                                                    <path id="Path_2936" data-name="Path 2936"
                                                        d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z"
                                                        fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('My Wallet') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('support_ticket.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001"
                                            viewBox="0 0 16 16.001">
                                            <g id="Group_25259" data-name="Group 25259" transform="translate(-316 -1066)">
                                                <path id="Subtraction_184" data-name="Subtraction 184"
                                                    d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z"
                                                    transform="translate(-16096 180)" fill="#b5b5bf" />
                                                <path id="Union_12" data-name="Union 12"
                                                    d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z"
                                                    transform="translate(-16090.998 183.001)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Support Ticket') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                <a href="{{ route('logout') }}"
                                    class="text-truncate px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                        viewBox="0 0 16 15.999">
                                        <g id="Group_25503" data-name="Group 25503" transform="translate(-24.002 -377)">
                                            <g id="Group_25265" data-name="Group 25265"
                                                transform="translate(-216.534 -160)">
                                                <path id="Subtraction_192" data-name="Subtraction 192"
                                                    d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z"
                                                    transform="translate(-11803.999 -2367)" fill="#d43533" />
                                            </g>
                                            <rect id="Rectangle_19022" data-name="Rectangle 19022" width="1" height="8"
                                                rx="0.5" transform="translate(31.5 377)" fill="#d43533" />
                                        </g>
                                    </svg>
                                    <span
                                        class="user-top-menu-name has-transition ml-3 text-dark">{{ translate('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Bar -->
    <div class="d-none  position-relative h-50px bottom-background-color-visibility" style="background-color: {{ get_setting('bottom_header_bg_color') }}">
        <div class="container h-100">
            <div class="d-flex h-100">
                <!-- Categoty Menu Button -->
                <div class="d-none d-xl-block all-category has-transition bg-black-10" id="category-menu-bar">
                    <div class="px-3 h-100"
                        style="padding-top: 12px;padding-bottom: 12px; width:270px; cursor: pointer;">
                        <div class="d-flex align-items-center justify-content-between bottom-text-color-visibility" style="color: {{ $bottomHeaderTextColor }}">
                            <div>
                                <span class="fw-700 fs-16 mr-3">{{ translate('Categories') }}</span>
                                <a href="{{ route('categories.all') }}" class="text-reset categoriesAll">
                                    <span
                                        class="d-none d-lg-inline-block animate-underline-white">({{ translate('See All') }})</span>
                                </a>
                            </div>
                            <i class="las la-angle-down has-transition" id="category-menu-bar-icon"
                                style="font-size: 1.2rem !important"></i>
                        </div>
                    </div>
                </div>
                <!-- Header Menus -->
                <div class="ml-xl-4 w-100 overflow-hidden">
                    <div class="d-flex align-items-center justify-content-center justify-content-xl-start h-100">
                        <ul class="list-inline mb-0 pl-0 hor-swipe c-scrollbar-light">
                            @if (get_setting('header_menu_labels') != null)
                                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                                    <li class="list-inline-item mr-0 animate-underline-white">
                                        <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                                            class="fs-13 px-3 py-3 d-inline-block fw-700 header_menu_links hov-bg-black-10 bottom-text-color-visibility
                                                                    @if (url()->current() == json_decode(get_setting('header_menu_links'), true)[$key]) active @endif" style="color: {{ $bottomHeaderTextColor }}">
                                            {{ translate($value) }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <!-- Cart -->
                <div class="d-none d-xl-block align-self-stretch ml-5 mr-0 has-transition bg-black-10"
                    data-hover="dropdown">
                    <div class="nav-cart-box dropdown h-100" id="cart_items" style="width: max-content;">
                        @include('frontend.partials.cart.cart')
                    </div>
                </div>
            </div>
        </div>
        <!-- Categoty Menus -->
        <div class="hover-category-menu position-absolute w-100 top-100 left-0 right-0 z-3 d-none"
            id="click-category-menu">
            <div class="container">
                <div class="d-flex position-relative"></div>
                    <div class="position-static">
                        @include('frontend.' . get_setting('homepage_select') . '.partials.category_menu')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ascolour-header-wrapper">
    <!-- Top Banner (EST. 2005) -->
    <div class="top-navbar z-1035 h-35px h-sm-auto top-background-color-visibility ascolour-top-banner"
    style="background-color: {{  get_setting('top_header_bg_color')  }}">
        <div class="container">
            <span>EST. 2005 - Celebrating 20 Years Made Better</span>
            <button class="banner-close">&times;</button>
        </div>
    </div>
    
    <!-- Main Navigation Bar -->
    <nav class="ascolour-dark-nav bottom-background-color-visibility" style="background-color: {{ get_setting('bottom_header_bg_color') }}">
        <div class="ascolour-nav-container" >
            <!-- Logo -->
            <!-- <a href="{{ route('home') }}" class="ascolour-logo">
                {{ strtolower(get_setting('site_name', 'ascolour')) }}.
            </a> -->
            <div class="col-auto pl-0 pr-3 d-flex align-items-center">
                    <a class="d-block py-20px mr-3 ml-0" href="{{ route('home') }}">
                        @php
                            $header_logo = get_setting('header_logo');
                        @endphp
                        @if ($header_logo != null)
                            <img id="header-logo-preview" src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                                class="mw-75 h-30px h-md-40px" height="40">
                        @else
                            <img id="header-logo-preview" src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                                class="mw-75 h-30px h-md-40px" height="40">
                        @endif
                    </a>
                </div>
            
            <!-- Center Navigation -->
            <ul class="ascolour-main-nav aiz-category-menu">
                @php
                    $mainCategories = get_level_zero_categories()->take(2);
                @endphp

                <li class="ascolour-nav-item">
                            <a href="{{ route('home') }}"
                               class="@if (strtolower($value) == 'outlet') text-warning @endif">
                                Home
                            </a>
                        </li>
                
                @foreach ($mainCategories as $category)
                    <li class="ascolour-nav-item">
                        <a href="{{ route('products.category', $category->slug) }}">
                            {{ $category->getTranslation('name') }}
                        </a>
                        
                        <!-- Mega Menu Dropdown -->
                        @php
                            $categoryChildren = $category->children ?? collect();
                        @endphp
                        @if ($categoryChildren->count() > 0)
                            <div class="ascolour-mega-menu">
                                <div class="ascolour-mega-content">
                                    @php
                                        $childCategories = $categoryChildren;
                                        $totalColumns = min(5, $childCategories->count());
                                        $chunks = $childCategories->chunk(ceil($childCategories->count() / $totalColumns));
                                    @endphp
                                    
                                    @foreach($chunks as $chunk)
                                        <div class="ascolour-mega-column">
                                            @foreach($chunk as $childCategory)
                                                <div class="mb-4">
                                                    <h3 class="ascolour-column-title">
                                                        {{ $childCategory->getTranslation('name') }}
                                                    </h3>
                                                    @php
                                                        $subCategoryChildren = $childCategory->children ?? collect();
                                                    @endphp
                                                    @if ($subCategoryChildren->count() > 0)
                                                        <ul class="ascolour-column-links">
                                                            @foreach($subCategoryChildren as $subCategory)
                                                                <li>
                                                                    <a href="{{ route('products.category', $subCategory->slug) }}">
                                                                        {{ $subCategory->getTranslation('name') }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <ul class="ascolour-column-links">
                                                            <li>
                                                                <a href="{{ route('products.category', $childCategory->slug) }}">
                                                                    {{ translate('View All') }}
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach

                
                
                <!-- Additional Menu Items -->
                @if (get_setting('header_menu_labels') != null)
                    @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                        <li class="ascolour-nav-item">
                            <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                               class="@if (strtolower($value) == 'outlet') text-warning @endif">
                                {{ translate($value) }}
                            </a>
                        </li>
                    @endforeach
                @endif

                <li class="ascolour-nav-item">
                    <a href="{{ route('categories.all') }}">
                        {{ translate('All Categories') }}
                    </a>
                    
                    <!-- All Categories Mega Menu -->
                    <div class="ascolour-mega-menu">
                        <div class="ascolour-mega-content">
                            @php
                                $allCategories = get_level_zero_categories();
                                $totalColumns = min(6, $allCategories->count());
                                $categoryChunks = $allCategories->chunk(ceil($allCategories->count() / $totalColumns));
                            @endphp
                            
                            @foreach($categoryChunks as $chunk)
                                <div class="ascolour-mega-column">
                                    @foreach($chunk as $mainCategory)
                                        <div class="mb-4">
                                            <h3 class="ascolour-column-title">
                                                <a href="{{ route('products.category', $mainCategory->slug) }}" class="text-white">
                                                    {{ $mainCategory->getTranslation('name') }}
                                                </a>
                                            </h3>
                                            @php
                                                $mainCategoryChildren = $mainCategory->children ?? collect();
                                            @endphp
                                            @if ($mainCategoryChildren->count() > 0)
                                                <ul class="ascolour-column-links">
                                                    @foreach($mainCategoryChildren->take(8) as $subCat)
                                                        <li>
                                                            <a href="{{ route('products.category', $subCat->slug) }}">
                                                                {{ $subCat->getTranslation('name') }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                    @if($mainCategoryChildren->count() > 8)
                                                        <li>
                                                            <a href="{{ route('products.category', $mainCategory->slug) }}" class="text-warning">
                                                                {{ translate('View All') }} →
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </li>
                
                
            </ul>
            
            <!-- Right Utility Nav -->
            <ul class="ascolour-utility-nav">
                <li><a href="#" onclick="toggleSearch(); return false;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z" transform="translate(-1.854 -1.854)" fill="#fff"/>
                        <path d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z" transform="translate(-5.2 -5.2)" fill="#fff"/>
                    </svg></a></li>

                 <!-- Notifications -->
                 <ul class="list-inline mb-0 h-100 d-none d-xl-flex justify-content-end align-items-center">
                        <li class="list-inline-item ml-0 mr-0 pr-0 pl-0 dropdown">
                            <a class="dropdown-toggle no-arrow text-secondary fs-12" data-toggle="dropdown"
                                href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false"
                                onclick="nonLinkableNotificationRead()">
                                <span class="position-relative d-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14.668" height="16"
                                        viewBox="0 0 14.668 16">
                                        <path id="_26._Notification" data-name="26. Notification"
                                            d="M8.333,16A3.34,3.34,0,0,0,11,14.667H5.666A3.34,3.34,0,0,0,8.333,16ZM15.06,9.78a2.457,2.457,0,0,1-.727-1.747V6a6,6,0,1,0-12,0V8.033A2.457,2.457,0,0,1,1.606,9.78,2.083,2.083,0,0,0,3.08,13.333H13.586A2.083,2.083,0,0,0,15.06,9.78Z"
                                            transform="translate(-0.999)" fill="#91919b" />
                                    </svg>
                                    @if (Auth::check() && count($user->unreadNotifications) > 0)
                                        <span
                                            class="badge badge-primary badge-inline badge-pill absolute-top-right--10px unread-notification-count">{{ count($user->unreadNotifications) }}</span>
                                    @endif
                                </span>
                            </a>
                            @auth
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 rounded-0">
                                    <div class="p-3 bg-light border-bottom">
                                        <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                    </div>
                                    <div class="c-scrollbar-light overflow-auto" style="max-height:300px;">
                                        <ul class="list-group list-group-flush">
                                            @forelse($user->unreadNotifications as $notification)
                                                @php
                                                    $showNotification = true;
                                                    if (
                                                        $notification->type ==
                                                        'App\Notifications\PreorderNotification' &&
                                                        !addon_is_activated('preorder')
                                                    ) {
                                                        $showNotification = false;
                                                    }
                                                @endphp
                                                @if ($showNotification)
                                                    @php
                                                        $isLinkable = true;
                                                        $notificationType = get_notification_type(
                                                            $notification->notification_type_id,
                                                            'id',
                                                        );
                                                        $notifyContent = $notificationType->getTranslation(
                                                            'default_text',
                                                        );
                                                        $notificationShowDesign = get_setting(
                                                            'notification_show_type',
                                                        );
                                                        if (
                                                            $notification->type ==
                                                            'App\Notifications\customNotification' &&
                                                            $notification->data['link'] == null
                                                        ) {
                                                            $isLinkable = false;
                                                        }
                                                    @endphp
                                                    <li class="list-group-item">
                                                        <div class="d-flex">
                                                            @if ($notificationShowDesign != 'only_text')
                                                                <div class="size-35px mr-2">
                                                                    @php
                                                                        $notifyImageDesign = '';
                                                                        if ($notificationShowDesign == 'design_2') {
                                                                            $notifyImageDesign = 'rounded-1';
                                                                        } elseif (
                                                                            $notificationShowDesign == 'design_3'
                                                                        ) {
                                                                            $notifyImageDesign = 'rounded-circle';
                                                                        }
                                                                    @endphp
                                                                    <img src="{{ uploaded_asset($notificationType->image) }}"
                                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/notification.png') }}';"
                                                                        class="img-fit h-100 {{ $notifyImageDesign }}">
                                                                </div>
                                                            @endif
                                                            <div>
                                                                @if ($notification->type == 'App\Notifications\OrderNotification')
                                                                    @php
                                                                        $orderCode =
                                                                            $notification->data['order_code'];
                                                                        $route = route(
                                                                            'purchase_history.details',
                                                                            encrypt(
                                                                                $notification->data['order_id'],
                                                                            ),
                                                                        );
                                                                        $orderCode =
                                                                            "<span class='text-blue'>" .
                                                                            $orderCode .
                                                                            '</span>';
                                                                        $notifyContent = str_replace(
                                                                            '[[order_code]]',
                                                                            $orderCode,
                                                                            $notifyContent,
                                                                        );
                                                                    @endphp
                                                                @elseif($notification->type == 'App\Notifications\PreorderNotification')
                                                                    @php
                                                                        $orderCode =
                                                                            $notification->data['order_code'];
                                                                        $route = route(
                                                                            'preorder.order_details',
                                                                            encrypt(
                                                                                $notification->data['preorder_id'],
                                                                            ),
                                                                        );
                                                                        $orderCode =
                                                                            "<span class='text-blue'>" .
                                                                            $orderCode .
                                                                            '</span>';
                                                                        $notifyContent = str_replace(
                                                                            '[[order_code]]',
                                                                            $orderCode,
                                                                            $notifyContent,
                                                                        );
                                                                    @endphp
                                                                @endif

                                                                @if ($isLinkable = true)
                                                                    <a
                                                                        href="{{ route('notification.read-and-redirect', encrypt($notification->id)) }}">
                                                                @endif
                                                                    <span
                                                                        class="fs-12 text-dark text-truncate-2">{!! $notifyContent !!}</span>
                                                                    @if ($isLinkable = true)
                                                                        </a>
                                                                    @endif
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="py-4 text-center fs-16">
                                                        {{ translate('No notification found') }}
                                                    </div>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div class="text-center border-top">
                                        <a href="{{ route('customer.all-notifications') }}"
                                            class="text-secondary fs-12 d-block py-2">
                                            {{ translate('View All Notifications') }}
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </li>
                    </ul>


                <li><!-- Cart -->
                <div class="d-none d-xl-block align-self-stretch ml-0 mr-0 has-transition bg-black-10"
                    data-hover="dropdown">
                    <div class="nav-cart-box dropdown p-0" id="cart_items" style="width: max-content;">
                        @include('frontend.partials.cart.cart')
                    </div>
                </div></li>
                <!-- <li><a href="{{ route('cart') }}">Cart</a></li> -->
                @auth
                    <li class="ascolour-user-dropdown position-relative">
                        
                        <a href="javascript:void(0);" class="d-flex align-items-center">
                        <span class="d-flex align-items-center nav-user-info py-20px @if (isAdmin()) ml-0 @endif"
                            id="nav-user-info">
                            <!-- Image -->
                            <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                                @if ($user->avatar_original != null)
                                    <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}" class="img-fit h-100" alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @else
                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                @endif
                            </span>
                            <!-- Name -->
                            <h4 class="h5 fs-14 fw-700 ml-2 mb-0 middle-text-color-visibility" style="color: {{ $middleHeaderTextColor }}">{{ $user->name }}</h4>
                        </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="8" viewBox="0 0 12 8" class="ml-2">
                                <path d="M6,8L0,0H12Z" fill="#fff"/>
                            </svg>
                        </a>
                        
                        <!-- User Dropdown Menu -->
                        <div class="ascolour-user-menu">
                            <ul>
                                @if(isAdmin())
                                    <li>
                                        <a href="{{ route('admin.dashboard') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z" fill="#333"/>
                                            </svg>
                                            {{ translate('Dashboard') }}
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('dashboard') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z" fill="#333"/>
                                            </svg>
                                            {{ translate('Dashboard') }}
                                        </a>
                                    </li>
                                @endif
                                @if(isCustomer())
                                    <li>
                                        <a href="{{ route('purchase_history.index') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                                <g transform="translate(-27.466 -542.963)">
                                                    <path d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1" transform="translate(22.966 537)" fill="#333"/>
                                                </g>
                                            </svg>
                                            {{ translate('Orders') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('wishlists.index') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                                <path d="M8,16l-1.1-1C2.8,11.2,0,8.7,0,5.5A4.482,4.482,0,0,1,4.5,1,4.888,4.888,0,0,1,8,2.6,4.888,4.888,0,0,1,11.5,1,4.482,4.482,0,0,1,16,5.5c0,3.2-2.8,5.7-6.9,9.5Z" fill="#333"/>
                                            </svg>
                                            {{ translate('Wishlist') }}
                                        </a>
                                    </li>
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('purchase_history.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                            <g id="Group_25261" data-name="Group 25261"
                                                transform="translate(-27.466 -542.963)">
                                                <path id="Path_2953" data-name="Path 2953"
                                                    d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                    transform="translate(22.966 537)" fill="#b5b5bf" />
                                                <path id="Path_2954" data-name="Path 2954"
                                                    d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                    transform="translate(27.466 536)" fill="#b5b5bf" />
                                                <path id="Path_2955" data-name="Path 2955"
                                                    d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 532)" fill="#b5b5bf" />
                                                <path id="Path_2956" data-name="Path 2956"
                                                    d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 529)" fill="#b5b5bf" />
                                                <path id="Path_2957" data-name="Path 2957"
                                                    d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                    transform="translate(23.966 526)" fill="#b5b5bf" />
                                                <path id="Path_2958" data-name="Path 2958"
                                                    d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 531.5)" fill="#b5b5bf" />
                                                <path id="Path_2959" data-name="Path 2959"
                                                    d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 528.5)" fill="#b5b5bf" />
                                                <path id="Path_2960" data-name="Path 2960"
                                                    d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(20.966 525.5)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Purchase History') }}</span>
                                    </a>
                                </li>

                                @if (addon_is_activated('preorder'))
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('preorder.order_list') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.002"
                                                viewBox="0 0 16 16.002">
                                                <path id="Union_63" data-name="Union 63"
                                                    d="M14072,894a8,8,0,1,1,8,8A8.011,8.011,0,0,1,14072,894Zm1,0a7,7,0,1,0,7-7A7.007,7.007,0,0,0,14073,894Zm10.652,3.674-3.2-2.781a1,1,0,0,1-.953-1.756V889.5a.5.5,0,1,1,1,0v3.634a1,1,0,0,1,.5.863c0,.015,0,.029,0,.044l3.311,2.876a.5.5,0,0,1,.05.7.5.5,0,0,1-.708.049Z"
                                                    transform="translate(-14072 -885.998)" fill="#b5b5bf" />
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('Preorder List') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if (get_setting('wallet_system') == 1)
                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                        <a href="{{ route('wallet.index') }}"
                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="16" height="16" viewBox="0 0 16 16">
                                                <defs>
                                                    <clipPath id="clip-path1">
                                                        <rect id="Rectangle_1386" data-name="Rectangle 1386" width="16"
                                                            height="16" fill="#b5b5bf" />
                                                    </clipPath>
                                                </defs>
                                                <g id="Group_8102" data-name="Group 8102" clip-path="url(#clip-path1)">
                                                    <path id="Path_2936" data-name="Path 2936"
                                                        d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z"
                                                        fill="#b5b5bf" />
                                                </g>
                                            </svg>
                                            <span
                                                class="user-top-menu-name has-transition ml-3">{{ translate('My Wallet') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('support_ticket.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001"
                                            viewBox="0 0 16 16.001">
                                            <g id="Group_25259" data-name="Group 25259" transform="translate(-316 -1066)">
                                                <path id="Subtraction_184" data-name="Subtraction 184"
                                                    d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z"
                                                    transform="translate(-16096 180)" fill="#b5b5bf" />
                                                <path id="Union_12" data-name="Union 12"
                                                    d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z"
                                                    transform="translate(-16090.998 183.001)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Support Ticket') }}</span>
                                    </a>
                                </li>
                                @endif
                                <li class="border-top">
                                    <a href="{{ route('logout') }}" class="text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                            <g transform="translate(-24.002 -377)">
                                                <path d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z" transform="translate(-11803.999 -2367)" fill="#d43533"/>
                                                <rect width="1" height="8" rx="0.5" transform="translate(31.5 377)" fill="#d43533"/>
                                            </g>
                                        </svg>
                                        {{ translate('Logout') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li><a href="{{ route('user.login') }}">Sign In</a></li>
                    <li><a href="{{ route(get_setting('customer_registration_verify') === '1' ? 'registration.verification' : 'user.registration') }}">Create Account</a></li>
                @endauth
            </ul>
        </div>
    </nav>
</div>
</header>