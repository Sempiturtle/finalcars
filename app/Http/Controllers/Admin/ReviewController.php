<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'vehicle', 'serviceLog'])->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'admin_reply' => $request->admin_reply,
            'replied_at' => now(),
        ]);

        // Notify the user
        $user = $review->user;
        $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />';
        $user->notify(new \App\Notifications\SystemNotification(
            "Service Response Received",
            "AutoCheck Admin has responded to your review for your {$review->vehicle->make} {$review->vehicle->model}.",
            $icon,
            route('customer.history.index') // Or where reviews are shown
        ));

        return back()->with('success', 'Your response has been posted and the customer has been notified.');
    }

    public function toggleVisibility(Review $review)
    {
        $review->update(['is_public' => !$review->is_public]);
        return back()->with('success', 'Review visibility updated.');
    }
}
