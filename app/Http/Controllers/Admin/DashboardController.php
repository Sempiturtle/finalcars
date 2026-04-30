<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addDays(7);

        // Basic Statistics
        $stats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'total_vehicles' => Vehicle::count(),
            'total_services' => \App\Models\ServiceLog::count(),
            'points_liability' => User::where('role', 'customer')->sum('loyalty_points'),
        ];

        // Operational Pulse (Queue Depth)
        $queuePulse = [
            'pending' => \App\Models\ServiceLog::where('status', 'scheduled')->count(),
            'active' => \App\Models\ServiceLog::where('status', 'in progress')->count(),
            'completed_today' => \App\Models\ServiceLog::where('status', 'completed')
                ->whereDate('updated_at', $today)
                ->count(),
        ];

        // Platform Velocity: Average turnaround (Created -> Completed) in hours
        $completedServices = \App\Models\ServiceLog::where('status', 'completed')
            ->whereNotNull('updated_at')
            ->whereNotNull('created_at')
            ->take(50)
            ->get();
        
        $avgVelocity = $completedServices->count() > 0 
            ? round($completedServices->avg(function($log) {
                return $log->created_at->diffInHours($log->updated_at);
            }), 1)
            : 0;

        // Maintenance Status Overview
        $maintenanceOverview = [
            'upcoming' => Vehicle::where('next_service_date', '>', $nextWeek)->count(),
            'due_soon' => Vehicle::whereBetween('next_service_date', [$today, $nextWeek])->count(),
            'overdue' => Vehicle::where('next_service_date', '<', $today)
                                ->whereNotIn('status', ['in progress', 'completed'])
                                ->count(),
            'critical_overdue' => Vehicle::criticalOverdue()->count(),
        ];

        // Vehicles Requiring Attention (Overdue)
        $attentionRequired = Vehicle::where('next_service_date', '<', $today)
            ->whereNotIn('status', ['in progress', 'completed'])
            ->orderBy('next_service_date', 'asc')
            ->take(5)
            ->get()
            ->map(function ($vehicle) use ($today) {
                $nextService = Carbon::parse($vehicle->next_service_date);
                return [
                    'id' => $vehicle->id,
                    'plate_number' => $vehicle->plate_number,
                    'make_model' => "{$vehicle->make} {$vehicle->model}",
                    'days_overdue' => $today->diffInDays($nextService),
                    'phone' => $vehicle->owner ? $vehicle->owner->phone : null,
                ];
            });

        // Recent Activity Feed
        $recentActivity = \App\Models\ServiceLog::with('vehicle.owner')
            ->latest('updated_at')
            ->take(8)
            ->get();

        $chartData = [
            'labels' => ['Upcoming', 'Due Soon', 'Overdue'],
            'series' => [
                $maintenanceOverview['upcoming'],
                $maintenanceOverview['due_soon'],
                $maintenanceOverview['overdue']
            ]
        ];

        return view('admin.dashboard', compact(
            'stats', 
            'queuePulse',
            'avgVelocity',
            'maintenanceOverview', 
            'attentionRequired', 
            'recentActivity',
            'chartData'
        ));
    }
}
