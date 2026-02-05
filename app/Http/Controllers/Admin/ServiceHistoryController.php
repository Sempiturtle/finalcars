<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\ServiceLog;
use App\Models\Vehicle;

class ServiceHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceLog::with('vehicle');

        // Search Filter (Plate, Owner, Service Type)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('vehicle', function($v) use ($request) {
                    $v->where('plate_number', 'like', '%' . $request->search . '%')
                      ->orWhere('owner_name', 'like', '%' . $request->search . '%');
                })->orWhere('service_type', 'like', '%' . $request->search . '%');
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Vehicle/Customer Filter
        if ($request->filled('vehicle') && $request->vehicle !== 'all') {
            $query->where('vehicle_id', $request->vehicle);
        }

        // Summary Stats (Total, Completed, Total Cost)
        $totalRecords = ServiceLog::count();
        $completedServices = ServiceLog::where('status', 'completed')->count();
        $totalCost = ServiceLog::where('status', 'completed')->sum('cost');

        $services = $query->latest('service_date')->paginate(10);
        
        // For filter dropdown
        $vehicles = Vehicle::orderBy('owner_name')->get();

        return view('admin.service-history.index', compact(
            'services', 
            'totalRecords', 
            'completedServices', 
            'totalCost',
            'vehicles'
        ));
    }
}
