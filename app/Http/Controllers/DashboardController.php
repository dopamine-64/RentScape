<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeRole = session('active_role', 'owner'); // Default role: owner

        if ($activeRole === 'owner') {
            return redirect()->route('owner.dashboard');
        } elseif ($activeRole === 'tenant') {
            return redirect()->route('tenant.dashboard');
        }

        // Default fallback
        return redirect()->route('home');
    }
}