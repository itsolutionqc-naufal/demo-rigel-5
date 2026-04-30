<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#f8fafc] dark:bg-neutral-950 antialiased">
        <!-- Main Container with Background Pattern -->
        <div class="min-h-screen w-full bg-[#f8fafc] dark:bg-neutral-950 relative">
            <!-- Top Fade Grid Background - Light Mode -->
            <div class="absolute inset-0 z-0 dark:hidden" style="
                background-image: 
                    linear-gradient(to right, #e2e8f0 1px, transparent 1px),
                    linear-gradient(to bottom, #e2e8f0 1px, transparent 1px);
                background-size: 20px 30px;
                -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 0%, #000 60%, transparent 100%);
                mask-image: radial-gradient(ellipse 70% 60% at 50% 0%, #000 60%, transparent 100%);
            "></div>
            
            <!-- Top Fade Grid Background - Dark Mode -->
            <div class="absolute inset-0 z-0 hidden dark:block" style="
                background-image: 
                    linear-gradient(to right, #374151 1px, transparent 1px),
                    linear-gradient(to bottom, #374151 1px, transparent 1px);
                background-size: 20px 30px;
                -webkit-mask-image: radial-gradient(ellipse 70% 60% at 50% 0%, #000 60%, transparent 100%);
                mask-image: radial-gradient(ellipse 70% 60% at 50% 0%, #000 60%, transparent 100%);
                opacity: 0.4;
            "></div>

            <!-- Content Container -->
            <div class="relative z-10 flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
                <div class="flex w-full max-w-sm flex-col gap-2">
                    <div class="flex flex-col gap-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
