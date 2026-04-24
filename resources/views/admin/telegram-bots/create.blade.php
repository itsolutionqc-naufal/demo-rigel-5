<x-layouts::app :title="__('Tambah Telegram Bot')">
    <div class="container mx-auto px-4 py-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Tambah Telegram Bot</h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Daftarkan bot baru untuk notifikasi per layanan</p>
            </div>
            <a href="{{ route('admin.telegram-bots.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 dark:border-neutral-700 px-4 py-2 text-sm font-medium text-neutral-900 dark:text-white hover:bg-neutral-50 dark:hover:bg-neutral-800">
                <i data-lucide="arrow-left" class="size-4"></i>Kembali
            </a>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6">
            <form action="{{ route('admin.telegram-bots.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Nama Bot</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-neutral-900 dark:focus:ring-neutral-500" />
                    @error('name')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Username Bot</label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="RigelSugoNotifBot"
                        class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-neutral-900 dark:focus:ring-neutral-500" />
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Tanpa karakter <span class="font-mono">@</span>, dan biasanya diakhiri <span class="font-mono">Bot</span>.</p>
                    @error('username')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Token Bot</label>
                    <input type="text" name="token" value="{{ old('token') }}" required autocomplete="off"
                        class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-neutral-900 dark:focus:ring-neutral-500" />
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Token dari BotFather. Simpan dengan aman.</p>
                    @error('token')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Chat ID Default</label>
                    <input type="text" name="chat_id" value="{{ old('chat_id') }}" required placeholder="123456789 atau -100xxxxxxxxxx"
                        class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-neutral-900 dark:focus:ring-neutral-500" />
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">Chat ID user / grup tujuan notifikasi default bot ini.</p>
                    @error('chat_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-3">
                    <input id="is_active" type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                        class="size-4 rounded border-neutral-300 dark:border-neutral-700 text-neutral-900 focus:ring-neutral-900" />
                    <label for="is_active" class="text-sm text-neutral-700 dark:text-neutral-300">Aktifkan bot</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.telegram-bots.index') }}" class="inline-flex items-center rounded-lg border border-neutral-300 dark:border-neutral-700 px-4 py-2 text-sm font-medium text-neutral-900 dark:text-white hover:bg-neutral-50 dark:hover:bg-neutral-800">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800 dark:bg-white dark:text-neutral-900">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>

