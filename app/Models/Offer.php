<?php

namespace App\Models;

use App\Enums\OfferStatus;
use Database\Factories\OfferFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    /** @use HasFactory<OfferFactory> */
    use HasFactory;

    protected $fillable = [
        'install_request_id',
        'expert_user_id',
        'is_free',
        'price_amount',
        'currency',
        'message',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_free' => 'boolean',
            'price_amount' => 'decimal:2',
            'status' => OfferStatus::class,
        ];
    }

    public function installRequest(): BelongsTo
    {
        return $this->belongsTo(InstallRequest::class);
    }

    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_user_id');
    }
}
