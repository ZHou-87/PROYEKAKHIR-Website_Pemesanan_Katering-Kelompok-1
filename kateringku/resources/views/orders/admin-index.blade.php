<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-stone-900">Validasi &amp; Kelola Pesanan</h1>
            <p class="text-sm text-stone-600 mt-0.5">Verifikasi pembayaran customer dan perbarui status pengerjaan pesanan.</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Filter Status -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="bg-white border border-stone-200 rounded-xl p-4 shadow-sm flex flex-wrap items-center gap-3">
            <label for="status" class="text-xs font-semibold uppercase tracking-wide text-stone-600">Filter Status:</label>
            <select id="status" name="status" class="px-3 py-2 text-sm border border-stone-300 rounded-lg">
                <option value="">Semua Status</option>
                @foreach ($allowedStatuses as $st)
                    <option value="{{ $st }}" @selected($status === $st)>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium bg-stone-900 text-white hover:bg-stone-800 transition">Terapkan</button>
            @if ($status)
                <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 rounded-lg text-sm font-medium border border-stone-300 text-stone-700 hover:bg-stone-100">Reset</a>
            @endif
        </form>

        <!-- Daftar Pesanan -->
        <div class="space-y-4">
            @forelse ($orders as $order)
                <div class="bg-white border border-stone-200 rounded-xl p-5 shadow-sm space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-100 pb-3">
                        <div>
                            <span class="font-bold text-stone-900">{{ $order->order_code }}</span>
                            <span class="text-xs text-stone-500 ml-2">Customer: {{ $order->user?->name }} ({{ $order->phone }})</span>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            @if ($order->order_status === 'pending') bg-amber-100 text-amber-800
                            @elseif ($order->order_status === 'waiting_validation') bg-sky-100 text-sky-800
                            @elseif ($order->order_status === 'processed') bg-indigo-100 text-indigo-800
                            @elseif ($order->order_status === 'completed') bg-emerald-100 text-emerald-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-stone-700">
                        <div>
                            <div class="text-xs font-semibold uppercase text-stone-500 mb-1">Menu Dipesan</div>
                            <ul class="space-y-0.5">
                                @foreach ($order->items as $item)
                                    <li>{{ $item->menu?->name ?? 'Menu' }} &times; {{ $item->quantity }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <div class="text-xs font-semibold uppercase text-stone-500 mb-1">Pengiriman</div>
                            <div>Acara: {{ $order->event_date?->format('d M Y') }}</div>
                            <div>Metode: {{ ucfirst($order->delivery_method) }} ({{ number_format((float) $order->distance_km, 1) }} km)</div>
                            <div class="text-xs text-stone-500">{{ $order->delivery_address }}</div>
                        </div>

                        <div>
                            <div class="text-xs font-semibold uppercase text-stone-500 mb-1">Pembayaran</div>
                            <div>Metode: <span class="uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span></div>
                            <div>Ongkir: <span class="tabular-nums">Rp {{ number_format((float) $order->delivery_fee, 0, ',', '.') }}</span></div>
                            <div class="font-bold text-stone-900">Total: <span class="tabular-nums text-orange-700">Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}</span></div>
                        </div>
                    </div>

                    <!-- Aksi Validasi -->
                    @if (in_array($order->order_status, ['pending', 'waiting_validation', 'processed'], true))
                        <div class="flex flex-wrap justify-end gap-2 pt-3 border-t border-stone-100">
                            @if ($order->order_status === 'waiting_validation')
                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="order_status" value="processed">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-semibold bg-indigo-700 hover:bg-indigo-800 text-white transition">
                                        <i class="bi bi-check2-circle"></i>
                                        <span>Validasi &amp; Proses</span>
                                    </button>
                                </form>
                            @endif

                            @if ($order->order_status === 'processed')
                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="order_status" value="completed">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-semibold bg-emerald-700 hover:bg-emerald-800 text-white transition">
                                        <i class="bi bi-bag-check"></i>
                                        <span>Tandai Selesai</span>
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" onsubmit="return confirm('Batalkan pesanan {{ $order->order_code }}?')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="order_status" value="cancelled">
                                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold border border-red-200 text-red-700 hover:bg-red-50 transition">
                                    <i class="bi bi-x-circle"></i>
                                    <span>Batalkan</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white border border-stone-200 rounded-xl p-10 text-center text-stone-500">
                    Tidak ada pesanan yang cocok dengan filter.
                </div>
            @endforelse
        </div>

        <div>
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
