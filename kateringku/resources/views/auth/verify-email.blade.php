<x-guest-layout>
    <div class="mb-4 text-sm text-stone-600 leading-relaxed">
        Terima kasih telah mendaftar. Silakan verifikasi alamat email Anda melalui tautan yang dikirimkan ke email Anda.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-3 py-2 text-sm font-medium text-emerald-700">
            Tautan verifikasi baru telah dikirim ke alamat email Anda.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-700 hover:bg-orange-800 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                <i class="bi bi-envelope"></i>
                <span>Kirim Ulang Verifikasi</span>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm font-medium text-stone-600 hover:text-stone-900 underline">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
