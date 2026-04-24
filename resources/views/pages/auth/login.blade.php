<x-layouts::auth>
	    @php
	        $downloadPromptEnabled = (bool) \App\Models\Setting::get('app.download_prompt_enabled', true);
	        $downloadUrl = (string) \App\Models\Setting::get('app.download_url', '');
	        $downloadPromptTitle = (string) \App\Models\Setting::get('app.download_prompt_title', 'Download Aplikasi RigelCoin');
	        $downloadPromptBody = (string) \App\Models\Setting::get('app.download_prompt_body', 'Beli & jual coin lebih cepat, pantau transaksi, dan klaim bonus/komisi langsung dari aplikasi.');
	    @endphp
    <div class="flex flex-col gap-6">
        <div class="flex justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto dark:invert" />
        </div>

        <div class="flex flex-col gap-6 rounded-2xl border border-neutral-100 bg-white p-6 shadow-lg dark:border-neutral-800 dark:bg-neutral-900/80 sm:p-8">
            <div class="flex w-full flex-col text-center">
                <h1 class="text-3xl font-bold tracking-tight text-neutral-900 dark:text-white">
                    {{ __('Selamat Datang') }}
                </h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">
                    {{ __('Di Earnings Community Rigel Agency') }}
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                @csrf

                <!-- Email Address -->
                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="{{ __('Masukkan email Anda') }}"
                />

                <!-- Password -->
                <div class="relative">
                    <flux:input
                        name="password"
                        :label="__('Password')"
                        type="password"
                        required
                        autocomplete="current-password"
                        :placeholder="__('Password')"
                        viewable
                    />
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" id="loginButton" class="inline-flex items-center justify-center gap-2 rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200 w-full">
                        <svg id="loginSpinner" class="animate-spin size-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="loginText">{{ __('Log in') }}</span>
                    </button>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                    <span>{{ __('Don\'t have an account?') }}</span>
                    <a href="https://wa.me/{{ \App\Models\Setting::getWhatsAppNumber() }}?text={{ urlencode('Halo kak mau join Community Rigel Agency') }}"
                       target="_blank"
                       class="font-medium text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 transition-colors">
                        {{ __('Sign up') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form[method="POST"]');
            const loginButton = document.getElementById('loginButton');
            const loginSpinner = document.getElementById('loginSpinner');
            const loginText = document.getElementById('loginText');

            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Show loading state
                    loginSpinner.classList.remove('hidden');
                    loginText.textContent = 'Memproses...';
                    loginButton.disabled = true;
                    loginButton.classList.add('opacity-75', 'cursor-not-allowed');
                    loginButton.classList.remove('hover:bg-neutral-800', 'dark:hover:bg-neutral-200');
                });
            }
        });
    </script>

    <!-- Download App Bottom Sheet -->
    <div
        id="downloadPromptModal"
        class="fixed inset-0 z-50 hidden items-end justify-center bg-black/50"
        aria-hidden="true"
    >
        <div
            id="downloadPromptSheet"
            class="w-full max-w-sm rounded-t-3xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 shadow-2xl transform translate-y-full transition-transform duration-300"
            role="dialog"
            aria-modal="true"
            aria-labelledby="downloadPromptTitle"
        >
            <div class="relative px-5 pb-5 pt-3">

                <button
                    type="button"
                    id="downloadPromptClose"
                    class="absolute right-4 top-3 rounded-lg p-2 text-neutral-500 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-800"
                    aria-label="Tutup"
                >
                    <i data-lucide="x" class="size-5"></i>
                </button>

                <div class="flex flex-col items-center text-center gap-3">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        alt="Logo"
                        class="h-10 w-auto dark:invert"
                    />
                    <div>
                        <h2 id="downloadPromptTitle" class="text-lg font-semibold text-neutral-900 dark:text-white">
                            {{ $downloadPromptTitle }}
                        </h2>
                        <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                            {{ $downloadPromptBody }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 flex flex-col gap-3">
                    <a
                        href="{{ $downloadUrl ?: 'javascript:void(0)' }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold transition
                            {{ $downloadUrl ? 'bg-neutral-900 text-white hover:bg-neutral-800 dark:bg-white dark:text-neutral-900 dark:hover:bg-neutral-200' : 'bg-neutral-200 text-neutral-500 cursor-not-allowed dark:bg-neutral-800 dark:text-neutral-500' }}"
                        {{ $downloadUrl ? '' : 'aria-disabled=true' }}
                    >
                        <i data-lucide="download" class="size-4"></i>
                        Download aplikasi
                    </a>

                    <button
                        type="button"
                        id="downloadPromptContinue"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-4 py-3 text-sm font-semibold text-neutral-900 dark:text-white hover:bg-neutral-50 dark:hover:bg-neutral-800 transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe-icon lucide-globe size-4"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                        Lanjutkan di browser
                    </button>
                </div>

                <div class="mt-4 flex justify-center">
                    <div class="h-1.5 w-14 rounded-full bg-neutral-200 dark:bg-neutral-700"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const enabled = @json($downloadPromptEnabled);
            if (!enabled) return;

            const lastClosedKey = 'rigel_download_prompt_last_closed_date';
            const today = new Date();
            const todayKey = today.toISOString().slice(0, 10); // YYYY-MM-DD

            try {
                if (localStorage.getItem(lastClosedKey) === todayKey) {
                    return;
                }
            } catch (e) {
                // ignore storage errors
            }

            const modal = document.getElementById('downloadPromptModal');
            const sheet = document.getElementById('downloadPromptSheet');
            const closeBtn = document.getElementById('downloadPromptClose');
            const continueBtn = document.getElementById('downloadPromptContinue');

            if (!modal || !sheet || !closeBtn || !continueBtn) return;

            function open() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                requestAnimationFrame(() => {
                    sheet.classList.remove('translate-y-full');
                });
            }

            function close() {
                sheet.classList.add('translate-y-full');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 250);

                try {
                    localStorage.setItem(lastClosedKey, todayKey);
                } catch (e) {
                    // ignore
                }
            }

            open();

            closeBtn.addEventListener('click', close);
            continueBtn.addEventListener('click', close);

            modal.addEventListener('click', function (e) {
                if (e.target === modal) close();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    close();
                }
            });
        });
    </script>
</x-layouts::auth>
