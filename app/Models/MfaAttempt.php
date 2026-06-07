<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MfaAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'success',
        'user_agent',
        'attempted_at',
    ];

    protected $casts = [
        'success' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the MFA attempt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
