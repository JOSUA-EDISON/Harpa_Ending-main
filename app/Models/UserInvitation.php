<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'role',
        'is_used',
        'expires_at',
        'invited_by',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the admin who invited this user.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Check if the invitation has expired.
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the invitation is valid.
     */
    public function isValid()
    {
        return !$this->is_used && !$this->isExpired();
    }
}
