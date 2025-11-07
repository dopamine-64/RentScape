<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TenantDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'tenant' && $user->role !== 'both') {
            return redirect('/')->with('error', 'Access denied.');
        }
        return view('tenant.dashboard', compact('user'));
    }
}