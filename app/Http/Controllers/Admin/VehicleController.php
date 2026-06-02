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
        $query = Vehicle::query()->where('is_archived', false);

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
        $mechanics = \App\Models\Mechanic::orderBy('name')->get();
        return view('admin.vehicles.create', compact('users', 'serviceTypes', 'mechanics'));
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
            'mechanic_name' => 'nullable|string|max:255',
            'next_service_date' => 'nullable|date',
            'registration_date' => 'nullable|date',
            'status' => 'nullable|in:completed,in progress,scheduled,inactive,overdue',
            'services' => 'nullable|array',
            'services.*.type' => 'required_with:services|string',
            'services.*.cost' => 'required_with:services|numeric|min:0',
            'services.*.status' => 'nullable|string|in:scheduled,in progress,completed',
            'services.*.notes' => 'nullable|string|max:1000',
            'services.*.date' => ['nullable', 'date', new \App\Rules\AvailableServiceDate],
            'total_cost' => 'nullable|numeric|min:0',
        ]);

        // Automatically set owner_name from user_id for display consistency
        $user = User::find($validated['user_id']);
        $validated['owner_name'] = $user->name;

        // If next_service_date is not provided, calculate it based on registration (6 months)
        if (empty($validated['next_service_date'])) {
            $baseDate = !empty($validated['registration_date']) 
                ? Carbon::parse($validated['registration_date']) 
                : Carbon::now();
            
            $nextDate = $baseDate->addMonths(6);
            
            // Ensure next_service_date respects rest days
            $restDays = array_map('intval', \App\Models\Setting::get('rest_days', [0]));
            while (in_array((int)$nextDate->dayOfWeek, $restDays)) {
                $nextDate->addDay();
            }
            
            $validated['next_service_date'] = $nextDate->toDateString();
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
        $this->checkAccess($vehicle);
        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $this->checkAccess($vehicle);
        $users = User::where('role', 'customer')->orderBy('name')->get();
        $serviceTypes = \App\Models\ServiceType::orderBy('name')->get();
        $mechanics = \App\Models\Mechanic::orderBy('name')->get();
        return view('admin.vehicles.edit', compact('vehicle', 'users', 'serviceTypes', 'mechanics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->checkAccess($vehicle);
        $validated = $request->validate([
            'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'make' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|string',
            'color' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'mechanic_name' => 'nullable|string|max:255',
            'next_service_date' => 'nullable|date',
            'registration_date' => 'nullable|date',
            'status' => 'nullable|in:completed,in progress,scheduled,inactive,overdue',
            'services' => 'nullable|array',
            'services.*.type' => 'required_with:services|string',
            'services.*.cost' => 'required_with:services|numeric|min:0',
            'services.*.status' => 'nullable|string|in:scheduled,in progress,completed',
            'services.*.notes' => 'nullable|string|max:1000',
            'services.*.date' => ['nullable', 'date', new \App\Rules\AvailableServiceDate],
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
        $this->checkAccess($vehicle);
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle removed from the fleet.');
    }

    public function quickVerify(Request $request, Vehicle $vehicle)
    {
        $this->checkAccess($vehicle);
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

        // Notify User in Bell
        if ($vehicle->owner) {
            $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />';
            $vehicle->owner->notify(new SystemNotification(
                "Service Completed!",
                "Maintenance for your {$vehicle->make} {$vehicle->model} is finished. Check your history for points earned!",
                $icon,
                route('customer.history.index')
            ));
        }

        return redirect()->back()->with('success', 'Services verified and moved to maintenance history.');
    }

    public function quickStart(Request $request, Vehicle $vehicle)
    {
        $this->checkAccess($vehicle);
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

            // Notify User in Bell
            if ($vehicle->owner) {
                $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />';
                $vehicle->owner->notify(new SystemNotification(
                    "Service In Progress",
                    "We have started working on your {$vehicle->make} {$vehicle->model}. We will notify you once it's ready!",
                    $icon,
                    route('customer.maintenance.timeline')
                ));
            }
            
            return redirect()->back()->with('success', 'Selected services started! Vehicle status updated to In Progress.');
        }

        return redirect()->back()->with('info', 'No valid services were selected to start.');
    }

    /**
     * Display a listing of archived vehicles.
     */
    public function archived(Request $request)
    {
        $query = Vehicle::query()->where('is_archived', true);

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

        return view('admin.vehicles.archived', compact('vehicles'));
    }

    /**
     * Move a vehicle to archives.
     */
    public function archive(Vehicle $vehicle)
    {
        $this->checkAccess($vehicle);
        $vehicle->update(['is_archived' => true]);

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle successfully moved to archives.');
    }

    /**
     * Restore a vehicle from archives.
     */
    public function restore(Vehicle $vehicle)
    {
        $this->checkAccess($vehicle);
        $vehicle->update(['is_archived' => false]);

        return redirect()->route('admin.vehicles.archived')
            ->with('success', 'Vehicle successfully restored to active fleet.');
    }

    /**
     * Display the invoice receipt of the completed vehicle.
     */
    public function receipt(Vehicle $vehicle)
    {
        $this->checkAccess($vehicle);
        return view('admin.vehicles.receipt', compact('vehicle'));
    }

    /**
     * Quick assign a mechanic to a vehicle.
     */
    public function quickAssign(Request $request, Vehicle $vehicle)
    {
        $this->checkAccess($vehicle);
        $validated = $request->validate([
            'mechanic_name' => 'required|string|max:255|exists:mechanics,name',
        ]);

        $vehicle->update(['mechanic_name' => $validated['mechanic_name']]);

        return redirect()->back()->with('success', 'Mechanic assigned successfully.');
    }

    /**
     * Check if the authenticated staff user has access to this vehicle.
     */
    private function checkAccess(Vehicle $vehicle): void
    {
        // No checks needed as access is admin-only now
    }
}
