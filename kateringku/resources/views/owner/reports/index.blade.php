<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-stone-900">Laporan Pemasukan Owner</h2>
                <p class="text-sm text-stone-500">Rekap transaksi berstatus selesai (completed) beserta filter tanggal dan pengurutan.</p>
            </div>
            <a href="{{ route('owner.reports.print', request()->query()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                <i class="bi bi-printer"></i>
                <span>Cetak / Export PDF</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Ringkasan Total Pendapatan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white border border-stone-200 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Total Pendapatan (Completed)</p>
                    <p class="text-3xl font-bold text-stone-900 mt-1 tabular-nums">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-stone-100 text-orange-700 flex items-center justify-center">
                    <i class="bi bi-cash-stack text-2xl"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200 rounded-xl p-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">Periode Laporan</p>
                    <p class="text-base font-semibold text-stone-900 mt-1 tabular-nums">
                        @if ($filters['start_date'] || $filters['end_date'])
                            {{ $filters['start_date'] ? \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') : 'Awal' }}
                            &mdash;
                            {{ $filters['end_date'] ? \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') : 'Sekarang' }}
                        @else
                            Semua Periode
                        @endif
                    </p>
                    <p class="text-xs text-stone-500 mt-1 tabular-nums">Menampilkan {{ $orders->total() }} transaksi selesai</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center">
                    <i class="bi bi-calendar-range text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Form Filter & Sorting -->
        <div class="bg-white border border-stone-200 rounded-xl p-6">
            <form method="GET" action="{{ route('owner.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label for="start_date" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1">Dari Tanggal Acara</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $filters['start_date'] }}" class="w-full rounded-lg border-stone-300 text-sm focus:border-orange-700 focus:ring-orange-700">
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1">Sampai Tanggal Acara</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $filters['end_date'] }}" class="w-full rounded-lg border-stone-300 text-sm focus:border-orange-700 focus:ring-orange-700">
                </div>

                <div>
                    <label for="sort_by" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1">Urutkan Berdasarkan</label>
                    <select id="sort_by" name="sort_by" class="w-full rounded-lg border-stone-300 text-sm focus:border-orange-700 focus:ring-orange-700">
                        <option value="event_date" @selected($filters['sort_by'] === 'event_date')>Tanggal Acara</option>
                        <option value="total_price" @selected($filters['sort_by'] === 'total_price')>Total Harga</option>
                        <option value="created_at" @selected($filters['sort_by'] === 'created_at')>Tanggal Dibuat</option>
                    </select>
                </div>

                <div>
                    <label for="sort_dir" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1">Arah Urutan</label>
                    <select id="sort_dir" name="sort_dir" class="w-full rounded-lg border-stone-300 text-sm focus:border-orange-700 focus:ring-orange-700">
                        <option value="desc" @selected($filters['sort_dir'] === 'desc')>Terbaru / Tertinggi</option>
                        <option value="asc" @selected($filters['sort_dir'] === 'asc')>Terlama / Terendah</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                        <i class="bi bi-funnel"></i>
                        <span>Terapkan</span>
                    </button>
                    <a href="{{ route('owner.reports.index') }}" class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-sm font-medium bg-stone-100 hover:bg-stone-200 text-stone-700 transition" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel Transaksi Completed -->
        <div class="bg-white border border-stone-200 rounded-xl overflow-hidden">
            @php
                $nextDir = fn (string $col): string => ($filters['sort_by'] === $col && $filters['sort_dir'] === 'asc') ? 'desc' : 'asc';
            @endphp

            @if ($orders->isEmpty())
                <div class="p-8 text-center text-sm text-stone-500">
                    Tidak ada transaksi selesai pada rentang tanggal yang dipilih.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200 text-sm">
                        <thead class="bg-stone-50 text-stone-600 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Kode Pesanan</th>
                                <th class="px-6 py-3 text-left font-semibold">Pelanggan</th>
                                <th class="px-6 py-3 text-left font-semibold">
                                    <a href="{{ route('owner.reports.index', array_merge(request()->query(), ['sort_by' => 'event_date', 'sort_dir' => $nextDir('event_date')])) }}" class="inline-flex items-center gap-1 hover:text-stone-900">
                                        <span>Tanggal Acara</span>
                                        <i class="bi bi-arrow-down-up text-xs"></i>
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left font-semibold">Pengiriman</th>
                                <th class="px-6 py-3 text-right font-semibold">Total Porsi</th>
                                <th class="px-6 py-3 text-right font-semibold">
                                    <a href="{{ route('owner.reports.index', array_merge(request()->query(), ['sort_by' => 'total_price', 'sort_dir' => $nextDir('total_price')])) }}" class="inline-flex items-center gap-1 hover:text-stone-900">
                                        <span>Total Harga</span>
                                        <i class="bi bi-arrow-down-up text-xs"></i>
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left font-semibold">
                                    <a href="{{ route('owner.reports.index', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_dir' => $nextDir('created_at')])) }}" class="inline-flex items-center gap-1 hover:text-stone-900">
                                        <span>Tanggal Order</span>
                                        <i class="bi bi-arrow-down-up text-xs"></i>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 bg-white">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-stone-50">
                                    <td class="px-6 py-4 font-medium text-stone-900 whitespace-nowrap">{{ $order->order_code }}</td>
                                    <td class="px-6 py-4 text-stone-700 whitespace-nowrap">{{ $order->customer_name }}</td>
                                    <td class="px-6 py-4 text-stone-600 whitespace-nowrap tabular-nums">
                                        {{ \Carbon\Carbon::parse($order->event_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-stone-600 whitespace-nowrap uppercase text-xs font-medium">
                                        {{ $order->delivery_method }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-stone-700 whitespace-nowrap tabular-nums">
                                        {{ (int) $order->total_portions }} porsi
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-stone-900 whitespace-nowrap tabular-nums">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-stone-500 whitespace-nowrap tabular-nums">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="px-6 py-4 border-t border-stone-200">
                        {{ $orders->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
