<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-stone-900">Klien REST API Menu (`/api/v1/menus`)</h1>
                <p class="text-sm text-stone-600 mt-0.5">Kelola data menu secara asinkron menggunakan Fetch API &amp; Sanctum Token.</p>
            </div>
            <a href="{{ route('menus.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-sm font-medium border border-stone-300 bg-white text-stone-700 hover:bg-stone-50 transition self-start sm:self-auto">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Kelola Menu</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Alert Container -->
        <div id="api-alert" class="hidden rounded-lg border px-4 py-3 text-sm"></div>

        <!-- Token Sanctum Panel -->
        <div class="bg-white border border-stone-200 rounded-xl p-4 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-end gap-3">
                <div class="flex-1">
                    <label for="sanctum-token" class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-1">
                        Bearer Token Sanctum (`localStorage`)
                    </label>
                    <input
                        type="password"
                        id="sanctum-token"
                        placeholder="Tempel token Sanctum untuk operasi POST/PUT/DELETE..."
                        class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600"
                    >
                </div>
                <div class="flex gap-2">
                    <button type="button" id="btn-save-token" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-stone-900 hover:bg-stone-800 text-white transition">
                        <i class="bi bi-key"></i>
                        <span>Simpan Token</span>
                    </button>
                    <button type="button" id="btn-clear-token" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium border border-stone-300 text-stone-700 hover:bg-stone-100 transition">
                        <i class="bi bi-x-circle"></i>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Tambah / Edit Menu via API -->
            <div class="lg:col-span-4 bg-white border border-stone-200 rounded-xl p-5 shadow-sm h-fit">
                <h2 id="form-title" class="text-base font-bold text-stone-900 mb-4">Tambah Menu via API</h2>
                <form id="api-menu-form" class="space-y-4">
                    <input type="hidden" id="menu-id" value="">

                    <div>
                        <label for="api-name" class="block text-sm font-medium text-stone-700 mb-1">Nama Menu</label>
                        <input type="text" id="api-name" required class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="api-category" class="block text-sm font-medium text-stone-700 mb-1">Kategori</label>
                            <select id="api-category" required class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg">
                                <option value="makanan">Makanan</option>
                                <option value="minuman">Minuman</option>
                            </select>
                        </div>
                        <div>
                            <label for="api-min-order" class="block text-sm font-medium text-stone-700 mb-1">Min. Order</label>
                            <input type="number" id="api-min-order" min="1" value="10" required class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label for="api-price" class="block text-sm font-medium text-stone-700 mb-1">Harga (Rp)</label>
                        <input type="number" id="api-price" min="0" required class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg">
                    </div>

                    <div>
                        <label for="api-description" class="block text-sm font-medium text-stone-700 mb-1">Deskripsi</label>
                        <textarea id="api-description" rows="2" class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg"></textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="api-is-active" checked class="rounded border-stone-300 text-orange-700">
                        <label for="api-is-active" class="text-sm text-stone-700">Aktif di katalog</label>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                            <i class="bi bi-send"></i>
                            <span id="btn-submit-label">Kirim (POST)</span>
                        </button>
                        <button type="button" id="btn-reset-form" class="hidden px-3 py-2 rounded-lg text-sm font-medium border border-stone-300 text-stone-700 hover:bg-stone-100">
                            Batal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Daftar Menu via API -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white border border-stone-200 rounded-xl p-4 shadow-sm flex flex-col sm:flex-row gap-3">
                    <input type="text" id="filter-search" placeholder="Cari menu..." class="flex-1 px-3 py-2 text-sm border border-stone-300 rounded-lg">
                    <select id="filter-category" class="px-3 py-2 text-sm border border-stone-300 rounded-lg">
                        <option value="">Semua Kategori</option>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                    </select>
                    <button type="button" id="btn-refresh" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-stone-900 hover:bg-stone-800 text-white transition">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span>Muat Ulang</span>
                    </button>
                </div>

                <div class="bg-white border border-stone-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-stone-200 text-sm">
                            <thead class="bg-stone-50 text-stone-600 uppercase text-xs font-semibold">
                                <tr>
                                    <th class="px-4 py-3 text-left">Nama</th>
                                    <th class="px-4 py-3 text-left">Kategori</th>
                                    <th class="px-4 py-3 text-right">Harga</th>
                                    <th class="px-4 py-3 text-center">Min.</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="menu-table-body" class="divide-y divide-stone-200">
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-stone-500">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/menu-client.js') }}"></script>
    @endpush
</x-app-layout>
