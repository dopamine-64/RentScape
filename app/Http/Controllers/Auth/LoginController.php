<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Mail\LoginNotification;
use Illuminate\Support\Facades\Mail;

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

    // Send login email
    Mail::to($user->email)->send(new LoginNotification($user));

    // Role logic
    if ($user->role === 'owner' || $user->role === 'both') {
        session(['active_role' => 'owner']);
        return '/dashboard';
    } elseif ($user->role === 'tenant') {
        session(['active_role' => 'tenant']);
        return '/dashboard';
    }

    return '/login';
}



    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $redirectTo = '/dashboard';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}