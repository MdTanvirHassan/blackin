@extends('backend.layouts.app')

@section('content')

<div class="page-content">
    <div class="aiz-titlebar text-left mt-2 pb-2 px-3 px-md-2rem border-bottom border-gray">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Add New Group Product') }}</h1>
            </div>
        </div>
    </div>

    <div class="bg-white p-3 p-sm-2rem">
        <form action="{{route('group_products.store')}}" method="POST" enctype="multipart/form-data" id="group_product_form">
            @csrf
            
            <!-- Basic Information -->
            <h5 class="mb-3 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;">{{translate('Basic Information')}}</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label fs-13">{{translate('Name')}} <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="{{ translate('Group Product Name') }}" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label fs-13">{{translate('Slug')}}</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" placeholder="{{ translate('URL Slug (auto-generated if empty)') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="fs-13">{{translate('Description')}}</label>
                <textarea class="aiz-text-editor" name="description">{{ old('description') }}</textarea>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-from-label fs-13">{{translate('Thumbnail Image')}}</label>
                <div class="col-md-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="thumbnail_img" class="selected-files" value="{{ old('thumbnail_img') }}">
                    </div>
                    <div class="file-preview box sm">
                    </div>
                </div>
            </div>

            <!-- Deal Type -->
            <h5 class="mb-3 mt-4 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;">{{translate('Deal Configuration')}}</h5>
            
            @php
                $dealTypeValue = old('deal_type', 'custom');
                $dealTypeOptions = [
                    'custom' => [
                        'label' => translate('Custom Mix'),
                        'icon' => 'las la-sliders-h',
                        'description' => translate('Define your own buy/free quantities.'),
                    ],
                    'buy_3_get_1_free' => [
                        'label' => translate('Buy 3 Get 1 Free'),
                        'icon' => 'las la-gift',
                        'description' => translate('Preloads buy/free counts (you can still edit).'),
                    ],
                    'buy_5_get_2_free' => [
                        'label' => translate('Buy 5 Get 2 Free'),
                        'icon' => 'las la-layer-group',
                        'description' => translate('Great for bundles, editable counts.'),
                    ],
                    'signature_polo_bundle' => [
                        'label' => translate('3 Signature Polo Bundle'),
                        'icon' => 'las la-tshirt',
                        'description' => translate('Preset bundle without freebies (editable).'),
                    ],
                ];
            @endphp

            <div class="deal-type-selector mb-3">
                <div class="row gutters-10">
                    @foreach($dealTypeOptions as $value => $option)
                        <div class="col-md-3 mb-3">
                            <label class="deal-type-card {{ $dealTypeValue === $value ? 'active' : '' }}">
                                <input type="radio" name="deal_type" value="{{ $value }}" class="deal-type-input" {{ $dealTypeValue === $value ? 'checked' : '' }}>
                                <span class="deal-type-icon">
                                    <i class="{{ $option['icon'] }}"></i>
                                </span>
                                <span class="deal-type-title">{{ $option['label'] }}</span>
                                <span class="deal-type-description">{{ $option['description'] }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="fs-13">{{translate('Buy Quantity')}} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="buy_quantity" id="buy_quantity" value="{{ old('buy_quantity', 1) }}" min="1">
                        <small class="text-muted">{{ translate('Controls how many paid slots shoppers must fill.') }}</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="fs-13">{{translate('Free Quantity')}}</label>
                            <input type="number" class="form-control" name="free_quantity" id="free_quantity" value="{{ old('free_quantity', 0) }}" min="0">
                        <small class="text-muted">{{ translate('Matching number of free slots will be generated.') }}</small>
                    </div>
                </div>
            </div>

            @php
                $buyQuantity = max((int)old('buy_quantity', 1), 1);
                $freeQuantity = max((int)old('free_quantity', 0), 0);

                $defaultSlot = [
                    'name' => '',
                    'discount_type' => 'none',
                    'discount_value' => '',
                    'product_ids' => [],
                    'sort_order' => 0,
                ];

                $paidSlotsData = old('slots_paid');
                if (!is_array($paidSlotsData) || empty($paidSlotsData)) {
                    $paidSlotsData = [];
                    for ($i = 0; $i < $buyQuantity; $i++) {
                        $slot = $defaultSlot;
                        $slot['sort_order'] = $i;
                        $paidSlotsData[] = $slot;
                    }
                } else {
                    $paidSlotsData = array_values($paidSlotsData);
                }

                $freeSlotsData = old('slots_free');
                if (!is_array($freeSlotsData) || empty($freeSlotsData)) {
                    $freeSlotsData = [];
                    for ($i = 0; $i < $freeQuantity; $i++) {
                        $slot = $defaultSlot;
                        $slot['sort_order'] = $i;
                        $freeSlotsData[] = $slot;
                    }
                } else {
                    $freeSlotsData = array_values($freeSlotsData);
                }
            @endphp

            @php
                $dealTypeLabel = $dealTypeOptions[$dealTypeValue]['label'] ?? ucwords(str_replace('_', ' ', $dealTypeValue));
                $dealTypeDescription = $dealTypeOptions[$dealTypeValue]['description'] ?? translate('Custom configuration');
                $paidProductTotal = array_sum(array_map(function ($slot) {
                    return count($slot['product_ids'] ?? []);
                }, $paidSlotsData));
                $freeProductTotal = array_sum(array_map(function ($slot) {
                    return count($slot['product_ids'] ?? []);
                }, $freeSlotsData));
                $totalProductOptions = $paidProductTotal + $freeProductTotal;
                $coverageTemplate = translate(':paid paid / :free free options');
                $coverageHelper = str_replace([':paid', ':free'], [$paidProductTotal, $freeProductTotal], $coverageTemplate);
                $overviewCards = [
                    [
                        'label' => translate('Deal Type'),
                        'value' =>
                        $dealTypeLabel,
                        'helper' => $dealTypeDescription,
                        'icon' => 'las la-bolt',
                    ],
                    [
                        'label' => translate('Buy Quantity'),
                        'value' => $buyQuantity,
                        'helper' => translate('Paid slots shoppers must fill'),
                        'icon' => 'las la-shopping-cart',
                    ],
                    [
                        'label' => translate('Free Quantity'),
                        'value' => $freeQuantity,
                        'helper' => $freeQuantity > 0 ? translate('Free selections in this bundle') : translate('No free items configured'),
                        'icon' => 'las la-gift',
                    ],
                    [
                        'label' => translate('Product Coverage'),
                        'value' => $totalProductOptions,
                        'helper' => $coverageHelper,
                        'icon' => 'las la-layer-group',
                    ],
                ];
            @endphp

            <div class="deal-overview-grid mt-3">
                @foreach($overviewCards as $card)
                    <div class="deal-overview-card">
                        <div class="deal-overview-icon">
                            <i class="{{ $card['icon'] }}"></i>
                        </div>
                        <div>
                            <span class="deal-overview-label">{{ $card['label'] }}</span>
                            <span class="deal-overview-value">{{ $card['value'] }}</span>
                            <span class="deal-overview-helper">{{ $card['helper'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paid Slots -->
            <div class="slot-section mt-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0 fs-17 fw-700">{{ translate('Paid Slots') }}</h5>
                    <small class="text-muted" id="paid_slot_status"></small>
                </div>
                @error('slots_paid')
                    <div class="alert alert-danger py-2 px-3">{{ $message }}</div>
                @enderror
                <div id="paid_slots_container" class="slot-collection" data-slot-group="paid">
                    @foreach($paidSlotsData as $slotIndex => $slotData)
                        @include('backend.product.group_products.partials.slot_form', [
                            'slotIndex' => $slotIndex,
                            'slotData' => $slotData,
                            'products' => $products,
                            'slotNamespace' => 'slots_paid',
                            'slotGroupKey' => 'paid',
                            'isFreeSlot' => false,
                            'isFirst' => $loop->first,
                        ])
                    @endforeach
                </div>
            </div>

            <!-- Free Slots -->
            <div class="slot-section mt-4 {{ $freeQuantity > 0 ? '' : 'd-none' }}" id="free_slots_section">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0 fs-17 fw-700">{{ translate('Free Slots') }}</h5>
                    <small class="text-muted" id="free_slot_status"></small>
                </div>
                @error('slots_free')
                    <div class="alert alert-danger py-2 px-3">{{ $message }}</div>
                @enderror
                <div id="free_slots_container" class="slot-collection" data-slot-group="free">
                    @foreach($freeSlotsData as $slotIndex => $slotData)
                        @include('backend.product.group_products.partials.slot_form', [
                            'slotIndex' => $slotIndex,
                            'slotData' => $slotData,
                            'products' => $products,
                            'slotNamespace' => 'slots_free',
                            'slotGroupKey' => 'free',
                            'isFreeSlot' => true,
                            'isFirst' => $loop->first,
                        ])
                    @endforeach
                </div>
            </div>

            <!-- Refund -->
            @if (addon_is_activated('refund_request'))
                <h5 class="mb-3 mt-4 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;">{{translate('Refund')}}</h5>
                <div class="w-100">
                    <div class="form-group row">
                        <label class="col-md-2 col-from-label fs-13">{{translate('Refundable')}}?</label>
                        <div class="col-md-10">
                            <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                <input type="checkbox"
                                       name="refundable"
                                       value="1"
                                       onchange="groupProductIsRefundable()"
                                       {{ old('refundable', get_setting('refund_type') != 'category_based_refund' ? 1 : 0) ? 'checked' : '' }}>
                                <span></span>
                            </label>
                            <small id="group-product-refundable-note" class="text-muted d-none"></small>
                        </div>
                    </div>
                    <div class="w-100 group-product-refund-block d-none">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-check-label fw-bold">
                                    <b>{{translate('Note (Add from preset)')}} </b>
                                </label>
                            </div>
                        </div>

                        <input type="hidden" name="refund_note_id" id="group_product_refund_note_id" value="{{ old('refund_note_id') }}">
                        <div id="group_product_refund_note" class="">
                        </div>
                        <button
                            type="button"
                            class="btn btn-block border border-dashed hov-bg-soft-secondary mt-2 fs-14 rounded-0 d-flex align-items-center justify-content-center"
                            onclick="noteModal('refund')">
                            <i class="las la-plus"></i>
                            <span class="ml-2">{{ translate('Select Refund Note') }}</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Categories -->
            <h5 class="mb-3 mt-4 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;">{{translate('Categories')}}</h5>
            
            <div class="form-group">
                <label class="fs-13">{{translate('Select Categories')}}</label>
                <select class="form-control aiz-selectpicker" name="category_ids[]" id="category_ids" multiple data-live-search="true">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                        @foreach($category->childrenCategories as $childCategory)
                            <option value="{{ $childCategory->id }}">-- {{ $childCategory->getTranslation('name') }}</option>
                        @endforeach
                    @endforeach
                </select>
                <small class="text-muted">{{ translate('Optional: Associate this group product with categories') }}</small>
            </div>

            <!-- SEO -->
            <h5 class="mb-3 mt-4 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;">{{translate('SEO Settings')}}</h5>
            
            <div class="form-group row">
                <label class="col-md-2 col-from-label fs-13">{{translate('Meta Title')}}</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}" placeholder="{{ translate('Meta Title') }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 col-from-label fs-13">{{translate('Meta Description')}}</label>
                <div class="col-md-10">
                    <textarea class="form-control" name="meta_description" rows="3" placeholder="{{ translate('Meta Description') }}">{{ old('meta_description') }}</textarea>
                </div>
            </div>

            <!-- Status -->
            <h5 class="mb-3 mt-4 pb-3 fs-17 fw-700" style="border-bottom: 1px dashed #e4e5eb;">{{translate('Status')}}</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-from-label fs-13">{{translate('Published')}}</label>
                        <div class="col-md-8">
                            <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                <input type="checkbox" name="published" value="1" {{ old('published', true) ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-from-label fs-13">{{translate('Active')}}</label>
                        <div class="col-md-8">
                            <label class="aiz-switch aiz-switch-success mb-0 d-block">
                                <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-primary">{{translate('Save Group Product')}}</button>
            </div>
        </form>
    </div>
</div>

@endsection

@include('backend.product.group_products.partials.slot_styles')
@include('backend.product.group_products.partials.slot_template', ['products' => $products])
@include('backend.product.group_products.partials.slot_bulk_modal')

@php
    $initialSlotCounts = [
        'paid' => count($paidSlotsData),
        'free' => count($freeSlotsData),
    ];
@endphp

@section('script')
    @include('backend.product.group_products.partials.form_scripts', [
        'initialSlotCounts' => $initialSlotCounts
    ])

    <script type="text/javascript">
        function noteModal(noteType){
            $.post('{{ route('get_notes') }}',{_token:'{{ @csrf_token() }}', note_type: noteType}, function(data){
                $('#note_modal #note_modal_content').html(data);
                $('#note_modal').modal('show', {backdrop: 'static'});
            });
        }

        function addNote(noteId, noteType){
            var noteDescription = $('#note_description_'+ noteId).val();
            $('#'+noteType+'_note_id').val(noteId);
            $('#'+noteType+'_note').html(noteDescription);
            $('#'+noteType+'_note').addClass('border border-gray my-2 p-2');
            $('#note_modal').modal('hide');
        }
    </script>
@endsection

