<?php

namespace App\Policies;

use App\Enums\InstallRequestStatus;
use App\Enums\UserRole;
use App\Models\InstallRequest;
use App\Models\Review;
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

        if ($installRequest->status === InstallRequestStatus::SPAM) {
            return false;
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

    /**
     * File a moderation report about this install request.
     */
    public function report(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active) {
            return false;
        }

        return $this->view($user, $installRequest);
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
            return in_array($installRequest->status, [
                InstallRequestStatus::OPEN,
                InstallRequestStatus::MATCHED,
            ], true);
        }

        return $installRequest->user_id === $user->id
            && $installRequest->status === InstallRequestStatus::OPEN;
    }

    /**
     * Admin marks an install request as spam / not a legitimate LIR.
     */
    public function markAsSpam(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active || $user->role !== UserRole::ADMIN) {
            return false;
        }

        if ($installRequest->status === InstallRequestStatus::SPAM) {
            return false;
        }

        return in_array($installRequest->status, [
            InstallRequestStatus::OPEN,
            InstallRequestStatus::MATCHED,
            InstallRequestStatus::CLOSED,
            InstallRequestStatus::CANCELLED,
        ], true);
    }

    public function acceptOffer(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active) {
            return false;
        }

        return $installRequest->user_id === $user->id
            && $installRequest->status === InstallRequestStatus::OPEN;
    }

    /**
     * Seeker marks the install as finished (expert assignment moves to "completed" for experts).
     */
    public function complete(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active) {
            return false;
        }

        return $installRequest->user_id === $user->id
            && $installRequest->status === InstallRequestStatus::MATCHED;
    }

    /**
     * View the coordination message thread (seeker, assigned expert, or admin).
     */
    public function viewMessages(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($installRequest->accepted_offer_id === null) {
            return false;
        }

        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ($installRequest->user_id === $user->id) {
            return true;
        }

        if ($installRequest->status === InstallRequestStatus::SPAM) {
            return false;
        }

        return $this->userIsAcceptedExpert($user, $installRequest);
    }

    /**
     * Post a new message (seeker or assigned expert only, while the job is active).
     */
    public function postMessage(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($installRequest->status !== InstallRequestStatus::MATCHED) {
            return false;
        }

        if ($installRequest->accepted_offer_id === null) {
            return false;
        }

        if ($installRequest->user_id === $user->id) {
            return true;
        }

        return $this->userIsAcceptedExpert($user, $installRequest);
    }

    private function userIsAcceptedExpert(User $user, InstallRequest $installRequest): bool
    {
        if ($user->role !== UserRole::EXPERT) {
            return false;
        }

        $installRequest->loadMissing('acceptedOffer');

        return $installRequest->acceptedOffer !== null
            && (int) $installRequest->acceptedOffer->expert_user_id === (int) $user->id;
    }

    public function expertCanAccessRequest(User $user, InstallRequest $installRequest): bool
    {
        if ($user->role !== UserRole::EXPERT) {
            return false;
        }

        if ($installRequest->status === InstallRequestStatus::SPAM) {
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

    /**
     * Whether the user may submit a post-completion review for this install request.
     */
    public function createReview(User $user, InstallRequest $installRequest): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($installRequest->status !== InstallRequestStatus::CLOSED) {
            return false;
        }

        if ($installRequest->accepted_offer_id === null) {
            return false;
        }

        $installRequest->loadMissing('acceptedOffer');

        if ($installRequest->acceptedOffer === null) {
            return false;
        }

        $seekerId = (int) $installRequest->user_id;
        $expertId = (int) $installRequest->acceptedOffer->expert_user_id;

        if ((int) $user->id !== $seekerId && (int) $user->id !== $expertId) {
            return false;
        }

        $already = Review::query()
            ->where('install_request_id', $installRequest->id)
            ->where('reviewer_id', $user->id)
            ->exists();

        return ! $already;
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
