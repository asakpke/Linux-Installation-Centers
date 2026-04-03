<?php

namespace App\Models;

use App\Enums\InstallRequestStatus;
use App\Enums\OfferStatus;
use Database\Factories\InstallRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class InstallRequest extends Model
{
    /** @use HasFactory<InstallRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'city',
        'country',
        'region',
        'hardware_notes',
        'status',
        'accepted_offer_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => InstallRequestStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function pendingOffers(): HasMany
    {
        return $this->offers()->where('status', OfferStatus::PENDING);
    }

    public function acceptedOffer(): BelongsTo
    {
        return $this->belongsTo(Offer::class, 'accepted_offer_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(InstallRequestMessage::class);
    }

    public function isOpen(): bool
    {
        return $this->status === InstallRequestStatus::OPEN;
    }

    public function acceptOffer(Offer $offer): void
    {
        if ($offer->install_request_id !== $this->id) {
            throw new \InvalidArgumentException('Offer does not belong to this request.');
        }

        if ($offer->status !== OfferStatus::PENDING) {
            throw new \InvalidArgumentException('Only a pending offer can be accepted.');
        }

        if (! $this->isOpen()) {
            throw new \InvalidArgumentException('Request is not open for acceptance.');
        }

        DB::transaction(function () use ($offer) {
            $offer->update(['status' => OfferStatus::ACCEPTED]);

            Offer::query()
                ->where('install_request_id', $this->id)
                ->where('id', '!=', $offer->id)
                ->where('status', OfferStatus::PENDING)
                ->update(['status' => OfferStatus::REJECTED]);

            $this->update([
                'status' => InstallRequestStatus::MATCHED,
                'accepted_offer_id' => $offer->id,
            ]);
        });
    }
}
