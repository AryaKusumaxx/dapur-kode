<nav x-data="{ open: false, profileDropdown: false, productDropdown: false }" class="navbar-container glass-effect sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="container mx-auto px-4 lg:px-6">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center logo-container">
                        <img src="{{ asset('images/dapurkode.png') }}" alt="DapurKode Logo" class="h-8 w-auto transition-transform duration-300 ease-in-out hover:scale-105" />
                    </a>
                </div>

                <!-- Navigation Links - Dynamic based on user role -->
                <div class="hidden sm:flex items-center ml-8">
                    <div class="flex space-x-1">
                        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'nav-active' : '' }} flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>{{ __('Home') }}</span>
                        </a>

                        <!-- Products with dropdown -->
                        <div class="relative" @click.away="productDropdown = false">
                            <button @click="productDropdown = !productDropdown" class="nav-item flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                                </svg>
                                <span>{{ __('Products') }}</span>
                                <svg class="ml-1 w-4 h-4 transition-transform duration-200" :class="{'rotate-180': productDropdown}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                       <!-- Product Dropdown -->
<div x-cloak x-show="productDropdown" class="nav-dropdown absolute left-0 mt-2 w-56 py-2 z-50 bg-gray-800"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="transform opacity-0 translate-y-2"
     x-transition:enter-end="transform opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="transform opacity-100 translate-y-0"
     x-transition:leave-end="transform opacity-0 translate-y-2">
    <div class="px-4 py-2 font-medium text-sm text-blue-300 uppercase tracking-wider border-b border-gray-700">
        Kategori Produk
    </div>
    <a href="{{ route('products.index', ['type' => 'paket']) }}" class="dropdown-item flex items-center px-4 py-2 text-sm">
        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-yellow-900/20 mr-2.5">
            <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
        </span>
        Paket Dapur
    </a>
    <a href="{{ route('products.index', ['type' => 'jasa_pasang']) }}" class="dropdown-item flex items-center px-4 py-2 text-sm">
        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-900/20 mr-2.5">
            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
        </span>
        Jasa Pemasangan
    </a>
    <a href="{{ route('products.index', ['type' => 'lepas']) }}" class="dropdown-item flex items-center px-4 py-2 text-sm">
        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-900/20 mr-2.5">
            <span class="w-2 h-2 rounded-full bg-green-400"></span>
        </span>
        Produk Lepas
    </a>
</div>

                    </div>

                    @auth
                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }} flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>{{ __('Admin Dashboard') }}</span>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            </div>

            <div class="flex items-center gap-4">
                <!-- Search Box -->
                <div class="hidden lg:flex items-center relative">
                    <div class="search-box relative">
                        <form action="{{ route('products.index') }}" method="GET" class="search-box relative">
                            <input 
                                type="text" 
                                name="search" 
                                class="bg-white text-black placeholder-gray-400 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all duration-200"
                                placeholder="Cari produk..." 
                                value="{{ request('search') }}"
                            >
                            <button type="submit" class="hidden"></button>
                        </form>
                    </div>
                </div>
                
                <!-- Authentication Links - Desktop View -->
                <div class="hidden sm:flex items-center gap-3">
                    @auth
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" class="notification-button flex items-center justify-center hover:bg-gray-700/50 rounded-full focus:outline-none transition duration-200 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-pink-500 rounded-full w-5 h-5 text-xs text-white flex items-center justify-center shadow-lg animate-pulse ring-2 ring-red-500/20">3</span>
                    </button>
                    
                    <div x-cloak x-show="notifOpen" @click.away="notifOpen = false" class="nav-dropdown absolute right-0 mt-2 w-80 py-2 z-50" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-2" 
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2">
                        <h3 class="px-4 py-3 font-semibold text-white border-b border-gray-700 flex items-center justify-between bg-gray-700/50">
                            <span>Notifications</span>
                            <span class="bg-red-500/80 text-white text-xs font-medium px-2 py-0.5 rounded">3 New</span>
                        </h3>
                        <div class="max-h-64 overflow-y-auto">
                            <a href="#" class="dropdown-item px-4 py-3 border-b border-gray-700">
                                <p class="font-medium">Order #1234 confirmed</p>
                                <p class="text-xs text-gray-400 mt-1">2 minutes ago</p>
                            </a>
                            <a href="#" class="dropdown-item px-4 py-3 border-b border-gray-700">
                                <p class="font-medium">Payment received</p>
                                <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                            </a>
                            <a href="#" class="dropdown-item px-4 py-3">
                                <p class="font-medium">Warranty expires soon</p>
                                <p class="text-xs text-gray-400 mt-1">Yesterday</p>
                            </a>
                        </div>
                        <a href="#" class="block px-4 py-2 text-sm text-center text-blue-400 border-t border-gray-700 font-medium hover:text-blue-300 transition duration-200">View all notifications</a>
                    </div>
                </div>

                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="profile-button flex items-center gap-3 pl-3 pr-2 py-1.5">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full avatar-gradient flex items-center justify-center overflow-hidden text-white ring-2 ring-blue-400/30 transition-all duration-300 hover:scale-105">
                                    <span class="text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <div class="font-medium text-gray-200">{{ Auth::user()->name }}</div>
                            </div>
                            <svg class="fill-current h-4 w-4 text-gray-400 transition-transform duration-200 hover:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                @else
                    <a href="{{ route('login') }}" class="auth-button-login">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="auth-button-signup">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>Sign Up</span>
                    </a>
                @endauth
                </div>

                    <x-slot name="content">
                        <div class="bg-gray-800 border-b border-gray-700 pb-1 px-4 pt-2">
                            <p class="text-xs text-gray-400">Signed in as</p>
                            <p class="text-sm font-semibold text-gray-200 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <div class="bg-gray-800">
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-white hover:bg-blue-600/30 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ __('Profile') }}
                            </x-dropdown-link>
                        </div>
                        
                        @if(Auth::user()->isCustomer())
                            <x-dropdown-link :href="route('dashboard')" class="flex items-center gap-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ __('Dashboard') }}
                            </x-dropdown-link>
                            
                            <x-dropdown-link :href="route('orders.index')" class="flex items-center gap-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                {{ __('My Orders') }}
                            </x-dropdown-link>
                            
                            <x-dropdown-link :href="route('warranties.index')" class="flex items-center gap-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                {{ __('My Warranties') }}
                            </x-dropdown-link>
                        @endif
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                            <div class="border-t border-b border-gray-700 py-1">
                                <div class="px-4 py-1 text-xs text-blue-400 uppercase font-semibold">Admin Area</div>
                                
                                <x-dropdown-link :href="route('admin.dashboard')" class="flex items-center gap-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    {{ __('Dashboard') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('admin.products.index')" class="flex items-center gap-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                                    </svg>
                                    {{ __('Products Management') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('admin.orders.index')" class="flex items-center gap-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    {{ __('Orders Management') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('admin.payments.index')" class="flex items-center gap-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ __('Payments') }}
                                </x-dropdown-link>
                                
                                <x-dropdown-link :href="route('admin.warranties.index')" class="flex items-center gap-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    {{ __('Warranties') }}
                                </x-dropdown-link>
                            </div>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="flex items-center gap-2 text-red-400 hover:bg-gray-700 hover:text-red-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Search Box has been moved up, removing this duplicate -->
            
            <!-- Mobile Auth Buttons -->
            <div class="sm:hidden hidden flex items-center space-x-2">
                @guest
                <a href="{{ route('login') }}" class="auth-button-login text-xs py-1.5">
                    <span>Login</span>
                </a>
                @endguest
            </div>
                
            <!-- Hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-300 hover:text-white hover:bg-gradient-to-r hover:from-gray-800 hover:to-gray-700 focus:outline-none transition-all duration-300 border border-gray-600/50">
                    <svg class="h-6 w-6 hamburger-line" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="hamburger-line inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hamburger-line hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden bg-gradient-to-b from-gray-900/95 to-slate-900/95 border-t border-gray-700/50 backdrop-blur-lg">
        <div class="pt-3 pb-4 space-y-2 px-3">
            <div class="mb-3 px-2">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari produk..." 
                    class="w-full px-4 py-2.5 bg-white text-black placeholder-gray-400 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 text-sm transition-all duration-200"
                    value="{{ request('search') }}"
                >
            </div>
            
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="flex items-center gap-2 px-3 py-2 rounded-md text-gray-300 hover:bg-gray-800 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ __('Home') }}
            </x-responsive-nav-link>
            
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-base font-medium rounded-md transition duration-150 ease-in-out" :class="{'text-blue-400 bg-gray-800': open, 'text-gray-300 hover:text-white hover:bg-gray-800': !open }">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                        </svg>
                        {{ __('Products') }}
                    </div>
                    <svg class="h-5 w-5 transform" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-cloak x-show="open" x-collapse class="mt-1 space-y-1 px-4 bg-gray-800 rounded-md border border-gray-700">
                    <a href="{{ route('products.index', ['type' => 'paket']) }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Paket Dapur</a>
                    <a href="{{ route('products.index', ['type' => 'jasa_pasang']) }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Jasa Pemasangan</a>
                    <a href="{{ route('products.index', ['type' => 'lepas']) }}" class="block px-3 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-700 rounded-md">Produk Lepas</a>
                </div>
            </div>

            @auth
                @if(Auth::user()->isCustomer())
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        {{ __('Orders') }}
                    </x-responsive-nav-link>
                    
                    <x-responsive-nav-link :href="route('warranties.index')" :active="request()->routeIs('warranties.*')" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        {{ __('Warranty') }}
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                    <div class="pt-2 mt-3 border-t border-gray-700">
                        <div class="px-4 py-1 text-xs uppercase tracking-wider font-semibold text-blue-400">
                            Admin Area
                        </div>
                        
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="flex items-center gap-2 text-gray-300 hover:bg-gray-800 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            {{ __('Admin Dashboard') }}
                        </x-responsive-nav-link>
                        
                        <x-responsive-nav-link :href="route('admin.products.create')" :active="request()->routeIs('admin.products.create')" class="flex items-center gap-2 text-gray-300 hover:bg-gray-800 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Add Product') }}
                        </x-responsive-nav-link>
                    </div>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center gap-2">
                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden border border-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="flex items-center gap-2 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex flex-col space-y-2">
                <a href="{{ route('login') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Login
                </a>
                <a href="{{ route('register') }}" class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Register
                </a>
            </div>
        </div>
        @endauth
    </div>
</nav>

<script>
    // Add this to handle x-collapse directive which isn't included in Alpine.js by default
    document.addEventListener('alpine:init', () => {
        Alpine.directive('collapse', (el, { modifiers, expression }, { evaluate }) => {
            const toggle = (open) => {
                if (open) {
                    el.style.height = el.scrollHeight + 'px';
                    el.style.opacity = 1;
                } else {
                    el.style.height = '0px';
                    el.style.opacity = 0;
                }
            };
            
            el._x_doHide = () => {
                toggle(false);
            };
            
            el._x_doShow = () => {
                toggle(true);
            };
            
            // Initialize
            el.style.overflow = 'hidden';
            el.style.transition = 'height 150ms ease-in-out, opacity 150ms ease-in-out';
            el.style.height = '0px';
            el.style.opacity = 0;
            
            if (evaluate(expression)) {
                el._x_doShow();
            } else {
                el._x_doHide();
            }
        });
    });
</script>
