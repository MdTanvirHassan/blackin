@php
    $categories = \App\Models\Category::where('level', 0)->orderBy('order_level', 'desc')->get()->take(10);
@endphp

<!-- Top Banner (EST. 2005 - Celebrating 20 Years) -->
<div class="top-banner-modern bg-dark text-white position-relative z-1035" style="background-color: #2b2b2b !important;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-center py-2">
            <span class="fs-13">
                {{ translate('EST. 2005 - Celebrating') }} 
                <span style="color: #d4a574;">{{ translate('20 Years') }}</span> 
                {{ translate('Made Better') }}
            </span>
        </div>
    </div>
    <button class="btn btn-link text-white position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); padding: 0; opacity: 0.7;" onclick="this.closest('.top-banner-modern').style.display='none'">
        <i class="la la-times"></i>
    </button>
</div>

<!-- Main Navigation Bar -->
<nav class="main-nav-modern bg-white shadow-sm sticky-top z-1020">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between py-3">
            
            <!-- Mobile Menu Button -->
            <button type="button" class="btn d-lg-none mr-3 p-0" data-toggle="class-toggle" data-target=".aiz-top-menu-sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <rect width="24" height="2" fill="#000"/>
                    <rect y="11" width="24" height="2" fill="#000"/>
                    <rect y="22" width="24" height="2" fill="#000"/>
                </svg>
            </button>

            <!-- Logo -->
            <div class="nav-logo">
                <a href="{{ route('home') }}" class="d-block">
                    @php
                        $header_logo = get_setting('header_logo');
                    @endphp
                    @if ($header_logo != null)
                        <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}" class="h-30px h-md-40px" height="40">
                    @else
                        <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" class="h-30px h-md-40px" height="40">
                    @endif
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="d-none d-lg-flex align-items-center flex-grow-1 justify-content-center">
                <ul class="list-unstyled d-flex mb-0 nav-items-modern">
                    @foreach($categories as $key => $category)
                        <li class="nav-item-modern position-relative">
                            <a href="{{ route('products.category', $category->slug) }}" class="nav-link-modern px-3 py-2 fs-14 fw-600 text-dark d-inline-block">
                                {{ $category->getTranslation('name') }}
                            </a>
                            
                            <!-- Mega Menu Dropdown -->
                            @if ($category->children()->count() > 0)
                                <div class="mega-menu-dropdown position-absolute w-100 bg-white shadow-lg" style="left: 0; top: 100%; min-width: 800px; display: none; z-index: 9999;">
                                    <div class="container py-5">
                                        <div class="row">
                                            @php
                                                $childCategories = $category->children;
                                                $chunks = $childCategories->chunk(ceil($childCategories->count() / 3));
                                            @endphp
                                            
                                            @foreach($chunks as $chunkIndex => $chunk)
                                                <div class="col-lg-4">
                                                    @foreach($chunk as $childCategory)
                                                        <div class="mb-4">
                                                            <h6 class="fs-14 fw-700 text-dark mb-3">
                                                                <a href="{{ route('products.category', $childCategory->slug) }}" class="text-dark">
                                                                    {{ $childCategory->getTranslation('name') }}
                                                                </a>
                                                            </h6>
                                                            @if ($childCategory->children()->count() > 0)
                                                                <ul class="list-unstyled mb-0">
                                                                    @foreach($childCategory->children()->take(6) as $subCategory)
                                                                        <li class="mb-2">
                                                                            <a href="{{ route('products.category', $subCategory->slug) }}" class="fs-13 text-secondary d-inline-block mega-submenu-link">
                                                                                {{ $subCategory->getTranslation('name') }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                    @if($childCategory->children()->count() > 6)
                                                                        <li class="mb-2">
                                                                            <a href="{{ route('products.category', $childCategory->slug) }}" class="fs-13 fw-700 text-primary d-inline-block">
                                                                                {{ translate('View All') }}
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
                                </div>
                            @endif
                        </li>
                    @endforeach
                    
                    <!-- Additional Menu Items -->
                    @if (get_setting('header_menu_labels') != null)
                        @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                            <li class="nav-item-modern">
                                <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}" class="nav-link-modern px-3 py-2 fs-14 fw-600 text-dark d-inline-block @if($key == 'outlet' || strtolower($value) == 'outlet') text-warning @endif">
                                    {{ translate($value) }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <!-- Right Side Utility Links -->
            <div class="d-flex align-items-center">
                <!-- Search -->
                <a href="javascript:void(0);" class="btn btn-link text-dark p-2 mr-2" data-toggle="class-toggle" data-target=".search-modal-modern">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                        <path d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z" transform="translate(-1.854 -1.854)" fill="#000"/>
                        <path d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z" transform="translate(-5.2 -5.2)" fill="#000"/>
                    </svg>
                </a>

                <!-- Cart -->
                @if (Auth::check() && auth()->user()->user_type == 'customer')
                    @php
                        $count = count(get_user_cart());
                    @endphp
                    <a href="{{ route('cart') }}" class="btn btn-link text-dark p-2 mr-2 position-relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                            <path d="M8,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1" transform="translate(-3 -11.999)" fill="#000"/>
                            <path d="M24,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1" transform="translate(-10.999 -11.999)" fill="#000"/>
                            <path d="M15.923,3.975A1.5,1.5,0,0,0,14.5,2h-9a.5.5,0,1,0,0,1h9a.507.507,0,0,1,.129.017.5.5,0,0,1,.355.612l-1.581,6a.5.5,0,0,1-.483.372H5.456a.5.5,0,0,1-.489-.392L3.1,1.176A1.5,1.5,0,0,0,1.632,0H.5a.5.5,0,1,0,0,1H1.544a.5.5,0,0,1,.489.392L3.9,9.826A1.5,1.5,0,0,0,5.368,11h7.551a1.5,1.5,0,0,0,1.423-1.026Z" transform="translate(0 -0.001)" fill="#000"/>
                        </svg>
                        @if($count > 0)
                            <span class="badge badge-primary badge-sm badge-dot position-absolute" style="top: 5px; right: 5px;"></span>
                        @endif
                    </a>
                @endif

                <!-- Sign In / User Menu -->
                @auth
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="btn btn-link text-dark p-2 dropdown-toggle" data-toggle="dropdown">
                            <span class="fs-13 fw-600">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if(isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item">{{ translate('Dashboard') }}</a>
                            @else
                                <a href="{{ route('dashboard') }}" class="dropdown-item">{{ translate('Dashboard') }}</a>
                            @endif
                            <a href="{{ route('logout') }}" class="dropdown-item">{{ translate('Logout') }}</a>
                        </div>
                    </div>
                @else
                    <a href="{{ route('user.login') }}" class="btn btn-link text-dark p-2 fs-13 fw-600 mr-2">
                        {{ translate('Sign In') }}
                    </a>
                    <a href="{{ route(get_setting('customer_registration_verify') === '1' ? 'registration.verification' : 'user.registration') }}" class="btn btn-dark px-4 py-2 fs-13 fw-600 rounded-0">
                        {{ translate('Create Account') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Search Modal -->
<div class="search-modal-modern position-fixed w-100 h-100 d-none" style="top: 0; left: 0; background: rgba(0,0,0,0.8); z-index: 9999;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="bg-white rounded mt-5 p-4 position-relative">
                    <button class="btn btn-link position-absolute" style="right: 10px; top: 10px;" data-toggle="class-toggle" data-target=".search-modal-modern">
                        <i class="la la-times la-2x"></i>
                    </button>
                    <form action="{{ route('search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control border-0 fs-16" name="keyword" @isset($query) value="{{ $query }}" @endisset placeholder="{{ translate('Search for products...') }}" autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-dark px-4" type="submit">
                                    {{ translate('Search') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mega Menu Styles -->
<style>
    /* Navigation Styles */
    .main-nav-modern {
        background-color: #ffffff;
    }
    
    .nav-link-modern {
        position: relative;
        transition: all 0.3s ease;
    }
    
    .nav-link-modern:hover {
        color: #000 !important;
    }
    
    .nav-link-modern::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background-color: #000;
        transition: width 0.3s ease;
    }
    
    .nav-item-modern:hover .nav-link-modern::after {
        width: 80%;
    }
    
    /* Mega Menu */
    .nav-item-modern:hover .mega-menu-dropdown {
        display: block !important;
    }
    
    .mega-menu-dropdown {
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        animation: fadeInDown 0.3s ease;
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .mega-submenu-link {
        transition: all 0.2s ease;
        opacity: 0.75;
    }
    
    .mega-submenu-link:hover {
        opacity: 1;
        color: #000 !important;
        padding-left: 5px;
    }
    
    /* Top Banner */
    .top-banner-modern {
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    /* Search Modal */
    .search-modal-modern {
        backdrop-filter: blur(5px);
    }
    
    /* Responsive */
    @media (max-width: 991px) {
        .mega-menu-dropdown {
            display: none !important;
        }
    }
</style>

<!-- Mega Menu JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle mega menu positioning
        const navItems = document.querySelectorAll('.nav-item-modern');
        
        navItems.forEach(item => {
            const megaMenu = item.querySelector('.mega-menu-dropdown');
            if (megaMenu) {
                item.addEventListener('mouseenter', function() {
                    const rect = this.getBoundingClientRect();
                    const menuWidth = 800; // min-width from CSS
                    const windowWidth = window.innerWidth;
                    
                    // Center the mega menu under the navigation
                    if (rect.left + menuWidth / 2 > windowWidth) {
                        megaMenu.style.left = 'auto';
                        megaMenu.style.right = '0';
                    } else if (rect.left - menuWidth / 2 < 0) {
                        megaMenu.style.left = '0';
                        megaMenu.style.right = 'auto';
                    } else {
                        megaMenu.style.left = '50%';
                        megaMenu.style.transform = 'translateX(-50%)';
                        megaMenu.style.right = 'auto';
                    }
                });
            }
        });
        
        // Close search modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelector('.search-modal-modern').classList.add('d-none');
            }
        });
        
        // Close search modal when clicking outside
        document.querySelector('.search-modal-modern')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('d-none');
            }
        });
    });
</script>

