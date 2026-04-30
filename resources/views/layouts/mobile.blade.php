<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('partials.head')
    </head>
    <body class="h-full bg-neutral-100 dark:bg-neutral-900 flex justify-center overflow-hidden">
        
        <!-- Mobile Container -->
        <div class="w-full max-w-md h-full bg-white dark:bg-neutral-950 shadow-2xl relative flex flex-col">
            @include('mobile.partials.header')

            <main class="flex-1 overflow-y-auto no-scrollbar scroll-smooth">
                {{ $slot }}
            </main>

            @include('mobile.partials.bottom')
        </div>

        <script>
            lucide.createIcons();

            // Navigation History Manager untuk back button
            (function() {
                const HISTORY_KEY = 'rigel_nav_history';
                const MAX_HISTORY = 20;

                function getHistory() {
                    try {
                        return JSON.parse(sessionStorage.getItem(HISTORY_KEY)) || [];
                    } catch {
                        return [];
                    }
                }

                function saveHistory(history) {
                    try {
                        sessionStorage.setItem(HISTORY_KEY, JSON.stringify(history));
                    } catch {}
                }

                function getCurrentPage() {
                    const match = window.location.pathname.match(/^\/app(?:\/|$)(.*)/);
                    return match ? match[1] : 'dashboard';
                }

                function addToHistory(page) {
                    if (!page || page === 'dashboard') return;
                    
                    const history = getHistory();
                    const currentPage = getCurrentPage();
                    
                    if (page === currentPage) return;
                    
                    if (history.length === 0 || history[history.length - 1] !== page) {
                        history.push(page);
                        if (history.length > MAX_HISTORY) {
                            history.shift();
                        }
                        saveHistory(history);
                    }
                }

                function canGoBack() {
                    return getHistory().length > 0;
                }

                function goBack() {
                    const history = getHistory();
                    if (history.length > 0) {
                        const prevPage = history.pop();
                        saveHistory(history);
                        window.location.href = '/app/' + prevPage;
                        return true;
                    }
                    return false;
                }

                function init() {
                    if (!/^\/app(?:\/|$)/.test(window.location.pathname)) {
                        return;
                    }

                    const history = getHistory();
                    if (history.length === 0) {
                        addToHistory('dashboard');
                    }

                    // Expose function for Android back button via capacitor bridge
                    if (window.Capacitor && window.Capacitor.isNativePlatform()) {
                        window.RigelNavigation = {
                            canGoBack: canGoBack,
                            goBack: function() {
                                if (canGoBack()) {
                                    goBack();
                                    return 'navigated';
                                }
                                return 'no_history';
                            },
                            getCurrentPage: getCurrentPage,
                            addToHistory: addToHistory,
                            getHistoryLength: function() {
                                return getHistory().length;
                            }
                        };
                    }

                    document.addEventListener('click', function(e) {
                        const link = e.target.closest('a');
                        if (link && link.href && link.href.includes('/app/')) {
                            const url = new URL(link.href);
                            const match = url.pathname.match(/^\/app(?:\/|$)(.*)/);
                            if (match) {
                                addToHistory(match[1]);
                            }
                        }
                    }, true);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }
            })();

            // Prevent back button navigation to login page (for browser back)
            (function() {
                if (!/^\/app(?:\/|$)/.test(window.location.pathname)) {
                    return;
                }

                window.history.replaceState({ __appNoBack: true }, '', window.location.href);
                window.history.pushState({ __appNoBack: true }, '', window.location.href);

                window.addEventListener('popstate', function() {
                    window.history.pushState({ __appNoBack: true }, '', window.location.href);
                });
            })();
        </script>

        @fluxScripts
    </body>
</html>