<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::today();
        
        $vehicles = Vehicle::orderByRaw('CASE WHEN next_service_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('next_service_date', 'asc')
            ->get();

        $schedules = $vehicles->map(function ($vehicle) use ($today) {
            $nextService = $vehicle->next_service_date ? Carbon::parse($vehicle->next_service_date) : null;
            $isOverdue = $nextService ? $nextService->isPast() : false;
            $daysDiff = $nextService ? $today->diffInDays($nextService, false) : null;
            
            return [
                'vehicle_id' => $vehicle->id,
                'plate_number' => $vehicle->plate_number,
                'make_model' => "{$vehicle->make} {$vehicle->model}",
                'owner' => $vehicle->owner_name,
                'next_service_date' => $nextService ? $nextService->format('m/d/Y') : 'Not Scheduled',
                'sort_date' => $nextService ? $nextService->toDateString() : null,
                'is_overdue' => $isOverdue,
                'days_diff' => $daysDiff !== null ? abs($daysDiff) : null,
                'status' => $nextService ? ($isOverdue ? 'Overdue' : 'Scheduled') : 'Unscheduled',
                'description' => $nextService ? 'Regular maintenance service' : 'No service scheduled'
            ];
        });

        return view('admin.maintenance.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
