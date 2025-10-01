<?php

namespace App\Http\Controllers;

use App\Models\Geofence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeofenceController extends Controller
{
    public function index()
    {
        $geofences = Geofence::where('user_id', Auth::id())->get();
        return response()->json($geofences);
    }

    public function store(Request $request)
    {
        $geo = Geofence::create([
            'user_id' => Auth::id(),
            'name' => $request->name ?? 'My Area',
            'coordinates' => $request->coordinates,
        ]);
        return response()->json($geo);
    }

    public function update(Request $request, $id)
    {
        $geo = Geofence::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $geo->update(['coordinates' => $request->coordinates]);
        return response()->json($geo);
    }

    public function destroy($id)
    {
        $geo = Geofence::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $geo->delete();
        return response()->json(['success' => true]);
    }
}

