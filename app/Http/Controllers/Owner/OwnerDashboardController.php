<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'owner' && $user->role !== 'both') {
            return redirect('/')->with('error', 'Access denied.');
        }
        return view('owner.dashboard', compact('user'));
    }
}