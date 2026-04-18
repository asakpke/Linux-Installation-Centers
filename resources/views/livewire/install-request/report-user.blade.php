<?php

use App\Enums\ReportCategory;
use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public User $targetUser;

    public string $category = '';

    public string $details = '';

    public function mount(User $targetUser): void
    {
        $this->targetUser = $targetUser;
        $this->authorize('report', $this->targetUser);
        $this->category = ReportCategory::USER_CONDUCT->value;
    }

    public function submitReport(): void
    {
        $this->authorize('report', $this->targetUser);

        $validated = $this->validate([
            'category' => ['required', 'string', 'in:'.implode(',', array_column(ReportCategory::cases(), 'value'))],
            'details' => ['nullable', 'string', 'max:5000'],
        ]);

        Report::create([
            'reporter_id' => Auth::id(),
            'subject_type' => 'user',
            'subject_id' => $this->targetUser->id,
            'category' => $validated['category'],
            'details' => $validated['details'] ?? null,
            'status' => ReportStatus::OPEN,
        ]);

        $this->reset('details');
        $this->dispatch('user-reported');
    }
}; ?>

<flux:card class="mt-6 border-amber-200/80 p-4 dark:border-amber-900/50">
    <flux:heading size="sm">{{ __('Report :name', ['name' => $targetUser->name]) }}</flux:heading>
    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">{{ __('Reports are reviewed by moderators.') }}</p>

    <form wire:submit="submitReport" class="mt-4 max-w-lg space-y-4">
        <flux:select wire:model="category" :label="__('Reason')">
            <flux:select.option value="{{ ReportCategory::USER_CONDUCT->value }}">{{ __('Conduct / professionalism') }}</flux:select.option>
            <flux:select.option value="{{ ReportCategory::HARASSMENT->value }}">{{ __('Harassment or safety concern') }}</flux:select.option>
            <flux:select.option value="{{ ReportCategory::OTHER->value }}">{{ __('Other') }}</flux:select.option>
        </flux:select>
        <flux:textarea wire:model="details" :label="__('Details (optional)')" rows="3" />
        <flux:button type="submit" variant="ghost">{{ __('Submit report') }}</flux:button>
    </form>

    <x-action-message class="mt-2" on="user-reported">
        {{ __('Report submitted. Thank you.') }}
    </x-action-message>
</flux:card>
