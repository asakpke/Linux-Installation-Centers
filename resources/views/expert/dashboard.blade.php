<x-layouts.app :title="__('Expert Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div>
            <flux:heading size="xl">{{ __('Expert dashboard') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Browse install requests in your city and send offers.') }}</flux:subheading>
        </div>

        <livewire:reputation-summary />

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <flux:card class="p-6">
                <flux:heading size="lg">{{ __('My assignments') }}</flux:heading>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Accepted offers and completed installs with seeker contact details.') }}
                </p>
                <flux:button class="mt-4" :href="route('expert.assignments')" variant="primary" wire:navigate>
                    {{ __('View assignments') }}
                </flux:button>
            </flux:card>
            <flux:card class="p-6">
                <flux:heading size="lg">{{ __('Open requests') }}</flux:heading>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Matched to the city and country on your expert profile.') }}
                </p>
                <flux:button class="mt-4" :href="route('expert.requests.index')" variant="filled" wire:navigate>
                    {{ __('Browse requests') }}
                </flux:button>
            </flux:card>
            <flux:card class="p-6 md:col-span-2 lg:col-span-1">
                <flux:heading size="lg">{{ __('Your profile') }}</flux:heading>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Bio, city, and country are required before you can submit offers.') }}
                </p>
                <flux:button class="mt-4" :href="route('expert.profile')" variant="ghost" wire:navigate>
                    {{ __('Edit profile') }}
                </flux:button>
            </flux:card>
        </div>
    </div>
</x-layouts.app>
