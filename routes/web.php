<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Caretaker\DashboardController as CaretakerDashboardController;
use App\Http\Controllers\Tenant\PortalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Landing & Auth
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Authenticated Redirect
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin (admin, super_admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('rooms', RoomController::class)
            ->except(['show']);

        Route::resource('tenants', TenantController::class)
            ->except(['show']);

        Route::resource('bills', BillController::class)
            ->only(['index', 'create', 'store', 'destroy'])
            ->parameters(['bills' => 'bill']);
        Route::patch('bills/{bill}/status', [BillController::class, 'updateStatus'])
            ->name('bills.updateStatus');
    });

/*
|--------------------------------------------------------------------------
| Caretaker (caretaker)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:caretaker'])
    ->prefix('caretaker')
    ->name('caretaker.')
    ->group(function () {
        Route::get('/dashboard', [CaretakerDashboardController::class, 'index'])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Tenant (tenant)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:tenant'])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {
        Route::get('/dashboard', [PortalController::class, 'index'])->name('dashboard');
    });