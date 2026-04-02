<?php

namespace App\Policies;

use App\Enums\InstallRequestStatus;
use App\Enums\UserRole;
use App\Models\InstallRequest;
use App\Models\User;

class InstallRequestPolicy
{
    public function viewAny(User $user): bool
    {
        if (! $user->is_active) {
            return false;
        }

        return in_array($user->role, [UserRole::ADMIN, UserRole::USER, UserRole::EXPERT], true);
    }

    public function view(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($installRequest->user_id === $user->id) {
            return true;
        }

        if ($user->role === UserRole::EXPERT) {
            return $this->expertCanAccessRequest($user, $installRequest);
        }

        return false;
    }

    public function create(User $user): bool
    {
        if (! $user->is_active || ! $user->hasVerifiedEmail()) {
            return false;
        }

        return $user->role === UserRole::USER;
    }

    public function update(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active) {
            return false;
        }

        return $installRequest->user_id === $user->id
            && $installRequest->status === InstallRequestStatus::OPEN;
    }

    public function cancel(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->role === UserRole::ADMIN) {
            return in_array($installRequest->status, [InstallRequestStatus::OPEN, InstallRequestStatus::MATCHED], true);
        }

        return $installRequest->user_id === $user->id
            && $installRequest->status === InstallRequestStatus::OPEN;
    }

    public function acceptOffer(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active) {
            return false;
        }

        return $installRequest->user_id === $user->id
            && $installRequest->status === InstallRequestStatus::OPEN;
    }

    public function expertCanAccessRequest(User $user, InstallRequest $installRequest): bool
    {
        if ($user->role !== UserRole::EXPERT) {
            return false;
        }

        if ($installRequest->offers()->where('expert_user_id', $user->id)->exists()) {
            return true;
        }

        if ($installRequest->status !== InstallRequestStatus::OPEN) {
            return false;
        }

        return $this->locationMatchesExpert($user, $installRequest);
    }

    private function locationMatchesExpert(User $expert, InstallRequest $request): bool
    {
        $profile = $expert->expertProfile;

        if (! $profile || ! filled($request->city) || ! filled($request->country)) {
            return false;
        }

        return strcasecmp((string) $profile->city, (string) $request->city) === 0
            && strcasecmp((string) $profile->country, (string) $request->country) === 0;
    }
}
