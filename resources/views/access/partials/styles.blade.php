@include('system-settings.partials.styles')

<style>
    .access-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .access-toolbar .settings-lead {
        margin-bottom: 0;
        flex: 1 1 16rem;
    }

    .access-search {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .access-search .form-control {
        max-width: 22rem;
    }

    .realtime-search-results[aria-busy='true'] {
        opacity: 0.55;
    }

    .settings-shell .form-select {
        min-height: 2.85rem;
        border-radius: 0.7rem;
    }

    .access-table {
        margin-bottom: 0;
        vertical-align: middle;
    }

    .access-table th {
        font-size: 0.78rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: var(--bs-secondary-color);
        white-space: nowrap;
    }

    .access-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.4rem;
    }

    .permission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(16rem, 1fr));
        gap: 0.65rem 1rem;
    }

    .permission-grid .form-check {
        margin: 0;
        padding: 0.7rem 0.8rem;
        padding-inline-start: 2.2rem;
        border: 1px solid var(--bs-border-color);
        border-radius: 0.7rem;
        background: var(--bs-tertiary-bg);
    }

    .role-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    .role-choice {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin: 0;
        padding: 0.75rem 0.9rem;
        border: 1px solid var(--bs-border-color);
        border-radius: 0.75rem;
        background: var(--bs-tertiary-bg);
    }

    .role-choice .form-check-input {
        float: none;
        margin: 0;
    }

    .profile-photo,
    .profile-thumb {
        object-fit: cover;
        border-radius: 50%;
    }

    .profile-thumb {
        width: 2.25rem;
        height: 2.25rem;
        flex: 0 0 auto;
    }
</style>
