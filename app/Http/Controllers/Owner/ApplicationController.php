<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    public function accept($id)
    {
        $application = Application::findOrFail($id);
        $application->status = 'accepted';
        $application->save();

        // Automatically reject other applicants for the same property
        Application::where('property_id', $application->property_id)
            ->where('id', '!=', $id)
            ->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Tenant confirmed successfully!');

    }
    public function index()  
    {
     $userId = auth()->id();
     $myProperties = \App\Models\Property::where('owner_id', $userId)->get();
    // Fetch all applications for properties owned by the current owner
    $applications = Application::whereHas('property', function ($query) {
        $query->where('owner_id', auth()->id());
    })->with('tenant', 'property')->get();

    return view('owner.applications.index', compact('applications'));
}

}

