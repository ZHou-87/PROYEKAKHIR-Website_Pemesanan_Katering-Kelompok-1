<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-stone-900">Katalog Menu KateringKu</h1>
                <p class="text-sm text-stone-600 mt-0.5">Pilih paket makanan dan minuman segar untuk acara Anda.</p>
            </div>
            @guest
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-orange-700 hover:bg-orange-800 text-white transition self-start sm:self-auto">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Login untuk Memesan</span>
                </a>
            @endguest
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Filter & Pencarian -->
        <form method="GET" action="{{ route('catalog.index') }}" class="bg-white border border-stone-200 rounded-xl p-4 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                <div class="sm:col-span-5">
                    <label for="search" class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-1">Cari Menu</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-400">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nasi kotak, tumpeng, minuman..."
                            class="w-full pl-9 pr-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600"
                        >
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="category" class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-1">Kategori</label>
                    <select id="category" name="category" class="w-full py-2 px-3 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600">
                        <option value="">Semua Kategori</option>
                        <option value="makanan" @selected($category === 'makanan')>Makanan</option>
                        <option value="minuman" @selected($category === 'minuman')>Minuman</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="sort" class="block text-xs font-semibold uppercase tracking-wide text-stone-600 mb-1">Urutkan</label>
                    <select id="sort" name="sort" class="w-full py-2 px-3 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600">
                        <option value="name" @selected($sort === 'name')>Nama (A-Z)</option>
                        <option value="price" @selected($sort === 'price')>Harga</option>
                        <option value="min_order" @selected($sort === 'min_order')>Min. Order</option>
                    </select>
                </div>

                <div class="sm:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-stone-900 hover:bg-stone-800 text-white transition">
                        <i class="bi bi-funnel"></i>
                        <span>Filter</span>
                    </button>
                    @if ($search !== '' || $category)
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-sm font-medium border border-stone-300 text-stone-700 hover:bg-stone-100 transition" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Grid Kartu Menu -->
        @if ($menus->isEmpty())
            <div class="bg-white border border-stone-200 rounded-xl p-10 text-center">
                <i class="bi bi-inbox text-4xl text-stone-400"></i>
                <h2 class="mt-3 text-base font-semibold text-stone-800">Menu tidak ditemukan</h2>
                <p class="mt-1 text-sm text-stone-500">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($menus as $menu)
                    <article class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-sm flex flex-col hover:border-orange-300 transition">
                        <div class="aspect-[16/10] bg-stone-100 relative overflow-hidden">
                            @if ($menu->image)
                                <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-stone-400">
                                    <i class="bi bi-image text-3xl"></i>
                                    <span class="text-xs mt-1">Foto belum tersedia</span>
                                </div>
                            @endif

                            <span class="absolute top-3 left-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $menu->category === 'makanan' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 'bg-sky-100 text-sky-800 border border-sky-200' }}">
                                {{ ucfirst($menu->category) }}
                            </span>
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between gap-4">
                            <div>
                                <h2 class="text-base font-bold text-stone-900">{{ $menu->name }}</h2>
                                <p class="mt-1.5 text-sm text-stone-600 line-clamp-2">
                                    {{ $menu->description ?: 'Hidangan katering berkualitas dengan bahan segar pilihan.' }}
                                </p>
                            </div>

                            <div class="space-y-3 pt-3 border-t border-stone-100">
                                <div class="flex items-center justify-between text-xs text-stone-600">
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="bi bi-box-seam text-orange-700"></i>
                                        <span>Min. order {{ $menu->min_order }} porsi</span>
                                    </span>
                                </div>

                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <span class="block text-[11px] uppercase tracking-wider text-stone-500">Harga / Porsi</span>
                                        <span class="text-lg font-bold text-stone-900 tabular-nums">
                                            Rp {{ number_format((float) $menu->price, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <a href="{{ route('catalog.order', $menu) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                                        <i class="bi bi-cart-plus"></i>
                                        <span>Pesan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $menus->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
