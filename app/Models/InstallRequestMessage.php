<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallRequestMessage extends Model
{
    protected $fillable = [
        'install_request_id',
        'user_id',
        'body',
    ];

    public function installRequest(): BelongsTo
    {
        return $this->belongsTo(InstallRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
