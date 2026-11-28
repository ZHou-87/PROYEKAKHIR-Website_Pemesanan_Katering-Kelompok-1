<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-stone-900">Dashboard Pelanggan</h2>
                <p class="text-sm text-stone-500">Ringkasan status pesanan dan riwayat transaksi terakhir Anda.</p>
            </div>
            <a href="{{ url('/catalog') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                <i class="bi bi-cart-plus"></i>
                <span>Buat Pesanan Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Kartu Ringkasan Status Pesanan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white border border-stone-200 rounded-xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Menunggu Bayar</p>
                    <p class="text-2xl font-bold text-stone-900 mt-1 tabular-nums">{{ $counts['pending'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center">
                    <i class="bi bi-hourglass-split text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Validasi Bayar</p>
                    <p class="text-2xl font-bold text-stone-900 mt-1 tabular-nums">{{ $counts['waiting_validation'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center">
                    <i class="bi bi-credit-card text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Sedang Diproses</p>
                    <p class="text-2xl font-bold text-stone-900 mt-1 tabular-nums">{{ $counts['processed'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center">
                    <i class="bi bi-fire text-xl"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Selesai</p>
                    <p class="text-2xl font-bold text-stone-900 mt-1 tabular-nums">{{ $counts['completed'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-stone-100 text-emerald-700 flex items-center justify-center">
                    <i class="bi bi-bag-check text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tabel 5 Pesanan Terakhir -->
        <div class="bg-white border border-stone-200 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-stone-900">5 Pesanan Terakhir</h3>
                <a href="{{ url('/customer/orders') }}" class="text-sm font-medium text-orange-700 hover:text-orange-800">
                    Lihat Semua
                </a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="p-8 text-center text-sm text-stone-500">
                    Anda belum memiliki pesanan. Silakan pilih menu melalui halaman katalog.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200 text-sm">
                        <thead class="bg-stone-50 text-stone-600 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Kode Pesanan</th>
                                <th class="px-6 py-3 text-left font-semibold">Tanggal Acara</th>
                                <th class="px-6 py-3 text-left font-semibold">Item Menu</th>
                                <th class="px-6 py-3 text-right font-semibold">Total</th>
                                <th class="px-6 py-3 text-left font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 bg-white">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-stone-50">
                                    <td class="px-6 py-4 font-medium text-stone-900 whitespace-nowrap">
                                        {{ $order->order_code }}
                                    </td>
                                    <td class="px-6 py-4 text-stone-600 whitespace-nowrap tabular-nums">
                                        {{ $order->event_date?->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-stone-700">
                                        @foreach ($order->orderItems as $item)
                                            <div>
                                                {{ $item->menu?->name ?? 'Menu dihapus' }}
                                                <span class="text-stone-500 tabular-nums">({{ $item->quantity }}x)</span>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-stone-900 whitespace-nowrap tabular-nums">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badgeClass = match ($order->order_status) {
                                                'completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                'processed' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'waiting_validation' => 'bg-sky-50 text-sky-800 border-sky-200',
                                                'cancelled' => 'bg-red-50 text-red-800 border-red-200',
                                                default => 'bg-stone-100 text-stone-800 border-stone-200',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium border {{ $badgeClass }}">
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
