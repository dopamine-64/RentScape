<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect users after login based on their role.
     *
     * @return string
     */
    protected function redirectTo() {
        $user = Auth::user();

        // Default for both role users is Owner
        if ($user->role === 'owner' || $user->role === 'both') {
            session(['active_role' => 'owner']); // store in session
            return '/dashboard';
        } elseif ($user->role === 'tenant') {
            session(['active_role' => 'tenant']);
            return '/dashboard';
        } else {
            return '/login';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
