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
            'total_services' => Vehicle::all()->sum(function ($vehicle) {
                return is_array($vehicle->services) ? count($vehicle->services) : 0;
            }),
            'emails_sent' => 1250, // Placeholder
        ];

        // Maintenance Status Overview
        $maintenanceOverview = [
            'upcoming' => Vehicle::where('next_service_date', '>', $nextWeek)->count(),
            'due_soon' => Vehicle::whereBetween('next_service_date', [$today, $nextWeek])->count(),
            'overdue' => Vehicle::where('next_service_date', '<', $today)->count(),
        ];

        // Vehicles Requiring Attention (Overdue)
        $attentionRequired = Vehicle::where('next_service_date', '<', $today)
            ->orderBy('next_service_date', 'asc')
            ->take(5)
            ->get()
            ->map(function ($vehicle) use ($today) {
                $nextService = Carbon::parse($vehicle->next_service_date);
                return [
                    'plate_number' => $vehicle->plate_number,
                    'make_model' => "{$vehicle->make} {$vehicle->model}",
                    'days_overdue' => $today->diffInDays($nextService),
                ];
            });

        // Recent Services
        // We'll simulate recent services by getting vehicles with services and sorting by updated_at
        $recentServices = Vehicle::whereNotNull('services')
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->flatMap(function ($vehicle) {
                return collect($vehicle->services)->map(function ($service) use ($vehicle) {
                    return [
                        'vehicle' => "{$vehicle->make} {$vehicle->model}",
                        'plate_number' => $vehicle->plate_number,
                        'customer' => $vehicle->owner_name,
                        'service_type' => $service['type'] ?? 'N/A',
                        'date' => $vehicle->updated_at->format('M d, Y'),
                        'status' => 'Completed',
                    ];
                });
            })
            ->sortByDesc('date')
            ->take(5);

        // For the Chart
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
            'maintenanceOverview', 
            'attentionRequired', 
            'recentServices',
            'chartData'
        ));
    }
}
