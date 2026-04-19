<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::orderBy('created_at', 'desc')->get();
        
        // Fetch all claims with user and reward info
        $claims = DB::table('reward_user')
            ->join('users', 'reward_user.user_id', '=', 'users.id')
            ->join('rewards', 'reward_user.reward_id', '=', 'rewards.id')
            ->select('users.name as user_name', 'users.email as user_email', 'rewards.name as reward_name', 'rewards.points_cost', 'reward_user.claimed_at', 'reward_user.id as claim_id')
            ->orderBy('reward_user.claimed_at', 'desc')
            ->get();

        return view('admin.rewards.index', compact('rewards', 'claims'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_cost' => 'required|integer|min:0',
        ]);

        Reward::create($request->all());

        return redirect()->back()->with('success', 'New loyalty reward successfully created!');
    }

    public function update(Request $request, Reward $reward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_cost' => 'required|integer|min:0',
        ]);

        $reward->update($request->all());

        return redirect()->back()->with('success', 'Reward information successfully updated!');
    }

    public function destroy(Reward $reward)
    {
        // Check if there are claims
        if ($reward->users()->exists()) {
            // Soft deactivate instead? Or just delete? 
            // Better to delete the reward but maybe warn. User asked for delete.
            $reward->users()->detach();
        }
        
        $reward->delete();

        return redirect()->back()->with('success', 'Reward successfully removed from the system.');
    }

    public function updateStatus(Reward $reward)
    {
        $reward->update(['is_active' => !$reward->is_active]);
        $status = $reward->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Reward successfully {$status}!");
    }
}
