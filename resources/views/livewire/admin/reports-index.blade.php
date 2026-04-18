<?php

use App\Enums\ReportStatus;
use App\Models\Report;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('components.layouts.app.admin')] class extends Component {
    use WithPagination;

    public string $statusFilter = 'open';

    public function mount(): void
    {
        $this->authorize('viewAny', Report::class);
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function getReportsProperty()
    {
        return Report::query()
            ->with(['reporter'])
            ->when($this->statusFilter === 'open', fn ($q) => $q->where('status', ReportStatus::OPEN))
            ->when($this->statusFilter === 'dismissed', fn ($q) => $q->where('status', ReportStatus::DISMISSED))
            ->when($this->statusFilter === 'actioned', fn ($q) => $q->where('status', ReportStatus::ACTIONED))
            ->when($this->statusFilter === 'all', fn ($q) => $q)
            ->latest()
            ->paginate(15);
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <flux:heading size="xl">{{ __('Reports') }}</flux:heading>

    <flux:select wire:model.live="statusFilter" class="max-w-xs">
        <flux:select.option value="open">{{ __('Open') }}</flux:select.option>
        <flux:select.option value="dismissed">{{ __('Dismissed') }}</flux:select.option>
        <flux:select.option value="actioned">{{ __('Actioned') }}</flux:select.option>
        <flux:select.option value="all">{{ __('All') }}</flux:select.option>
    </flux:select>

    <div class="mt-4 space-y-2">
        @forelse ($this->reports as $report)
            <flux:card class="p-4 text-sm" wire:key="rep-row-{{ $report->id }}">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div>
                        <flux:link :href="route('admin.reports.show', $report)" class="font-medium" wire:navigate>
                            #{{ $report->id }} · {{ $report->category->value }}
                        </flux:link>
                        <p class="mt-1 text-zinc-500">{{ __('Reporter') }}: {{ $report->reporter->name }} · {{ $report->subject_type }} #{{ $report->subject_id }}</p>
                        <p class="mt-1 text-xs text-zinc-400">{{ $report->created_at->diffForHumans() }}</p>
                    </div>
                    <flux:badge>{{ $report->status->value }}</flux:badge>
                </div>
            </flux:card>
        @empty
            <p class="text-zinc-500">{{ __('No reports in this filter.') }}</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $this->reports->links() }}
    </div>
</div>
