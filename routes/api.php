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
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');

// Public routes
Route::get('/states', [StateController::class, 'index'])->name('api.states.index');
Route::get('/states/{state}/cities', [StateController::class, 'cities'])->name('api.states.cities');

// Helper routes
Route::get('/idempotency-key', [UserController::class, 'getIdempotencyKey'])->name('api.idempotency-key');

// Protected routes
Route::middleware(['auth:api', 'idempotent'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    
    // Users API
    Route::apiResource('users', UserController::class)->names([
        'index' => 'api.users.index',
        'store' => 'api.users.store',
        'show' => 'api.users.show',
        'update' => 'api.users.update',
        'destroy' => 'api.users.destroy',
    ]);
    
    // Roles API
    Route::apiResource('roles', RoleController::class)->names([
        'index' => 'api.roles.index',
        'store' => 'api.roles.store',
        'show' => 'api.roles.show',
        'update' => 'api.roles.update',
        'destroy' => 'api.roles.destroy',
    ]);
    Route::post('roles/{role}/permissions', [RoleController::class, 'attachPermissions'])->name('api.roles.permissions.attach');
    Route::delete('roles/{role}/permissions', [RoleController::class, 'detachPermissions'])->name('api.roles.permissions.detach');
    
    // Permissions API
    Route::apiResource('permissions', PermissionController::class)->names([
        'index' => 'api.permissions.index',
        'store' => 'api.permissions.store',
        'show' => 'api.permissions.show',
        'update' => 'api.permissions.update',
        'destroy' => 'api.permissions.destroy',
    ]);
    
    // Suppliers
    Route::apiResource('suppliers', SupplierController::class)->names([
        'index' => 'api.suppliers.index',
        'store' => 'api.suppliers.store',
        'show' => 'api.suppliers.show',
        'update' => 'api.suppliers.update',
        'destroy' => 'api.suppliers.destroy',
    ]);
    
    // Location
    Route::get('/states/{state}/cities', [StateController::class, 'getCities'])->name('api.states.cities.get');

    // Notifications
    Route::post('/notifications/send', [NotificationController::class, 'send'])->name('api.notifications.send');
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('api.notifications.list');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('api.notifications.mark-read');

    // Broadcasting authentication
    Broadcast::routes(['middleware' => ['auth:api']]);
});

// Auth routes
Route::middleware('auth:web')->group(function() {
    Route::get('/token', [AuthController::class, 'getToken'])->name('api.token.get');
});
