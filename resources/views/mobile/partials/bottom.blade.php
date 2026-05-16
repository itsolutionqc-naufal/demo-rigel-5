<nav class="sticky bottom-0 z-20 flex w-full items-center justify-around border-t border-neutral-100 bg-white px-2 py-3 pb-safe dark:border-neutral-800 dark:bg-neutral-950">
    <!-- Home -->
    <a href="{{ route('mobile.app', ['page' => 'dashboard']) }}" 
       class="flex flex-col items-center gap-1 px-3 py-1 transition-colors {{ (request()->route('page') == 'dashboard' || !request()->route('page')) ? 'text-indigo-600 dark:text-indigo-400' : 'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white' }}">
        <i data-lucide="home" class="size-6"></i>
        <span class="text-[10px] font-medium">Home</span>
    </a>

    <!-- Job -->
    <a href="{{ route('mobile.app', ['page' => 'job']) }}" 
       class="flex flex-col items-center gap-1 px-3 py-1 transition-colors {{ request()->route('page') == 'job' ? 'text-indigo-600 dark:text-indigo-400' : 'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white' }}">
        <i data-lucide="briefcase" class="size-6"></i>
        <span class="text-[10px] font-medium">{{ auth()->user()->role === 'user' ? 'Coin' : 'Job' }}</span>
    </a>

    <!-- Hunter -->
    <a href="{{ route('mobile.app', ['page' => 'hunter']) }}"
       class="flex flex-col items-center gap-1 px-3 py-1 transition-colors {{ request()->route('page') == 'hunter' ? 'text-indigo-600 dark:text-indigo-400' : 'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white' }}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-6">
            <circle cx="10" cy="8" r="5" />
            <path d="M2 21a8 8 0 0 1 10.434-7.62" />
            <circle cx="18" cy="18" r="3" />
            <path d="m22 22-1.9-1.9" />
        </svg>
        <span class="text-[10px] font-medium">Hunter</span>
    </a>

    <!-- Wallet -->
    <a href="{{ route('mobile.app', ['page' => 'wallet']) }}" 
       class="flex flex-col items-center gap-1 px-3 py-1 transition-colors {{ request()->route('page') == 'wallet' ? 'text-indigo-600 dark:text-indigo-400' : 'text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-white' }}">
        <i data-lucide="wallet" class="size-6"></i>
        <span class="text-[10px] font-medium">Wallet</span>
    </a>
</nav>
