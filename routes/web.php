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
        return redirect()->route('customer.landing');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Global Chat Notifications
    Route::get('/chat/unread-count', [\App\Http\Controllers\ChatController::class, 'getUnreadCounts'])->name('chat.unread-count');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('vehicles', \App\Http\Controllers\Admin\VehicleController::class);
    Route::post('/vehicles/{vehicle}/quick-verify', [\App\Http\Controllers\Admin\VehicleController::class, 'quickVerify'])->name('vehicles.quick-verify');
    Route::post('/vehicles/{vehicle}/quick-start', [\App\Http\Controllers\Admin\VehicleController::class, 'quickStart'])->name('vehicles.quick-start');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('service-types', \App\Http\Controllers\Admin\ServiceTypeController::class)->except(['create', 'show', 'edit']);
    
    Route::get('/maintenance', [\App\Http\Controllers\Admin\MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    Route::get('/service-history', [\App\Http\Controllers\Admin\ServiceHistoryController::class, 'index'])->name('service-history.index');
    Route::delete('/service-history/{log}', [\App\Http\Controllers\Admin\ServiceHistoryController::class, 'destroy'])->name('service-history.destroy');
    
    Route::get('/notifications', [\App\Http\Controllers\Admin\EmailNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{vehicle}/send', [\App\Http\Controllers\Admin\EmailNotificationController::class, 'send'])->name('notifications.send');
    
    // Attention Required (Overdue Vehicles)
    Route::get('/attention-required', [\App\Http\Controllers\Admin\EmailNotificationController::class, 'attentionRequired'])->name('attention-required');
    Route::post('/attention-required/notify-all', [\App\Http\Controllers\Admin\EmailNotificationController::class, 'notifyAll'])->name('attention-required.notify-all');
    Route::post('/notifications/{vehicle}/call', [\App\Http\Controllers\Admin\EmailNotificationController::class, 'call'])->name('notifications.call');
    
    // Pointing System
    Route::get('/pointing-system', [\App\Http\Controllers\Admin\PointSystemController::class, 'index'])->name('points.index');
    Route::post('/point-system/adjust/{user?}', [\App\Http\Controllers\Admin\PointSystemController::class, 'adjust'])->name('points.adjust');
    Route::post('/point-system/sync-all', [\App\Http\Controllers\Admin\PointSystemController::class, 'syncAll'])->name('points.sync-all');
    Route::post('/test-email', [\App\Http\Controllers\Admin\TestEmailController::class, 'send'])->name('test-email.send');
    
    // Reward Management
    Route::resource('rewards', \App\Http\Controllers\Admin\RewardController::class);
    Route::patch('/rewards/{reward}/toggle', [\App\Http\Controllers\Admin\RewardController::class, 'updateStatus'])->name('rewards.toggle');
    
    // Timeline Monitoring
    Route::get('/timeline', [\App\Http\Controllers\Admin\MaintenanceTimelineController::class, 'index'])->name('maintenance.timeline');

    // Chat System
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'adminIndex'])->name('chat.index');
    Route::get('/chat/{user}', [\App\Http\Controllers\ChatController::class, 'adminShow'])->name('chat.show');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/fetch/{other_user_id}', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');
    // Scheduling Management
    Route::get('/scheduling', [\App\Http\Controllers\Admin\SchedulingController::class, 'index'])->name('scheduling.index');
    Route::post('/scheduling/settings', [\App\Http\Controllers\Admin\SchedulingController::class, 'updateSettings'])->name('scheduling.update-settings');
    Route::post('/scheduling/weights', [\App\Http\Controllers\Admin\SchedulingController::class, 'updateServiceWeights'])->name('scheduling.update-weights');

    // Audit Logs
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
});

Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/home', [\App\Http\Controllers\Customer\DashboardController::class, 'landing'])->name('landing');
    Route::get('/dashboard', function() { return redirect()->route('customer.landing'); })->name('dashboard');
    Route::get('/timeline', [\App\Http\Controllers\Customer\MaintenanceTimelineController::class, 'index'])->name('maintenance.timeline');

    // Chat System
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'customerIndex'])->name('chat.index');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/fetch', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');

    // Vehicle Management
    Route::resource('vehicles', \App\Http\Controllers\Customer\VehicleController::class);
    Route::get('/vehicles/check-availability', [\App\Http\Controllers\Customer\VehicleController::class, 'checkAvailability'])->name('vehicles.check-availability');
    Route::get('/vehicles/month-availability', [\App\Http\Controllers\Customer\VehicleController::class, 'fetchMonthAvailability'])->name('vehicles.month-availability');
    Route::post('/vehicles/{vehicle}/log-service', [\App\Http\Controllers\Customer\VehicleController::class, 'logService'])->name('vehicles.log-service');

    // Loyalty Rewards
    Route::get('/rewards', [\App\Http\Controllers\Customer\RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/{reward}/claim', [\App\Http\Controllers\Customer\RewardController::class, 'claim'])->name('rewards.claim');

    // Vehicle History
    Route::get('/history', [\App\Http\Controllers\Customer\VehicleHistoryController::class, 'index'])->name('history.index');

    // Customer Profile
    Route::get('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [\App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Customer\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Customer\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Customer\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

require __DIR__.'/auth.php';
