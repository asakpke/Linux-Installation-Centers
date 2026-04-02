<?php

use App\Enums\InstallRequestStatus;
use App\Models\InstallRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public ?InstallRequest $installRequest = null;

    public string $title = '';

    public string $body = '';

    public string $city = '';

    public string $country = '';

    public string $region = '';

    public string $hardware_notes = '';

    public function mount(?InstallRequest $installRequest = null): void
    {
        $this->installRequest = $installRequest;

        if ($installRequest) {
            $this->authorize('update', $installRequest);
            $this->title = $installRequest->title;
            $this->body = (string) $installRequest->body;
            $this->city = (string) $installRequest->city;
            $this->country = (string) $installRequest->country;
            $this->region = (string) $installRequest->region;
            $this->hardware_notes = (string) $installRequest->hardware_notes;
        } else {
            $this->authorize('create', InstallRequest::class);
        }
    }

    public function save(): void
    {
        if ($this->installRequest) {
            $this->authorize('update', $this->installRequest);
        } else {
            $this->authorize('create', InstallRequest::class);
        }

        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['nullable', 'string', 'max:5000'],
            'city' => ['required', 'string', 'max:120'],
            'country' => ['required', 'string', 'max:120'],
            'region' => ['nullable', 'string', 'max:120'],
            'hardware_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($this->installRequest) {
            $this->installRequest->update($validated);
            $this->redirect(route('requests.show', $this->installRequest), navigate: false);

            return;
        }

        $request = Auth::user()->installRequests()->create([
            ...$validated,
            'status' => InstallRequestStatus::OPEN,
        ]);

        $this->redirect(route('requests.show', $request), navigate: false);
    }

    public function cancelRequest(): void
    {
        if (! $this->installRequest) {
            return;
        }

        $this->authorize('cancel', $this->installRequest);

        if ($this->installRequest->status !== InstallRequestStatus::OPEN) {
            return;
        }

        $this->installRequest->update(['status' => InstallRequestStatus::CANCELLED]);
        $this->redirect(route('requests.index'), navigate: false);
    }
}; ?>

<section class="w-full">
    <flux:heading>{{ $installRequest ? __('Edit install request') : __('New install request') }}</flux:heading>
    <flux:subheading>{{ __('Describe what you need so nearby experts can respond.') }}</flux:subheading>

    <form wire:submit="save" class="mt-6 max-w-2xl space-y-6">
        <flux:input wire:model="title" :label="__('Title')" required />

        <flux:textarea wire:model="body" :label="__('Details')" rows="5" :placeholder="__('What you want to use Linux for, timeline, etc.')" />

        <div class="grid gap-4 sm:grid-cols-2">
            <flux:input wire:model="city" :label="__('City')" required />
            <flux:input wire:model="country" :label="__('Country')" required />
        </div>

        <flux:input wire:model="region" :label="__('Region / state (optional)')" />

        <flux:textarea wire:model="hardware_notes" :label="__('Hardware notes (optional)')" rows="3" :placeholder="__('Laptop model, RAM, disk space…')" />

        <div class="flex flex-wrap gap-3">
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
            <flux:button :href="route('requests.index')" variant="ghost" wire:navigate>{{ __('Back') }}</flux:button>
            @if ($installRequest && $installRequest->status === InstallRequestStatus::OPEN)
                <flux:button type="button" variant="danger" wire:click="cancelRequest" wire:confirm="{{ __('Cancel this request?') }}">
                    {{ __('Cancel request') }}
                </flux:button>
            @endif
        </div>
    </form>
</section>
