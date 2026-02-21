@extends('frontend.layouts.app')

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('content')

    <section class="mb-4 pt-4">
        <div class="containerx sm-px-0 pt-2 p-2">
            <form class="" id="search-form" action="" method="GET">
                <div class="row">

                    <!-- Sidebar Filters -->
                    <div class="col-lg-3 col-xl-2">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar c-scrollbar-light text-left">
                                <div class="d-flex d-xl-none justify-content-between align-items-center pl-3 border-bottom">
                                    <h3 class="h6 mb-0 fw-600">{{ translate('Filters') }}</h3>
                                    <button type="button" class="btn btn-sm p-2 filter-sidebar-thumb" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" >
                                        <i class="las la-times la-2x"></i>
                                    </button>
                                </div>

                                <!-- Categories -->
                                <div class="bg-white border mb-3">
                                    <div class="fs-16 fw-700 p-3">
                                        <a href="#collapse_1" class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between" data-toggle="collapse">
                                            {{ translate('Categories')}}
                                        </a>
                                    </div>
                                    <div class="collapse show" id="collapse_1">
                                        <ul class="p-3 mb-0 list-unstyled">
                                            <li class="mb-3">
                                                <a class="text-reset fs-14 fw-600 hov-text-primary" href="{{ route('home') }}">
                                                    <i class="las la-angle-left"></i>
                                                    {{ translate('All Categories')}}
                                                </a>
                                            </li>
                                            
                                            @if (count($parent_ids) > 0 && $category->parentCategory)
                                                <li class="mb-3">
                                                    <a class="text-reset fs-14 fw-600 hov-text-primary" href="{{ route('group_products.category', $category->parentCategory->slug) }}">
                                                        <i class="las la-angle-left"></i>
                                                        {{ $category->parentCategory->getTranslation('name') }}
                                                    </a>
                                                </li>
                                            @endif
                                            
                                            <li class="mb-3">
                                                <a class="text-reset fs-14 fw-600 hov-text-primary" href="{{ route('group_products.category', $category->slug) }}">
                                                    {{ $category->getTranslation('name') }}
                                                </a>
                                            </li>
                                            
                                            @if ($category->childrenCategories && $category->childrenCategories->count() > 0)
                                                @foreach ($category->childrenCategories as $child_category)
                                                    <li class="ml-4 mb-3">
                                                        <a class="text-reset fs-14 hov-text-primary" href="{{ route('group_products.category', $child_category->slug) }}">
                                                            {{ $child_category->getTranslation('name') }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif

                                            @foreach ($categories as $cat)
                                                @if ($cat->id != $category->id && !in_array($cat->id, $parent_ids))
                                                    <li class="mb-3">
                                                        <a class="text-reset fs-14 hov-text-primary" href="{{ route('group_products.category', $cat->slug) }}">
                                                            {{ $cat->getTranslation('name') }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contents -->
                    <div class="col-lg-9 col-xl-10">
                        <!-- Breadcrumb -->
                        <ul class="breadcrumb bg-transparent py-0 px-1">
                            <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                                <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
                            </li>
                            <li class="breadcrumb-item opacity-50 hov-opacity-100">
                                <a class="text-reset" href="{{ route('home') }}">{{ translate('Group Products')}}</a>
                            </li>
                            <li class="text-dark fw-600 breadcrumb-item">
                                {{ $category->getTranslation('name') }}
                            </li>
                        </ul>
                        
                        <!-- Top Filters -->
                        <div class="text-left">
                            <div class="row gutters-5 flex-wrap align-items-center">
                                <div class="col-lg col-10">
                                    <h1 class="fs-20 fs-md-24 fw-700 text-dark">
                                        {{ $category->getTranslation('name') }}
                                    </h1>
                                    <input type="hidden" name="keyword" value="{{ $query }}">
                                </div>
                                <div class="col-2 col-lg-auto d-xl-none mb-lg-3 text-right">
                                    <button type="button" class="btn btn-icon p-0" data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                        <i class="la la-filter la-2x"></i>
                                    </button>
                                </div>

                                <div class="col-6 col-lg-auto mb-3 w-lg-200px">
                                    <select class="form-control form-control-sm aiz-selectpicker rounded-0" name="sort_by" onchange="filter()">
                                        <option value="">{{ translate('Sort by')}}</option>
                                        <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>{{ translate('Newest')}}</option>
                                        <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>{{ translate('Oldest')}}</option>
                                        <option value="name-asc" @isset($sort_by) @if ($sort_by == 'name-asc') selected @endif @endisset>{{ translate('Name A-Z')}}</option>
                                        <option value="name-desc" @isset($sort_by) @if ($sort_by == 'name-desc') selected @endif @endisset>{{ translate('Name Z-A')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Group Products -->
                        <div class="px-3">
                            <div class="row gutters-16 row-cols-xxl-6 row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-2 border-top border-left">
                                @foreach ($group_products as $key => $group_product)
                                    <div class="col border-right border-bottom has-transition hov-shadow-out z-1">
                                        @include('frontend.group_products.partials.product_box', ['group_product' => $group_product])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        @if($group_products->count() == 0)
                            <div class="text-center py-5">
                                <h5 class="text-muted">{{ translate('No group products found in this category') }}</h5>
                            </div>
                        @endif
                        
                        <div class="aiz-pagination mt-4">
                            {{ $group_products->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }
    </script>
@endsection

