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

const LOGIN_PATH = '/login';
const ROLE_SUPER_ADMIN = 'role:Super Admin';
const ROLE_SUPER_ADMIN_AND_ADMIN = 'role:Super Admin,Admin';
const CREATE_PATH = '/create';

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
    return redirect(LOGIN_PATH);
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
    Route::get(LOGIN_PATH, [AuthController::class, 'showLoginForm'])->name('login');
    Route::post(LOGIN_PATH, [AuthController::class, 'login']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User management routes
    Route::prefix('users')->middleware([ROLE_SUPER_ADMIN])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get(CREATE_PATH, [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Customer management routes
    Route::prefix('customers')->middleware([ROLE_SUPER_ADMIN_AND_ADMIN])->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get(CREATE_PATH, [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::get('/get-data', [CustomerController::class, 'getData'])->name('customers.data');
    });

    // Supplier management routes
    Route::prefix('suppliers')->middleware([ROLE_SUPER_ADMIN_AND_ADMIN])->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get(CREATE_PATH, [SupplierController::class, 'create'])->name('suppliers.create');
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

    // User export route
    Route::get('users/export', [UserController::class, 'export'])
        ->name('users.export')
        ->middleware(ROLE_SUPER_ADMIN);

    // User export PDF route
    Route::get('users/export-pdf', [UserController::class, 'exportPdf'])
        ->name('users.export-pdf')
        ->middleware(ROLE_SUPER_ADMIN);

    Route::get('/notifications-test', function () {
        return view('notifications-test', [
            'userId' => auth()->id()
        ]);
    })->name('notifications-test');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
