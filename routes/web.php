<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\MinhAuthController;
use App\Http\Controllers\Admin\MinhCardController;
use App\Http\Controllers\Admin\PushMessageController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\MinhCardPublicController;
use App\Http\Controllers\NfcCardController;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.employees.index')
        : redirect()->route('login');
});

// Authentication cho quản trị HBG
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/admin/login', [AuthController::class, 'login'])->middleware(['guest', 'throttle:5,1'])->name('admin.login');
Route::post('/admin/logout', [AuthController::class, 'logout'])->middleware('auth')->name('admin.logout');

Route::post('/push-subscribe', [PushSubscriptionController::class, 'store'])
    ->middleware('throttle:30,1')->name('push.subscribe');
Route::post('/push-unsubscribe', [PushSubscriptionController::class, 'destroy'])
    ->middleware('throttle:30,1')->name('push.unsubscribe');

// Authentication riêng cho quản trị cá nhân Minh (/admin/minh)
Route::get('/admin/minh/login', [MinhAuthController::class, 'showLogin'])->name('admin.minh.login')->middleware('guest');
Route::post('/admin/minh/login', [MinhAuthController::class, 'login'])->middleware(['guest', 'throttle:5,1'])->name('admin.minh.login.submit');
Route::post('/admin/minh/logout', [MinhAuthController::class, 'logout'])->middleware('auth')->name('admin.minh.logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::redirect('/', '/admin/employees');
    Route::match(['get', 'post'], 'employees/export-qr-zip', [EmployeeController::class, 'exportQrZip'])->name('employees.export_qr_zip');
    Route::get('employees/{employee}/qr', [EmployeeController::class, 'downloadQr'])->name('employees.download_qr');
    Route::delete('employees/bulk-delete', [EmployeeController::class, 'bulkDestroy'])->name('employees.bulk_destroy');
    Route::resource('employees', EmployeeController::class)->except(['show']);
    Route::resource('minh', MinhCardController::class)->parameters(['minh' => 'card'])->except(['show']);
    Route::resource('notifications', PushMessageController::class)->except(['show']);
    Route::post('notifications/{notification}/send', [PushMessageController::class, 'send'])->name('notifications.send');
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::delete('subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
});

// Route thẻ cá nhân riêng của Minh: /minh/{code}
Route::get('/minh/{code}', [MinhCardPublicController::class, 'show'])
    ->where('code', '[A-Za-z0-9_-]{2,32}')
    ->name('minh.card.show');

Route::get('/{code}/qr', [NfcCardController::class, 'qr'])
    ->where('code', '[A-Za-z0-9_-]{2,32}')
    ->name('card.qr');

// QUAN TRỌNG: public catch-all phải đứng CUỐI file routes.
Route::get('/{code}', [NfcCardController::class, 'show'])
    ->where('code', '[A-Za-z0-9_-]{2,32}')
    ->name('card.show');
