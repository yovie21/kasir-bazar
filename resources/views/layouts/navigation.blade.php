<nav x-data="{ open: false }" class="navbar navbar-expand-lg navbar-light bg-white shadow fixed-top"> 
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('logo-ah.png') }}" alt="Logo" class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                {{-- ADMIN (ID 1): Master Data Dropdown --}}
                @if (Auth::user()->role === 1) 
                    <!-- Master Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-10 relative" x-data="{ masterOpen: false }">
                        <button @click="masterOpen = !masterOpen" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none transition">
                            {{ __('Master Data') }}
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="masterOpen" @click.away="masterOpen = false" class="absolute mt-2 w-48 bg-white border rounded shadow-lg z-10">
                            <x-dropdown-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                                {{ __('Data Produk') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('uoms.index')" :active="request()->routeIs('uoms.*')">
                                {{ __('Data UOM') }}
                            </x-dropdown-link>
                        </div>
                    </div>
                @endif
                
                {{-- KASIR (ID 3): Penjualan --}}
                @if (Auth::user()->role === 2)
                    <!-- Sales -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('kasir.index')" :active="request()->routeIs('kasir.index')">
                            {{ __('Penjualan (Kasir)') }}
                        </x-nav-link>
                    </div>
                @endif

                {{-- ADMIN & SPV (ID 1 dan 2): Laporan dan Riwayat Penjualan --}}
                @if (in_array(Auth::user()->role, [1, 3]))
                    <!-- Laporan Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-10 relative" x-data="{ reportOpen: false }">
                        <button @click="reportOpen = !reportOpen" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none transition">
                            {{ __('Laporan & Riwayat') }}
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="reportOpen" @click.away="reportOpen = false" class="absolute mt-2 w-56 bg-white border rounded shadow-lg z-10">
                            <x-dropdown-link :href="route('sales.index')" :active="request()->routeIs('sales.index')">
                                {{ __('Riwayat Penjualan') }}
                            </x-dropdown-link>
                            <div class="border-t my-1"></div>
                            <x-dropdown-link :href="route('laporan.stok')" :active="request()->routeIs('laporan.stok')">
                                {{ __('Laporan Stok') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('laporan.keuangan')" :active="request()->routeIs('laporan.keuangan')">
                                {{ __('Laporan Keuangan') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('laporan.transaksi')" :active="request()->routeIs('laporan.transaksi')">
                                {{ __('Laporan Transaksi') }}
                            </x-dropdown-link>
                        </div>
                    </div>
                @endif

                {{-- ADMIN (ID 1): Register User --}}
                @if (Auth::user()->role === 1)
                    <!-- Register -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('registeruser')" :active="request()->routeIs('registeruser')">
                            {{ __('Register User') }}
                        </x-nav-link>
                    </div>
                @endif
                
            </div>
            
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        {{-- (Tambahan: Anda mungkin perlu membuat relasi di User model untuk menampilkan nama role) --}}
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }} (ID: {{ Auth::user()->role }})</div> 
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            {{-- Responsive ADMIN (ID 1): Master Data --}}
            @if (Auth::user()->role === 1)
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="text-xs uppercase text-gray-600 px-4 mb-2">Master Data</div>
                    <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Data Produk') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('uoms.index')" :active="request()->routeIs('uoms.*')">
                        {{ __('Data UOM') }}
                    </x-responsive-nav-link>
                </div>
            @endif

            {{-- Responsive KASIR (ID 3): Penjualan --}}
            @if (Auth::user()->role === 2)
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <x-responsive-nav-link :href="route('kasir.index')" :active="request()->routeIs('kasir.index')">
                        {{ __('Transaksi Penjualan') }}
                    </x-responsive-nav-link>
                </div>
            @endif
            
            {{-- Responsive ADMIN & SPV (ID 1 dan 2): Laporan --}}
            @if (in_array(Auth::user()->role, [1, 3]))
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="text-xs uppercase text-gray-600 px-4 mb-2">Laporan</div>
                    <x-responsive-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.index')">
                        {{ __('Riwayat Penjualan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('laporan.stok')" :active="request()->routeIs('laporan.stok')">
                        {{ __('Laporan Stok') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('laporan.keuangan')" :active="request()->routeIs('laporan.keuangan')">
                        {{ __('Laporan Keuangan') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('laporan.transaksi')" :active="request()->routeIs('laporan.transaksi')">
                        {{ __('Laporan Transaksi') }}
                    </x-responsive-nav-link>
                </div>
            @endif

            {{-- Responsive ADMIN (ID 1): Register --}}
            @if (Auth::user()->role === 1)
                <div class="border-t border-gray-200 mt-2 pt-2">
                    <x-responsive-nav-link :href="route('registeruser')" :active="request()->routeIs('registeruser')">
                        {{ __('Register User') }}
                    </x-responsive-nav-link>
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        {{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
