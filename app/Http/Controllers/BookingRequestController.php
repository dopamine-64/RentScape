<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingRequest;
use App\Models\Property;

class BookingRequestController extends Controller
{
    /**
     * Tenant applies for a property
     */
    public function apply($propertyId)
    {
        $userId = Auth::id();

        // 🔒 Fetch property with booking requests
        $property = Property::with('bookingRequests')->findOrFail($propertyId);

        // 🚫 Owner cannot apply to own property
        if ($property->user_id === $userId) {
            return back()->with('error', 'You cannot apply to your own property.');
        }

        // 🚫 Property already inactive (tenant selected)
        $hasActiveTenant = $property->bookingRequests
            ->where('status', 'active')
            ->count() > 0;

        if ($hasActiveTenant) {
            return back()->with('error', 'This property is no longer available.');
        }

        // 🚫 Prevent duplicate application
        $alreadyApplied = BookingRequest::where('property_id', $propertyId)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'You have already applied for this property.');
        }

        // ✅ Create booking request (pending by default)
        BookingRequest::create([
            'property_id' => $propertyId,
            'user_id'     => $userId,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'You have successfully applied for this property.');
    }

    /**
     * Owner selects a tenant
     */
    public function selectTenant(Request $request, $propertyId)
    {
        $property = Property::with('bookingRequests')->findOrFail($propertyId);

        // 🔒 Only property owner can select tenant
        if ($property->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'booking_request_id' => 'required|exists:booking_requests,id',
        ]);

        // 🚫 Prevent re-selection
        $alreadySelected = $property->bookingRequests
            ->where('status', 'active')
            ->count() > 0;

        if ($alreadySelected) {
            return back()->with('error', 'A tenant has already been selected.');
        }

        // 🔁 Selected request → active
        BookingRequest::where('id', $request->booking_request_id)
            ->where('property_id', $propertyId)
            ->update(['status' => 'active']);

        // 🔁 All other requests → inactive
        BookingRequest::where('property_id', $propertyId)
            ->where('id', '!=', $request->booking_request_id)
            ->update(['status' => 'inactive']);

        return back()->with('success', 'Tenant selected successfully.');
    }
}
