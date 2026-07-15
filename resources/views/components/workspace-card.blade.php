@props(['org', 'active' => false])

<div class="p-6 bg-gray-900 border border-gray-800 rounded-2xl shadow-lg hover:shadow-orange-500/[0.03] hover:border-orange-500/30 transition duration-200 flex flex-col justify-between space-y-4">
    <div class="space-y-3">
        <!-- Header -->
        <div class="flex justify-between items-start">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-orange-600/10 text-orange-500 border border-orange-500/20 rounded-xl flex items-center justify-center font-black text-lg">
                    {{ strtoupper(substr($org->name, 0, 1)) }}
                </div>
                <div>
                    <h4 class="text-base font-bold text-gray-100 tracking-tight leading-snug">{{ $org->name }}</h4>
                    <p class="text-xs text-gray-500">Slug: {{ $org->slug }}</p>
                </div>
            </div>
            <x-badge :value="$org->pivot->role" type="priority" />
        </div>

        <!-- Meta list -->
        <div class="grid grid-cols-2 gap-4 py-2 border-t border-b border-gray-850 text-xs text-gray-400">
            <div>
                <span class="text-gray-500 block uppercase tracking-wider text-[9px] font-bold">Projects</span>
                <span class="font-bold text-gray-200">{{ $org->projects()->count() }} active</span>
            </div>
            <div>
                <span class="text-gray-500 block uppercase tracking-wider text-[9px] font-bold">Created</span>
                <span class="font-semibold text-gray-300">{{ $org->created_at->format('M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between pt-2">
        <span class="text-[10px] text-gray-500">Workspace Member</span>
        
        @if($active)
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-950/30 text-emerald-400 border border-emerald-900/50">
                Active
            </span>
        @else
            <form method="POST" action="{{ route('organizations.switch', $org) }}">
                @csrf
                <x-button type="submit" variant="secondary" class="py-1.5 px-3">
                    Switch
                </x-button>
            </form>
        @endif
    </div>
</div>
