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

        // System Summary - Services Statistics
        $totalServicesAllTime = ServiceLog::count();
        $totalCostAllTime = ServiceLog::sum('cost');
        $avgCostPerService = $totalServicesAllTime > 0 ? $totalCostAllTime / $totalServicesAllTime : 0;

        // Recent Activity
        $recentActivity = ServiceLog::with('vehicle')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'reportType', 'startDate', 'endDate',
            'totalCustomers', 'totalVehicles', 'servicesThisMonth', 'totalCostThisMonth',
            'dueSoonCount', 'overdueCount', 'totalServicesAllTime', 'totalCostAllTime', 'avgCostPerService',
            'recentActivity'
        ));
    }
}
