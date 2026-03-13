<?php

use App\Models\ExpertProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public string $bio = '';
    public string $location = '';
    public string $website = '';
    public string $hourly_rate = '';

    public function mount(): void
    {
        $profile = Auth::user()->expertProfile;

        if ($profile) {
            $this->bio = $profile->bio ?? '';
            $this->location = $profile->location ?? '';
            $this->website = $profile->website ?? '';
            $this->hourly_rate = $profile->hourly_rate !== null && $profile->hourly_rate !== ''
                ? (string) $profile->hourly_rate
                : '';
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'bio' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
        ]);

        Auth::user()->expertProfile()->updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        $this->dispatch('profile-updated', name: 'expert-profile');
    }
}; ?>

<section class="w-full">
    <flux:heading>{{ __('Expert Profile') }}</flux:heading>
    <flux:subheading>{{ __('Update your expert profile information.') }}</flux:subheading>

    <div class="mt-5 w-full max-w-lg">
        <form wire:submit="save" class="my-6 w-full space-y-6">
            <flux:textarea
                wire:model="bio"
                :label="__('Bio')"
                rows="4"
                :placeholder="__('Tell us about your expertise...')"
            />

            <flux:input
                wire:model="location"
                :label="__('Location')"
                type="text"
                :placeholder="__('City, Country')"
            />

            <flux:input
                wire:model="website"
                :label="__('Website')"
                type="url"
                placeholder="https://example.com"
            />

            <flux:input
                wire:model="hourly_rate"
                :label="__('Hourly Rate ($)')"
                type="number"
                step="0.01"
                placeholder="0.00"
            />

            <div class="flex items-center gap-4">
                <flux:button type="submit" variant="primary">
                    {{ __('Save') }}
                </flux:button>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </div>
</section>
