<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class TenantComplaintController extends Controller
{
    // Show complaints page
    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('tenant.complaints', compact('complaints'));
    }

    // Store new complaint
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Complaint::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Complaint submitted successfully.');
    }
}
