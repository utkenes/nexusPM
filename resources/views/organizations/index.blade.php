<x-app-layout>
    <!-- Page Header -->
    <x-page-header title="Organizations" description="Manage your organization workspaces.">
        <x-slot name="actions">
            <a href="{{ route('organizations.create') }}" class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-955 text-white font-semibold text-xs uppercase tracking-widest rounded-xl transition shadow-lg shadow-orange-500/20">
                + New Organization
            </a>
        </x-slot>
    </x-page-header>

    <div class="space-y-8">
        <!-- Top statistics metrics row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <x-metric-card title="Total Workspaces" value="{{ $organizations->count() }}" />
            <x-metric-card title="Active Workspace" value="{{ Auth::user()->currentOrganization ? Auth::user()->currentOrganization->name : 'None' }}" />
            <x-metric-card title="My Role" value="{{ Auth::user()->currentOrganization ? Auth::user()->organizations()->where('organization_id', Auth::user()->current_organization_id)->first()?->pivot?->role ?? 'Member' : 'N/A' }}" />
        </div>

        <!-- Organizations Grid -->
        <x-panel title="My Workspace Workspaces" :padding="false">
            <div class="p-6">
                @if($organizations->isEmpty())
                    <x-empty-state 
                        title="No organizations workspace found" 
                        message="Please create an organization workspace to start managing projects and tasks." 
                        actionUrl="{{ route('organizations.create') }}" 
                        actionText="Create Organization"
                        icon="folder"
                    />
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($organizations as $org)
                            <x-workspace-card 
                                :org="$org" 
                                :active="Auth::user()->current_organization_id === $org->id" 
                            />
                        @endforeach
                    </div>
                @endif
            </div>
        </x-panel>
    </div>
</x-app-layout>
