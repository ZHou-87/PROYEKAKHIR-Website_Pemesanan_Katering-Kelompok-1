<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-orange-50 text-orange-700 border border-orange-200">
                <i class="bi bi-people text-xl"></i>
            </span>
            <div>
                <h2 class="font-bold text-xl text-stone-900 leading-tight">
                    Daftar Customer
                </h2>
                <p class="text-xs text-stone-500">Daftar akun customer terdaftar (tampilan read-only untuk Admin).</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-stone-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-stone-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-base font-semibold text-stone-900">Pelanggan Terdaftar</h3>
                        <p class="text-xs text-stone-500">Menampilkan seluruh pengguna dengan role customer.</p>
                    </div>

                    <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-2">
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Cari nama atau email..."
                               class="rounded-lg border-stone-300 text-sm text-stone-900 shadow-sm focus:border-orange-700 focus:ring-orange-700" />
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-orange-700 hover:bg-orange-800 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                            <i class="bi bi-search"></i>
                            <span>Cari</span>
                        </button>
                        @if ($search !== '')
                            <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center px-3 py-2 border border-stone-300 text-stone-700 hover:bg-stone-50 text-sm font-medium rounded-lg transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-stone-200 text-sm">
                        <thead class="bg-stone-50 text-stone-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider">Jumlah Pesanan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-200 bg-white">
                            @forelse ($customers as $customer)
                                <tr class="hover:bg-stone-50/70 transition">
                                    <td class="px-6 py-4 font-semibold text-stone-900">
                                        {{ $customer->name }}
                                    </td>
                                    <td class="px-6 py-4 text-stone-600">
                                        {{ $customer->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-stone-100 text-stone-700 border border-stone-200">
                                            <i class="bi bi-person"></i>
                                            <span>Customer</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-stone-800">
                                        {{ $customer->orders_count }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-stone-500 whitespace-nowrap">
                                        {{ $customer->created_at?->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-stone-500">
                                        Belum ada data customer yang sesuai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($customers->hasPages())
                    <div class="px-6 py-4 border-t border-stone-200 bg-stone-50">
                        {{ $customers->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
