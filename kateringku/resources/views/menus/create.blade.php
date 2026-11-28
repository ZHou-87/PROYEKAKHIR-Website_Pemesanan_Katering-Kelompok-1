<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-stone-900">Tambah Menu Baru</h1>
            <p class="text-sm text-stone-600 mt-0.5">Lengkapi detail hidangan dan aturan minimal order.</p>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-sm">
            <form method="POST" action="{{ route('menus.store') }}" enctype="multipart/form-data">
                @include('menus._form')
            </form>
        </div>
    </div>
</x-app-layout>
