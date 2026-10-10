<style>
    .treasury-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .treasury-card {
        padding: 0.95rem 1rem 0.85rem;
        border: 1px solid var(--bs-border-color);
        border-radius: 0.85rem;
        background: var(--bs-body-bg);
    }

    .treasury-card-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem 1rem;
    }

    .treasury-identity {
        flex: 1 1 12rem;
        min-width: 0;
    }

    .treasury-code {
        margin: 0;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        color: var(--bs-secondary-color);
    }

    .treasury-name {
        margin: 0.15rem 0 0;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.35;
        overflow-wrap: anywhere;
    }

    .treasury-badges {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.35rem;
    }

    .treasury-facts {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem 1rem;
        margin: 0.9rem 0 0;
    }

    @media (min-width: 768px) {
        .treasury-facts {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    .treasury-facts dt {
        margin: 0;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--bs-secondary-color);
    }

    .treasury-facts dd {
        margin: 0.15rem 0 0;
        font-weight: 650;
    }

    .treasury-notes {
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 0.2rem 0.55rem;
        margin-top: 0.9rem;
    }

    .treasury-notes .settings-kicker {
        margin-bottom: 0;
    }

    .treasury-notes-text,
    .treasury-notes .settings-empty-note {
        margin: 0;
        line-height: 1.55;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .treasury-record {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem 1.25rem;
        margin: 0.9rem 0 0;
        padding-top: 0.75rem;
        border-top: 1px solid var(--bs-border-color);
        color: var(--bs-secondary-color);
        font-size: 0.8125rem;
        line-height: 1.45;
    }

    .treasury-record span {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 0.35rem;
    }

    .treasury-record strong {
        color: var(--bs-emphasis-color);
        font-weight: 600;
    }
</style>
