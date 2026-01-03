<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\BookingRequest;
use App\Models\Property;
use App\Models\Conversation;

class BookingRequestController extends Controller
{
    const STATUS_PENDING = 'pending';
    const STATUS_ACTIVE  = 'active';

    /**
     * Tenant applies for a property
     * → Creates booking request
     * → Creates conversation between owner & tenant
     */
    public function apply($propertyId)
    {
        $userId = Auth::id();

        $property = Property::with('bookingRequests')->findOrFail($propertyId);

        // ❌ Owner cannot apply to own property
        if ($property->user_id === $userId) {
            return back()->with('error', 'You cannot apply to your own property.');
        }

        // ❌ Property already assigned
        if ($property->bookingRequests()
            ->where('status', self::STATUS_ACTIVE)
            ->exists()) {
            return back()->with('error', 'This property is no longer available.');
        }

        // ❌ Duplicate application
        if (BookingRequest::where('property_id', $propertyId)
            ->where('user_id', $userId)
            ->exists()) {
            return back()->with('error', 'You have already applied for this property.');
        }

        DB::transaction(function () use ($property, $userId) {

            // ✅ Create booking request
            $bookingRequest = BookingRequest::create([
                'property_id' => $property->id,
                'user_id'     => $userId,
                'status'      => self::STATUS_PENDING,
            ]);

            // ✅ Create conversation (if not exists)
            Conversation::firstOrCreate([
                'property_id' => $property->id,
                'owner_id'    => $property->user_id,
                'tenant_id'   => $userId,
            ]);
        });

        return back()->with('success', 'Application sent! You can now chat with the owner.');
    }

    /**
     * Owner views applicants for own properties
     */
    public function viewApplicants()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['owner', 'both'])) {
            abort(403, 'Unauthorized');
        }

        $bookingRequests = BookingRequest::with([
                'user',
                'property',
                'conversation'
            ])
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

        // 🔒 Only property owner
        if ($property->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::transaction(function () use ($request, $property) {

            $selectedRequest = BookingRequest::findOrFail($request->booking_request_id);
            $tenantId = $selectedRequest->user_id;

            // ❌ Tenant already active elsewhere
            $alreadyActive = BookingRequest::where('user_id', $tenantId)
                ->where('status', self::STATUS_ACTIVE)
                ->where('id', '!=', $selectedRequest->id)
                ->exists();

            if ($alreadyActive) {
                abort(400, 'This tenant is already selected for another property.');
            }

            // ❌ Remove other applicants for this property
            BookingRequest::where('property_id', $property->id)
                ->where('id', '!=', $selectedRequest->id)
                ->delete();

            // ❌ Remove tenant's other applications
            BookingRequest::where('user_id', $tenantId)
                ->where('property_id', '!=', $property->id)
                ->delete();

            // ✅ Activate tenant
            $selectedRequest->update([
                'status' => self::STATUS_ACTIVE
            ]);

            // ✅ Mark property inactive
            $property->update([
                'status' => 'inactive'
            ]);
        });

        return back()->with('success', 'Tenant selected successfully!');
    }
}
