<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Geofence;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalUsers = User::count();
        $totalGeofences = Geofence::count();

        $dates = [];
        $userCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
            $userCounts[] = User::whereDate('created_at', $date)->count();
        }

        return view('dashboard.index', compact(
            'user',
            'totalUsers',
            'totalGeofences',
            'dates',
            'userCounts'
        ));
    }
}
