<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-orange-50 text-orange-700 border border-orange-200">
                    <i class="bi bi-person-badge text-xl"></i>
                </span>
                <div>
                    <h2 class="font-bold text-xl text-stone-900 leading-tight">
                        Profil Akun
                    </h2>
                    <p class="text-xs text-stone-500">Kelola nama tampilan dan keamanan password akun Anda.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-white border border-stone-200 shadow-sm rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white border border-stone-200 shadow-sm rounded-xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
