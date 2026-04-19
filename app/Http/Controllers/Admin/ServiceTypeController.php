<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::orderBy('name')->get();
        return view('admin.service-types.index', compact('serviceTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name',
            'description' => 'nullable|string',
            'base_cost' => 'required|numeric|min:0',
            'points_awarded' => 'required|integer|min:0',
            'promo_points_cost' => 'required|integer|min:0',
        ]);

        ServiceType::create($validated);

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service type created successfully.');
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name,' . $serviceType->id,
            'description' => 'nullable|string',
            'base_cost' => 'required|numeric|min:0',
            'points_awarded' => 'required|integer|min:0',
            'promo_points_cost' => 'required|integer|min:0',
        ]);

        $serviceType->update($validated);

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service type updated successfully.');
    }

    public function destroy(ServiceType $serviceType)
    {
        $serviceType->delete();

        return redirect()->route('admin.service-types.index')
            ->with('success', 'Service type deleted successfully.');
    }
}
