<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ServiceLog;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SchedulingController extends Controller
{
    public function index(Request $request)
    {
        $maxSlots = Setting::get('max_slots_per_day', 10);
        $restDays = Setting::get('rest_days', [0]);
        $serviceTypes = ServiceType::orderBy('name')->get();

        // Get calendar data for current month
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $logs = ServiceLog::whereBetween('service_date', [$startDate->toDateString(), $endDate->toDateString()])->get();
        
        $dailyUsage = [];
        foreach ($logs as $log) {
            $date = $log->service_date->toDateString();
            if (!isset($dailyUsage[$date])) $dailyUsage[$date] = 0;
            
            $st = ServiceType::whereRaw('LOWER(name) = ?', [strtolower($log->service_type)])->first();
            $dailyUsage[$date] += ($st->required_slots ?? 1);
        }

        return view('admin.scheduling.index', compact('maxSlots', 'restDays', 'dailyUsage', 'startDate', 'serviceTypes'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'max_slots_per_day' => 'required|integer|min:1',
            'rest_days' => 'nullable|array',
            'rest_days.*' => 'integer|between:0,6',
        ]);

        Setting::set('max_slots_per_day', $validated['max_slots_per_day'], 'integer');
        Setting::set('rest_days', $validated['rest_days'] ?? [], 'array');

        return redirect()->back()->with('success', 'Scheduling settings updated successfully.');
    }

    public function updateServiceWeights(Request $request)
    {
        $validated = $request->validate([
            'weights' => 'required|array',
            'weights.*' => 'integer|min:1',
        ]);

        foreach ($validated['weights'] as $id => $weight) {
            ServiceType::where('id', $id)->update(['required_slots' => $weight]);
        }

        return redirect()->back()->with('success', 'Service weights updated successfully.');
    }
}
