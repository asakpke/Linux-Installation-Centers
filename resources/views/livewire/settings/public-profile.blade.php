<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component {
    public bool $public_profile_enabled = false;

    public string $public_slug = '';

    public string $public_bio = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->public_profile_enabled = (bool) $user->public_profile_enabled;
        $this->public_slug = (string) ($user->public_slug ?? '');
        $this->public_bio = (string) ($user->public_bio ?? '');
    }

    public function save(): void
    {
        $user = Auth::user();

        if ($user->role === UserRole::ADMIN) {
            return;
        }

        $slugRules = [
            'nullable',
            'string',
            'min:3',
            'max:80',
            'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            Rule::unique('users', 'public_slug')->ignore($user->id),
        ];

        if ($this->public_profile_enabled) {
            $slugRules[] = 'required';
        }

        $rules = [
            'public_profile_enabled' => ['boolean'],
            'public_slug' => $slugRules,
        ];

        if ($user->role === UserRole::USER) {
            $rules['public_bio'] = ['nullable', 'string', 'max:1000'];
        }

        $validated = $this->validate($rules);

        $user->public_profile_enabled = $validated['public_profile_enabled'];

        if ($user->role === UserRole::USER) {
            $user->public_bio = $validated['public_bio'] ?? null;
        }

        if ($user->public_profile_enabled) {
            $user->public_slug = Str::lower($validated['public_slug']);
        }

        $user->save();

        $this->dispatch('public-profile-saved');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Public profile')" :subheading="__('Optional public page with your reviews (slug URL).')">
        @if (auth()->user()->role === \App\Enums\UserRole::ADMIN)
            <flux:callout variant="warning" class="my-6" icon="exclamation-triangle">
                {{ __('Admin accounts cannot enable a public profile.') }}
            </flux:callout>
        @else
            <form wire:submit="save" class="my-6 w-full space-y-6">
                <flux:switch wire:model="public_profile_enabled" :label="__('Show public profile')" />

                <flux:input
                    wire:model="public_slug"
                    :label="__('Profile URL slug')"
                    :description="__('Used as :url', ['url' => url('/profiles/').'…'])"
                    autocomplete="off"
                />

                @if (auth()->user()->role === \App\Enums\UserRole::USER)
                    <flux:textarea wire:model="public_bio" :label="__('Public bio')" rows="4" :placeholder="__('A short introduction visible on your public page.')" />
                @endif

                @if (auth()->user()->public_profile_enabled && filled(auth()->user()->public_slug))
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('Your page:') }}
                        <flux:link :href="route('profiles.show', ['public_slug' => auth()->user()->public_slug])" target="_blank" rel="noopener noreferrer">
                            {{ url('/profiles/'.auth()->user()->public_slug) }}
                        </flux:link>
                    </p>
                @endif

                <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>

                <x-action-message class="ms-3" on="public-profile-saved">
                    {{ __('Saved.') }}
                </x-action-message>
            </form>
        @endif
    </x-settings.layout>
</section>
