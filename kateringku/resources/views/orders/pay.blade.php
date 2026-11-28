<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-stone-900">Pembayaran Pesanan {{ $order->order_code }}</h1>
                <p class="text-sm text-stone-600 mt-0.5">Selesaikan konfirmasi pembayaran sebelum batas waktu 10 menit berakhir.</p>
            </div>

            <!-- Countdown Timer di Pojok Kanan Atas -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border {{ $order->order_status === 'pending' ? 'bg-amber-50 border-amber-200 text-amber-900' : 'bg-stone-100 border-stone-200 text-stone-700' }}">
                <i class="bi bi-stopwatch text-lg"></i>
                <div class="text-right">
                    <span class="block text-[10px] uppercase tracking-wider font-semibold">Sisa Waktu</span>
                    <span
                        id="payment-countdown"
                        data-expired-at="{{ $order->payment_expired_at?->toIso8601String() }}"
                        data-status="{{ $order->order_status }}"
                        class="text-base font-bold tabular-nums"
                    >
                        {{ $order->order_status === 'pending' ? '10:00' : '00:00' }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm space-y-5">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-200 pb-4">
                <div>
                    <div class="text-xs text-stone-500">Kode Pesanan</div>
                    <div class="text-lg font-bold text-stone-900">{{ $order->order_code }}</div>
                </div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                        @if ($order->order_status === 'pending') bg-amber-100 text-amber-800
                        @elseif ($order->order_status === 'waiting_validation') bg-sky-100 text-sky-800
                        @elseif ($order->order_status === 'processed') bg-indigo-100 text-indigo-800
                        @elseif ($order->order_status === 'completed') bg-emerald-100 text-emerald-800
                        @else bg-red-100 text-red-800 @endif">
                        Status: {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                    </span>
                </div>
            </div>

            <!-- Rincian Item -->
            <div>
                <h2 class="text-sm font-bold text-stone-900 mb-2">Rincian Menu</h2>
                <div class="divide-y divide-stone-100 border border-stone-200 rounded-lg">
                    @foreach ($order->items as $item)
                        <div class="px-4 py-3 flex items-center justify-between text-sm">
                            <div>
                                <div class="font-medium text-stone-900">{{ $item->menu?->name ?? 'Menu' }}</div>
                                <div class="text-xs text-stone-500 tabular-nums">{{ $item->quantity }} porsi &times; Rp {{ number_format((float) $item->price_per_portion, 0, ',', '.') }}</div>
                            </div>
                            <div class="font-semibold text-stone-900 tabular-nums">
                                Rp {{ number_format((float) $item->price_per_portion * $item->quantity, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Rincian Ongkir & Total -->
            <div class="bg-stone-50 border border-stone-200 rounded-lg p-4 space-y-2 text-sm">
                <div class="flex justify-between text-stone-600">
                    <span>Metode Pengiriman</span>
                    <span class="font-medium text-stone-900">
                        {{ $order->delivery_method === 'pickup' ? 'Pickup (Ambil di Tempat)' : 'Delivery (' . number_format((float) $order->distance_km, 1) . ' km)' }}
                    </span>
                </div>
                <div class="flex justify-between text-stone-600">
                    <span>Ongkos Kirim</span>
                    <span class="font-semibold text-stone-900 tabular-nums">Rp {{ number_format((float) $order->delivery_fee, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-stone-600">
                    <span>Metode Pembayaran</span>
                    <span class="font-medium text-stone-900 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-stone-200 text-base font-bold text-stone-900">
                    <span>Total Tagihan</span>
                    <span class="text-orange-700 tabular-nums">Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Tombol Aksi -->
            @if ($order->order_status === 'pending')
                <div id="payment-actions" class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2">
                    <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-lg text-sm font-semibold border border-red-300 text-red-700 hover:bg-red-50 transition">
                            <i class="bi bi-x-circle"></i>
                            <span>Batalkan</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('customer.orders.confirm-payment', $order) }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" id="btn-pay" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-lg text-sm font-semibold bg-emerald-700 hover:bg-emerald-800 text-white transition">
                            <i class="bi bi-check2-circle"></i>
                            <span>Bayar</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="flex justify-end">
                    <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-stone-900 text-white hover:bg-stone-800 transition">
                        <i class="bi bi-receipt"></i>
                        <span>Lihat Riwayat Pesanan</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const timerEl = document.getElementById('payment-countdown');
                if (!timerEl || timerEl.dataset.status !== 'pending') return;

                const expiredAt = new Date(timerEl.dataset.expiredAt).getTime();
                const payBtn = document.getElementById('btn-pay');

                function tick() {
                    const diffMs = expiredAt - Date.now();
                    if (diffMs <= 0) {
                        timerEl.textContent = '00:00';
                        if (payBtn) {
                            payBtn.disabled = true;
                            payBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                        return;
                    }

                    const totalSeconds = Math.floor(diffMs / 1000);
                    const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
                    const seconds = String(totalSeconds % 60).padStart(2, '0');
                    timerEl.textContent = `${minutes}:${seconds}`;
                }

                tick();
                setInterval(tick, 1000);
            })();
        </script>
    @endpush
</x-app-layout>
