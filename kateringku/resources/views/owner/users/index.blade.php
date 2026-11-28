<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-orange-50 text-orange-700 border border-orange-200">
                <i class="bi bi-person-gear text-xl"></i>
            </span>
            <div>
                <h2 class="font-bold text-xl text-stone-900 leading-tight">
                    Manajemen User
                </h2>
                <p class="text-xs text-stone-500">Tambah akun baru, atur role pengguna, atau hapus akun secara aman.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Tambah Akun Baru -->
            <div class="bg-white border border-stone-200 rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-50 text-orange-700 border border-orange-200">
                        <i class="bi bi-person-plus"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-stone-900">Tambah Akun Baru</h3>
                        <p class="text-xs text-stone-500">Buat akun baru untuk admin, customer, atau owner tambahan.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('owner.users.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-start">
                    @csrf

                    <div>
                        <label for="new_name" class="block text-xs font-medium text-stone-700">Nama Lengkap</label>
                        <input id="new_name"
                               name="name"
                               type="text"
                               value="{{ old('name') }}"
                               required
                               placeholder="Nama user"
                               class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <label for="new_email" class="block text-xs font-medium text-stone-700">Email</label>
                        <input id="new_email"
                               name="email"
                               type="email"
                               value="{{ old('email') }}"
                               required
                               placeholder="user@kateringku.com"
                               class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <div>
                        <label for="new_role" class="block text-xs font-medium text-stone-700">Role</label>
                        <select id="new_role"
                                name="role"
                                required
                                class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700">
                            @foreach ($roles as $roleOption)
                                <option value="{{ $roleOption }}" @selected(old('role', 'admin') === $roleOption)>
                                    {{ ucfirst($roleOption) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-1" />
                    </div>

                    <div>
                        <label for="new_password" class="block text-xs font-medium text-stone-700">Password</label>
                        <input id="new_password"
                               name="password"
                               type="password"
                               required
                               placeholder="Minimal 8 karakter"
                               class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-xs font-medium text-stone-700">Konfirmasi Password</label>
                        <input id="new_password_confirmation"
                               name="password_confirmation"
                               type="password"
                               required
                               placeholder="Ulangi password"
                               class="mt-1 block w-full rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
                    </div>

                    <div class="md:col-span-2 lg:col-span-5 flex justify-end pt-1">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-700 hover:bg-orange-800 focus:bg-orange-800 active:bg-orange-900 text-white text-sm font-semibold rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-700 focus:ring-offset-2 transition">
                            <i class="bi bi-person-plus"></i>
                            <span>Tambah User</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Filter & Tabel Daftar User -->
            <div class="bg-white border border-stone-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-stone-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-stone-900">Daftar Pengguna</h3>
                        <p class="text-xs text-stone-500">Anda tidak dapat mengubah role atau menghapus akun Anda sendiri.</p>
                    </div>

                    <form method="GET" action="{{ route('owner.users.index') }}" class="flex flex-wrap items-center gap-2">
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari nama atau email..."
                               class="rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />

                        <select name="role"
                                class="rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700">
                            <option value="">Semua Role</option>
                            @foreach ($roles as $roleOption)
                                <option value="{{ $roleOption }}" @selected($roleFilter === $roleOption)>
                                    {{ ucfirst($roleOption) }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-stone-900 hover:bg-stone-800 text-white text-sm font-medium rounded-lg transition">
                            <i class="bi bi-search"></i>
                            <span>Filter</span>
                        </button>

                        @if ($search !== '' || $roleFilter !== '')
                            <a href="{{ route('owner.users.index') }}" class="inline-flex items-center gap-1 px-3 py-2 border border-stone-300 text-stone-700 hover:bg-stone-50 text-sm font-medium rounded-lg transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200 text-sm">
                        <thead class="bg-stone-50 text-stone-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Pengguna</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Role Saat Ini</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Terdaftar</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 bg-white">
                            @forelse ($users as $targetUser)
                                @php
                                    $currentRole = $targetUser->roles->first()?->name ?? '-';
                                    $badgeClass = match ($currentRole) {
                                        'owner' => 'bg-orange-50 text-orange-800 border-orange-200',
                                        'admin' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        default => 'bg-stone-100 text-stone-700 border-stone-200',
                                    };
                                @endphp
                                <tr class="hover:bg-stone-50/70 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-stone-900">
                                            {{ $targetUser->name }}
                                            @if (auth()->id() === $targetUser->id)
                                                <span class="ms-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Akun Anda
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-stone-500">{{ $targetUser->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                            <i class="bi bi-shield-check"></i>
                                            <span>{{ ucfirst($currentRole) }}</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-stone-500 whitespace-nowrap">
                                        {{ $targetUser->created_at?->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            @can('update', $targetUser)
                                                <form method="POST" action="{{ route('owner.users.update-role', $targetUser) }}" class="inline-flex items-center gap-1.5">
                                                    @csrf
                                                    @method('patch')
                                                    <select name="role" aria-label="Pilih role untuk {{ $targetUser->name }}" class="rounded-lg border-stone-300 text-xs text-stone-800 py-1.5 ps-2.5 pe-7 shadow-sm focus:border-orange-700 focus:ring-orange-700">
                                                        @foreach ($roles as $roleOption)
                                                            <option value="{{ $roleOption }}" @selected($currentRole === $roleOption)>
                                                                {{ ucfirst($roleOption) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-orange-700 hover:bg-orange-800 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                                        <i class="bi bi-shield-check"></i>
                                                        <span>Ubah Role</span>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-stone-400 italic">Dilindungi</span>
                                            @endcan

                                            @can('delete', $targetUser)
                                                <form method="POST" action="{{ route('owner.users.destroy', $targetUser) }}" onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                                        <i class="bi bi-trash"></i>
                                                        <span>Hapus</span>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-stone-500">
                                        Tidak ada data pengguna yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t border-stone-200 bg-stone-50">
                        {{ $users->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
