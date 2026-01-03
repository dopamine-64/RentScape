<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
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
        'user_id',
        'owner_id', 
        'tenant_id',
    ];

    /**
     * Relationship with property images
     */
    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }
    public function user()
{
    return $this->belongsTo(User::class, 'user_id'); // assuming your property table has user_id
}
    /**
     * Relationship with the owner
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with booking requests
     */
    public function bookingRequests()
    {
        return $this->hasMany(BookingRequest::class);
    }

    /**
     * Get the active tenant (user_id from booking_requests with status 'active')
     */
    public function activeTenant()
    {
        return $this->bookingRequests()->where('status', 'active')->first();
    }

    /**
     * Compute the property status based on bookings
     * @return string 'active', 'pending', or 'inactive'
     */
    public function status()
    {
        if ($this->bookingRequests()->where('status', 'active')->exists()) {
            return 'inactive'; // property already rented
        }

        if ($this->bookingRequests()->where('status', 'pending')->exists()) {
            return 'pending'; // tenant applied but not yet confirmed
        }

        return 'active'; // no applications
    }

    /**
     * Check if the property is available for rent
     */
    public function isAvailable()
    {
        return $this->status() === 'active';
    }

    /**
     * Check if a given tenant can pay rent for this property
     */
    public function canPayRent($tenantId)
    {
        $activeBooking = $this->bookingRequests()->where('status', 'active')->first();
        return $activeBooking && $activeBooking->user_id === $tenantId;
    }
}
