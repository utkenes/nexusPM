<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ $project->title }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ $project->description }}</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="openCreateTaskModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Add Task
                </button>
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Project Settings
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Success Alert -->
            <div id="toast" class="hidden fixed bottom-5 right-5 z-50 bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3 transition-opacity duration-300">
                <span id="toast-message">Task updated!</span>
            </div>

            <!-- Kanban Columns Container -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-start">
                
                <!-- Column: To Do -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex flex-col min-h-[500px]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">To Do</h3>
                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-0.5 rounded-full font-semibold">
                            {{ $tasks->where('status', \App\Enums\TaskStatus::Todo)->count() }}
                        </span>
                    </div>
                    <div id="col-todo" class="space-y-4 flex-grow" ondragover="allowDrop(event)" ondrop="drop(event, 'todo')">
                        @foreach($tasks->where('status', \App\Enums\TaskStatus::Todo) as $task)
                            <x-task-card :task="$task" />
                        @endforeach
                    </div>
                </div>

                <!-- Column: In Progress -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex flex-col min-h-[500px]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">In Progress</h3>
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-semibold">
                            {{ $tasks->where('status', \App\Enums\TaskStatus::InProgress)->count() }}
                        </span>
                    </div>
                    <div id="col-in_progress" class="space-y-4 flex-grow" ondragover="allowDrop(event)" ondrop="drop(event, 'in_progress')">
                        @foreach($tasks->where('status', \App\Enums\TaskStatus::InProgress) as $task)
                            <x-task-card :task="$task" />
                        @endforeach
                    </div>
                </div>

                <!-- Column: Review -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex flex-col min-h-[500px]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">Review</h3>
                        <span class="bg-amber-100 text-amber-700 text-xs px-2 py-0.5 rounded-full font-semibold">
                            {{ $tasks->where('status', \App\Enums\TaskStatus::Review)->count() }}
                        </span>
                    </div>
                    <div id="col-review" class="space-y-4 flex-grow" ondragover="allowDrop(event)" ondrop="drop(event, 'review')">
                        @foreach($tasks->where('status', \App\Enums\TaskStatus::Review) as $task)
                            <x-task-card :task="$task" />
                        @endforeach
                    </div>
                </div>

                <!-- Column: Done -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 flex flex-col min-h-[500px]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">Done</h3>
                        <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-0.5 rounded-full font-semibold">
                            {{ $tasks->where('status', \App\Enums\TaskStatus::Done)->count() }}
                        </span>
                    </div>
                    <div id="col-done" class="space-y-4 flex-grow" ondragover="allowDrop(event)" ondrop="drop(event, 'done')">
                        @foreach($tasks->where('status', \App\Enums\TaskStatus::Done) as $task)
                            <x-task-card :task="$task" />
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Create Task Modal -->
    <div id="create-task-modal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-150 w-full max-w-lg overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Add New Task</h3>
                <button onclick="closeCreateTaskModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('tasks.store', $project) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Task Title</label>
                    <input type="text" name="title" required class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Description</label>
                    <textarea name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Priority</label>
                        <select name="priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Assign To</label>
                        <select name="assigned_to" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Unassigned</option>
                            @foreach($organizationMembers as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Due Date</label>
                    <input type="date" name="due_date" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                </div>
                <div class="pt-4 border-t border-gray-100 flex justify-end space-x-2">
                    <button type="button" onclick="closeCreateTaskModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-250 text-gray-700 font-semibold rounded-md">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md">Add Task</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Task Detail Sidebar/Modal (AJAX Injected) -->
    <div id="task-detail-modal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl border border-gray-150 w-full max-w-2xl overflow-hidden flex flex-col max-h-[85vh]">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <span id="detail-priority" class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 capitalize mb-1">High</span>
                    <h3 id="detail-title" class="text-xl font-bold text-gray-900">Task Title</h3>
                </div>
                <button onclick="closeTaskDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-6 space-y-6 overflow-y-auto flex-grow">
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Description</h4>
                    <p id="detail-description" class="text-gray-700 text-sm">No description provided.</p>
                </div>

                <!-- Meta Row -->
                <div class="grid grid-cols-2 gap-4 border-t border-b border-gray-100 py-4">
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Assignee</h4>
                        <p id="detail-assignee" class="text-sm font-medium text-gray-800 mt-1">Unassigned</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Due Date</h4>
                        <p id="detail-due-date" class="text-sm font-medium text-gray-800 mt-1">No due date</p>
                    </div>
                </div>

                <!-- Checklist Items -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Checklist</h4>
                    <div id="detail-checklist" class="space-y-2">
                        <!-- Items injected here -->
                    </div>
                </div>

                <!-- Comments Section -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Comments</h4>
                    <div id="detail-comments" class="space-y-4 mb-4">
                        <!-- Comments injected here -->
                    </div>
                    <!-- Add Comment Form -->
                    <form id="comment-form" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" id="comment-content" name="content" required placeholder="Add a comment..." class="flex-grow border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md">Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanban & AJAX Scripts -->
    <script>
        function drag(ev) {
            ev.dataTransfer.setData("text/plain", ev.target.id);
            ev.target.classList.add('opacity-50');
        }

        document.addEventListener("dragend", function(event) {
            if (event.target.classList.contains('opacity-50')) {
                event.target.classList.remove('opacity-50');
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
                        showToast(`Status updated to "${status.replace('_', ' ')}"!`);
                    } else {
                        showToast('Error updating status.', 'bg-red-600');
                    }
                })
                .catch(err => {
                    showToast('Connection error.', 'bg-red-600');
                });
            }
        }

        function showToast(message, bgColorClass = 'bg-indigo-600') {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');
            toast.className = `fixed bottom-5 right-5 z-50 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3 transition-opacity duration-300 ${bgColorClass}`;
            toastMsg.innerText = message;
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Create Task Modal Controls
        function openCreateTaskModal() {
            document.getElementById('create-task-modal').classList.remove('hidden');
        }

        function closeCreateTaskModal() {
            document.getElementById('create-task-modal').classList.add('hidden');
        }

        // Task Detail Fetching & Display
        function openTaskDetailModal(taskId) {
            fetch(`/tasks/${taskId}`)
                .then(response => response.json())
                .then(data => {
                    const task = data.task;
                    
                    document.getElementById('detail-title').innerText = task.title;
                    document.getElementById('detail-description').innerText = task.description || 'No description provided.';
                    document.getElementById('detail-priority').innerText = task.priority;
                    document.getElementById('detail-assignee').innerText = task.assignee ? task.assignee.name : 'Unassigned';
                    document.getElementById('detail-due-date').innerText = task.due_date ? new Date(task.due_date).toLocaleDateString() : 'No due date';

                    // Priority styling
                    const priTag = document.getElementById('detail-priority');
                    if (task.priority === 'high') {
                        priTag.className = 'inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 capitalize mb-1';
                    } else if (task.priority === 'medium') {
                        priTag.className = 'inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800 capitalize mb-1';
                    } else {
                        priTag.className = 'inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 capitalize mb-1';
                    }

                    // Comments Form Setup
                    document.getElementById('comment-form').action = `/tasks/${task.id}/comments`;

                    // Checklist Rendering
                    const checklistBox = document.getElementById('detail-checklist');
                    checklistBox.innerHTML = '';
                    if (task.checklist_items.length === 0) {
                        checklistBox.innerHTML = '<p class="text-sm text-gray-500">No checklist items defined.</p>';
                    } else {
                        task.checklist_items.forEach(item => {
                            checklistBox.innerHTML += `
                                <div class="flex items-center space-x-3 py-1">
                                    <input type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" ${item.is_completed ? 'checked' : ''} disabled>
                                    <span class="text-sm ${item.is_completed ? 'line-through text-gray-400' : 'text-gray-700'}">${item.title}</span>
                                </div>
                            `;
                        });
                    }

                    // Comments Rendering
                    const commentsBox = document.getElementById('detail-comments');
                    commentsBox.innerHTML = '';
                    if (task.comments.length === 0) {
                        commentsBox.innerHTML = '<p class="text-sm text-gray-500">No comments yet. Start the conversation!</p>';
                    } else {
                        task.comments.forEach(comment => {
                            commentsBox.innerHTML += `
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 space-y-1">
                                    <div class="flex justify-between text-xs text-gray-500">
                                        <span class="font-semibold">${comment.user.name}</span>
                                        <span>${new Date(comment.created_at).toLocaleString()}</span>
                                    </div>
                                    <p class="text-sm text-gray-800">${comment.content}</p>
                                </div>
                            `;
                        });
                    }

                    document.getElementById('task-detail-modal').classList.remove('hidden');
                });
        }

        function closeTaskDetailModal() {
            document.getElementById('task-detail-modal').classList.add('hidden');
        }
    </script>
</x-app-layout>
