<style>
    .settings-shell {
        width: 100%;
    }

    .settings-lead {
        margin: 0 0 1.25rem;
        color: var(--bs-secondary-color);
        font-size: 1rem;
        line-height: 1.6;
    }

    .settings-card {
        background: var(--bs-body-bg);
        border: 1px solid var(--bs-border-color);
        border-radius: 1rem;
        padding: 1.35rem 1.4rem 1.25rem;
        margin-bottom: 1rem;
    }

    .settings-card-head {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        margin-bottom: 1.15rem;
    }

    .settings-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        background: color-mix(in srgb, var(--bs-primary) 16%, transparent);
        color: var(--bs-primary-text-emphasis);
        font-size: 1.1rem;
    }

    .settings-card-title {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 600;
        line-height: 1.3;
        color: var(--bs-emphasis-color);
    }

    .settings-card-hint,
    .settings-hint {
        margin: 0.2rem 0 0;
        color: var(--bs-secondary-color);
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .settings-label {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.4rem;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--bs-emphasis-color);
    }

    .settings-required {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--bs-secondary-color);
    }

    .settings-field {
        margin-bottom: 1.1rem;
    }

    .settings-field:last-child {
        margin-bottom: 0;
    }

    .settings-shell .form-control {
        min-height: 2.85rem;
        padding: 0.6rem 0.85rem;
        border-radius: 0.7rem;
        background-color: var(--bs-body-bg);
        border-color: var(--bs-border-color);
        color: var(--bs-emphasis-color);
        line-height: 1.5;
    }

    .settings-shell textarea.form-control {
        min-height: 7.5rem;
        line-height: 1.6;
    }

    .settings-shell .form-control:focus {
        border-color: rgba(var(--bs-primary-rgb), 0.4);
        box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.12);
    }

    .settings-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.25rem;
    }

    .settings-identity {
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 0;
    }

    .settings-photo,
    .settings-photo-placeholder {
        width: 5.5rem;
        height: 5.5rem;
        border-radius: 1rem;
        flex: 0 0 auto;
    }

    .settings-photo {
        object-fit: cover;
        border: 1px solid var(--bs-border-color);
        background: var(--bs-tertiary-bg);
    }

    .settings-photo-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bs-tertiary-bg);
        color: var(--bs-secondary-color);
        font-size: 1.6rem;
    }

    .settings-name {
        margin: 0 0 0.45rem;
        font-size: 1.45rem;
        font-weight: 600;
        line-height: 1.25;
        color: var(--bs-emphasis-color);
        word-break: break-word;
    }

    .settings-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.45rem;
    }

    .settings-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.2rem 0.65rem;
        border-radius: 999px;
        font-size: 0.8125rem;
        font-weight: 600;
        line-height: 1.4;
    }

    .settings-pill-ok {
        background: var(--bs-success-bg-subtle);
        color: var(--bs-success-text-emphasis);
    }

    .settings-pill-off {
        background: var(--bs-secondary-bg);
        color: var(--bs-secondary-color);
    }

    .settings-pill-code {
        background: var(--bs-tertiary-bg);
        color: var(--bs-emphasis-color);
    }

    .settings-kicker {
        display: block;
        margin-bottom: 0.3rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--bs-secondary-color);
    }

    .settings-value {
        margin: 0;
        font-size: 1.05rem;
        line-height: 1.55;
        color: var(--bs-emphasis-color);
        word-break: break-word;
    }

    .settings-notice {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.9rem 1rem;
        border-radius: 0.85rem;
        background: var(--bs-warning-bg-subtle);
        color: var(--bs-warning-text-emphasis);
        line-height: 1.55;
    }

    .settings-empty-note {
        margin: 0;
        color: var(--bs-secondary-color);
        line-height: 1.5;
    }

    .settings-photo-row {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .settings-file {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .settings-switch.form-check {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 0.9rem;
        width: fit-content;
        max-width: 100%;
        min-height: 0;
        margin: 0;
        padding: 0.85rem 1rem;
        border-radius: 0.8rem;
        background: var(--bs-tertiary-bg);
    }

    [dir="rtl"] .settings-shell .ltr-nums {
        text-align: right;
    }

    .settings-switch .form-check-input {
        float: none;
        width: 2.75rem;
        height: 1.4rem;
        margin: 0;
        cursor: pointer;
        flex: 0 0 auto;
    }

    .settings-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        margin-top: 0.25rem;
    }

    .settings-actions .btn {
        min-height: 2.75rem;
        padding-inline: 1.15rem;
        border-radius: 0.7rem;
    }

    .settings-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-bottom: 0.75rem;
        color: var(--bs-secondary-color);
        text-decoration: none;
        font-weight: 600;
    }

    .settings-back:hover {
        color: var(--bs-emphasis-color);
    }

    [dir="rtl"] .settings-back .bi {
        transform: scaleX(-1);
    }

    .settings-blank {
        text-align: center;
        padding: 2.5rem 1rem;
    }

    .settings-blank .bi {
        font-size: 2rem;
        color: var(--bs-secondary-color);
    }

    @media (max-width: 575.98px) {
        .settings-hero,
        .settings-identity,
        .settings-photo-row {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
