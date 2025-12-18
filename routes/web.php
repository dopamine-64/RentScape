<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Tenant\TenantDashboardController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPropertyController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Owner / Tenant / Both)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Home (fallback)
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Unified dashboard (decides owner / tenant / both)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Owner dashboard
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])
        ->name('owner.dashboard');

    // Tenant dashboard
    Route::get('/tenant/dashboard', [TenantDashboardController::class, 'index'])
        ->name('tenant.dashboard');

    // Switch role (owner <-> tenant)
    Route::post('/switch-role', [RoleController::class, 'switchRole'])
        ->name('switch.role');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (is_admin = 1 ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Approve property
        Route::post('/property/{id}/approve', [AdminPropertyController::class, 'approve'])
            ->name('property.approve');

        // Future admin routes go here
        // Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    });
