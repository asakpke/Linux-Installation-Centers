<?php

use App\Models\ExpertProfile;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

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
            $this->hourly_rate = $profile->hourly_rate ?? '';
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

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Expert Profile') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Update your expert profile information.") }}
                        </p>
                    </header>

                    <form wire:submit="save" class="mt-6 space-y-6">
                        <!-- Bio -->
                        <flux:textarea
                            wire:model="bio"
                            :label="__('Bio')"
                            rows="4"
                            :placeholder="__('Tell us about your expertise...')"
                        />

                        <!-- Location -->
                        <flux:input
                            wire:model="location"
                            :label="__('Location')"
                            type="text"
                            :placeholder="__('City, Country')"
                        />

                        <!-- Website -->
                        <flux:input
                            wire:model="website"
                            :label="__('Website')"
                            type="url"
                            placeholder="https://example.com"
                        />

                        <!-- Hourly Rate -->
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
                </section>
            </div>
        </div>
    </div>
</div>
