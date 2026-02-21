@foreach (['paid' => false, 'free' => true] as $groupKey => $isFreeSlot)
    @php
        $slotTemplateHtml = view('backend.product.group_products.partials.slot_form', [
            'slotIndex' => '__INDEX__',
            'slotData' => [
                'name' => '',
                'discount_type' => 'none',
                'discount_value' => '',
                'product_ids' => [],
                'sort_order' => '__INDEX__',
            ],
            'products' => $products,
            'slotNamespace' => $groupKey === 'paid' ? 'slots_paid' : 'slots_free',
            'slotGroupKey' => $groupKey,
            'isFreeSlot' => $isFreeSlot,
            'isFirst' => false,
        ])->render();
    @endphp

    <script type="text/template" id="slot-template-{{ $groupKey }}">
    {!! $slotTemplateHtml !!}
    </script>
@endforeach
