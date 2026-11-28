<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-stone-900">Dashboard Admin</h2>
                <p class="text-sm text-stone-500">Ringkasan operasional pesanan dan ketersediaan menu harian.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ url('/admin/orders') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                    <i class="bi bi-bag-check"></i>
                    <span>Kelola Pesanan</span>
                </a>
                <a href="{{ url('/menus') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-white border border-stone-300 text-stone-800 hover:bg-stone-50 transition">
                    <i class="bi bi-journal-text"></i>
                    <span>Kelola Menu</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Kartu Ringkasan Operasional -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-stone-200 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Butuh Validasi Bayar</p>
                    <p class="text-3xl font-bold text-stone-900 mt-1 tabular-nums">{{ $waitingValidationCount }}</p>
                    <p class="text-xs text-stone-500 mt-1">Status: waiting_validation</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center">
                    <i class="bi bi-receipt text-2xl"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Sedang Diproses</p>
                    <p class="text-3xl font-bold text-stone-900 mt-1 tabular-nums">{{ $processedCount }}</p>
                    <p class="text-xs text-stone-500 mt-1">Status: processed</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center">
                    <i class="bi bi-box-seam text-2xl"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Total Menu Aktif</p>
                    <p class="text-3xl font-bold text-stone-900 mt-1 tabular-nums">{{ $activeMenusCount }}</p>
                    <p class="text-xs text-stone-500 mt-1">Tampil di katalog pelanggan</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-stone-100 text-emerald-700 flex items-center justify-center">
                    <i class="bi bi-grid text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Antrian Pesanan Operasional -->
        <div class="bg-white border border-stone-200 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-stone-900">Antrian Validasi &amp; Produksi Terbaru</h3>
                <a href="{{ url('/admin/orders') }}" class="text-sm font-medium text-orange-700 hover:text-orange-800">
                    Buka Semua Pesanan
                </a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="p-8 text-center text-sm text-stone-500">
                    Tidak ada pesanan yang menunggu validasi atau sedang diproses saat ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200 text-sm">
                        <thead class="bg-stone-50 text-stone-600 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Kode</th>
                                <th class="px-6 py-3 text-left font-semibold">Pelanggan</th>
                                <th class="px-6 py-3 text-left font-semibold">Tanggal Acara</th>
                                <th class="px-6 py-3 text-right font-semibold">Total</th>
                                <th class="px-6 py-3 text-left font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 bg-white">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-stone-50">
                                    <td class="px-6 py-4 font-medium text-stone-900 whitespace-nowrap">{{ $order->order_code }}</td>
                                    <td class="px-6 py-4 text-stone-700 whitespace-nowrap">{{ $order->user?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-stone-600 whitespace-nowrap tabular-nums">{{ $order->event_date?->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right font-semibold text-stone-900 whitespace-nowrap tabular-nums">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium border border-stone-200 bg-stone-100 text-stone-800">
                                            {{ $order->order_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
