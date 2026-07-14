<div 
    x-data="{ 
        toasts: [],
        add(type, message) {
            const id = Date.now();
            this.toasts.push({ id, type, message });
            setTimeout(() => { this.remove(id) }, 3500);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    @toast.window="add($event.detail.type, $event.detail.message)"
    class="fixed bottom-5 right-5 z-[100] flex flex-col space-y-3 max-w-sm w-full"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div 
            x-transition:enter="transition ease-out duration-300 transform translate-y-2 opacity-0"
            x-transition:enter-start="translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-200 opacity-0"
            class="flex items-center p-4 rounded-xl shadow-xl border text-white"
            :class="{
                'bg-emerald-600 border-emerald-500': toast.type === 'success',
                'bg-amber-500 border-amber-400': toast.type === 'warning',
                'bg-red-600 border-red-500': toast.type === 'error'
            }"
            role="alert"
        >
            <!-- Icon -->
            <div class="mr-3">
                <template x-if="toast.type === 'success'">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <template x-if="toast.type === 'warning'">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
            </div>
            
            <!-- Message -->
            <div class="flex-grow text-sm font-semibold" x-text="toast.message"></div>
            
            <!-- Close Button -->
            <button @click="remove(toast.id)" class="ml-4 hover:opacity-80">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>
