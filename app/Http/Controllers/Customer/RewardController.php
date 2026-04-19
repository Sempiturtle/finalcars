<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->recalculateLoyaltyPoints();
        
        $rewards = Reward::with('serviceType')->where('is_active', true)->orderBy('points_cost', 'asc')->get();
        $claimedRewards = $user->rewards()->orderBy('pivot_claimed_at', 'desc')->get();
        $serviceTypes = ServiceType::orderBy('points_awarded', 'desc')->get();

        // Per-service points breakdown for the customer
        $pointsBreakdown = $user->vehicles()
            ->with(['serviceLogs' => fn($q) => $q->where('status', 'completed')->orderBy('service_date', 'desc')])
            ->get()
            ->flatMap->serviceLogs
            ->map(fn($log) => [
                'service_type'  => $log->service_type,
                'points_earned' => $log->points_earned,
                'cost'          => $log->cost,
                'service_date'  => optional($log->service_date)->format('M d, Y'),
            ]);

        // Map reward id => points the user has available for THAT specific service
        $rewardPoints = $rewards->mapWithKeys(function ($reward) use ($user) {
            if ($reward->serviceType) {
                $pts = $user->pointsForServiceType($reward->serviceType->name);
            } else {
                $pts = $user->availablePoints();
            }
            return [$reward->id => $pts];
        });

        return view('customer.rewards.index', compact(
            'user', 'rewards', 'claimedRewards', 'serviceTypes', 'pointsBreakdown', 'rewardPoints'
        ));
    }

    public function claim(Reward $reward)
    {
        $user = Auth::user();

        // Determine the points pool to check against
        if ($reward->serviceType) {
            $availablePoints = $user->pointsForServiceType($reward->serviceType->name);
            $context = $reward->serviceType->name;
        } else {
            $availablePoints = $user->availablePoints();
            $context = 'any service';
        }

        if ($availablePoints < $reward->points_cost) {
            return back()->with('error',
                "You need {$reward->points_cost} pts from {$context} to claim this reward. You currently have {$availablePoints} pts from that service."
            );
        }

        $user->rewards()->attach($reward->id, ['claimed_at' => now()]);

        return back()->with('success', "Congratulations! You have claimed '{$reward->name}'.");
    }
}
