<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <body class="font-sans antialiased bg-gray-50 text-gray-800">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-gray-150">
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
                    triggerEl: null,
                    
                    openDrawer(taskId, triggerEl) {
                        this.open = true;
                        this.taskId = taskId;
                        this.loading = true;
                        this.task = null;
                        this.triggerEl = triggerEl;
                        
                        fetch(`/tasks/${taskId}`)
                            .then(res => res.json())
                            .then(data => {
                                this.task = data.task;
                                this.loading = false;
                                
                                // Focus first focusable inside drawer for accessibility
                                setTimeout(() => {
                                    const container = document.getElementById('task-drawer-container');
                                    if (container) {
                                        const focusables = container.querySelectorAll('button, input, textarea');
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
