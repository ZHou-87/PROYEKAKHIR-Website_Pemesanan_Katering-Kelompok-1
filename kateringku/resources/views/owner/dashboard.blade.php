<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-stone-900">Dashboard Owner</h2>
                <p class="text-sm text-stone-500">Ringkasan kinerja bisnis KateringKu secara keseluruhan.</p>
            </div>
            <a href="{{ route('owner.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                <i class="bi bi-bar-chart-line"></i>
                <span>Buka Laporan Pemasukan</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Kartu Ringkasan Bisnis -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white border border-stone-200 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Total Omzet (Selesai)</p>
                    <p class="text-2xl font-bold text-stone-900 mt-1 tabular-nums">
                        Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center shrink-0">
                    <i class="bi bi-cash-stack text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Transaksi Selesai</p>
                    <p class="text-2xl font-bold text-stone-900 mt-1 tabular-nums">{{ $completedOrdersCount }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-stone-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <i class="bi bi-check2-all text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Total Menu</p>
                    <p class="text-2xl font-bold text-stone-900 mt-1 tabular-nums">{{ $totalMenusCount }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center shrink-0">
                    <i class="bi bi-journal-text text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Jumlah Pelanggan</p>
                    <p class="text-2xl font-bold text-stone-900 mt-1 tabular-nums">{{ $totalCustomersCount }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center shrink-0">
                    <i class="bi bi-people text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tautan Cepat Manajemen -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <a href="{{ route('owner.reports.index') }}" class="bg-white border border-stone-200 rounded-xl p-5 hover:border-orange-700 transition flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center shrink-0">
                    <i class="bi bi-bar-chart-line text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-stone-900">Laporan Pemasukan &amp; PDF</h3>
                    <p class="text-xs text-stone-500 mt-1">Filter tanggal transaksi selesai dan cetak laporan A4.</p>
                </div>
            </a>

            <a href="{{ url('/menus') }}" class="bg-white border border-stone-200 rounded-xl p-5 hover:border-orange-700 transition flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center shrink-0">
                    <i class="bi bi-journal-text text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-stone-900">Kelola Menu</h3>
                    <p class="text-xs text-stone-500 mt-1">Atur daftar makanan, tumpeng, minuman, dan status aktif.</p>
                </div>
            </a>

            <a href="{{ url('/owner/users') }}" class="bg-white border border-stone-200 rounded-xl p-5 hover:border-orange-700 transition flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center shrink-0">
                    <i class="bi bi-person-gear text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-stone-900">Kelola User</h3>
                    <p class="text-xs text-stone-500 mt-1">Kelola akun admin dan pelanggan terdaftar.</p>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
