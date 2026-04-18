<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\InstallRequest;
use App\Models\Offer;
use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_active && $user->role === UserRole::ADMIN;
    }

    public function view(User $user, Report $report): bool
    {
        return $user->is_active && $user->role === UserRole::ADMIN;
    }

    public function update(User $user, Report $report): bool
    {
        return $user->is_active && $user->role === UserRole::ADMIN;
    }

    public function createForInstallRequest(User $reporter, InstallRequest $installRequest): bool
    {
        if (! $reporter->is_active) {
            return false;
        }

        return $reporter->can('view', $installRequest);
    }

    public function createForUser(User $reporter, User $target): bool
    {
        if (! $reporter->is_active || $reporter->id === $target->id) {
            return false;
        }

        if ($target->role === UserRole::ADMIN) {
            return false;
        }

        if ($reporter->role === UserRole::USER) {
            return Offer::query()
                ->where('expert_user_id', $target->id)
                ->whereHas('installRequest', fn ($q) => $q->where('user_id', $reporter->id))
                ->exists();
        }

        if ($reporter->role === UserRole::EXPERT) {
            return Offer::query()
                ->where('expert_user_id', $reporter->id)
                ->whereHas('installRequest', fn ($q) => $q->where('user_id', $target->id))
                ->exists();
        }

        return false;
    }
}
