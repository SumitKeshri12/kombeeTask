<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\SupplierController;
// use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public routes
Route::get('/states', [StateController::class, 'index']);
Route::get('/states/{state}/cities', [StateController::class, 'cities']);

// Helper routes
Route::get('/idempotency-key', [UserController::class, 'getIdempotencyKey']);

// Protected routes
Route::middleware(['auth:api', 'idempotent'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Users API
    Route::apiResource('users', UserController::class);
    
    // Roles API
    Route::apiResource('roles', RoleController::class);
    Route::post('roles/{role}/permissions', [RoleController::class, 'attachPermissions']);
    Route::delete('roles/{role}/permissions', [RoleController::class, 'detachPermissions']);
    
    // Permissions API
    Route::apiResource('permissions', PermissionController::class);
    
    // Suppliers
    Route::apiResource('suppliers', SupplierController::class);
    
    // Customers
    // Route::apiResource('customers', CustomerController::class);
    
    // Location
    Route::get('/states/{state}/cities', [StateController::class, 'getCities']);

    // Notifications
    Route::post('/notifications/send', [NotificationController::class, 'send']);
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);

    // Broadcasting authentication
    Broadcast::routes();
});

// Auth routes
Route::middleware('auth:web')->group(function() {
    Route::get('/token', [AuthController::class, 'getToken']);
});
