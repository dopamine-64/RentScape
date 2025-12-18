<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel convention)
    protected $table = 'properties';

    // Mass assignable fields
    protected $fillable = [
        'name',           // Property name/title
        'address',        // Property address
        'owner_id',       // Owner user id
        'is_approved',    // Approval status (0 = pending, 1 = approved)
        'price',          // Property price/rent
        'description',    // Property description
        // add other fields you have in the table
    ];

    // Optional: cast fields
    protected $casts = [
        'is_approved' => 'boolean',
    ];

    // Relationship: Owner of the property
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Relationship: Applications for this property
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
