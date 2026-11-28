@php
    $roleLabel = $user->getRoleNames()->map(fn (string $role) => ucfirst($role))->implode(', ');
@endphp

<section>
    <header class="flex items-start gap-3">
        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-stone-100 text-orange-700 border border-stone-200 shrink-0">
            <i class="bi bi-person-badge text-lg"></i>
        </span>
        <div>
            <h2 class="text-lg font-semibold text-stone-900">
                Informasi Profil
            </h2>
            <p class="mt-0.5 text-sm text-stone-600">
                Anda hanya dapat memperbarui nama lengkap. Email dan role bersifat read-only.
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-stone-700">Nama Lengkap</label>
            <input id="name"
                   name="name"
                   type="text"
                   value="{{ old('name', $user->name) }}"
                   required
                   autofocus
                   autocomplete="name"
                   class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
            <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-stone-700">Email (Read-Only)</label>
            <input id="email"
                   type="email"
                   value="{{ $user->email }}"
                   readonly
                   disabled
                   class="mt-1 block w-full rounded-lg border-stone-200 bg-stone-100 text-sm text-stone-600 shadow-sm cursor-not-allowed" />
            <p class="mt-1 text-xs text-stone-500">Email tidak dapat diubah untuk menjaga konsistensi autentikasi.</p>
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-stone-700">Role Akun (Read-Only)</label>
            <input id="role"
                   type="text"
                   value="{{ $roleLabel !== '' ? $roleLabel : 'Customer' }}"
                   readonly
                   disabled
                   class="mt-1 block w-full rounded-lg border-stone-200 bg-stone-100 text-sm text-stone-600 shadow-sm cursor-not-allowed" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-700 hover:bg-orange-800 focus:bg-orange-800 active:bg-orange-900 text-white text-sm font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:ring-offset-2 transition">
                <i class="bi bi-check-lg"></i>
                <span>Simpan Nama</span>
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm font-medium text-emerald-700">
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>
