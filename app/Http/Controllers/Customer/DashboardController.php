<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\ServiceLog;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function landing(Request $request)
    {
        $user = Auth::user();
        $vehicles = $user->vehicles;
        
        // Featured Services for the Landing Page
        $featuredServices = ServiceType::take(6)->get();
        
        // System Highlights
        $highlights = [
            'total_vehicles' => $vehicles->count(),
            'available_points' => $user->availablePoints(),
            'system_uptime' => '100%',
        ];

        return view('customer.landing', compact('user', 'featuredServices', 'highlights', 'vehicles'));
    }
}
