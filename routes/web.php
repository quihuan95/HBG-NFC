<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\NfcCardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.employees.index')
        : redirect()->route('login');
});
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/admin/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:5,1'])->name('admin.login');
Route::post('/admin/logout', [AuthController::class, 'logout'])->middleware('auth')->name('admin.logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::redirect('/', '/admin/employees');
    Route::resource('employees', EmployeeController::class)->except(['show', 'destroy']);
});

// QUAN TRỌNG: public catch-all phải đứng CUỐI file routes.
Route::get('/{code}', [NfcCardController::class, 'show'])
    ->where('code', '[A-Za-z0-9_-]{2,32}')
    ->name('card.show');
