<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <div>
            <flux:heading size="xl">{{ __('Welcome') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Find a local expert to help install Linux on your computer.') }}</flux:subheading>
        </div>

        <livewire:reputation-summary />

        <div class="grid gap-4 md:grid-cols-2">
            <flux:card class="p-6">
                <flux:heading size="lg">{{ __('Install requests') }}</flux:heading>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Post your city and what you need. Experts in your area can send offers.') }}
                </p>
                <flux:button class="mt-4" :href="route('requests.index')" variant="primary" wire:navigate>
                    {{ __('My requests') }}
                </flux:button>
            </flux:card>
            <flux:card class="p-6">
                <flux:heading size="lg">{{ __('New request') }}</flux:heading>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                    {{ __('Describe your hardware and goals so experts can prepare.') }}
                </p>
                <flux:button class="mt-4" :href="route('requests.create')" variant="filled" wire:navigate>
                    {{ __('Create request') }}
                </flux:button>
            </flux:card>
        </div>
    </div>
</x-layouts.app>
