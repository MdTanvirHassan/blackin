@php
    $slotProducts = $slot->slotItems->filter(fn($item) => $item->product);
    $slotId = $slot->id;
    $slotDiscountType = $slot->discount_type ?? 'none';
    $slotDiscountValue = $slot->discount_value ?? 0;
    $activeSelection = null;
    $oldSelection = old('slots.' . $slotId);
    if (is_array($oldSelection) && isset($oldSelection['product_id'])) {
        $oldProductId = (int)$oldSelection['product_id'];
        $oldVariant = $oldSelection['variant'] ?? '';
        foreach ($slotProducts as $slotItem) {
            if ($slotItem->product && $slotItem->product->id === $oldProductId) {
                $product = $slotItem->product;
                $stocks = $product->variant_product ? $product->stocks : collect([$product->stocks->first()]);
                foreach ($stocks as $stock) {
                    if (!$stock) {
                        continue;
                    }
                    $variantKey = $stock->variant ?? '';
                    if ($variantKey === $oldVariant) {
                        $activeSelection = [
                            'product_id' => $product->id,
                            'variant' => $variantKey,
                        ];
                        break 2;
                    }
                }
            }
        }
    }
    $activeProductId = $activeSelection['product_id'] ?? null;
    $activeVariantKey = $activeSelection['variant'] ?? '';
@endphp

<input type="hidden" name="slots[{{ $slotId }}][variant]" class="slot-variant-input" data-slot="{{ $slotId }}" value="{{ $activeVariantKey }}">

@if($slotProducts->isEmpty())
    <div class="alert alert-warning mb-0">
        {{ translate('No products have been assigned to this slot yet.') }}
    </div>
@else
    <div class="row g-4">
        @foreach($slotProducts as $slotItem)
            @php
                $product = $slotItem->product;
                $productStocks = $product->variant_product ? $product->stocks : collect([$product->stocks->first()]);
                $productActive = $activeProductId === $product->id;
                $choiceOptions = collect(json_decode($product->choice_options, true) ?? []);
                $variantFallbackLabels = [
                    translate('Color'),
                    translate('Size'),
                ];
                $productPriceSummary = null;
                foreach ($productStocks as $priceProbe) {
                    if (!$priceProbe) {
                        continue;
                    }
                    $baseDisplayProbe = home_base_price_by_stock_id($priceProbe->id);
                    $slotAdjustedProbe = home_base_price_by_stock_id($priceProbe->id, false);
                    if ($slotDiscountType === 'flat') {
                        $slotAdjustedProbe = max($slotAdjustedProbe - $slotDiscountValue, 0);
                    } elseif ($slotDiscountType === 'percent') {
                        $slotAdjustedProbe = max($slotAdjustedProbe * (100 - $slotDiscountValue) / 100, 0);
                    }
                    $productPriceSummary = [
                        'base' => $baseDisplayProbe,
                        'discounted' => format_price($slotAdjustedProbe),
                    ];
                    break;
                }
                if (!$productPriceSummary) {
                    $productPriceSummary = [
                        'base' => home_price($product),
                        'discounted' => home_discounted_base_price($product),
                    ];
                }
            @endphp
            <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="slot-product-card {{ $productActive ? 'is-active' : '' }}" data-slot="{{ $slotId }}" data-product-id="{{ $product->id }}">
                    <div class="slot-product-card__image">
                        <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}">
                    </div>
                    <div class="slot-product-card__details">
                        <h6 class="slot-product-card__title">{{ $product->getTranslation('name') }}</h6>
                        <div class="slot-product-card__price">
                            <div class="slot-product-card__price-row">
                                <span class="slot-product-card__price-label">{{ translate('Base price') }}:</span>
                                <span class="slot-product-card__price-base">{{ $productPriceSummary['base'] }}</span>
                            </div>
                            <div class="slot-product-card__price-row">
                                <span class="slot-product-card__price-label">{{ translate('Price') }}:</span>
                                <span class="slot-product-card__price-discounted">{{ $productPriceSummary['discounted'] }}</span>
                            </div>
                        </div>
                        <input type="radio"
                               class="slot-product-radio d-none"
                               id="slot-product-{{ $slotId }}-{{ $product->id }}"
                               name="slots[{{ $slotId }}][product_id]"
                               value="{{ $product->id }}"
                               data-product-name="{{ $product->getTranslation('name') }}"
                               {{ $productActive ? 'checked' : '' }}>
                    </div>
                    <div class="variant-selection-group">
                        @php
                            $renderedVariant = false;
                            $sizeVariants = [];
                            $colorVariants = [];
                            $allVariants = [];
                            
                            foreach ($productStocks as $stock) {
                                if(!$stock) continue;
                                $variantKey = $stock->variant ?? '';
                                $variantLabel = $variantKey !== '' ? $variantKey : '';
                                $variantParts = $variantKey !== '' ? preg_split('/\s*(?:\-|\/|\||,|·)\s*/', $variantKey) : [];
                                if ($variantKey !== '' && count($variantParts) <= 1) {
                                    $variantParts = preg_split('/\s+/', $variantKey);
                                }
                                $variantMeta = [];
                                $sizeValue = '';
                                $colorValue = '';
                                
                                foreach ($choiceOptions as $idx => $choice) {
                                    $label = $choice['title'] ?? ($idx === 0 ? translate('Color') : ($idx === 1 ? translate('Size') : translate('Attribute') . ' ' . ($idx + 1)));
                                    $value = trim($variantParts[$idx] ?? '');
                                    if ($value === '') {
                                        continue;
                                    }
                                    $variantMeta[] = [
                                        'label' => $label,
                                        'value' => $value,
                                    ];
                                    if (stripos($label, translate('Size')) !== false || stripos($label, 'size') !== false) {
                                        $sizeValue = $value;
                                    }
                                    if (stripos($label, translate('Color')) !== false || stripos($label, 'color') !== false) {
                                        $colorValue = $value;
                                    }
                                }
                                
                                if ($variantKey !== '' && count($variantParts) > count($variantMeta)) {
                                    for ($i = count($variantMeta); $i < count($variantParts); $i++) {
                                        $value = trim($variantParts[$i]);
                                        if ($value === '') {
                                            continue;
                                        }
                                        $label = $i === 0 ? translate('Color') : ($i === 1 ? translate('Size') : translate('Attribute') . ' ' . ($i + 1));
                                        $variantMeta[] = [
                                            'label' => $label,
                                            'value' => $value,
                                        ];
                                        if ($i === 0) {
                                            $colorValue = $value;
                                        } elseif ($i === 1) {
                                            $sizeValue = $value;
                                        }
                                    }
                                }
                                
                                if (empty($variantMeta) && $variantLabel !== '') {
                                    $variantMeta[] = [
                                        'label' => $variantFallbackLabels[0] ?? translate('Variant'),
                                        'value' => $variantLabel,
                                    ];
                                }
                                
                                $baseVariantRaw = home_base_price_by_stock_id($stock->id, false);
                                $variantPriceRaw = $baseVariantRaw;
                                if ($slotDiscountType === 'flat') {
                                    $variantPriceRaw = max($variantPriceRaw - $slotDiscountValue, 0);
                                } elseif ($slotDiscountType === 'percent') {
                                    $variantPriceRaw = max($variantPriceRaw * (100 - $slotDiscountValue) / 100, 0);
                                }
                                $variantQty = $stock->qty ?? 0;
                                $isOut = $variantQty <= 0;
                                $isActiveVariant = $productActive && $activeVariantKey === $variantKey;
                                
                                $variantData = [
                                    'key' => $variantKey,
                                    'label' => $variantLabel,
                                    'size' => $sizeValue,
                                    'color' => $colorValue,
                                    'meta' => $variantMeta,
                                    'basePrice' => $baseVariantRaw,
                                    'price' => $variantPriceRaw,
                                    'stock' => $variantQty,
                                    'outOfStock' => $isOut,
                                    'active' => $isActiveVariant,
                                    'stockObj' => $stock,
                                ];
                                
                                $allVariants[] = $variantData;
                                
                                if ($sizeValue && !isset($sizeVariants[$sizeValue])) {
                                    $sizeVariants[$sizeValue] = $variantData;
                                }
                                if ($colorValue && !isset($colorVariants[$colorValue])) {
                                    $colorVariants[$colorValue] = $variantData;
                                }
                                
                                $renderedVariant = true;
                            }
                            
                            // Get unique sizes and colors
                            $uniqueSizes = array_unique(array_filter(array_column($allVariants, 'size')));
                            $uniqueColors = array_unique(array_filter(array_column($allVariants, 'color')));
                        @endphp
                        
                        @if($renderedVariant)
                            @if(count($uniqueSizes) > 0)
                                <div class="variant-option-group">
                                    <label class="variant-option-label">{{ translate('Size') }}</label>
                                    <div class="variant-size-buttons">
                                        @foreach($uniqueSizes as $size)
                                            @php
                                                $sizeVariantsForSize = array_filter($allVariants, function($v) use ($size) {
                                                    return $v['size'] === $size;
                                                });
                                                $firstSizeVariant = reset($sizeVariantsForSize);
                                                $isSizeActive = $productActive && in_array($size, array_column(array_filter($allVariants, function($v) { return $v['active']; }), 'size'));
                                            @endphp
                                            <button type="button"
                                                    class="variant-size-btn {{ $isSizeActive ? 'is-active' : '' }}"
                                                    data-size="{{ $size }}"
                                                    data-slot="{{ $slotId }}"
                                                    data-product-id="{{ $product->id }}"
                                                    data-variants='@json(array_values($sizeVariantsForSize))'>
                                                {{ $size }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            @if(count($uniqueColors) > 0)
                                <div class="variant-option-group">
                                    <label class="variant-option-label">{{ translate('Color') }}</label>
                                    <div class="variant-color-swatches">
                                        @foreach($uniqueColors as $color)
                                            @php
                                                $colorVariantsForColor = array_filter($allVariants, function($v) use ($color) {
                                                    return $v['color'] === $color;
                                                });
                                                $firstColorVariant = reset($colorVariantsForColor);
                                                $isColorActive = $productActive && in_array($color, array_column(array_filter($allVariants, function($v) { return $v['active']; }), 'color'));
                                                
                                                // Try to get color hex from Color model
                                                $colorHex = '#4a5568'; // Default dark gray
                                                try {
                                                    $colorModel = \App\Models\Color::whereRaw('LOWER(name) = ?', [strtolower(trim($color))])->first();
                                                    if ($colorModel && $colorModel->code) {
                                                        $colorHex = $colorModel->code;
                                                    } else {
                                                        // Fallback mapping for common color names
                                                        $colorNameLower = strtolower(trim($color));
                                                        $colorMap = [
                                                            'red' => '#ff0000',
                                                            'blue' => '#0000ff',
                                                            'green' => '#008000',
                                                            'yellow' => '#ffff00',
                                                            'orange' => '#ffa500',
                                                            'purple' => '#800080',
                                                            'pink' => '#ffc0cb',
                                                            'black' => '#000000',
                                                            'white' => '#ffffff',
                                                            'gray' => '#808080',
                                                            'grey' => '#808080',
                                                            'brown' => '#a52a2a',
                                                            'cyan' => '#00ffff',
                                                            'darkcyan' => '#008b8b',
                                                            'magenta' => '#ff00ff',
                                                            'lime' => '#00ff00',
                                                            'navy' => '#000080',
                                                            'maroon' => '#800000',
                                                            'olive' => '#808000',
                                                            'teal' => '#008080',
                                                            'silver' => '#c0c0c0',
                                                            'gold' => '#ffd700',
                                                            'beige' => '#f5f5dc',
                                                            'ivory' => '#fffff0',
                                                            'tan' => '#d2b48c',
                                                            'salmon' => '#fa8072',
                                                            'coral' => '#ff7f50',
                                                            'turquoise' => '#40e0d0',
                                                            'lavender' => '#e6e6fa',
                                                            'violet' => '#ee82ee',
                                                            'indigo' => '#4b0082',
                                                            'khaki' => '#f0e68c',
                                                            'plum' => '#dda0dd',
                                                            'crimson' => '#dc143c',
                                                            'azure' => '#f0ffff',
                                                            'mint' => '#f5fffa',
                                                            'peach' => '#ffdab9',
                                                            'coral' => '#ff7f50',
                                                            'amber' => '#ffbf00',
                                                            'burgundy' => '#800020',
                                                            'charcoal' => '#36454f',
                                                            'cream' => '#fffdd0',
                                                            'emerald' => '#50c878',
                                                            'forest' => '#228b22',
                                                            'jade' => '#00a86b',
                                                            'lemon' => '#fff700',
                                                            'lilac' => '#c8a2c8',
                                                            'mauve' => '#e0b0ff',
                                                            'mustard' => '#ffdb58',
                                                            'pearl' => '#f8f6f0',
                                                            'periwinkle' => '#ccccff',
                                                            'rose' => '#ff007f',
                                                            'ruby' => '#e0115f',
                                                            'sapphire' => '#0f52ba',
                                                            'scarlet' => '#ff2400',
                                                            'tangerine' => '#ff9500',
                                                            'wine' => '#722f37',
                                                        ];
                                                        if (isset($colorMap[$colorNameLower])) {
                                                            $colorHex = $colorMap[$colorNameLower];
                                                        }
                                                    }
                                                } catch (\Exception $e) {
                                                    // If any error occurs, use default
                                                    $colorHex = '#4a5568';
                                                }
                                            @endphp
                                            <button type="button"
                                                    class="variant-color-swatch {{ $isColorActive ? 'is-active' : '' }}"
                                                    data-color="{{ $color }}"
                                                    data-slot="{{ $slotId }}"
                                                    data-product-id="{{ $product->id }}"
                                                    data-variants='@json(array_values($colorVariantsForColor))'
                                                    style="background-color: {{ $colorHex }};"
                                                    title="{{ $color }}">
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            @if(count($uniqueSizes) == 0 && count($uniqueColors) == 0 && count($allVariants) > 0)
                                <div class="variant-option-group">
                                    @foreach($allVariants as $variantData)
                                        @if($variantData['label'] && $variantData['label'] !== 'Default' && $variantData['label'] !== translate('Default'))
                                            <button type="button"
                                                    class="variant-pill {{ $variantData['active'] ? 'is-active' : '' }} {{ $variantData['outOfStock'] ? 'is-disabled' : '' }}"
                                                    data-slot="{{ $slotId }}"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->getTranslation('name') }}"
                                                    data-product-image="{{ uploaded_asset($product->thumbnail_img) }}"
                                                    data-variant="{{ $variantData['key'] }}"
                                                    data-variant-label="{{ $variantData['label'] }}"
                                                    data-base-price="{{ $variantData['basePrice'] }}"
                                                    data-variant-meta='@json($variantData['meta'])'
                                                    data-price="{{ $variantData['price'] }}"
                                                    data-stock="{{ $variantData['stock'] }}"
                                                    {{ $variantData['outOfStock'] ? 'disabled' : '' }}>
                                                <span>{{ $variantData['label'] }}</span>
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Hidden variant pills for JavaScript to use -->
                            <div class="d-none variant-pills-data">
                                @foreach($allVariants as $variantData)
                                    <button type="button"
                                            class="variant-pill {{ $variantData['active'] ? 'is-active' : '' }} {{ $variantData['outOfStock'] ? 'is-disabled' : '' }}"
                                            data-slot="{{ $slotId }}"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->getTranslation('name') }}"
                                            data-product-image="{{ uploaded_asset($product->thumbnail_img) }}"
                                            data-variant="{{ $variantData['key'] }}"
                                            data-variant-label="{{ $variantData['label'] }}"
                                            data-base-price="{{ $variantData['basePrice'] }}"
                                            data-variant-meta='@json($variantData['meta'])'
                                            data-price="{{ $variantData['price'] }}"
                                            data-stock="{{ $variantData['stock'] }}"
                                            data-size="{{ $variantData['size'] }}"
                                            data-color="{{ $variantData['color'] }}"
                                            {{ $variantData['outOfStock'] ? 'disabled' : '' }}>
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="variant-option-group">
                                <span class="text-muted small">{{ translate('Stock details unavailable') }}</span>
                            </div>
                        @endif
                    </div>
                    <!-- Select Button -->
                    <div class="slot-product-select-btn-wrapper mt-3 pt-3 border-top">
                        <button type="button" 
                                class="btn btn-primary btn-block rounded-5 slot-product-select-btn" 
                                data-slot="{{ $slotId }}" 
                                data-product-id="{{ $product->id }}"
                                disabled>
                            <i class="las la-check-circle mr-1"></i>
                            {{ translate('Select') }}
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@error('slots.' . $slotId . '.product_id')
    <div class="text-danger small mt-2">{{ $message }}</div>
@enderror
@error('slots.' . $slotId . '.variant')
    <div class="text-danger small mt-1">{{ $message }}</div>
@enderror

