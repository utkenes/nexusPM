<x-app-layout>
    <x-slot name="header">
        <x-section-header 
            title="Create New Organization" 
            description="Establish a new workspace organization to manage projects and team collaboration."
        />
    </x-slot>

    <div class="py-8 bg-gray-955">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form method="POST" action="{{ route('organizations.store') }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <x-form-group label="Organization Name" :error="$errors->first('name')" required>
                        <x-input 
                            id="name" 
                            name="name" 
                            type="text" 
                            :value="old('name')" 
                            required 
                            autofocus 
                            autocomplete="name" 
                            placeholder="e.g. Acme Corporation" 
                        />
                    </x-form-group>

                    <!-- Slug -->
                    <x-form-group label="Slug (Globally Unique)" :error="$errors->first('slug')" required>
                        <x-input 
                            id="slug" 
                            name="slug" 
                            type="text" 
                            :value="old('slug')" 
                            required 
                            placeholder="e.g. acme-corp" 
                        />
                    </x-form-group>

                    <div class="flex items-center space-x-3 pt-4 border-t border-gray-850">
                        <x-button type="submit" variant="primary">Create</x-button>
                        <a href="{{ route('organizations.index') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-gray-300 transition duration-150">
                            Cancel
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>

    <!-- Automatically fill slug based on name typing -->
    <script>
        document.getElementById('name').addEventListener('input', function() {
            let name = this.value;
            let slug = name.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes
            document.getElementById('slug').value = slug;
        });
    </script>
</x-app-layout>
