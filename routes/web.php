<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Tenant\TenantDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PropertyController; // Add this

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (login, register, etc.)
Auth::routes();

// Default home route
Route::get('/home', [HomeController::class, 'index'])->name('home');

// 🔒 Dashboard and Role Routes (only for logged-in users)
Route::middleware(['auth'])->group(function () {

    // Combined dashboard route (redirects based on role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Owner dashboard
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');

    // Tenant dashboard
    Route::get('/tenant/dashboard', [TenantDashboardController::class, 'index'])->name('tenant.dashboard');

    // Role switch route
    Route::post('/switch-role', [RoleController::class, 'switchRole'])->name('switch.role');

    // ------------------------
    // Post Property Routes
    // ------------------------

    // Show create property form
    Route::get('/property/create', [PropertyController::class, 'create'])
        ->name('property.create');

    // Handle form submission
    Route::post('/property/store', [PropertyController::class, 'store'])
        ->name('property.store');
});
