<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ExpertProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'location',
        'website',
        'hourly_rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
