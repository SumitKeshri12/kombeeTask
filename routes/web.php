<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CustomerController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\PermissionController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\SupplierController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return redirect(config('constants.paths.login'));
});

Route::get('/run-command', function () {
    $exitCode = Artisan::call('db:seed');

    return response()->json([
        'message' => 'Command executed',
        'output' => Artisan::output(),
        'exitCode' => $exitCode
    ]);
});

Route::middleware('guest')->group(function () {
    Route::get(config('constants.paths.login'), [AuthController::class, 'showLoginForm'])->name('login');
    Route::post(config('constants.paths.login'), [AuthController::class, 'login']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User management routes
    Route::prefix('users')->middleware([config('constants.roles.super_admin')])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get(config('constants.paths.create'), [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // User export routes
        Route::get('/export', [UserController::class, 'export'])->name('users.export');
        Route::get('/export-pdf', [UserController::class, 'exportPdf'])->name('users.export-pdf');
    });

    // Customer management routes
    Route::prefix('customers')->middleware([config('constants.roles.super_admin_and_admin')])->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get(config('constants.paths.create'), [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::get('/get-data', [CustomerController::class, 'getData'])->name('customers.data');
    });

    // Supplier management routes
    Route::prefix('suppliers')->middleware([config('constants.roles.super_admin_and_admin')])->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get(config('constants.paths.create'), [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
        Route::get('/get-data', [SupplierController::class, 'getData'])->name('suppliers.data');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Roles Management
    Route::resource('roles', RoleController::class);

    // Permissions Management
    Route::resource('permissions', PermissionController::class);

    Route::get('/notifications-test', function () {
        $user = auth()->user();
        $token = $user->createToken('notification-token')->accessToken;
        
        return view('notifications-test', [
            'userId' => $user->id,
            'userToken' => $token
        ]);
    })->name('notifications-test');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
