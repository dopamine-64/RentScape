<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPropertyController extends Controller
{
    // Dashboard for admin
    public function dashboard()
    {
        return view('admin.dashboard'); // make sure this view exists
    }

    // Approve property
    public function approve($propertyId)
    {
        // Add your approval logic here
        // Example: Property::find($propertyId)->update(['approved' => true]);

        return redirect()->back()->with('success', 'Property approved successfully!');
    }
}
