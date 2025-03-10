<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\Api\PermissionController;

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

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Users API
    Route::apiResource('users', UserController::class);
    
    // Roles API
    Route::apiResource('roles', RoleController::class);
    
    // Permissions API
    Route::apiResource('permissions', PermissionController::class);
    
    // Suppliers
    Route::apiResource('suppliers', SupplierController::class);
    
    // Customers
    Route::apiResource('customers', CustomerController::class);
    
    // Location
    Route::get('states/{state}/cities', [LocationController::class, 'getCities']);
});

// Auth routes
Route::middleware('auth:web')->group(function() {
    Route::get('/token', [AuthController::class, 'getToken']);
});
