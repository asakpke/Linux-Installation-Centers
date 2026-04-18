<?php

use App\Enums\InstallRequestStatus;
use App\Models\InstallRequest;
use App\Models\Offer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public InstallRequest $installRequest;

    public function mount(InstallRequest $installRequest): void
    {
        $this->installRequest = $installRequest->load(['pendingOffers.expert', 'acceptedOffer.expert', 'user', 'reviews']);
        $this->authorize('view', $this->installRequest);
    }

    public function markInstallComplete(): void
    {
        $this->authorize('complete', $this->installRequest);

        if ($this->installRequest->status !== InstallRequestStatus::MATCHED) {
            return;
        }

        $this->installRequest->update(['status' => InstallRequestStatus::CLOSED]);
        $this->installRequest->refresh()->load(['pendingOffers.expert', 'acceptedOffer.expert', 'user', 'reviews']);
        $this->dispatch('install-marked-complete');
    }

    public function acceptOffer(int $offerId): void
    {
        $this->authorize('acceptOffer', $this->installRequest);

        $offer = Offer::query()
            ->where('install_request_id', $this->installRequest->id)
            ->whereKey($offerId)
            ->firstOrFail();

        $this->installRequest->acceptOffer($offer);
        $this->installRequest->refresh()->load(['pendingOffers.expert', 'acceptedOffer.expert', 'reviews']);
        $this->dispatch('offer-accepted');
    }

    public function cancelRequest(): void
    {
        $this->authorize('cancel', $this->installRequest);

        if ($this->installRequest->status !== InstallRequestStatus::OPEN) {
            return;
        }

        $this->installRequest->update(['status' => InstallRequestStatus::CANCELLED]);
        $this->redirect(route('requests.index'), navigate: false);
    }

    #[On('review-submitted')]
    public function refreshAfterReview(): void
    {
        $this->installRequest->refresh()->load(['pendingOffers.expert', 'acceptedOffer.expert', 'user', 'reviews']);
    }
}; ?>

<section class="w-full">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <flux:link :href="route('requests.index')" class="text-sm" wire:navigate>{{ __('← Back to requests') }}</flux:link>
            <flux:heading class="mt-2">{{ $installRequest->title }}</flux:heading>
            <flux:subheading>
                {{ $installRequest->city }}, {{ $installRequest->country }}
                @if ($installRequest->region)
                    · {{ $installRequest->region }}
                @endif
                · {{ __($installRequest->status->value) }}
            </flux:subheading>
        </div>
        @if ($installRequest->status === InstallRequestStatus::OPEN)
            <flux:button variant="danger" wire:click="cancelRequest" wire:confirm="{{ __('Cancel this request?') }}">
                {{ __('Cancel request') }}
            </flux:button>
        @endif
        @if ($installRequest->status === InstallRequestStatus::MATCHED)
            <flux:button variant="primary" wire:click="markInstallComplete" wire:confirm="{{ __('Mark this install as complete? The expert will see it under completed assignments.') }}">
                {{ __('Mark install complete') }}
            </flux:button>
        @endif
    </div>

    @if ($installRequest->status === InstallRequestStatus::SPAM)
        <flux:callout variant="danger" class="mt-6" icon="exclamation-triangle">
            {{ __('This request was removed as spam or not a legitimate Linux installation request. If you believe this is a mistake, contact support.') }}
        </flux:callout>
    @endif

    @if ($installRequest->body)
        <flux:card class="prose dark:prose-invert mt-6 max-w-none p-4">
            {!! nl2br(e($installRequest->body)) !!}
        </flux:card>
    @endif

    @if ($installRequest->hardware_notes)
        <div class="mt-4">
            <flux:heading size="sm">{{ __('Hardware notes') }}</flux:heading>
            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">{{ $installRequest->hardware_notes }}</p>
        </div>
    @endif

    @if ($installRequest->status === InstallRequestStatus::CLOSED && $installRequest->acceptedOffer)
        <flux:callout variant="success" class="mt-6" icon="check-circle">
            {{ __('This install is marked complete. Thank you for using Linux Installation Centers.') }}
        </flux:callout>
    @endif

    @if ($installRequest->acceptedOffer)
        <flux:card class="mt-6 border-emerald-200 bg-emerald-50/50 p-4 dark:border-emerald-900 dark:bg-emerald-950/30">
            <flux:heading size="sm">{{ __('Accepted expert') }}</flux:heading>
            <p class="mt-2 font-medium">{{ $installRequest->acceptedOffer->expert->name }}</p>
            <p class="mt-1 flex flex-wrap items-center gap-x-2 text-sm text-zinc-600 dark:text-zinc-400">
                <x-user-public-profile-link :user="$installRequest->acceptedOffer->expert" :label="__('Public profile')" />
                @unless ($installRequest->acceptedOffer->expert->public_profile_enabled && $installRequest->acceptedOffer->expert->public_slug)
                    {{ $installRequest->acceptedOffer->expert->email }}
                @endunless
            </p>
            <p class="mt-2 text-sm">
                @if ($installRequest->acceptedOffer->is_free)
                    {{ __('Free service') }}
                @else
                    {{ $installRequest->acceptedOffer->currency }} {{ $installRequest->acceptedOffer->price_amount }}
                @endif
            </p>
            @if ($installRequest->acceptedOffer->message)
                <p class="mt-2 text-sm">{{ $installRequest->acceptedOffer->message }}</p>
            @endif
        </flux:card>
    @endif

    @if ($installRequest->status === InstallRequestStatus::CLOSED && $installRequest->acceptedOffer)
        <livewire:install-request.post-review :install-request="$installRequest" wire:key="post-review-user-{{ $installRequest->id }}" />
    @endif

    @can('report', $installRequest)
        <livewire:install-request.report-install-request :install-request="$installRequest" wire:key="rep-ir-user-{{ $installRequest->id }}" />
    @endcan

    @if ($installRequest->acceptedOffer)
        @can('report', $installRequest->acceptedOffer->expert)
            <livewire:install-request.report-user :target-user="$installRequest->acceptedOffer->expert" wire:key="rep-expert-{{ $installRequest->acceptedOffer->expert->id }}-{{ $installRequest->id }}" />
        @endcan
    @endif

    @can('viewMessages', $installRequest)
        <livewire:install-request.message-thread :install-request="$installRequest" wire:key="ir-msg-user-{{ $installRequest->id }}" />
    @endcan

    <flux:heading class="mt-8" size="lg">{{ __('Offers from experts') }}</flux:heading>

    @if ($installRequest->status === InstallRequestStatus::OPEN && $installRequest->pendingOffers->isEmpty())
        <p class="mt-2 text-sm text-zinc-500">{{ __('No pending offers yet.') }}</p>
    @endif

    <div class="mt-4 space-y-3">
        @foreach ($installRequest->pendingOffers as $offer)
            <flux:card class="p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-medium">{{ $offer->expert->name }}</p>
                        <p class="flex flex-wrap items-center gap-x-2 text-sm text-zinc-500 dark:text-zinc-400">
                            <x-user-public-profile-link :user="$offer->expert" class="font-medium" :label="__('Public profile')" />
                            @unless ($offer->expert->public_profile_enabled && $offer->expert->public_slug)
                                {{ $offer->expert->email }}
                            @endunless
                        </p>
                        <p class="mt-2 text-sm">
                            @if ($offer->is_free)
                                {{ __('Free') }}
                            @else
                                {{ $offer->currency }} {{ $offer->price_amount }}
                            @endif
                        </p>
                        @if ($offer->message)
                            <p class="mt-2 text-sm">{{ $offer->message }}</p>
                        @endif
                    </div>
                    @if ($installRequest->status === InstallRequestStatus::OPEN)
                        <flux:button variant="primary" wire:click="acceptOffer({{ $offer->id }})" wire:confirm="{{ __('Accept this offer? Other pending offers will be declined.') }}">
                            {{ __('Accept offer') }}
                        </flux:button>
                    @endif
                </div>
            </flux:card>
        @endforeach
    </div>
</section>
