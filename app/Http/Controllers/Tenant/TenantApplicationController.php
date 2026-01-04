<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use Illuminate\Support\Facades\Auth;

class TenantApplicationController extends Controller
{
    public function index()
    {
        $applications = BookingRequest::with('property')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('tenant.applications', compact('applications'));
    }
}
