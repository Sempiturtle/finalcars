<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vehicles = $user->vehicles()->with(['serviceLogs' => function($query) {
            $query->orderBy('service_date', 'desc');
        }])->get();

        return view('customer.history.index', compact('vehicles'));
    }
}
