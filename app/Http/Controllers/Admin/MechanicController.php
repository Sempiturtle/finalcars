<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MechanicController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (!auth()->user()->isAdmin()) {
                    return redirect()->route('admin.dashboard')->with('error', 'Only administrators can manage mechanics.');
                }
                return $next($request);
            }),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalMechanics = Mechanic::count();
        $mechanics = Mechanic::orderBy('name')->paginate(10);
        return view('admin.mechanics.index', compact('mechanics', 'totalMechanics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:mechanics',
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Mechanic::create($validated);

        return redirect()->route('admin.mechanics.index')
            ->with('success', 'Mechanic created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mechanic $mechanic)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:mechanics,name,' . $mechanic->id,
            'specialty' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $mechanic->update($validated);

        return redirect()->route('admin.mechanics.index')
            ->with('success', 'Mechanic updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mechanic $mechanic)
    {
        $mechanic->delete();

        return redirect()->route('admin.mechanics.index')
            ->with('success', 'Mechanic deleted successfully.');
    }
}
