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

            // Prevent back button navigation to login page
            // This works by pushing a state and intercepting popstate
            (function() {
                if (!/^\/app(\/|$)/.test(window.location.pathname)) {
                    return;
                }

                // Push a state when page loads
                if (!window.history.state || !window.history.state.__appNoBack) {
                    window.history.replaceState({ __appNoBack: true }, '', window.location.href);
                    window.history.pushState({ __appNoBack: true }, '', window.location.href);
                }

                // Listen for back button
                window.addEventListener('popstate', function() {
                    // If user tries to go back, push state again to prevent navigation
                    window.history.pushState({ __appNoBack: true }, '', window.location.href);
                });

                // Prevent back navigation on page show (when coming from back/forward cache)
                window.addEventListener('pageshow', function(event) {
                    if (event.persisted) {
                        // Page was loaded from cache, push state again
                        window.history.pushState({ __appNoBack: true }, '', window.location.href);
                    }
                });
            })();
        </script>

        @fluxScripts
    </body>
</html>
