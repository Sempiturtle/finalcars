<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Customer;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('welcome');
Route::view('/about', 'about')->name('about');
Route::view('/announcements', 'announcements')->name('announcements.index');
Route::view('/services', 'services')->name('services.index');
Route::view('/features', 'features')->name('features.index');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isCustomer()) {
            return redirect()->route('customer.landing');
        }

        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/unread-count', [ChatController::class, 'getUnreadCounts'])->name('unread-count');
        Route::get('/status', [ChatController::class, 'getOtherUserStatus'])->name('status');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Vehicles
    Route::get('/vehicles/archived-fleet', [Admin\VehicleController::class, 'archived'])->name('vehicles.archived');
    Route::post('/vehicles/{vehicle}/archive', [Admin\VehicleController::class, 'archive'])->name('vehicles.archive');
    Route::post('/vehicles/{vehicle}/restore', [Admin\VehicleController::class, 'restore'])->name('vehicles.restore');
    Route::get('/vehicles/{vehicle}/receipt', [Admin\VehicleController::class, 'receipt'])->name('vehicles.receipt');
    Route::resource('vehicles', Admin\VehicleController::class);
    Route::post('/vehicles/{vehicle}/quick-verify', [Admin\VehicleController::class, 'quickVerify'])->name('vehicles.quick-verify');
    Route::post('/vehicles/{vehicle}/quick-start', [Admin\VehicleController::class, 'quickStart'])->name('vehicles.quick-start');
    Route::post('/vehicles/{vehicle}/quick-assign', [Admin\VehicleController::class, 'quickAssign'])->name('vehicles.quick-assign');

    // Users, Mechanics & Service Types
    Route::resource('users', Admin\UserController::class);
    Route::resource('mechanics', Admin\MechanicController::class);
    Route::resource('service-types', Admin\ServiceTypeController::class)->except(['create', 'show', 'edit']);

    // Maintenance & Reports
    Route::get('/maintenance', [Admin\MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::get('/timeline', [Admin\MaintenanceTimelineController::class, 'index'])->name('maintenance.timeline');
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [Admin\ReportController::class, 'index'])->name('index');
        Route::get('/export', [Admin\ReportController::class, 'export'])->name('export');
    });

    // Service History
    Route::prefix('service-history')->name('service-history.')->group(function () {
        Route::get('/', [Admin\ServiceHistoryController::class, 'index'])->name('index');
        Route::delete('/{log}', [Admin\ServiceHistoryController::class, 'destroy'])->name('destroy');
    });

    // Notifications & Attention
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [Admin\EmailNotificationController::class, 'index'])->name('index');
        Route::post('/{vehicle}/send', [Admin\EmailNotificationController::class, 'send'])->name('send');
        Route::post('/{vehicle}/call', [Admin\EmailNotificationController::class, 'call'])->name('call');
    });
    Route::prefix('attention-required')->name('attention-required.')->group(function () {
        Route::get('/', [Admin\EmailNotificationController::class, 'attentionRequired'])->name('index');
        Route::post('/notify-all', [Admin\EmailNotificationController::class, 'notifyAll'])->name('notify-all');
    });

    // Points System
    Route::prefix('pointing-system')->name('points.')->group(function () {
        Route::get('/', [Admin\PointSystemController::class, 'index'])->name('index');
        Route::post('/adjust/{user?}', [Admin\PointSystemController::class, 'adjust'])->name('adjust');
        Route::post('/sync-all', [Admin\PointSystemController::class, 'syncAll'])->name('sync-all');
    });

    // Rewards
    Route::resource('rewards', Admin\RewardController::class);
    Route::patch('/rewards/{reward}/toggle', [Admin\RewardController::class, 'updateStatus'])->name('rewards.toggle');

    // Scheduling
    Route::prefix('scheduling')->name('scheduling.')->group(function () {
        Route::get('/', [Admin\SchedulingController::class, 'index'])->name('index');
        Route::post('/settings', [Admin\SchedulingController::class, 'updateSettings'])->name('update-settings');
        Route::post('/weights', [Admin\SchedulingController::class, 'updateServiceWeights'])->name('update-weights');
    });

    // Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'adminIndex'])->name('index');
        Route::get('/search', [ChatController::class, 'searchCustomers'])->name('search');
        Route::post('/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::get('/fetch/{other_user_id}', [ChatController::class, 'fetchMessages'])->name('fetch');
        Route::get('/{user}', [ChatController::class, 'adminShow'])->name('show');
    });

    // Audit Logs & Reviews
    Route::get('/audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [Admin\ReviewController::class, 'index'])->name('index');
        Route::post('/{review}/reply', [Admin\ReviewController::class, 'reply'])->name('reply');
        Route::post('/{review}/toggle', [Admin\ReviewController::class, 'toggleVisibility'])->name('toggle');
    });

    // Misc
    Route::post('/test-email', [Admin\TestEmailController::class, 'send'])->name('test-email.send');
});

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/home', [Customer\DashboardController::class, 'landing'])->name('landing');
    Route::get('/dashboard', fn() => redirect()->route('customer.landing'))->name('dashboard');
    Route::get('/timeline', [Customer\MaintenanceTimelineController::class, 'index'])->name('maintenance.timeline');

    // Vehicles
    Route::get('/vehicles/{vehicle}/receipt', [Customer\VehicleController::class, 'receipt'])->name('vehicles.receipt');
    Route::resource('vehicles', Customer\VehicleController::class);
    Route::get('/vehicles/check-availability', [Customer\VehicleController::class, 'checkAvailability'])->name('vehicles.check-availability');
    Route::get('/vehicles/month-availability', [Customer\VehicleController::class, 'fetchMonthAvailability'])->name('vehicles.month-availability');
    Route::post('/vehicles/{vehicle}/log-service', [Customer\VehicleController::class, 'logService'])->name('vehicles.log-service');

    // Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'customerIndex'])->name('index');
        Route::post('/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::get('/fetch', [ChatController::class, 'fetchMessages'])->name('fetch');
    });

    // Rewards
    Route::prefix('rewards')->name('rewards.')->group(function () {
        Route::get('/', [Customer\RewardController::class, 'index'])->name('index');
        Route::post('/{reward}/claim', [Customer\RewardController::class, 'claim'])->name('claim');
    });

    // History, Profile, Notifications, Reviews
    Route::get('/history', [Customer\VehicleHistoryController::class, 'index'])->name('history.index');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [Customer\ProfileController::class, 'index'])->name('index');
        Route::post('/update', [Customer\ProfileController::class, 'update'])->name('update');
        Route::post('/password', [Customer\ProfileController::class, 'updatePassword'])->name('password');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [Customer\NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [Customer\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [Customer\NotificationController::class, 'markAllAsRead'])->name('read-all');
    });

    Route::post('/reviews', [Customer\ReviewController::class, 'store'])->name('reviews.store');
});

require __DIR__ . '/auth.php';
