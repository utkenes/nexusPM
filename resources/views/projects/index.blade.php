<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Projects') }}
            </h2>
            @if($organization)
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + New Project
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                
                @if(!$organization)
                    <div class="text-center py-12">
                        <h3 class="text-lg font-medium text-gray-900">No active workspace</h3>
                        <p class="mt-1 text-sm text-gray-500">Please switch to or create an organization workspace to manage projects.</p>
                        <div class="mt-6">
                            <a href="{{ route('organizations.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Switch Workspace
                            </a>
                        </div>
                    </div>
                @elseif($projects->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No projects in this workspace</h3>
                        <p class="mt-1 text-sm text-gray-500">Create your first project under {{ $organization->name }}.</p>
                        <div class="mt-6">
                            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Create Project
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($projects as $proj)
                            <div class="p-6 border border-gray-100 rounded-lg shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-lg font-bold text-gray-900">
                                            <a href="{{ route('projects.show', $proj) }}" class="hover:text-indigo-600">
                                                {{ $proj->title }}
                                            </a>
                                        </h4>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700 capitalize">
                                            {{ str_replace('_', ' ', $proj->status->value) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-2">{{ Str::limit($proj->description, 120) }}</p>
                                </div>
                                <div class="border-t border-gray-50 pt-4 flex justify-between items-center text-xs text-gray-400">
                                    <span>Created by: {{ $proj->creator->name }}</span>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('projects.edit', $proj) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
