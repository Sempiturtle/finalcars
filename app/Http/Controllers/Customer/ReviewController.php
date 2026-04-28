<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ServiceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_log_id' => 'required|exists:service_logs,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $serviceLog = ServiceLog::findOrFail($validated['service_log_id']);

        // Security Check: Ensure the user owns the vehicle/service log
        if ($serviceLog->vehicle->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Prevent duplicate reviews
        if ($serviceLog->review()->exists()) {
            return response()->json(['message' => 'Review already submitted'], 422);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'service_log_id' => $serviceLog->id,
            'vehicle_id' => $serviceLog->vehicle_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_public' => true,
        ]);

        // Notify Admin
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            $customerName = Auth::user()->name;
            $stars = str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating);
            $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />';
            $admin->notify(new \App\Notifications\SystemNotification(
                "New Review: {$stars}",
                "{$customerName} left a rating for their {$serviceLog->service_type} service.",
                $icon,
                route('admin.service-history.index')
            ));
        }

        return response()->json([
            'message' => 'Review submitted successfully!',
            'review' => $review
        ]);
    }
}
