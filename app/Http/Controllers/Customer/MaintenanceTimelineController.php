<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceTimelineController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $vehicles = Vehicle::where('user_id', Auth::id())->get();
        
        // Corrective pass: Ensure DB status matches calculated state
        foreach ($vehicles as $v) {
            if ($v->next_service_date && $v->next_service_date->isPast() && $v->status === 'overdue') {
                $v->syncServiceLogs();
            }
        }

        $timeline = [
            'in_progress' => $vehicles->filter(fn($v) => strtolower($v->calculated_status) === 'in progress'),
            'overdue' => $vehicles->filter(fn($v) => $v->next_service_date && Carbon::parse($v->next_service_date)->isPast() && !Carbon::parse($v->next_service_date)->isToday() && !in_array(strtolower($v->calculated_status), ['in progress', 'completed'])),
            'today' => $vehicles->filter(fn($v) => $v->next_service_date && Carbon::parse($v->next_service_date)->isToday() && strtolower($v->calculated_status) !== 'in progress'),
            'this_week' => $vehicles->filter(fn($v) => $v->next_service_date && Carbon::parse($v->next_service_date)->isBetween($today->copy()->addDay(), $today->copy()->addDays(7)) && strtolower($v->calculated_status) !== 'in progress'),
            'this_month' => $vehicles->filter(fn($v) => $v->next_service_date && Carbon::parse($v->next_service_date)->isBetween($today->copy()->addDays(8), $today->copy()->addDays(30)) && strtolower($v->calculated_status) !== 'in progress'),
            'future' => $vehicles->filter(fn($v) => $v->next_service_date && Carbon::parse($v->next_service_date)->isAfter($today->copy()->addDays(30)) && strtolower($v->calculated_status) !== 'in progress'),
            'unscheduled' => $vehicles->filter(fn($v) => !$v->next_service_date && strtolower($v->calculated_status) !== 'in progress'),
        ];

        return view('customer.timeline', compact('timeline'));
    }
}
