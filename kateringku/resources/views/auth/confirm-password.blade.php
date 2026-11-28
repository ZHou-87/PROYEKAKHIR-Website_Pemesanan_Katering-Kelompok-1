<x-guest-layout>
    <div class="mb-5">
        <div class="flex items-center gap-2.5">
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-orange-50 text-orange-700 border border-orange-200">
                <i class="bi bi-shield-lock text-lg"></i>
            </span>
            <div>
                <h1 class="text-lg font-bold text-stone-900">Konfirmasi Password</h1>
                <p class="text-xs text-stone-500">Area aman. Masukkan password Anda sebelum melanjutkan.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-stone-700">Password</label>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-700 hover:bg-orange-800 text-white text-sm font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:ring-offset-2 transition">
            <i class="bi bi-check-lg"></i>
            <span>Konfirmasi</span>
        </button>
    </form>
</x-guest-layout>
