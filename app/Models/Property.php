<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    // Add all the fields you want to fill via $fillable
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
        'available_date',
        'lease_term',
        'security_deposit',
        'parking',
        'laundry',
        'pet_policy',
        'furnished',
        'contact_email',
        'contact_phone',
        'owner_id'
    ];

    // Relationship with images
    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }
    // app/Models/Property.php

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bookingRequests()
    {
        return $this->hasMany(BookingRequest::class);
    }
}
