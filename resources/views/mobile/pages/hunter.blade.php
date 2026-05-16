<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
            Hunter
        </h2>

        <a href="{{ route('mobile.app', ['page' => 'host-history']) }}"
           class="inline-flex items-center gap-1.5 rounded-lg border border-neutral-200 bg-white px-3 py-1.5 text-sm font-medium text-neutral-700 shadow-sm transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800">
            <i data-lucide="history" class="size-4"></i>
            <span>Riwayat Submit Host</span>
        </a>
    </div>

    <div class="rounded-xl border border-neutral-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
        <div class="mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-indigo-600 dark:text-indigo-400">
                <circle cx="10" cy="8" r="5" />
                <path d="M2 21a8 8 0 0 1 10.434-7.62" />
                <circle cx="18" cy="18" r="3" />
                <path d="m22 22-1.9-1.9" />
            </svg>
            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">Submit Host</h3>
        </div>

        <form id="hostSubmitForm" class="space-y-4">
            <div>
                <label class="mb-1 block text-xs font-medium text-neutral-700 dark:text-neutral-300">Aplikasi</label>

                @php
                    $serviceOptions = (isset($services) ? $services : collect())
                        ->map(function ($service): array {
                            return [
                                'id' => $service->id,
                                'name' => $service->name,
                                'image_url' => $service->image ? asset($service->image) : null,
                                'initial' => mb_substr($service->name ?? '', 0, 1),
                            ];
                        })
                        ->values();
                @endphp

	                <div
	                    x-data="{
	                        open: false,
	                        query: '',
	                        selectedIds: [],
	                        options: @js($serviceOptions),
	                        isSelected(id) {
	                            return this.selectedIds.includes(id);
	                        },
	                        toggle(opt) {
	                            if (this.isSelected(opt.id)) {
	                                this.selectedIds = this.selectedIds.filter(i => i !== opt.id);
	                                return;
	                            }
	                            this.selectedIds = [...this.selectedIds, opt.id];
	                        },
	                        get selectedOptions() {
	                            return this.options.filter(o => this.selectedIds.includes(o.id));
	                        },
	                        get filtered() {
	                            const q = (this.query || '').toLowerCase();
	                            if (!q) return this.options;
	                            return this.options.filter(o => (o.name || '').toLowerCase().includes(q));
	                        }
	                    }"
	                    @click.outside="open = false"
	                    class="relative"
	                >
	                    <template x-for="id in selectedIds" :key="id">
	                        <input type="hidden" name="service_ids[]" :value="id">
	                    </template>

	                    <button
	                        type="button"
	                        @click="open = !open"
	                        class="flex w-full items-center justify-between gap-3 rounded-lg border border-neutral-300 bg-white px-4 py-3 text-left text-sm text-neutral-900 shadow-sm transition focus:border-white focus:outline-none focus:ring-1 focus:ring-white dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:focus:border-white dark:focus:ring-white"
	                    >
	                        <span class="flex min-w-0 items-center gap-3">
	                            <span class="flex size-8 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-neutral-200 bg-neutral-50 text-xs font-semibold text-neutral-600 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300">
	                                <template x-if="selectedOptions.length > 0 && selectedOptions[0].image_url">
	                                    <img :src="selectedOptions[0].image_url" :alt="selectedOptions[0].name" class="h-full w-full object-cover" />
	                                </template>
	                                <template x-if="!(selectedOptions.length > 0 && selectedOptions[0].image_url)">
	                                    <span x-text="selectedOptions.length > 0 ? (selectedOptions[0].initial || 'A') : 'A'"></span>
	                                </template>
	                            </span>
	                            <span class="min-w-0">
	                                <span class="block truncate font-medium" x-text="selectedOptions.length > 0 ? `${selectedOptions.length} aplikasi dipilih` : 'Pilih aplikasi'"></span>
	                                <span class="block truncate text-[11px] text-neutral-500 dark:text-neutral-400" x-text="selectedOptions.length > 0 ? selectedOptions.map(o => o.name).join(', ') : 'Talent Hunter saat ini punya 10 aplikasi'"></span>
	                            </span>
	                        </span>
	                        <i data-lucide="chevrons-up-down" class="size-4 text-neutral-400"></i>
	                    </button>

                    <div
                        x-show="open"
                        x-transition
                        class="absolute z-30 mt-2 w-full overflow-hidden rounded-xl border border-neutral-200 bg-white shadow-lg dark:border-neutral-800 dark:bg-neutral-950"
                    >
                        <div class="p-3 border-b border-neutral-100 dark:border-neutral-800">
                            <input
                                type="text"
                                placeholder="Cari aplikasi..."
                                x-model="query"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2 text-sm text-neutral-900 placeholder-neutral-400 focus:border-white focus:outline-none focus:ring-1 focus:ring-white dark:border-neutral-700 dark:bg-neutral-900 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:ring-white"
                            />
                        </div>

	                        <div class="max-h-72 overflow-y-auto p-2">
	                            <template x-if="filtered.length === 0">
	                                <div class="px-3 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
	                                    Tidak ada aplikasi ditemukan
	                                </div>
	                            </template>

	                            <template x-for="opt in filtered" :key="opt.id">
	                                <button
	                                    type="button"
	                                    @click="
	                                        toggle(opt);
	                                        $nextTick(() => { lucide && lucide.createIcons && lucide.createIcons(); });
	                                    "
	                                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm text-neutral-900 transition hover:bg-neutral-50 dark:text-white dark:hover:bg-neutral-900"
	                                >
	                                    <input
	                                        type="checkbox"
	                                        class="size-4 accent-indigo-600 pointer-events-none"
	                                        :checked="isSelected(opt.id)"
	                                        aria-hidden="true"
	                                    />
	                                    <span class="flex size-8 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-neutral-200 bg-neutral-50 text-xs font-semibold text-neutral-600 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300">
	                                        <template x-if="opt.image_url">
	                                            <img :src="opt.image_url" :alt="opt.name" class="h-full w-full object-cover" />
	                                        </template>
	                                        <template x-if="!opt.image_url">
	                                            <span x-text="opt.initial || 'A'"></span>
	                                        </template>
	                                    </span>
	                                    <span class="min-w-0 truncate font-medium" x-text="opt.name"></span>
	                                </button>
	                            </template>
	                        </div>

	                        <div class="flex items-center justify-end gap-2 border-t border-neutral-100 p-3 dark:border-neutral-800">
	                            <button
	                                type="button"
	                                @click="open = false; query = ''"
	                                class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-700"
	                            >
	                                Selesai
	                            </button>
	                        </div>
	                    </div>
	                </div>
	            </div>

            <div>
                <label for="host_id" class="mb-1 block text-xs font-medium text-neutral-700 dark:text-neutral-300">ID Host</label>
                <input
                    id="host_id"
                    name="host_id"
                    type="text"
                    inputmode="numeric"
                    autocomplete="off"
                    placeholder="Masukkan ID Host"
                    class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 placeholder-neutral-400 focus:border-white focus:outline-none focus:ring-1 focus:ring-white dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:ring-white"
                    required
                />
            </div>

            <div>
                <label for="nickname" class="mb-1 block text-xs font-medium text-neutral-700 dark:text-neutral-300">Nickname Aplikasi</label>
                <input
                    id="nickname"
                    name="nickname"
                    type="text"
                    autocomplete="off"
                    placeholder="Masukkan nickname"
                    class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 placeholder-neutral-400 focus:border-white focus:outline-none focus:ring-1 focus:ring-white dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:ring-white"
                    required
                />
            </div>

            <div>
                <label for="whatsapp_host" class="mb-1 block text-xs font-medium text-neutral-700 dark:text-neutral-300">Nomor WhatsApp Host</label>
                <input
                    id="whatsapp_host"
                    name="whatsapp_host"
                    type="tel"
                    inputmode="tel"
                    autocomplete="off"
                    placeholder="Contoh: 62812xxxxxxx"
                    class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-900 placeholder-neutral-400 focus:border-white focus:outline-none focus:ring-1 focus:ring-white dark:border-neutral-700 dark:bg-neutral-950 dark:text-white dark:placeholder-neutral-500 dark:focus:border-white dark:focus:ring-white"
                    required
                />
                <p class="mt-1 text-[11px] text-neutral-500 dark:text-neutral-400">Gunakan format 62 (tanpa 0 di depan).</p>
            </div>

            <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-800 dark:bg-neutral-950">
                <p class="mb-3 text-xs font-semibold text-neutral-900 dark:text-white">Apakah host sudah isi formulir?</p>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800">
                        <input type="radio" name="form_filled" value="yes" class="accent-indigo-600" required />
                        <span>Sudah</span>
                    </label>
                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700 transition hover:bg-neutral-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800">
                        <input type="radio" name="form_filled" value="no" class="accent-indigo-600" required />
                        <span>Belum</span>
                    </label>
                </div>
            </div>

            <button
                id="submitHostButton"
                type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-70"
            >
                <span
                    id="submitHostSpinner"
                    class="hidden size-4 animate-spin rounded-full border-2 border-white/40 border-t-white"
                    aria-hidden="true"
                ></span>
                <i id="submitHostIcon" data-lucide="send" class="size-4"></i>
                <span id="submitHostText">Submit Host</span>
            </button>
        </form>
    </div>
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('hostSubmitForm');
        const submitButton = document.getElementById('submitHostButton');
        const submitSpinner = document.getElementById('submitHostSpinner');
        const submitIcon = document.getElementById('submitHostIcon');
        const submitText = document.getElementById('submitHostText');

        if (!form) return;

        const setSubmitting = (isSubmitting) => {
            if (!submitButton) return;

            submitButton.disabled = isSubmitting;

            if (submitSpinner) submitSpinner.classList.toggle('hidden', !isSubmitting);
            if (submitIcon) submitIcon.classList.toggle('hidden', isSubmitting);
            if (submitText) submitText.textContent = isSubmitting ? 'Memproses...' : 'Submit Host';
        };

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(form);
            const serviceIds = formData.getAll('service_ids[]').map(v => String(v).trim()).filter(Boolean);
            const hostId = String(formData.get('host_id') || '').trim();
            const nickname = String(formData.get('nickname') || '').trim();
            const whatsappHost = String(formData.get('whatsapp_host') || '').trim();
            const formFilled = String(formData.get('form_filled') || '').trim();

            if (serviceIds.length === 0 || !hostId || !nickname || !whatsappHost || !formFilled) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Lengkapi data',
                    text: serviceIds.length === 0 ? 'Pilih minimal 1 aplikasi.' : 'Semua field wajib diisi.',
                    confirmButtonColor: '#ef4444',
                    background: '#1f2937',
                    color: '#fff'
                });
                return;
            }

            setSubmitting(true);

            try {
                const controller = new AbortController();
                const timeoutId = window.setTimeout(() => controller.abort(), 20000);

                let response;
                try {
                    response = await fetch('/app/submit-host', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        signal: controller.signal,
                    });
                } finally {
                    window.clearTimeout(timeoutId);
                }

                const data = await response.json().catch(() => ({}));

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Gagal submit host');
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message || 'talent yang anda submit masuk proses seleksi, hasil seleksi akan Di update secara berkala',
                    confirmButtonColor: '#10b981',
                    background: '#1f2937',
                    color: '#fff',
                    iconColor: '#10b981'
                });

                window.location.href = '/app/host-history';
            } catch (error) {
                const message = error?.name === 'AbortError'
                    ? 'Request timeout. Coba lagi ya.'
                    : (error.message || 'Terjadi kesalahan saat memproses');
                await Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message,
                    confirmButtonColor: '#ef4444',
                    background: '#1f2937',
                    color: '#fff'
                });
            } finally {
                setSubmitting(false);
            }
        });
    });
</script>
