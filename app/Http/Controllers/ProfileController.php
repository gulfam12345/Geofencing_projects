<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $imageName = time().'.'.$request->profile_image->extension();
            $request->profile_image->move(public_path('uploads'), $imageName);
            $user->profile_image = $imageName;
        }

        $lat = $user->lat; 
        $lng = $user->lng;

        if ($request->address) {
            $geo = $this->getLatLng($request->address);
            if ($geo) {
                $lat = $geo['lat'];
                $lng = $geo['lng'];
            }
        }

        $user->update([
            'name' => $request->name,
            'address' => $request->address,
            'lat' => $lat,
            'lng' => $lng,
            'profile_image' => $user->profile_image,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    // Delete User
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();
        Auth::logout();
        return redirect()->route('login')->with('success', 'Account deleted successfully!');
    }
    private function getLatLng($address)
    {
        $address = urlencode($address);
        $url = "https://nominatim.openstreetmap.org/search?q={$address}&format=json&limit=1";

        $options = [
            "http" => [
                "header" => "User-Agent: MyApp/1.0\r\n"
            ]
        ];

        $context = stream_context_create($options);

        $response = @file_get_contents($url, false, $context);
        if (!$response) {
            return null;
        }

        $data = json_decode($response, true);

        if (!empty($data)) {
            return [
                'lat' => $data[0]['lat'],
                'lng' => $data[0]['lon']
            ];
        }

        return null;
    }
}
