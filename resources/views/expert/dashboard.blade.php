<x-layouts.app :title="__('Expert Dashboard')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in as an expert!") }}

                    <div class="mt-4">
                        <flux:button :href="route('expert.profile')" wire:navigate>
                            {{ __('Edit Profile') }}
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
