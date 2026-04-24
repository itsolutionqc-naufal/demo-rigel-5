@php
    $user = auth()->user();
    $fallbackAvatar = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=6366f1&color=fff&size=128';
    $avatarSrc = $fallbackAvatar;

    if (! empty($user->avatar) && file_exists(public_path($user->avatar))) {
        $avatarSrc = asset($user->avatar).'?t='.time();
    }
@endphp

<div {{ $attributes->except('name') }}>
    <flux:dropdown position="bottom" align="start">
        <flux:button
            variant="ghost"
            class="w-full justify-between gap-3 rounded-xl px-2 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-800"
            data-test="sidebar-menu-button"
        >
            <div class="flex items-center gap-3 min-w-0">
                <img
                    src="{{ $avatarSrc }}"
                    alt="{{ $user->name }}"
                    class="h-9 w-9 rounded-full object-cover border-2 border-white dark:border-zinc-700 shadow-sm flex-shrink-0"
                    onerror="this.onerror=null; this.src='{{ $fallbackAvatar }}'"
                />
                <div class="min-w-0 text-start">
                    <div class="text-sm font-semibold text-zinc-900 dark:text-white truncate">{{ $user->name }}</div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ $user->email }}</div>
                </div>
            </div>
            <i data-lucide="chevrons-up-down" class="size-4 text-zinc-500 dark:text-zinc-400"></i>
        </flux:button>

        <flux:menu>
            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                <img
                    src="{{ $avatarSrc }}"
                    alt="{{ $user->name }}"
                    class="h-10 w-10 rounded-full object-cover border-2 border-white dark:border-zinc-700 shadow-md"
                    onerror="this.onerror=null; this.src='{{ $fallbackAvatar }}'"
                />
                <div class="grid flex-1 text-start text-sm leading-tight">
                    <flux:heading class="truncate">{{ $user->name }}</flux:heading>
                    <flux:text class="truncate">{{ $user->email }}</flux:text>
                </div>
            </div>
            <flux:menu.separator />
            <flux:menu.item
                as="button"
                type="button"
                icon="arrow-right-start-on-rectangle"
                class="w-full cursor-pointer"
                data-test="logout-button"
                onclick="openLogoutModal()"
            >
                {{ __('Log Out') }}
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>
</div>

<!-- Logout Form (Hidden) -->
<form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
    @csrf
</form>
