<section>
    <header class="flex items-start gap-3">
        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-stone-100 text-orange-700 border border-stone-200 shrink-0">
            <i class="bi bi-key text-lg"></i>
        </span>
        <div>
            <h2 class="text-lg font-semibold text-stone-900">
                Ubah Password
            </h2>
            <p class="mt-0.5 text-sm text-stone-600">
                Pastikan akun Anda menggunakan password yang kuat dan tidak dibagikan ke pihak lain.
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-stone-700">Password Saat Ini</label>
            <input id="update_password_current_password"
                   name="current_password"
                   type="password"
                   required
                   autocomplete="current-password"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-stone-700">Password Baru</label>
            <input id="update_password_password"
                   name="password"
                   type="password"
                   required
                   autocomplete="new-password"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-stone-700">Konfirmasi Password Baru</label>
            <input id="update_password_password_confirmation"
                   name="password_confirmation"
                   type="password"
                   required
                   autocomplete="new-password"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-700 hover:bg-orange-800 focus:bg-orange-800 active:bg-orange-900 text-white text-sm font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:ring-offset-2 transition">
                <i class="bi bi-key"></i>
                <span>Perbarui Password</span>
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm font-medium text-emerald-700">
                    Password diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>
