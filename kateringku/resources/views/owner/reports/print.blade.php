<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cetak Laporan Pemasukan - {{ config('app.name', 'KateringKu') }}</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        @vite(['resources/css/app.css'])
        <style>
            @page {
                size: A4;
                margin: 15mm;
            }
            @media print {
                .no-print {
                    display: none !important;
                }
                body {
                    background-color: #ffffff !important;
                }
            }
        </style>
    </head>
    <body class="bg-white text-stone-900 font-sans antialiased">
        <!-- Toolbar Cetak (Hanya Layar) -->
        <div class="no-print bg-stone-100 border-b border-stone-200 px-6 py-3 flex items-center justify-between">
            <a href="{{ route('owner.reports.index', request()->query()) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-stone-700 hover:text-stone-900">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Laporan</span>
            </a>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                <i class="bi bi-printer"></i>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>

        <!-- Dokumen A4 -->
        <div class="max-w-4xl mx-auto px-6 py-8 space-y-6">
            <!-- Kop KateringKu -->
            <header class="border-b-2 border-stone-900 pb-4 flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-stone-900">{{ config('app.name', 'KateringKu') }}</h1>
                    <p class="text-xs text-stone-600 mt-0.5">Layanan Katering Harian, Nasi Kotak, Minuman &amp; Paket Tumpeng</p>
                </div>
                <div class="text-right">
                    <h2 class="text-base font-bold uppercase tracking-wider text-stone-900">Laporan Pemasukan</h2>
                    <p class="text-xs text-stone-600 mt-0.5 tabular-nums">
                        Periode:
                        @if ($filters['start_date'] || $filters['end_date'])
                            {{ $filters['start_date'] ? \Carbon\Carbon::parse($filters['start_date'])->format('d M Y') : 'Awal' }}
                            -
                            {{ $filters['end_date'] ? \Carbon\Carbon::parse($filters['end_date'])->format('d M Y') : 'Sekarang' }}
                        @else
                            Semua Periode
                        @endif
                    </p>
                    <p class="text-xs text-stone-500 tabular-nums">Dicetak: {{ now()->format('d M Y H:i') }}</p>
                </div>
            </header>

            <!-- Tabel Transaksi Completed -->
            <table class="w-full border-collapse border border-stone-300 text-xs">
                <thead>
                    <tr class="bg-stone-100 text-stone-800">
                        <th class="border border-stone-300 px-3 py-2 text-left font-semibold">No</th>
                        <th class="border border-stone-300 px-3 py-2 text-left font-semibold">Kode Pesanan</th>
                        <th class="border border-stone-300 px-3 py-2 text-left font-semibold">Pelanggan</th>
                        <th class="border border-stone-300 px-3 py-2 text-left font-semibold">Tgl Acara</th>
                        <th class="border border-stone-300 px-3 py-2 text-left font-semibold">Metode</th>
                        <th class="border border-stone-300 px-3 py-2 text-right font-semibold">Porsi</th>
                        <th class="border border-stone-300 px-3 py-2 text-right font-semibold">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $index => $order)
                        <tr>
                            <td class="border border-stone-300 px-3 py-2 tabular-nums">{{ $index + 1 }}</td>
                            <td class="border border-stone-300 px-3 py-2 font-medium">{{ $order->order_code }}</td>
                            <td class="border border-stone-300 px-3 py-2">{{ $order->customer_name }}</td>
                            <td class="border border-stone-300 px-3 py-2 tabular-nums">
                                {{ \Carbon\Carbon::parse($order->event_date)->format('d/m/Y') }}
                            </td>
                            <td class="border border-stone-300 px-3 py-2 uppercase">{{ $order->delivery_method }}</td>
                            <td class="border border-stone-300 px-3 py-2 text-right tabular-nums">{{ (int) $order->total_portions }}</td>
                            <td class="border border-stone-300 px-3 py-2 text-right font-medium tabular-nums">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border border-stone-300 px-3 py-6 text-center text-stone-500">
                                Tidak ada transaksi berstatus completed pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-stone-100 font-bold text-stone-900">
                        <td colspan="6" class="border border-stone-300 px-3 py-2.5 text-right uppercase tracking-wider">
                            Grand Total Omzet
                        </td>
                        <td class="border border-stone-300 px-3 py-2.5 text-right tabular-nums">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <!-- Kolom Tanda Tangan Owner -->
            <div class="pt-10 flex justify-end">
                <div class="w-56 text-center text-xs text-stone-800">
                    <p>Mengetahui,</p>
                    <p class="font-semibold mt-0.5">Owner {{ config('app.name', 'KateringKu') }}</p>
                    <div class="h-20 border-b border-stone-400"></div>
                    <p class="mt-2 font-medium">{{ auth()->user()?->name ?? 'Owner' }}</p>
                </div>
            </div>
        </div>

        <script>
            window.addEventListener('load', function () {
                window.print();
            });
        </script>
    </body>
</html>
