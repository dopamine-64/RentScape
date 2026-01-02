<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

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

    public function viewApplicants()
{
    $user = Auth::user();

    if ($user->role !== 'owner' && $user->role !== 'both') {
        return redirect('/')->with('error', 'Access denied.');
    }

    $applications = Application::with('tenant', 'property')->get(); // <-- no owner filter

    return view('owner.applications.index', compact('applications'));
}

}
