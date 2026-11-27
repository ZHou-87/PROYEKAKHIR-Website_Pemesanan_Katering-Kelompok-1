@csrf
@isset($menu)
    @method('PUT')
@endisset

<div class="space-y-5">
    <div>
        <label for="name" class="block text-sm font-semibold text-stone-800 mb-1">Nama Menu <span class="text-red-600">*</span></label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $menu->name ?? '') }}"
            required
            class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-orange-600 focus:border-orange-600 @error('name') border-red-500 @else border-stone-300 @enderror"
            placeholder="Contoh: Nasi Kotak Ayam Bakar"
        >
        @error('name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label for="category" class="block text-sm font-semibold text-stone-800 mb-1">Kategori <span class="text-red-600">*</span></label>
            <select
                id="category"
                name="category"
                required
                class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-orange-600 focus:border-orange-600 @error('category') border-red-500 @else border-stone-300 @enderror"
            >
                <option value="">-- Pilih Kategori --</option>
                <option value="makanan" @selected(old('category', $menu->category ?? '') === 'makanan')>Makanan</option>
                <option value="minuman" @selected(old('category', $menu->category ?? '') === 'minuman')>Minuman</option>
            </select>
            @error('category')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="price" class="block text-sm font-semibold text-stone-800 mb-1">Harga per Porsi (Rp) <span class="text-red-600">*</span></label>
            <input
                type="number"
                id="price"
                name="price"
                min="0"
                step="500"
                value="{{ old('price', isset($menu) ? (int) $menu->price : '') }}"
                required
                class="w-full px-3 py-2 text-sm border rounded-lg tabular-nums focus:ring-orange-600 focus:border-orange-600 @error('price') border-red-500 @else border-stone-300 @enderror"
                placeholder="25000"
            >
            @error('price')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="min_order" class="block text-sm font-semibold text-stone-800 mb-1">Minimal Order (Porsi) <span class="text-red-600">*</span></label>
            <input
                type="number"
                id="min_order"
                name="min_order"
                min="1"
                value="{{ old('min_order', $menu->min_order ?? 10) }}"
                required
                class="w-full px-3 py-2 text-sm border rounded-lg tabular-nums focus:ring-orange-600 focus:border-orange-600 @error('min_order') border-red-500 @else border-stone-300 @enderror"
            >
            @error('min_order')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="description" class="block text-sm font-semibold text-stone-800 mb-1">Deskripsi</label>
        <textarea
            id="description"
            name="description"
            rows="3"
            class="w-full px-3 py-2 text-sm border rounded-lg focus:ring-orange-600 focus:border-orange-600 @error('description') border-red-500 @else border-stone-300 @enderror"
            placeholder="Rincian lauk, porsi, dan kelengkapan menu..."
        >{{ old('description', $menu->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="image" class="block text-sm font-semibold text-stone-800 mb-1">Foto Menu (Opsional, Maks. 2 MB)</label>
        <input
            type="file"
            id="image"
            name="image"
            accept="image/jpeg,image/png,image/webp"
            class="block w-full text-sm text-stone-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200"
        >
        @isset($menu)
            @if ($menu->image)
                <div class="mt-2 flex items-center gap-3">
                    <img src="{{ $menu->image }}" alt="{{ $menu->name }}" class="w-16 h-16 object-cover rounded-lg border border-stone-200">
                    <span class="text-xs text-stone-500">Foto saat ini tersimpan di Supabase Storage.</span>
                </div>
            @endif
        @endisset
        @error('image')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-2">
        <input
            type="checkbox"
            id="is_active"
            name="is_active"
            value="1"
            @checked(old('is_active', $menu->is_active ?? true))
            class="rounded border-stone-300 text-orange-700 focus:ring-orange-600"
        >
        <label for="is_active" class="text-sm font-medium text-stone-700">Tampilkan menu di katalog publik (Aktif)</label>
    </div>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-200">
        <a href="{{ route('menus.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium border border-stone-300 text-stone-700 hover:bg-stone-100 transition">
            <i class="bi bi-arrow-left"></i>
            <span>Batal</span>
        </a>
        <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-lg text-sm font-semibold bg-orange-700 hover:bg-orange-800 text-white transition">
            <i class="bi bi-check-lg"></i>
            <span>{{ isset($menu) ? 'Simpan Perubahan' : 'Simpan Menu' }}</span>
        </button>
    </div>
</div>
