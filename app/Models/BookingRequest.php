<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'status',
    ];

    /**
     * Default values for attributes
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Booking belongs to a property
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Booking belongs to a user (tenant)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Status helpers (optional but clean)
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
