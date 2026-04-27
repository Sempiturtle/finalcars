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
 
    public function export(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate   = $request->filled('end_date')   ? Carbon::parse($request->end_date)   : Carbon::now()->endOfMonth();

        $fileName = 'service_report_' . $startDate->format('Ymd') . '_to_' . $endDate->format('Ymd') . '.csv';

        $services = ServiceLog::with('vehicle.owner')
            ->whereBetween('service_date', [$startDate, $endDate->endOfDay()])
            ->orderBy('service_date', 'desc')
            ->get();

        $headers = [
            'Content-type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = [
            'Date',
            'Vehicle',
            'Year',
            'Plate Number',
            'Owner',
            'Service Type',
            'Mode',
            'Cost (PHP)',
            'Status',
            'Mechanic',
            'Notes',
        ];

        $callback = function () use ($services, $columns) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM — prevents Excel from garbling special characters (₱, accented names, etc.)
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, $columns);

            foreach ($services as $service) {
                $vehicle  = $service->vehicle;
                $owner    = $vehicle?->owner;

                // Clean date — Carbon is cast to date, format it as a readable string
                $date = $service->service_date instanceof \Carbon\Carbon
                    ? $service->service_date->format('F j, Y')
                    : ($service->service_date ? \Carbon\Carbon::parse($service->service_date)->format('F j, Y') : 'N/A');

                // Humanize slug-style fields (e.g. "oil_change" → "Oil Change", "in_progress" → "In Progress")
                $humanize = fn($value) => $value
                    ? ucwords(str_replace(['_', '-'], ' ', trim($value)))
                    : 'N/A';

                // Cost — plain number (no ₱ symbol so Excel treats it as a number, column header says PHP)
                $cost = is_numeric($service->cost)
                    ? number_format((float) $service->cost, 2, '.', ',')
                    : '0.00';

                fputcsv($file, [
                    $date,
                    $vehicle ? trim($vehicle->make . ' ' . $vehicle->model) : 'N/A',
                    $vehicle?->year ?? 'N/A',
                    $vehicle?->plate_number ?? 'N/A',
                    $owner?->name ?? 'N/A',
                    $humanize($service->service_type),
                    $humanize($service->service_mode),
                    $cost,
                    $humanize($service->status),
                    $service->mechanic_name ? trim($service->mechanic_name) : 'N/A',
                    $service->notes ? strip_tags(trim($service->notes)) : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
 }
