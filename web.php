<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Tenant\TenantDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Admin\AdminDashboardController;

// Default Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Auth::routes();

// Default home route
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Protected routes (must be logged in)
Route::middleware(['auth'])->group(function () {

    // Universal Dashboard (redirects based on role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Owner dashboard
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])
        ->name('owner.dashboard');

    // Tenant dashboard
    Route::get('/tenant/dashboard', [TenantDashboardController::class, 'index'])
        ->name('tenant.dashboard');

    // Switch role
    Route::post('/switch-role', [RoleController::class, 'switchRole'])
        ->name('switch.role');
});

// ================================
// ADMIN ROUTES
// ================================
Route::middleware(['auth', 'is_admin'])->group(function () {

    // Admin Dashboard Page
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

});
