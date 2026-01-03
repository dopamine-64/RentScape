<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\RentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentPaymentController extends Controller
{
    /**
     * Pay rent using virtual wallet
     */
    public function pay(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $user = Auth::user();
        $activeRole = session('active_role', $user->role);

        // Only tenants can pay
        if ($activeRole !== 'tenant') {
            abort(403, 'Only tenants can pay rent.');
        }

        $property = Property::findOrFail($request->property_id);

        // Get the active booking for this property
        $activeBooking = $property->bookingRequests()
            ->where('status', 'active')
            ->first();

        if (!$activeBooking || $activeBooking->user_id !== $user->id) {
            return back()->with('error', 'You are not assigned as tenant for this property.');
        }

        // Prevent double payment
        $alreadyPaid = RentPayment::where('property_id', $property->id)
            ->where('tenant_id', $user->id)
            ->exists();

        if ($alreadyPaid) {
            return back()->with('error', 'Rent already paid for this property.');
        }

        $owner = $property->owner; // Property owner via user_id

        if (!$owner) {
            return back()->with('error', 'Property owner not found.');
        }

        $amount = $property->price;

        // Check wallet balance before transaction
        if ($user->wallet_balance < $amount) {
            return back()->with('error', 'Insufficient wallet balance.');
        }

        try {
            DB::transaction(function () use ($user, $owner, $property, $amount) {
                // Deduct tenant wallet
                $user->decrement('wallet_balance', $amount);

                // Add to owner wallet
                $owner->increment('wallet_balance', $amount);

                // Record payment
                RentPayment::create([
                    'property_id'    => $property->id,
                    'tenant_id'      => $user->id,
                    'owner_id'       => $owner->id,
                    'amount'         => $amount,
                    'payment_method' => 'wallet',
                    'status'         => 'success',
                    'paid_at'        => now(),
                ]);
            });

            return back()->with('success', 'Rent paid successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    /**
     * Rent payment history for owner / tenant
     */
    public function history()
    {
        $user = Auth::user();
        $role = session('active_role', $user->role);

        if ($role === 'owner') {
            $payments = RentPayment::with(['property', 'tenant'])
                ->where('owner_id', $user->id)
                ->latest('paid_at')
                ->get();
        } else {
            $payments = RentPayment::with(['property', 'owner'])
                ->where('tenant_id', $user->id)
                ->latest('paid_at')
                ->get();
        }

        return view('rent.history', compact('payments', 'role'));
    }
}