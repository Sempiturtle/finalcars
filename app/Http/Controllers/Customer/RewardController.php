<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rewards = Reward::where('is_active', true)->orderBy('points_cost', 'asc')->get();
        $claimedRewards = $user->rewards()->orderBy('pivot_claimed_at', 'desc')->get();

        return view('customer.rewards.index', compact('user', 'rewards', 'claimedRewards'));
    }

    public function claim(Reward $reward)
    {
        $user = Auth::user();

        if ($user->availablePoints() < $reward->points_cost) {
            return back()->with('error', 'You do not have enough points to claim this reward.');
        }

        $user->rewards()->attach($reward->id, ['claimed_at' => now()]);

        return back()->with('success', "Congratulations! You have claimed '{$reward->name}'.");
    }
}
