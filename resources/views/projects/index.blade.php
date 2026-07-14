<x-app-layout>
    <x-slot name="header">
        <x-section-header title="Projects" description="Manage projects inside your active workspace.">
            <x-slot name="actions">
                @if($organization)
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        + New Project
                    </a>
                @endif
            </x-slot>
        </x-section-header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-150 p-6">
                
                @if(!$organization)
                    <x-empty-state 
                        title="No active workspace" 
                        message="Please switch to or create an organization workspace to manage projects." 
                        actionUrl="{{ route('organizations.index') }}" 
                        actionText="Switch Workspace"
                        icon="folder"
                    />
                @elseif($projects->isEmpty())
                    <x-empty-state 
                        title="No projects in this workspace" 
                        message="Create your first project under {{ $organization->name }} workspace." 
                        actionUrl="{{ route('projects.create') }}" 
                        actionText="Create Project"
                        icon="folder"
                    />
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($projects as $proj)
                            <div class="p-6 border border-gray-150 rounded-xl shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-4 bg-white">
                                <div class="space-y-2">
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-base font-bold text-gray-900 leading-snug">
                                            <a href="{{ route('projects.show', $proj) }}" class="hover:text-indigo-600">
                                                {{ $proj->title }}
                                            </a>
                                        </h4>
                                        <x-badge :value="$proj->status->value" type="status" />
                                    </div>
                                    <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $proj->description }}</p>
                                </div>
                                <div class="border-t border-gray-100 pt-4 flex justify-between items-center text-[11px] text-gray-400">
                                    <span class="flex items-center space-x-1.5">
                                        <x-avatar :name="$proj->creator->name" size="xs" />
                                        <span>{{ $proj->creator->name }}</span>
                                    </span>
                                    <a href="{{ route('projects.edit', $proj) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Edit</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
