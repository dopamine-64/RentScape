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

    // Link to property
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Link to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}