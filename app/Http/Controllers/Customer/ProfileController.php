<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vehicles = $user->vehicles;
        
        // Get recent service history for the profile summary
        $recentServices = $user->vehicles()
            ->with(['serviceLogs' => function($query) {
                $query->latest('service_date')->limit(5);
            }])
            ->get()
            ->flatMap->serviceLogs
            ->sortByDesc('service_date')
            ->take(5);

        return view('customer.profile.index', compact('user', 'vehicles', 'recentServices'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($validated);

        return back()->with('status', 'profile-updated')->with('message', 'Profile information updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated')->with('message', 'Password updated successfully.');
    }
}
