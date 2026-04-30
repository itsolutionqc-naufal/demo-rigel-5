<x-layouts::app :title="__('Telegram Bots')">
    <div class="container mx-auto px-4 py-6 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Telegram Bots</h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Kelola bot Telegram untuk notifikasi per layanan</p>
            </div>
            <a href="{{ route('admin.telegram-bots.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800 dark:bg-white dark:text-neutral-900">
                <i data-lucide="plus" class="size-4"></i>Tambah Bot
            </a>
        </div>

        @if(session('success'))<div class="rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 p-4"><p class="text-sm text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p></div>@endif

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 overflow-hidden">
            <table class="w-full">
                <thead class="bg-neutral-50 dark:bg-neutral-900 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Bot</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Chat ID</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Layanan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-neutral-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse($bots as $bot)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                        <td class="px-6 py-4 text-sm font-medium text-neutral-900 dark:text-white">{{ $bot->name }}</td>
                        <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">{{ '@' . $bot->username }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-neutral-900 dark:text-white">{{ $bot->chat_id }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-semibold rounded-full border
                                {{ $bot->is_active
                                    ? 'border-emerald-300 text-emerald-700 dark:border-emerald-700 dark:text-emerald-200'
                                    : 'border-neutral-300 text-neutral-700 dark:border-neutral-700 dark:text-neutral-200'
                                }}"
                            >
                                <i data-lucide="{{ $bot->is_active ? 'circle-check' : 'circle-x' }}" class="size-3"></i>
                                {{ $bot->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
	                        <td class="px-6 py-4 text-center text-sm">{{ $bot->services->count() }}</td>
	                        <td class="px-6 py-4">
	                            <div class="flex justify-center gap-2">
	                                <a href="{{ route('admin.telegram-bots.edit', $bot) }}" class="text-blue-600">
	                                    <i data-lucide="edit" class="size-4"></i>
	                                </a>
	                                <a href="{{ route('admin.telegram-bots.test', $bot) }}" class="text-emerald-600">
	                                    <i data-lucide="circle-check" class="size-4"></i>
	                                </a>
	                                <form action="{{ route('admin.telegram-bots.toggle', $bot) }}" method="POST" class="inline">
	                                    @csrf
	                                    <button type="submit" class="text-amber-600">
	                                        <i data-lucide="{{ $bot->is_active ? 'pause' : 'play' }}" class="size-4"></i>
	                                    </button>
	                                </form>
	                                @if($bot->services->count() === 0)
	                                    <form action="{{ route('admin.telegram-bots.destroy', $bot) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
	                                        @csrf
	                                        <input type="hidden" name="_method" value="DELETE" />
	                                        <button type="submit" class="text-red-600">
	                                            <i data-lucide="trash" class="size-4"></i>
	                                        </button>
	                                    </form>
	                                @endif
	                            </div>
	                        </td>
	                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center"><p class="text-sm text-neutral-900 dark:text-white">Belum ada bot Telegram</p></td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($bots->hasPages())<div class="px-6 py-4 border-t">{{ $bots->links() }}</div>@endif
        </div>
    </div>
</x-layouts::app>
