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
            class="absolute inset-0 bg-gray-950/60 backdrop-blur-sm transition-opacity" 
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
                class="w-screen max-w-lg bg-gray-900 shadow-2xl border-l border-gray-800 flex flex-col"
                @keydown="trapFocus($event)"
            >
                <!-- Drawer Header -->
                <div class="px-6 py-5 border-b border-gray-800 flex justify-between items-center bg-gray-900">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Task Details</span>
                        <h2 class="text-lg font-bold text-gray-155 mt-0.5" x-text="$store.taskDrawer.task ? $store.taskDrawer.task.title : 'Loading...'"></h2>
                    </div>
                    <button @click="$store.taskDrawer.closeDrawer()" class="rounded-md text-gray-450 hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500">
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
                        <!-- Priority, Status & Watch Button -->
                        <div class="flex items-center justify-between border-b border-gray-850 pb-4">
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold uppercase tracking-wide border bg-slate-800 text-slate-300 border-slate-700"
                                      :class="{
                                          'bg-red-950/30 text-red-400 border-red-900/50': $store.taskDrawer.task && $store.taskDrawer.task.priority === 'high',
                                          'bg-amber-950/30 text-amber-400 border-amber-900/50': $store.taskDrawer.task && $store.taskDrawer.task.priority === 'medium'
                                      }"
                                      x-text="$store.taskDrawer.task ? $store.taskDrawer.task.priority : ''"></span>
                                      
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-semibold uppercase tracking-wide border bg-slate-800 text-slate-300 border-slate-700"
                                      x-text="$store.taskDrawer.task ? $store.taskDrawer.task.status.replace('_', ' ') : ''"></span>
                            </div>
                            
                            <!-- Watcher Toggle Button -->
                            <button 
                                @click="$store.taskDrawer.toggleWatch()"
                                class="inline-flex items-center px-3 py-1 border border-gray-700 rounded-lg text-xs font-semibold transition"
                                :class="$store.taskDrawer.isWatching ? 'bg-orange-600 text-white border-orange-500 hover:bg-orange-700' : 'bg-gray-800 text-gray-300 hover:bg-gray-750'"
                            >
                                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span x-text="$store.taskDrawer.isWatching ? 'Watching' : 'Watch'"></span>
                            </button>
                        </div>

                        <!-- Description -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Description</h3>
                            <p class="text-sm text-gray-300 leading-relaxed bg-gray-950/20 p-3 rounded-lg border border-gray-850" x-text="$store.taskDrawer.task && $store.taskDrawer.task.description ? $store.taskDrawer.task.description : 'No description provided.'"></p>
                        </div>

                        <!-- Assignee Reassignment & Watchers list -->
                        <div class="grid grid-cols-2 gap-4 border-t border-b border-gray-850 py-4">
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Assignee</h4>
                                <select 
                                    :value="$store.taskDrawer.task ? $store.taskDrawer.task.assigned_to : ''" 
                                    @change="$store.taskDrawer.updateAssignee($event.target.value)"
                                    class="mt-2 block w-full border-gray-800 rounded-lg text-xs bg-gray-950 text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                >
                                    <option value="">Unassigned</option>
                                    <template x-for="m in $store.taskDrawer.members" :key="m.id">
                                        <option :value="m.id" x-text="m.name" :selected="$store.taskDrawer.task && m.id === $store.taskDrawer.task.assigned_to"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Watchers</h4>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    <template x-if="$store.taskDrawer.task && (!$store.taskDrawer.task.watchers || $store.taskDrawer.task.watchers.length === 0)">
                                        <span class="text-xs text-gray-550 italic">No watchers.</span>
                                    </template>
                                    <template x-for="w in ($store.taskDrawer.task ? $store.taskDrawer.task.watchers : [])" :key="w.id">
                                        <div 
                                            class="inline-flex items-center justify-center rounded-full text-white font-semibold shadow-sm h-6 w-6 text-[10px] bg-orange-600" 
                                            :title="w.name"
                                            role="img"
                                            :aria-label="'Avatar of ' + w.name"
                                            x-text="w.name.split(' ').map(n => n[0]).join('').substring(0,2).toUpperCase()"
                                        ></div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Labels Selection Panel -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Labels</h3>
                            <div class="flex flex-wrap gap-2 py-1 bg-gray-950/20 p-3 rounded-lg border border-gray-850">
                                <template x-if="$store.taskDrawer.orgLabels && $store.taskDrawer.orgLabels.length === 0">
                                    <span class="text-xs text-gray-500 italic">No labels available in organization.</span>
                                </template>
                                <template x-for="l in $store.taskDrawer.orgLabels" :key="l.id">
                                    <label class="inline-flex items-center space-x-2 px-2 py-1 rounded-md border border-gray-800 cursor-pointer hover:bg-gray-800/40 select-none">
                                        <input 
                                            type="checkbox" 
                                            class="rounded border-gray-700 text-orange-600 focus:ring-orange-500 bg-gray-950" 
                                            :checked="$store.taskDrawer.task.labels.some(tl => tl.id === l.id)"
                                            @change="$store.taskDrawer.toggleLabel(l.id, $event.target.checked)"
                                        >
                                        <span class="text-xs font-bold" :style="'color: ' + l.color" x-text="l.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Checklist -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Checklist</h3>
                            <div class="space-y-2">
                                <template x-if="$store.taskDrawer.task && $store.taskDrawer.task.checklist_items && $store.taskDrawer.task.checklist_items.length === 0">
                                    <p class="text-sm text-gray-550 italic">No checklist items defined.</p>
                                </template>
                                <template x-for="item in ($store.taskDrawer.task ? $store.taskDrawer.task.checklist_items : [])" :key="item.id">
                                    <div class="flex items-center space-x-3 py-1">
                                        <input type="checkbox" class="rounded border-gray-850 bg-gray-950 text-orange-600 focus:ring-orange-500" :checked="item.is_completed" disabled>
                                        <span class="text-sm" :class="item.is_completed ? 'line-through text-gray-500' : 'text-gray-300'" x-text="item.title"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Comments</h3>
                            <div class="space-y-4 mb-4">
                                <template x-if="$store.taskDrawer.task && $store.taskDrawer.task.comments && $store.taskDrawer.task.comments.length === 0">
                                    <p class="text-sm text-gray-550 italic">No comments yet. Start the conversation!</p>
                                </template>
                                <template x-for="comment in ($store.taskDrawer.task ? $store.taskDrawer.task.comments : [])" :key="comment.id">
                                    <div class="bg-gray-950/40 p-3.5 rounded-xl border border-gray-850 space-y-1.5 shadow-sm">
                                        <div class="flex justify-between text-[11px] font-semibold text-gray-500">
                                            <span class="text-gray-400" x-text="comment.user.name"></span>
                                            <span x-text="new Date(comment.created_at).toLocaleString()"></span>
                                        </div>
                                        <div class="text-sm text-gray-300 leading-snug" x-html="comment.content"></div>
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
                                            'Accept': 'application/json',
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
                                <input type="text" name="content" required placeholder="Add a comment... (use @name to mention)" class="flex-grow border-gray-800 bg-gray-950 focus:border-orange-500 focus:ring-orange-500 rounded-lg text-sm shadow-sm text-gray-200 placeholder-gray-550">
                                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold text-sm rounded-lg shadow-sm">Post</button>
                            </form>
                        </div>

                        <!-- Activity Timeline -->
                        <div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-t border-gray-850 pt-4">Activity Timeline</h3>
                            <div class="flow-root">
                                <ul role="list" class="-mb-8">
                                    <template x-for="(act, index) in $store.taskDrawer.activities" :key="act.id">
                                        <li>
                                            <div class="relative pb-8">
                                                <!-- Connecting Line -->
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-850" aria-hidden="true" x-show="index < $store.taskDrawer.activities.length - 1"></span>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-gray-950 border border-gray-850 flex items-center justify-center ring-8 ring-gray-900 text-xs text-orange-500">
                                                            ⏱️
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow min-w-0 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-xs text-gray-400">
                                                                <span class="font-bold text-gray-250" x-text="act.causer ? act.causer.name : 'System'"></span>
                                                                <span x-text="act.description"></span>
                                                            </p>
                                                        </div>
                                                        <div class="text-right text-[10px] whitespace-nowrap text-gray-555">
                                                            <span x-text="new Date(act.created_at).toLocaleDateString() + ' ' + new Date(act.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
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
