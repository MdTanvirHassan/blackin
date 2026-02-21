<div class="modal fade" id="slotBulkApplyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-0 pb-0">
                <div>
                    <p class="text-uppercase text-muted small mb-1">{{ translate('Bulk apply selection') }}</p>
                    <h5 class="mb-0">{{ translate('Use these products for other slots?') }}</h5>
                </div>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        data-dismiss="modal"
                        aria-label="{{ translate('Close') }}"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">
                    {{ translate('Copy the configuration from') }}
                    <strong data-slot-bulk-name>{{ translate('this slot') }}</strong>
                </p>

                <div class="bulk-target-options">
                    <label class="bulk-target-card">
                        <input type="radio" name="slot_bulk_target" value="paid">
                        <span>
                            <strong>{{ translate('All paid slots') }}</strong>
                            <small class="d-block text-muted">{{ translate('Fill every required slot with these products') }}</small>
                        </span>
                    </label>
                    <label class="bulk-target-card">
                        <input type="radio" name="slot_bulk_target" value="free">
                        <span>
                            <strong>{{ translate('All free slots') }}</strong>
                            <small class="d-block text-muted">{{ translate('Use for every bonus slot instead') }}</small>
                        </span>
                    </label>
                    <label class="bulk-target-card">
                        <input type="radio" name="slot_bulk_target" value="all">
                        <span>
                            <strong>{{ translate('Paid + Free') }}</strong>
                            <small class="d-block text-muted">{{ translate('Apply everywhere this product list is allowed') }}</small>
                        </span>
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button"
                        class="btn btn-link text-muted"
                        data-bs-dismiss="modal"
                        data-dismiss="modal">
                    {{ translate('Cancel') }}
                </button>
                <button type="button" class="btn btn-primary" id="slotBulkApplyConfirm">
                    {{ translate('Apply Selection') }}
                </button>
            </div>
        </div>
    </div>
</div>

