(() => {
    const root = document.getElementById('confirm-dialog');

    if (! root) {
        return;
    }

    const title = document.getElementById('confirm-dialog-title');
    const accept = root.querySelector('[data-confirm-accept]');
    const cancel = root.querySelector('.confirm-dialog-actions [data-confirm-cancel]');
    const app = document.querySelector('.app-wrapper');

    /** @type {HTMLFormElement|null} */
    let pendingForm = null;

    /** @type {HTMLElement|null} */
    let returnFocus = null;

    document.addEventListener('submit', (event) => {
        const form = event.target;

        if (! (form instanceof HTMLFormElement)) {
            return;
        }

        const message = form.dataset.confirm;

        if (! message || form.dataset.confirming === '1') {
            return;
        }

        event.preventDefault();
        open(form, message);
    });

    root.addEventListener('click', (event) => {
        if (! (event.target instanceof Element)) {
            return;
        }

        if (event.target.closest('[data-confirm-cancel]')) {
            close();
        }
    });

    accept?.addEventListener('click', () => {
        const form = pendingForm;

        if (! form) {
            close();

            return;
        }

        form.dataset.confirming = '1';
        close();
        form.requestSubmit();
        delete form.dataset.confirming;
    });

    document.addEventListener('keydown', (event) => {
        if (root.hidden) {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            close();

            return;
        }

        if (event.key !== 'Tab' || ! cancel || ! accept) {
            return;
        }

        const backwards = event.shiftKey && document.activeElement === cancel;
        const forwards = ! event.shiftKey && document.activeElement === accept;

        if (backwards || forwards) {
            event.preventDefault();
            (backwards ? accept : cancel).focus();
        }
    });

    /**
     * @param {HTMLFormElement} form
     * @param {string} message
     */
    function open(form, message) {
        pendingForm = form;
        returnFocus = document.activeElement instanceof HTMLElement ? document.activeElement : null;

        if (title) {
            title.textContent = message;
        }

        root.hidden = false;
        root.classList.add('is-open');
        document.body.classList.add('confirm-dialog-open');
        app?.setAttribute('inert', '');
        accept?.focus();
    }

    function close() {
        root.hidden = true;
        root.classList.remove('is-open');
        document.body.classList.remove('confirm-dialog-open');
        app?.removeAttribute('inert');
        pendingForm = null;
        returnFocus?.focus();
        returnFocus = null;
    }
})();
