<nav class="bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-2 group">
                    <div class="bg-white text-primary-600 p-2 rounded-lg group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold">Smart Case</h1>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="/" class="px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200 font-medium">
                    Home
                </a>
                
                @can('admin')
                <a href="{{ route('kategori_gangguan.index') }}" class="px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200 font-medium">
                    Kategori Gangguan
                </a>
                <a href="{{ route('kategori_pelanggan.index') }}" class="px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200 font-medium">
                    Kategori Pelanggan
                </a>
                <a href="{{ route('admin.tiket.index') }}" class="px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200 font-medium">
                    Manajemen Tiket
                </a>
                @endcan

                @can('user')
                <a href="{{ route('tiket.index') }}" class="px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200 font-medium">
                    Tiket Saya
                </a>
                @endcan
            </div>

            <!-- Auth Section Desktop -->
            <div class="hidden md:flex items-center space-x-3">
                @guest
                <a href="{{ route('login.index') }}" class="px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200 font-medium">
                    Login
                </a>
                <a href="{{ route('register.index') }}" class="px-4 py-2 bg-white text-primary-600 rounded-lg hover:bg-gray-100 transition-colors duration-200 font-medium shadow-md">
                    Register
                </a>
                @endguest

                @auth
                <div class="relative" x-data="{ open: false }">
                    <!-- User Dropdown Button -->
                    <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200">
                        <div class="w-8 h-8 bg-white text-primary-600 rounded-full flex items-center justify-center font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50"
                         style="display: none;">
                        
                        <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profile</span>
                            </div>
                        </a>

                        <hr class="my-1">

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-danger-600 hover:bg-danger-50 transition-colors duration-200">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Logout</span>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg hover:bg-white/10 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="md:hidden bg-primary-700 border-t border-primary-600"
         style="display: none;">
        
        <div class="px-4 py-3 space-y-1">
            <a href="/" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200">
                Home
            </a>
            
            @can('admin')
            <a href="{{ route('kategori_gangguan.index') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200">
                Kategori Gangguan
            </a>
            <a href="{{ route('kategori_pelanggan.index') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200">
                Kategori Pelanggan
            </a>
            <a href="{{ route('admin.tiket.index') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200">
                Manajemen Tiket
            </a>
            @endcan

            @can('user')
            <a href="{{ route('tiket.index') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200">
                Tiket Saya
            </a>
            @endcan

            @guest
            <hr class="my-2 border-primary-600">
            <a href="{{ route('login.index') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200">
                Login
            </a>
            <a href="{{ route('register.index') }}" class="block px-4 py-2 bg-white text-primary-600 rounded-lg hover:bg-gray-100 transition-colors duration-200 font-medium">
                Register
            </a>
            @endguest

            @auth
            <hr class="my-2 border-primary-600">
            <div class="px-4 py-2 text-sm text-white/70">
                Welcome, {{ Auth::user()->name }}
            </div>
            <a href="{{ route('profile.index') }}" class="block px-4 py-2 rounded-lg hover:bg-white/10 transition-colors duration-200">
                Profile
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 rounded-lg text-danger-200 hover:bg-danger-600/20 transition-colors duration-200">
                    Logout
                </button>
            </form>
            @endauth
        </div>
    </div>
</nav>