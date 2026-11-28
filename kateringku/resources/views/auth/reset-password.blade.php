<x-guest-layout>
    <div class="mb-5">
        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-orange-50 text-orange-700 border border-orange-200">
                <i class="bi bi-key text-lg"></i>
            </span>
            <div>
                <h1 class="text-lg font-bold text-stone-900">Atur Ulang Password</h1>
                <p class="text-xs text-stone-500">Buat password baru yang aman untuk akun Anda.</p>
            </div>
        </div>
    </div>

    <x-auth-session-status class="mb-4 text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2" :status="session('status')" />

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email', $request->email) }}"
                   required
                   autofocus
                   autocomplete="username"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-stone-700">Password Baru</label>
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
            <label for="password_confirmation" class="block text-sm font-medium text-stone-700">Konfirmasi Password Baru</label>
            <input id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   required
                   autocomplete="new-password"
                   placeholder="Ulangi password baru"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-700 hover:bg-orange-800 focus:bg-orange-800 active:bg-orange-900 text-white text-sm font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:ring-offset-2 transition">
            <i class="bi bi-key"></i>
            <span>Simpan Password Baru</span>
        </button>
    </form>
</x-guest-layout>
