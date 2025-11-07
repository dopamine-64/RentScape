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
    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user->role === 'owner') {
            return '/owner/dashboard';
        } elseif ($user->role === 'tenant') {
            return '/tenant/dashboard';
        } else {
            return '/home'; // fallback for 'both' or unknown roles
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