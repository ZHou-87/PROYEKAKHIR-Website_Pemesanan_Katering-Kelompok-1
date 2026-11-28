<x-guest-layout>
    <div class="mb-6">
        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-orange-50 text-orange-700 border border-orange-200">
                <i class="bi bi-box-arrow-in-right text-lg"></i>
            </span>
            <div>
                <h1 class="text-lg font-bold text-stone-900">Masuk ke KateringKu</h1>
                <p class="text-xs text-stone-500">Gunakan akun Anda untuk memesan atau mengelola katering.</p>
            </div>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="nama@email.com"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-stone-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-orange-700 hover:text-orange-800 hover:underline">
                        Lupa password?
                    </a>
                @endif
            </div>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="Masukkan password"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-stone-300 text-orange-700 shadow-sm focus:ring-orange-700">
                <span class="ms-2 text-sm text-stone-600">Ingat saya</span>
            </label>
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-700 hover:bg-orange-800 focus:bg-orange-800 active:bg-orange-900 text-white text-sm font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:ring-offset-2 transition">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Masuk</span>
        </button>

        @if (Route::has('register'))
            <p class="pt-2 text-center text-xs text-stone-600 border-t border-stone-200">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-orange-700 hover:text-orange-800 hover:underline">
                    Daftar sebagai Customer
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>
