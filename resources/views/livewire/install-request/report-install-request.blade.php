<?php

use App\Enums\ReportCategory;
use App\Enums\ReportStatus;
use App\Models\InstallRequest;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public InstallRequest $installRequest;

    public string $category = '';

    public string $details = '';

    public function mount(InstallRequest $installRequest): void
    {
        $this->installRequest = $installRequest;
        $this->authorize('report', $this->installRequest);
        $this->category = ReportCategory::SPAM_INSTALL_REQUEST->value;
    }

    public function submitReport(): void
    {
        $this->authorize('report', $this->installRequest);

        $validated = $this->validate([
            'category' => ['required', 'string', 'in:'.implode(',', array_column(ReportCategory::cases(), 'value'))],
            'details' => ['nullable', 'string', 'max:5000'],
        ]);

        Report::create([
            'reporter_id' => Auth::id(),
            'subject_type' => 'install_request',
            'subject_id' => $this->installRequest->id,
            'category' => $validated['category'],
            'details' => $validated['details'] ?? null,
            'status' => ReportStatus::OPEN,
        ]);

        $this->reset('details');
        $this->dispatch('install-request-reported');
    }
}; ?>

<flux:card class="mt-6 border-amber-200/80 p-4 dark:border-amber-900/50">
    <flux:heading size="sm">{{ __('Report this request') }}</flux:heading>
    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Use this if the listing is spam, abusive, or not a genuine Linux installation request. The team will review reports.') }}</p>

    <form wire:submit="submitReport" class="mt-4 max-w-lg space-y-4">
        <flux:select wire:model="category" :label="__('Reason')">
            <flux:select.option value="{{ ReportCategory::SPAM_INSTALL_REQUEST->value }}">{{ __('Spam / not a real install request') }}</flux:select.option>
            <flux:select.option value="{{ ReportCategory::HARASSMENT->value }}">{{ __('Harassment or safety concern') }}</flux:select.option>
            <flux:select.option value="{{ ReportCategory::OTHER->value }}">{{ __('Other') }}</flux:select.option>
        </flux:select>
        <flux:textarea wire:model="details" :label="__('Details (optional)')" rows="3" />
        <flux:button type="submit" variant="ghost">{{ __('Submit report') }}</flux:button>
    </form>

    <x-action-message class="mt-2" on="install-request-reported">
        {{ __('Report submitted. Thank you.') }}
    </x-action-message>
</flux:card>
