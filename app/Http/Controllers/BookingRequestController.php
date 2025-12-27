<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingRequest;
use App\Models\Property;

class BookingRequestController extends Controller
{
    public function apply($propertyId)
    {
        $userId = Auth::id();

        // 🔒 Fetch property
        $property = Property::findOrFail($propertyId);

        // 🚫 Owner CANNOT apply to their own property (even if role = tenant)
        if ($property->user_id === $userId) {
            return back()->with('error', 'You cannot apply to your own property.');
        }

        // 🚫 Prevent duplicate application
        $alreadyApplied = BookingRequest::where('property_id', $propertyId)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'You have already applied for this property.');
        }

        // ✅ Create booking request
        BookingRequest::create([
            'property_id' => $propertyId,
            'user_id' => $userId,
            'status' => 'pending',
        ]);

        // ✅ Success message for alert
        return back()->with('success', 'You have successfully applied for this property.');
    }
}