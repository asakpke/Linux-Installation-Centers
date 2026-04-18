<?php

use App\Enums\ReportStatus;
use App\Models\InstallRequest;
use App\Models\Report;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.app.admin')] class extends Component {
    public Report $report;

    public string $admin_notes = '';

    public function mount(Report $report): void
    {
        $this->authorize('view', $report);
        $this->report = $report->load(['reporter', 'subject']);
        $this->admin_notes = (string) ($this->report->admin_notes ?? '');
    }

    public function saveNotes(): void
    {
        $this->authorize('update', $this->report);

        $validated = $this->validate([
            'admin_notes' => ['nullable', 'string', 'max:10000'],
        ]);

        $this->report->update(['admin_notes' => $validated['admin_notes'] ?? null]);
        $this->report->refresh();
        $this->dispatch('report-notes-saved');
    }

    public function dismiss(): void
    {
        $this->authorize('update', $this->report);

        $this->report->update(['status' => ReportStatus::DISMISSED]);
        $this->report->refresh();
        $this->dispatch('report-updated');
    }

    public function markActioned(): void
    {
        $this->authorize('update', $this->report);

        $this->report->update(['status' => ReportStatus::ACTIONED]);
        $this->report->refresh();
        $this->dispatch('report-updated');
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:link :href="route('admin.reports.index')" class="text-sm" wire:navigate>{{ __('← Back to reports') }}</flux:link>

    <flux:heading size="xl">{{ __('Report #') }}{{ $report->id }}</flux:heading>
    <p class="text-sm text-zinc-500">{{ __('Status') }}: {{ $report->status->value }} · {{ $report->category->value }}</p>

    <flux:card class="space-y-2 p-4 text-sm">
        <p><span class="font-medium">{{ __('Reporter') }}:</span> {{ $report->reporter->name }} ({{ $report->reporter->email }})</p>
        <p><span class="font-medium">{{ __('Subject') }}:</span> {{ $report->subject_type }} #{{ $report->subject_id }}</p>
        @if ($report->details)
            <p class="mt-2 whitespace-pre-wrap">{{ $report->details }}</p>
        @endif
    </flux:card>

    @if ($report->subject instanceof InstallRequest)
        <flux:link :href="route('admin.requests.show', $report->subject)" wire:navigate class="text-sm font-medium">{{ __('Open install request in admin') }}</flux:link>
    @elseif ($report->subject instanceof User)
        <flux:link :href="route('admin.users.edit', $report->subject)" wire:navigate class="text-sm font-medium">{{ __('Open user in admin') }}</flux:link>
    @elseif ($report->subject === null)
        <p class="text-sm text-amber-600 dark:text-amber-400">{{ __('Subject record is missing (may have been deleted).') }}</p>
    @endif

    <form wire:submit="saveNotes" class="max-w-xl space-y-3">
        <flux:textarea wire:model="admin_notes" :label="__('Admin notes')" rows="4" />
        <flux:button type="submit" variant="primary">{{ __('Save notes') }}</flux:button>
    </form>

    <x-action-message on="report-notes-saved">{{ __('Notes saved.') }}</x-action-message>

    @if ($report->status === ReportStatus::OPEN)
        <div class="flex flex-wrap gap-2">
            <flux:button wire:click="dismiss" variant="ghost" wire:confirm="{{ __('Dismiss this report?') }}">{{ __('Dismiss') }}</flux:button>
            <flux:button wire:click="markActioned" variant="primary" wire:confirm="{{ __('Mark as actioned?') }}">{{ __('Mark actioned') }}</flux:button>
        </div>
    @endif

    <x-action-message on="report-updated">{{ __('Report status updated.') }}</x-action-message>
</div>
