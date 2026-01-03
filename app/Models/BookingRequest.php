<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
        'status',
    ];

    // Tenant who applied
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Property applied for
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // 💬 Conversation between owner & tenant
    public function conversation()
    {
        return $this->hasOne(Conversation::class, 'tenant_id', 'user_id')
            ->where('property_id', $this->property_id);
    }
}
