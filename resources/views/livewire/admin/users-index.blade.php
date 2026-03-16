<?php

use App\Enums\UserRole;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('components.layouts.app.admin')] class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteUser(int $id): void
    {
        if ($id === auth()->id()) {
            $this->dispatch('notify', type: 'error', message: __('You cannot delete your own account.'));
            return;
        }
        $user = User::findOrFail($id);
        $user->delete();
        $this->dispatch('notify', type: 'success', message: __('User deleted.'));
    }

    public function getUsersProperty()
    {
        return User::query()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            }))
            ->orderBy('name')
            ->paginate(10);
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <flux:heading size="xl">{{ __('Users') }}</flux:heading>
        <flux:link :href="route('admin.users.create')" variant="primary" icon="plus" wire:navigate>
            {{ __('Add user') }}
        </flux:link>
    </div>

    <flux:input
        wire:model.live.debounce.300ms="search"
        :label="__('Search')"
        :placeholder="__('Search by name or email...')"
        icon="magnifying-glass"
        class="max-w-sm"
    />

    <flux:card class="overflow-hidden p-0">
        <flux:table :paginate="$this->users">
            <flux:table.columns>
                <flux:table.column>{{ __('Name') }}</flux:table.column>
                <flux:table.column>{{ __('Email') }}</flux:table.column>
                <flux:table.column>{{ __('Role') }}</flux:table.column>
                <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->users as $user)
                    <flux:table.row :key="$user->id">
                        <flux:table.cell>
                            <span class="font-medium">{{ $user->name }}</span>
                        </flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell>
                            @php
                                $roleLabels = [
                                    UserRole::ADMIN->value => __('Admin'),
                                    UserRole::EXPERT->value => __('Expert'),
                                    UserRole::USER->value => __('User'),
                                ];
                                $roleColors = [
                                    UserRole::ADMIN->value => 'amber',
                                    UserRole::EXPERT->value => 'blue',
                                    UserRole::USER->value => 'zinc',
                                ];
                            @endphp
                            <flux:badge :color="$roleColors[$user->role->value] ?? 'zinc'" size="sm" inset="top bottom">
                                {{ $roleLabels[$user->role->value] ?? $user->role->value }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            <div class="flex items-center justify-end gap-2">
                                <flux:link :href="route('admin.users.edit', $user)" size="sm" icon="pencil" wire:navigate>
                                    {{ __('Edit') }}
                                </flux:link>
                                @if ($user->id !== auth()->id())
                                    <flux:button size="sm" variant="danger" icon="trash" wire:click="deleteUser({{ $user->id }})" wire:confirm="{{ __('Are you sure you want to delete this user?') }}">
                                        {{ __('Delete') }}
                                    </flux:button>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center text-zinc-500 dark:text-zinc-400 py-8">
                            {{ __('No users found.') }}
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
