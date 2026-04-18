<?php

use App\Enums\UserRole;
use App\Models\Review;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('components.layouts.public-simple')] class extends Component {
    use WithPagination;

    public string $public_slug = '';

    public User $profileUser;

    public function mount(string $public_slug): void
    {
        $this->public_slug = $public_slug;

        $user = User::query()
            ->where('public_slug', $this->public_slug)
            ->where('public_profile_enabled', true)
            ->where('role', '!=', UserRole::ADMIN)
            ->with(['expertProfile'])
            ->first();

        if (! $user) {
            abort(404);
        }

        $this->profileUser = $user;
    }

    #[Computed]
    public function ratingSummary(): array
    {
        $agg = Review::query()
            ->where('reviewee_id', $this->profileUser->id)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as c')
            ->first();

        return [
            'avg' => $agg && $agg->c > 0 ? round((float) $agg->avg_rating, 1) : null,
            'count' => (int) ($agg->c ?? 0),
        ];
    }

    #[Computed]
    public function reviews()
    {
        return Review::query()
            ->where('reviewee_id', $this->profileUser->id)
            ->with('reviewer')
            ->latest()
            ->paginate(10, pageName: 'reviewsPage');
    }
}; ?>

<div>
    <flux:heading size="xl">{{ $this->profileUser->name }}</flux:heading>
    <flux:subheading class="mt-1">
        {{ $this->profileUser->publicReviewRoleLabel() }}
        @if ($this->ratingSummary['count'] > 0)
            · {{ __(':avg average (:count reviews)', ['avg' => $this->ratingSummary['avg'], 'count' => $this->ratingSummary['count']]) }}
        @endif
    </flux:subheading>

    @if ($this->profileUser->role === UserRole::EXPERT && $this->profileUser->expertProfile)
        @php $p = $this->profileUser->expertProfile; @endphp
        <flux:card class="mt-6 space-y-3 p-4 text-sm">
            @if (filled($p->bio))
                <p class="leading-relaxed text-zinc-700 dark:text-zinc-300">{!! nl2br(e($p->bio)) !!}</p>
            @endif
            <p class="text-zinc-600 dark:text-zinc-400">
                {{ $p->city }}{{ filled($p->city) && filled($p->country) ? ', ' : '' }}{{ $p->country }}
            </p>
            @if (filled($p->website))
                <p><flux:link :href="$p->website" target="_blank" rel="noopener noreferrer">{{ __('Website') }}</flux:link></p>
            @endif
            @if ($p->hourly_rate !== null && $p->hourly_rate !== '')
                <p>{{ __('Hourly rate') }}: {{ $p->hourly_rate }}</p>
            @endif
        </flux:card>
    @elseif ($this->profileUser->role === UserRole::USER)
        @if (filled($this->profileUser->public_bio))
            <flux:card class="mt-6 p-4 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">
                {!! nl2br(e($this->profileUser->public_bio)) !!}
            </flux:card>
        @endif
    @endif

    <flux:heading class="mt-10" size="lg">{{ __('Reviews') }}</flux:heading>
    @if ($this->reviews->isEmpty())
        <p class="mt-2 text-sm text-zinc-500">{{ __('No public reviews yet.') }}</p>
    @else
        <ul class="mt-4 space-y-4">
            @foreach ($this->reviews as $review)
                <flux:card class="p-4 text-sm" wire:key="rev-{{ $review->id }}">
                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                        <span class="font-medium text-zinc-800 dark:text-zinc-100">
                            {{ $review->reviewer->publicReviewFirstName() }} · {{ $review->reviewer->publicReviewRoleLabel() }}
                        </span>
                        <span class="text-amber-600 dark:text-amber-400">{{ __(':n / 5', ['n' => $review->rating]) }}</span>
                    </div>
                    @if ($review->comment)
                        <p class="mt-2 text-zinc-600 dark:text-zinc-400">{{ $review->comment }}</p>
                    @endif
                </flux:card>
            @endforeach
        </ul>
        <div class="mt-6">
            {{ $this->reviews->links() }}
        </div>
    @endif
</div>
