<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ServiceType;
use App\Models\ServiceLog;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Fetch featured services
        $featuredServices = ServiceType::all();

        // Fetch public reviews with user relationship
        $reviews = Review::where('is_public', true)
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        // Architectural Metric: Recent Service Count
        $recentServicesCount = ServiceLog::where('created_at', '>=', now()->subDays(30))->count();

        return view('welcome', compact('featuredServices', 'reviews', 'recentServicesCount'));
    }
}
