<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str; 
use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.registration');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'address' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,

        ]);

        $otp = substr(preg_replace('/[^0-9]/', '', Str::random(10).'1234567890'), 0, 6);

        Otp::create([
            'user_id'    => $user->id,
            'otp_code'   => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpMail($otp));

        return redirect()->route('login.otp.form')
                         ->with('success', 'Account created! OTP sent to your email.')
                         ->with('email', $user->email);
    }

    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function sendLoginOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = substr(preg_replace('/[^0-9]/', '', Str::random(10).'1234567890'), 0, 6);

        Otp::where('user_id', $user->id)->delete();

        Otp::create([
            'user_id'    => $user->id,
            'otp_code'   => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpMail($otp));

        return redirect()->route('login.otp.form')->with([
            'success' => 'OTP sent to your email',
            'email'   => $user->email,
        ]);
    }

    public function showLoginOtpForm(Request $request)
{
    $email = session('email');
    return view('auth.login-otp', compact('email'));
}


    public function verifyLoginOtp(Request $request)
{
    $request->validate([
        'email'    => 'required|email|exists:users,email',
        'otp_code' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    $otp = Otp::where('user_id', $user->id)
              ->where('otp_code', $request->otp_code)
              ->where('expires_at', '>', now())
              ->first();

    if (!$otp) {
        return back()->withErrors('Invalid or expired OTP.');
    }

    $otp->delete();

    Auth::login($user);

    $request->session()->regenerate();

    $request->session()->put('last_activity_time', now()->timestamp);

    return redirect()->route('dashboard')->with('success', 'Logged in successfully!');
}

public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login.form')->with('success', 'Logged out successfully.');
}

    
}
