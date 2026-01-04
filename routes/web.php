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
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Tenant\TenantComplaintController;
use App\Http\Controllers\Tenant\TenantApplicationController;


/*
|--------------------------------------------------------------------------
| Web Routes
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
Route::get('/home', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users Only)
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
    | Role Switching
    |--------------------------------------------------------------------------
    */
    Route::post('/switch-role', [RoleController::class, 'switchRole'])
        ->name('switch.role');

    /*
    |--------------------------------------------------------------------------
    | Property Management
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

    // Delete property (owner only)
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])
        ->name('property.destroy');

    /*
    |--------------------------------------------------------------------------
    | Booking Requests (Tenant Apply)
    |--------------------------------------------------------------------------
    */
    Route::post('/properties/{property}/apply', [BookingRequestController::class, 'apply'])
        ->name('booking.apply');

    /*
    |--------------------------------------------------------------------------
    | Wishlist ❤️ (Tenant Feature)
    |--------------------------------------------------------------------------
    */

    // Add / Remove property from wishlist
    Route::post('/wishlist/toggle/{property}', [WishlistController::class, 'toggle'])
        ->name('wishlist.toggle');

    // View tenant wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])
        ->name('wishlist.index');



    Route::get('/tenant/applications', [TenantApplicationController::class, 'index'])
    ->name('tenant.applications');


    Route::middleware(['auth'])->group(function () {
    Route::get('/tenant/complaints', [TenantComplaintController::class, 'index'])
        ->name('tenant.complaints');

    Route::post('/tenant/complaints', [TenantComplaintController::class, 'store'])
        ->name('tenant.complaints.store');
    });



});
