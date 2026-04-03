<?php

use App\Enums\InstallRequestStatus;
use App\Enums\UserRole;
use App\Models\InstallRequest;
use App\Models\InstallRequestMessage;
use Livewire\Component;

new class extends Component
{
    public InstallRequest $installRequest;

    public string $body = '';

    public function mount(InstallRequest $installRequest): void
    {
        $this->installRequest = $installRequest;
        $this->authorize('viewMessages', $this->installRequest);
        $this->installRequest->load(['messages.user']);
    }

    public function postMessage(): void
    {
        $this->authorize('postMessage', $this->installRequest);

        $validated = $this->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        InstallRequestMessage::create([
            'install_request_id' => $this->installRequest->id,
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        $this->reset('body');
        $this->installRequest->refresh()->load(['messages.user']);
    }
}; ?>

<div class="mt-8">
    <flux:heading size="lg">{{ __('Messages') }}</flux:heading>
    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Coordinate timing, location, and device details here.') }}</p>

    <div class="mt-4 space-y-3">
        @forelse ($installRequest->messages as $message)
            <flux:card class="p-4">
                <div class="flex flex-wrap items-baseline justify-between gap-2">
                    <p class="font-medium text-zinc-900 dark:text-zinc-100">
                        {{ $message->user->name }}
                        @if ((int) $message->user_id === (int) auth()->id())
                            <span class="text-sm font-normal text-zinc-500">{{ __('(you)') }}</span>
                        @endif
                    </p>
                    <time class="text-xs text-zinc-500 dark:text-zinc-400" datetime="{{ $message->created_at->toIso8601String() }}">
                        {{ $message->created_at->timezone(config('app.timezone'))->format('Y-m-d H:i') }}
                    </time>
                </div>
                <p class="mt-2 whitespace-pre-wrap text-sm text-zinc-700 dark:text-zinc-300">{{ $message->body }}</p>
            </flux:card>
        @empty
            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('No messages yet.') }}</p>
        @endforelse
    </div>

    @can('postMessage', $installRequest)
        <form wire:submit="postMessage" class="mt-6 max-w-2xl space-y-3">
            <flux:textarea wire:model="body" :label="__('Your message')" rows="4" required />
            <flux:button type="submit" variant="primary">{{ __('Send message') }}</flux:button>
        </form>
    @else
        @if (auth()->user()->role === UserRole::ADMIN)
            <flux:callout variant="info" class="mt-6" icon="information-circle">
                {{ __('Admins can read this thread for support; only the seeker and assigned expert can post.') }}
            </flux:callout>
        @elseif ($installRequest->status === InstallRequestStatus::CLOSED)
            <p class="mt-6 text-sm text-zinc-500 dark:text-zinc-400">{{ __('This job is complete. Message history is read-only.') }}</p>
        @endif
    @endcan
</div>
