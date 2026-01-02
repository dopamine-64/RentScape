<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Tenant\TenantDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BookingRequestController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

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
| Default Home Route
|--------------------------------------------------------------------------
*/

Route::get('/home', [HomeController::class, 'index'])
    ->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Owner / Tenant)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboards
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])
        ->name('owner.dashboard');

    Route::get('/tenant/dashboard', [TenantDashboardController::class, 'index'])
        ->name('tenant.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Role Switch
    |--------------------------------------------------------------------------
    */

    Route::post('/switch-role', [RoleController::class, 'switchRole'])
        ->name('switch.role');

    /*
    |--------------------------------------------------------------------------
    | Property Routes
    |--------------------------------------------------------------------------
    */

    // Create property
    Route::get('/property/create', [PropertyController::class, 'create'])
        ->name('property.create');

    // Store property
    Route::post('/property/store', [PropertyController::class, 'store'])
        ->name('property.store');

    // View all properties (owners + tenants)
    Route::get('/properties', [PropertyController::class, 'index'])
        ->name('properties.index');

    // Delete property (only owner)
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])
        ->name('property.destroy');

    /*
    |--------------------------------------------------------------------------
    | Booking / Apply Routes
    |--------------------------------------------------------------------------
    */

    // Tenant applies for property
    Route::post('/properties/{property}/apply', 
        [BookingRequestController::class, 'apply']
    )->name('booking.apply');

    // Owner selects a tenant
    Route::post('/properties/{property}/select-tenant', 
        [BookingRequestController::class, 'selectTenant']
    )->name('booking.selectTenant');

    // Owner views all applicants for their properties
    Route::get('/owner/applicants', [BookingRequestController::class, 'viewApplicants'])
        ->name('owner.applications.index');
    Route::post('/booking/select-tenant/{property}', [BookingRequestController::class, 'selectTenant'])
    ->name('booking.selectTenant')
    ->middleware('auth');

});
