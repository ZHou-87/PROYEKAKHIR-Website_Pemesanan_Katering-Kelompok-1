<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KateringKu') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-stone-50 text-stone-900">
        <div class="min-h-screen flex flex-col bg-stone-50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-stone-200">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Flash Alerts -->
            @if (session('success') || session('error'))
                <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between gap-3 rounded-lg bg-white border border-stone-200 border-l-4 border-l-emerald-600 px-4 py-3 text-sm text-stone-900 shadow-sm" role="alert">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-check-circle text-emerald-700 text-lg shrink-0"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button type="button" @click="show = false" class="text-stone-400 hover:text-stone-700 focus:outline-none" aria-label="Tutup">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div x-data="{ show: true }" x-show="show" class="mt-2 flex items-center justify-between gap-3 rounded-lg bg-white border border-stone-200 border-l-4 border-l-red-600 px-4 py-3 text-sm text-stone-900 shadow-sm" role="alert">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-exclamation-triangle text-red-700 text-lg shrink-0"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button type="button" @click="show = false" class="text-stone-400 hover:text-stone-700 focus:outline-none" aria-label="Tutup">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                @isset($slot)
                    {{ $slot }}
                @endisset
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-stone-200 mt-12">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-stone-500">
                    <span class="font-medium text-stone-700">{{ config('app.name', 'KateringKu') }}</span>
                    <span>&copy; {{ date('Y') }} KateringKu. Layanan katering harian &amp; acara.</span>
                </div>
            </footer>
        </div>

        @stack('scripts')
    </body>
</html>
