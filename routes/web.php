<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\AuthController;
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

        /* [STAFF ROUTES] START */
        Route::get('/staff', [StaffController::class, 'index'])->name('admin.staff.list');
        Route::get('/staff/create', [StaffController::class, 'create'])->name('admin.staff.create.form');
        Route::post('/staff', [StaffController::class, 'store'])->name('admin.staff.store');
        Route::get('/staff/{staff}', [StaffController::class, 'show'])->name('admin.staff.show');
        Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('admin.staff.edit.form');
        Route::post('/staff/{staff}', [StaffController::class, 'update'])->name('admin.staff.update');
        Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('admin.staff.delete');
    });

    Route::group(['middleware' => 'staff.permission:all'], function () {
        /** [DASHBOARD ADMIN ROUTES] */
        Route::get('/dashboard', function() {
            return view('admin.welcome');
        })->name('admin.dashboard');
    });

});
