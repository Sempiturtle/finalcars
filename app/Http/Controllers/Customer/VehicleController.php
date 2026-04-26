<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Auth::user()->vehicles()->latest()->paginate(10);
        return view('customer.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $serviceTypes = \App\Models\ServiceType::orderBy('name')->get();
        return view('customer.vehicles.create', compact('serviceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:vehicles,plate_number',
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|string',
            'color' => 'nullable|string',
            'registration_date' => 'nullable|date',
            'next_service_date' => 'nullable|date',
            'services' => 'nullable|array',
            'services.*.type' => 'required|string',
            'services.*.mode' => 'required|string',
            'services.*.cost' => 'required|numeric',
            'services.*.date' => ['nullable', 'date', new \App\Rules\AvailableServiceDate],
            'services.*.notes' => 'nullable|string|max:1000',
            'services.*.status' => 'nullable|string',
        ]);

        $user = Auth::user();
        $vehicleData = $validated;
        $vehicleData['user_id'] = $user->id;
        $vehicleData['owner_name'] = $user->name;
        $vehicleData['status'] = 'scheduled'; // Force scheduled status for customers
        $vehicleData['total_cost'] = $request->total_cost ?? 0;

        // Set default next service date if not provided
        if (empty($vehicleData['next_service_date'])) {
            $baseDate = !empty($validated['registration_date']) 
                ? Carbon::parse($validated['registration_date']) 
                : Carbon::now();
            $vehicleData['next_service_date'] = $baseDate->addMonths(6)->toDateString();
        }

        $vehicle = Vehicle::create($vehicleData);

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Your vehicle and its initial service records have been registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $serviceHistory = $vehicle->serviceLogs()->latest('service_date')->get();
        $serviceTypes = \App\Models\ServiceType::orderBy('name')->get();

        return view('customer.vehicles.show', compact('vehicle', 'serviceHistory', 'serviceTypes'));
    }

    /**
     * Log a new service for the vehicle.
     */
    public function logService(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $serviceType = \App\Models\ServiceType::findOrFail($request->service_type_id);
        $requiredSlots = $serviceType->required_slots ?? 1;

        $validated = $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'service_mode' => 'required|in:Walk-in,Towing,Home Service',
            'service_date' => ['required', 'date', new \App\Rules\AvailableServiceDate($requiredSlots)],
            'notes' => 'nullable|string|max:1000',
        ]);

        // Update the services JSON array (the source of truth for Admin Fleet view)
        $services = $vehicle->services ?? [];
        $services[] = [
            'type' => $serviceType->name,
            'mode' => $validated['service_mode'],
            'cost' => $serviceType->base_cost,
            'status' => 'scheduled',
            'date' => $validated['service_date'],
            'notes' => $validated['notes'] ?? null,
        ];
        
        // This update will trigger the Vehicle model's 'updated' observer 
        // which calls syncServiceLogs() to create the database record.
        $vehicle->update([
            'services' => $services,
            'next_service_date' => Carbon::parse($validated['service_date'])->addMonths(6)->toDateString(),
            'status' => 'scheduled'
        ]);

        return redirect()->route('customer.vehicles.show', $vehicle)
            ->with('success', 'Maintenance service for ' . $serviceType->name . ' has been logged and scheduled.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }
        $serviceTypes = \App\Models\ServiceType::orderBy('name')->get();
        return view('customer.vehicles.edit', compact('vehicle', 'serviceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|string',
            'color' => 'nullable|string',
            'registration_date' => 'nullable|date',
            'next_service_date' => 'nullable|date',
            'services' => 'nullable|array',
            'services.*.type' => 'required|string',
            'services.*.mode' => 'required|string',
            'services.*.cost' => 'required|numeric',
            'services.*.date' => ['nullable', 'date', new \App\Rules\AvailableServiceDate],
            'services.*.notes' => 'nullable|string|max:1000',
            'services.*.status' => 'nullable|string',
        ]);

        $validated['status'] = 'scheduled'; // Ensure customers cannot self-complete services
        $vehicle->update($validated);

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Vehicle information and service logs updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $vehicle->delete();

        return redirect()->route('customer.vehicles.index')
            ->with('success', 'Vehicle removed from your account.');
    }

    /**
     * Check availability for a specific date.
     */
    public function fetchMonthAvailability(Request $request)
    {
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $maxSlots = \App\Models\Setting::get('max_slots_per_day', 10);
        $restDays = \App\Models\Setting::get('rest_days', [0]);

        $logs = ServiceLog::whereBetween('service_date', [$startDate->toDateString(), $endDate->toDateString()])->get();
        
        $dailyUsage = [];
        foreach ($logs as $log) {
            $date = $log->service_date->toDateString();
            if (!isset($dailyUsage[$date])) $dailyUsage[$date] = 0;
            $st = \App\Models\ServiceType::whereRaw('LOWER(name) = ?', [strtolower($log->service_type)])->first();
            $dailyUsage[$date] += ($st->required_slots ?? 1);
        }

        $days = [];
        $tempDate = $startDate->copy();
        while ($tempDate <= $endDate) {
            $d = $tempDate->toDateString();
            $used = $dailyUsage[$d] ?? 0;
            $days[$d] = [
                'used' => $used,
                'total' => $maxSlots,
                'available' => !in_array($tempDate->dayOfWeek, $restDays) && $used < $maxSlots && ($tempDate->isFuture() || $tempDate->isToday()),
                'is_rest_day' => in_array($tempDate->dayOfWeek, $restDays)
            ];
            $tempDate->addDay();
        }

        return response()->json($days);
    }

    public function checkAvailability(Request $request)
    {
        $dateStr = $request->query('date');
        if (!$dateStr) return response()->json(['error' => 'No date provided'], 400);

        $date = Carbon::parse($dateStr);
        $maxSlots = \App\Models\Setting::get('max_slots_per_day', 10);
        $restDays = \App\Models\Setting::get('rest_days', [0]);

        $isRestDay = in_array($date->dayOfWeek, $restDays);
        $isPast = $date->isPast() && !$date->isToday();

        // Calculate used slots based on weights
        $existingLogs = ServiceLog::whereDate('service_date', $date->toDateString())->get();
        $usedSlots = 0;
        foreach ($existingLogs as $log) {
            $st = \App\Models\ServiceType::whereRaw('LOWER(name) = ?', [strtolower($log->service_type)])->first();
            $usedSlots += ($st->required_slots ?? 1);
        }

        return response()->json([
            'date' => $date->toDateString(),
            'available' => !$isRestDay && !$isPast && ($usedSlots < $maxSlots),
            'slots_total' => $maxSlots,
            'slots_used' => $usedSlots,
            'slots_remaining' => max(0, $maxSlots - $usedSlots),
            'is_rest_day' => $isRestDay,
            'is_full' => $usedSlots >= $maxSlots,
            'is_past' => $isPast
        ]);
    }
}
