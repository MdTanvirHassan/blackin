@extends('frontend.layouts.app')

@section('meta_title', $metaTitle)
@section('meta_description', $metaDescription)

@section('content')
@php
    $galleryImages = collect();
    if ($groupProduct->thumbnail_img) {
        $galleryImages->push(uploaded_asset($groupProduct->thumbnail_img));
    }
    foreach ($groupProduct->slots as $slot) {
        foreach ($slot->slotItems as $slotItem) {
            $image = optional($slotItem->product)->thumbnail_img;
            if ($image) {
                $galleryImages->push(uploaded_asset($image));
            }
        }
    }
    $galleryImages = $galleryImages->unique()->values();
    if ($galleryImages->isEmpty()) {
        $galleryImages->push(static_asset('assets/img/placeholder.jpg'));
    }
    $bundleDealCopy = str_replace(
        [':paid', ':free'],
        [$groupProduct->buy_quantity, $groupProduct->free_quantity],
        translate('Buy :paid, get :free free slots')
    );
@endphp

<section class="py-5 bg-light border-bottom">
    <div class="container bundle-page-container">
        <form
            action="{{ route('group_products.add_to_cart', filled($groupProduct->slug) ? $groupProduct->slug : $groupProduct->id) }}"
            method="POST"
            id="group-bundle-form"
            data-bundle-discount-type="{{ $groupDiscountMeta['active'] ? $groupDiscountMeta['type'] : '' }}"
            data-bundle-discount-value="{{ $groupDiscountMeta['active'] ? $groupDiscountMeta['value'] : 0 }}"
            data-currency-symbol="{{ $currencySymbol }}"
        >
            @csrf
            <input type="hidden" name="action_type" id="bundle-action-type" value="add_to_cart">
            <div class="row align-items-start gy-4">
                <div class="col-lg-6">
                    <div class="bundle-gallery-elegant">
                        <div class="bundle-gallery-thumbs-elegant">
                            <button type="button" class="thumb-nav-elegant" data-thumb-nav="prev" aria-label="{{ translate('Previous thumbnails') }}">
                                <i class="las la-chevron-up thumb-nav-icon-vertical"></i>
                                <i class="las la-chevron-left thumb-nav-icon-horizontal d-none"></i>
                            </button>
                            <div class="thumbs-track-elegant" id="bundle-thumb-track">
                                @foreach($galleryImages as $index => $image)
                                    <button type="button"
                                            class="thumb-elegant {{ $loop->first ? 'is-active' : '' }}"
                                            data-target="#bundle-main-image"
                                            data-image="{{ $image }}"
                                            data-index="{{ $index }}">
                                        <img src="{{ $image }}" alt="{{ $groupProduct->name }}">
                                    </button>
                                @endforeach
                            </div>
                            <button type="button" class="thumb-nav-elegant" data-thumb-nav="next" aria-label="{{ translate('Next thumbnails') }}">
                                <i class="las la-chevron-down thumb-nav-icon-vertical"></i>
                                <i class="las la-chevron-right thumb-nav-icon-horizontal d-none"></i>
                            </button>
                        </div>
                        <div class="bundle-gallery-main-elegant">
                            <div class="bundle-main-stage-elegant" id="bundle-main-stage">
                                <img id="bundle-main-image"
                                     src="{{ $galleryImages->first() }}"
                                     data-zoom="{{ $galleryImages->first() }}"
                                     alt="{{ $groupProduct->name }}"
                                     class="bundle-main-image-elegant">
                                <div class="bundle-zoom-pane" id="bundle-zoom-pane"></div>
                                <button type="button" class="bundle-fullscreen-trigger-elegant" id="bundle-fullscreen-trigger" aria-label="{{ translate('View fullscreen') }}">
                                    <i class="las la-expand-arrows-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6"> 
                    <div class="card shadow-sm rounded border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                <span class="badge badge-inline badge-soft-primary text-uppercase fs-10">{{ translate('Bundle Offer') }}</span>
                                <!-- <span class="text-muted">#{{ $groupProduct->id }}</span> -->
                            </div>
                            <h1 class="fs-20 fw-700 mb-2">{{ $groupProduct->name }}</h1>
                            @php
                                // Independent calculation for price breakdown - isolated from other sections
                                $breakdownBasePrice = 0;
                                $breakdownDiscountedPrice = 0;
                                foreach ($groupProduct->slots as $slot) {
                                    $slotProducts = $slot->slotItems;
                                    $avgPrice = $slotProducts->count() > 0 
                                        ? $slotProducts->average(function ($item) { return optional($item->product)->unit_price ?? 0; })
                                        : 0;
                                    
                                    if ($avgPrice > 0) {
                                        $breakdownBasePrice += $avgPrice;
                                        
                                        $discountedPrice = $avgPrice;
                                        if ($slot->discount_type === 'percentage') {
                                            $discountedPrice = $avgPrice * (1 - ($slot->discount_value / 100));
                                        } elseif ($slot->discount_type === 'flat') {
                                            $discountedPrice = max(0, $avgPrice - $slot->discount_value);
                                        }
                                        
                                        if (!$slot->is_free) {
                                            $breakdownDiscountedPrice += $discountedPrice;
                                        }
                                    }
                                }
                            @endphp
                            <div class="row g-3">
                                <div class="bundle-price-breakdown mb-3 col-auto">
                                    <span class="text-muted d-block fs-11 mb-1">{{ translate('Base Price') }}</span>
                                    <span class="text-danger text-decoration-line-through fs-14 fw-700">{{ $currencySymbol }}{{ number_format($breakdownBasePrice, 2) }}</span>
                                </div>
                                <div class="bundle-price-breakdown mb-3 col-auto">                                    
                                    <span class="text-muted d-block fs-11 mb-1">{{ translate('Discounted Price') }}</span>
                                    <span class="fs-14 fw-700" style="color: var(--primary);">{{ $currencySymbol }}{{ number_format($breakdownDiscountedPrice, 2) }}</span>
                                </div>
                                <div class="col-auto bundle-price-chip {{ $breakdownBasePrice > $breakdownDiscountedPrice ? '' : 'd-none' }} mb-2" id="bundle-save-chip-wrapper">
                                    <span class="text-uppercase fs-10">{{ translate('Save') }}</span>
                                    <strong class="fs-12">{{ $currencySymbol }}{{ number_format(max(0, $breakdownBasePrice - $breakdownDiscountedPrice), 2) }}</strong>
                                </div>
                            </div>

                            <div class="bundle-selection-wrapper mt-4 mb-4">
                                <h5 class="fs-14 fw-600 mb-2">{{ translate('Customize Your Bundle') }}</h5>
                                @php
                                    $allSlots = $groupProduct->slots;
                                @endphp
                                <div class="slot-group">
                                    <div class="row g-2">
                                        @foreach($allSlots as $slot)
                                            @php
                                                $slotIndex = $loop->iteration;
                                                $slotLabel = $slot->name ?: ($slot->is_free ? str_replace(':number', $slotIndex, translate('Free Slot :number')) : str_replace(':number', $slotIndex, translate('Slot :number')));
                                            @endphp
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                                                <div class="bundle-slot-card slot-open-trigger"
                                                    data-slot="{{ $slot->id }}"
                                                    data-slot-type="{{ $slot->is_free ? 'free' : 'paid' }}">
                                                    <!-- Empty State -->
                                                    <div class="slot-empty-state" data-slot-empty-state>
                                                        <div class="slot-empty-icon">
                                                            <i class="las la-plus-circle"></i>
                                                        </div>
                                                        <p class="slot-empty-text" data-slot-summary-text>{{ translate('Not selected') }}</p>
                                                        <button type="button"
                                                                class="slot-modal-trigger slot-action-btn-elegant"
                                                                data-slot="{{ $slot->id }}">
                                                            <i class="las la-search"></i>
                                                            <span>{{ translate('Select Product') }}</span>
                                                        </button>
                                                    </div>
                                                    
                                                    <!-- Filled State -->
                                                    <div class="slot-filled-state d-none" data-slot-filled-state>
                                                        <div class="slot-image-container">
                                                            <div class="slot-preview-wrapper" data-slot-preview-wrapper>
                                                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $slotLabel }}" data-slot-preview-image>
                                                                <div class="slot-image-overlay">
                                                                    <button type="button" class="slot-change-btn" data-slot="{{ $slot->id }}">
                                                                        <i class="las la-edit"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="slot-product-info">
                                                            <h6 class="slot-product-name" data-slot-summary-text>{{ translate('Not selected') }}</h6>
                                                            <div class="slot-variant-info" data-slot-summary-meta></div>
                                                            <div class="slot-price-info" data-slot-price-meta>
                                                                <div class="slot-price-row">
                                                                    <span class="slot-price-label">{{ translate('Base') }}:</span>
                                                                    <span class="slot-price-base price-strike" data-slot-price-base>{{ $currencySymbol }}0.00</span>
                                                                </div>
                                                                <div class="slot-price-row">
                                                                    <span class="slot-price-label">{{ translate('Price') }}:</span>
                                                                    <span class="slot-price-final" data-slot-price-discount>{{ $currencySymbol }}0.00</span>
                                                                </div>
                                                            </div>
                                                            <button type="button"
                                                                    class="slot-clear-selection-elegant"
                                                                    data-slot="{{ $slot->id }}">
                                                                <i class="las la-trash-alt"></i>
                                                                <span>{{ translate('Remove') }}</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Hidden variant field that always stays in form -->
                                                    <input type="hidden" name="slots[{{ $slot->id }}][variant]" class="slot-variant-input-form" data-slot="{{ $slot->id }}" value="">
                                                    <div class="slot-modal-anchor" data-slot-modal-anchor="{{ $slot->id }}">
                                                        <div class="slot-picker-hidden d-none" id="slot-picker-{{ $slot->id }}">
                                                            @include('frontend.group_products.slot-picker', ['slot' => $slot, 'isFree' => (bool)$slot->is_free])
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="bundle-selection-overview mb-4 no-scroll">
                                @if($paidSlots->count())
                                    @php
                                        $slotIndex = 1;
                                    @endphp
                                    <div class="slot-group-header mb-2">
                                        <h6 class="fs-11 mb-0 text-uppercase text-muted">{{ translate('Required Slots') }}</h6>
                                    </div>
                                    @foreach($paidSlots as $slot)
                                        @php
                                            $slotLabel = $slot->name ?: str_replace(':number', $slotIndex++, translate('Slot :number'));
                                        @endphp
                                        <div class="bundle-summary-card is-empty bundle-summary-card--required"
                                             data-slot="{{ $slot->id }}"
                                             data-required="1"
                                             role="button"
                                             tabindex="0"
                                             data-summary-trigger="{{ $slot->id }}">
                                            <div class="bundle-summary-header d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong>{{ $slotLabel }}</strong>
                                                    <small style="width: 100px;" class="text-muted d-block">{{ translate('Required') }}</small>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <span style="width: 100px;" class="badge badge-soft-danger" data-slot-required-badge>
                                                        {{ translate('Required') }}
                                                    </span>
                                                    <button type="button"
                                                            class="slot-clear-selection slot-clear-selection--summary d-none"
                                                            data-slot="{{ $slot->id }}">
                                                        <i class="las la-times"></i>
                                                        <span style="width: 100px;" class="badge badge-soft-danger">{{ translate('Unselect') }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="bundle-summary-body">
                                                <div class="bundle-summary-thumb" data-slot-summary-thumb>
                                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $slotLabel }}" class="d-none" data-slot-summary-image>
                                                    <span class="bundle-summary-thumb-placeholder" data-slot-summary-placeholder>
                                                        <i class="las la-image"></i>
                                                    </span>
                                                </div>
                                                <div class="bundle-summary-details">
                                                    <p class="bundle-summary-name bundle-summary-value mb-1" data-slot-summary-text>{{ translate('Not selected') }}</p>
                                                    <div class="bundle-summary-meta-group">
                                                        <div class="bundle-summary-meta d-none" data-slot-summary-meta></div>
                                                        <div class="bundle-summary-price bundle-summary-meta d-none" data-slot-summary-price>
                                                            <small class="text-danger">{{ translate('Base price') }}:
                                                                <span class="price-strike text-danger" data-slot-summary-base>{{ $currencySymbol }}0.00</span>
                                                            </small>
                                                            <small>{{ translate('Discounted price') }}:
                                                                <span data-slot-summary-discount>{{ $currencySymbol }}0.00</span>
                                                            </small>
                                                            <small>{{ translate('Qty') }}:
                                                                <span data-slot-summary-qty>1</span>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                @if($freeSlots->count())
                                    <div class="slot-group-divider my-3"></div>
                                    <div class="slot-group-header mb-2">
                                        <h6 class="fs-11 mb-0 text-uppercase text-muted">{{ translate('Free Slots') }}</h6>
                                    </div>
                                    @php $freeIndex = 1; @endphp
                                    @foreach($freeSlots as $slot)
                                        @php
                                            $slotLabel = $slot->name ?: str_replace(':number', $freeIndex++, translate('Free Slot :number'));
                                        @endphp
                                        <div class="bundle-summary-card is-empty bundle-summary-card--optional"
                                             data-slot="{{ $slot->id }}"
                                             data-required="0"
                                             role="button"
                                             tabindex="0"
                                             data-summary-trigger="{{ $slot->id }}">
                                            <div class="bundle-summary-header d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong>{{ $slotLabel }}</strong>
                                                    <small class="text-muted d-block">{{ translate('Optional') }}</small>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <span style="width: 100px;" class="badge badge-soft-primary" data-slot-required-badge>
                                                        {{ translate('Free slot') }}
                                                    </span>
                                                    <button type="button"
                                                            class="slot-clear-selection slot-clear-selection--summary d-none"
                                                            data-slot="{{ $slot->id }}">
                                                        <i class="las la-times"></i>
                                                        <span style="width: 100px;" class="badge badge-soft-danger">{{ translate('Unselect') }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="bundle-summary-body">
                                                <div class="bundle-summary-thumb" data-slot-summary-thumb>
                                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="{{ $slotLabel }}" class="d-none" data-slot-summary-image>
                                                    <span class="bundle-summary-thumb-placeholder" data-slot-summary-placeholder>
                                                        <i class="las la-image"></i>
                                                    </span>
                                                </div>
                                                <div class="bundle-summary-details">
                                                    <p class="bundle-summary-name bundle-summary-value mb-1" data-slot-summary-text>{{ translate('Not selected') }}</p>
                                                    <div class="bundle-summary-meta-group">
                                                        <div class="bundle-summary-meta d-none" data-slot-summary-meta></div>
                                                        <div class="bundle-summary-price bundle-summary-meta d-none" data-slot-summary-price>
                                                            <small class="text-danger">{{ translate('Base price') }}:
                                                                <span class="price-strike text-danger" data-slot-summary-base>{{ $currencySymbol }}0.00</span>
                                                            </small>
                                                            <small>{{ translate('Discounted price') }}:
                                                                <span data-slot-summary-discount>{{ $currencySymbol }}0.00</span>
                                                            </small>
                                                            <small>{{ translate('Qty') }}:
                                                                <span data-slot-summary-qty>1</span>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div> -->
                            <div class="mb-3">
                                <label for="bundle_quantity" class="bold-text form-label fs-13">{{ translate('Bundle Quantity') }}</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary qty-btn" type="button" data-qty-action="decrease">-</button>
                                    <input type="number"
                                           name="bundle_quantity"
                                           id="bundle_quantity"
                                           class="form-control @error('bundle_quantity') is-invalid @enderror text-center"
                                           value="{{ old('bundle_quantity', 1) }}"
                                           min="1">
                                    <button class="btn btn-outline-secondary qty-btn" type="button" data-qty-action="increase">+</button>
                                </div>
                                @error('bundle_quantity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <ul class="list-unstyled mb-3 bundle-summary-list">
                                <li class="d-flex justify-content-between fs-12">
                                    <span class="text-danger">{{ translate('Base price') }}</span>
                                    <strong class="text-danger text-decoration-line-through" id="bundle-base-total">{{ $currencySymbol }}0.00</strong>
                                </li>
                                <li class="d-flex justify-content-between fs-12">
                                    <span>{{ translate('Paid value') }}</span>
                                    <strong id="bundle-paid-total">{{ $currencySymbol }}0.00</strong>
                                </li>
                                <li class="d-flex justify-content-between text-success fs-12">
                                    <span>{{ translate('Free value') }}</span>
                                    <strong id="bundle-free-total">{{ $currencySymbol }}0.00</strong>
                                </li>
                                <li class="d-flex justify-content-between text-primary fs-12">
                                    <span>{{ translate('Bundle savings') }}</span>
                                    <strong id="bundle-discount-total">{{ $currencySymbol }}0.00</strong>
                                </li>
                                <li class="d-flex justify-content-between border-top pt-2 mt-2 fs-13 fw-700">
                                    <span>{{ translate('Total due') }}</span>
                                    <strong id="bundle-grand-total">{{ $currencySymbol }}0.00</strong>
                                </li>
                            </ul>
                            @error('bundle')
                                <div class="alert alert-danger py-2">{{ $message }}</div>
                            @enderror
                            <div class="alert alert-warning d-none" id="bundle-action-warning">
                                {{ translate('Please resolve the highlighted selections before continuing.') }}
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit"
                                        class="btn btn-dark btn-sm text-uppercase rounded-0 bundle-action-trigger fs-12"
                                        data-action="add_to_cart">
                                    {{ translate('Add to Cart') }}
                                </button>
                                <button type="submit"
                                        class="btn btn-outline-dark btn-sm text-uppercase rounded-0 bundle-action-trigger fs-12"
                                        data-action="buy_now">
                                    {{ translate('Buy Now') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<section class="py-5 bg-white border-top">
    <div class="container bundle-page-container">
        <div class="bundle-details-tabs">
            <ul class="nav nav-tabs" id="bundleDetailsTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="bundle-desc-tab" data-bs-toggle="tab" data-bs-target="#bundle-desc" type="button" role="tab">
                        {{ translate('Product Details') }}
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="bundle-slot-tab" data-bs-toggle="tab" data-bs-target="#bundle-slot" type="button" role="tab">
                        {{ translate('Slot Overview') }}
                    </button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0 rounded-bottom shadow-sm bg-white">
                <div class="tab-pane fade show active" id="bundle-desc" role="tabpanel">
                    {!! $groupProduct->description !!}
                </div>
                <div class="tab-pane fade" id="bundle-slot" role="tabpanel">
                    @php
                        $orderedSlots = $groupProduct->slots->sortBy(function ($slot) {
                            return $slot->is_free ? 1 : 0;
                        })->values();
                    @endphp
                    <div class="slot-overview-timeline">
                        @foreach($orderedSlots as $slotIndex => $slot)
                            @php
                                $slotLabel = $slot->name ?: str_replace(':number', $slotIndex + 1, translate('Slot :number'));
                                $topProducts = $slot->slotItems->filter(fn($item) => $item->product)->take(3);
                                $remainingCount = max($slot->slotItems->count() - $topProducts->count(), 0);
                            @endphp
                            <article class="slot-overview-row">
                                <div class="slot-overview-marker">
                                    <span class="slot-marker-index">{{ str_pad($slotIndex + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    @if(!$loop->last)
                                        <span class="slot-marker-line"></span>
                                    @endif
                                </div>
                                <div class="slot-overview-card"
                                     data-slot="{{ $slot->id }}"
                                     data-slot-overview-trigger
                                     role="button"
                                     tabindex="0">
                                    <div class="slot-overview-header">
                                        <div>
                                            <h5 class="fs-14 mb-1">{{ $slotLabel }}</h5>
                                            <small class="fs-11 text-muted">{{ $slot->is_free ? translate('Bonus pick') : translate('Required pick') }}</small>
                                        </div>
                                        <span class="slot-type-chip {{ $slot->is_free ? 'slot-type-chip--free' : 'slot-type-chip--paid' }}">
                                            {{ $slot->is_free ? translate('Free slot') : translate('Paid slot') }}
                                        </span>
                                    </div>
                                    <div class="slot-overview-meta">
                                        <span>{{ translate('Products assigned') }}: <strong>{{ $slot->slotItems->count() }}</strong></span>
                                        <span>{{ translate('Structure') }}: <strong>{{ $slot->is_free ? translate('Optional') : translate('Mandatory') }}</strong></span>
                                    </div>
                                    @if($topProducts->count())
                                        <div class="slot-overview-products">
                                            @foreach($topProducts as $productItem)
                                                <span class="slot-product-pill">
                                                    {{ \Illuminate\Support\Str::limit(optional($productItem->product)->getTranslation('name'), 32) }}
                                                </span>
                                            @endforeach
                                            @if($remainingCount)
                                                <span class="slot-product-pill slot-product-pill--more">
                                                    +{{ $remainingCount }} {{ translate('more') }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="slot-overview-cta">
                                        <button type="button"
                                                class="slot-overview-select-btn"
                                                data-slot="{{ $slot->id }}">
                                            <span>{{ translate('Customize this slot') }}</span>
                                            <i class="las la-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="slotSelectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header slot-modal-header-elegant">
                <div class="slot-modal-header-content">
                    <h5 class="modal-title slot-modal-title-elegant" id="slotModalTitle">{{ translate('Select Products') }}</h5>
                    <p class="modal-subtitle slot-modal-subtitle-elegant mb-0" id="slotModalSubtitle"></p>
                </div>
                <button type="button" class="btn slot-modal-close-elegant slot-modal-close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body modal-slot-body p-4">
            </div>
        </div>
    </div>
</div>

<div class="modal fade bundle-gallery-modal" id="bundleGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog bundle-modal-dialog-elegant">
        <div class="modal-content bundle-modal-content-elegant">
            <div class="modal-body bundle-modal-body-elegant p-0">
                <button type="button" class="bundle-modal-close-elegant" data-bs-dismiss="modal" data-dismiss="modal" aria-label="{{ translate('Close') }}">
                    <i class="las la-times"></i>
                </button>
                <div class="bundle-modal-layout-elegant">
                    <!-- Left Thumbnails Column -->
                    <div class="bundle-modal-thumbs-column">
                        <div class="bundle-modal-thumbs-track" id="bundle-modal-thumb-row">
                            @foreach($galleryImages as $index => $image)
                                <button type="button"
                                        class="bundle-modal-thumb-elegant {{ $loop->first ? 'is-active' : '' }}"
                                        data-index="{{ $index }}"
                                        data-image="{{ $image }}">
                                    <img src="{{ $image }}" alt="{{ $groupProduct->name }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <!-- Right Main Image Area -->
                    <div class="bundle-modal-main-area">
                        <div class="bundle-modal-stage-wrapper-elegant" id="bundle-modal-stage-wrapper">
                            <img id="bundle-modal-image"
                                 src="{{ $galleryImages->first() }}"
                                 alt="{{ $groupProduct->name }}"
                                 class="bundle-modal-stage-elegant">
                        </div>
                        <div class="bundle-modal-counter-elegant" id="bundle-modal-counter">
                            1 / {{ $galleryImages->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Elegant Minimalistic Gallery Design */
    .bundle-gallery-elegant{
        display:flex;
        gap:1.5rem;
        align-items:flex-start;
    }
    .bundle-page-container{
        max-width:1980px;
    }
    .bundle-gallery-thumbs-elegant{
        display:flex;
        flex-direction:column;
        gap:0.75rem;
        align-items:center;
        width:90px;
        flex-shrink:0;
    }
    .thumbs-track-elegant{
        display:flex;
        flex-direction:column;
        gap:0.75rem;
        max-height:500px;
        overflow-y:auto;
        overflow-x:hidden;
        padding:0.25rem;
        scrollbar-width:thin;
        scrollbar-color:rgba(0,0,0,0.2) transparent;
    }
    .thumbs-track-elegant::-webkit-scrollbar{
        width:4px;
    }
    .thumbs-track-elegant::-webkit-scrollbar-track{
        background:transparent;
    }
    .thumbs-track-elegant::-webkit-scrollbar-thumb{
        background:rgba(0,0,0,0.2);
        border-radius:2px;
    }
    .thumb-elegant{
        border:2px solid transparent;
        border-radius:0.5rem;
        padding:0.25rem;
        background:#fff;
        transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor:pointer;
        position:relative;
        overflow:hidden;
        flex-shrink:0;
        display:block;
    }
    .thumb-elegant::before{
        content:'';
        position:absolute;
        inset:0;
        border-radius:0.5rem;
        padding:2px;
        background:linear-gradient(135deg, var(--primary), rgba(var(--primary-rgb), 0.6));
        -webkit-mask:linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite:xor;
        mask-composite:exclude;
        opacity:0;
        transition:opacity 0.3s ease;
    }
    .thumb-elegant:hover{
        transform:translateY(-2px);
        box-shadow:0 4px 12px rgba(0,0,0,0.1);
    }
    .thumb-elegant.is-active{
        border-color:var(--primary);
        box-shadow:0 0 0 2px rgba(var(--primary-rgb), 0.1), 0 4px 12px rgba(0,0,0,0.1);
    }
    .thumb-elegant.is-active::before{
        opacity:1;
    }
    .thumb-elegant img{
        width:70px;
        height:70px;
        min-width:70px;
        min-height:70px;
        object-fit:cover;
        border-radius:0.35rem;
        display:block;
        transition:transform 0.3s ease;
        background:#f0f0f0;
    }
    .thumb-elegant:hover img{
        transform:scale(1.05);
    }
    .bundle-gallery-main-elegant{
        flex:1;
        min-width:0;
    }
    .bundle-main-stage-elegant{
        position:relative;
        border-radius:1rem;
        overflow:visible;
        background:#f8f9fa;
        width:100%;
        aspect-ratio:1;
        min-height:400px;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:2rem;
        box-sizing:border-box;
    }
    .bundle-main-image-elegant{
        max-width:calc(100% - 4rem);
        max-height:calc(100% - 4rem);
        width:auto;
        height:auto;
        object-fit:contain;
        display:block;
        transition:transform 0.3s ease;
        position:relative;
        z-index:1;
    }
    .bundle-zoom-pane{
        position:absolute;
        top:1.5rem;
        right:1.5rem;
        width:200px;
        height:200px;
        border:2px solid rgba(255,255,255,0.95);
        border-radius:0.75rem;
        background-repeat:no-repeat;
        background-size:260% 260%;
        box-shadow:0 20px 40px rgba(0,0,0,0.25);
        opacity:0;
        pointer-events:none;
        transition:opacity 0.2s ease;
        z-index:10;
    }
    .bundle-zoom-pane.is-visible{
        opacity:1;
    }
    .bundle-fullscreen-trigger-elegant{
        position:absolute;
        bottom:1.5rem;
        right:1.5rem;
        width:44px;
        height:44px;
        border-radius:50%;
        background:rgba(255,255,255,0.95);
        backdrop-filter:blur(10px);
        border:1px solid rgba(0,0,0,0.08);
        color:var(--dark);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1.1rem;
        transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor:pointer;
        z-index:5;
        box-shadow:0 4px 12px rgba(0,0,0,0.1);
    }
    .bundle-fullscreen-trigger-elegant:hover{
        background:var(--primary);
        color:#fff;
        transform:scale(1.1);
        box-shadow:0 6px 20px rgba(var(--primary-rgb), 0.4);
    }
    .thumb-nav-elegant{
        border:none;
        background:rgba(255,255,255,0.9);
        backdrop-filter:blur(8px);
        width:40px;
        height:40px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        color:var(--dark);
        transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor:pointer;
        box-shadow:0 2px 8px rgba(0,0,0,0.08);
        flex-shrink:0;
    }
    .thumb-nav-elegant:hover{
        background:var(--primary);
        color:#fff;
        transform:scale(1.1);
        box-shadow:0 4px 12px rgba(var(--primary-rgb), 0.3);
    }
    .thumb-nav-elegant:active{
        transform:scale(0.95);
    }
    .thumb-nav-elegant i{
        font-size:0.9rem;
    }
    .thumb-nav-icon-vertical{
        display:block;
    }
    .thumb-nav-icon-horizontal{
        display:none;
    }
    
    /* Responsive Gallery Styles */
    @media (max-width:991.98px){
        .bundle-gallery-elegant{
            flex-direction:column;
            gap:1rem;
        }
        .bundle-gallery-thumbs-elegant{
            flex-direction:row;
            width:100%;
            order:2;
            justify-content:center;
        }
        .thumb-nav-icon-vertical{
            display:none !important;
        }
        .thumb-nav-icon-horizontal{
            display:block !important;
        }
        .thumbs-track-elegant{
            flex-direction:row;
            max-height:none;
            max-width:100%;
            overflow-x:auto;
            overflow-y:hidden;
            padding:0.5rem;
        }
        .thumb-elegant img{
            width:60px;
            height:60px;
        }
        .bundle-gallery-main-elegant{
            order:1;
        }
        .bundle-main-stage-elegant{
            aspect-ratio:4/3;
            min-height:300px;
        }
    }
    @media (max-width:575.98px){
        .bundle-gallery-thumbs-elegant{
            gap:0.5rem;
        }
        .thumbs-track-elegant{
            gap:0.5rem;
        }
        .thumb-elegant img{
            width:50px;
            height:50px;
        }
        .thumb-nav-elegant{
            width:32px;
            height:32px;
        }
        .thumb-nav-elegant i{
            font-size:0.75rem;
        }
        .bundle-fullscreen-trigger-elegant{
            width:38px;
            height:38px;
            bottom:1rem;
            right:1rem;
            font-size:0.95rem;
        }
        .bundle-main-stage-elegant{
            border-radius:0.75rem;
            min-height:250px;
            padding:0.75rem;
        }
    }
    .bundle-price-chip{
        background:#ffe9ec;
        color:var(--primary);
        padding:0.4rem 0.9rem;
        border-radius:999px;
        font-weight:600;
    }
    .bundle-price-stack{
        display:flex;
        flex-direction:column;
    }
    .bundle-price-original{
        font-size:0.75rem;
    }
    .bundle-meta-list{
        list-style:none;
        padding:0;
        margin:0;
    }
    .bundle-meta-list li{
        margin-bottom:0.3rem;
        font-size:0.8rem;
    }
    .bundle-slot-grid{
        margin-top:0;
    }
    .bundle-slot-card{
        height:100%;
        border:2px dashed #e5e7eb;
        border-radius:0.75rem;
        padding:1rem;
        background:#fff;
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        box-shadow:0 2px 8px rgba(0,0,0,0.04);
        min-height:200px;
        overflow:hidden;
        word-wrap:break-word;
        transition:all .3s cubic-bezier(0.4, 0, 0.2, 1);
        position:relative;
        cursor:pointer;
    }
    .bundle-slot-card:hover{
        border-color:var(--primary);
        box-shadow:0 4px 16px rgba(var(--primary-rgb),0.15);
        transform:translateY(-2px);
    }
    .bundle-slot-card.is-filled{
        min-height:320px;
        padding:0;
        border:2px solid var(--primary) !important;
        box-shadow:0 8px 24px rgba(var(--primary-rgb),0.2), 0 2px 8px rgba(var(--primary-rgb),0.1);
        background:#fff;
        justify-content:flex-start;
        align-items:stretch;
    }
    .bundle-slot-card.is-filled:hover{
        transform:translateY(-4px);
        box-shadow:0 12px 32px rgba(var(--primary-rgb),0.25), 0 4px 12px rgba(var(--primary-rgb),0.15);
    }
    /* Empty State Styles */
    .slot-empty-state{
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        text-align:center;
        width:100%;
        padding:1.5rem 0.5rem;
    }
    .slot-empty-icon{
        width:64px;
        height:64px;
        border-radius:50%;
        background:linear-gradient(135deg, rgba(var(--primary-rgb),0.1), rgba(var(--primary-rgb),0.05));
        display:flex;
        align-items:center;
        justify-content:center;
        margin-bottom:1rem;
        transition:all .3s ease;
    }
    .slot-empty-icon i{
        font-size:2rem;
        color:var(--primary);
    }
    .bundle-slot-card:hover .slot-empty-icon{
        transform:scale(1.1);
        background:linear-gradient(135deg, rgba(var(--primary-rgb),0.15), rgba(var(--primary-rgb),0.08));
    }
    .slot-empty-text{
        font-size:0.75rem;
        font-weight:600;
        color:#6b7280;
        margin-bottom:1rem;
        line-height:1.4;
    }
    .slot-action-btn-elegant{
        border:2px solid var(--primary);
        background:var(--primary);
        color:#fff;
        font-weight:600;
        font-size:0.7rem;
        padding:0.5rem 1rem;
        border-radius:0.5rem;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:0.5rem;
        transition:all .3s ease;
        cursor:pointer;
    }
    .slot-action-btn-elegant:hover{
        background:transparent;
        color:var(--primary);
        transform:translateY(-2px);
        box-shadow:0 4px 12px rgba(var(--primary-rgb),0.3);
    }
    .slot-action-btn-elegant i{
        font-size:0.9rem;
    }
    
    /* Filled State Styles */
    .slot-filled-state{
        display:flex;
        flex-direction:column;
        height:100%;
        width:100%;
    }
    .slot-image-container{
        position:relative;
        width:100%;
        height:180px;
        overflow:hidden;
        background:#f9fafb;
    }
    .slot-preview-wrapper{
        width:100%;
        height:100%;
        position:relative;
    }
    .slot-preview-wrapper img{
        width:100%;
        height:100%;
        object-fit:cover;
        transition:transform .3s ease;
    }
    .bundle-slot-card.is-filled:hover .slot-preview-wrapper img{
        transform:scale(1.05);
    }
    .slot-image-overlay{
        position:absolute;
        top:0;
        left:0;
        right:0;
        bottom:0;
        background:rgba(0,0,0,0.4);
        display:flex;
        align-items:center;
        justify-content:center;
        opacity:0;
        transition:opacity .3s ease;
    }
    .bundle-slot-card.is-filled:hover .slot-image-overlay{
        opacity:1;
    }
    .slot-change-btn{
        background:rgba(255,255,255,0.95);
        border:none;
        color:var(--primary);
        width:40px;
        height:40px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        cursor:pointer;
        transition:all .3s ease;
        font-size:1.1rem;
    }
    .slot-change-btn:hover{
        background:#fff;
        transform:scale(1.1);
    }
    .slot-product-info{
        padding:1rem;
        flex:1;
        display:flex;
        flex-direction:column;
    }
    .slot-product-name{
        font-size:0.85rem;
        font-weight:700;
        color:#1f2937;
        margin-bottom:0.5rem;
        line-height:1.4;
        word-wrap:break-word;
        overflow-wrap:break-word;
    }
    .slot-variant-info{
        font-size:0.65rem;
        color:#6b7280;
        margin-bottom:0.75rem;
        font-weight:500;
        display:flex;
        flex-wrap:wrap;
        gap:0.75rem;
    }
    .slot-variant-info .variant-meta-line{
        display:inline-block;
    }
    .slot-price-info{
        margin-bottom:0.75rem;
        padding-top:0.75rem;
        border-top:1px solid #e5e7eb;
    }
    .slot-price-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:0.25rem;
        font-size:0.7rem;
    }
    .slot-price-row:last-child{
        margin-bottom:0;
    }
    .slot-price-label{
        color:#6b7280;
        font-weight:500;
    }
    .slot-price-base{
        color:#ef4444;
        text-decoration:line-through;
        font-weight:600;
    }
    .slot-price-final{
        color:var(--primary);
        font-weight:700;
        font-size:0.8rem;
    }
    .slot-clear-selection-elegant{
        border:1px solid #ef4444;
        background:#ef4444;
        color:#fff;
        font-size:0.65rem;
        font-weight:600;
        padding:0.4rem 0.75rem;
        border-radius:0.5rem;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:0.4rem;
        transition:all .3s ease;
        cursor:pointer;
        margin-top:auto;
        width:100%;
    }
    .slot-clear-selection-elegant:hover{
        background:#dc2626;
        color:#fff;
        border-color:#dc2626;
        transform:translateY(-2px);
        box-shadow:0 4px 12px rgba(220,38,38,0.4);
    }
    .slot-clear-selection-elegant i{
        font-size:0.8rem;
    }
    
    .fs-9{
        font-size:0.65rem !important;
    }
    .bundle-slot-card.is-empty{
        border-color:rgba(231, 76, 60,0.3);
        box-shadow:0 15px 35px rgba(231, 76, 60,0.18);
    }
    .slot-group-header h6{
        letter-spacing:0.05em;
    }
    .slot-group-divider{
        border-top:1px dashed #e5e7eb;
    }
    .bundle-selection-overview{
        border:1px dashed #e5e7eb;
        border-radius:0.75rem;
        padding:1rem;
        max-height:260px;
        overflow-y:auto;
    }
    .bundle-selection-overview.no-scroll{
        max-height:none;
        overflow:visible;
    }
    .bundle-summary-card{
        border:1px solid #f1f1f5;
        border-radius:0.75rem;
        padding:0.6rem 0.85rem;
        margin-bottom:0.6rem;
        transition:all .2s ease;
        background:#fff;
    }
    .bundle-summary-card.is-empty{
        border-color:rgba(231,76,60,0.3);
        background:linear-gradient(135deg,rgba(231,76,60,0.05),rgba(231,76,60,0.02));
        box-shadow:0 10px 30px rgba(231,76,60,0.12);
    }
    .bundle-summary-card--required.is-empty{
        border-color:rgba(231,76,60,0.45);
    }
    .bundle-summary-card--required.is-filled{
        border-color:rgba(var(--primary-rgb),0.45);
    }
    .bundle-summary-card--optional.is-empty{
        border-color:rgba(37,99,235,0.2);
    }
    .bundle-summary-card--optional.is-filled{
        border-color:rgba(37,99,235,0.4);
        box-shadow:0 12px 32px rgba(37,99,235,0.15);
    }
    .bundle-summary-card.is-filled{
        border-color:rgba(var(--primary-rgb),0.35);
        background:linear-gradient(135deg,rgba(var(--primary-rgb),0.08),rgba(var(--primary-rgb),0.02));
        box-shadow:0 12px 32px rgba(var(--primary-rgb),0.18);
    }
    .bundle-summary-header{
        margin-bottom:0.6rem;
    }
    .bundle-summary-body{
        display:flex;
        align-items:center;
        gap:0.85rem;
    }
    .bundle-summary-thumb{
        width:48px;
        height:48px;
        border-radius:0.75rem;
        background:#f7f7fa;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        flex-shrink:0;
        position:relative;
    }
    .bundle-summary-thumb img{
        width:100%;
        height:100%;
        object-fit:cover;
    }
    .bundle-summary-thumb-placeholder{
        font-size:1rem;
        color:#c7c7cf;
    }
    .bundle-summary-details{
        flex:1 1 auto;
    }
    .bundle-summary-value{
        display:inline-flex;
        align-items:center;
        gap:0.35rem;
        white-space:normal;
        text-align:left;
    }
    .bundle-summary-card.is-empty .bundle-summary-value{
        color:#b4231e;
    }
    .bundle-summary-card.is-filled .bundle-summary-value{
        color:var(--primary);
    }
    .slot-glance-preview{
        margin-top:0.75rem;
        margin-bottom:0.5rem;
    }
    .slot-glance-meta{
        display:flex;
        flex-direction:column;
        gap:0.1rem;
        font-size:0.7rem;
        color:#6b7280;
    }
    .slot-glance-meta .variant-meta-line{
        display:block;
    }
    .slot-glance-preview img{
        width:64px;
        height:64px;
        border-radius:0.75rem;
        object-fit:cover;
        box-shadow:0 10px 25px rgba(0,0,0,0.12);
        transition:all .3s ease;
    }
    /* Bigger image and elegant design for filled cards */
    .bundle-slot-card.is-filled .slot-glance-preview{
        margin-top:0.5rem;
        margin-bottom:0.75rem;
    }
    .bundle-slot-card.is-filled .slot-glance-preview img{
        width:100%;
        height:140px;
        border-radius:0.5rem;
        object-fit:cover;
        box-shadow:0 8px 20px rgba(var(--primary-rgb), 0.25);
        border:2px solid rgba(var(--primary-rgb), 0.2);
    }
    .bundle-slot-card.is-filled .slot-glance-meta{
        font-size:0.55rem;
        font-weight:700;
        color:#1f2937;
        margin-top:0.25rem;
        margin-bottom:0.25rem;
    }
    .bundle-slot-card.is-filled .slot-price-meta{
        font-size:0.5rem;
        font-weight:700;
        color:var(--primary);
    }
    .bundle-slot-card.is-filled .slot-price-meta small{
        font-weight:700;
    }
    .bundle-slot-card.is-filled .slot-price-meta .price-strike{
        font-weight:600;
    }
    .bundle-slot-card.is-filled .slot-glance-pill{
        font-size:0.6rem;
        font-weight:700;
        padding:0.3rem 0.6rem;
        background:linear-gradient(135deg, rgba(var(--primary-rgb),0.2), rgba(var(--primary-rgb),0.1));
        color:var(--primary);
        border:1px solid rgba(var(--primary-rgb), 0.3);
    }
    .bundle-summary-name{
        font-weight:600;
        font-size:0.8rem;
        margin-bottom:0.1rem;
    }
    .bundle-summary-meta{
        display:flex;
        flex-direction:column;
        gap:0.1rem;
        font-size:0.75rem;
        color:#6b7280;
    }
    .bundle-summary-meta-group{
        display:flex;
        flex-direction:column;
        gap:0.2rem;
    }
    .bundle-summary-price small{
        display:block;
    }
    .price-strike{
        text-decoration:line-through;
    }
    .bundle-summary-meta .variant-meta-line{
        display:block;
    }
    .bundle-summary-list li{
        font-size:0.8rem;
    }
    .slot-product-card{
        border:1px solid #ececec;
        border-radius:1rem;
        padding:0;
        background:#fff;
        transition:all .3s ease;
        cursor:pointer;
        overflow:hidden;
        display:flex;
        flex-direction:column;
        height:100%;
        max-width:100%;
        margin-bottom:1rem;
    }
    .slot-product-card:hover{
        border-color:var(--primary);
        box-shadow:0 4px 16px rgba(var(--primary-rgb),0.15);
        transform:translateY(-2px);
    }
    .slot-product-card.is-active{
        border:2px solid var(--primary);
        box-shadow:0 8px 24px rgba(var(--primary-rgb),0.2);
    }
    .slot-product-card__image{
        width:100%;
        height:160px;
        overflow:hidden;
        background:#f9fafb;
        display:flex;
        align-items:center;
        justify-content:center;
    }
    .slot-product-card__image img{
        width:100%;
        height:100%;
        object-fit:contain;
        transition:transform .3s ease;
    }
    .slot-product-card:hover .slot-product-card__image img{
        transform:scale(1.05);
    }
    .slot-product-card__details{
        padding:0.4rem;
        flex:1;
        display:flex;
        flex-direction:column;
    }
    .slot-product-card__title{
        font-size:0.65rem;
        font-weight:700;
        color:#1f2937;
        margin-bottom:0.3rem;
        line-height:1.3;
        display:-webkit-box;
        -webkit-line-clamp:2;
        -webkit-box-orient:vertical;
        overflow:hidden;
        text-overflow:ellipsis;
        min-height:1.6em;
    }
    .slot-product-card__price{
        display:flex;
        flex-direction:column;
        gap:0.1rem;
        margin-bottom:0.4rem;
    }
    .slot-product-card__price-row{
        display:flex;
        align-items:center;
        gap:0.3rem;
    }
    .slot-product-card__price-label{
        font-size:0.55rem;
        font-weight:600;
        color:#6b7280;
        white-space:nowrap;
    }
    .slot-product-card__price-base{
        font-size:0.55rem;
        font-weight:700;
        color:var(--primary);
    }
    .slot-product-card__price-discounted{
        font-size:0.65rem;
        font-weight:700;
        color:var(--primary);
    }
    .variant-selection-group{
        margin-top:0;
        padding:0.4rem;
        border-top:1px solid #f3f4f6;
    }
    .variant-option-group{
        margin-bottom:0.5rem;
        display:flex;
        align-items:center;
        gap:0.4rem;
    }
    .variant-option-group:last-child{
        margin-bottom:0;
    }
    .variant-option-label{
        display:block;
        font-size:0.6rem;
        font-weight:600;
        color:#374151;
        margin-bottom:0;
        white-space:nowrap;
        flex-shrink:0;
    }
    .variant-size-buttons{
        display:flex;
        flex-wrap:wrap;
        gap:0.25rem;
    }
    .variant-size-btn{
        border:1px solid #e5e7eb;
        border-radius:0.25rem;
        background:#fff;
        color:#374151;
        font-size:0.6rem;
        font-weight:500;
        padding:0.25rem 0.5rem;
        min-width:28px;
        text-align:center;
        transition:all .2s ease;
        cursor:pointer;
    }
    .variant-size-btn:hover{
        border-color:var(--primary);
        color:var(--primary);
    }
    .variant-size-btn.is-active{
        border-color:var(--primary);
        background-color:var(--primary);
        color:#fff;
    }
    .variant-color-swatches{
        display:flex;
        flex-wrap:wrap;
        gap:0.25rem;
    }
    .variant-color-swatch{
        width:24px;
        height:24px;
        border:2px solid #e5e7eb;
        border-radius:0.25rem;
        padding:0;
        cursor:pointer;
        transition:all .2s ease;
        position:relative;
    }
    .variant-color-swatch:hover{
        border-color:var(--primary);
        transform:scale(1.1);
    }
    .variant-color-swatch.is-active{
        border-color:var(--primary);
        border-width:2px;
        box-shadow:0 0 0 1px rgba(var(--primary-rgb), 0.2);
    }
    .variant-pill-group{
        margin-top:0;
        padding-top:0.75rem;
        border-top:1px solid #f3f4f6;
        display:flex;
        flex-wrap:wrap;
        gap:0.5rem;
    }
    .variant-pill{
        border:1px solid #e5e7eb;
        border-radius:999px;
        background:#fff;
        padding:0.3rem 0.75rem;
        font-size:0.75rem;
        display:flex;
        flex-direction:column;
        align-items:flex-start;
        gap:0.1rem;
        min-width:100px;
    }
    .variant-pill small{
        font-weight:600;
        color:var(--dark);
    }
    .variant-pill.is-active{
        border-color:var(--primary);
        color:var(--primary);
        box-shadow:0 6px 20px rgba(0,0,0,0.08);
    }
    .variant-pill.is-disabled{
        opacity:0.45;
        cursor:not-allowed;
    }
    .variant-pill em{
        font-style:normal;
        color:#b4231e;
        font-size:0.65rem;
    }
    .qty-btn{
        width:44px;
        border-color:#e5e7eb !important;
        color:#6b7280 !important;
        background-color:#fff !important;
    }
    .qty-btn:hover{
        background-color:var(--primary) !important;
        color:#fff !important;
        border-color:var(--primary) !important;
    }
    .qty-btn:focus{
        box-shadow:0 0 0 0.2rem rgba(var(--primary-rgb), 0.25) !important;
        border-color:var(--primary) !important;
    }
    .slot-picker-hidden{
        min-height:300px;
    }
    .slot-preview-grid{
        display:flex;
        align-items:center;
        gap:0.5rem;
        flex-wrap:wrap;
    }
    .slot-preview-item{
        display:flex;
        align-items:center;
        gap:0.4rem;
        background:#f7f7f9;
        padding:0.35rem 0.6rem;
        border-radius:999px;
    }
    .slot-preview-item img{
        width:32px;
        height:32px;
        object-fit:cover;
        border-radius:50%;
    }
    .slot-preview-item small{
        font-size:0.65rem;
        max-width:100px;
        display:block;
        white-space:nowrap;
        text-overflow:ellipsis;
        overflow:hidden;
    }
    .slot-preview-more{
        font-size:0.75rem;
        font-weight:600;
        color:var(--primary);
    }
    .price-stack small{
        font-size:0.65rem;
        color:#8f8f98;
    }
    .slot-modal-body{
        max-height:70vh;
        overflow-y:auto;
    }
    .modal-subtitle{
        font-size:0.75rem;
    }
    
    /* Elegant Modal Header Design */
    #slotSelectionModal{
        transition:opacity 0.3s ease-out;
    }
    #slotSelectionModal .modal-content{
        border-radius:1.25rem;
        overflow:hidden;
        border:none;
        box-shadow:0 20px 60px rgba(0,0,0,0.15);
        transition:transform 0.3s ease-out, opacity 0.3s ease-out;
    }
    #slotSelectionModal.show .modal-dialog{
        transition:transform 0.3s ease-out;
    }
    #slotSelectionModal .slot-modal-header-elegant{
        background:#d43533 !important;
        background:var(--primary, #d43533) !important;
        background-color:var(--primary, #d43533) !important;
        padding:1.5rem 2rem !important;
        border:none !important;
        border-bottom:none !important;
        border-radius:0 !important;
        display:flex !important;
        align-items:center !important;
        justify-content:space-between !important;
        position:relative !important;
        min-height:auto !important;
        visibility:visible !important;
        opacity:1 !important;
    }
    #slotSelectionModal .slot-modal-header-elegant::after{
        content:'';
        position:absolute;
        bottom:0;
        left:0;
        right:0;
        height:3px;
        background:rgba(255,255,255,0.2);
        z-index:1;
    }
    #slotSelectionModal .slot-modal-header-content{
        flex:1;
        position:relative;
        z-index:2;
    }
    #slotSelectionModal .slot-modal-title-elegant{
        color:#fff !important;
        font-weight:700 !important;
        font-size:1.5rem !important;
        margin:0 0 0.5rem 0 !important;
        line-height:1.3 !important;
    }
    #slotSelectionModal .slot-modal-subtitle-elegant{
        color:rgba(255,255,255,0.9) !important;
        font-weight:500 !important;
        font-size:0.9rem !important;
        margin:0 !important;
        line-height:1.4 !important;
    }
    #slotSelectionModal .slot-modal-subtitle-elegant.text-muted{
        color:rgba(255,255,255,0.9) !important;
    }
    #slotSelectionModal .slot-modal-close-elegant{
        background:rgba(255,255,255,0.15) !important;
        border:2px solid rgba(255,255,255,0.3) !important;
        color:#fff !important;
        width:40px !important;
        height:40px !important;
        border-radius:50% !important;
        padding:0 !important;
        display:flex !important;
        align-items:center !important;
        justify-content:center !important;
        transition:all 0.3s ease;
        font-size:1.2rem !important;
        margin-left:1rem !important;
        flex-shrink:0 !important;
        position:relative !important;
        z-index:2 !important;
    }
    #slotSelectionModal .slot-modal-close-elegant:hover,
    #slotSelectionModal .slot-modal-close-elegant:focus{
        background:rgba(255,255,255,0.25) !important;
        border-color:rgba(255,255,255,0.5) !important;
        color:#fff !important;
        transform:scale(1.1);
        box-shadow:0 4px 12px rgba(0,0,0,0.2);
    }
    #slotSelectionModal .slot-modal-close-elegant i{
        line-height:1;
    }
    
    @media (max-width:767.98px){
        .slot-modal-header-elegant{
            padding:1.25rem 1.5rem;
        }
        .slot-modal-title-elegant{
            font-size:1.25rem;
        }
        .slot-modal-subtitle-elegant{
            font-size:0.85rem;
        }
        .slot-modal-close-elegant{
            width:36px;
            height:36px;
            font-size:1.1rem;
        }
    }
    @media (max-width:575.98px){
        .slot-modal-header-elegant{
            padding:1rem 1.25rem;
        }
        .slot-modal-title-elegant{
            font-size:1.1rem;
            margin-bottom:0.4rem;
        }
        .slot-modal-subtitle-elegant{
            font-size:0.8rem;
        }
        .slot-modal-close-elegant{
            width:32px;
            height:32px;
            font-size:1rem;
            margin-left:0.75rem;
        }
    }
    .price-stack small{
        font-size:0.75rem;
    }
    .price-stack small{
        font-size:0.75rem;
    }
    .bundle-details-tabs .nav-link{
        text-transform:uppercase;
        font-weight:600;
        letter-spacing:0.03em;
    }
    /* Elegant Gallery Modal Design */
    .bundle-modal-dialog-elegant{
        max-width:95vw;
        width:95vw;
        margin:2vh auto;
        height:auto;
    }
    .bundle-modal-content-elegant{
        background:#fff;
        border:none;
        border-radius:1rem;
        height:85vh;
        max-height:85vh;
        box-shadow:0 20px 60px rgba(0,0,0,0.3);
    }
    .bundle-modal-body-elegant{
        padding:0 !important;
        height:100%;
        max-height:85vh;
        display:flex;
        align-items:stretch;
        position:relative;
        overflow:hidden;
    }
    .bundle-modal-close-elegant{
        position:absolute;
        top:1.5rem;
        right:1.5rem;
        width:44px;
        height:44px;
        border-radius:50%;
        background:rgba(255,255,255,0.95);
        backdrop-filter:blur(10px);
        border:1px solid rgba(0,0,0,0.08);
        color:var(--dark);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1.2rem;
        transition:all 0.3s ease;
        cursor:pointer;
        z-index:100;
        box-shadow:0 4px 12px rgba(0,0,0,0.1);
    }
    .bundle-modal-close-elegant:hover{
        background:var(--primary);
        color:#fff;
        transform:scale(1.1);
        box-shadow:0 6px 20px rgba(var(--primary-rgb), 0.4);
    }
    .bundle-modal-layout-elegant{
        display:flex;
        width:100%;
        height:100%;
        gap:0;
    }
    .bundle-modal-thumbs-column{
        width:180px;
        flex-shrink:0;
        background:#f8f9fa;
        padding:1rem 0.75rem;
        display:flex;
        align-items:center;
        justify-content:center;
        border-right:1px solid #e5e7eb;
    }
    .bundle-modal-thumbs-track{
        display:flex;
        flex-direction:column;
        gap:0.6rem;
        max-height:calc(85vh - 4rem);
        overflow-y:auto;
        overflow-x:hidden;
        padding:0.5rem 0;
        scrollbar-width:thin;
        scrollbar-color:rgba(0,0,0,0.2) transparent;
    }
    .bundle-modal-thumbs-track::-webkit-scrollbar{
        width:4px;
    }
    .bundle-modal-thumbs-track::-webkit-scrollbar-track{
        background:transparent;
    }
    .bundle-modal-thumbs-track::-webkit-scrollbar-thumb{
        background:rgba(0,0,0,0.2);
        border-radius:2px;
    }
    .bundle-modal-thumb-elegant{
        border:2px solid transparent;
        border-radius:0.5rem;
        padding:0.25rem;
        background:#fff;
        transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor:pointer;
        position:relative;
        overflow:hidden;
        flex-shrink:0;
        display:block;
    }
    .bundle-modal-thumb-elegant::before{
        content:'';
        position:absolute;
        inset:0;
        border-radius:0.5rem;
        padding:2px;
        background:linear-gradient(135deg, var(--primary), rgba(var(--primary-rgb), 0.6));
        -webkit-mask:linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite:xor;
        mask-composite:exclude;
        opacity:0;
        transition:opacity 0.3s ease;
    }
    .bundle-modal-thumb-elegant:hover{
        transform:translateY(-2px);
        box-shadow:0 4px 12px rgba(0,0,0,0.1);
    }
    .bundle-modal-thumb-elegant.is-active{
        border-color:var(--primary);
        box-shadow:0 0 0 2px rgba(var(--primary-rgb), 0.1), 0 4px 12px rgba(0,0,0,0.1);
    }
    .bundle-modal-thumb-elegant.is-active::before{
        opacity:1;
    }
    .bundle-modal-thumb-elegant img{
        width:140px;
        height:140px;
        min-width:140px;
        min-height:140px;
        object-fit:cover;
        border-radius:0.35rem;
        display:block;
        transition:transform 0.3s ease;
        background:#f0f0f0;
    }
    .bundle-modal-thumb-elegant:hover img{
        transform:scale(1.05);
    }
    .bundle-modal-main-area{
        flex:1;
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        background:#fff;
        position:relative;
        padding:1rem;
    }
    .bundle-modal-stage-wrapper-elegant{
        position:relative;
        width:100%;
        height:100%;
        max-width:100%;
        max-height:calc(85vh - 4rem);
        display:flex;
        align-items:center;
        justify-content:center;
        background:#f8f9fa;
        border-radius:0.75rem;
        overflow:hidden;
        cursor:zoom-in;
        transition:all 0.3s ease;
    }
    .bundle-modal-stage-wrapper-elegant.is-zooming{
        cursor:zoom-out;
    }
    .bundle-modal-stage-elegant{
        max-width:100%;
        max-height:100%;
        width:auto;
        height:auto;
        object-fit:contain;
        display:block;
        transition:transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), filter 0.3s ease;
        transform-origin:center center;
    }
    .bundle-modal-stage-wrapper-elegant.is-zooming .bundle-modal-stage-elegant{
        transform:scale(2.5);
        filter:brightness(1.05);
    }
    .bundle-modal-counter-elegant{
        position:absolute;
        bottom:2rem;
        right:2rem;
        background:rgba(0,0,0,0.7);
        backdrop-filter:blur(10px);
        padding:0.5rem 1rem;
        border-radius:999px;
        font-size:0.85rem;
        color:#fff;
        font-weight:500;
        box-shadow:0 4px 12px rgba(0,0,0,0.2);
    }
    
    @media (max-width:991.98px){
        .bundle-modal-dialog-elegant{
            max-width:98vw;
            width:98vw;
            margin:1vh auto;
        }
        .bundle-modal-content-elegant{
            height:90vh;
            max-height:90vh;
        }
        .bundle-modal-body-elegant{
            max-height:90vh;
        }
        .bundle-modal-thumbs-column{
            width:150px;
            padding:0.75rem 0.5rem;
        }
        .bundle-modal-thumb-elegant img{
            width:110px;
            height:110px;
            min-width:110px;
            min-height:110px;
        }
        .bundle-modal-main-area{
            padding:0.75rem;
        }
        .bundle-modal-stage-wrapper-elegant{
            max-height:calc(90vh - 3rem);
        }
        .bundle-modal-thumbs-track{
            max-height:calc(90vh - 3rem);
        }
        .bundle-modal-counter-elegant{
            bottom:1rem;
            right:1rem;
            font-size:0.75rem;
            padding:0.4rem 0.8rem;
        }
    }
    @media (max-width:575.98px){
        .bundle-modal-dialog-elegant{
            max-width:100vw;
            width:100vw;
            margin:0;
        }
        .bundle-modal-content-elegant{
            height:100vh;
            max-height:100vh;
            border-radius:0;
        }
        .bundle-modal-body-elegant{
            max-height:100vh;
        }
        .bundle-modal-layout-elegant{
            flex-direction:column;
        }
        .bundle-modal-thumbs-column{
            width:100%;
            padding:0.75rem;
            border-right:none;
            border-bottom:1px solid #e5e7eb;
        }
        .bundle-modal-thumbs-track{
            flex-direction:row;
            max-height:none;
            overflow-x:auto;
            overflow-y:hidden;
            gap:0.6rem;
        }
        .bundle-modal-thumb-elegant img{
            width:60px;
            height:60px;
            min-width:60px;
            min-height:60px;
        }
        .bundle-modal-main-area{
            padding:0.75rem;
        }
        .bundle-modal-stage-wrapper-elegant{
            max-height:calc(100vh - 180px);
        }
    }
    .slot-overview-timeline{
        display:flex;
        flex-direction:column;
        gap:1.25rem;
    }
    .slot-overview-row{
        display:flex;
        gap:1.25rem;
        align-items:stretch;
    }
    .slot-overview-marker{
        width:56px;
        display:flex;
        align-items:center;
        flex-direction:column;
        position:relative;
    }
    .slot-marker-index{
        width:36px;
        height:36px;
        border-radius:999px;
        background:var(--primary);
        color:#fff;
        font-weight:600;
        font-size:0.85rem;
        display:flex;
        align-items:center;
        justify-content:center;
        box-shadow:0 10px 25px rgba(0,0,0,0.15);
        margin-bottom:0.3rem;
    }
    .slot-marker-line{
        flex:1;
        width:2px;
        background:linear-gradient(180deg, rgba(79,70,229,0.25), rgba(79,70,229,0));
        border-radius:999px;
    }
    .slot-overview-card{
        flex:1;
        border:1px solid #e5e7eb;
        border-radius:0.85rem;
        padding:1.25rem;
        background:#fff;
        box-shadow:0 15px 35px rgba(15,23,42,0.05);
        transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        cursor:pointer;
    }
    .slot-overview-card:hover{
        transform:translateY(-4px);
        border-color:rgba(79,70,229,0.6);
        box-shadow:0 25px 45px rgba(15,23,42,0.12);
    }
    .slot-overview-card:focus{
        outline:2px solid rgba(79,70,229,0.8);
        outline-offset:3px;
    }
    .slot-overview-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:0.85rem;
        margin-bottom:0.85rem;
    }
    .slot-type-chip{
        border-radius:999px;
        padding:0.25rem 0.7rem;
        font-size:0.75rem;
        font-weight:600;
        text-transform:uppercase;
        letter-spacing:0.05em;
    }
    .slot-type-chip--free{
        background:rgba(52,211,153,0.18);
        color:#047857;
    }
    .slot-type-chip--paid{
        background:rgba(59,130,246,0.15);
        color:#1d4ed8;
    }
    .slot-overview-meta{
        display:flex;
        flex-wrap:wrap;
        gap:1rem;
        font-size:0.8rem;
        color:#6b7280;
        margin-bottom:0.75rem;
    }
    .slot-overview-meta strong{
        color:#111827;
    }
    .slot-overview-products{
        display:flex;
        flex-wrap:wrap;
        gap:0.5rem;
        margin-bottom:1.25rem;
    }
    .slot-product-pill{
        padding:0.3rem 0.65rem;
        border-radius:0.6rem;
        background:#f3f4f6;
        font-size:0.75rem;
        color:#374151;
        max-width:180px;
        white-space:nowrap;
        text-overflow:ellipsis;
        overflow:hidden;
    }
    .slot-product-pill--more{
        background:#e0e7ff;
        color:#4338ca;
    }
    .slot-overview-cta{
        display:flex;
        justify-content:flex-end;
    }
    .slot-overview-select-btn{
        display:inline-flex;
        align-items:center;
        gap:0.35rem;
        border:none;
        background:var(--primary);
        color:#fff;
        font-weight:600;
        font-size:0.8rem;
        padding:0.5rem 1rem;
        border-radius:0.6rem;
        transition:transform .2s ease, box-shadow .2s ease;
    }
    .slot-overview-select-btn i{
        font-size:0.9rem;
    }
    .slot-overview-select-btn:hover{
        transform:translateX(4px);
        box-shadow:0 10px 20px rgba(79,70,229,0.35);
    }
    .bundle-details-tabs .nav-tabs{
        border-bottom:2px solid #e5e7eb;
    }
    .bundle-details-tabs .nav-link{
        border:none;
        border-bottom:3px solid transparent;
        color:#6b7280;
        padding:0.75rem 1.25rem;
        font-size:0.85rem;
        font-weight:500;
        transition:all .2s ease;
    }
    .bundle-details-tabs .nav-link:hover{
        color:var(--primary);
        border-bottom-color:rgba(var(--primary-rgb),0.3);
    }
    .bundle-details-tabs .nav-link.active{
        color:var(--primary);
        border-bottom-color:var(--primary);
        background:transparent;
    }
    /* Responsive styles for slot cards */
    @media (min-width: 1400px) {
        .bundle-slot-card{
            min-height:150px;
        }
        .bundle-slot-card.is-filled{
            min-height:300px;
        }
        .bundle-slot-card.is-filled .slot-glance-preview img{
            height:160px;
        }
        .slot-glance-title{
            font-size:0.8rem;
        }
    }
    @media (max-width: 1199.98px) {
        .bundle-slot-card{
            min-height:135px;
            padding:0.55rem;
        }
        .bundle-slot-card.is-filled{
            min-height:260px;
        }
        .bundle-slot-card.is-filled .slot-glance-preview img{
            height:130px;
        }
        .slot-glance-title{
            font-size:0.7rem;
        }
        .slot-glance-pill{
            font-size:0.6rem;
            padding:0.15rem 0.4rem;
        }
        .slot-action-btn{
            font-size:0.6rem;
            padding:0.2rem 0.4rem;
        }
    }
    @media (max-width: 991.98px) {
        .bundle-slot-card{
            min-height:180px;
            padding:0.75rem;
        }
        .bundle-slot-card.is-filled{
            min-height:280px;
        }
        .slot-image-container{
            height:160px;
        }
        .slot-product-info{
            padding:0.75rem;
        }
        .slot-product-name{
            font-size:0.75rem;
        }
        .slot-variant-info{
            font-size:0.6rem;
        }
        .slot-price-row{
            font-size:0.65rem;
        }
        .slot-price-final{
            font-size:0.7rem;
        }
        .slot-empty-icon{
            width:56px;
            height:56px;
        }
        .slot-empty-icon i{
            font-size:1.75rem;
        }
        .slot-action-btn-elegant{
            font-size:0.65rem;
            padding:0.4rem 0.85rem;
        }
        .slot-product-card__image{
            height:180px;
        }
        .slot-product-card__details{
            padding:0.45rem;
        }
        .slot-product-card__title{
            font-size:0.65rem;
            margin-bottom:0.35rem;
        }
        .slot-product-card__price{
            margin-bottom:0.4rem;
        }
        .slot-product-card__price-label{
            font-size:0.55rem;
        }
        .slot-product-card__price-base{
            font-size:0.55rem;
        }
        .slot-product-card__price-discounted{
            font-size:0.7rem;
        }
        .variant-selection-group{
            padding:0.45rem;
        }
        .variant-option-group{
            margin-bottom:0.5rem;
            gap:0.4rem;
        }
        .variant-option-label{
            font-size:0.6rem;
        }
        .variant-size-btn{
            font-size:0.6rem;
            padding:0.25rem 0.5rem;
            min-width:28px;
        }
        .variant-color-swatch{
            width:24px;
            height:24px;
        }
    }
        .slot-glance-selection{
            min-height:24px;
            font-size:0.65rem;
            overflow:visible;
        }
        .slot-glance-pill{
            font-size:0.58rem;
            padding:0.15rem 0.35rem;
            white-space:normal;
            overflow:visible;
            text-overflow:clip;
            line-height:1.3;
        }
        .slot-action-btn{
            font-size:0.58rem;
            padding:0.2rem 0.35rem;
        }
        .slot-clear-selection{
            font-size:0.58rem;
            padding:0.15rem 0.4rem;
        }
    }
    @media (max-width: 767.98px) {
        .bundle-selection-wrapper{
            margin-top:1.5rem !important;
        }
        .bundle-selection-wrapper h5{
            font-size:0.85rem !important;
            margin-bottom:0.75rem !important;
        }
        .bundle-slot-card{
            min-height:120px;
            padding:0.4rem;
            border-radius:0.5rem;
            overflow:hidden;
        }
        .bundle-slot-card.is-filled{
            min-height:240px;
            padding:0.5rem;
        }
        .bundle-slot-card.is-filled .slot-glance-preview img{
            height:120px;
        }
        .bundle-slot-card.is-filled .slot-glance-meta{
            font-size:0.5rem;
        }
        .bundle-slot-card.is-filled .slot-price-meta{
            font-size:0.45rem;
        }
        .bundle-slot-card.is-filled .slot-glance-pill{
            font-size:0.55rem;
            padding:0.25rem 0.5rem;
        }
        .slot-product-card__image{
            height:140px;
        }
        .slot-product-card__details{
            padding:0.3rem;
        }
        .slot-product-card__title{
            font-size:0.55rem;
            margin-bottom:0.25rem;
        }
        .slot-product-card__price{
            margin-bottom:0.3rem;
        }
        .slot-product-card__price-label{
            font-size:0.45rem;
        }
        .slot-product-card__price-base{
            font-size:0.45rem;
        }
        .slot-product-card__price-discounted{
            font-size:0.55rem;
        }
        .variant-selection-group{
            padding:0.3rem;
        }
        .variant-option-group{
            margin-bottom:0.35rem;
            gap:0.3rem;
        }
        .variant-option-label{
            font-size:0.5rem;
        }
        .variant-size-btn{
            font-size:0.5rem;
            padding:0.15rem 0.35rem;
            min-width:22px;
        }
        .variant-color-swatch{
            width:20px;
            height:20px;
        }
        .slot-glance-head{
            gap:0.25rem;
            margin-bottom:0.3rem;
            flex-wrap:wrap;
        }
        .slot-glance-title{
            font-size:0.6rem;
            line-height:1.1;
            max-width:calc(100% - 35px);
        }
        .slot-glance-badge{
            padding:0.06rem 0.25rem;
            font-size:0.5rem;
            flex-shrink:0;
        }
        .slot-glance-selection{
            min-height:20px;
            font-size:0.55rem;
            margin-bottom:0.4rem;
            overflow:visible;
        }
        .slot-glance-pill{
            font-size:0.5rem;
            padding:0.1rem 0.25rem;
            line-height:1.3;
            max-width:100%;
            white-space:normal;
            overflow:visible;
            text-overflow:clip;
        }
        .slot-price-meta{
            font-size:0.5rem;
            overflow:hidden;
        }
        .slot-price-meta small{
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }
        .slot-action-row{
            margin-top:0.25rem;
        }
        .slot-action-btn{
            font-size:0.5rem;
            padding:0.15rem 0.25rem;
            width:100%;
            min-height:24px;
        }
        .slot-clear-selection{
            font-size:0.5rem;
            padding:0.12rem 0.3rem;
            width:100%;
            min-height:22px;
        }
        .slot-clear-selection i{
            font-size:0.55rem;
        }
        .fs-9{
            font-size:0.5rem !important;
        }
        .slot-glance-preview{
            max-width:100%;
            max-height:40px;
            overflow:hidden;
        }
        .slot-glance-preview img{
            max-width:100%;
            max-height:40px;
            object-fit:cover;
        }
        .bundle-slot-card.is-filled .slot-glance-preview{
            max-height:none;
            overflow:visible;
        }
        .bundle-slot-card.is-filled .slot-glance-preview img{
            max-height:none;
            height:120px;
        }
    }
    @media (max-width: 575.98px) {
        .bundle-slot-card{
            min-height:150px;
            padding:0.65rem;
        }
        .bundle-slot-card.is-filled{
            min-height:240px;
        }
        .slot-image-container{
            height:120px;
        }
        .slot-product-info{
            padding:0.6rem;
        }
        .slot-product-name{
            font-size:0.65rem;
        }
        .slot-variant-info{
            font-size:0.5rem;
        }
        .slot-price-row{
            font-size:0.55rem;
        }
        .slot-price-final{
            font-size:0.6rem;
        }
        .slot-empty-icon{
            width:44px;
            height:44px;
        }
        .slot-empty-icon i{
            font-size:1.35rem;
        }
        .slot-empty-text{
            font-size:0.65rem;
        }
        .slot-action-btn-elegant{
            font-size:0.55rem;
            padding:0.3rem 0.6rem;
        }
        .slot-clear-selection-elegant{
            font-size:0.55rem;
            padding:0.3rem 0.55rem;
        }
        .slot-glance-head{
            gap:0.2rem;
            margin-bottom:0.25rem;
            flex-wrap:wrap;
        }
        .slot-glance-title{
            font-size:0.55rem;
            max-width:calc(100% - 30px);
        }
        .slot-glance-badge{
            padding:0.05rem 0.2rem;
            font-size:0.45rem;
            flex-shrink:0;
        }
        .slot-glance-selection{
            min-height:18px;
            font-size:0.5rem;
            margin-bottom:0.3rem;
            overflow:visible;
        }
        .slot-glance-pill{
            font-size:0.45rem;
            padding:0.08rem 0.2rem;
            max-width:100%;
            white-space:normal;
            overflow:visible;
            text-overflow:clip;
            line-height:1.3;
        }
        .slot-price-meta{
            font-size:0.45rem;
            overflow:hidden;
        }
        .slot-price-meta small{
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }
        .slot-action-btn{
            font-size:0.45rem;
            padding:0.12rem 0.2rem;
            min-height:22px;
        }
        .slot-clear-selection{
            font-size:0.45rem;
            padding:0.1rem 0.25rem;
            min-height:20px;
        }
        .fs-9{
            font-size:0.45rem !important;
        }
        .slot-glance-preview{
            max-width:100%;
            max-height:35px;
            overflow:hidden;
        }
        .slot-glance-preview img{
            max-width:100%;
            max-height:35px;
            object-fit:cover;
        }
    }
    @media (max-width: 400px) {
        .bundle-slot-card{
            min-height:100px;
            padding:0.3rem;
            overflow:hidden;
        }
        .bundle-slot-card.is-filled{
            min-height:220px;
            padding:0.4rem;
        }
        .bundle-slot-card.is-filled .slot-glance-preview img{
            height:100px;
        }
        .bundle-slot-card.is-filled .slot-glance-meta{
            font-size:0.45rem;
        }
        .bundle-slot-card.is-filled .slot-price-meta{
            font-size:0.4rem;
        }
        .bundle-slot-card.is-filled .slot-glance-pill{
            font-size:0.5rem;
            padding:0.2rem 0.4rem;
        }
        .slot-glance-head{
            gap:0.15rem;
            margin-bottom:0.2rem;
        }
        .slot-glance-title{
            font-size:0.5rem;
            max-width:calc(100% - 28px);
        }
        .slot-glance-badge{
            font-size:0.4rem;
            padding:0.04rem 0.15rem;
            flex-shrink:0;
        }
        .slot-glance-selection{
            min-height:16px;
            font-size:0.45rem;
            margin-bottom:0.25rem;
            overflow:hidden;
        }
        .slot-glance-pill{
            font-size:0.4rem;
            padding:0.06rem 0.15rem;
            max-width:100%;
            white-space:normal;
            overflow:visible;
            text-overflow:clip;
            line-height:1.3;
        }
        .slot-price-meta{
            font-size:0.4rem;
            overflow:hidden;
        }
        .slot-price-meta small{
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }
        .slot-action-btn{
            font-size:0.4rem;
            padding:0.1rem 0.15rem;
            min-height:20px;
        }
        .slot-clear-selection{
            font-size:0.4rem;
            padding:0.08rem 0.2rem;
            min-height:18px;
        }
        .slot-clear-selection i{
            font-size:0.45rem;
        }
        .fs-9{
            font-size:0.4rem !important;
        }
        .slot-glance-preview{
            max-width:100%;
            max-height:30px;
            overflow:hidden;
        }
        .slot-glance-preview img{
            max-width:100%;
            max-height:30px;
            object-fit:cover;
        }
    }
</style>
@endsection

@section('script')
<script>
    (function () {
        const form = document.getElementById('group-bundle-form');
        if (!form) {
            return;
        }

        const currencySymbol = form.dataset.currencySymbol || '';
        const discountType = form.dataset.bundleDiscountType || '';
        const discountValue = parseFloat(form.dataset.bundleDiscountValue || 0);
        const quantityInput = document.getElementById('bundle_quantity');
        const summary = {
            base: document.getElementById('bundle-base-total'),
            paid: document.getElementById('bundle-paid-total'),
            free: document.getElementById('bundle-free-total'),
            discount: document.getElementById('bundle-discount-total'),
            grand: document.getElementById('bundle-grand-total'),
        };
        const heroTotal = document.getElementById('bundle-grand-total-hero');
        const heroOriginal = document.getElementById('bundle-hero-original');
        const heroSaveWrapper = document.getElementById('bundle-save-chip-wrapper');
        const heroSave = document.getElementById('bundle-save-chip');
        const warning = document.getElementById('bundle-action-warning');
        const actionButtons = document.querySelectorAll('.bundle-action-trigger');
        const slotSelections = {};
        const notSelectedCopy = '{{ translate('Not selected') }}';
        const selectButtonText = '{{ translate('Select') }}';
        const outOfStockButtonText = '{{ translate('Out of Stock') }}';
        const slotModalEl = document.getElementById('slotSelectionModal');
        const slotModalBody = slotModalEl.querySelector('.modal-slot-body');
        const slotModalTitle = document.getElementById('slotModalTitle');
        const slotModalSubtitle = document.getElementById('slotModalSubtitle');
        const slotModalJQ = window.jQuery ? window.jQuery(slotModalEl) : null;
        let slotModalInstance = (window.bootstrap && window.bootstrap.Modal && typeof window.bootstrap.Modal.getOrCreateInstance === 'function')
            ? window.bootstrap.Modal.getOrCreateInstance(slotModalEl, {focus: true})
            : null;
        let activeSlotContent = null;
        const galleryThumbs = document.querySelectorAll('.bundle-gallery-thumbs-elegant .thumb-elegant, .bundle-gallery-thumbs .thumb');
        const galleryThumbTrack = document.getElementById('bundle-thumb-track');
        const thumbNavButtons = document.querySelectorAll('[data-thumb-nav]');
        const galleryMainImage = document.getElementById('bundle-main-image');
        const galleryStage = document.getElementById('bundle-main-stage');
        const zoomPane = document.getElementById('bundle-zoom-pane');
        const fullscreenTrigger = document.getElementById('bundle-fullscreen-trigger');
        const galleryModalEl = document.getElementById('bundleGalleryModal');
        const galleryModalImage = document.getElementById('bundle-modal-image');
        const galleryModalCounter = document.getElementById('bundle-modal-counter');
        const galleryModalThumbs = document.querySelectorAll('.bundle-modal-thumb-elegant, .bundle-modal-thumb');
        const galleryModalNavButtons = document.querySelectorAll('[data-gallery-dir]');
        const galleryModalJQ = galleryModalEl && window.jQuery ? window.jQuery(galleryModalEl) : null;
        const galleryModalStageWrapper = document.getElementById('bundle-modal-stage-wrapper');
        let galleryModalInstance = (galleryModalEl && window.bootstrap && window.bootstrap.Modal && typeof window.bootstrap.Modal.getOrCreateInstance === 'function')
            ? window.bootstrap.Modal.getOrCreateInstance(galleryModalEl, {focus: true})
            : null;
        const galleryState = {
            images: Array.from(galleryThumbs).map(btn => ({
                src: btn.dataset.image,
                zoom: btn.dataset.zoom || btn.dataset.image,
            })),
            activeIndex: 0,
            modalOpen: false,
        };
        function createDefaultSlotState(slotCard) {
            return {
                price: 0,
                basePrice: 0,
                free: slotCard.dataset.slotType === 'free',
                required: slotCard.dataset.slotType !== 'free',
                productName: '',
                variantLabel: '',
                stockQty: 0,
                productImage: '',
                variantMeta: [],
            };
        }

        function formatCurrency(value) {
            return currencySymbol + Number(value || 0).toFixed(2);
        }

        function parseVariantMetaFromLabel(label) {
            if (!label) {
                return [];
            }
            const tokens = label.split(/[\s\-\/|,·]+/).map(token => token.trim()).filter(Boolean);
            if (!tokens.length) {
                tokens.push(label.trim());
            }
            const fallbackLabels = ["{{ translate('Color') }}", "{{ translate('Size') }}"];
            return tokens.map((value, index) => ({
                label: fallbackLabels[index] || "{{ translate('Attribute') }}" + ' ' + (index + 1),
                value,
            }));
        }

        function resolveVariantMeta(metaString, variantLabel) {
            let meta = [];
            if (metaString) {
                try {
                    meta = JSON.parse(metaString);
                } catch (err) {
                    meta = [];
                }
            }
            if (!meta || !meta.length) {
                meta = parseVariantMetaFromLabel(variantLabel);
            }
            return meta || [];
        }

        function renderVariantMeta(container, meta) {
            if (!container) {
                return;
            }
            container.innerHTML = '';
            if (!meta || !meta.length) {
                container.classList.add('d-none');
                return;
            }
            meta.forEach(entry => {
                const line = document.createElement('small');
                line.className = 'variant-meta-line';
                line.textContent = `${entry.label}: ${entry.value}`;
                container.appendChild(line);
            });
            container.classList.remove('d-none');
        }

        function updateSlotSummary(slotId) {
            const slotCard = document.querySelector(`.bundle-slot-card[data-slot="${slotId}"]`);
            const data = slotSelections[slotId] || {};
            const text = data.productName ? data.productName : notSelectedCopy;
            const qty = Math.max(parseInt(quantityInput.value || 1, 10), 1);
            if (slotCard) {
                const isEmpty = !data.productName;
                const emptyState = slotCard.querySelector('[data-slot-empty-state]');
                const filledState = slotCard.querySelector('[data-slot-filled-state]');
                
                // Toggle states
                slotCard.classList.toggle('is-filled', !isEmpty);
                slotCard.classList.toggle('is-empty', isEmpty);
                
                if (isEmpty) {
                    // Show empty state, hide filled state
                    if (emptyState) emptyState.classList.remove('d-none');
                    if (filledState) filledState.classList.add('d-none');
                    // Update empty state text
                    slotCard.querySelectorAll('[data-slot-summary-text]').forEach(el => el.textContent = text);
                } else {
                    // Show filled state, hide empty state
                    if (emptyState) emptyState.classList.add('d-none');
                    if (filledState) filledState.classList.remove('d-none');
                    
                    // Update product name
                    slotCard.querySelectorAll('[data-slot-summary-text]').forEach(el => el.textContent = text);
                    
                    // Update variant meta
                    const slotMetaContainer = slotCard.querySelector('[data-slot-summary-meta]');
                    renderVariantMeta(slotMetaContainer, data.variantMeta);
                    
                    // Update price
                    const slotPriceMeta = slotCard.querySelector('[data-slot-price-meta]');
                    if (slotPriceMeta) {
                        const slotBaseEl = slotPriceMeta.querySelector('[data-slot-price-base]');
                        const slotDiscEl = slotPriceMeta.querySelector('[data-slot-price-discount]');
                        if (data.basePrice || data.price) {
                            slotPriceMeta.classList.remove('d-none');
                            if (slotBaseEl) {
                                slotBaseEl.textContent = formatCurrency((Number(data.basePrice || data.price || 0)) * qty);
                            }
                            if (slotDiscEl) {
                                slotDiscEl.textContent = formatCurrency((Number(data.price || 0)) * qty);
                            }
                        } else {
                            slotPriceMeta.classList.add('d-none');
                        }
                    }
                    
                    // Update image
                    const previewWrapper = slotCard.querySelector('[data-slot-preview-wrapper]');
                    const previewImage = slotCard.querySelector('[data-slot-preview-image]');
                    if (previewWrapper && previewImage) {
                        if (data.productImage) {
                            previewImage.src = data.productImage;
                            previewWrapper.classList.remove('d-none');
                        } else {
                            previewWrapper.classList.add('d-none');
                        }
                    }
                }
            }
            const summaryRow = document.querySelector(`.bundle-summary-card[data-slot="${slotId}"]`);
            if (summaryRow) {
                const summaryLabel = summaryRow.querySelector('[data-slot-summary-text]');
                const summaryMeta = summaryRow.querySelector('[data-slot-summary-meta]');
                const summaryPriceMeta = summaryRow.querySelector('[data-slot-summary-price]');
                const summaryImage = summaryRow.querySelector('[data-slot-summary-image]');
                const summaryPlaceholder = summaryRow.querySelector('[data-slot-summary-placeholder]');
                const summaryBadge = summaryRow.querySelector('[data-slot-required-badge]');
                const isRequired = summaryRow.dataset.required === '1';
                if (summaryLabel) {
                    summaryLabel.textContent = text;
                }
                renderVariantMeta(summaryMeta, data.variantMeta);
                if (summaryPriceMeta) {
                    const summaryBase = summaryPriceMeta.querySelector('[data-slot-summary-base]');
                    const summaryDisc = summaryPriceMeta.querySelector('[data-slot-summary-discount]');
                    const summaryQty = summaryPriceMeta.querySelector('[data-slot-summary-qty]');
                    if (data.productName && (data.basePrice || data.price)) {
                        summaryPriceMeta.classList.remove('d-none');
                        if (summaryBase) {
                            summaryBase.textContent = formatCurrency((Number(data.basePrice || data.price || 0)) * qty);
                        }
                        if (summaryDisc) {
                            summaryDisc.textContent = formatCurrency((Number(data.price || 0)) * qty);
                        }
                        if (summaryQty) {
                            summaryQty.textContent = qty;
                        }
                    } else {
                        summaryPriceMeta.classList.add('d-none');
                    }
                }
                if (summaryBadge && isRequired) {
                    summaryBadge.classList.toggle('badge-soft-danger', !data.productName);
                    summaryBadge.classList.toggle('badge-soft-success', !!data.productName);
                }
                if (summaryImage) {
                    if (data.productImage) {
                        summaryImage.src = data.productImage;
                        summaryImage.classList.remove('d-none');
                        if (summaryPlaceholder) {
                            summaryPlaceholder.classList.add('d-none');
                        }
                    } else {
                        summaryImage.classList.add('d-none');
                        if (summaryPlaceholder) {
                            summaryPlaceholder.classList.remove('d-none');
                        }
                    }
                }
                summaryRow.classList.toggle('is-filled', !!data.productName);
                summaryRow.classList.toggle('is-empty', !data.productName);
            }
            document.querySelectorAll(`.slot-clear-selection[data-slot="${slotId}"], .slot-clear-selection-elegant[data-slot="${slotId}"]`).forEach(btn => {
                btn.classList.toggle('d-none', !data.productName);
            });
        }

        function clearSlotSelection(slotId) {
            const slotCard = document.querySelector(`.bundle-slot-card[data-slot="${slotId}"]`);
            if (!slotCard) {
                return;
            }
            slotSelections[slotId] = createDefaultSlotState(slotCard);
            slotCard.querySelectorAll('.slot-product-card').forEach(card => card.classList.remove('is-active'));
            slotCard.querySelectorAll('.slot-product-radio').forEach(radio => radio.checked = false);
            // Clear both form and picker variant fields
            const formVariantField = form.querySelector(`.slot-variant-input-form[data-slot="${slotId}"]`);
            if (formVariantField) {
                formVariantField.value = '';
            }
            const pickerVariantField = document.querySelector(`.slot-variant-input[data-slot="${slotId}"]`);
            if (pickerVariantField) {
                pickerVariantField.value = '';
            }
            updateSlotSummary(slotId);
            updateTotals();
        }

        function refreshAllSlotSummaries() {
            Object.keys(slotSelections).forEach(id => updateSlotSummary(id));
        }

        function updateTotals() {
            const bundleQty = Math.max(parseInt(quantityInput.value || 1, 10), 1);
            let paidUnitTotal = 0;
            let baseUnitTotal = 0;
            let freeUnitValue = 0;
            let hasBlockingIssue = false;

            Object.keys(slotSelections).forEach(slotId => {
                const sel = slotSelections[slotId];
                if (!sel) {
                    if (event.target.closest('.slot-clear-selection')) {
                        return;
                    }
                }
                const basePrice = Number(sel.basePrice || 0);
                const finalPrice = Number(sel.price || 0);
                baseUnitTotal += basePrice;
                if (sel.free) {
                    freeUnitValue += basePrice;
                } else {
                    paidUnitTotal += finalPrice;
                }
                if (sel.required && ((!sel.productName) || sel.stockQty <= 0)) {
                    hasBlockingIssue = true;
                }
            });

            const baseTotal = baseUnitTotal * bundleQty;
            const paidTotal = paidUnitTotal * bundleQty;
            const freeValue = freeUnitValue * bundleQty;

            let bundleDiscount = 0;
            if (discountType === 'percentage') {
                bundleDiscount = paidTotal * (discountValue / 100);
            } else if (discountType === 'fixed') {
                bundleDiscount = discountValue * bundleQty;
            }

            const priceSavings = Math.max(baseTotal - paidTotal, 0);
            const totalSavings = priceSavings + bundleDiscount;
            const finalTotal = Math.max(paidTotal - bundleDiscount, 0);

            if (summary.base) {
                summary.base.textContent = formatCurrency(baseTotal);
            }
            summary.paid.textContent = formatCurrency(paidTotal);
            summary.free.textContent = formatCurrency(freeValue);
            summary.discount.textContent = formatCurrency(totalSavings);
            summary.grand.textContent = formatCurrency(finalTotal);

            if (heroTotal) {
                heroTotal.textContent = formatCurrency(finalTotal);
            }
            if (heroOriginal) {
                heroOriginal.textContent = formatCurrency(baseTotal);
                heroOriginal.classList.toggle('d-none', totalSavings <= 0);
            }
            if (heroSaveWrapper && heroSave) {
                heroSaveWrapper.classList.toggle('d-none', totalSavings <= 0);
                heroSave.textContent = formatCurrency(totalSavings);
            }

            actionButtons.forEach(btn => btn.disabled = hasBlockingIssue);
            if (warning) {
                warning.classList.toggle('d-none', !hasBlockingIssue);
            }
        }

        function setGalleryImage(index, options = {scrollIntoView: true}) {
            if (!galleryMainImage || !galleryState.images.length) {
                return;
            }
            const item = galleryState.images[index];
            if (!item) {
                return;
            }
            galleryState.activeIndex = index;
            galleryMainImage.src = item.src;
            galleryMainImage.dataset.zoom = item.zoom || item.src;
            galleryThumbs.forEach(btn => {
                const isActive = Number(btn.dataset.index) === index;
                btn.classList.toggle('active', isActive);
                btn.classList.toggle('is-active', isActive);
            });
            if (options.scrollIntoView && galleryThumbTrack) {
                const activeThumb = Array.from(galleryThumbs).find(btn => Number(btn.dataset.index) === index);
                if (activeThumb) {
                    activeThumb.scrollIntoView({block: 'nearest', behavior: 'smooth'});
                }
            }
        }

        const pointerFine = window.matchMedia ? window.matchMedia('(pointer: fine)').matches : true;

        function updateZoomPane(event) {
            if (!galleryStage || !zoomPane || !pointerFine) {
                return;
            }
            const rect = galleryStage.getBoundingClientRect();
            const xPercent = ((event.clientX - rect.left) / rect.width) * 100;
            const yPercent = ((event.clientY - rect.top) / rect.height) * 100;
            const zoomImage = galleryMainImage.dataset.zoom || galleryMainImage.src;
            zoomPane.style.backgroundImage = `url('${zoomImage}')`;
            zoomPane.style.backgroundPosition = `${xPercent}% ${yPercent}%`;
        }

        function updateGalleryModal(index) {
            if (!galleryModalImage || !galleryState.images.length) {
                return;
            }
            const item = galleryState.images[index];
            if (!item) {
                return;
            }
            galleryState.activeIndex = index;
            galleryModalImage.src = item.src;
            galleryModalImage.dataset.zoom = item.zoom || item.src;
            galleryModalImage.style.transformOrigin = 'center center';
            if (galleryModalCounter) {
                galleryModalCounter.textContent = `${index + 1} / ${galleryState.images.length}`;
            }
            galleryModalThumbs.forEach(btn => {
                btn.classList.toggle('is-active', Number(btn.dataset.index) === index);
            });
        }

        function openGalleryModal(index) {
            updateGalleryModal(index);
            if (galleryModalInstance) {
                galleryModalInstance.show();
            } else if (galleryModalJQ && typeof galleryModalJQ.modal === 'function') {
                galleryModalJQ.modal('show');
            }
        }

        function navigateGallery(step) {
            const total = galleryState.images.length;
            if (!total) {
                return;
            }
            const nextIndex = (galleryState.activeIndex + step + total) % total;
            updateGalleryModal(nextIndex);
            setGalleryImage(nextIndex, {scrollIntoView: true});
        }

        function activateVariantPill(pill) {
            if (!pill || pill.classList.contains('is-disabled')) {
                return;
            }
            const slotId = pill.dataset.slot;
            const productId = pill.dataset.productId;
            const slotCard = document.querySelector(`.bundle-slot-card[data-slot="${slotId}"]`);
            const slotPicker = pill.closest('.slot-picker-hidden') || document.getElementById(`slot-picker-${slotId}`);
            const selectionRoot = slotPicker || slotCard;
            if (!slotCard || !selectionRoot) {
                return;
            }

            // Only update visual state - don't select the product
            selectionRoot.querySelectorAll('.variant-pill').forEach(btn => btn.classList.remove('is-active'));
            pill.classList.add('is-active');

            // Find the product card and update size/color button states
            const productCard = selectionRoot.querySelector(`.slot-product-card[data-product-id="${productId}"]`);
            if (productCard) {
                const pillSize = pill.dataset.size || '';
                const pillColor = pill.dataset.color || '';
                
                // Update size buttons
                productCard.querySelectorAll('.variant-size-btn').forEach(btn => {
                    btn.classList.toggle('is-active', btn.dataset.size === pillSize);
                });
                
                // Update color swatches
                productCard.querySelectorAll('.variant-color-swatch').forEach(swatch => {
                    swatch.classList.toggle('is-active', swatch.dataset.color === pillColor);
                });
                
                // Update select button state (enable if variant is selected and in stock)
                updateProductSelectButton(productCard);
            }
        }

        function activateProductCard(card) {
            if (!card) {
                return;
            }
            const slotId = card.dataset.slot;
            const slotCard = document.querySelector(`.bundle-slot-card[data-slot="${slotId}"]`);
            const selectionRoot = card.closest('.slot-picker-hidden') || slotCard;
            if (!slotCard || !selectionRoot) {
                return;
            }
            
            // Check if variations are complete and in stock before allowing selection
            const status = checkProductVariationsComplete(card);
            if (!status.complete || status.outOfStock) {
                return; // Don't select if variations are not complete or out of stock
            }
            
            selectionRoot.querySelectorAll('.slot-product-card').forEach(item => {
                const isActive = item === card;
                item.classList.toggle('is-active', isActive);
                const radio = item.querySelector('.slot-product-radio');
                if (radio) {
                    radio.checked = isActive;
                }
            });
            
            // Get the active variant pill (if any)
            const activePill = card.querySelector('.variant-pills-data .variant-pill.is-active:not(.is-disabled)');
            
            if (activePill) {
                // Product has variants - use the selected variant
                const variantValue = activePill.dataset.variant || '';
                const formVariantField = form.querySelector(`.slot-variant-input-form[data-slot="${slotId}"]`);
                if (formVariantField) {
                    formVariantField.value = variantValue;
                }
                const pickerVariantField = document.querySelector(`.slot-variant-input[data-slot="${slotId}"]`);
                if (pickerVariantField) {
                    pickerVariantField.value = variantValue;
                }
                
                slotSelections[slotId] = {
                    price: parseFloat(activePill.dataset.price || 0),
                    basePrice: parseFloat(activePill.dataset.basePrice || activePill.dataset.price || 0),
                    free: slotCard.dataset.slotType === 'free',
                    required: slotCard.dataset.slotType !== 'free',
                    productName: activePill.dataset.productName || '',
                    variantLabel: activePill.dataset.variantLabel || '',
                    stockQty: parseInt(activePill.dataset.stock || 0, 10),
                    productImage: activePill.dataset.productImage || '',
                    variantMeta: resolveVariantMeta(activePill.dataset.variantMeta, activePill.dataset.variantLabel),
                };
            } else {
                // No variant pills - this is a non-variant product
                const formVariantField = form.querySelector(`.slot-variant-input-form[data-slot="${slotId}"]`);
                if (formVariantField) {
                    formVariantField.value = ''; // Empty string for non-variant products
                }
                const pickerVariantField = document.querySelector(`.slot-variant-input[data-slot="${slotId}"]`);
                if (pickerVariantField) {
                    pickerVariantField.value = '';
                }
                const defaultState = createDefaultSlotState(slotCard);
                defaultState.productName = card.querySelector('.slot-product-card__title')?.textContent?.trim() || 
                                           card.querySelector('.fw-600')?.textContent?.trim() || '';
                defaultState.productImage = card.querySelector('.slot-product-card__image img')?.src || 
                                          card.querySelector('.slot-product-card__top img')?.src || '';
                slotSelections[slotId] = defaultState;
            }
            
            updateSlotSummary(slotId);
            updateTotals();
            
            // Close modal with fade effect after selection
            hideSlotModalWithEffect();
        }

        // Function to check if a product has all required variations selected
        // Returns: { complete: boolean, outOfStock: boolean }
        function checkProductVariationsComplete(productCard) {
            const productId = productCard.dataset.productId;
            const slotId = productCard.dataset.slot;
            
            // Check if product has any variant selection UI
            const hasVariantSelection = productCard.querySelector('.variant-selection-group');
            if (!hasVariantSelection) {
                // No variant selection UI - product can be selected directly
                return { complete: true, outOfStock: false };
            }
            
            // Check if product has variant pills (hidden data)
            const hasVariants = productCard.querySelector('.variant-pills-data .variant-pill');
            if (!hasVariants) {
                // No variants - product can be selected directly
                return { complete: true, outOfStock: false };
            }
            
            // Check if an active variant pill exists (size+color selected or variant pill clicked)
            const activePill = productCard.querySelector('.variant-pills-data .variant-pill.is-active');
            if (activePill) {
                const stockQty = parseInt(activePill.dataset.stock || 0, 10);
                const isDisabled = activePill.classList.contains('is-disabled');
                // Variant is selected
                if (stockQty > 0 && !isDisabled) {
                    return { complete: true, outOfStock: false };
                } else {
                    // Variant is selected but out of stock
                    return { complete: true, outOfStock: true };
                }
            }
            
            // Check if size and color are both selected (for products with size+color variants)
            const sizeButtons = productCard.querySelectorAll('.variant-size-btn');
            const colorSwatches = productCard.querySelectorAll('.variant-color-swatch');
            const activeSize = Array.from(sizeButtons).find(btn => btn.classList.contains('is-active'));
            const activeColor = Array.from(colorSwatches).find(swatch => swatch.classList.contains('is-active'));
            
            if (sizeButtons.length > 0 && colorSwatches.length > 0) {
                // Both size and color must be selected
                if (activeSize && activeColor) {
                    // Check if the matching variant is out of stock
                    const hiddenPills = productCard.querySelectorAll('.variant-pills-data .variant-pill');
                    let matchingPill = null;
                    hiddenPills.forEach(pill => {
                        const pillSize = pill.dataset.size || '';
                        const pillColor = pill.dataset.color || '';
                        if (pillSize === activeSize.dataset.size && pillColor === activeColor.dataset.color) {
                            matchingPill = pill;
                        }
                    });
                    if (matchingPill) {
                        const stockQty = parseInt(matchingPill.dataset.stock || 0, 10);
                        const isDisabled = matchingPill.classList.contains('is-disabled');
                        if (stockQty > 0 && !isDisabled) {
                            return { complete: true, outOfStock: false };
                        } else {
                            return { complete: true, outOfStock: true };
                        }
                    }
                }
                return { complete: false, outOfStock: false };
            } else if (sizeButtons.length > 0) {
                // Only size must be selected
                if (activeSize) {
                    // Check if any variant with this size is in stock
                    const hiddenPills = productCard.querySelectorAll('.variant-pills-data .variant-pill');
                    let hasInStock = false;
                    let hasOutOfStock = false;
                    hiddenPills.forEach(pill => {
                        const pillSize = pill.dataset.size || '';
                        if (pillSize === activeSize.dataset.size) {
                            const stockQty = parseInt(pill.dataset.stock || 0, 10);
                            const isDisabled = pill.classList.contains('is-disabled');
                            if (stockQty > 0 && !isDisabled) {
                                hasInStock = true;
                            } else {
                                hasOutOfStock = true;
                            }
                        }
                    });
                    if (hasInStock) {
                        return { complete: true, outOfStock: false };
                    } else if (hasOutOfStock) {
                        return { complete: true, outOfStock: true };
                    }
                }
                return { complete: false, outOfStock: false };
            } else if (colorSwatches.length > 0) {
                // Only color must be selected
                if (activeColor) {
                    // Check if any variant with this color is in stock
                    const hiddenPills = productCard.querySelectorAll('.variant-pills-data .variant-pill');
                    let hasInStock = false;
                    let hasOutOfStock = false;
                    hiddenPills.forEach(pill => {
                        const pillColor = pill.dataset.color || '';
                        if (pillColor === activeColor.dataset.color) {
                            const stockQty = parseInt(pill.dataset.stock || 0, 10);
                            const isDisabled = pill.classList.contains('is-disabled');
                            if (stockQty > 0 && !isDisabled) {
                                hasInStock = true;
                            } else {
                                hasOutOfStock = true;
                            }
                        }
                    });
                    if (hasInStock) {
                        return { complete: true, outOfStock: false };
                    } else if (hasOutOfStock) {
                        return { complete: true, outOfStock: true };
                    }
                }
                return { complete: false, outOfStock: false };
            }
            
            return { complete: false, outOfStock: false };
        }
        
        // Function to update select button state for a product
        function updateProductSelectButton(productCard) {
            const selectBtn = productCard.querySelector('.slot-product-select-btn');
            if (!selectBtn) return;
            
            const status = checkProductVariationsComplete(productCard);
            const isComplete = status.complete;
            const isOutOfStock = status.outOfStock;
            
            // Get button text element (icon + text)
            const buttonIcon = selectBtn.querySelector('i');
            const buttonText = Array.from(selectBtn.childNodes).find(node => node.nodeType === 3 && node.textContent.trim());
            
            if (isComplete && isOutOfStock) {
                // Variations selected but out of stock
                selectBtn.disabled = true;
                selectBtn.classList.remove('btn-primary');
                selectBtn.classList.add('btn-secondary', 'btn-out-of-stock');
                // Update button text
                selectBtn.innerHTML = '<i class="las la-exclamation-triangle mr-1"></i>' + outOfStockButtonText;
            } else if (isComplete) {
                // Variations selected and in stock
                selectBtn.disabled = false;
                selectBtn.classList.remove('btn-secondary', 'btn-out-of-stock');
                selectBtn.classList.add('btn-primary');
                // Update button text
                selectBtn.innerHTML = '<i class="las la-check-circle mr-1"></i>' + selectButtonText;
            } else {
                // Variations not complete
                selectBtn.disabled = true;
                selectBtn.classList.remove('btn-primary', 'btn-out-of-stock');
                selectBtn.classList.add('btn-secondary');
                // Update button text
                selectBtn.innerHTML = '<i class="las la-check-circle mr-1"></i>' + selectButtonText;
            }
        }
        
        // Update all select buttons when variants change
        function updateAllSelectButtons(slotId) {
            const slotPicker = document.getElementById(`slot-picker-${slotId}`);
            if (!slotPicker) return;
            
            slotPicker.querySelectorAll('.slot-product-card').forEach(card => {
                updateProductSelectButton(card);
            });
        }
        
        // Handle select button clicks
        document.addEventListener('click', function (event) {
            const selectBtn = event.target.closest('.slot-product-select-btn');
            if (selectBtn && !selectBtn.disabled) {
                event.stopPropagation();
                event.preventDefault();
                const productCard = selectBtn.closest('.slot-product-card');
                if (productCard) {
                    activateProductCard(productCard);
                }
            }
        });

        // Use event delegation for variant pills (works with dynamically moved content)
        document.addEventListener('click', function (event) {
            const pill = event.target.closest('.variant-pill');
            if (pill && !pill.classList.contains('is-disabled')) {
                event.stopPropagation();
                event.preventDefault();
                activateVariantPill(pill);
                return;
            }
            
            // Handle size button clicks
            const sizeBtn = event.target.closest('.variant-size-btn');
            if (sizeBtn) {
                event.stopPropagation();
                event.preventDefault();
                const slotId = sizeBtn.dataset.slot;
                const productId = sizeBtn.dataset.productId;
                const selectedSize = sizeBtn.dataset.size;
                
                // Find all variant pills for this product
                const productCard = sizeBtn.closest('.slot-product-card');
                if (!productCard) return;
                
                const hiddenPills = productCard.querySelectorAll('.variant-pills-data .variant-pill');
                const colorSwatches = productCard.querySelectorAll('.variant-color-swatch');
                let selectedColor = '';
                
                // Get selected color if any
                colorSwatches.forEach(swatch => {
                    if (swatch.classList.contains('is-active')) {
                        selectedColor = swatch.dataset.color || '';
                    }
                });
                
                // Find matching variant pill from hidden pills
                // First, try to find exact match (size + color if color is selected)
                let exactMatchPill = null;
                let fallbackPill = null;
                
                hiddenPills.forEach(pill => {
                    const pillSize = pill.dataset.size || '';
                    const pillColor = pill.dataset.color || '';
                    
                    // Exact match: size matches and (no color selected or color matches)
                    if (pillSize === selectedSize && (!selectedColor || pillColor === selectedColor)) {
                        if (!exactMatchPill) {
                            exactMatchPill = pill; // Get first exact match
                        }
                    }
                    // Fallback: size matches (for when no exact match with color)
                    if (pillSize === selectedSize && !exactMatchPill) {
                        if (!fallbackPill) {
                            fallbackPill = pill;
                        }
                    }
                });
                
                // Update active states
                productCard.querySelectorAll('.variant-size-btn').forEach(btn => {
                    btn.classList.toggle('is-active', btn === sizeBtn);
                });
                
                // Only activate if the variant is in stock
                const pillToActivate = exactMatchPill || fallbackPill;
                if (pillToActivate) {
                    const isInStock = !pillToActivate.classList.contains('is-disabled');
                    const stockQty = parseInt(pillToActivate.dataset.stock || 0, 10);
                    const isAvailable = isInStock && stockQty > 0;
                    
                    if (isAvailable) {
                        // Variant is in stock - activate it normally
                        activateVariantPill(pillToActivate);
                    } else {
                        // Variant is out of stock - show it visually but don't activate
                        // Visually mark as active for UI feedback
                        productCard.querySelectorAll('.variant-pill').forEach(p => p.classList.remove('is-active'));
                        pillToActivate.classList.add('is-active');
                        
                        // Clear form fields and slot selection since it's out of stock
                        const slotId = sizeBtn.dataset.slot;
                        const slotCard = document.querySelector(`.bundle-slot-card[data-slot="${slotId}"]`);
                        if (slotCard) {
                            const formVariantField = form.querySelector(`.slot-variant-input-form[data-slot="${slotId}"]`);
                            if (formVariantField) {
                                formVariantField.value = '';
                            }
                            const pickerVariantField = document.querySelector(`.slot-variant-input[data-slot="${slotId}"]`);
                            if (pickerVariantField) {
                                pickerVariantField.value = '';
                            }
                            // Clear slot selection
                            slotSelections[slotId] = createDefaultSlotState(slotCard);
                            updateSlotSummary(slotId);
                            updateTotals();
                        }
                    }
                    // Update select button state
                    updateProductSelectButton(productCard);
                }
                return;
            }
            
            // Handle color swatch clicks
            const colorSwatch = event.target.closest('.variant-color-swatch');
            if (colorSwatch) {
                event.stopPropagation();
                event.preventDefault();
                const slotId = colorSwatch.dataset.slot;
                const productId = colorSwatch.dataset.productId;
                const selectedColor = colorSwatch.dataset.color;
                
                // Find all variant pills for this product
                const productCard = colorSwatch.closest('.slot-product-card');
                if (!productCard) return;
                
                const hiddenPills = productCard.querySelectorAll('.variant-pills-data .variant-pill');
                const sizeButtons = productCard.querySelectorAll('.variant-size-btn');
                let selectedSize = '';
                
                // Get selected size if any
                sizeButtons.forEach(btn => {
                    if (btn.classList.contains('is-active')) {
                        selectedSize = btn.dataset.size || '';
                    }
                });
                
                // Find matching variant pill from hidden pills
                // First, try to find exact match (color + size if size is selected)
                let exactMatchPill = null;
                let fallbackPill = null;
                
                hiddenPills.forEach(pill => {
                    const pillSize = pill.dataset.size || '';
                    const pillColor = pill.dataset.color || '';
                    
                    // Exact match: color matches and (no size selected or size matches)
                    if (pillColor === selectedColor && (!selectedSize || pillSize === selectedSize)) {
                        if (!exactMatchPill) {
                            exactMatchPill = pill; // Get first exact match
                        }
                    }
                    // Fallback: color matches (for when no exact match with size)
                    if (pillColor === selectedColor && !exactMatchPill) {
                        if (!fallbackPill) {
                            fallbackPill = pill;
                        }
                    }
                });
                
                // Update active states
                productCard.querySelectorAll('.variant-color-swatch').forEach(swatch => {
                    swatch.classList.toggle('is-active', swatch === colorSwatch);
                });
                
                // Only activate if the variant is in stock
                const pillToActivate = exactMatchPill || fallbackPill;
                if (pillToActivate) {
                    const isInStock = !pillToActivate.classList.contains('is-disabled');
                    const stockQty = parseInt(pillToActivate.dataset.stock || 0, 10);
                    const isAvailable = isInStock && stockQty > 0;
                    
                    if (isAvailable) {
                        // Variant is in stock - activate it normally
                        activateVariantPill(pillToActivate);
                    } else {
                        // Variant is out of stock - show it visually but don't activate
                        // Visually mark as active for UI feedback
                        productCard.querySelectorAll('.variant-pill').forEach(p => p.classList.remove('is-active'));
                        pillToActivate.classList.add('is-active');
                        
                        // Clear form fields and slot selection since it's out of stock
                        const slotId = colorSwatch.dataset.slot;
                        const slotCard = document.querySelector(`.bundle-slot-card[data-slot="${slotId}"]`);
                        if (slotCard) {
                            const formVariantField = form.querySelector(`.slot-variant-input-form[data-slot="${slotId}"]`);
                            if (formVariantField) {
                                formVariantField.value = '';
                            }
                            const pickerVariantField = document.querySelector(`.slot-variant-input[data-slot="${slotId}"]`);
                            if (pickerVariantField) {
                                pickerVariantField.value = '';
                            }
                            // Clear slot selection
                            slotSelections[slotId] = createDefaultSlotState(slotCard);
                            updateSlotSummary(slotId);
                            updateTotals();
                        }
                    }
                    // Update select button state
                    updateProductSelectButton(productCard);
                }
                return;
            }
        });

        function openSlotPicker(slotId) {
            const trigger = document.querySelector(`.slot-modal-trigger[data-slot="${slotId}"], .slot-change-btn[data-slot="${slotId}"]`);
            if (trigger) {
                trigger.click();
            } else {
                // Fallback: find any trigger for this slot
                const fallbackTrigger = document.querySelector(`[data-slot="${slotId}"].slot-modal-trigger, [data-slot="${slotId}"].slot-action-btn-elegant`);
                if (fallbackTrigger) {
                    fallbackTrigger.click();
                }
            }
        }
        
        // Add click handler for change button
        document.addEventListener('click', function(e) {
            const changeBtn = e.target.closest('.slot-change-btn');
            if (changeBtn) {
                e.preventDefault();
                e.stopPropagation();
                const slotId = changeBtn.dataset.slot;
                const modalTrigger = document.querySelector(`.slot-modal-trigger[data-slot="${slotId}"]`);
                if (modalTrigger) {
                    modalTrigger.click();
                }
            }
        });

        document.querySelectorAll('.slot-open-trigger').forEach(card => {
            card.addEventListener('click', function (event) {
                if (event.target.closest('.slot-modal-trigger') || event.target.closest('.slot-clear-selection') || event.target.closest('.slot-price-meta')) {
                    return;
                }
                openSlotPicker(this.dataset.slot);
            });
        });

        document.querySelectorAll('[data-summary-trigger]').forEach(card => {
            card.addEventListener('click', function () {
                openSlotPicker(this.dataset.summaryTrigger);
            });
        });

        document.querySelectorAll('[data-slot-overview-trigger]').forEach(card => {
            card.addEventListener('click', function (event) {
                if (event.target.closest('.slot-overview-select-btn')) {
                    return;
                }
                openSlotPicker(this.dataset.slot);
            });
        });

        document.querySelectorAll('.slot-overview-select-btn').forEach(button => {
            button.addEventListener('click', function (event) {
                event.stopPropagation();
                openSlotPicker(this.dataset.slot);
            });
        });

        document.querySelectorAll('.slot-clear-selection, .slot-clear-selection-elegant').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                clearSlotSelection(this.dataset.slot);
            });
        });

        // Variant pill clicks are now handled by event delegation above

        function showSlotModal() {
            if (slotModalInstance) {
                slotModalInstance.show();
            } else if (slotModalJQ && typeof slotModalJQ.modal === 'function') {
                slotModalJQ.modal('show');
            }
        }

        function hideSlotModal() {
            if (slotModalInstance) {
                slotModalInstance.hide();
            } else if (slotModalJQ && typeof slotModalJQ.modal === 'function') {
                slotModalJQ.modal('hide');
            }
        }
        
        function hideSlotModalWithEffect() {
            if (!slotModalEl) return;
            
            // Check if modal is actually visible
            const isVisible = slotModalEl.classList.contains('show') || 
                             (slotModalJQ && slotModalJQ.hasClass('show')) ||
                             (slotModalInstance && slotModalInstance._isShown);
            
            if (!isVisible) return;
            
            // Get modal backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            
            // Add fade-out effect to modal
            slotModalEl.style.transition = 'opacity 0.3s ease-out';
            slotModalEl.style.opacity = '0';
            
            // Add fade-out effect to backdrop if exists
            if (backdrop) {
                backdrop.style.transition = 'opacity 0.3s ease-out';
                backdrop.style.opacity = '0';
            }
            
            // Add scale-down effect to modal content
            const modalContent = slotModalEl.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.transition = 'transform 0.3s ease-out, opacity 0.3s ease-out';
                modalContent.style.transform = 'scale(0.95)';
                modalContent.style.opacity = '0';
            }
            
            // Close modal after fade animation
            setTimeout(() => {
                hideSlotModal();
                // Reset styles after modal is hidden
                setTimeout(() => {
                    slotModalEl.style.opacity = '';
                    slotModalEl.style.transition = '';
                    if (backdrop) {
                        backdrop.style.opacity = '';
                        backdrop.style.transition = '';
                    }
                    if (modalContent) {
                        modalContent.style.transform = '';
                        modalContent.style.opacity = '';
                        modalContent.style.transition = '';
                    }
                }, 100);
            }, 300);
        }

        function openSlotModal(slotId) {
            const hidden = document.getElementById(`slot-picker-${slotId}`);
            if (!hidden) {
                return;
            }
            const slotCard = document.querySelector(`.bundle-slot-card[data-slot="${slotId}"]`);
            if (slotCard) {
                slotModalTitle.textContent = slotCard.querySelector('.slot-glance-title')?.textContent?.trim() || '{{ translate('Select Products') }}';
                const typeCopy = slotCard.dataset.slotType === 'free'
                    ? '{{ translate('Pick any bonus item for this slot') }}'
                    : '{{ translate('Select an item to fulfill this slot') }}';
                slotModalSubtitle.textContent = typeCopy;
            }
            slotModalBody.innerHTML = '';
            slotModalBody.appendChild(hidden);
            hidden.classList.remove('d-none');
            activeSlotContent = {node: hidden, slotId};
            // Initialize select buttons state after modal content is loaded
            setTimeout(() => {
                updateAllSelectButtons(slotId);
            }, 100);
            showSlotModal();
        }

        const handleModalHide = function () {
            if (!activeSlotContent) {
                return;
            }
            const anchor = document.querySelector(`[data-slot-modal-anchor="${activeSlotContent.slotId}"]`);
            if (anchor) {
                anchor.appendChild(activeSlotContent.node);
            }
            activeSlotContent.node.classList.add('d-none');
            activeSlotContent = null;
            slotModalBody.innerHTML = '';
        };

        document.querySelectorAll('.slot-modal-close').forEach(btn => {
            btn.addEventListener('click', hideSlotModal);
        });

        if (slotModalJQ && typeof slotModalJQ.on === 'function') {
            slotModalJQ.on('hidden.bs.modal', handleModalHide);
        } else {
            slotModalEl.addEventListener('hidden.bs.modal', handleModalHide);
        }

        document.querySelectorAll('.slot-modal-trigger').forEach(btn => {
            btn.addEventListener('click', function () {
                openSlotModal(this.dataset.slot);
            });
        });

        galleryThumbs.forEach(btn => {
            btn.addEventListener('click', function () {
                const index = Number(this.dataset.index);
                setGalleryImage(index);
            });
        });

        thumbNavButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                if (!galleryThumbTrack) {
                    return;
                }
                // Check if thumbnails are in horizontal layout (mobile view)
                const isHorizontal = window.getComputedStyle(galleryThumbTrack).flexDirection === 'row';
                const delta = this.dataset.thumbNav === 'next' ? 140 : -140;
                
                if (isHorizontal) {
                    // Horizontal scrolling for mobile view
                    galleryThumbTrack.scrollBy({left: delta, behavior: 'smooth'});
                } else {
                    // Vertical scrolling for desktop view
                    galleryThumbTrack.scrollBy({top: delta, behavior: 'smooth'});
                }
            });
        });

        if (galleryStage && zoomPane) {
            if (pointerFine) {
                galleryStage.addEventListener('mouseenter', () => zoomPane.classList.add('is-visible'));
                galleryStage.addEventListener('mouseleave', () => zoomPane.classList.remove('is-visible'));
                galleryStage.addEventListener('mousemove', updateZoomPane);
            } else {
                zoomPane.style.display = 'none';
            }
        }

        if (pointerFine && galleryModalStageWrapper && galleryModalImage) {
            const modalZoomMove = (event) => {
                const rect = galleryModalStageWrapper.getBoundingClientRect();
                const xPercent = ((event.clientX - rect.left) / rect.width) * 100;
                const yPercent = ((event.clientY - rect.top) / rect.height) * 100;
                galleryModalImage.style.transformOrigin = `${xPercent}% ${yPercent}%`;
            };
            galleryModalStageWrapper.addEventListener('mouseenter', () => {
                galleryModalStageWrapper.classList.add('is-zooming');
            });
            galleryModalStageWrapper.addEventListener('mouseleave', () => {
                galleryModalStageWrapper.classList.remove('is-zooming');
                galleryModalImage.style.transformOrigin = 'center center';
            });
            galleryModalStageWrapper.addEventListener('mousemove', modalZoomMove);
        }

        if (fullscreenTrigger) {
            fullscreenTrigger.addEventListener('click', function (event) {
                event.stopPropagation();
                openGalleryModal(galleryState.activeIndex);
            });
        }

        if (galleryStage) {
            galleryStage.addEventListener('click', function (event) {
                if (event.target.closest('.bundle-fullscreen-trigger')) {
                    return;
                }
                openGalleryModal(galleryState.activeIndex);
            });
        }

        galleryModalNavButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const dir = this.dataset.galleryDir === 'next' ? 1 : -1;
                navigateGallery(dir);
            });
        });

        galleryModalThumbs.forEach(btn => {
            btn.addEventListener('click', function () {
                const index = Number(this.dataset.index);
                updateGalleryModal(index);
                setGalleryImage(index, {scrollIntoView: true});
            });
        });

        const registerModalState = function (open) {
            galleryState.modalOpen = open;
        };

        const galleryModalCloseButtons = galleryModalEl ? galleryModalEl.querySelectorAll('[data-bs-dismiss="modal"], [data-dismiss="modal"]') : [];

        if (galleryModalJQ && typeof galleryModalJQ.on === 'function') {
            galleryModalJQ.on('shown.bs.modal', () => registerModalState(true));
            galleryModalJQ.on('hidden.bs.modal', () => registerModalState(false));
            galleryModalCloseButtons.forEach(btn => btn.addEventListener('click', () => galleryModalJQ.modal('hide')));
        } else if (galleryModalEl) {
            galleryModalEl.addEventListener('shown.bs.modal', () => registerModalState(true));
            galleryModalEl.addEventListener('hidden.bs.modal', () => registerModalState(false));
            galleryModalCloseButtons.forEach(btn => btn.addEventListener('click', () => {
                if (galleryModalInstance) {
                    galleryModalInstance.hide();
                }
            }));
        }

        document.addEventListener('keydown', function (event) {
            if (!galleryState.modalOpen) {
                return;
            }
            if (event.key === 'ArrowRight') {
                navigateGallery(1);
            } else if (event.key === 'ArrowLeft') {
                navigateGallery(-1);
            }
        });

        setGalleryImage(galleryState.activeIndex, {scrollIntoView: false});

        document.querySelectorAll('.qty-btn').forEach(button => {
            button.addEventListener('click', function () {
                const delta = this.dataset.qtyAction === 'increase' ? 1 : -1;
                const next = Math.max(1, parseInt(quantityInput.value || 1, 10) + delta);
                quantityInput.value = next;
                updateTotals();
                refreshAllSlotSummaries();
            });
        });

        quantityInput.addEventListener('input', function () {
            if (quantityInput.value === '' || parseInt(quantityInput.value, 10) < 1) {
                quantityInput.value = 1;
            }
            updateTotals();
            refreshAllSlotSummaries();
        });

        // Handle form submission with AJAX
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Get action type from hidden input (already set by button click handler)
            const actionType = document.getElementById('bundle-action-type').value || 'add_to_cart';
            
            // Disable buttons during submission
            const actionButtons = document.querySelectorAll('.bundle-action-trigger');
            actionButtons.forEach(btn => btn.disabled = true);
            
            // Show loading state
            const originalTexts = new Map(); // Use Map for better reliability with object keys
            const addingText = '{{ translate('Adding...') }}';
            actionButtons.forEach(btn => {
                // Store original text with button element as key
                originalTexts.set(btn, btn.innerHTML);
                btn.innerHTML = '<i class="las la-spinner la-spin"></i> ' + addingText;
            });
            
            // Prepare form data
            const formData = new FormData(form);
            
            // Submit via AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (response.redirected) {
                    // If redirect, follow it (success case)
                    if (actionType === 'buy_now') {
                        window.location.href = response.url;
                    } else {
                        // For add_to_cart with redirect, just show success message
                        // The cart will be updated when the page loads
                        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                            AIZ.plugins.notify('success', '{{ translate('Bundle added to cart successfully.') }}');
                        }
                    }
                    return;
                }
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            return { error: 'Invalid response', text: text };
                        }
                    });
                }
            })
            .then(data => {
                console.log('Response data:', data); // Debug log
                if (typeof data === 'object') {
                    if (data.success) {
                        // JSON success response
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            // Show modal popup if modal_view is provided
                            console.log('Modal view exists:', !!data.modal_view); // Debug log
                            if (data.modal_view) {
                                // Check if modal exists
                                const modal = $('#addToCart');
                                if (modal.length === 0) {
                                    console.error('Add to cart modal not found');
                                    // Fallback to notification
                                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                                        AIZ.plugins.notify('success', data.message || '{{ translate('Bundle added to cart successfully.') }}');
                                    }
                                } else {
                                    // Clear modal body
                                    $('#addToCart-modal-body').html('');
                                    // Remove modal-lg class for smaller modal
                                    $('#modal-size').removeClass('modal-lg');
                                    // Show preloader
                                    $('.c-preloader').show();
                                    // Set modal content
                                    $('#addToCart-modal-body').html(data.modal_view);
                                    // Hide preloader
                                    $('.c-preloader').hide();
                                    // Use setTimeout to ensure DOM is updated before showing modal
                                    setTimeout(function() {
                                        modal.modal('show');
                                        // Initialize plugins if needed
                                        if (typeof AIZ !== 'undefined') {
                                            if (AIZ.plugins && AIZ.plugins.slickCarousel) {
                                                AIZ.plugins.slickCarousel();
                                            }
                                            if (AIZ.extra && AIZ.extra.plusMinus) {
                                                AIZ.extra.plusMinus();
                                            }
                                        }
                                    }, 100);
                                }
                            } else {
                                // Fallback: show notification if no modal
                                if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                                    AIZ.plugins.notify('success', data.message || '{{ translate('Bundle added to cart successfully.') }}');
                                }
                            }
                            
                            // Update cart count without reload
                            if (typeof updateNavCart === 'function' && data.nav_cart_view && data.cart_count !== undefined) {
                                updateNavCart(data.nav_cart_view, data.cart_count);
                            }
                            
                            // Google Tag Manager - AddToCart Event for Bundle/Group Products
                            @if (get_setting('google_tag_manager') == 1)
                            if (typeof dataLayer !== 'undefined' && data.product_data) {
                                var prod = data.product_data;
                                dataLayer.push({
                                    'event': 'add_to_cart',
                                    'ecommerce': {
                                        'currency': '{{ get_system_default_currency()->code }}',
                                        'value': parseFloat(prod.price) * parseInt(prod.quantity),
                                        'items': [{
                                            'item_id': 'bundle_' + prod.id.toString(),
                                            'item_name': (prod.name || '') + ' (Bundle)',
                                            'item_category': prod.category || '',
                                            'item_brand': prod.brand || '',
                                            'price': parseFloat(prod.price),
                                            'quantity': parseInt(prod.quantity)
                                        }]
                                    }
                                });
                            }
                            @endif
                            // End Google Tag Manager - AddToCart Event
                        }
                    } else {
                        // Error response
                        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                            AIZ.plugins.notify('danger', data.message || '{{ translate('An error occurred. Please try again.') }}');
                        } else {
                            alert(data.message || '{{ translate('An error occurred. Please try again.') }}');
                        }
                    }
                } else if (typeof data === 'string') {
                    // HTML response - might be error page
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(data, 'text/html');
                    const errorMessages = doc.querySelectorAll('.alert-danger, .is-invalid, [role="alert"]');
                    
                    if (errorMessages.length > 0) {
                        // Show errors
                        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.notify) {
                            AIZ.plugins.notify('danger', '{{ translate('Please check your selections and try again.') }}');
                        } else {
                            alert('{{ translate('Please check your selections and try again.') }}');
                        }
                        window.location.reload(); // Reload to show validation errors
                    } else {
                        // Success - reload to show updated cart
                        window.location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ translate('An error occurred. Please try again.') }}');
            })
            .finally(() => {
                // Re-enable buttons and restore their original text
                actionButtons.forEach(btn => {
                    btn.disabled = false;
                    // Restore original text from Map
                    if (originalTexts.has(btn)) {
                        btn.innerHTML = originalTexts.get(btn);
                    } else {
                        // Fallback: restore based on data-action attribute
                        const action = btn.dataset.action;
                        if (action === 'add_to_cart') {
                            btn.innerHTML = '{{ translate('Add to Cart') }}';
                        } else if (action === 'buy_now') {
                            btn.innerHTML = '{{ translate('Buy Now') }}';
                        }
                    }
                });
                // Reset action type to add_to_cart for next submission
                document.getElementById('bundle-action-type').value = 'add_to_cart';
            });
        });

        document.querySelectorAll('.bundle-action-trigger').forEach(button => {
            button.addEventListener('click', function (e) {
                // Prevent default form submission
                e.preventDefault();
                // Set the action type based on which button was clicked
                const actionType = this.dataset.action || 'add_to_cart';
                document.getElementById('bundle-action-type').value = actionType;
                // Trigger form submit event
                form.dispatchEvent(new Event('submit'));
            });
        });

        document.querySelectorAll('.bundle-slot-card').forEach(card => {
            const slotId = card.dataset.slot;
            slotSelections[slotId] = slotSelections[slotId] || createDefaultSlotState(card);
            updateSlotSummary(slotId);
        });

        document.querySelectorAll('.variant-pill.is-active').forEach(pill => {
            activateVariantPill(pill);
        });

        updateTotals();
        refreshAllSlotSummaries();

        const bundleTabs = document.querySelectorAll('#bundleDetailsTabs button[data-bs-toggle="tab"]');
        bundleTabs.forEach(tab => {
            tab.addEventListener('click', function (event) {
                event.preventDefault();
                const targetId = this.getAttribute('data-bs-target');
                const targetPane = document.querySelector(targetId);
                
                if (targetPane) {
                    document.querySelectorAll('#bundleDetailsTabs .nav-link').forEach(link => {
                        link.classList.remove('active');
                    });
                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });
                    
                    this.classList.add('active');
                    targetPane.classList.add('show', 'active');
                }
            });
        });
    })();
</script>
@endsection

