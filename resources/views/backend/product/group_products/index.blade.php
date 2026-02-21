@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('Group Products') }}</h1>
        </div>
        <div class="col text-right">
            <a href="{{ route('group_products.create') }}" class="btn btn-circle btn-info">
                <span>{{ translate('Add Bundle') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="card">
    <form id="sort_group_products" action="" method="GET">
        <div class="card-header flex-column flex-xl-row align-items-xl-center gap-3">
            <div class="mb-3 mb-xl-0">
                <h5 class="mb-1 h6">{{ translate('All Bundles') }}</h5>
                <small class="text-muted">
                    {{ translate('Showing') }} {{ $group_products->total() }} {{ translate('bundle(s)') }}
                </small>
            </div>
            <div class="ml-xl-auto w-100 w-xl-auto">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-soft-secondary border-0">
                            <i class="las la-search"></i>
                        </span>
                    </div>
                    <input type="text"
                           class="form-control"
                           id="search"
                           name="search"
                           value="{{ $sort_search }}"
                           placeholder="{{ translate('Search bundles') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">{{ translate('Search') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-top px-3 px-lg-4 py-3 bg-soft-secondary">
            <div class="row gutters-10 align-items-end">
                <div class="col-md-4">
                    <label class="mb-1 small text-uppercase text-muted">{{ translate('Category') }}</label>
                    <select class="form-control aiz-selectpicker" data-live-search="true" name="category_id" data-placeholder="{{ translate('All categories') }}">
                        <option value="">{{ translate('All categories') }}</option>
                        @foreach(($filterCategories ?? collect()) as $filterCategory)
                            <option value="{{ $filterCategory->id }}" {{ (string)request('category_id') === (string)$filterCategory->id ? 'selected' : '' }}>
                                {{ $filterCategory->getTranslation('name') }}
                            </option>
                            @foreach($filterCategory->childrenCategories as $childCategory)
                                <option value="{{ $childCategory->id }}" {{ (string)request('category_id') === (string)$childCategory->id ? 'selected' : '' }}>
                                    — {{ $childCategory->getTranslation('name') }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="mb-1 small text-uppercase text-muted">{{ translate('Publication') }}</label>
                    <select class="form-control aiz-selectpicker" name="published">
                        <option value="">{{ translate('Published & Draft') }}</option>
                        <option value="1" {{ request('published') === '1' ? 'selected' : '' }}>{{ translate('Published') }}</option>
                        <option value="0" {{ request('published') === '0' ? 'selected' : '' }}>{{ translate('Draft') }}</option>
                    </select>
                </div>
                <div class="col-md-5 d-flex justify-content-md-end mt-3 mt-md-0">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="las la-filter mr-1"></i> {{ translate('Apply Filters') }}
                    </button>
                    <a href="{{ route('group_products.index') }}" class="btn btn-soft-secondary">
                        <i class="las la-sync mr-1"></i> {{ translate('Reset') }}
                    </a>
                </div>
            </div>
            @php
                $activeFilters = array_filter([
                    'category' => request('category_id'),
                    'published' => request('published'),
                ], function ($value) {
                    return $value !== null && $value !== '';
                });
            @endphp
            @if(!empty($activeFilters))
                <div class="mt-3 bg-white border rounded px-3 py-2 d-flex align-items-center flex-wrap gap-2 shadow-xs">
                    <span class="small text-muted text-uppercase mr-2">{{ translate('Active filters') }}</span>
                    @if(request('category_id') && ($currentCategory = ($filterCategories ?? collect())->firstWhere('id', request('category_id'))))
                        <span class="badge badge-inline badge-soft-primary font-weight-medium mr-2">
                            {{ translate('Category') }}: {{ $currentCategory->getTranslation('name') }}
                        </span>
                    @endif
                    @if(request('published') !== null && request('published') !== '')
                        <span class="badge badge-inline badge-soft-secondary font-weight-medium">
                            {{ translate('Publication') }}:
                            {{ request('published') === '1' ? translate('Published') : translate('Draft') }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('#') }}</th>
                            <th>{{ translate('Bundle') }}</th>
                            <th data-breakpoints="lg">{{ translate('Deal Type') }}</th>
                            <th data-breakpoints="md">{{ translate('Buy / Free Slots') }}</th>
                            <th data-breakpoints="lg">{{ translate('Slot Coverage') }}</th>
                            <th data-breakpoints="lg">{{ translate('Categories') }}</th>
                            <th data-breakpoints="md">{{ translate('Discount') }}</th>
                            <th data-breakpoints="md">{{ translate('Published') }}</th>
                            <th data-breakpoints="lg">{{ translate('Status') }}</th>
                            <th class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($group_products->count())
                            @foreach($group_products as $key => $group_product)
                                <tr>
                                    <td>{{ ($key + 1) + ($group_products->currentPage() - 1) * $group_products->perPage() }}</td>
                                    <td>
                                        <div class="row gutters-5 w-250px mw-100">
                                            <div class="col-auto">
                                                <img src="{{ uploaded_asset($group_product->thumbnail_img)}}" alt="Image" class="size-60px img-fit">
                                            </div>
                                            <div class="col">
                                                <a href="{{ route('group_products.show', filled($group_product->slug) ? $group_product->slug : $group_product->id) }}"
                                                   class="text-muted text-truncate-2 d-block"
                                                   target="_blank">
                                                    {{ $group_product->name }}
                                                </a>
                                                <small class="text-muted">#{{ $group_product->id }} &middot; {{ translate('Updated') }} {{ $group_product->updated_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($group_product->deal_type == 'buy_3_get_1_free')
                                            {{ translate('Buy 3 Get 1 Free') }}
                                        @elseif($group_product->deal_type == 'buy_5_get_2_free')
                                            {{ translate('Buy 5 Get 2 Free') }}
                                        @elseif($group_product->deal_type == 'signature_polo_bundle')
                                            {{ translate('3 Signature Polo Bundle') }}
                                        @else
                                            {{ translate('Custom Mix') }}
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ translate('Buy') }}:</strong> {{ $group_product->buy_quantity }}<br>
                                        <strong>{{ translate('Free') }}:</strong> {{ $group_product->free_quantity }}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="badge badge-inline badge-soft-success mb-1">
                                                {{ translate('Paid slots') }}: {{ $group_product->paid_slots_count ?? 0 }}
                                            </span>
                                            <span class="badge badge-inline badge-soft-info">
                                                {{ translate('Free slots') }}: {{ $group_product->free_slots_count ?? 0 }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($group_product->categories->count() > 0)
                                            @foreach($group_product->categories->take(3) as $category)
                                                <span class="badge badge-inline badge-light mb-1">{{ $category->getTranslation('name') }}</span>
                                            @endforeach
                                            @if($group_product->categories->count() > 3)
                                                <span class="badge badge-inline badge-soft-primary mb-1">+{{ $group_product->categories->count() - 3 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">{{ translate('Uncategorized') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $slotsWithDiscounts = $group_product->slots->filter(function ($slot) {
                                                return $slot->discount_type !== 'none' && $slot->discount_value > 0;
                                            });
                                        @endphp
                                        @if($slotsWithDiscounts->count() > 0)
                                            <div class="d-flex flex-column gap-1">
                                                @foreach($slotsWithDiscounts->take(2) as $slot)
                                                    <span class="badge badge-inline badge-success mb-1">
                                                        {{ $slot->name ?? translate('Slot') }}: 
                                                        @if($slot->discount_type == 'percentage')
                                                            {{ $slot->discount_value }}%
                                                        @else
                                                            {{ single_price($slot->discount_value) }}
                                                        @endif
                                                    </span>
                                                @endforeach
                                                @if($slotsWithDiscounts->count() > 2)
                                                    <span class="badge badge-inline badge-soft-primary">
                                                        +{{ $slotsWithDiscounts->count() - 2 }} {{ translate('more') }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge badge-inline badge-secondary">{{ translate('None') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_published(this)" value="{{ $group_product->id }}" type="checkbox" {{ $group_product->published ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                    <td>
                                        @if($group_product->active)
                                            <span class="badge badge-inline badge-success">{{ translate('Active') }}</span>
                                        @else
                                            <span class="badge badge-inline badge-secondary">{{ translate('Inactive') }}</span>
                                        @endif
                                        <div class="text-muted small">{{ translate('Created') }} {{ $group_product->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="text-right">
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('group_products.edit', $group_product->id)}}" title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('group_products.destroy', $group_product->id)}}" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <h5 class="mb-1">{{ translate('No group products found') }}</h5>
                                    <p class="text-muted mb-3">{{ translate('Click “Add Bundle” to create your first combo experience.') }}</p>
                                    <a href="{{ route('group_products.create') }}" class="btn btn-primary">
                                        <i class="las la-plus mr-1"></i> {{ translate('Create Group Product') }}
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if($group_products->count())
            <div class="card-footer">
                <div class="aiz-pagination">
                    {{ $group_products->appends(request()->input())->links() }}
                </div>
            </div>
        @endif
    </form>
</div>

@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function update_published(el){
            if ("{{ env('DEMO_MODE') }}" == 'On') {
                AIZ.plugins.notify('info', "{{ translate('Data can not change in demo mode.') }}");
                return;
            }
            
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            
            $.post("{{ route('group_products.update_published') }}", {_token:"{{ csrf_token() }}", id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', "{{ translate('Published status updated successfully') }}");
                }
                else{
                    AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
                }
            });
        }

        $(document).ready(function(){
            $('#search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    $('#sort_group_products').submit();
                }
            });
        });
    </script>
@endsection

