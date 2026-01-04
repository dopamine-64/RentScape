<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Wishlist; // ✅ THIS LINE FIXES THE ERROR

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'address',
        'city',
        'neighborhood',
        'property_type',
        'bedrooms',
        'bathrooms',
        'area',
        'parking',
        'laundry',
        'pet_policy',
        'furnished',
        'contact_email',
        'contact_phone',
        'user_id'
    ];

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function bookingRequests()
    {
        return $this->hasMany(BookingRequest::class);
    }

    // ❤️ Wishlist relationship
    public function wishlistedBy()
    {
        return $this->hasMany(Wishlist::class);
    }
}
