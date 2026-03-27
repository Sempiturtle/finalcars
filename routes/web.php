<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/announcements', function () {
    return view('announcements');
})->name('announcements.index');

Route::get('/services', function () {
    return view('services');
})->name('services.index');

Route::get('/features', function () {
    return view('features');
})->name('features.index');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin() || $user->isStaff()) {
        return redirect()->route('admin.dashboard');
    }
    if ($user->isCustomer()) {
        return redirect()->route('customer.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('vehicles', \App\Http\Controllers\Admin\VehicleController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('/maintenance', [\App\Http\Controllers\Admin\MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::get('/notifications', [\App\Http\Controllers\Admin\EmailNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{vehicle}/send', [\App\Http\Controllers\Admin\EmailNotificationController::class, 'send'])->name('notifications.send');
    
    // Attention Required (Overdue Vehicles)
    Route::get('/attention-required', [\App\Http\Controllers\Admin\EmailNotificationController::class, 'attentionRequired'])->name('attention-required');
    Route::post('/attention-required/notify-all', [\App\Http\Controllers\Admin\EmailNotificationController::class, 'notifyAll'])->name('attention-required.notify-all');

    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/service-history', [\App\Http\Controllers\Admin\ServiceHistoryController::class, 'index'])->name('service-history.index');
    Route::post('/test-email', [\App\Http\Controllers\Admin\TestEmailController::class, 'send'])->name('test-email.send');
});

Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
