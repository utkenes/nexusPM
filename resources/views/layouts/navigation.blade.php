<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-orange-500 font-black text-xl tracking-wider">
                        Nexus<span class="text-white">PM</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                        {{ __('Projects') }}
                    </x-nav-link>
                    <x-nav-link :href="route('organizations.index')" :active="request()->routeIs('organizations.*')">
                        {{ __('Organizations') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings & Notification Dropdown & Workspace Switcher -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                
                <!-- Notification Center Dropdown -->
                <div 
                    x-data="{
                        open: false,
                        notifications: [],
                        unreadCount: 0,
                        
                        init() {
                            this.fetchCount();
                            // Fetch unread count periodically or on custom event
                            window.addEventListener('comment-added', () => this.fetchCount());
                            window.addEventListener('toast', () => this.fetchCount());
                        },
                        
                        fetchCount() {
                            fetch('/notifications')
                                .then(res => res.json())
                                .then(data => {
                                    this.unreadCount = data.unread_count;
                                    this.notifications = data.notifications;
                                });
                        },
                        
                        toggleDropdown() {
                            this.open = !this.open;
                            if (this.open) {
                                this.fetchCount();
                            }
                        },
                        
                        markAsRead(notification) {
                            fetch(`/notifications/${notification.id}/read`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.fetchCount();
                                this.open = false;
                                // Open task drawer for the notification's task
                                if (notification.data.task_id) {
                                    $store.taskDrawer.openDrawer(notification.data.task_id, null);
                                }
                            });
                        },
                        
                        markAllRead() {
                            fetch('/notifications/read-all', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').getAttribute('content')
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.fetchCount();
                                window.dispatchEvent(new CustomEvent('toast', { 
                                    detail: { type: 'success', message: 'All notifications marked as read' }
                                }));
                            });
                        }
                    }"
                    class="relative"
                >
                    <button 
                        @click="toggleDropdown()" 
                        class="relative p-1.5 rounded-full text-gray-400 hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500"
                        aria-label="Notifications"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <template x-if="unreadCount > 0">
                            <span 
                                class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-orange-600 rounded-full"
                                x-text="unreadCount"
                            ></span>
                        </template>
                    </button>

                    <!-- Dropdown Panel -->
                    <div 
                        x-show="open" 
                        @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-80 bg-gray-900 rounded-xl shadow-2xl border border-gray-800 py-2 z-50 overflow-hidden"
                        style="display: none;"
                    >
                        <div class="px-4 py-2 border-b border-gray-800 flex justify-between items-center bg-gray-900/60">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Notifications</span>
                            <button 
                                x-show="unreadCount > 0" 
                                @click="markAllRead()" 
                                class="text-xs font-semibold text-orange-500 hover:text-orange-400"
                            >
                                Mark all as read
                            </button>
                        </div>
                        <div class="max-h-60 overflow-y-auto divide-y divide-gray-800">
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-6 text-center text-sm text-gray-500">
                                    No notifications found.
                                </div>
                            </template>
                            <template x-for="item in notifications" :key="item.id">
                                <div 
                                    @click="markAsRead(item)"
                                    class="px-4 py-3 hover:bg-gray-800/50 cursor-pointer flex flex-col space-y-1 transition-colors"
                                    :class="item.read_at === null ? 'bg-gray-800/20' : 'opacity-60'"
                                >
                                    <p class="text-xs text-gray-200" x-text="item.data.message"></p>
                                    <span class="text-[10px] text-gray-500" x-text="new Date(item.created_at).toLocaleString()"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Workspace Switcher -->
                @if(Auth::user()->currentOrganization)
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-semibold rounded-lg text-orange-500 bg-orange-950/10 hover:bg-orange-950/20 focus:outline-none transition ease-in-out duration-150 border-orange-500/20 border">
                                <div>Workspace: {{ Auth::user()->currentOrganization->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-500 uppercase tracking-widest bg-gray-900 border-b border-gray-800">
                                {{ __('Switch Workspace') }}
                            </div>
                            @foreach(Auth::user()->organizations as $org)
                                @if($org->id !== Auth::user()->current_organization_id)
                                    <form method="POST" action="{{ route('organizations.switch', $org) }}">
                                        @csrf
                                        <x-dropdown-link :href="route('organizations.switch', $org)"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ $org->name }}
                                        </x-dropdown-link>
                                    </form>
                                @endif
                            @endforeach
                            <div class="border-t border-gray-800"></div>
                            <x-dropdown-link :href="route('organizations.create')">
                                {{ __('+ Create Organization') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('organizations.create') }}" class="text-sm text-orange-500 hover:text-orange-400 font-semibold">
                        {{ __('+ Create Organization') }}
                    </a>
                @endif

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-300 bg-gray-900 hover:text-gray-150 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-gray-300 hover:bg-gray-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-900 border-b border-gray-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                {{ __('Projects') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('organizations.index')" :active="request()->routeIs('organizations.*')">
                {{ __('Organizations') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-850">
            <div class="px-4">
                <div class="font-semibold text-base text-gray-250">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
