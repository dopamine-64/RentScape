<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Tenant\TenantDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingRequestController;
use Illuminate\Support\Facades\Mail;
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication routes (login, register, etc.)
Auth::routes();

// Default home route
Route::get('/home', [HomeController::class, 'index'])->name('home');

// 🔒 Routes for logged-in users only
Route::middleware(['auth'])->group(function () {

    // ------------------------
    // Dashboards
    // ------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/tenant/dashboard', [TenantDashboardController::class, 'index'])->name('tenant.dashboard');

    // ------------------------
    // Profile Routes
    // ------------------------
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // ------------------------
    // Role switch
    // ------------------------
    Route::post('/switch-role', [RoleController::class, 'switchRole'])->name('switch.role');

    // ------------------------
    // Property Routes
    // ------------------------
    Route::get('/property/create', [PropertyController::class, 'create'])->name('property.create');
    Route::post('/property/store', [PropertyController::class, 'store'])->name('property.store');
    Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])->name('property.destroy');

    // ------------------------
    // Tenant Apply Route
    // ------------------------
    Route::post('/properties/{property}/apply', [BookingRequestController::class, 'apply'])->name('booking.apply');

});
Route::get('/test-mail', function() {
    Mail::raw('Test email from Rentscape', function ($message) {
        $message->to('rafinsammo@gmail.com')
                ->subject('SMTP Test');
    });

    return 'Mail sent (check Gmail inbox/spam)';
});