@props([
    'user',
    'label' => null,
])

@if ($user->public_profile_enabled && filled($user->public_slug))
    <flux:link
        :href="route('profiles.show', ['public_slug' => $user->public_slug])"
        wire:navigate
        {{ $attributes }}
    >
        {{ $label ?? __('Public profile') }}
    </flux:link>
@endif
