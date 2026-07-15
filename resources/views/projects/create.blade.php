<x-app-layout>
    <x-slot name="header">
        <x-section-header 
            title="Create Project" 
            description="Start a new project under workspace: {{ $organization->name }}"
        />
    </x-slot>

    <div class="py-8 bg-gray-955">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form method="POST" action="{{ route('projects.store') }}" class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <x-form-group label="Project Title" :error="$errors->first('title')" required>
                        <x-input 
                            id="title" 
                            name="title" 
                            type="text" 
                            :value="old('title')" 
                            required 
                            autofocus 
                            placeholder="e.g. Mobile Application v2" 
                        />
                    </x-form-group>

                    <!-- Slug -->
                    <x-form-group label="Slug (Unique in Organization)" :error="$errors->first('slug')" required>
                        <x-input 
                            id="slug" 
                            name="slug" 
                            type="text" 
                            :value="old('slug')" 
                            required 
                            placeholder="e.g. mobile-app-v2" 
                        />
                    </x-form-group>

                    <!-- Description -->
                    <x-form-group label="Description" :error="$errors->first('description')">
                        <x-textarea 
                            id="description" 
                            name="description" 
                            rows="4" 
                            placeholder="Brief details about the project..."
                        >{{ old('description') }}</x-textarea>
                    </x-form-group>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4">
                        <x-form-group label="Start Date" :error="$errors->first('start_date')">
                            <x-input 
                                id="start_date" 
                                name="start_date" 
                                type="date" 
                                :value="old('start_date')" 
                            />
                        </x-form-group>
                        
                        <x-form-group label="Due Date" :error="$errors->first('due_date')">
                            <x-input 
                                id="due_date" 
                                name="due_date" 
                                type="date" 
                                :value="old('due_date')" 
                            />
                        </x-form-group>
                    </div>

                    <div class="flex items-center space-x-3 pt-4 border-t border-gray-850">
                        <x-button type="submit" variant="primary">Create Project</x-button>
                        <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-gray-300 transition duration-150">
                            Cancel
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>

    <!-- Automatically fill slug based on title typing -->
    <script>
        document.getElementById('title').addEventListener('input', function() {
            let title = this.value;
            let slug = title.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes
            document.getElementById('slug').value = slug;
        });
    </script>
</x-app-layout>
