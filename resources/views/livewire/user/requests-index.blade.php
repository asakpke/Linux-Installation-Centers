<?php

use App\Models\InstallRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    #[Computed]
    public function rows()
    {
        return InstallRequest::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
    }
}; ?>

<section class="w-full">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading>{{ __('My install requests') }}</flux:heading>
            <flux:subheading>{{ __('Track requests for local Linux installation help.') }}</flux:subheading>
        </div>
        <flux:button :href="route('requests.create')" variant="primary" wire:navigate>
            {{ __('New request') }}
        </flux:button>
    </div>

    <div class="mt-6 space-y-3">
        @forelse ($this->rows as $installRequest)
            <flux:card class="p-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <flux:link :href="route('requests.show', $installRequest)" class="font-medium" wire:navigate>
                            {{ $installRequest->title }}
                        </flux:link>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $installRequest->city }}, {{ $installRequest->country }}
                            · {{ $installRequest->status->value }}
                            @if ($installRequest->accepted_offer_id)
                                · {{ __('Matched') }}
                            @endif
                        </p>
                    </div>
                    <flux:badge>{{ __($installRequest->status->value) }}</flux:badge>
                </div>
            </flux:card>
        @empty
            <flux:card class="p-8 text-center text-zinc-500 dark:text-zinc-400">
                {{ __('No requests yet. Create one to find a local expert.') }}
            </flux:card>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $this->rows->links() }}
    </div>
</section>
