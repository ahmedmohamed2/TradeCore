(() => {
    const headerName = 'X-Realtime-Search';

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('form[data-realtime-search]').forEach((form) => {
            bindSearch(form);
        });
    });

    /**
     * @param {HTMLFormElement} form
     */
    function bindSearch(form) {
        const input = form.querySelector('input[name="search"]');
        const status = form.querySelector('[data-realtime-search-status]');
        const results = document.getElementById(form.dataset.resultsId ?? '');

        if (! (input instanceof HTMLInputElement) || ! results) {
            return;
        }

        let activeController = null;
        let debounceId = 0;

        input.addEventListener('input', schedule);
        input.addEventListener('search', schedule);

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            window.clearTimeout(debounceId);
            load(searchUrl(), 'replace');
        });

        results.addEventListener('click', (event) => {
            if (! (event.target instanceof Element)) {
                return;
            }

            const link = event.target.closest('a.page-link');

            if (! (link instanceof HTMLAnchorElement) || ! results.contains(link)) {
                return;
            }

            event.preventDefault();
            window.clearTimeout(debounceId);
            load(link.href, 'push');
        });

        window.addEventListener('popstate', () => {
            const url = new URL(window.location.href);
            input.value = url.searchParams.get('search') ?? '';
            window.clearTimeout(debounceId);
            load(url.toString(), 'none');
        });

        function schedule() {
            window.clearTimeout(debounceId);
            debounceId = window.setTimeout(() => {
                load(searchUrl(), 'replace');
            }, 250);
        }

        function searchUrl() {
            const url = new URL(form.action, window.location.href);
            const term = input.value.trim();

            if (term === '') {
                url.searchParams.delete('search');
            } else {
                url.searchParams.set('search', term);
            }

            url.searchParams.delete('page');

            return url.toString();
        }

        /**
         * @param {'replace'|'push'|'none'} history
         */
        function load(url, history) {
            activeController?.abort();

            const controller = new AbortController();
            activeController = controller;
            results.setAttribute('aria-busy', 'true');

            if (status) {
                status.textContent = form.dataset.searching ?? '';
            }

            fetch(url, {
                method: 'GET',
                headers: {
                    Accept: 'text/html',
                    [headerName]: '1',
                },
                credentials: 'same-origin',
                signal: controller.signal,
            }).then((response) => {
                if (! response.ok || response.redirected || response.headers.get(headerName) !== '1') {
                    window.location.assign(url);

                    return null;
                }

                return response.text();
            }).then((html) => {
                if (html === null || controller.signal.aborted) {
                    return;
                }

                results.innerHTML = html;
                remember(url, history);

                if (status) {
                    status.textContent = '';
                }
            }).catch((error) => {
                if (error.name === 'AbortError') {
                    return;
                }

                window.location.assign(url);
            }).finally(() => {
                if (! controller.signal.aborted) {
                    results.removeAttribute('aria-busy');
                }
            });
        }

        /**
         * @param {'replace'|'push'|'none'} history
         */
        function remember(url, history) {
            const nextUrl = new URL(url, window.location.href);

            if (history === 'replace') {
                window.history.replaceState({ realtimeSearch: true }, '', nextUrl);
            }

            if (history === 'push') {
                window.history.pushState({ realtimeSearch: true }, '', nextUrl);
            }
        }
    }
})();
