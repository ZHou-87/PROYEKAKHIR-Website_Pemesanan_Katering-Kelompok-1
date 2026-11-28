<x-app-layout>
    <!-- Hero Section -->
    <section class="bg-white border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-stone-100 border border-stone-200 text-xs font-semibold uppercase tracking-wider text-stone-700">
                        <i class="bi bi-award text-orange-700"></i>
                        <span>Layanan Katering Harian &amp; Acara</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-stone-900 leading-tight">
                        Sajian Katering Rapi, Tepat Waktu, untuk Setiap Momen Penting Anda.
                    </h1>

                    <p class="text-base sm:text-lg text-stone-600 leading-relaxed max-w-2xl">
                        Pesan praktis nasi kotak, minuman segar, hingga paket tumpeng spesial. Tersedia layanan antar hingga 40 km dengan tarif transparan kelipatan 5 km, atau ambil langsung di dapur kami.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ url('/catalog') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                            <i class="bi bi-grid"></i>
                            <span>Lihat Katalog Menu</span>
                        </a>

                        @guest
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg text-sm font-semibold bg-white border border-stone-300 text-stone-800 hover:bg-stone-50 transition">
                                <i class="bi bi-person-plus"></i>
                                <span>Daftar Pelanggan</span>
                            </a>
                        @else
                            @role('customer')
                                <a href="{{ url('/customer/dashboard') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg text-sm font-semibold bg-white border border-stone-300 text-stone-800 hover:bg-stone-50 transition">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>Dashboard Saya</span>
                                </a>
                            @endrole
                        @endguest
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-stone-50 border border-stone-200 rounded-xl p-6 space-y-4">
                        <div class="flex items-center justify-between border-b border-stone-200 pb-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-stone-500">Ringkasan Layanan</span>
                            <span class="text-xs font-medium text-orange-700">Siap Antar &amp; Pickup</span>
                        </div>
                        <div class="space-y-3 text-sm text-stone-700">
                            <div class="flex items-start justify-between gap-4">
                                <span class="font-medium text-stone-900">Nasi Kotak &amp; Minuman</span>
                                <span class="text-stone-600 tabular-nums shrink-0">Min. 10 porsi</span>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="font-medium text-stone-900">Paket Tumpeng (Varian 1-3)</span>
                                <span class="text-stone-600 tabular-nums shrink-0">Min. 1 paket</span>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="font-medium text-stone-900">Jangkauan Pengiriman</span>
                                <span class="text-stone-600 tabular-nums shrink-0">Maks. 40 km</span>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="font-medium text-stone-900">Tarif Ongkir</span>
                                <span class="text-stone-600 tabular-nums shrink-0">Rp 15.000 / 5 km</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Grid Keunggulan Layanan -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-stone-200 rounded-xl p-6 space-y-3">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-stone-100 text-orange-700">
                    <i class="bi bi-truck text-xl"></i>
                </div>
                <h2 class="text-base font-semibold text-stone-900">Antar hingga 40 km atau Pickup</h2>
                <p class="text-sm text-stone-600 leading-relaxed">
                    Ongkos kirim dihitung adil kelipatan 5 km (Rp 15.000 per 5 km) hingga radius 40 km, atau gratis biaya kirim jika ambil langsung di tempat.
                </p>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-6 space-y-3">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-stone-100 text-orange-700">
                    <i class="bi bi-clock-history text-xl"></i>
                </div>
                <h2 class="text-base font-semibold text-stone-900">Pemesanan &amp; Status Terpantau</h2>
                <p class="text-sm text-stone-600 leading-relaxed">
                    Konfirmasi pembayaran praktis dalam batas waktu 10 menit dan pantau progres pesanan dari validasi hingga selesai dimasak.
                </p>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-6 space-y-3">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-stone-100 text-orange-700">
                    <i class="bi bi-patch-check text-xl"></i>
                </div>
                <h2 class="text-base font-semibold text-stone-900">Menu Segar &amp; Terstandar</h2>
                <p class="text-sm text-stone-600 leading-relaxed">
                    Disiapkan harian dengan bahan pilihan untuk kebutuhan rapat kantor, syukuran keluarga, hingga acara besar.
                </p>
            </div>
        </div>
    </section>

    <!-- Showcase Menu Aktif -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 space-y-12">
        <!-- Kategori Makanan (termasuk Tumpeng) -->
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 border-b border-stone-200 pb-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-orange-700">Pilihan Hidangan</span>
                    <h2 class="text-2xl font-bold text-stone-900 mt-1">Menu Makanan &amp; Tumpeng</h2>
                </div>
                <a href="{{ url('/catalog?category=makanan') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-orange-700 hover:text-orange-800">
                    <span>Lihat Semua Makanan</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if ($makananMenus->isEmpty())
                <div class="bg-white border border-stone-200 rounded-xl p-8 text-center text-sm text-stone-500">
                    Belum ada menu makanan aktif yang tersedia saat ini.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($makananMenus as $menu)
                        <div class="bg-white border border-stone-200 rounded-xl overflow-hidden flex flex-col">
                            @if ($menu->image)
                                <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="w-full h-48 object-cover bg-stone-100">
                            @else
                                <div class="w-full h-48 bg-stone-100 flex items-center justify-center text-stone-400">
                                    <i class="bi bi-image text-3xl"></i>
                                </div>
                            @endif

                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-stone-100 text-stone-700 uppercase">
                                            {{ $menu->category }}
                                        </span>
                                        <span class="text-xs text-stone-500 tabular-nums">
                                            Min. {{ $menu->min_order }} porsi
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-stone-900">{{ $menu->name }}</h3>
                                    <p class="text-sm text-stone-600 line-clamp-2">{{ $menu->description }}</p>
                                </div>

                                <div class="pt-3 border-t border-stone-100 flex items-center justify-between gap-3">
                                    <span class="text-base font-bold text-stone-900 tabular-nums">
                                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ url('/catalog') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                                        <i class="bi bi-cart-plus"></i>
                                        <span>Pesan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Kategori Minuman -->
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 border-b border-stone-200 pb-4">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-orange-700">Pelengkap Acara</span>
                    <h2 class="text-2xl font-bold text-stone-900 mt-1">Menu Minuman</h2>
                </div>
                <a href="{{ url('/catalog?category=minuman') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-orange-700 hover:text-orange-800">
                    <span>Lihat Semua Minuman</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @if ($minumanMenus->isEmpty())
                <div class="bg-white border border-stone-200 rounded-xl p-8 text-center text-sm text-stone-500">
                    Belum ada menu minuman aktif yang tersedia saat ini.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($minumanMenus as $menu)
                        <div class="bg-white border border-stone-200 rounded-xl overflow-hidden flex flex-col">
                            @if ($menu->image)
                                <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="w-full h-48 object-cover bg-stone-100">
                            @else
                                <div class="w-full h-48 bg-stone-100 flex items-center justify-center text-stone-400">
                                    <i class="bi bi-cup-straw text-3xl"></i>
                                </div>
                            @endif

                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-stone-100 text-stone-700 uppercase">
                                            {{ $menu->category }}
                                        </span>
                                        <span class="text-xs text-stone-500 tabular-nums">
                                            Min. {{ $menu->min_order }} porsi
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-stone-900">{{ $menu->name }}</h3>
                                    <p class="text-sm text-stone-600 line-clamp-2">{{ $menu->description }}</p>
                                </div>

                                <div class="pt-3 border-t border-stone-100 flex items-center justify-between gap-3">
                                    <span class="text-base font-bold text-stone-900 tabular-nums">
                                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ url('/catalog') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                                        <i class="bi bi-cart-plus"></i>
                                        <span>Pesan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
