## 1. Standar Teknis Lintas Modul
- **Modul III (CRUD & Validasi):** Gunakan `Route::resource()`, Route Model Binding, Form Request terpisah (`StoreMenuRequest`, `UpdateMenuRequest`, `StoreOrderRequest`) dengan `authorize()`, `prepareForValidation()`, `rules()`, `messages()`, `attributes()`, dan `Rule::unique(...)->ignore(...)`. Wajib eager loading (`with()`, `withCount()`) karena `Model::preventLazyLoading()` aktif. Wajib definisikan `$fillable` eksplisit karena `Model::preventSilentlyDiscardingAttributes()` aktif. Gunakan `DB::table()` (`join`, `groupBy`, `DB::raw`) untuk laporan, serta `->when()` dan `->paginate(10)->withQueryString()`.
- **Modul IV (RESTful API & Fetch Client):** Endpoint API di `routes/api.php` prefix `v1` (`/api/v1/...`), middleware `ForceJsonResponse`, `Route::apiResource()`. Reuse Form Request Modul III di `Api\V1\*Controller`, bungkus JSON pakai API Resource (`MenuResource`, `OrderResource`) dengan `whenLoaded()` & `whenCounted()`. Auth Sanctum (`HasApiTokens` di `User`): `POST /api/v1/login` (`throttle:5,1`), `GET /api/v1/me`, `POST /api/v1/logout`. Fetch client di `public/js/menu-client.js` via `@stack('scripts')` memakai `apiRequest()`, custom `ApiError`, `tanganiError()`, sanitasi `esc()`, `URLSearchParams`, `tbody.onclick`, dan hapus token `localStorage` saat `401`.
- **Modul V (Auth & RBAC):** Breeze (Blade) + Spatie Permission (`HasRoles`) dengan 3 role persis: `'owner'`, `'admin'`, `'customer'`. Proteksi route berlapis (`['auth', 'role:...', 'can:...']`), tampilan memakai `@role` dan `@can`.
- **Modul VI (Keamanan Web & API):**
  - Kolom `user_id` pada `orders` DILARANG masuk `$fillable`; wajib diisi di controller via `$order->user_id = $request->user()->id;`.
  - Gunakan Gate (`kelola-menu`, `validasi-pesanan`, `kelola-user`, `lihat-laporan`) untuk BFLA, dan Policy (`MenuPolicy`, `OrderPolicy`, `UserPolicy`) dengan `Gate::authorize(...)` untuk IDOR di Web maupun API.
  - Anti-XSS: wajib `{{ $var }}` di Blade (dilarang `{!! !!}`) dan `esc()` / `textContent` di JS.
  - Anti-SQLi: gunakan Eloquent/parameter binding, dan wajib validasi whitelist `in_array($sort, $allowed, true)` pada `orderBy()` dinamis.
  - Anti-CSRF: sertakan `@csrf` di semua form dan header `X-CSRF-TOKEN` pada request AJAX/fetch web.
- **Modul VII (Vercel & Supabase):** Upload foto menu wajib memakai disk `'s3'` (Supabase Storage), dilarang disk `'public'` lokal. Query wajib kompatibel PostgreSQL.

## 2. Struktur Tabel Database (DILARANG UBAH NAMA KOLOM)
- `users`: `id`, `name`, `email`, `password`
- `menus`: `id`, `name`, `category` (`'makanan'` | `'minuman'`), `description`, `price` (decimal), `min_order` (integer), `image` (string url nullable), `is_active` (boolean)
- `orders`: `id`, `user_id`, `order_code`, `event_date` (date), `delivery_method` (`'delivery'` | `'pickup'`), `distance_km` (decimal), `delivery_fee` (decimal), `delivery_address` (text), `phone`, `notes` (nullable), `total_price` (decimal), `payment_method`, `payment_expired_at` (timestamp), `order_status`
- `order_items`: `id`, `order_id`, `menu_id`, `quantity` (integer), `price_per_portion` (decimal)

## 3. Aturan Bisnis KateringKu
- **Kategori & Minimal Order:** `menus.category` hanya `'makanan'` dan `'minuman'`. Varian Tumpeng (Versi 1, 2, 3) masuk kategori `'makanan'` dengan `min_order = 1`. Nasi Kotak & Minuman memiliki `min_order = 10`.
- **Pengiriman & Ongkir:** `delivery_address` SELALU WAJIB diisi (`required`) meski memilih `'pickup'`. Jika `'pickup'`, `distance_km = 0` dan `delivery_fee = 0`. Jika `'delivery'`, jarak maksimal `40` km, ongkir dibulatkan ke atas kelipatan 5 km: `ceil($distance_km / 5) * 15000` (misal 2 km otomatis dihitung tarif 5 km = Rp 15.000). `total_price = subtotal_items + delivery_fee`.
- **5 Nilai Baku `order_status`:** `'pending'`, `'waiting_validation'`, `'processed'`, `'completed'`, `'cancelled'`.
- **Pembayaran:** Tanpa upload screenshot. Halaman pembayaran menampilkan countdown timer 10 menit di pojok atas (`payment_expired_at`), tombol **Bayar** (`-> 'waiting_validation'`), dan tombol **Batalkan** (`-> 'cancelled'`). Jika waktu 10 menit habis dan belum dibayar, status otomatis menjadi `'cancelled'` (wajib divalidasi di backend controller).