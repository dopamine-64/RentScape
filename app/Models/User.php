<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'wallet_balance',
    ];

    /**
     * Hidden attributes for serialization
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'wallet_balance' => 'integer',
    ];

    /**
     * Automatically set default wallet balance on user creation
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            // Only assign default if wallet_balance is null or empty
            if (!isset($user->wallet_balance) || $user->wallet_balance === 0) {
                if (in_array($user->role, ['tenant', 'both'])) {
                    $user->wallet_balance = 100000; // Default tenant balance
                } else {
                    $user->wallet_balance = 0;      // Default owner balance
                }
            }
        });
    }

    /**
     * Optional: Check if user is tenant
     */
    public function isTenant(): bool
    {
        return in_array($this->role, ['tenant', 'both']);
    }

    /**
     * Optional: Check if user is owner
     */
    public function isOwner(): bool
    {
        return in_array($this->role, ['owner', 'both']);
    }
}
