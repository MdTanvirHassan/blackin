@php
    $slotProducts = $slotData['product_ids'] ?? [];
    $discountType = $slotData['discount_type'] ?? 'none';
    $discountValue = $slotData['discount_value'] ?? '';
    $slotName = $slotData['name'] ?? '';
    $slotDefaultLabel = translate('New Slot');
    $slotPosition = is_numeric($slotIndex) ? intval($slotIndex) + 1 : null;
    $productCount = count($slotProducts);
    $slotNamespace = $slotNamespace ?? 'slots';
    $slotGroupKey = $slotGroupKey ?? 'paid';
    $isFreeSlot = $isFreeSlot ?? false;
    $slotBadgeLabel = $isFreeSlot ? translate('Free Slot') : translate('Slot');

    if ($discountType === 'flat' && $discountValue !== '') {
        $discountSummary = str_replace(':amount', $discountValue, translate('Flat :amount off'));
        $flatHelpClass = '';
        $percentHelpClass = 'd-none';
    } elseif ($discountType === 'percent' && $discountValue !== '') {
        $discountSummary = str_replace(':value', $discountValue, translate(':value% off'));
        $flatHelpClass = 'd-none';
        $percentHelpClass = '';
    } else {
        $discountSummary = translate('No slot discount');
        $flatHelpClass = 'd-none';
        $percentHelpClass = 'd-none';
    }

    if ($productCount === 0) {
        $productSummary = translate('No products selected yet');
    } elseif ($productCount === 1) {
        $productSummary = translate('1 eligible product');
    } else {
        $productSummary = str_replace(':count', $productCount, translate(':count eligible products'));
    }
@endphp

<div class="card slot-card shadow-sm border-0 mb-3"
     data-slot-index="{{ $slotIndex }}"
     data-slot-default-label="{{ $slotDefaultLabel }}"
     data-discount-label-none="{{ translate('No slot discount') }}"
     data-discount-label-flat="{{ translate('Flat :amount off') }}"
     data-discount-label-percent="{{ translate(':value% off') }}"
     data-product-label-empty="{{ translate('No products selected yet') }}"
     data-product-label-single="{{ translate('1 eligible product') }}"
     data-product-label-multiple="{{ translate(':count eligible products') }}"
     data-slot-group="{{ $slotGroupKey }}">
    <div class="card-header bg-white border-0 d-flex flex-wrap align-items-center justify-content-between">
        <div class="d-flex align-items-center flex-grow-1 min-w-0 slot-title-wrapper">
            <span class="badge badge-soft-primary mr-2">
                {{ $slotBadgeLabel }} <span class="slot-order-text">{{ $slotPosition ?? '—' }}</span>
            </span>
            <h6 class="mb-0 slot-title-text text-truncate">{{ $slotName ?: $slotDefaultLabel }}</h6>
        </div>
        <div class="btn-group btn-group-sm slot-header-actions">
            <button type="button" class="btn btn-icon btn-soft-secondary slot-collapse-toggle" aria-label="{{ translate('Collapse slot') }}">
                <i class="las la-compress-arrows-alt"></i>
            </button>
            <button type="button" class="btn btn-icon btn-soft-secondary slot-move-up" aria-label="{{ translate('Move slot up') }}">
                <i class="las la-arrow-up"></i>
            </button>
            <button type="button" class="btn btn-icon btn-soft-secondary slot-move-down" aria-label="{{ translate('Move slot down') }}">
                <i class="las la-arrow-down"></i>
            </button>
            <button type="button" class="btn btn-icon btn-soft-primary slot-bulk-apply-trigger" aria-label="{{ translate('Copy this selection to other slots') }}">
                <i class="las la-clone"></i>
            </button>
            <button type="button" class="btn btn-icon btn-soft-danger remove-slot-btn" aria-label="{{ translate('Remove slot') }}">
                <i class="las la-trash-alt"></i>
            </button>
        </div>
    </div>
    <div class="card-body slot-body border-top">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="fs-13">{{ translate('Slot Name') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control slot-name-input" name="{{ $slotNamespace }}[{{ $slotIndex }}][name]" value="{{ $slotName }}" placeholder="{{ translate('e.g. Shoes') }}" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="fs-13">{{ translate('Discount Type') }}</label>
                    <select class="form-control aiz-selectpicker slot-discount-type" name="{{ $slotNamespace }}[{{ $slotIndex }}][discount_type]" data-live-search="false">
                        <option value="none" {{ $discountType == 'none' ? 'selected' : '' }}>{{ translate('No Discount') }}</option>
                        <option value="flat" {{ $discountType == 'flat' ? 'selected' : '' }}>{{ translate('Flat Amount') }}</option>
                        <option value="percent" {{ $discountType == 'percent' ? 'selected' : '' }}>{{ translate('Percent Off') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-0">
                    <label class="fs-13">{{ translate('Discount Value') }}</label>
                    <input type="number" class="form-control slot-discount-value" name="{{ $slotNamespace }}[{{ $slotIndex }}][discount_value]" value="{{ $discountValue }}" min="0" step="0.01" placeholder="{{ translate('Amount or %') }}">
                    <small class="text-muted slot-discount-help slot-discount-help-flat {{ $flatHelpClass }}">{{ translate('Applied as flat deduction from product price') }}</small>
                    <small class="text-muted slot-discount-help slot-discount-help-percent {{ $percentHelpClass }}">{{ translate('Applied as percentage off (0-100)') }}</small>
                </div>
            </div>
        </div>

        <div class="form-group mt-3 mb-0">
            <label class="fs-13">{{ translate('Eligible Products') }} <span class="text-danger">*</span></label>
            <select class="form-control aiz-selectpicker slot-products-select" name="{{ $slotNamespace }}[{{ $slotIndex }}][product_ids][]" data-live-search="true" multiple required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ in_array($product->id, $slotProducts) ? 'selected' : '' }}>
                        {{ $product->getTranslation('name') }} - {{ single_price($product->unit_price) }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted d-block">{{ translate('Customer must choose one product from this slot') }}</small>
        </div>

        <input type="hidden" class="slot-sort-order" name="{{ $slotNamespace }}[{{ $slotIndex }}][sort_order]" value="{{ $slotData['sort_order'] ?? $slotIndex }}">
        <input type="hidden" name="{{ $slotNamespace }}[{{ $slotIndex }}][is_free]" value="{{ $isFreeSlot ? 1 : 0 }}">
    </div>
    <div class="card-footer bg-soft-secondary d-flex flex-wrap justify-content-between align-items-center slot-card-footer">
        <span class="small text-muted slot-discount-summary">
            <i class="las la-badge-percent mr-1"></i>
            <span class="slot-discount-summary-text">{{ $discountSummary }}</span>
        </span>
        <span class="small text-muted slot-product-summary">
            <i class="las la-layer-group mr-1"></i>
            <span class="slot-product-summary-text">{{ $productSummary }}</span>
        </span>
    </div>
</div>


