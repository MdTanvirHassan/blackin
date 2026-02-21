@php
    $group_product_url = route('group_products.show', filled($group_product->slug) ? $group_product->slug : $group_product->id);
    $thumbnail = $group_product->thumbnail_img ? uploaded_asset($group_product->thumbnail_img) : static_asset('assets/img/placeholder.jpg');
@endphp

<div class="aiz-card-box h-auto bg-white p-1 hov-scale-img border-none rounded-lg shadow-sm hove-shadow-md product-box-hover" style="transition: background-color 0.3s ease;" >
    <div class="position-relative  img-fit overflow-hidden" style="aspect-ratio: 4/5; min-height: 240px; max-height: 400px;">
        <!-- Image -->
        <a href="{{ $group_product_url }}" class="d-block bg-light rounded-lg p-0 h-100">
            <img class="lazyload mx-auto img-cover has-transition rounded-lg w-100 h-100"
                src="{{ $thumbnail }}"
                alt="{{ $group_product->name }}" title="{{ $group_product->name }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>

        <!-- Discount tag -->
        @if ($group_product->has_discount)
            <span class="absolute-top-left bg-primary ml-1 mt-1 fs-11 fw-700 text-white px-2 text-center"
                style="padding-top:2px;padding-bottom:2px;">
                @if($group_product->discount_type == 'percentage')
                    -{{ $group_product->discount_amount }}%
                @else
                    -{{ single_price($group_product->discount_amount) }}
                @endif
            </span>
        @endif

        <!-- Bundle tag -->
        <span class="absolute-top-right bg-success ml-1 mt-1 fs-11 fw-700 text-white px-2"
            style="padding-top:2px;padding-bottom:2px;">
            {{ translate('Bundle') }}
        </span>
    </div>

    <div class="p-2 py-md-3 text-left">
        <!-- Group product name -->
        <h3 class="fw-400 fs-13 text-truncate-2 lh-1-4 mb-0 h-35px text-left">
            <a href="{{ $group_product_url }}" class="d-block text-reset hov-text-primary"
                title="{{ $group_product->name }}">{{ $group_product->name }}</a>
        </h3>
        
        <!-- Bundle deal info -->
        <div class="fs-12 text-secondary mt-1 mb-2">
            @php
                $dealCopy = str_replace(
                    [':paid', ':free'],
                    [$group_product->buy_quantity, $group_product->free_quantity],
                    translate('Buy :paid, get :free free')
                );
            @endphp
            <!-- {{ $dealCopy }} -->
        </div>
        
        <!-- Price breakdown -->
        @php
            $boxBasePrice = 0;
            $boxDiscountedPrice = 0;
            $currencySymbol = currency_symbol();
            
            foreach ($group_product->slots as $slot) {
                $slotProducts = $slot->slotItems;
                $avgPrice = $slotProducts->count() > 0 
                    ? $slotProducts->average(function ($item) { return optional($item->product)->unit_price ?? 0; })
                    : 0;
                
                if ($avgPrice > 0) {
                    $boxBasePrice += $avgPrice;
                    
                    $discountedPrice = $avgPrice;
                    if ($slot->discount_type === 'percentage') {
                        $discountedPrice = $avgPrice * (1 - ($slot->discount_value / 100));
                    } elseif ($slot->discount_type === 'flat') {
                        $discountedPrice = max(0, $avgPrice - $slot->discount_value);
                    }
                    
                    if (!$slot->is_free) {
                        $boxDiscountedPrice += $discountedPrice;
                    }
                }
            }
        @endphp
        
        <div class="row g-2 mt-1">
            <div class="col-auto">
                <span class="text-muted d-block fs-10 mb-1">{{ translate('Base Price') }}</span>
                <span class="text-danger text-decoration-line-through fs-12 fw-700">{{ $currencySymbol }}{{ number_format($boxBasePrice, 2) }}</span>
            </div>
            <div class="col-auto">
                <span class="text-muted d-block fs-10 mb-1">{{ translate('Discounted Price') }}</span>
                <span class="fs-12 fw-700" style="color: var(--primary);">{{ $currencySymbol }}{{ number_format($boxDiscountedPrice, 2) }}</span>
            </div>
            <div class="col-auto {{ $boxBasePrice > $boxDiscountedPrice ? '' : 'd-none' }}">
                <span class="text-uppercase fs-9 text-muted d-block mb-1">{{ translate('Save') }}</span>
                <strong class="fs-11 text-success">{{ $currencySymbol }}{{ number_format(max(0, $boxBasePrice - $boxDiscountedPrice), 2) }}</strong>
            </div>
        </div>
    </div>

    <!-- View Details button -->
    <div class="d-flex justify-content-center pt-2 border-top border-light">
        <a class="shadow-sm btn btn-sm btn-soft-dark-base btn btn-outline-dark hov-svg-white border border-dark border-1 hov-text-white rounded-4 d-flex align-items-center gap-2"
            href="{{ $group_product_url }}">
            <i class="las la-eye fs-18"></i>
            <span>{{ translate('View Details') }}</span>
        </a>
    </div>
</div>

<style>
    .product-box-hover:hover {
        background-color: #f8f9fa !important;
    }
</style>

