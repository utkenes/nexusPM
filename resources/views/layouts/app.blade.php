<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NexusPM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-950 text-gray-100">
        <div class="min-h-screen flex flex-col bg-gray-950">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gray-900 border-b border-gray-800/80">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>
        </div>

        <!-- Reusable global Toast Notifications container -->
        <x-toast />

        <!-- Initialize global Alpine stores -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('taskDrawer', {
                    open: false,
                    taskId: null,
                    loading: false,
                    task: null,
                    orgLabels: [],
                    members: [],
                    activities: [],
                    isWatching: false,
                    triggerEl: null,
                    
                    openDrawer(taskId, triggerEl) {
                        this.open = true;
                        this.taskId = taskId;
                        this.loading = true;
                        this.task = null;
                        this.orgLabels = [];
                        this.members = [];
                        this.activities = [];
                        this.triggerEl = triggerEl;
                        
                        fetch(`/tasks/${taskId}`)
                            .then(res => res.json())
                            .then(data => {
                                this.task = data.task;
                                this.orgLabels = data.org_labels;
                                this.members = data.members;
                                this.activities = data.activities;
                                this.isWatching = data.is_watching;
                                this.loading = false;
                                
                                // Focus first focusable inside drawer for accessibility
                                setTimeout(() => {
                                    const container = document.getElementById('task-drawer-container');
                                    if (container) {
                                        const focusables = container.querySelectorAll('button, input, textarea, select');
                                        if (focusables.length > 1) {
                                            focusables[1].focus(); // focus first content element, not the close button
                                        }
                                    }
                                }, 100);
                            })
                            .catch(() => {
                                this.loading = false;
                                this.open = false;
                                window.dispatchEvent(new CustomEvent('toast', { 
                                    detail: { type: 'error', message: 'Failed to load task details.' }
                                }));
                            });
                    },
                    
                    closeDrawer() {
                        this.open = false;
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
                                this.task.watchers = data.watchers;
                                window.dispatchEvent(new CustomEvent('toast', { 
                                    detail: { type: 'success', message: this.isWatching ? 'Started watching task' : 'Stopped watching task' }
                                }));
                            }
                        });
                    },

                    updateAssignee(userId) {
                        fetch(`/tasks/${this.taskId}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ assigned_to: userId })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                window.dispatchEvent(new CustomEvent('toast', { 
                                    detail: { type: 'success', message: 'Task assignee updated' }
                                }));
                                // Refresh current task card display
                                const card = document.getElementById(`task-${this.taskId}`);
                                if (card) {
                                    card.setAttribute('data-assignee', userId || '');
                                }
                                // Re-load task detail to update UI
                                this.openDrawer(this.taskId, this.triggerEl);
                            }
                        });
                    },

                    toggleLabel(labelId, isChecked) {
                        const currentLabelIds = this.task.labels.map(l => l.id);
                        let nextLabelIds = [];
                        if (isChecked) {
                            nextLabelIds = [...currentLabelIds, labelId];
                        } else {
                            nextLabelIds = currentLabelIds.filter(id => id !== labelId);
                        }

                        fetch(`/tasks/${this.taskId}/labels`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ labels: nextLabelIds })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.task.labels = data.labels;
                                window.dispatchEvent(new CustomEvent('toast', { 
                                    detail: { type: 'success', message: 'Task labels updated' }
                                }));
                                // Also trigger reload on Kanban column to reflect label changes instantly
                                const card = document.getElementById(`task-${this.taskId}`);
                                if (card) {
                                    // Refresh labels container in card dynamically
                                    fetch(`/tasks/${this.taskId}`)
                                        .then(r => r.json())
                                        .then(d => {
                                            // Re-render task card elements or just update page
                                            window.location.reload(); // Simple sync
                                        });
                                }
                            }
                        });
                    }
                });
            });
        </script>

        <!-- Dispatch session flash alerts as custom toast events -->
        @if(session('success'))
            <script>
                window.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: "{{ session('success') }}" }}));
                });
            </script>
        @endif
        @if(session('warning'))
            <script>
                window.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'warning', message: "{{ session('warning') }}" }}));
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                window.addEventListener('DOMContentLoaded', () => {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: "{{ session('error') }}" }}));
                });
            </script>
        @endif
    </body>
</html>
