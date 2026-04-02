<?php

use App\Models\InstallRequest;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('components.layouts.app.admin')] class extends Component {
    use WithPagination;

    #[Computed]
    public function rows()
    {
        return InstallRequest::query()
            ->with(['user', 'acceptedOffer.expert'])
            ->latest()
            ->paginate(15);
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:heading size="xl">{{ __('Install requests') }}</flux:heading>
    <p class="text-zinc-600 dark:text-zinc-400">{{ __('All requests on the platform.') }}</p>

    <div class="mt-2 space-y-2">
        @forelse ($this->rows as $installRequest)
            <flux:card class="p-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <flux:link :href="route('admin.requests.show', $installRequest)" class="font-medium" wire:navigate>
                            {{ $installRequest->title }}
                        </flux:link>
                        <p class="text-sm text-zinc-500">
                            {{ $installRequest->user->email }} · {{ $installRequest->city }}, {{ $installRequest->country }}
                            · {{ $installRequest->status->value }}
                        </p>
                    </div>
                </div>
            </flux:card>
        @empty
            <p class="text-zinc-500">{{ __('No install requests yet.') }}</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $this->rows->links() }}
    </div>
</div>
