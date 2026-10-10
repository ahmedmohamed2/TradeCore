<div id="confirm-dialog" class="confirm-dialog" hidden>
    <div class="confirm-dialog-backdrop" data-confirm-cancel></div>

    <div
        class="confirm-dialog-panel"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="confirm-dialog-title"
        aria-describedby="confirm-dialog-text"
    >
        <div class="confirm-dialog-icon" aria-hidden="true">
            <i class="bi bi-trash3"></i>
        </div>
        <h2 id="confirm-dialog-title" class="confirm-dialog-title"></h2>
        <p id="confirm-dialog-text" class="confirm-dialog-text">{{ __('general.confirm_irreversible') }}</p>
        <div class="confirm-dialog-actions">
            <button type="button" class="btn btn-outline-secondary" data-confirm-cancel>
                {{ __('general.cancel') }}
            </button>
            <button type="button" class="btn btn-danger" data-confirm-accept>
                {{ __('general.delete') }}
            </button>
        </div>
    </div>
</div>

<style>
    .confirm-dialog[hidden] {
        display: none !important;
    }

    .confirm-dialog {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: grid;
        place-items: center;
        padding: 1.25rem;
    }

    .confirm-dialog-backdrop {
        position: absolute;
        inset: 0;
        z-index: 0;
        background: color-mix(in srgb, #0b1220 62%, transparent);
        backdrop-filter: blur(8px);
    }

    .confirm-dialog-panel {
        position: relative;
        z-index: 1;
        width: min(100%, 24rem);
        padding: 1.6rem 1.4rem 1.3rem;
        border: 1px solid var(--bs-border-color);
        border-radius: 1.15rem;
        background-color: var(--bs-body-bg);
        box-shadow: 0 24px 70px color-mix(in srgb, #020617 32%, transparent);
        text-align: center;
    }

    .confirm-dialog.is-open .confirm-dialog-panel {
        animation: confirm-dialog-in 180ms ease-out;
    }

    .confirm-dialog-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 3.4rem;
        height: 3.4rem;
        margin-bottom: 0.95rem;
        border-radius: 999px;
        background: color-mix(in srgb, var(--bs-danger) 16%, transparent);
        color: var(--bs-danger);
        font-size: 1.35rem;
    }

    .confirm-dialog-title {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        line-height: 1.45;
        color: var(--bs-emphasis-color);
    }

    .confirm-dialog-text {
        margin: 0.4rem 0 0;
        color: var(--bs-secondary-color);
        font-size: 0.92rem;
        line-height: 1.55;
    }

    .confirm-dialog-actions {
        display: flex;
        justify-content: center;
        gap: 0.6rem;
        margin-top: 1.3rem;
    }

    .confirm-dialog-actions .btn {
        min-width: 6.75rem;
        border-radius: 0.7rem;
        font-weight: 600;
    }

    body.confirm-dialog-open {
        overflow: hidden;
    }

    @keyframes confirm-dialog-in {
        from {
            opacity: 0;
            transform: translateY(10px) scale(0.98);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .confirm-dialog.is-open .confirm-dialog-panel {
            animation: none;
        }
    }
</style>

@once
    @push('scripts')
        <script src="{{ asset('assets/js/confirm-dialog.js') }}"></script>
    @endpush
@endonce
