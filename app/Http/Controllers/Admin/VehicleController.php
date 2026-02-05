<?php
 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\User;
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
        return view('admin.vehicles.create', compact('users'));
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
            'mechanic_name' => 'nullable|string',
            'next_service_date' => 'nullable|date',
            'registration_date' => 'nullable|date',
            'status' => 'required|in:completed,in progress,scheduled,inactive,overdue',
            'services' => 'nullable|array',
            'services.*.type' => 'required_with:services|string',
            'services.*.cost' => 'required_with:services|numeric|min:0',
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
        return view('admin.vehicles.edit', compact('vehicle', 'users'));
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
            'mechanic_name' => 'nullable|string',
            'next_service_date' => 'nullable|date',
            'registration_date' => 'nullable|date',
            'status' => 'required|in:completed,in progress,scheduled,inactive,overdue',
            'services' => 'nullable|array',
            'services.*.type' => 'required_with:services|string',
            'services.*.cost' => 'required_with:services|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
        ]);

        // Automatically set owner_name from user_id for display consistency
        $user = User::find($validated['user_id']);
        $validated['owner_name'] = $user->name;

        $vehicle->update($validated);

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
}
