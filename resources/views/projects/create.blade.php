<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Create Project') }} under {{ $organization->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                
                <form method="POST" action="{{ route('projects.store') }}" class="space-y-6">
                    @csrf

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Project Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus placeholder="e.g. Mobile Application v2" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <!-- Slug -->
                    <div>
                        <x-input-label for="slug" :value="__('Slug (Unique in Organization)')" />
                        <x-text-input id="slug" name="slug" type="text" class="mt-1 block w-full" :value="old('slug')" required placeholder="e.g. mobile-app-v2" />
                        <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" rows="4" placeholder="Brief details about the project...">{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date')" />
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>
                        <div>
                            <x-input-label for="due_date" :value="__('Due Date')" />
                            <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date')" />
                            <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Create Project') }}</x-primary-button>
                        <a href="{{ route('projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>

            </div>
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
