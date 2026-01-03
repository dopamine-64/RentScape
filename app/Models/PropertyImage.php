<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',       // allows mass assignment
        'property_id' // foreign key
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
