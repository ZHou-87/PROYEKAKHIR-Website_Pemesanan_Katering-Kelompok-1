<x-guest-layout>
    <div class="mb-5">
        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-orange-50 text-orange-700 border border-orange-200">
                <i class="bi bi-key text-lg"></i>
            </span>
            <div>
                <h1 class="text-lg font-bold text-stone-900">Lupa Password</h1>
                <p class="text-xs text-stone-500">Verifikasi email terdaftar untuk langsung mengatur ulang password.</p>
            </div>
        </div>
    </div>

    <div class="mb-4 rounded-lg bg-stone-50 border border-stone-200 px-3.5 py-2.5 text-xs text-stone-600 leading-relaxed">
        Masukkan alamat email akun Anda yang terdaftar. Sistem akan membuat token reset resmi dan mengarahkan Anda langsung ke halaman pembuatan password baru.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-stone-700">Email Terdaftar</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   placeholder="nama@email.com"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-700 hover:bg-orange-800 focus:bg-orange-800 active:bg-orange-900 text-white text-sm font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:ring-offset-2 transition">
            <i class="bi bi-key"></i>
            <span>Lanjutkan Reset Password</span>
        </button>

        <p class="pt-2 text-center text-xs text-stone-600 border-t border-stone-200">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="font-semibold text-orange-700 hover:text-orange-800 hover:underline">
                Kembali ke Login
            </a>
        </p>
    </form>
</x-guest-layout>
