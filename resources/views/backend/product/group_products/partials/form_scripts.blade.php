<script type="text/javascript">
    var slotDiscountPendingTexts = {
        flat: "{{ translate('Flat discount configured') }}",
        percent: "{{ translate('Percent discount configured') }}"
    };

    var slotStatusLabels = {
        paid: "{{ translate('slots') }}",
        free: "{{ translate('free slots') }}"
    };

    var slotGroups = {
        paid: {
            container: '#paid_slots_container',
            template: '#slot-template-paid',
            qtyInput: '#buy_quantity',
            status: '#paid_slot_status',
            min: 1
        },
        free: {
            container: '#free_slots_container',
            template: '#slot-template-free',
            qtyInput: '#free_quantity',
            status: '#free_slot_status',
            section: '#free_slots_section',
            min: 0
        }
    };

    var slotIndexCounters = {
        paid: 0,
        free: 0
    };

var slotBulkModal = {
    $modal: null,
    $slotName: null,
    $targetInputs: null
};

var bulkApplyState = {
    card: null
};

var slotBulkMessages = {
    chooseTarget: "{{ translate('Select a target group before applying.') }}",
    noProducts: "{{ translate('Please select at least one product in the source slot.') }}",
    success: "{{ translate('Selection copied to :count slot(s).') }}",
    none: "{{ translate('No eligible slots were updated.') }}"
};

    function toggleDiscountFields() {
        if ($('#has_discount').is(':checked')) {
            $('#discount_fields').removeClass('d-none');
        } else {
            $('#discount_fields').addClass('d-none');
        }
    }

    function refreshSlotPlugins() {
        if (window.AIZ && AIZ.plugins && typeof AIZ.plugins.bootstrapSelect === 'function') {
            AIZ.plugins.bootstrapSelect('refresh');
        }
    }

    function formatTemplate(template, replacements) {
        var output = template || '';
        if (!replacements) {
            return output;
        }
        Object.keys(replacements).forEach(function (key) {
            var regex = new RegExp(':' + key, 'g');
            output = output.replace(regex, replacements[key]);
        });
        return output;
    }

    function syncSlotDiscountState($card) {
        var type = $card.find('.slot-discount-type').val();
        var $valueInput = $card.find('.slot-discount-value');

        $valueInput.prop('disabled', type === 'none');

        if (type === 'percent') {
            $valueInput.attr('max', 100);
        } else {
            $valueInput.removeAttr('max');
        }

        $card.find('.slot-discount-help').hide();
        if (type === 'flat') {
            $card.find('.slot-discount-help-flat').show();
        } else if (type === 'percent') {
            $card.find('.slot-discount-help-percent').show();
        }

        updateSlotMeta($card);
    }

    function syncAllSlotDiscountStates() {
        $('.slot-card').each(function () {
            syncSlotDiscountState($(this));
        });
    }

    function updateSlotSortOrder() {
        Object.keys(slotGroups).forEach(function (groupKey) {
            var selector = slotGroups[groupKey].container + ' .slot-card';
            $(selector).each(function (index) {
                $(this).find('.slot-sort-order').val(index);
                $(this).find('.slot-order-text').text(index + 1);
            });
        });
    }

    function syncSlotRemoveButtons() {
        Object.keys(slotGroups).forEach(function (groupKey) {
            var minCount = slotGroups[groupKey].min;
            var $cards = $(slotGroups[groupKey].container + ' .slot-card');
            if ($cards.length <= minCount) {
                $cards.find('.remove-slot-btn').prop('disabled', true).addClass('disabled');
            } else {
                $cards.find('.remove-slot-btn').prop('disabled', false).removeClass('disabled');
            }
        });
    }

    function toggleFreeSection() {
        var freeQuantity = parseInt($('#free_quantity').val(), 10) || 0;
        if (slotGroups.free.section) {
            if (freeQuantity > 0) {
                $(slotGroups.free.section).removeClass('d-none');
            } else {
                $(slotGroups.free.section).addClass('d-none');
            }
        }
    }

    function syncSlotCountIndicators() {
        Object.keys(slotGroups).forEach(function (groupKey) {
            var group = slotGroups[groupKey];
            var required = parseInt($(group.qtyInput).val(), 10) || 0;
            if (groupKey === 'paid' && required < group.min) {
                required = group.min;
                $(group.qtyInput).val(required);
            }
            var count = $(group.container + ' .slot-card').length;
            var $status = $(group.status);
            if ($status.length) {
                if (groupKey === 'free' && required === 0) {
                    $status.text('');
                } else {
                    $status.text(count + ' / ' + required + ' ' + slotStatusLabels[groupKey]);
                }
            }
        });
    }

    function updateSlotMeta($card) {
        if (!$card || !$card.length) {
            return;
        }

        var defaultLabel = $card.data('slot-default-label') || 'New Slot';
        var name = $.trim($card.find('.slot-name-input').val());
        var displayName = name.length ? name : defaultLabel;
        $card.find('.slot-title-text').text(displayName);

        var discountType = $card.find('.slot-discount-type').val();
        var rawValueInput = $card.find('.slot-discount-value').val();
        var hasRawValue = rawValueInput !== '' && rawValueInput !== null;
        var numericValue = parseFloat(rawValueInput);
        var hasNumericValue = hasRawValue && !isNaN(numericValue);
        var discountSummary = $card.data('discount-label-none') || 'No slot discount';

        if (discountType === 'flat') {
            if (hasNumericValue && numericValue > 0) {
                discountSummary = formatTemplate($card.data('discount-label-flat'), {
                    amount: rawValueInput
                });
            } else if (slotDiscountPendingTexts.flat) {
                discountSummary = slotDiscountPendingTexts.flat;
            }
        } else if (discountType === 'percent') {
            if (hasNumericValue && numericValue > 0) {
                if (numericValue > 100) {
                    numericValue = 100;
                    $card.find('.slot-discount-value').val(100);
                }
                discountSummary = formatTemplate($card.data('discount-label-percent'), {
                    value: numericValue
                });
            } else if (slotDiscountPendingTexts.percent) {
                discountSummary = slotDiscountPendingTexts.percent;
            }
        }

        $card.find('.slot-discount-summary-text').text(discountSummary);

        var selectedProducts = $card.find('.slot-products-select option:selected').length;
        var productSummary;
        if (selectedProducts === 0) {
            productSummary = $card.data('product-label-empty') || 'No products selected yet';
        } else if (selectedProducts === 1) {
            productSummary = $card.data('product-label-single') || '1 eligible product';
        } else {
            productSummary = formatTemplate($card.data('product-label-multiple'), {
                count: selectedProducts
            });
        }

        $card.find('.slot-product-summary-text').text(productSummary);
    }

function notifySlotBuilder(type, message) {
    if (!message) {
        return;
    }
    if (window.AIZ && AIZ.plugins && typeof AIZ.plugins.notify === 'function') {
        AIZ.plugins.notify(type, message);
    } else {
        if (type === 'danger' || type === 'warning') {
            console.warn(message);
        } else {
            console.log(message);
        }
    }
}

function openSlotBulkModal($card) {
    if (!slotBulkModal.$modal || !$card || !$card.length) {
        return;
    }
    bulkApplyState.card = $card;
    var slotName = $.trim($card.find('.slot-name-input').val()) || $.trim($card.find('.slot-title-text').text()) || "{{ translate('This slot') }}";
    slotBulkModal.$slotName.text(slotName);
    if (slotBulkModal.$targetInputs && slotBulkModal.$targetInputs.length) {
        slotBulkModal.$targetInputs.prop('checked', false);
        slotBulkModal.$targetInputs.filter('[value="paid"]').prop('checked', true);
    }
    if (slotBulkModal.$discountToggle && slotBulkModal.$discountToggle.length) {
        slotBulkModal.$discountToggle.prop('checked', true);
    }
    slotBulkModal.$modal.modal('show');
}

function collectSlotSelection($card) {
    var selection = {
        products: [],
        discountType: 'none',
        discountValue: ''
    };
    if (!$card || !$card.length) {
        return selection;
    }
    var selectedOptions = $card.find('.slot-products-select option:selected').map(function () {
        return $(this).val();
    }).get();
    if (!selectedOptions.length) {
        var products = $card.find('.slot-products-select').val();
        if (Array.isArray(products)) {
            selectedOptions = products;
        } else if (products) {
            selectedOptions = [products];
        }
    }
    selection.products = selectedOptions.filter(function (value) {
        return value !== null && value !== undefined && value !== '';
    });
    selection.discountType = $card.find('.slot-discount-type').val() || 'none';
    selection.discountValue = $card.find('.slot-discount-value').val() || '';
    return selection;
}

function applySelectionToTargets(target) {
    var $sourceCard = bulkApplyState.card;
    if (!$sourceCard || !$sourceCard.length) {
        return 0;
    }
    var selection = collectSlotSelection($sourceCard);
    if (!selection.products.length) {
        notifySlotBuilder('warning', slotBulkMessages.noProducts);
        return -1;
    }
    var groups = [];
    if (target === 'paid' || target === 'all') {
        groups.push('paid');
    }
    if (target === 'free' || target === 'all') {
        groups.push('free');
    }
    var updated = 0;
    var uniqueGroups = {};
    groups.forEach(function (groupKey) {
        var group = slotGroups[groupKey];
        if (!group) {
            return;
        }
        var selector = group.container + ' .slot-card';
        $(selector).each(function () {
            if (this === $sourceCard[0]) {
                return;
            }
            var $card = $(this);
            $card.find('.slot-products-select').selectpicker('val', selection.products);
            uniqueGroups[groupKey] = true;
            // Keep existing discount settings untouched
            updateSlotMeta($card);
            updated++;
        });
    });
    refreshSlotPlugins();
    return updated;
}

    function addSlotCard(groupKey) {
        var group = slotGroups[groupKey];
        var template = $(group.template).html();
        if (!template) {
            return;
        }

        var newIndex = slotIndexCounters[groupKey]++;
        var html = template.replace(/__INDEX__/g, newIndex);
        var $card = $(html);
        $(group.container).append($card);
        refreshSlotPlugins();
        syncSlotDiscountState($card);
        updateSlotMeta($card);
    }

    function ensureSlotGroupMatchesQuantity(groupKey) {
        var group = slotGroups[groupKey];
        var target = parseInt($(group.qtyInput).val(), 10) || 0;
        if (groupKey === 'paid' && target < group.min) {
            target = group.min;
            $(group.qtyInput).val(target);
        }
        var $container = $(group.container);
        var current = $container.find('.slot-card').length;

        if (current < target) {
            for (var i = 0; i < target - current; i++) {
                addSlotCard(groupKey);
            }
        } else if (current > target) {
            $container.find('.slot-card').slice(target).remove();
        }
    }

    function toggleFreeQuantityUI() {
        toggleFreeSection();
        ensureSlotGroupMatchesQuantity('free');
    }

    function refreshSlotLayout() {
        updateSlotSortOrder();
        syncSlotRemoveButtons();
        syncSlotCountIndicators();
        syncAllSlotDiscountStates();
    }

    function updateDealType() {
        var dealType = $('input[name="deal_type"]:checked').val();
        if (!dealType) {
            dealType = 'custom';
            $('input[name="deal_type"][value="custom"]').prop('checked', true);
        }

        if (dealType === 'buy_3_get_1_free') {
            $('#buy_quantity').val(3);
            $('#free_quantity').val(1);
        } else if (dealType === 'buy_5_get_2_free') {
            $('#buy_quantity').val(5);
            $('#free_quantity').val(2);
        } else if (dealType === 'signature_polo_bundle') {
            $('#buy_quantity').val(3);
            $('#free_quantity').val(0);
        }

        ensureSlotGroupMatchesQuantity('paid');
        toggleFreeQuantityUI();
    }

    function highlightDealTypeCards() {
        $('.deal-type-card').removeClass('active');
        var selectedVal = $('input[name="deal_type"]:checked').val();
        $('.deal-type-card input[value="' + selectedVal + '"]').closest('.deal-type-card').addClass('active');
    }

    function groupProductIsRefundable() {
        var $refundable = $('input[name="refundable"]');
        var $note = $('#group-product-refundable-note');
        var $block = $('.group-product-refund-block');

        if (!$refundable.length) {
            return;
        }

        // For group products, we keep the logic simple: toggle block by checkbox.
        $refundable.prop('disabled', false);
        $note.addClass('d-none');

        if ($refundable.is(':checked')) {
            $block.removeClass('d-none');
        } else {
            $block.addClass('d-none');
        }
    }

    $(document).ready(function () {
        var initialSlotCounts = <?php echo json_encode($initialSlotCounts ?? ['paid' => 0, 'free' => 0]); ?>;
        slotIndexCounters.paid = initialSlotCounts.paid || 0;
        slotIndexCounters.free = initialSlotCounts.free || 0;
        slotBulkModal.$modal = $('#slotBulkApplyModal');
        if (slotBulkModal.$modal.length) {
            slotBulkModal.$slotName = slotBulkModal.$modal.find('[data-slot-bulk-name]');
            slotBulkModal.$targetInputs = slotBulkModal.$modal.find('input[name="slot_bulk_target"]');
            slotBulkModal.$modal.on('hidden.bs.modal', function () {
                bulkApplyState.card = null;
            });
            $('#slotBulkApplyConfirm').on('click', function () {
                if (!bulkApplyState.card || !bulkApplyState.card.length) {
                    slotBulkModal.$modal.modal('hide');
                    return;
                }
                var target = slotBulkModal.$targetInputs.filter(':checked').val();
                if (!target) {
                    notifySlotBuilder('warning', slotBulkMessages.chooseTarget);
                    return;
                }
                var result = applySelectionToTargets(target);
                if (result === -1) {
                    return;
                }
                slotBulkModal.$modal.modal('hide');
                if (result > 0) {
                    notifySlotBuilder('success', slotBulkMessages.success.replace(':count', result));
                } else {
                    notifySlotBuilder('warning', slotBulkMessages.none);
                }
            });
        }

        updateDealType();
        toggleDiscountFields();
        toggleFreeQuantityUI();
        groupProductIsRefundable();
        syncAllSlotDiscountStates();
        $('.slot-card').each(function () {
            updateSlotMeta($(this));
        });
        refreshSlotLayout();
        highlightDealTypeCards();

        $('#buy_quantity').on('input change', function () {
            ensureSlotGroupMatchesQuantity('paid');
            refreshSlotLayout();
        });

        $('#free_quantity').on('input change', function () {
            toggleFreeQuantityUI();
            refreshSlotLayout();
        });

        $(document).on('click', '.remove-slot-btn', function () {
            if ($(this).prop('disabled')) {
                return;
            }
            var $card = $(this).closest('.slot-card');
            var groupKey = $card.data('slot-group');
            var group = slotGroups[groupKey];
            $card.remove();
            var newCount = $(group.container + ' .slot-card').length;
            var sanitized = Math.max(newCount, group.min);
            $(group.qtyInput).val(sanitized);
            ensureSlotGroupMatchesQuantity(groupKey);
            refreshSlotLayout();
        });

        $(document).on('change', '.slot-discount-type', function () {
            syncSlotDiscountState($(this).closest('.slot-card'));
        });

        $(document).on('input', '.slot-discount-value', function () {
            var $card = $(this).closest('.slot-card');
            updateSlotMeta($card);
        });

        $(document).on('input', '.slot-name-input', function () {
            updateSlotMeta($(this).closest('.slot-card'));
        });

        $(document).on('changed.bs.select change', '.slot-products-select', function () {
            updateSlotMeta($(this).closest('.slot-card'));
        });

        $(document).on('click', '.slot-collapse-toggle', function () {
            var $btn = $(this);
            var $card = $btn.closest('.slot-card');
            var $body = $card.find('.slot-body');
            $body.slideToggle(150);
            $btn.toggleClass('is-collapsed');
            $btn.find('i').toggleClass('la-compress-arrows-alt la-expand-arrows-alt');
        });

        $(document).on('click', '.slot-move-up', function () {
            var $card = $(this).closest('.slot-card');
            var $prev = $card.prev('.slot-card');
            if ($prev.length) {
                $card.insertBefore($prev);
                refreshSlotLayout();
            }
        });

        $(document).on('click', '.slot-move-down', function () {
            var $card = $(this).closest('.slot-card');
            var $next = $card.next('.slot-card');
            if ($next.length) {
                $card.insertAfter($next);
                refreshSlotLayout();
            }
        });

        $(document).on('click', '.slot-bulk-apply-trigger', function () {
            if (!slotBulkModal.$modal || !slotBulkModal.$modal.length) {
                return;
            }
            var $card = $(this).closest('.slot-card');
            openSlotBulkModal($card);
        });

        $(document).on('change', 'input[name="deal_type"]', function () {
            highlightDealTypeCards();
            updateDealType();
            refreshSlotLayout();
        });
    });
</script>

