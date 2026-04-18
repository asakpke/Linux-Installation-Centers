<?php

use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function summary(): array
    {
        $user = Auth::user();
        $agg = Review::query()
            ->where('reviewee_id', $user->id)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as c')
            ->first();

        return [
            'avg' => $agg && $agg->c > 0 ? round((float) $agg->avg_rating, 1) : null,
            'count' => (int) ($agg->c ?? 0),
        ];
    }

    #[Computed]
    public function recentReviews()
    {
        return Review::query()
            ->where('reviewee_id', Auth::id())
            ->with('reviewer')
            ->latest()
            ->limit(5)
            ->get();
    }
}; ?>

<flux:card class="p-6">
    <flux:heading size="lg">{{ __('Your reputation') }}</flux:heading>
    @if ($this->summary['count'] === 0)
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ __('No reviews yet. Completed installs can earn reviews from the other party.') }}</p>
    @else
        <p class="mt-2 text-sm text-zinc-700 dark:text-zinc-300">
            {{ __(':avg average from :count reviews', ['avg' => $this->summary['avg'], 'count' => $this->summary['count']]) }}
        </p>
        <ul class="mt-4 space-y-3 text-sm">
            @foreach ($this->recentReviews as $review)
                <li class="border-b border-zinc-200 pb-3 last:border-0 dark:border-zinc-700" wire:key="rr-{{ $review->id }}">
                    <span class="font-medium">{{ $review->reviewer->publicReviewFirstName() }} · {{ $review->reviewer->publicReviewRoleLabel() }}</span>
                    <span class="text-amber-600 dark:text-amber-400"> · {{ $review->rating }}/5</span>
                    @if ($review->comment)
                        <p class="mt-1 text-zinc-600 dark:text-zinc-400">{{ $review->comment }}</p>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</flux:card>
