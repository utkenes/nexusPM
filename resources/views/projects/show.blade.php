<x-app-layout>
    <x-slot name="header">
        <x-section-header 
            title="{{ $project->title }}" 
            description="{{ $project->description }}"
        >
            <x-slot name="actions">
                <button onclick="openCreateTaskModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    + Add Task
                </button>
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
        class="py-8"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Search, Filters, and Utilities Row -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white p-4 rounded-xl border border-gray-150 shadow-sm">
                <!-- Search Box -->
                <x-search-box placeholder="Search tasks..." />

                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-6">
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

            </div>

        </div>
    </div>

    <!-- Reusable Task details slide-over drawer -->
    <x-drawer />

    <!-- Create Task Modal -->
    <div id="create-task-modal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-150 w-full max-w-lg overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Add New Task</h3>
                <button onclick="closeCreateTaskModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('tasks.store', $project) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Task Title</label>
                    <input type="text" name="title" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg text-sm shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Description</label>
                    <textarea name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg text-sm shadow-sm" rows="3"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Priority</label>
                        <select name="priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg text-sm shadow-sm">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Assign To</label>
                        <select name="assigned_to" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg text-sm shadow-sm">
                            <option value="">Unassigned</option>
                            @foreach($organizationMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Due Date</label>
                    <input type="date" name="due_date" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg text-sm shadow-sm">
                </div>
                <div class="pt-4 border-t border-gray-100 flex justify-end space-x-2">
                    <button type="button" onclick="closeCreateTaskModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-250 text-gray-700 font-semibold text-sm rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-lg shadow-sm">Add Task</button>
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
