<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layouts.app.admin')] class extends Component {
    public ?User $user = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'user';

    public function mount(?User $user = null): void
    {
        $this->user = $user;

        if ($this->user) {
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->role = $this->user->role->value;
        }
    }

    public function save(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . ($this->user?->id ?? 'NULL')],
            'role' => ['required', 'string', 'in:admin,expert,user'],
        ];

        if ($this->user) {
            $rules['password'] = ['nullable', 'string', 'confirmed', Password::defaults()];
        } else {
            $rules['password'] = ['required', 'string', 'confirmed', Password::defaults()];
        }

        $validated = $this->validate($rules);

        if ($this->user) {
            $this->user->name = $validated['name'];
            $this->user->email = $validated['email'];
            $this->user->role = UserRole::from($validated['role']);
            if (! empty($validated['password'])) {
                $this->user->password = Hash::make($validated['password']);
            }
            $this->user->save();
            $this->redirect(route('admin.users.index'), navigate: true);
            return;
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::from($validated['role']),
        ]);

        $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function deleteUser(): void
    {
        if (! $this->user) {
            return;
        }
        if ($this->user->id === auth()->id()) {
            $this->dispatch('notify', type: 'error', message: __('You cannot delete your own account.'));
            return;
        }
        $this->user->delete();
        $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function getIsEditingProperty(): bool
    {
        return $this->user !== null;
    }
}; ?>

<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <flux:heading size="xl">
            {{ $this->isEditing ? __('Edit user') : __('Add user') }}
        </flux:heading>
        <flux:link :href="route('admin.users.index')" variant="ghost" icon="arrow-left" wire:navigate>
            {{ __('Back to users') }}
        </flux:link>
    </div>

    <form wire:submit="save" class="flex max-w-xl flex-col gap-6">
        <flux:input
            wire:model="name"
            :label="__('Name')"
            required
            autocomplete="name"
        />

        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
        />

        <flux:input
            wire:model="password"
            :label="__('Password') . ($this->isEditing ? ' (' . __('leave blank to keep current') . ')' : '')"
            type="password"
            autocomplete="new-password"
            viewable
            :required="!$this->isEditing"
        />

        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password') . ($this->isEditing ? ' (' . __('if changing password') . ')' : '')"
            type="password"
            autocomplete="new-password"
            viewable
            :required="!$this->isEditing"
        />

        <flux:select wire:model="role" :label="__('Role')">
            <flux:select.option value="user">{{ __('User') }}</flux:select.option>
            <flux:select.option value="expert">{{ __('Expert') }}</flux:select.option>
            <flux:select.option value="admin">{{ __('Admin') }}</flux:select.option>
        </flux:select>

        <div class="flex flex-wrap items-center gap-3">
            <flux:button type="submit" variant="primary">
                {{ $this->isEditing ? __('Update user') : __('Create user') }}
            </flux:button>
            @if ($this->isEditing && $this->user && $this->user->id !== auth()->id())
                <flux:button type="button" variant="danger" wire:click="deleteUser" wire:confirm="{{ __('Are you sure you want to delete this user?') }}">
                    {{ __('Delete user') }}
                </flux:button>
            @endif
        </div>
    </form>
</div>
