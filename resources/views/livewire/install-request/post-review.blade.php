<?php

use App\Models\InstallRequest;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public InstallRequest $installRequest;

    public int $rating = 5;

    public string $comment = '';

    public function mount(InstallRequest $installRequest): void
    {
        $this->installRequest = $installRequest;
        $this->authorize('view', $this->installRequest);
    }

    public function submitReview(): void
    {
        $this->authorize('createReview', $this->installRequest);

        $this->installRequest->loadMissing('acceptedOffer');

        $validated = $this->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $seekerId = (int) $this->installRequest->user_id;
        $expertId = (int) $this->installRequest->acceptedOffer->expert_user_id;
        $me = (int) Auth::id();

        $revieweeId = $me === $seekerId ? $expertId : $seekerId;

        Review::create([
            'install_request_id' => $this->installRequest->id,
            'reviewer_id' => $me,
            'reviewee_id' => $revieweeId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        $this->installRequest->refresh()->load(['reviews']);
        $this->dispatch('review-submitted');
    }
}; ?>

@php
    $me = auth()->id();
    $myReview = $installRequest->reviews->firstWhere('reviewer_id', $me);
    $canReview = auth()->user()->can('createReview', $installRequest);
    $counterparty = null;
    if ($installRequest->acceptedOffer) {
        $counterparty = (int) $me === (int) $installRequest->user_id
            ? $installRequest->acceptedOffer->expert
            : $installRequest->user;
    }
@endphp

<flux:card class="mt-6 p-4">
    <flux:heading size="sm">{{ __('Review') }}</flux:heading>

    @if ($myReview)
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ __('You submitted a review for this install.') }}</p>
        @if ($counterparty && $counterparty->public_profile_enabled && $counterparty->public_slug)
            <p class="mt-2 text-sm">
                <x-user-public-profile-link :user="$counterparty" :label="__('Their public profile')" />
            </p>
        @endif
    @elseif ($canReview)
        @if ($counterparty && $counterparty->public_profile_enabled && $counterparty->public_slug)
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                <x-user-public-profile-link :user="$counterparty" :label="__('About them (public profile)')" />
            </p>
        @endif
        <form wire:submit="submitReview" class="mt-4 max-w-md space-y-4">
            <x-rating-scale-picker :current="$rating" />
            <flux:textarea wire:model="comment" :label="__('Comment (optional)')" rows="3" />
            <flux:button type="submit" variant="primary">{{ __('Submit review') }}</flux:button>
        </form>
    @endif

    <x-action-message class="mt-2" on="review-submitted">
        {{ __('Review saved.') }}
    </x-action-message>
</flux:card>
