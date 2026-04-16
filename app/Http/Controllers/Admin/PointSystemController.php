<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SystemNotification;
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

        // Notify User in Bell
        $icon = '<path stroke-linecap = "round" stroke-linejoin = "round" stroke-width = "2" d = "M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />';
        $user->notify(new SystemNotification(
            "Loyalty Points Updated",
            "Your loyalty points balance has been adjusted. You now have {$user->loyalty_points} points.",
            $icon,
            route('customer.rewards.index')
        ));

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
