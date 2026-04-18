<?php

use App\Enums\InstallRequestStatus;
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
        $user = Auth::user();
        $profile = $user->expertProfile;

        if (! $profile || ! filled($profile->city) || ! filled($profile->country)) {
            return InstallRequest::query()->whereKey([])->paginate(10);
        }

        return InstallRequest::query()
            ->where('status', InstallRequestStatus::OPEN)
            ->whereRaw('LOWER(city) = ?', [mb_strtolower($profile->city)])
            ->whereRaw('LOWER(country) = ?', [mb_strtolower($profile->country)])
            ->whereNotNull('city')
            ->whereNotNull('country')
            ->with('user')
            ->latest()
            ->paginate(10);
    }
}; ?>

<section class="w-full">
    <flux:heading>{{ __('Open requests in your area') }}</flux:heading>
    <flux:subheading>{{ __('Same city and country as your expert profile.') }}</flux:subheading>

    @if (! Auth::user()->expertProfileComplete())
        <flux:callout variant="warning" class="mt-4" icon="exclamation-triangle">
            {{ __('Complete your expert profile (bio, city, country) before you can respond to requests.') }}
            <flux:link :href="route('expert.profile')" class="ms-1 font-medium" wire:navigate>{{ __('Edit profile') }}</flux:link>
        </flux:callout>
    @endif

    <div class="mt-6 space-y-3">
        @forelse ($this->rows as $installRequest)
            <flux:card class="p-4">
                <flux:link :href="route('expert.requests.show', $installRequest)" class="font-medium" wire:navigate>
                    {{ $installRequest->title }}
                </flux:link>
                <p class="mt-1 flex flex-wrap items-center gap-x-1 gap-y-1 text-sm text-zinc-500 dark:text-zinc-400">
                    <span>{{ $installRequest->city }}, {{ $installRequest->country }}</span>
                    <span>·</span>
                    <span>{{ __('Seeker') }}: {{ $installRequest->user->name }}</span>
                    @if ($installRequest->user->public_profile_enabled && $installRequest->user->public_slug)
                        <span>·</span>
                        <x-user-public-profile-link :user="$installRequest->user" class="text-sm font-medium" :label="__('Profile')" />
                    @endif
                </p>
            </flux:card>
        @empty
            <flux:card class="p-8 text-center text-zinc-500 dark:text-zinc-400">
                {{ __('No open requests match your location right now.') }}
            </flux:card>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $this->rows->links() }}
    </div>
</section>
