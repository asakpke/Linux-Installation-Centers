<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function view(User $user, Review $review): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        if ((int) $review->reviewer_id === (int) $user->id || (int) $review->reviewee_id === (int) $user->id) {
            return true;
        }

        $reviewee = $review->reviewee;

        return $reviewee
            && $reviewee->public_profile_enabled
            && filled($reviewee->public_slug);
    }
}
