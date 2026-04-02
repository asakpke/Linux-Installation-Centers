<x-layouts.app.admin :title="__('Admin Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">{{ __('Admin Dashboard') }}</flux:heading>
        <p class="text-zinc-600 dark:text-zinc-400">{{ __('Manage users and configure the system.') }}</p>
        <div class="grid auto-rows-min gap-4 md:grid-cols-2 lg:grid-cols-3">
            <flux:card class="p-4">
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3" wire:navigate>
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        <flux:icon name="users" class="size-6" />
                    </span>
                    <div>
                        <span class="font-medium">{{ __('Users') }}</span>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Manage all users') }}</p>
                    </div>
                </a>
            </flux:card>
            <flux:card class="p-4">
                <a href="{{ route('admin.requests.index') }}" class="flex items-center gap-3" wire:navigate>
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400">
                        <flux:icon name="document-text" class="size-6" />
                    </span>
                    <div>
                        <span class="font-medium">{{ __('Install requests') }}</span>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('View and moderate requests') }}</p>
                    </div>
                </a>
            </flux:card>
        </div>
    </div>
</x-layouts.app.admin>
