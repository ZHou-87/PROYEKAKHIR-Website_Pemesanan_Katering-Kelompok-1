<x-guest-layout>
    <div class="mb-6">
        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-orange-50 text-orange-700 border border-orange-200">
                <i class="bi bi-person-plus text-lg"></i>
            </span>
            <div>
                <h1 class="text-lg font-bold text-stone-900">Daftar Akun Baru</h1>
                <p class="text-xs text-stone-500">Buat akun customer untuk mulai memesan paket katering.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-stone-700">Nama Lengkap</label>
            <input id="name"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   autocomplete="name"
                   placeholder="Masukkan nama lengkap"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autocomplete="username"
                   placeholder="nama@email.com"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-stone-700">Password</label>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="new-password"
                   placeholder="Minimal 8 karakter"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-stone-700">Konfirmasi Password</label>
            <input id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   required
                   autocomplete="new-password"
                   placeholder="Ulangi password"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-700 hover:bg-orange-800 focus:bg-orange-800 active:bg-orange-900 text-white text-sm font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:ring-offset-2 transition">
            <i class="bi bi-person-plus"></i>
            <span>Daftar Sekarang</span>
        </button>

        <p class="pt-2 text-center text-xs text-stone-600 border-t border-stone-200">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-orange-700 hover:text-orange-800 hover:underline">
                Masuk di sini
            </a>
        </p>
    </form>
</x-guest-layout>
