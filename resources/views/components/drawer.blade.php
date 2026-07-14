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
            class="absolute inset-0 bg-gray-955/80 backdrop-blur-sm transition-opacity" 
            @click="$store.taskDrawer.closeDrawer()"
        ></div>

        <!-- Sliding Panel (Wide layout for premium Linear-like feel) -->
        <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
            <div 
                x-show="$store.taskDrawer.open"
                x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="w-screen max-w-3xl bg-gray-900 shadow-2xl border-l border-gray-800 flex flex-col"
                @keydown="trapFocus($event)"
                x-data="{ activeTab: 'details' }"
            >
                <!-- Drawer Header -->
                <div class="px-8 py-6 border-b border-gray-850 flex justify-between items-center bg-gray-900/50">
                    <div class="min-w-0">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest block">Task Workspace</span>
                        <h2 class="text-xl font-black text-gray-100 mt-1 tracking-tight truncate leading-tight" x-text="$store.taskDrawer.task ? $store.taskDrawer.task.title : 'Loading...'"></h2>
                    </div>
                    <div class="flex items-center space-x-3 shrink-0">
                        <!-- Watcher Toggle Button -->
                        <button 
                            @click="$store.taskDrawer.toggleWatch()"
                            class="inline-flex items-center px-3.5 py-1.5 border border-gray-800 hover:border-gray-700 rounded-xl text-xs font-bold transition shadow-sm"
                            :class="$store.taskDrawer.isWatching ? 'bg-orange-600 text-white border-orange-500 hover:bg-orange-700' : 'bg-gray-950 text-gray-400 hover:text-gray-250'"
                        >
                            <svg class="h-3.5 w-3.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span x-text="$store.taskDrawer.isWatching ? 'Watching' : 'Watch Task'"></span>
                        </button>
                        
                        <!-- Close Button -->
                        <x-icon-button @click="$store.taskDrawer.closeDrawer()" ariaLabel="Close Task Details">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </x-icon-button>
                    </div>
                </div>

                <!-- Drawer Content -->
                <div class="flex-1 overflow-y-auto p-8">
                    <!-- Loading Skeletons -->
                    <div x-show="$store.taskDrawer.loading">
                        <x-skeleton type="drawer" />
                    </div>

                    <!-- Task Data -->
                    <div x-show="!$store.taskDrawer.loading && $store.taskDrawer.task" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start" style="display: none;">
                        
                        <!-- Left Span 2: Tabs Navigation & Panels -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Tabs Navigation Bar -->
                            <div class="border-b border-gray-850 flex space-x-6 text-xs font-bold uppercase tracking-wider text-gray-500">
                                <button @click="activeTab = 'details'" :class="activeTab === 'details' ? 'border-b-2 border-orange-500 text-orange-500 pb-3' : 'hover:text-gray-300 pb-3'">Details</button>
                                <button @click="activeTab = 'checklist'" :class="activeTab === 'checklist' ? 'border-b-2 border-orange-500 text-orange-500 pb-3' : 'hover:text-gray-300 pb-3'">Checklist</button>
                                <button @click="activeTab = 'comments'" :class="activeTab === 'comments' ? 'border-b-2 border-orange-500 text-orange-500 pb-3' : 'hover:text-gray-300 pb-3'">Comments</button>
                                <button @click="activeTab = 'activity'" :class="activeTab === 'activity' ? 'border-b-2 border-orange-500 text-orange-500 pb-3' : 'hover:text-gray-300 pb-3'">Activity</button>
                                <button @click="activeTab = 'attachments'" :class="activeTab === 'attachments' ? 'border-b-2 border-orange-500 text-orange-500 pb-3' : 'hover:text-gray-300 pb-3'">Attachments</button>
                            </div>

                            <!-- Panel: Details -->
                            <div x-show="activeTab === 'details'" class="space-y-4">
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Task Description</h3>
                                <p class="text-sm text-gray-300 leading-relaxed bg-gray-950/20 p-4 rounded-xl border border-gray-850" x-text="$store.taskDrawer.task && $store.taskDrawer.task.description ? $store.taskDrawer.task.description : 'No description provided.'"></p>
                            </div>

                            <!-- Panel: Checklist -->
                            <div x-show="activeTab === 'checklist'" class="space-y-4" style="display: none;">
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Checklist Items</h3>
                                <div class="space-y-2">
                                    <template x-if="$store.taskDrawer.task && $store.taskDrawer.task.checklist_items && $store.taskDrawer.task.checklist_items.length === 0">
                                        <p class="text-xs text-gray-550 italic">No checklist items defined.</p>
                                    </template>
                                    <template x-for="item in ($store.taskDrawer.task ? $store.taskDrawer.task.checklist_items : [])" :key="item.id">
                                        <div class="flex items-center space-x-3 py-2 px-3 bg-gray-950/25 border border-gray-850/60 rounded-xl">
                                            <input type="checkbox" class="rounded border-gray-800 bg-gray-950 text-orange-600 focus:ring-orange-500" :checked="item.is_completed" disabled>
                                            <span class="text-xs font-semibold" :class="item.is_completed ? 'line-through text-gray-500' : 'text-gray-300'" x-text="item.title"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Panel: Comments -->
                            <div x-show="activeTab === 'comments'" class="space-y-6" style="display: none;">
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Discussion Forum</h3>
                                <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                                    <template x-if="$store.taskDrawer.task && $store.taskDrawer.task.comments && $store.taskDrawer.task.comments.length === 0">
                                        <p class="text-xs text-gray-550 italic">No comments yet. Start the conversation!</p>
                                    </template>
                                    <template x-for="comment in ($store.taskDrawer.task ? $store.taskDrawer.task.comments : [])" :key="comment.id">
                                        <div class="bg-gray-950/30 p-4 rounded-xl border border-gray-850 space-y-2 shadow-sm">
                                            <div class="flex justify-between text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                <span class="text-gray-400" x-text="comment.user.name"></span>
                                                <span x-text="new Date(comment.created_at).toLocaleString()"></span>
                                            </div>
                                            <div class="text-xs text-gray-300 leading-relaxed font-medium" x-html="comment.content"></div>
                                        </div>
                                    </template>
                                </div>
                                
                                <!-- Comment Form -->
                                <form 
                                    :action="'/tasks/' + ($store.taskDrawer.task ? $store.taskDrawer.task.id : '') + '/comments'" 
                                    method="POST" 
                                    class="flex gap-2 pt-2 border-t border-gray-850"
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
                                    <input type="text" name="content" required placeholder="Add a comment... (use @name to mention)" class="flex-grow border-gray-800 bg-gray-950 focus:border-orange-500 focus:ring-orange-500 rounded-xl text-xs font-semibold text-gray-200 placeholder-gray-600">
                                    <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm">Post</button>
                                </form>
                            </div>

                            <!-- Panel: Activity -->
                            <div x-show="activeTab === 'activity'" class="space-y-4" style="display: none;">
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Timeline Logs</h3>
                                <div class="flow-root max-h-[300px] overflow-y-auto pr-1">
                                    <ul role="list" class="-mb-8">
                                        <template x-for="(act, index) in $store.taskDrawer.activities" :key="act.id">
                                            <li>
                                                <div class="relative pb-8">
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-850" aria-hidden="true" x-show="index < $store.taskDrawer.activities.length - 1"></span>
                                                    <div class="relative flex space-x-3">
                                                        <div>
                                                            <span class="h-8 w-8 rounded-full bg-gray-950 border border-gray-850 flex items-center justify-center text-xs text-orange-500">
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
                                                            <div class="text-right text-[10px] whitespace-nowrap text-gray-550">
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

                            <!-- Panel: Attachments -->
                            <div x-show="activeTab === 'attachments'" class="space-y-4" style="display: none;">
                                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Files & Assets</h3>
                                <template x-if="$store.taskDrawer.task && (!$store.taskDrawer.task.attachments || $store.taskDrawer.task.attachments.length === 0)">
                                    <p class="text-xs text-gray-550 italic">No attachments uploaded yet.</p>
                                </template>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <template x-for="file in ($store.taskDrawer.task ? $store.taskDrawer.task.attachments : [])" :key="file.id">
                                        <div class="p-3 bg-gray-950/20 border border-gray-850 rounded-xl flex items-center justify-between">
                                            <div class="flex items-center space-x-2 truncate">
                                                <span class="text-xs">📁</span>
                                                <span class="text-xs font-semibold text-gray-300 truncate" x-text="file.file_name"></span>
                                            </div>
                                            <a :href="'/attachments/' + file.id + '/download'" class="text-orange-500 hover:text-orange-400 font-bold text-[10px] uppercase tracking-wider shrink-0 ml-2">Download</a>
                                        </div>
                                    </template>
                                </div>
                            </div>

                        </div>

                        <!-- Right Panel: Meta Properties (Linear Sidebar layout) -->
                        <div class="space-y-6 lg:border-l border-gray-850 lg:pl-6">
                            <!-- Assignee Dropdown -->
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Assignee</h4>
                                <select 
                                    :value="$store.taskDrawer.task ? $store.taskDrawer.task.assigned_to : ''" 
                                    @change="$store.taskDrawer.updateAssignee($event.target.value)"
                                    class="mt-2 block w-full border-gray-800 rounded-xl text-xs bg-gray-950 text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-transparent font-semibold py-2"
                                >
                                    <option value="">Unassigned</option>
                                    <template x-for="m in $store.taskDrawer.members" :key="m.id">
                                        <option :value="m.id" x-text="m.name" :selected="$store.taskDrawer.task && m.id === $store.taskDrawer.task.assigned_to"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Watchers Profile Avatars list -->
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Watchers</h4>
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    <template x-if="$store.taskDrawer.task && (!$store.taskDrawer.task.watchers || $store.taskDrawer.task.watchers.length === 0)">
                                        <span class="text-xs text-gray-550 italic">No watchers.</span>
                                    </template>
                                    <template x-for="w in ($store.taskDrawer.task ? $store.taskDrawer.task.watchers : [])" :key="w.id">
                                        <div 
                                            class="inline-flex items-center justify-center rounded-full text-white font-bold shadow-sm h-6 w-6 text-[9px] bg-orange-600" 
                                            :title="w.name"
                                            role="img"
                                            :aria-label="'Avatar of ' + w.name"
                                            x-text="w.name.split(' ').map(n => n[0]).join('').substring(0,2).toUpperCase()"
                                        ></div>
                                    </template>
                                </div>
                            </div>

                            <!-- Labels Toggles Check list -->
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Labels</h4>
                                <div class="flex flex-col space-y-2 bg-gray-950/30 p-3.5 rounded-xl border border-gray-850">
                                    <template x-if="$store.taskDrawer.orgLabels && $store.taskDrawer.orgLabels.length === 0">
                                        <span class="text-xs text-gray-550 italic">No organization labels.</span>
                                    </template>
                                    <template x-for="l in $store.taskDrawer.orgLabels" :key="l.id">
                                        <label class="inline-flex items-center space-x-2.5 cursor-pointer hover:opacity-85 select-none py-0.5">
                                            <input 
                                                type="checkbox" 
                                                class="rounded border-gray-800 text-orange-600 focus:ring-orange-500 bg-gray-950" 
                                                :checked="$store.taskDrawer.task.labels.some(tl => tl.id === l.id)"
                                                @change="$store.taskDrawer.toggleLabel(l.id, $event.target.checked)"
                                            >
                                            <span class="text-xs font-bold" :style="'color: ' + l.color" x-text="l.name"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- Timeline Metadata details list using task-meta -->
                            <div class="border-t border-gray-850 pt-4 space-y-2.5">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-555 font-bold uppercase tracking-wider text-[9px]">Due Date</span>
                                    <span class="text-gray-300 font-bold" x-text="$store.taskDrawer.task && $store.taskDrawer.task.due_date ? new Date($store.taskDrawer.task.due_date).toLocaleDateString() : 'No due date'"></span>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-555 font-bold uppercase tracking-wider text-[9px]">Created By</span>
                                    <span class="text-gray-300 font-bold" x-text="$store.taskDrawer.task && $store.taskDrawer.task.creator ? $store.taskDrawer.task.creator.name : 'System'"></span>
                                </div>
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
