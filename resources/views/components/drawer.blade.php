<div 
    x-show="$store.taskDrawer.open"
    class="fixed inset-0 z-50 overflow-hidden"
    role="dialog" 
    aria-modal="true"
    id="task-drawer-container"
    @keydown.escape.window="$store.taskDrawer.closeDrawer()"
    style="display: none;"
>
    <div class="absolute inset-0 overflow-hidden">
        <!-- Backdrop -->
        <div 
            x-show="$store.taskDrawer.open"
            x-transition:enter="ease-in-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in-out duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-gray-500/40 backdrop-blur-sm transition-opacity" 
            @click="$store.taskDrawer.closeDrawer()"
        ></div>

        <!-- Sliding Panel -->
        <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
            <div 
                x-show="$store.taskDrawer.open"
                x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="w-screen max-w-lg bg-white shadow-2xl border-l border-gray-150 flex flex-col"
                @keydown="trapFocus($event)"
            >
                <!-- Drawer Header -->
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Task Details</span>
                        <h2 class="text-lg font-bold text-gray-900 mt-0.5" x-text="$store.taskDrawer.task ? $store.taskDrawer.task.title : 'Loading...'"></h2>
                    </div>
                    <button @click="$store.taskDrawer.closeDrawer()" class="rounded-md text-gray-450 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span class="sr-only">Close panel</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Drawer Content -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    <!-- Loading State / Skeletons -->
                    <div x-show="$store.taskDrawer.loading">
                        <x-skeleton type="drawer" />
                    </div>

                    <!-- Task Data -->
                    <div x-show="!$store.taskDrawer.loading && $store.taskDrawer.task" class="space-y-6">
                        <!-- Priority & Status Badges -->
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold uppercase tracking-wide border"
                                  :class="{
                                      'bg-red-50 text-red-700 border-red-100': $store.taskDrawer.task && $store.taskDrawer.task.priority === 'high',
                                      'bg-amber-50 text-amber-700 border-amber-100': $store.taskDrawer.task && $store.taskDrawer.task.priority === 'medium',
                                      'bg-slate-50 text-slate-700 border-slate-100': $store.taskDrawer.task && $store.taskDrawer.task.priority === 'low'
                                  }"
                                  x-text="$store.taskDrawer.task ? $store.taskDrawer.task.priority : ''"></span>
                                  
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold uppercase tracking-wide border"
                                  :class="{
                                      'bg-slate-100 text-slate-700 border-slate-200': $store.taskDrawer.task && $store.taskDrawer.task.status === 'todo',
                                      'bg-indigo-50 text-indigo-700 border-indigo-100': $store.taskDrawer.task && $store.taskDrawer.task.status === 'in_progress',
                                      'bg-amber-50 text-amber-700 border-amber-100': $store.taskDrawer.task && $store.taskDrawer.task.status === 'review',
                                      'bg-emerald-50 text-emerald-700 border-emerald-100': $store.taskDrawer.task && $store.taskDrawer.task.status === 'done'
                                  }"
                                  x-text="$store.taskDrawer.task ? $store.taskDrawer.task.status.replace('_', ' ') : ''"></span>
                        </div>

                        <!-- Description -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Description</h3>
                            <p class="text-sm text-gray-700 leading-relaxed" x-text="$store.taskDrawer.task && $store.taskDrawer.task.description ? $store.taskDrawer.task.description : 'No description provided.'"></p>
                        </div>

                        <!-- Assignee & Dates -->
                        <div class="grid grid-cols-2 gap-4 border-t border-b border-gray-100 py-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Assignee</h4>
                                <p class="text-sm font-semibold text-gray-800 mt-1" x-text="$store.taskDrawer.task && $store.taskDrawer.task.assignee ? $store.taskDrawer.task.assignee.name : 'Unassigned'"></p>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Due Date</h4>
                                <p class="text-sm font-semibold text-gray-800 mt-1" x-text="$store.taskDrawer.task && $store.taskDrawer.task.due_date ? new Date($store.taskDrawer.task.due_date).toLocaleDateString() : 'No due date'"></p>
                            </div>
                        </div>

                        <!-- Checklist -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Checklist</h3>
                            <div class="space-y-2">
                                <template x-if="$store.taskDrawer.task && $store.taskDrawer.task.checklist_items && $store.taskDrawer.task.checklist_items.length === 0">
                                    <p class="text-sm text-gray-450 italic">No checklist items defined.</p>
                                </template>
                                <template x-for="item in ($store.taskDrawer.task ? $store.taskDrawer.task.checklist_items : [])" :key="item.id">
                                    <div class="flex items-center space-x-3 py-1">
                                        <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" :checked="item.is_completed" disabled>
                                        <span class="text-sm" :class="item.is_completed ? 'line-through text-gray-400' : 'text-gray-700'" x-text="item.title"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Comments</h3>
                            <div class="space-y-4 mb-4">
                                <template x-if="$store.taskDrawer.task && $store.taskDrawer.task.comments && $store.taskDrawer.task.comments.length === 0">
                                    <p class="text-sm text-gray-450 italic">No comments yet. Start the conversation!</p>
                                </template>
                                <template x-for="comment in ($store.taskDrawer.task ? $store.taskDrawer.task.comments : [])" :key="comment.id">
                                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-1 shadow-sm">
                                        <div class="flex justify-between text-[11px] font-semibold text-gray-400">
                                            <span x-text="comment.user.name"></span>
                                            <span x-text="new Date(comment.created_at).toLocaleString()"></span>
                                        </div>
                                        <p class="text-sm text-gray-700 leading-snug" x-text="comment.content"></p>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Comment form -->
                            <form 
                                :action="'/tasks/' + ($store.taskDrawer.task ? $store.taskDrawer.task.id : '') + '/comments'" 
                                method="POST" 
                                class="flex gap-2"
                                @submit.prevent="
                                    const form = $el;
                                    const input = form.querySelector('input');
                                    fetch(form.action, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                                        },
                                        body: JSON.stringify({ content: input.value })
                                    })
                                    .then(res => {
                                        if (res.ok) {
                                            input.value = '';
                                            $store.taskDrawer.openDrawer($store.taskDrawer.taskId, $store.taskDrawer.triggerEl);
                                            window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'success', message: 'Comment added!' }}));
                                        } else {
                                            window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Error adding comment.' }}));
                                        }
                                    });
                                "
                            >
                                <input type="text" name="content" required placeholder="Add a comment..." class="flex-grow border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg text-sm shadow-sm">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-lg shadow-sm">Post</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function trapFocus(e) {
        if (e.key !== 'Tab') return;
        const container = document.getElementById('task-drawer-container');
        const focusables = container.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusables.length === 0) return;
        const first = focusables[0];
        const last = focusables[focusables.length - 1];
        if (e.shiftKey) {
            if (document.activeElement === first) {
                last.focus();
                e.preventDefault();
            }
        } else {
            if (document.activeElement === last) {
                first.focus();
                e.preventDefault();
            }
        }
    }
</script>
