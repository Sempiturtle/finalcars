<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PointSystemController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->orderBy('loyalty_points', 'desc')
            ->get()
            ->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'points' => $user->loyalty_points,
                    'status' => $user->calculateActivityLevel(),
                    'vehicle_count' => $user->vehicles()->count(),
                ];
            });

        return view('admin.points.index', compact('customers'));
    }

    public function adjust(Request $request, User $user)
    {
        $request->validate([
            'points' => 'required|integer',
            'action' => 'required|in:add,subtract,set',
        ]);

        switch ($request->action) {
            case 'add':
                $user->increment('loyalty_points', $request->points);
                break;
            case 'subtract':
                $user->decrement('loyalty_points', abs($request->points));
                break;
            case 'set':
                $user->update(['loyalty_points' => $request->points]);
                break;
        }

        return back()->with('success', "Points for {$user->name} have been updated.");
    }

    public function syncAll()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->recalculateLoyaltyPoints();
        }

        return redirect()->route('admin.points.index')->with('success', 'All customer loyalty points have been synchronized with their service history.');
    }
}
