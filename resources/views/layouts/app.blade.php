<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NexusPM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
            /* Custom dark scrollbar styling */
            ::-webkit-scrollbar {
                width: 5px;
                height: 5px;
            }
            ::-webkit-scrollbar-track {
                background: #090d16;
            }
            ::-webkit-scrollbar-thumb {
                background: #1f2937;
                border-radius: 9999px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #ea580c;
            }
        </style>
    </head>
    <body class="bg-gray-950 text-gray-100 antialiased min-h-screen caret-orange-500 selection:bg-orange-500/30">
        <!-- Main Layout Wrapper with Mobile Sidebar state -->
        <div x-data="{ sidebarOpen: false }" class="flex min-h-screen">

            <!-- Sidebar Injected Here -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-grow flex flex-col min-w-0 min-h-screen">
                <!-- Top Navbar Bar (Hamburger menu on mobile, Notifications drop, Switcher, Profile) -->
                <header class="h-16 bg-gray-900 border-b border-gray-800 flex items-center justify-between px-6 shrink-0 sticky top-0 z-30">
                    <div class="flex items-center space-x-3">
                        <!-- Mobile Hamburger Button -->
                        <button 
                            @click="sidebarOpen = true"
                            class="md:hidden p-2 rounded-lg text-gray-400 hover:text-gray-250 hover:bg-gray-850 border border-gray-800"
                            aria-label="Open sidebar"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        <!-- Page Title Slot (Optional fallback) -->
                        <div class="hidden sm:block text-xs font-bold text-gray-500 uppercase tracking-widest">
                            NexusPM Workspace
                        </div>
                    </div>

                    <!-- Right Top Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Notification center dropdown container -->
                        @include('layouts.navigation')
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-grow p-6 sm:p-8">
                    @if (isset($header))
                        <div class="mb-6">
                            {{ $header }}
                        </div>
                    @endif
                    
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Global Toast Alert Portal -->
        <x-toast />

        <!-- Flash Toast trigger scripts -->
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: @json(session('success')) }}));
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: @json(session('error')) }}));
                });
            </script>
        @endif

        <!-- Dynamic Drawer Store Declarations -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('taskDrawer', {
                    open: false,
                    loading: false,
                    taskId: null,
                    task: null,
                    orgLabels: [],
                    members: [],
                    activities: [],
                    isWatching: false,
                    triggerEl: null,

                    openDrawer(id, triggerEl) {
                        this.open = true;
                        this.loading = true;
                        this.taskId = id;
                        this.triggerEl = triggerEl;
                        
                        fetch(`/tasks/${id}`)
                            .then(res => res.json())
                            .then(data => {
                                this.task = data.task;
                                this.orgLabels = data.org_labels || [];
                                this.members = data.members || [];
                                this.activities = data.activities || [];
                                this.isWatching = data.is_watching || false;
                                this.loading = false;
                                
                                // Trap focus automatically
                                this.$nextTick(() => {
                                    const container = document.getElementById('task-drawer-container');
                                    if (container) {
                                        const focusable = container.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                                        if (focusable) focusable.focus();
                                    }
                                });
                            });
                    },

                    closeDrawer() {
                        this.open = false;
                        this.task = null;
                        if (this.triggerEl) {
                            this.triggerEl.focus();
                        }
                    },

                    toggleWatch() {
                        fetch(`/tasks/${this.taskId}/watch`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.isWatching = data.is_watching;
                                if (data.is_watching) {
                                    // add user to watchers list
                                    this.task.watchers.push(data.user);
                                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: 'You are now watching this task' }}));
                                } else {
                                    // remove user from watchers list
                                    this.task.watchers = this.task.watchers.filter(w => w.id !== data.user.id);
                                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: 'Stopped watching task' }}));
                                }
                            }
                        });
                    },

                    updateAssignee(memberId) {
                        fetch(`/tasks/${this.taskId}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ assigned_to: memberId })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.task.assigned_to = memberId;
                                // update the card's visual dataset so filters update
                                if (this.triggerEl) {
                                    this.triggerEl.setAttribute('data-assignee', memberId || '');
                                    // refresh avatar dynamically if assignee updated
                                    location.reload();
                                }
                                window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: 'Assignee updated successfully!' }}));
                            }
                        });
                    },

                    toggleLabel(labelId, isChecked) {
                        let currentLabels = this.task.labels.map(l => l.id);
                        if (isChecked) {
                            if (!currentLabels.includes(labelId)) currentLabels.push(labelId);
                        } else {
                            currentLabels = currentLabels.filter(id => id !== labelId);
                        }

                        fetch(`/tasks/${this.taskId}/labels`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ labels: currentLabels })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // refresh label objects in task drawer state
                                this.task.labels = this.orgLabels.filter(ol => currentLabels.includes(ol.id));
                                window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: 'Task labels updated!' }}));
                                // reload after short timeout to update Kanban card visually
                                setTimeout(() => location.reload(), 800);
                            }
                        });
                    }
                });
            });
        </script>

        <!-- Keyboard Shortcuts Help Modal -->
        <div 
            x-data="{ open: false }" 
            x-show="open"
            @toggle-shortcuts-modal.window="open = !open"
            @keydown.escape.window="open = false"
            class="fixed inset-0 bg-gray-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            style="display: none;"
        >
            <div @click.outside="open = false" class="bg-gray-900 rounded-2xl shadow-2xl border border-gray-800 w-full max-w-md overflow-hidden transform transition-all p-6 space-y-6">
                <div class="flex justify-between items-center border-b border-gray-850 pb-4">
                    <h3 class="text-sm font-black text-gray-200 uppercase tracking-widest flex items-center">
                        <span class="mr-2">⌨️</span> Keyboard Shortcuts
                    </h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-200 focus:outline-none">
                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-3.5">
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-gray-450">Create Task (Kanban)</span>
                        <kbd class="px-2 py-1 bg-gray-950 border border-gray-850 rounded-lg text-orange-500 font-bold">C / N</kbd>
                    </div>
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-gray-450">Focus Search Box</span>
                        <kbd class="px-2 py-1 bg-gray-950 border border-gray-850 rounded-lg text-orange-500 font-bold">/</kbd>
                    </div>
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-gray-450">Toggle Bulk Selection Mode</span>
                        <kbd class="px-2 py-1 bg-gray-950 border border-gray-850 rounded-lg text-orange-500 font-bold">B</kbd>
                    </div>
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-gray-450">Navigate to Dashboard</span>
                        <kbd class="px-2 py-1 bg-gray-950 border border-gray-850 rounded-lg text-orange-500 font-bold">T</kbd>
                    </div>
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-gray-450">Navigate to Projects</span>
                        <kbd class="px-2 py-1 bg-gray-950 border border-gray-850 rounded-lg text-orange-500 font-bold">P</kbd>
                    </div>
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-gray-450">Close Drawer or Modal</span>
                        <kbd class="px-2 py-1 bg-gray-950 border border-gray-850 rounded-lg text-orange-500 font-bold">Esc</kbd>
                    </div>
                    <div class="flex justify-between items-center text-xs font-semibold">
                        <span class="text-gray-450">Show Shortcuts Help</span>
                        <kbd class="px-2 py-1 bg-gray-950 border border-gray-850 rounded-lg text-orange-500 font-bold">?</kbd>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('keydown', function(e) {
                // If user is typing inside input, textarea or select, ignore shortcuts
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName) || document.activeElement.isContentEditable) {
                    // Allow escape key to unfocus/close even if focused
                    if (e.key === 'Escape') {
                        document.activeElement.blur();
                    }
                    return;
                }

                switch(e.key.toLowerCase()) {
                    case '?':
                        window.dispatchEvent(new CustomEvent('toggle-shortcuts-modal'));
                        e.preventDefault();
                        break;
                    case 'c':
                    case 'n':
                        if (typeof openCreateTaskModal === 'function') {
                            openCreateTaskModal();
                            e.preventDefault();
                        }
                        break;
                    case '/':
                        const searchInput = document.querySelector('input[type="search"]');
                        if (searchInput) {
                            searchInput.focus();
                            e.preventDefault();
                        }
                        break;
                    case 'b':
                        const bulkBtn = document.getElementById('bulk-mode-btn');
                        if (bulkBtn) {
                            bulkBtn.click();
                            e.preventDefault();
                        }
                        break;
                    case 't':
                        window.location.href = "{{ route('dashboard') }}";
                        break;
                    case 'p':
                        window.location.href = "{{ route('projects.index') }}";
                        break;
                }
            });
        </script>
    </body>
</html>
