<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function switchRole(Request $request)
    {
        $user = Auth::user();
        $requestedRole = $request->input('role');

        // Check if user has permission for the requested role
        if ($user->role === 'both' || $user->role === $requestedRole) {
            session(['active_role' => $requestedRole]);
            return redirect('/dashboard')->with('success', 'Role switched to ' . ucfirst($requestedRole));
        }

        return redirect()->back()->with('error', 'You do not have permission for that role.');
    }
}