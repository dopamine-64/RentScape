<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel convention)
    protected $table = 'applications';

    // Mass assignable fields
    protected $fillable = [
        'property_id',    // Property being applied for
        'tenant_id',      // User ID of the tenant applying
        'status',         // Application status (e.g., pending, approved, rejected)
        'message',        // Optional message from tenant
        // add other fields you have in the table
    ];

    // Optional: cast fields
    protected $casts = [
        'status' => 'string', // or integer if you use 0/1/2 for status
    ];

    // Relationship: the property this application is for
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Relationship: the tenant who applied
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
}
