<x-app-layout>
    <x-slot name="header">
        <x-section-header 
            title="Profile Settings" 
            description="Manage your account profile details, credentials, and configurations."
        />
    </x-slot>

    <div class="py-8 bg-gray-955">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Left: Profile Meta Summary Info -->
                <div class="space-y-6">
                    <x-card>
                        <div class="flex flex-col items-center text-center p-4 space-y-4">
                            <!-- Premium Avatar -->
                            <div class="h-20 w-20 bg-orange-600/10 text-orange-500 border-2 border-orange-500/20 rounded-full flex items-center justify-center font-black text-3xl shadow-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            
                            <div class="space-y-1">
                                <h3 class="text-base font-black text-gray-100 tracking-tight leading-snug">{{ $user->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-850 p-6 space-y-3.5">
                            <x-task-meta label="Current Org" :value="$user->currentOrganization ? $user->currentOrganization->name : 'None'" />
                            <x-task-meta label="My Role" :value="$user->currentOrganization ? ($user->organizations()->where('organization_id', $user->current_organization_id)->first()?->pivot?->role ?? 'Member') : 'N/A'" />
                            <x-task-meta label="Joined At" :value="$user->created_at->format('M d, Y')" />
                        </div>
                    </x-card>

                    <!-- Placeholder/Empty Settings sections -->
                    <x-card title="Account Security">
                        <div class="space-y-3 text-xs text-gray-500">
                            <div class="flex justify-between py-2 border-b border-gray-850">
                                <span>Two-Factor Auth</span>
                                <span class="text-[10px] font-bold text-gray-600 uppercase">Disabled</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-850">
                                <span>API Tokens</span>
                                <span class="text-[10px] font-bold text-gray-600 uppercase">None</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span>Notification Preferences</span>
                                <span class="text-[10px] font-bold text-orange-500/80 uppercase hover:underline cursor-pointer">Configure</span>
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Right: Settings Cards Forms (Span 2) -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Profile Information Card -->
                    <x-card title="Profile Information">
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <x-form-group label="Full Name" :error="$errors->first('name')" required>
                                <x-input id="name" name="name" type="text" :value="old('name', $user->name)" required autocomplete="name" />
                            </x-form-group>

                            <x-form-group label="Email Address" :error="$errors->first('email')" required>
                                <x-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="email" />
                            </x-form-group>

                            <div class="flex items-center space-x-3 pt-4 border-t border-gray-850">
                                <x-button type="submit" variant="primary">Save Changes</x-button>

                                @if (session('status') === 'profile-updated')
                                    <span 
                                        x-data="{ show: true }" 
                                        x-show="show" 
                                        x-init="setTimeout(() => show = false, 2000)" 
                                        class="text-xs text-emerald-500 font-bold"
                                    >
                                        ✓ Saved successfully
                                    </span>
                                @endif
                            </div>
                        </form>
                    </x-card>

                    <!-- Update Password Card -->
                    <x-card title="Update Password">
                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <x-form-group label="Current Password" :error="$errors->updatePassword->first('current_password')" required>
                                <x-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
                            </x-form-group>

                            <x-form-group label="New Password" :error="$errors->updatePassword->first('password')" required>
                                <x-input id="update_password_password" name="password" type="password" autocomplete="new-password" />
                            </x-form-group>

                            <x-form-group label="Confirm New Password" :error="$errors->updatePassword->first('password_confirmation')" required>
                                <x-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
                            </x-form-group>

                            <div class="flex items-center space-x-3 pt-4 border-t border-gray-850">
                                <x-button type="submit" variant="primary">Update Password</x-button>

                                @if (session('status') === 'password-updated')
                                    <span 
                                        x-data="{ show: true }" 
                                        x-show="show" 
                                        x-init="setTimeout(() => show = false, 2000)" 
                                        class="text-xs text-emerald-500 font-bold"
                                    >
                                        ✓ Password updated
                                    </span>
                                @endif
                            </div>
                        </form>
                    </x-card>

                    <!-- Danger Zone Card -->
                    <x-card title="Danger Zone">
                        <div class="flex justify-between items-center p-1">
                            <div class="space-y-1">
                                <span class="text-xs font-black text-gray-200 uppercase tracking-wider block">Delete Account</span>
                                <span class="text-xs text-gray-400 block">Permanently remove your account and all associated workspace data.</span>
                            </div>
                            
                            <!-- Trigger delete modal directly using action or placeholder -->
                            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action is irreversible.');">
                                @csrf
                                @method('DELETE')
                                <div class="w-full max-w-xs space-y-3">
                                    <x-input type="password" name="password" placeholder="Confirm password to delete..." required class="text-xs" />
                                    <x-button type="submit" variant="danger" class="w-full">
                                        Delete Account
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    </x-card>
                    
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
