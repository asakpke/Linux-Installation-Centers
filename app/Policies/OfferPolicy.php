<?php

namespace App\Policies;

use App\Enums\InstallRequestStatus;
use App\Enums\OfferStatus;
use App\Enums\UserRole;
use App\Models\InstallRequest;
use App\Models\Offer;
use App\Models\User;

class OfferPolicy
{
    public function create(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active || ! $user->hasVerifiedEmail()) {
            return false;
        }

        if ($user->role !== UserRole::EXPERT || ! $user->expertProfileComplete()) {
            return false;
        }

        if ($installRequest->status !== InstallRequestStatus::OPEN) {
            return false;
        }

        return $this->locationMatches($user, $installRequest);
    }

    public function update(User $user, Offer $offer): bool
    {
        if (! $user->is_active) {
            return false;
        }

        return $offer->expert_user_id === $user->id
            && $offer->status === OfferStatus::PENDING
            && $offer->installRequest?->status === InstallRequestStatus::OPEN;
    }

    private function locationMatches(User $expert, InstallRequest $request): bool
    {
        $profile = $expert->expertProfile;

        if (! $profile || ! filled($request->city) || ! filled($request->country)) {
            return false;
        }

        return strcasecmp((string) $profile->city, (string) $request->city) === 0
            && strcasecmp((string) $profile->country, (string) $request->country) === 0;
    }
}
