<x-app-layout>
    <x-slot name="header">
        <x-section-header 
            title="{{ $project->title }}" 
            description="{{ $project->description }}"
        >
            <x-slot name="actions">
                <button onclick="openCreateTaskModal()" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 active:bg-orange-950 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    + Add Task
                </button>
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-850 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Settings
                </a>
            </x-slot>
        </x-section-header>
    </x-slot>

    <!-- Main Board Wrapper with Alpine Filter/Search state -->
    <div 
        x-data="{
            search: '',
            priority: '',
            assignee: '',
            bulkMode: false,
            selectedTasks: [],
            toggleSelect(taskId) {
                if (this.selectedTasks.includes(taskId)) {
                    this.selectedTasks = this.selectedTasks.filter(id => id !== taskId);
                } else {
                    this.selectedTasks.push(taskId);
                }
            },
            bulkAction(actionType, value) {
                if (!value && value !== 'unassigned') return;
                
                const payload = {
                    task_ids: this.selectedTasks
                };

                if (actionType === 'status') {
                    payload.status = value;
                } else if (actionType === 'assigned_to') {
                    payload.assigned_to = value === 'unassigned' ? null : value;
                } else if (actionType === 'delete') {
                    if (!confirm('Are you sure you want to delete these tasks?')) return;
                    payload.delete = true;
                }

                fetch('/tasks/bulk', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.dispatchEvent(new CustomEvent('toast', { 
                            detail: { type: 'success', message: 'Tasks updated successfully in bulk.' }
                        }));
                        this.selectedTasks = [];
                        this.bulkMode = false;
                        setTimeout(() => location.reload(), 800);
                    } else {
                        window.dispatchEvent(new CustomEvent('toast', { 
                            detail: { type: 'error', message: 'Error performing bulk update.' }
                        }));
                    }
                });
            },
            matchesFilters(el) {
                const title = el.getAttribute('data-title') || '';
                const desc = el.getAttribute('data-description') || '';
                const cardPriority = el.getAttribute('data-priority') || '';
                const cardAssignee = el.getAttribute('data-assignee') || '';

                const searchMatch = !this.search || title.includes(this.search.toLowerCase()) || desc.includes(this.search.toLowerCase());
                const priorityMatch = !this.priority || cardPriority === this.priority;
                const assigneeMatch = !this.assignee || cardAssignee === this.assignee;

                return searchMatch && priorityMatch && assigneeMatch;
            }
        }"
        class="py-8 bg-gray-950"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Search, Filters, and Utilities Row -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-gray-900 p-4 rounded-xl border border-gray-800 shadow-sm">
                <!-- Search Box -->
                <x-search-box placeholder="Search tasks..." />

                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-6">
                    <!-- Bulk Mode Button -->
                    <button 
                        id="bulk-mode-btn"
                        @click="bulkMode = !bulkMode; if(!bulkMode) { selectedTasks = []; }"
                        class="inline-flex items-center px-3.5 py-1.5 border rounded-xl text-xs font-bold transition shadow-sm cursor-pointer"
                        :class="bulkMode ? 'bg-orange-600 text-white border-orange-500 hover:bg-orange-700' : 'bg-gray-950 border-gray-800 text-gray-400 hover:text-gray-250'"
                    >
                        Bulk Mode
                    </button>

                    <!-- Priority Filter -->
                    <x-filter-dropdown 
                        label="Priority" 
                        model="priority"
                        :options="['high' => 'High', 'medium' => 'Medium', 'low' => 'Low']"
                    />

                    <!-- Assignee Filter -->
                    @php
                        $assigneeOptions = $organizationMembers->pluck('name', 'id')->toArray();
                    @endphp
                    <x-filter-dropdown 
                        label="Assignee" 
                        model="assignee"
                        :options="$assigneeOptions"
                    />
                </div>
            </div>

            <!-- Kanban Columns Container: Grid on desktop, horizontal scroll on mobile -->
            <div class="flex space-x-6 overflow-x-auto pb-6 flex-nowrap scrollbar-thin snap-x snap-mandatory lg:grid lg:grid-cols-4 lg:gap-6 lg:space-x-0 lg:overflow-x-visible">
                
                <!-- Column: To Do -->
                <x-kanban-column status="todo" title="To Do" :tasks="$tasks" />

                <!-- Column: In Progress -->
                <x-kanban-column status="in_progress" title="In Progress" :tasks="$tasks" />

                <!-- Column: Review -->
                <x-kanban-column status="review" title="Review" :tasks="$tasks" />

                <!-- Column: Done -->
                <x-kanban-column status="done" title="Done" :tasks="$tasks" />

            <!-- Floating Bulk Actions Panel -->
            <div 
                x-show="bulkMode && selectedTasks.length > 0"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-40 bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl px-6 py-4 flex items-center justify-between space-x-6 min-w-[550px]"
                style="display: none;"
            >
                <div class="flex items-center space-x-2">
                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-gray-300">
                        <span x-text="selectedTasks.length" class="text-orange-500"></span> tasks selected
                    </span>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Bulk Change Status -->
                    <select 
                        @change="bulkAction('status', $event.target.value); $event.target.value = '';"
                        class="bg-gray-950 border border-gray-800 text-gray-400 text-xs rounded-xl font-bold py-1.5 px-3 focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="">Change Status...</option>
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="done">Done</option>
                    </select>

                    <!-- Bulk Change Assignee -->
                    <select 
                        @change="bulkAction('assigned_to', $event.target.value); $event.target.value = '';"
                        class="bg-gray-950 border border-gray-800 text-gray-400 text-xs rounded-xl font-bold py-1.5 px-3 focus:ring-orange-500 focus:border-orange-500"
                    >
                        <option value="">Change Assignee...</option>
                        <option value="unassigned">Unassigned</option>
                        @foreach($organizationMembers as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>

                    <!-- Bulk Delete -->
                    <button 
                        @click="bulkAction('delete', true)"
                        class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-1.5 px-4 rounded-xl shadow-sm transition"
                    >
                        Delete
                    </button>

                    <!-- Cancel -->
                    <button 
                        @click="selectedTasks = []; bulkMode = false;"
                        class="text-gray-500 hover:text-gray-400 text-xs font-bold py-1.5 px-2"
                    >
                        Cancel
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Reusable Task details slide-over drawer -->
    <x-drawer />

    <!-- Create Task Modal -->
    <div id="create-task-modal" class="hidden fixed inset-0 bg-gray-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-gray-900 rounded-2xl shadow-2xl border border-gray-800 w-full max-w-lg overflow-hidden">
            <div class="p-6 border-b border-gray-850 flex justify-between items-center bg-gray-900/50">
                <h3 class="text-sm font-black text-gray-200 uppercase tracking-widest">Add New Task</h3>
                <button onclick="closeCreateTaskModal()" class="text-gray-400 hover:text-gray-200 focus:outline-none">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('tasks.store', $project) }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <x-form-group label="Task Title" required>
                    <x-input type="text" name="title" required placeholder="Enter task title..." />
                </x-form-group>

                <x-form-group label="Description">
                    <x-textarea name="description" rows="3" placeholder="Briefly describe the task..."></x-textarea>
                </x-form-group>

                <div class="grid grid-cols-2 gap-4">
                    <x-form-group label="Priority" required>
                        <x-select name="priority">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </x-select>
                    </x-form-group>

                    <x-form-group label="Assign To">
                        <x-select name="assigned_to">
                            <option value="">Unassigned</option>
                            @foreach($organizationMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </x-select>
                    </x-form-group>
                </div>

                <x-form-group label="Due Date">
                    <x-input type="date" name="due_date" />
                </x-form-group>

                <div class="pt-6 border-t border-gray-850 flex justify-end space-x-3">
                    <x-button type="button" variant="secondary" onclick="closeCreateTaskModal()">Cancel</x-button>
                    <x-button type="submit" variant="primary">Add Task</x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Drag & Drop Scripts -->
    <script>
        function drag(ev) {
            ev.dataTransfer.setData("text/plain", ev.target.id);
            ev.target.classList.add('opacity-40');
        }

        document.addEventListener("dragend", function(event) {
            if (event.target.classList.contains('opacity-40')) {
                event.target.classList.remove('opacity-40');
            }
        });

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drop(ev, status) {
            ev.preventDefault();
            const data = ev.dataTransfer.getData("text/plain");
            const card = document.getElementById(data);
            const targetCol = document.getElementById('col-' + status);

            if (card && targetCol) {
                targetCol.appendChild(card);
                const taskId = card.getAttribute('data-task-id');
                
                // Update status in local dataset to match filters
                card.setAttribute('data-status', status);

                // Update Status via AJAX
                fetch(`/tasks/${taskId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        window.dispatchEvent(new CustomEvent('toast', { 
                            detail: { type: 'success', message: 'Task status updated to "' + status.replace('_', ' ') + '"!' }
                        }));
                    } else {
                        window.dispatchEvent(new CustomEvent('toast', { 
                            detail: { type: 'error', message: 'Error updating task status.' }
                        }));
                    }
                })
                .catch(err => {
                    window.dispatchEvent(new CustomEvent('toast', { 
                        detail: { type: 'error', message: 'Network error updating task.' }
                    }));
                });
            }
        }

        // Create Task Modal Controls
        function openCreateTaskModal() {
            document.getElementById('create-task-modal').classList.remove('hidden');
        }

        function closeCreateTaskModal() {
            document.getElementById('create-task-modal').classList.add('hidden');
        }
    </script>
</x-app-layout>
