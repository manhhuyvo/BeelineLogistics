<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StaffController;

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

    /* [STAFF ROUTES] START */
    Route::get('/staff', [StaffController::class, 'index'])->name('admin.staff.list');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('admin.staff.create.form');
    Route::post('/staff', [StaffController::class, 'store'])->name('admin.staff.store');
    Route::get('/staff/{staff}', [StaffController::class, 'show'])->name('admin.staff.show');
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('admin.staff.edit.form');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('admin.staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('admin.staff.delete');
    /* [STAFF ROUTES] END */

});
