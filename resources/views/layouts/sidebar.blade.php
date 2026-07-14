<!-- Desktop Sidebar -->
<aside class="hidden md:flex flex-col w-64 bg-gray-900 border-r border-gray-800 text-gray-400 shrink-0 h-screen sticky top-0">
    <!-- Header/Logo -->
    <div class="px-6 py-5 border-b border-gray-850 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5">
            <div class="h-9 w-9 bg-orange-600 rounded-xl flex items-center justify-center text-white font-black tracking-tighter shadow-lg shadow-orange-500/20">
                N
            </div>
            <span class="font-black text-lg tracking-tight text-gray-150">NexusPM</span>
        </a>
    </div>

    <!-- Active Workspace / Switcher -->
    @php
        $currOrg = Auth::user()->currentOrganization;
    @endphp
    <div class="px-4 py-4 border-b border-gray-850">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full p-2.5 bg-gray-950/40 hover:bg-gray-850 border border-gray-800 rounded-xl transition text-left">
                <div class="flex items-center space-x-2.5 truncate">
                    <div class="h-6 w-6 bg-orange-600/10 text-orange-500 border border-orange-500/20 rounded-lg flex items-center justify-center font-bold text-xs shrink-0">
                        {{ $currOrg ? strtoupper(substr($currOrg->name, 0, 1)) : 'W' }}
                    </div>
                    <span class="text-xs font-bold text-gray-250 truncate">{{ $currOrg ? $currOrg->name : 'No active workspace' }}</span>
                </div>
                <svg class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <!-- Switcher Dropdown -->
            <div 
                x-show="open" 
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute left-0 right-0 mt-2 z-50 rounded-xl bg-gray-900 border border-gray-800 shadow-xl overflow-hidden py-1.5"
                style="display: none;"
            >
                <div class="px-3 py-1.5 text-[9px] font-bold text-gray-550 uppercase tracking-widest border-b border-gray-850 mb-1">Switch Workspace</div>
                @foreach(Auth::user()->organizations as $org)
                    <form method="POST" action="{{ route('organizations.switch', $org) }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2 w-full px-3 py-2 text-xs font-semibold text-gray-400 hover:text-gray-200 hover:bg-gray-800 text-left">
                            <span class="h-2 w-2 rounded-full {{ Auth::user()->current_organization_id === $org->id ? 'bg-orange-500' : 'bg-transparent' }}"></span>
                            <span class="truncate">{{ $org->name }}</span>
                        </button>
                    </form>
                @endforeach
                <div class="border-t border-gray-850 mt-1.5 pt-1">
                    <a href="{{ route('organizations.index') }}" class="flex items-center space-x-2 w-full px-3 py-2 text-xs font-bold text-orange-500 hover:text-orange-400 hover:bg-gray-800">
                        <span>⚙️</span>
                        <span>Manage Workspaces</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors {{ request()->routeIs('dashboard') ? 'bg-orange-600/10 text-orange-500 border border-orange-500/20' : 'hover:bg-gray-850 hover:text-gray-200 text-gray-450' }}">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('organizations.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors {{ request()->routeIs('organizations.*') ? 'bg-orange-600/10 text-orange-500 border border-orange-500/20' : 'hover:bg-gray-850 hover:text-gray-200 text-gray-450' }}">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span>Organizations</span>
        </a>

        <a href="{{ route('projects.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors {{ request()->routeIs('projects.*') ? 'bg-orange-600/10 text-orange-500 border border-orange-500/20' : 'hover:bg-gray-850 hover:text-gray-200 text-gray-450' }}">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>
            <span>Projects</span>
        </a>

        <!-- Calendar Placeholder -->
        <a href="#" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors hover:bg-gray-850 hover:text-gray-200 text-gray-455">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Calendar</span>
        </a>

        <!-- Settings Placeholder -->
        <a href="#" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors hover:bg-gray-850 hover:text-gray-200 text-gray-455">
            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Settings</span>
        </a>
    </nav>

    <!-- User Profile & Log Out bottom section -->
    <div class="p-4 border-t border-gray-850 bg-gray-950/45">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2.5 min-w-0">
                <x-avatar :name="Auth::user()->name" size="sm" />
                <div class="truncate text-xs font-semibold">
                    <span class="text-gray-200 block truncate leading-snug">{{ Auth::user()->name }}</span>
                    <span class="text-gray-500 block truncate leading-none mt-0.5">{{ Auth::user()->email }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-1.5 rounded-lg hover:bg-gray-800 text-gray-500 hover:text-red-400 transition-colors" title="Log Out">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Mobile Drawer Sidebar overlay -->
<div 
    x-show="sidebarOpen" 
    class="fixed inset-0 z-40 md:hidden flex" 
    role="dialog" 
    aria-modal="true"
    style="display: none;"
>
    <!-- Backdrop overlay -->
    <div 
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm"
        @click="sidebarOpen = false"
    ></div>

    <!-- Mobile Drawer Content -->
    <div 
        x-show="sidebarOpen"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-900 border-r border-gray-800"
    >
        <div class="px-6 py-5 border-b border-gray-850 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5">
                <div class="h-9 w-9 bg-orange-600 rounded-xl flex items-center justify-center text-white font-black tracking-tighter shadow-lg shadow-orange-500/20">
                    N
                </div>
                <span class="font-black text-lg tracking-tight text-gray-150">NexusPM</span>
            </a>
            <button @click="sidebarOpen = false" class="p-1 rounded-md text-gray-450 hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors {{ request()->routeIs('dashboard') ? 'bg-orange-600/10 text-orange-500 border border-orange-500/20' : 'hover:bg-gray-850 hover:text-gray-200 text-gray-455' }}">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('organizations.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors {{ request()->routeIs('organizations.*') ? 'bg-orange-600/10 text-orange-500 border border-orange-500/20' : 'hover:bg-gray-850 hover:text-gray-200 text-gray-455' }}">
                <span>Organizations</span>
            </a>
            <a href="{{ route('projects.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-colors {{ request()->routeIs('projects.*') ? 'bg-orange-600/10 text-orange-500 border border-orange-500/20' : 'hover:bg-gray-850 hover:text-gray-200 text-gray-455' }}">
                <span>Projects</span>
            </a>
        </nav>
    </div>
</div>
