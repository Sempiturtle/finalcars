<?php
 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('plate_number', 'like', '%' . $request->search . '%')
                  ->orWhere('owner_name', 'like', '%' . $request->search . '%')
                  ->orWhere('make', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $vehicles = $query->latest()->paginate(10);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('role', 'customer')->orderBy('name')->get();
        $serviceTypes = \App\Models\ServiceType::orderBy('name')->get();
        return view('admin.vehicles.create', compact('users', 'serviceTypes'));
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
            'user_id' => 'required|exists:users,id',
            'next_service_date' => 'nullable|date',
            'registration_date' => 'nullable|date',
            'status' => 'nullable|in:completed,in progress,scheduled,inactive,overdue',
            'services' => 'nullable|array',
            'services.*.type' => 'required_with:services|string',
            'services.*.cost' => 'required_with:services|numeric|min:0',
            'services.*.status' => 'nullable|string|in:scheduled,in progress,completed',
            'services.*.notes' => 'nullable|string|max:1000',
            'services.*.date' => 'nullable|date',
            'total_cost' => 'nullable|numeric|min:0',
        ]);

        // Automatically set owner_name from user_id for display consistency
        $user = User::find($validated['user_id']);
        $validated['owner_name'] = $user->name;

        if (empty($validated['next_service_date'])) {
            $baseDate = !empty($validated['registration_date']) 
                ? Carbon::parse($validated['registration_date']) 
                : Carbon::now();
            $validated['next_service_date'] = $baseDate->addMonths(6)->toDateString();
        }

        $vehicle = Vehicle::create($validated);

        // Notify User in Bell
        if ($user) {
            $icon = '<path stroke-linecap = "round" stroke-linejoin = "round" stroke-width = "2" d = "M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />';
            $user->notify(new SystemNotification(
                "New Vehicle Added",
                "A new vehicle ({$vehicle->make} {$vehicle->model}) with plate number {$vehicle->plate_number} has been registered to your account.",
                $icon,
                route('customer.dashboard', ['vehicle_id' => $vehicle->id])
            ));
        }

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle added successfully to the fleet.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $users = User::where('role', 'customer')->orderBy('name')->get();
        $serviceTypes = \App\Models\ServiceType::orderBy('name')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'users', 'serviceTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|string',
            'color' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'next_service_date' => 'nullable|date',
            'registration_date' => 'nullable|date',
            'status' => 'nullable|in:completed,in progress,scheduled,inactive,overdue',
            'services' => 'nullable|array',
            'services.*.type' => 'required_with:services|string',
            'services.*.cost' => 'required_with:services|numeric|min:0',
            'services.*.status' => 'nullable|string|in:scheduled,in progress,completed',
            'services.*.notes' => 'nullable|string|max:1000',
            'services.*.date' => 'nullable|date',
            'total_cost' => 'nullable|numeric|min:0',
        ]);

        // Automatically set owner_name from user_id for display consistency
        $user = User::find($validated['user_id']);
        $validated['owner_name'] = $user->name;

        $oldUserId = $vehicle->user_id;

        // --- MERGE LOGIC TO PREVENT DATA LOSS ---
        // If the admin is saving, we need to make sure we don't overwrite 
        // services added by the customer while the admin had the edit page open.
        $incomingServices = $validated['services'] ?? [];
        $existingServices = $vehicle->fresh()->services ?? [];
        
        // We identify "Customer Added" services by looking for entries 
        // in the DB that aren't present in the incoming form data.
        // For simplicity and safety, we merge them based on type and date.
        foreach ($existingServices as $existing) {
            $found = false;
            foreach ($incomingServices as $incoming) {
                if (($incoming['type'] ?? '') === ($existing['type'] ?? '') && 
                    ($incoming['date'] ?? '') === ($existing['date'] ?? '')) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $incomingServices[] = $existing;
            }
        }
        $validated['services'] = $incomingServices;
        // ----------------------------------------

        $vehicle->update($validated);

        // If user_id changed, notify the new owner
        if ($oldUserId != $vehicle->user_id) {
            $user = User::find($vehicle->user_id);
            if ($user) {
                $icon = '<path stroke-linecap = "round" stroke-linejoin = "round" stroke-width = "2" d = "M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />';
                $user->notify(new SystemNotification(
                    "Vehicle Assigned to You",
                    "A vehicle ({$vehicle->make} {$vehicle->model}) with plate number {$vehicle->plate_number} has been moved to your account.",
                    $icon,
                    route('customer.dashboard', ['vehicle_id' => $vehicle->id])
                ));
            }
        }

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle information updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle removed from the fleet.');
    }

    public function quickVerify(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'completed_indexes' => 'required|array',
            'completed_indexes.*' => 'integer',
            'notes' => 'nullable|string|max:1000',
        ]);

        $services = $vehicle->services ?? [];
        
        foreach ($validated['completed_indexes'] as $index) {
            if (isset($services[$index])) {
                $services[$index]['status'] = 'completed';
                if ($request->filled('notes')) {
                    $services[$index]['notes'] = trim(($services[$index]['notes'] ?? '') . " " . $request->notes);
                }
            }
        }

        $vehicle->update(['services' => $services]);

        return redirect()->back()->with('success', 'Services verified and moved to maintenance history.');
    }

    public function quickStart(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'start_indexes' => 'required|array',
            'start_indexes.*' => 'integer',
        ]);

        $services = $vehicle->services ?? [];
        $anyStarted = false;

        foreach ($validated['start_indexes'] as $index) {
            if (isset($services[$index]) && ($services[$index]['status'] ?? 'scheduled') === 'scheduled') {
                $services[$index]['status'] = 'in progress';
                $anyStarted = true;
            }
        }

        if ($anyStarted) {
            $vehicle->update([
                'services' => $services,
                'status' => 'in progress'
            ]);
            
            // Force refresh and status sync
            $vehicle->refresh();
            $vehicle->syncServiceLogs();
            
            return redirect()->back()->with('success', 'Selected services started! Vehicle status updated to In Progress.');
        }

        return redirect()->back()->with('info', 'No valid services were selected to start.');
    }
}
