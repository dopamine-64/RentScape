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
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RentPaymentController;

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
    | Property Routes - ORDER IS IMPORTANT!
    |--------------------------------------------------------------------------
    */

    // Specific routes first (before parameter routes)
    Route::get('/properties/compare', [PropertyController::class, 'compare'])
        ->name('properties.compare');

    Route::get('/properties', [PropertyController::class, 'index'])
        ->name('properties.index');

    Route::get('/property/create', [PropertyController::class, 'create'])
        ->name('property.create');

    Route::post('/property/store', [PropertyController::class, 'store'])
        ->name('property.store');

    // Parameter routes last
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])
        ->name('property.destroy');

    Route::get('/properties/{id}', [PropertyController::class, 'show'])
        ->name('property.show');

    /*
    |--------------------------------------------------------------------------
    | Booking / Apply Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/properties/{property}/apply', 
        [BookingRequestController::class, 'apply']
    )->name('booking.apply');

    Route::post('/properties/{property}/select-tenant', 
        [BookingRequestController::class, 'selectTenant']
    )->name('booking.selectTenant');

    Route::get('/owner/applicants', [BookingRequestController::class, 'viewApplicants'])
        ->name('owner.applications.index');

    /*
    |--------------------------------------------------------------------------
    | Messenger-style Chat Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/messenger', [ConversationController::class, 'messenger'])
        ->name('chats.messenger');

    Route::post('/messages/send', [MessageController::class, 'store'])
        ->name('messages.store');

    Route::post('/chats/{conversation}/toggle-pin', 
        [ConversationController::class, 'togglePin']
    )->name('chats.togglePin');

    /*
    |--------------------------------------------------------------------------
    | Rent Payment Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/rent/pay', [RentPaymentController::class, 'pay'])
        ->name('rent.pay');

    Route::get('/rent/history', [RentPaymentController::class, 'history'])
        ->name('rent.history');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
