<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CustomerController;
use App\Models\Staff;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['staff.login.redirect']], function() {
        /* [ADMIN AUTHENTICATION] */
        Route::get('/', [AuthController::class,'index'])->name('admin.index');

        /* [ADMIN LOGIN ROUTES] */
        Route::get('/login', [AuthController::class, 'loginView'])->name('admin.login.form');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
    });

    /* [ADMIN LOGOUT ROUTE] */
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::group(['middleware' => 'staff.permission:'. Staff::POSITION_DIRECTOR], function () {
        /* [TEMPORARY ADMIN REGISTER ROUTE] */
        Route::get('/register', [AuthController::class, 'registerView'])->name('admin.register.form');
        Route::post('/register', [AuthController::class, 'register'])->name('admin.register');

        /** [DASHBOARD ADMIN ROUTES] */
        Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        /* [STAFF ROUTES] */
        Route::get('/staff', [StaffController::class, 'index'])->name('admin.staff.list');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('admin.staff.create.form');
        Route::post('/staff', [StaffController::class, 'store'])->name('admin.staff.store');
        Route::get('/staff/{staff}', [StaffController::class, 'show'])->name('admin.staff.show');
        Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('admin.staff.edit.form');
        Route::post('/staff/{staff}', [StaffController::class, 'update'])->name('admin.staff.update');
        Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('admin.staff.delete');

        /** [SUPPLIER ROUTES] */
        Route::get('/supplier', [SupplierController::class, 'index'])->name('admin.supplier.list');
        Route::get('/supplier/create', [SupplierController::class, 'create'])->name('admin.supplier.create.form');
        Route::post('/supplier', [SupplierController::class, 'store'])->name('admin.supplier.store');
        Route::get('/supplier/{supplier}', [SupplierController::class, 'show'])->name('admin.supplier.show');
        Route::get('/supplier/{supplier}/edit', [SupplierController::class, 'edit'])->name('admin.supplier.edit.form');
        Route::post('/supplier/{supplier}', [SupplierController::class, 'update'])->name('admin.supplier.update');
        Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('admin.supplier.delete');
    });

    Route::group(['middleware' => 'staff.permission:all'], function () {
        /** [DASHBOARD ADMIN ROUTES] */
        Route::get('/dashboard', function() {
            return view('admin.welcome');
        })->name('admin.dashboard');

        /** [USER PROFILE ROUTES] */
        Route::get('/profile', [ProfileController::class, 'index'])->name('admin.user.profile.form');
        Route::post('/profile', [ProfileController::class, 'update'])->name('admin.user.profile.update');
        Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('admin.user.profile.change-password');

        /** [CUSTOMER ROUTES] */
        Route::get('/customer', [CustomerController::class, 'index'])->name('admin.customer.list');
        Route::get('/customer/create', [CustomerController::class, 'create'])->name('admin.customer.create.form');
        Route::post('/customer', [CustomerController::class, 'store'])->name('admin.customer.store');
        Route::get('/customer/{customer}', [CustomerController::class, 'show'])->name('admin.customer.show');
        Route::get('/customer/{customer}/edit', [CustomerController::class, 'edit'])->name('admin.customer.edit.form');
        Route::post('/customer/{customer}', [CustomerController::class, 'update'])->name('admin.customer.update');
        Route::delete('/customer/{customer}', [CustomerController::class, 'destroy'])->name('admin.customer.delete');
    });

});
