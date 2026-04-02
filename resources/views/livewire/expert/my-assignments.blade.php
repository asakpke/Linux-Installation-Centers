<?php

use App\Enums\InstallRequestStatus;
use App\Enums\OfferStatus;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.app')] class extends Component {
    #[Computed]
    public function activeAssignments()
    {
        return Offer::query()
            ->where('expert_user_id', Auth::id())
            ->where('status', OfferStatus::ACCEPTED)
            ->whereHas('installRequest', fn ($q) => $q->where('status', InstallRequestStatus::MATCHED))
            ->with(['installRequest.user'])
            ->latest()
            ->get();
    }

    #[Computed]
    public function completedAssignments()
    {
        return Offer::query()
            ->where('expert_user_id', Auth::id())
            ->where('status', OfferStatus::ACCEPTED)
            ->whereHas('installRequest', fn ($q) => $q->where('status', InstallRequestStatus::CLOSED))
            ->with(['installRequest.user'])
            ->latest()
            ->get();
    }
}; ?>

<section class="w-full">
    <flux:heading>{{ __('My assignments') }}</flux:heading>
    <flux:subheading>{{ __('Requests where the seeker accepted your offer. Active jobs are in progress; completed jobs are archived here.') }}</flux:subheading>

    <flux:heading class="mt-8" size="lg">{{ __('In progress') }}</flux:heading>
    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('You are the assigned expert. Coordinate with the seeker using the contact details below.') }}</p>

    <div class="mt-4 space-y-3">
        @forelse ($this->activeAssignments as $offer)
            @php $req = $offer->installRequest; @endphp
            <flux:card class="p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <flux:link :href="route('expert.requests.show', $req)" class="font-medium" wire:navigate>
                            {{ $req->title }}
                        </flux:link>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $req->city }}, {{ $req->country }}
                        </p>
                        <p class="mt-2 text-sm">
                            <span class="font-medium text-zinc-700 dark:text-zinc-200">{{ __('Seeker') }}:</span>
                            {{ $req->user->name }} · {{ $req->user->email }}
                        </p>
                        <p class="mt-2 text-sm">
                            @if ($offer->is_free)
                                {{ __('Free service') }}
                            @else
                                {{ $offer->currency }} {{ $offer->price_amount }}
                            @endif
                        </p>
                        @if ($offer->message)
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Your offer') }}: {{ $offer->message }}</p>
                        @endif
                        @if ($req->hardware_notes)
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Hardware') }}: {{ $req->hardware_notes }}</p>
                        @endif
                    </div>
                    <flux:badge color="lime">{{ __('Matched') }}</flux:badge>
                </div>
            </flux:card>
        @empty
            <flux:card class="p-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('No active assignments. When a seeker accepts your offer, it will appear here.') }}
            </flux:card>
        @endforelse
    </div>

    <flux:heading class="mt-10" size="lg">{{ __('Completed') }}</flux:heading>
    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Install requests marked complete by the seeker.') }}</p>

    <div class="mt-4 space-y-3">
        @forelse ($this->completedAssignments as $offer)
            @php $req = $offer->installRequest; @endphp
            <flux:card class="p-4 opacity-90">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <flux:link :href="route('expert.requests.show', $req)" class="font-medium" wire:navigate>
                            {{ $req->title }}
                        </flux:link>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                            {{ $req->city }}, {{ $req->country }}
                        </p>
                        <p class="mt-2 text-sm">
                            <span class="font-medium">{{ __('Seeker') }}:</span>
                            {{ $req->user->name }} · {{ $req->user->email }}
                        </p>
                        <p class="mt-2 text-sm">
                            @if ($offer->is_free)
                                {{ __('Free service') }}
                            @else
                                {{ $offer->currency }} {{ $offer->price_amount }}
                            @endif
                        </p>
                    </div>
                    <flux:badge color="zinc">{{ __('Completed') }}</flux:badge>
                </div>
            </flux:card>
        @empty
            <flux:card class="p-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('No completed jobs yet.') }}
            </flux:card>
        @endforelse
    </div>
</section>
