<?php

use App\Models\InstallRequest;
use App\Models\Offer;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public InstallRequest $installRequest;

    public function mount(InstallRequest $installRequest): void
    {
        $this->installRequest = $installRequest->load(['pendingOffers.expert', 'acceptedOffer.expert', 'user']);
        $this->authorize('view', $this->installRequest);
    }

    public function acceptOffer(int $offerId): void
    {
        $this->authorize('acceptOffer', $this->installRequest);

        $offer = Offer::query()
            ->where('install_request_id', $this->installRequest->id)
            ->whereKey($offerId)
            ->firstOrFail();

        $this->installRequest->acceptOffer($offer);
        $this->installRequest->refresh()->load(['pendingOffers.expert', 'acceptedOffer.expert']);
        $this->dispatch('offer-accepted');
    }

    public function cancelRequest(): void
    {
        $this->authorize('cancel', $this->installRequest);

        if ($this->installRequest->status->value !== 'open') {
            return;
        }

        $this->installRequest->update(['status' => \App\Enums\InstallRequestStatus::CANCELLED]);
        $this->redirect(route('requests.index'), navigate: false);
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
        @if ($installRequest->status === \App\Enums\InstallRequestStatus::OPEN)
            <flux:button variant="danger" wire:click="cancelRequest" wire:confirm="{{ __('Cancel this request?') }}">
                {{ __('Cancel request') }}
            </flux:button>
        @endif
    </div>

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

    @if ($installRequest->acceptedOffer)
        <flux:card class="mt-6 border-emerald-200 bg-emerald-50/50 p-4 dark:border-emerald-900 dark:bg-emerald-950/30">
            <flux:heading size="sm">{{ __('Accepted expert') }}</flux:heading>
            <p class="mt-2 font-medium">{{ $installRequest->acceptedOffer->expert->name }}</p>
            <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $installRequest->acceptedOffer->expert->email }}</p>
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

    <flux:heading class="mt-8" size="lg">{{ __('Offers from experts') }}</flux:heading>

    @if ($installRequest->status === \App\Enums\InstallRequestStatus::OPEN && $installRequest->pendingOffers->isEmpty())
        <p class="mt-2 text-sm text-zinc-500">{{ __('No pending offers yet.') }}</p>
    @endif

    <div class="mt-4 space-y-3">
        @foreach ($installRequest->pendingOffers as $offer)
            <flux:card class="p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-medium">{{ $offer->expert->name }}</p>
                        <p class="text-sm text-zinc-500">{{ $offer->expert->email }}</p>
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
                    @if ($installRequest->status === \App\Enums\InstallRequestStatus::OPEN)
                        <flux:button variant="primary" wire:click="acceptOffer({{ $offer->id }})" wire:confirm="{{ __('Accept this offer? Other pending offers will be declined.') }}">
                            {{ __('Accept offer') }}
                        </flux:button>
                    @endif
                </div>
            </flux:card>
        @endforeach
    </div>
</section>
