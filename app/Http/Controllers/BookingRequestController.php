<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BookingRequest;
use App\Models\Property;

class BookingRequestController extends Controller
{
    // Use ONLY values that exist in your ENUM column
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE = 'active';

    /**
     * Tenant applies for a property
     */
    public function apply($propertyId)
    {
        $userId = Auth::id();

        $property = Property::with('bookingRequests')->findOrFail($propertyId);

        // Owner cannot apply to own property
        if ($property->user_id === $userId) {
            return back()->with('error', 'You cannot apply to your own property.');
        }

        // Property already has an active tenant
        if ($property->bookingRequests()->where('status', self::STATUS_ACTIVE)->exists()) {
            return back()->with('error', 'This property is no longer available.');
        }

        // Prevent duplicate application
        if (BookingRequest::where('property_id', $propertyId)
            ->where('user_id', $userId)
            ->exists()) {
            return back()->with('error', 'You have already applied for this property.');
        }

        BookingRequest::create([
            'property_id' => $propertyId,
            'user_id'     => $userId,
            'status'      => self::STATUS_PENDING,
        ]);

        return back()->with('success', 'You have successfully applied for this property.');
    }

    /**
     * Owner views applicants ONLY for their own properties
     */
    public function viewApplicants()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['owner', 'both'])) {
            abort(403, 'Unauthorized');
        }

        $bookingRequests = BookingRequest::with(['user', 'property'])
            ->whereHas('property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('owner.applicants', compact('bookingRequests'));
    }

    /**
     * Owner selects a tenant
     */
    public function selectTenant(Request $request, Property $property)
    {
        $request->validate([
            'booking_request_id' => 'required|exists:booking_requests,id',
        ]);

        // 🔒 Only property owner can select tenant
        if ($property->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::transaction(function () use ($request, $property) {

            $selectedRequest = BookingRequest::findOrFail($request->booking_request_id);
            $tenantId = $selectedRequest->user_id;

            // Tenant cannot already be active elsewhere
            $alreadyActiveElsewhere = BookingRequest::where('user_id', $tenantId)
                ->where('status', self::STATUS_ACTIVE)
                ->where('id', '!=', $selectedRequest->id)
                ->exists();

            if ($alreadyActiveElsewhere) {
                abort(400, 'This tenant is already selected for another property.');
            }

            // Remove other applicants for this property
            BookingRequest::where('property_id', $property->id)
                ->where('id', '!=', $selectedRequest->id)
                ->delete();

            // Remove tenant's other applications
            BookingRequest::where('user_id', $tenantId)
                ->where('property_id', '!=', $property->id)
                ->delete();

            // Activate selected tenant
            $selectedRequest->update(['status' => self::STATUS_ACTIVE]);

            // Mark property unavailable
            $property->update(['status' => 'inactive']);
        });

        return back()->with('success', 'Tenant selected successfully!');
    }
}
