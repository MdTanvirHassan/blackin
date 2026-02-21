<style>
    .slot-builder-overview .slot-summary-tags {
        gap: 0.5rem;
    }

    .slot-builder-overview .slot-summary-chip {
        width: 100%;
        display: flex;
        align-items: center;
        padding: 0.65rem 0.95rem;
        border-radius: 0.75rem;
        background-color: #fff;
        box-shadow: inset 0 0 0 1px rgba(23, 43, 77, 0.05);
        font-weight: 600;
        color: #2f3247;
    }

    .slot-builder-overview .slot-summary-chip + .slot-summary-chip {
        margin-top: 0.5rem;
    }

    .slot-builder-overview .slot-summary-bullet {
        width: 0.65rem;
        height: 0.65rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #845ef7, #5b8def);
        margin-right: 0.6rem;
        flex-shrink: 0;
    }

    .slot-builder-overview .slot-summary-chip-empty {
        box-shadow: inset 0 0 0 1px rgba(23, 43, 77, 0.08);
        background-color: rgba(250, 251, 255, 0.8);
        font-weight: 500;
    }

    .slot-builder-overview .slot-summary-chip-empty .slot-summary-bullet {
        background: #c4c6d1;
    }

    .slot-builder-overview .slot-summary-label {
        flex: 1;
    }

    .slot-builder-overview .slot-summary-label.text-muted {
        color: #8f96a9 !important;
    }

    .slot-card .slot-title-wrapper {
        min-width: 0;
    }

    .slot-card .slot-title-text {
        font-weight: 600;
        color: #16192c;
    }

    .slot-card .card-header .badge {
        width: 70px;
        justify-content: center;
    }

    .slot-card .slot-header-actions .btn {
        border-radius: 0.35rem;
    }

    .slot-card .slot-body {
        background-color: #fdfdfd;
    }

    .slot-card .slot-card-footer {
        border-top: 1px dashed #e5e7ed;
    }

    .slot-card .slot-card-footer span {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .deal-type-selector .deal-type-card {
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
        padding: 1rem 1rem 1.25rem;
        border-radius: 0.9rem;
        background: #fff;
        box-shadow: inset 0 0 0 1px #e7eaf3;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 150px;
    }

    .deal-type-card:hover {
        box-shadow: inset 0 0 0 1px #c6d3ff;
    }

    .deal-type-card.active {
        box-shadow: inset 0 0 0 2px #6574ff;
        background: rgba(101, 116, 255, 0.08);
    }

    .deal-type-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .deal-type-card .deal-type-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(101, 116, 255, 0.15), rgba(91, 141, 239, 0.25));
        color: #4c5ce7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        margin-bottom: 0.75rem;
    }

    .deal-type-card.active .deal-type-icon {
        background: linear-gradient(135deg, #6574ff, #5b8def);
        color: #fff;
    }

    .deal-type-card .deal-type-title {
        font-weight: 700;
        color: #222548;
        margin-bottom: 0.35rem;
    }

    .deal-type-card .deal-type-description {
        font-size: 0.85rem;
        color: #6b718c;
        line-height: 1.35;
    }

    .deal-overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .deal-overview-card {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 1.1rem 1.25rem;
        border-radius: 1rem;
        background: #fff;
        border: 1px solid #edf0f6;
    }

    .deal-overview-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(101, 116, 255, 0.15), rgba(91, 141, 239, 0.25));
        color: #4c5ce7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .deal-overview-label {
        display: block;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 600;
        color: #7a7f92;
    }

    .deal-overview-value {
        display: block;
        font-size: 1.35rem;
        font-weight: 700;
        color: #1f2237;
    }

    .deal-overview-helper {
        display: block;
        font-size: 0.85rem;
        color: #8b90a6;
    }

    .slot-section {
        border: 1px solid #eef1f4;
        border-radius: 1rem;
        background: #fff;
        padding: 1.25rem 1.5rem;
    }

    .slot-section + .slot-section {
        margin-top: 1.5rem;
    }

    .slot-section .slot-collection {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .slot-card .slot-bulk-apply-trigger {
        color: #4c5ce7;
    }

    .slot-card .slot-bulk-apply-trigger:hover {
        color: #fff;
        background-color: #4c5ce7;
        border-color: #4c5ce7;
    }

    .bulk-target-options {
        display: grid;
        gap: 0.75rem;
    }

    .bulk-target-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 0.85rem 1rem;
        display: flex;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
        align-items: flex-start;
    }

    .bulk-target-card input {
        margin-top: 0.3rem;
    }

    .bulk-target-card strong {
        display: block;
        font-weight: 600;
        color: #1f2237;
    }

    .bulk-target-card:hover {
        border-color: #8da2fb;
        box-shadow: inset 0 0 0 1px rgba(77, 107, 255, 0.35);
    }
</style>

