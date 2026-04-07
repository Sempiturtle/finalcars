<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MaintenanceTimelineController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $vehicles = Vehicle::with('owner')->get();

        $timeline = [
            'overdue' => $vehicles->filter(fn($v) => $v->next_service_date && Carbon::parse($v->next_service_date)->isPast() && !Carbon::parse($v->next_service_date)->isToday()),
            'today' => $vehicles->filter(fn($v) => $v->next_service_date && Carbon::parse($v->next_service_date)->isToday()),
            'this_week' => $vehicles->filter(fn($v) => $v->next_service_date && Carbon::parse($v->next_service_date)->isBetween($today->copy()->addDay(), $today->copy()->addDays(7))),
            'this_month' => $vehicles->filter(fn($v) => $v->next_service_date && Carbon::parse($v->next_service_date)->isBetween($today->copy()->addDays(8), $today->copy()->addDays(30))),
            'future' => $vehicles->filter(fn($v) => $v->next_service_date && Carbon::parse($v->next_service_date)->isAfter($today->copy()->addDays(30))),
            'unscheduled' => $vehicles->filter(fn($v) => !$v->next_service_date),
        ];

        return view('admin.maintenance.timeline', compact('timeline'));
    }
}
