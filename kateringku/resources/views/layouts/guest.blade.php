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
    <body class="font-sans text-stone-900 antialiased bg-stone-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4 bg-stone-50">
            <div class="text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 text-stone-900 hover:text-orange-700 transition">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-orange-700 text-white">
                        <i class="bi bi-shop text-xl"></i>
                    </span>
                    <span class="text-2xl font-bold tracking-tight">{{ config('app.name', 'KateringKu') }}</span>
                </a>
                <p class="mt-1 text-xs text-stone-500">Katering Harian &amp; Acara Terpercaya</p>
            </div>

            @if (session('success') || session('error'))
                <div class="w-full sm:max-w-md mt-4 space-y-2">
                    @if (session('success'))
                        <div class="flex items-center gap-3 rounded-lg bg-white border border-stone-200 border-l-4 border-l-emerald-600 px-4 py-3 text-sm text-stone-900 shadow-sm" role="alert">
                            <i class="bi bi-check-circle text-emerald-700 text-lg shrink-0"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="flex items-center gap-3 rounded-lg bg-white border border-stone-200 border-l-4 border-l-red-600 px-4 py-3 text-sm text-stone-900 shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle text-red-700 text-lg shrink-0"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white border border-stone-200 shadow-sm overflow-hidden rounded-xl">
                @isset($slot)
                    {{ $slot }}
                @endisset
                @yield('content')
            </div>

            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-stone-600 hover:text-orange-700 transition">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
