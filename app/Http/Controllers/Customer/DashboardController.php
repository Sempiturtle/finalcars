<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\ServiceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Fetch all vehicles owned by this user
        $vehicles = $user->vehicles;
        
        // Selected Vehicle Logic
        $vehicleId = $request->get('vehicle_id');
        $selectedVehicle = null;
        
        if ($vehicleId) {
            $selectedVehicle = $vehicles->where('id', $vehicleId)->first();
        }
        
        if (!$selectedVehicle && $vehicles->count() > 0) {
            $selectedVehicle = $vehicles->first();
        }
        
        // Fleet Stats
        $fleetStats = [
            'total_services' => ServiceLog::whereIn('vehicle_id', $vehicles->pluck('id'))->count(),
            'upcoming_services' => $vehicles->where('next_service_date', '>=', Carbon::today())->count(),
            'total_cost' => ServiceLog::whereIn('vehicle_id', $vehicles->pluck('id'))->sum('cost'),
        ];
        
        // Selected Vehicle Specific Stats
        $selectedVehicleStats = null;
        $serviceHistory = collect();
        
        if ($selectedVehicle) {
            $selectedVehicleStats = [
                'total_services' => $selectedVehicle->serviceLogs()->count(),
                'next_due' => $selectedVehicle->next_service_date,
            ];
            
            $serviceHistory = $selectedVehicle->serviceLogs()->latest('service_date')->get();
        }
        
        return view('customer.dashboard', compact(
            'user', 
            'vehicles', 
            'selectedVehicle', 
            'fleetStats', 
            'selectedVehicleStats',
            'serviceHistory'
        ));
    }
}
