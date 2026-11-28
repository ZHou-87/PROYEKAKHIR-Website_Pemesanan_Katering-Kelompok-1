<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-stone-900">Riwayat Pesanan Saya</h1>
                <p class="text-sm text-stone-600 mt-0.5">Pantau status pembayaran dan progres pesanan katering Anda.</p>
            </div>
            <a href="{{ route('customer.orders.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition self-start sm:self-auto">
                <i class="bi bi-plus-lg"></i>
                <span>Buat Pesanan Baru</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-5">
        @forelse ($orders as $order)
            <div class="bg-white border border-stone-200 rounded-xl p-5 shadow-sm space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-100 pb-3">
                    <div>
                        <span class="font-bold text-stone-900">{{ $order->order_code }}</span>
                        <span class="text-xs text-stone-500 ml-2">Acara: {{ $order->event_date?->format('d M Y') }}</span>
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wide text-stone-500 mb-1">Daftar Menu</div>
                        <ul class="space-y-1 text-stone-700">
                            @foreach ($order->items as $item)
                                <li class="flex justify-between">
                                    <span>{{ $item->menu?->name ?? 'Menu' }} &times; {{ $item->quantity }}</span>
                                    <span class="tabular-nums">Rp {{ number_format((float) $item->price_per_portion * $item->quantity, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="space-y-1 text-stone-700">
                        <div class="text-xs font-semibold uppercase tracking-wide text-stone-500 mb-1">Pengiriman &amp; Total</div>
                        <div>Metode: <span class="font-medium">{{ ucfirst($order->delivery_method) }}</span></div>
                        <div>Ongkir: <span class="tabular-nums font-medium">Rp {{ number_format((float) $order->delivery_fee, 0, ',', '.') }}</span></div>
                        <div class="text-base font-bold text-stone-900">
                            Total: <span class="text-orange-700 tabular-nums">Rp {{ number_format((float) $order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                @if (in_array($order->order_status, ['pending', 'waiting_validation'], true))
                    <div class="flex flex-wrap justify-end gap-2 pt-3 border-t border-stone-100">
                        @if ($order->order_status === 'pending')
                            <a href="{{ route('customer.orders.pay', $order) }}" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-semibold bg-emerald-700 hover:bg-emerald-800 text-white transition">
                                <i class="bi bi-check2-circle"></i>
                                <span>Halaman Pembayaran</span>
                            </a>
                        @endif

                        <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" onsubmit="return confirm('Batalkan pesanan {{ $order->order_code }}?')">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold border border-red-200 text-red-700 hover:bg-red-50 transition">
                                <i class="bi bi-x-circle"></i>
                                <span>Batalkan</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white border border-stone-200 rounded-xl p-10 text-center">
                <i class="bi bi-receipt text-4xl text-stone-400"></i>
                <h2 class="mt-3 text-base font-semibold text-stone-800">Belum ada pesanan</h2>
                <p class="mt-1 text-sm text-stone-500">Mulai pilih menu dari katalog untuk membuat pesanan katering.</p>
            </div>
        @endforelse

        <div>
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
