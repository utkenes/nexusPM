<x-app-layout>
    <x-slot name="header">
        <x-section-header title="Organizations" description="Manage your organization workspaces.">
            <x-slot name="actions">
                <a href="{{ route('organizations.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    + New Organization
                </a>
            </x-slot>
        </x-section-header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-150 p-6">
                
                @if($organizations->isEmpty())
                    <x-empty-state 
                        title="No organizations workspace found" 
                        message="Please create an organization workspace to start managing projects and tasks." 
                        actionUrl="{{ route('organizations.create') }}" 
                        actionText="Create Organization"
                        icon="folder"
                    />
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($organizations as $org)
                            <div class="p-6 border border-gray-150 rounded-xl shadow-sm hover:shadow-md transition-shadow flex justify-between items-center bg-white">
                                <div class="space-y-1">
                                    <h4 class="text-lg font-bold text-gray-900 leading-snug">{{ $org->name }}</h4>
                                    <p class="text-xs text-gray-400">Slug: {{ $org->slug }}</p>
                                    <div class="pt-2">
                                        <x-badge :value="$org->pivot->role" type="priority" />
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if(Auth::user()->current_organization_id === $org->id)
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Active
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('organizations.switch', $org) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                                Switch
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
