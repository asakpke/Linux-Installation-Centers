<?php

use App\Enums\InstallRequestStatus;
use App\Enums\OfferStatus;
use App\Models\InstallRequest;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public InstallRequest $installRequest;

    public bool $is_free = true;

    public string $price_amount = '';

    public string $message = '';

    public function mount(InstallRequest $installRequest): void
    {
        $this->authorize('view', $installRequest);
        $this->installRequest = $installRequest->load('user');

        $existing = Offer::query()
            ->where('install_request_id', $installRequest->id)
            ->where('expert_user_id', Auth::id())
            ->where('status', OfferStatus::PENDING)
            ->first();

        if ($existing) {
            $this->is_free = $existing->is_free;
            $this->price_amount = $existing->price_amount !== null ? (string) $existing->price_amount : '';
            $this->message = (string) ($existing->message ?? '');
        }
    }

    public function saveOffer(): void
    {
        $this->authorize('create', [Offer::class, $this->installRequest]);

        $rules = [
            'is_free' => ['boolean'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];

        if (! $this->is_free) {
            $rules['price_amount'] = ['required', 'numeric', 'min:0'];
        } else {
            $rules['price_amount'] = ['nullable'];
        }

        $validated = $this->validate($rules);

        $pending = Offer::query()
            ->where('install_request_id', $this->installRequest->id)
            ->where('expert_user_id', Auth::id())
            ->where('status', OfferStatus::PENDING)
            ->first();

        $payload = [
            'is_free' => $validated['is_free'],
            'price_amount' => $validated['is_free'] ? null : $validated['price_amount'],
            'currency' => 'USD',
            'message' => $validated['message'] ?? null,
            'status' => OfferStatus::PENDING,
        ];

        if ($pending) {
            $this->authorize('update', $pending);
            $pending->update($payload);
        } else {
            Offer::create([
                'install_request_id' => $this->installRequest->id,
                'expert_user_id' => Auth::id(),
                ...$payload,
            ]);
        }

        $this->dispatch('offer-saved');
    }
}; ?>

<section class="w-full">
    <flux:link :href="route('expert.requests.index')" class="text-sm" wire:navigate>{{ __('← Back to requests') }}</flux:link>

    <flux:heading class="mt-2">{{ $installRequest->title }}</flux:heading>
    <flux:subheading>
        {{ $installRequest->city }}, {{ $installRequest->country }}
        · {{ $installRequest->user->name }}
    </flux:subheading>

    @if ($installRequest->body)
        <flux:card class="mt-4 p-4 text-sm">
            {!! nl2br(e($installRequest->body)) !!}
        </flux:card>
    @endif

    @if ($installRequest->hardware_notes)
        <p class="mt-4 text-sm text-zinc-600 dark:text-zinc-300">
            <span class="font-medium">{{ __('Hardware') }}:</span> {{ $installRequest->hardware_notes }}
        </p>
    @endif

    @if ($installRequest->status !== InstallRequestStatus::OPEN)
        <flux:callout variant="info" class="mt-6" icon="information-circle">
            {{ __('This request is no longer open for new offers.') }}
        </flux:callout>
    @else
        <flux:heading class="mt-8" size="lg">{{ __('Your offer') }}</flux:heading>

        @if (! Auth::user()->expertProfileComplete())
            <flux:callout variant="warning" class="mt-2" icon="exclamation-triangle">
                {{ __('Complete bio, city, and country on your expert profile to submit an offer.') }}
            </flux:callout>
        @else
            <form wire:submit="saveOffer" class="mt-4 max-w-lg space-y-4">
                <flux:checkbox wire:model.live="is_free" :label="__('Free service')" />

                @if (! $is_free)
                    <flux:input wire:model="price_amount" :label="__('Price (USD)')" type="number" step="0.01" />
                @endif

                <flux:textarea wire:model="message" :label="__('Message to seeker')" rows="4" />

                <flux:button type="submit" variant="primary">{{ __('Save offer') }}</flux:button>

                <x-action-message class="ms-3" on="offer-saved">
                    {{ __('Offer saved.') }}
                </x-action-message>
            </form>
        @endif
    @endif
</section>
