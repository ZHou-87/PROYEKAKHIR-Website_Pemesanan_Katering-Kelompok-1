<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-stone-900">Buat Pesanan Katering</h1>
                <p class="text-sm text-stone-600 mt-0.5">Pilih menu, tentukan jadwal acara, dan lengkapi detail pengiriman.</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-sm font-medium border border-stone-300 bg-white text-stone-700 hover:bg-stone-50 transition">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Katalog</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                <div class="font-semibold mb-1">Periksa kembali isian pesanan Anda:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.orders.store') }}" id="checkout-form" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            @csrf

            <!-- Kolom Kiri: Pilih Menu & Detail Pengiriman -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Pilih Menu -->
                <div class="bg-white border border-stone-200 rounded-xl p-5 shadow-sm">
                    <h2 class="text-base font-bold text-stone-900 mb-3">1. Pilih Menu &amp; Jumlah Porsi</h2>
                    <div class="divide-y divide-stone-200">
                        @foreach ($menus as $idx => $menu)
                            @php
                                $oldQty = old("items.{$idx}.quantity");
                                $initialQty = $oldQty !== null ? (int) $oldQty : ($selectedMenuId === $menu->id ? $menu->min_order : 0);
                            @endphp
                            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-stone-900">{{ $menu->name }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium {{ $menu->category === 'makanan' ? 'bg-orange-100 text-orange-800' : 'bg-sky-100 text-sky-800' }}">
                                            {{ ucfirst($menu->category) }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-stone-500 mt-0.5 flex items-center gap-3">
                                        <span class="tabular-nums font-semibold text-stone-700">Rp {{ number_format((float) $menu->price, 0, ',', '.') }} / porsi</span>
                                        <span><i class="bi bi-box-seam text-orange-700"></i> Min. {{ $menu->min_order }} porsi</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="items[{{ $idx }}][menu_id]" value="{{ $menu->id }}">
                                    <label for="qty-{{ $menu->id }}" class="text-xs text-stone-600">Jumlah:</label>
                                    <input
                                        type="number"
                                        id="qty-{{ $menu->id }}"
                                        name="items[{{ $idx }}][quantity]"
                                        min="0"
                                        value="{{ $initialQty }}"
                                        data-menu-qty
                                        data-name="{{ $menu->name }}"
                                        data-price="{{ (float) $menu->price }}"
                                        data-min="{{ $menu->min_order }}"
                                        class="w-24 px-3 py-1.5 text-sm border border-stone-300 rounded-lg tabular-nums text-right focus:ring-orange-600 focus:border-orange-600"
                                    >
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Detail Acara & Pengiriman -->
                <div class="bg-white border border-stone-200 rounded-xl p-5 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-stone-900">2. Detail Acara &amp; Pengiriman</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="event_date" class="block text-sm font-medium text-stone-700 mb-1">Tanggal Acara <span class="text-red-600">*</span></label>
                            <input
                                type="date"
                                id="event_date"
                                name="event_date"
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('event_date', date('Y-m-d')) }}"
                                required
                                class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600"
                            >
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-stone-700 mb-1">Nomor Telepon / WhatsApp <span class="text-red-600">*</span></label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                required
                                placeholder="081234567890"
                                class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="delivery_method" class="block text-sm font-medium text-stone-700 mb-1">Metode Pengiriman <span class="text-red-600">*</span></label>
                            <select id="delivery_method" name="delivery_method" required class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600">
                                <option value="delivery" @selected(old('delivery_method', 'delivery') === 'delivery')>Delivery (Kurir KateringKu)</option>
                                <option value="pickup" @selected(old('delivery_method') === 'pickup')>Pickup (Ambil di Tempat)</option>
                            </select>
                        </div>

                        <div id="distance-wrapper">
                            <label for="distance_km" class="block text-sm font-medium text-stone-700 mb-1">Jarak Pengiriman (km, Maks. 40 km)</label>
                            <input
                                type="number"
                                id="distance_km"
                                name="distance_km"
                                step="0.1"
                                min="0.1"
                                max="40"
                                value="{{ old('distance_km', 5) }}"
                                class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg tabular-nums focus:ring-orange-600 focus:border-orange-600"
                            >
                            <p class="mt-1 text-xs text-stone-500">Tarif Rp 15.000 per kelipatan 5 km (dibulatkan ke atas).</p>
                        </div>
                    </div>

                    <div>
                        <label for="delivery_address" class="block text-sm font-medium text-stone-700 mb-1">Alamat Lengkap <span class="text-red-600">*</span></label>
                        <textarea
                            id="delivery_address"
                            name="delivery_address"
                            rows="2"
                            required
                            placeholder="Wajib diisi meski memilih metode pickup (untuk data verifikasi pesanan)."
                            class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600"
                        >{{ old('delivery_address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-stone-700 mb-1">Metode Pembayaran <span class="text-red-600">*</span></label>
                            <select id="payment_method" name="payment_method" required class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600">
                                <option value="transfer_bca" @selected(old('payment_method') === 'transfer_bca')>Transfer Bank BCA</option>
                                <option value="transfer_mandiri" @selected(old('payment_method') === 'transfer_mandiri')>Transfer Bank Mandiri</option>
                                <option value="qris" @selected(old('payment_method') === 'qris')>QRIS</option>
                                <option value="cash" @selected(old('payment_method') === 'cash')>Tunai (Bayar di Tempat)</option>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-stone-700 mb-1">Catatan Tambahan (Opsional)</label>
                            <input
                                type="text"
                                id="notes"
                                name="notes"
                                value="{{ old('notes') }}"
                                placeholder="Contoh: Sambal dipisah, antar jam 10.00"
                                class="w-full px-3 py-2 text-sm border border-stone-300 rounded-lg focus:ring-orange-600 focus:border-orange-600"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Ringkasan Biaya -->
            <div class="lg:col-span-4">
                <div class="bg-white border border-stone-200 rounded-xl p-5 shadow-sm sticky top-6 space-y-4">
                    <h2 class="text-base font-bold text-stone-900">Ringkasan Pesanan</h2>

                    <ul id="summary-items" class="space-y-2 text-sm text-stone-700 border-b border-stone-200 pb-3">
                        <li class="text-stone-400">Belum ada menu dipilih.</li>
                    </ul>

                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between text-stone-600">
                            <span>Subtotal Menu</span>
                            <span id="summary-subtotal" class="font-semibold text-stone-900 tabular-nums">Rp 0</span>
                        </div>

                        <div class="flex items-center justify-between text-stone-600">
                            <span class="inline-flex items-center gap-1.5">
                                <i id="summary-delivery-icon" class="bi bi-truck text-orange-700"></i>
                                <span id="summary-delivery-label">Ongkos Kirim</span>
                            </span>
                            <span id="summary-delivery-fee" class="font-semibold text-stone-900 tabular-nums">Rp 0</span>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-stone-200 text-base font-bold text-stone-900">
                            <span>Total Tagihan</span>
                            <span id="summary-total" class="text-orange-700 tabular-nums">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
                        <i class="bi bi-bag-check"></i>
                        <span>Buat Pesanan &amp; Lanjut Bayar</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            (() => {
                const methodSelect = document.getElementById('delivery_method');
                const distanceWrapper = document.getElementById('distance-wrapper');
                const distanceInput = document.getElementById('distance_km');
                const qtyInputs = document.querySelectorAll('[data-menu-qty]');
                const summaryList = document.getElementById('summary-items');
                const subtotalEl = document.getElementById('summary-subtotal');
                const deliveryFeeEl = document.getElementById('summary-delivery-fee');
                const deliveryIcon = document.getElementById('summary-delivery-icon');
                const deliveryLabel = document.getElementById('summary-delivery-label');
                const totalEl = document.getElementById('summary-total');

                const formatRupiah = (angka) => 'Rp ' + Number(angka).toLocaleString('id-ID');

                function updateSummary() {
                    const method = methodSelect.value;
                    if (method === 'pickup') {
                        distanceWrapper.classList.add('hidden');
                        distanceInput.removeAttribute('required');
                        deliveryIcon.className = 'bi bi-shop text-orange-700';
                        deliveryLabel.textContent = 'Pickup (Gratis)';
                    } else {
                        distanceWrapper.classList.remove('hidden');
                        distanceInput.setAttribute('required', 'required');
                        deliveryIcon.className = 'bi bi-truck text-orange-700';
                        deliveryLabel.textContent = 'Ongkos Kirim';
                    }

                    let subtotal = 0;
                    summaryList.textContent = '';
                    let count = 0;

                    qtyInputs.forEach((input) => {
                        const qty = parseInt(input.value || '0', 10);
                        if (qty > 0) {
                            count++;
                            const price = parseFloat(input.dataset.price || '0');
                            const itemTotal = qty * price;
                            subtotal += itemTotal;

                            const li = document.createElement('li');
                            li.className = 'flex items-center justify-between gap-2';

                            const nameSpan = document.createElement('span');
                            nameSpan.textContent = `${input.dataset.name} (${qty}x)`;

                            const priceSpan = document.createElement('span');
                            priceSpan.className = 'tabular-nums font-medium text-stone-900';
                            priceSpan.textContent = formatRupiah(itemTotal);

                            li.appendChild(nameSpan);
                            li.appendChild(priceSpan);
                            summaryList.appendChild(li);
                        }
                    });

                    if (count === 0) {
                        const emptyLi = document.createElement('li');
                        emptyLi.className = 'text-stone-400';
                        emptyLi.textContent = 'Belum ada menu dipilih.';
                        summaryList.appendChild(emptyLi);
                    }

                    let deliveryFee = 0;
                    if (method === 'delivery') {
                        const km = Math.max(0, parseFloat(distanceInput.value || '0'));
                        if (km > 0) {
                            deliveryFee = Math.ceil(km / 5) * 15000;
                        }
                    }

                    subtotalEl.textContent = formatRupiah(subtotal);
                    deliveryFeeEl.textContent = formatRupiah(deliveryFee);
                    totalEl.textContent = formatRupiah(subtotal + deliveryFee);
                }

                methodSelect.addEventListener('change', updateSummary);
                distanceInput.addEventListener('input', updateSummary);
                qtyInputs.forEach((input) => input.addEventListener('input', updateSummary));
                updateSummary();
            })();
        </script>
    @endpush
</x-app-layout>
