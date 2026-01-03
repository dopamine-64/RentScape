<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Application;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::count();
        $properties = Property::count();
        $applications = Application::count();
        $pending = Property::where('is_approved', 0)->count();
        $pendingProperties = Property::where('is_approved', 0)->with('owner')->get();

        return view('admin.dashboard', compact('users', 'properties', 'applications', 'pending', 'pendingProperties'));
    }
}
