<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\ServiceLog;
use App\Models\User;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        $reportType = $request->get('report_type', 'Summary Report');

        // Summary Cards
        $totalCustomers = User::where('role', 'customer')->count();
        $totalVehicles = Vehicle::count();
        $servicesThisMonth = ServiceLog::whereBetween('service_date', [$startDate, $endDate])->count();
        $totalCostThisMonth = ServiceLog::whereBetween('service_date', [$startDate, $endDate])->sum('cost');

        // System Summary - Vehicle Statistics
        $dueSoonCount = Vehicle::whereBetween('next_service_date', [Carbon::now(), Carbon::now()->addDays(7)])->count();
        $overdueCount = Vehicle::where('next_service_date', '<', Carbon::now())->count();
        $criticalOverdueCount = Vehicle::criticalOverdue()->count();

        // System Summary - Services Statistics
        $totalServicesAllTime = ServiceLog::count();
        $totalCostAllTime = ServiceLog::sum('cost');
        $avgCostPerService = $totalServicesAllTime > 0 ? $totalCostAllTime / $totalServicesAllTime : 0;

        // Recent Activity
        $recentActivityQuery = ServiceLog::with('vehicle')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $recentActivityQuery->whereHas('vehicle', function($query) use ($search) {
                $query->where('plate_number', 'like', "%{$search}%")
                      ->orWhere('make', 'like', "%{$search}%")
                      ->orWhere('model', 'like', "%{$search}%");
            });
        }

        $recentActivity = $recentActivityQuery->take(10)->get();

        return view('admin.reports.index', compact(
            'reportType', 'startDate', 'endDate',
            'totalCustomers', 'totalVehicles', 'servicesThisMonth', 'totalCostThisMonth',
            'dueSoonCount', 'overdueCount', 'criticalOverdueCount', 'totalServicesAllTime', 'totalCostAllTime', 'avgCostPerService',
            'recentActivity'
        ));
    }
}
