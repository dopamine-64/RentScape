<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'tenant_id',
        'status',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id'); // Make sure tenant_id is correct
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id'); // Make sure property_id is correct
    }
}
