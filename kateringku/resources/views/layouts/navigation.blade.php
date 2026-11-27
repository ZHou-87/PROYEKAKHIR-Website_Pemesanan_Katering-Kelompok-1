@php
    $brandUrl = url('/');
    if (auth()->check()) {
        if (auth()->user()->hasRole('owner')) {
            $brandUrl = url('/owner/dashboard');
        } elseif (auth()->user()->hasRole('admin')) {
            $brandUrl = url('/admin/dashboard');
        } elseif (auth()->user()->hasRole('customer')) {
            $brandUrl = url('/customer/dashboard');
        }
    }

    $desktopLinkClass = fn (bool $active): string => $active
        ? 'inline-flex items-center gap-1.5 px-1 pt-1 border-b-2 border-orange-700 text-sm font-semibold leading-5 text-stone-900 focus:outline-none transition duration-150 ease-in-out'
        : 'inline-flex items-center gap-1.5 px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-stone-600 hover:text-stone-900 hover:border-stone-300 focus:outline-none transition duration-150 ease-in-out';

    $mobileLinkClass = fn (bool $active): string => $active
        ? 'flex items-center gap-2.5 w-full ps-3 pe-4 py-2.5 border-l-4 border-orange-700 text-start text-sm font-semibold text-orange-800 bg-orange-50 focus:outline-none transition duration-150 ease-in-out'
        : 'flex items-center gap-2.5 w-full ps-3 pe-4 py-2.5 border-l-4 border-transparent text-start text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 hover:border-stone-300 focus:outline-none transition duration-150 ease-in-out';
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-stone-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ $brandUrl }}" class="inline-flex items-center gap-2 text-stone-900 hover:text-orange-700 transition">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-700 text-white">
                            <i class="bi bi-shop text-base"></i>
                        </span>
                        <span class="font-bold text-lg tracking-tight">{{ config('app.name', 'KateringKu') }}</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex">
                    @guest
                        <a href="{{ url('/') }}" class="{{ $desktopLinkClass(request()->is('/')) }}">
                            <i class="bi bi-house"></i>
                            <span>Beranda</span>
                        </a>
                        <a href="{{ url('/catalog') }}" class="{{ $desktopLinkClass(request()->is('catalog*')) }}">
                            <i class="bi bi-grid"></i>
                            <span>Katalog</span>
                        </a>
                    @endguest

                    @auth
                        @role('customer')
                            <a href="{{ url('/customer/dashboard') }}" class="{{ $desktopLinkClass(request()->is('customer/dashboard')) }}">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ url('/catalog') }}" class="{{ $desktopLinkClass(request()->is('catalog*')) }}">
                                <i class="bi bi-grid"></i>
                                <span>Katalog</span>
                            </a>
                            <a href="{{ url('/customer/orders') }}" class="{{ $desktopLinkClass(request()->is('customer/orders*')) }}">
                                <i class="bi bi-receipt"></i>
                                <span>Riwayat Pesanan</span>
                            </a>
                        @endrole

                        @role('admin')
                            <a href="{{ url('/admin/dashboard') }}" class="{{ $desktopLinkClass(request()->is('admin/dashboard')) }}">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        @endrole

                        @role('owner')
                            <a href="{{ url('/owner/dashboard') }}" class="{{ $desktopLinkClass(request()->is('owner/dashboard')) }}">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                        @endrole

                        @can('kelola-menu')
                            <a href="{{ url('/menus') }}" class="{{ $desktopLinkClass(request()->is('menus*')) }}">
                                <i class="bi bi-journal-text"></i>
                                <span>Kelola Menu</span>
                            </a>
                        @endcan

                        @can('validasi-pesanan')
                            <a href="{{ url('/admin/orders') }}" class="{{ $desktopLinkClass(request()->is('admin/orders*')) }}">
                                <i class="bi bi-bag-check"></i>
                                <span>Kelola Pesanan</span>
                            </a>
                        @endcan

                        @role('admin')
                            <a href="{{ url('/admin/customers') }}" class="{{ $desktopLinkClass(request()->is('admin/customers*')) }}">
                                <i class="bi bi-people"></i>
                                <span>Data Customer</span>
                            </a>
                        @endrole

                        @role('owner')
                            @can('lihat-laporan')
                                <a href="{{ url('/owner/reports') }}" class="{{ $desktopLinkClass(request()->is('owner/reports*')) }}">
                                    <i class="bi bi-bar-chart-line"></i>
                                    <span>Laporan Pemasukan</span>
                                </a>
                            @endcan
                            @can('kelola-user')
                                <a href="{{ url('/owner/users') }}" class="{{ $desktopLinkClass(request()->is('owner/users*')) }}">
                                    <i class="bi bi-person-gear"></i>
                                    <span>Kelola User</span>
                                </a>
                            @endcan
                        @endrole

                        <a href="{{ url('/profile') }}" class="{{ $desktopLinkClass(request()->is('profile*')) }}">
                            <i class="bi bi-person"></i>
                            <span>Profil</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Desktop Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                @guest
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-stone-700 hover:text-stone-900 hover:bg-stone-100 transition">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-orange-700 hover:bg-orange-800 text-white transition">
                        <i class="bi bi-person-plus"></i>
                        <span>Register</span>
                    </a>
                @endguest

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex items-center gap-2 px-3 py-2 border border-stone-200 text-sm leading-4 font-medium rounded-lg text-stone-700 bg-white hover:text-stone-900 hover:bg-stone-50 focus:outline-none transition ease-in-out duration-150">
                                <i class="bi bi-person-circle text-stone-500"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="bi bi-chevron-down text-xs text-stone-400"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="url('/profile')">
                                <i class="bi bi-person me-2"></i>Profil
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button type="button" @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-stone-500 hover:text-stone-900 hover:bg-stone-100 focus:outline-none transition duration-150 ease-in-out" aria-label="Toggle Menu">
                    <i class="bi bi-list text-2xl" x-show="!open"></i>
                    <i class="bi bi-x-lg text-xl" x-show="open" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-stone-200 bg-white">
        <div class="pt-2 pb-3 space-y-1">
            @guest
                <a href="{{ url('/') }}" class="{{ $mobileLinkClass(request()->is('/')) }}">
                    <i class="bi bi-house"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ url('/catalog') }}" class="{{ $mobileLinkClass(request()->is('catalog*')) }}">
                    <i class="bi bi-grid"></i>
                    <span>Katalog</span>
                </a>
                <a href="{{ route('login') }}" class="{{ $mobileLinkClass(request()->routeIs('login')) }}">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Login</span>
                </a>
                <a href="{{ route('register') }}" class="{{ $mobileLinkClass(request()->routeIs('register')) }}">
                    <i class="bi bi-person-plus"></i>
                    <span>Register</span>
                </a>
            @endguest

            @auth
                @role('customer')
                    <a href="{{ url('/customer/dashboard') }}" class="{{ $mobileLinkClass(request()->is('customer/dashboard')) }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ url('/catalog') }}" class="{{ $mobileLinkClass(request()->is('catalog*')) }}">
                        <i class="bi bi-grid"></i>
                        <span>Katalog</span>
                    </a>
                    <a href="{{ url('/customer/orders') }}" class="{{ $mobileLinkClass(request()->is('customer/orders*')) }}">
                        <i class="bi bi-receipt"></i>
                        <span>Riwayat Pesanan</span>
                    </a>
                @endrole

                @role('admin')
                    <a href="{{ url('/admin/dashboard') }}" class="{{ $mobileLinkClass(request()->is('admin/dashboard')) }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                @endrole

                @role('owner')
                    <a href="{{ url('/owner/dashboard') }}" class="{{ $mobileLinkClass(request()->is('owner/dashboard')) }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                @endrole

                @can('kelola-menu')
                    <a href="{{ url('/menus') }}" class="{{ $mobileLinkClass(request()->is('menus*')) }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Kelola Menu</span>
                    </a>
                @endcan

                @can('validasi-pesanan')
                    <a href="{{ url('/admin/orders') }}" class="{{ $mobileLinkClass(request()->is('admin/orders*')) }}">
                        <i class="bi bi-bag-check"></i>
                        <span>Kelola Pesanan</span>
                    </a>
                @endcan

                @role('admin')
                    <a href="{{ url('/admin/customers') }}" class="{{ $mobileLinkClass(request()->is('admin/customers*')) }}">
                        <i class="bi bi-people"></i>
                        <span>Data Customer</span>
                    </a>
                @endrole

                @role('owner')
                    @can('lihat-laporan')
                        <a href="{{ url('/owner/reports') }}" class="{{ $mobileLinkClass(request()->is('owner/reports*')) }}">
                            <i class="bi bi-bar-chart-line"></i>
                            <span>Laporan Pemasukan</span>
                        </a>
                    @endcan
                    @can('kelola-user')
                        <a href="{{ url('/owner/users') }}" class="{{ $mobileLinkClass(request()->is('owner/users*')) }}">
                            <i class="bi bi-person-gear"></i>
                            <span>Kelola User</span>
                        </a>
                    @endcan
                @endrole

                <a href="{{ url('/profile') }}" class="{{ $mobileLinkClass(request()->is('profile*')) }}">
                    <i class="bi bi-person"></i>
                    <span>Profil</span>
                </a>
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-2 border-t border-stone-200">
                <div class="px-4">
                    <div class="font-medium text-base text-stone-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-stone-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ url('/profile') }}" class="{{ $mobileLinkClass(request()->is('profile*')) }}">
                        <i class="bi bi-person"></i>
                        <span>Profil</span>
                    </a>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a href="{{ route('logout') }}"
                                class="flex items-center gap-2.5 w-full ps-3 pe-4 py-2.5 border-l-4 border-transparent text-start text-sm font-medium text-stone-600 hover:text-stone-900 hover:bg-stone-50 hover:border-stone-300 focus:outline-none transition duration-150 ease-in-out"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Keluar</span>
                        </a>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
