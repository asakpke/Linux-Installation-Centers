<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'public_profile_enabled',
        'public_slug',
        'public_bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
            'public_profile_enabled' => 'boolean',
        ];
    }

    public function expertProfile()
    {
        return $this->hasOne(ExpertProfile::class);
    }

    public function installRequests(): HasMany
    {
        return $this->hasMany(InstallRequest::class);
    }

    public function offersAsExpert(): HasMany
    {
        return $this->hasMany(Offer::class, 'expert_user_id');
    }

    public function reviewsWritten(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'subject');
    }

    public function expertProfileComplete(): bool
    {
        if ($this->role !== UserRole::EXPERT) {
            return false;
        }

        $profile = $this->expertProfile;

        if (! $profile) {
            return false;
        }

        return filled(trim((string) $profile->bio))
            && filled(trim((string) $profile->city))
            && filled(trim((string) $profile->country));
    }

    /**
     * When APP_ENV=local, treat the account as verified so you can develop without mail.
     * Staging/production still require a real verified email.
     */
    public function hasVerifiedEmail(): bool
    {
        if (app()->isLocal()) {
            return true;
        }

        return $this->email_verified_at !== null;
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * First token of display name for public review attribution.
     */
    public function publicReviewFirstName(): string
    {
        $first = Str::of(trim((string) $this->name))->explode(' ')->first();

        return filled($first) ? (string) $first : __('Member');
    }

    public function publicReviewRoleLabel(): string
    {
        return match ($this->role) {
            UserRole::EXPERT => __('Linux expert'),
            UserRole::USER => __('Linux seeker'),
            UserRole::ADMIN => __('Admin'),
        };
    }

    public function publicProfileUrl(): ?string
    {
        if (! $this->public_profile_enabled || ! filled($this->public_slug)) {
            return null;
        }

        return route('profiles.show', ['public_slug' => $this->public_slug], absolute: false);
    }
}
