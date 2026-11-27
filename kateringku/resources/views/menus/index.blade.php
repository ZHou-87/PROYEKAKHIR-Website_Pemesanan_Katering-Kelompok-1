<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-stone-900">Kelola Menu Katering</h1>
                <p class="text-sm text-stone-600 mt-0.5">Tambah, ubah, atau hapus daftar menu makanan dan minuman.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <!-- <a href="{{ route('menus.api-client') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-sm font-medium border border-stone-300 bg-white text-stone-700 hover:bg-stone-50 transition">
                    <i class="bi bi-braces"></i>
                    <span>Klien REST API</span>
                </a> -->
                
                <a href="{{ route('menus.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Menu</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Filter & Pencarian -->
        <form method="GET" action="{{ route('menus.index') }}" class="bg-white border border-stone-200 rounded-xl p-4 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                <div class="sm:col-span-6">
                    <label for="search" class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-1">Cari Menu</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama menu..."
                        class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600"
                    >
                </div>
                <div class="sm:col-span-3">
                    <label for="category" class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-1">Kategori</label>
                    <select id="category" name="category" class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600">
                        <option value="">Semua Kategori</option>
                        <option value="makanan" @selected($category === 'makanan')>Makanan</option>
                        <option value="minuman" @selected($category === 'minuman')>Minuman</option>
                    </select>
                </div>
                <div class="sm:col-span-3 flex gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-stone-900 hover:bg-stone-800 text-white transition">
                        <i class="bi bi-search"></i>
                        <span>Cari</span>
                    </button>
                    @if ($search !== '' || $category)
                        <a href="{{ route('menus.index') }}" class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-sm font-medium border border-stone-300 text-stone-700 hover:bg-stone-100 transition">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Menu -->
        <div class="bg-white border border-stone-200 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-200 text-sm">
                    <thead class="bg-stone-50 text-stone-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3 text-left">Menu</th>
                            <th class="px-4 py-3 text-left">Kategori</th>
                            <th class="px-4 py-3 text-right">Harga</th>
                            <th class="px-4 py-3 text-center">Min. Order</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @forelse ($menus as $menu)
                            <tr class="hover:bg-stone-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($menu->image)
                                            <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="w-11 h-11 rounded-lg object-cover border border-stone-200 shrink-0">
                                        @else
                                            <div class="w-11 h-11 rounded-lg bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-400 shrink-0">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-stone-900">{{ $menu->name }}</div>
                                            <div class="text-xs text-stone-500">{{ $menu->order_items_count }} kali dipesan</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $menu->category === 'makanan' ? 'bg-orange-100 text-orange-800' : 'bg-sky-100 text-sky-800' }}">
                                        {{ ucfirst($menu->category) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-stone-900 tabular-nums">
                                    Rp {{ number_format((float) $menu->price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center tabular-nums text-stone-700">
                                    {{ $menu->min_order }} porsi
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($menu->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-stone-200 text-stone-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('menus.edit', $menu) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium border border-stone-300 text-stone-700 hover:bg-stone-100 transition">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('menus.destroy', $menu) }}" onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium border border-red-200 text-red-700 hover:bg-red-50 transition">
                                                <i class="bi bi-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-stone-500">
                                    Belum ada data menu. Klik "Tambah Menu" untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $menus->links() }}
        </div>
    </div>
</x-app-layout>
