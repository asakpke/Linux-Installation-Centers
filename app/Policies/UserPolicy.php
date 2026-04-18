<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Whether the reporter may file a conduct report against the target user.
     */
    public function report(User $reporter, User $target): bool
    {
        return app(ReportPolicy::class)->createForUser($reporter, $target);
    }
}
