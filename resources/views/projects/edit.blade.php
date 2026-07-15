<x-app-layout>
    <x-slot name="header">
        <x-section-header 
            title="Edit Project" 
            description="Manage settings and dates for project: {{ $project->title }}"
        />
    </x-slot>

    <div class="py-8 bg-gray-955">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Title -->
                    <x-form-group label="Project Title" :error="$errors->first('title')" required>
                        <x-input 
                            id="title" 
                            name="title" 
                            type="text" 
                            :value="old('title', $project->title)" 
                            required 
                            autofocus 
                        />
                    </x-form-group>

                    <!-- Description -->
                    <x-form-group label="Description" :error="$errors->first('description')">
                        <x-textarea 
                            id="description" 
                            name="description" 
                            rows="4"
                        >{{ old('description', $project->description) }}</x-textarea>
                    </x-form-group>

                    <!-- Status -->
                    <x-form-group label="Status" :error="$errors->first('status')" required>
                        <x-select id="status" name="status">
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" @selected(old('status', $project->status->value) === $status->value)>
                                    {{ str_replace('_', ' ', $status->name) }}
                                </option>
                            @endforeach
                        </x-select>
                    </x-form-group>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4">
                        <x-form-group label="Start Date" :error="$errors->first('start_date')">
                            <x-input 
                                id="start_date" 
                                name="start_date" 
                                type="date" 
                                :value="old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '')" 
                            />
                        </x-form-group>
                        
                        <x-form-group label="Due Date" :error="$errors->first('due_date')">
                            <x-input 
                                id="due_date" 
                                name="due_date" 
                                type="date" 
                                :value="old('due_date', $project->due_date ? $project->due_date->format('Y-m-d') : '')" 
                            />
                        </x-form-group>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-gray-850">
                        <div class="flex items-center space-x-3">
                            <x-button type="submit" variant="primary">Save Changes</x-button>
                            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-gray-300 transition duration-150">
                                Cancel
                            </a>
                        </div>
                </form>

                        <!-- Delete button -->
                        <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Are you sure you want to delete this project?');">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger">Delete</x-button>
                        </form>
                    </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
