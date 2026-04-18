<?php

use App\Enums\InstallRequestStatus;
use App\Models\InstallRequest;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.app.admin')] class extends Component {
    public InstallRequest $installRequest;

    public function mount(InstallRequest $installRequest): void
    {
        $this->installRequest = $installRequest->load(['user', 'offers.expert', 'acceptedOffer.expert']);
        $this->authorize('view', $installRequest);
    }

    public function cancelRequest(): void
    {
        $this->authorize('cancel', $this->installRequest);

        if (! in_array($this->installRequest->status, [InstallRequestStatus::OPEN, InstallRequestStatus::MATCHED], true)) {
            return;
        }

        $this->installRequest->update(['status' => InstallRequestStatus::CANCELLED]);
        $this->installRequest->refresh();
        $this->dispatch('request-cancelled');
    }

    public function markAsSpam(): void
    {
        $this->authorize('markAsSpam', $this->installRequest);

        $this->installRequest->update(['status' => InstallRequestStatus::SPAM]);
        $this->installRequest->refresh();
        $this->dispatch('request-marked-spam');
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:link :href="route('admin.requests.index')" class="text-sm" wire:navigate>{{ __('← Back') }}</flux:link>

    <flux:heading size="xl">{{ $installRequest->title }}</flux:heading>
    <p class="flex flex-wrap items-center gap-x-2 text-zinc-600 dark:text-zinc-400">
        <span>{{ $installRequest->user->name }} · {{ $installRequest->user->email }}</span>
        <x-user-public-profile-link :user="$installRequest->user" :label="__('Seeker public profile')" />
        <span>· {{ __($installRequest->status->value) }}</span>
    </p>

    @if (in_array($installRequest->status, [\App\Enums\InstallRequestStatus::OPEN, \App\Enums\InstallRequestStatus::MATCHED], true))
        <flux:button variant="danger" wire:click="cancelRequest" wire:confirm="{{ __('Cancel this request as admin?') }}">
            {{ __('Cancel request') }}
        </flux:button>
    @endif

    @can('markAsSpam', $installRequest)
        <flux:button class="mt-2" variant="filled" wire:click="markAsSpam" wire:confirm="{{ __('Mark this install request as spam or not a legitimate LIR? Experts will lose access.') }}">
            {{ __('Mark as spam / invalid LIR') }}
        </flux:button>
    @endcan

    <x-action-message class="mt-2" on="request-marked-spam">
        {{ __('Marked as spam.') }}
    </x-action-message>

    @if ($installRequest->body)
        <flux:card class="p-4 text-sm">{!! nl2br(e($installRequest->body)) !!}</flux:card>
    @endif

    <flux:heading size="lg" class="mt-4">{{ __('Offers') }}</flux:heading>
    <div class="space-y-2">
        @forelse ($installRequest->offers as $offer)
            <flux:card class="p-3 text-sm">
                <span class="inline-flex flex-wrap items-center gap-x-2">
                    <strong>{{ $offer->expert->name }}</strong>
                    <x-user-public-profile-link :user="$offer->expert" class="text-sm font-normal" :label="__('Expert profile')" />
                </span>
                ({{ $offer->status->value }})
                @if ($offer->is_free)
                    · {{ __('Free') }}
                @else
                    · {{ $offer->currency }} {{ $offer->price_amount }}
                @endif
                @if ($offer->message)
                    <p class="mt-1">{{ $offer->message }}</p>
                @endif
            </flux:card>
        @empty
            <p class="text-zinc-500">{{ __('No offers.') }}</p>
        @endforelse
    </div>

    @can('viewMessages', $installRequest)
        <livewire:install-request.message-thread :install-request="$installRequest" wire:key="ir-msg-admin-{{ $installRequest->id }}" />
    @endcan
</div>
