<aside id="sidebar" class="w-64 bg-gray-800 text-gray-200 flex-shrink-0 hidden md:block">
    <div class="p-6">
        <a href="{{ route('admin.dashboard') }}" class="text-white text-2xl font-semibold">Tempat-In</a>
        <p class="text-sm text-gray-400">Admin Panel</p>
    </div>
    <nav class="mt-6">
        {{-- Helper untuk link aktif --}}
        @php
            $activeClass = 'bg-indigo-600 border-l-4 border-indigo-400';
            $linkClass = 'flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition-colors duration-200';
        @endphp

        <a href="{{ route('admin.dashboard') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.dashboard') ? $activeClass : '' }}">
            <i class="fas fa-tachometer-alt w-6"></i>
            <span class="mx-4">Dashboard</span>
        </a>
        
        @if(Auth::user()->restaurant)
            <a href="{{ route('admin.restaurant.edit', Auth::user()->restaurant->id) }}" class="{{ $linkClass }} {{ request()->routeIs('admin.restaurant.*') ? $activeClass : '' }}">
                <i class="fas fa-store w-6"></i>
                <span class="mx-4">Profil Restoran</span>
            </a>
        @endif
        
        <a href="{{ route('admin.menu-items.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.menu-items.*') ? $activeClass : '' }}">
            <i class="fas fa-utensils w-6"></i>
            <span class="mx-4">Manajemen Menu</span>

            <a href="{{ route('admin.packages.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.packages.*') ? $activeClass : '' }}">
        <i class="fas fa-box-open w-6"></i>
        <span class="mx-4">Kelola Menu Bundling</span>
    </a>

        <a href="{{ route('admin.tables.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.tables.*') ? $activeClass : '' }}">
        <i class="fas fa-chair w-6"></i>
        <span class="mx-4">Manajemen Meja</span>
    </a>


        </a>
        <a href="{{ route('admin.reservations.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.reservations.*') ? $activeClass : '' }}">
            <i class="fas fa-calendar-check w-6"></i>
            <span class="mx-4">Kelola Reservasi</span>
        </a>
        </nav>

    <div class="absolute bottom-0 w-full">
         <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="{{ $linkClass }} w-full">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span class="mx-4">Logout</span>
            </a>
        </form>
    </div>
</aside>