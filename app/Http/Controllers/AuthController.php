<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    //

    public function showLoginForm()
    {
        return view('auth.login');
    }

    // public function sendOtp(Request $request)
    // {

    //     $request->validate(['email' => 'required|email']);

    //     $user = User::firstOrCreate(['email' => $request->email]);

    //     $otp = rand(100000, 999999);
    //     $user->otp = $otp;
    //     $user->otp_expires_at = Carbon::now()->addMinutes(10);
    //     $user->save();
    //         // try{
    //         //     Mail::to(
    //         //         "your Otp is Here: $otp",  function ($message) use ($user) {
    //         //             $message->$to($user->email)
    //         //             ->subject('Your OTP Code');
    //         // }  );
    //         // }catch(\Exception $e){
    //         //     return back()->withErrors(['error' => 'Failed to send OTP. Please try again.']);
    //         // }

    //         try{
    //             Mail::to($user->email)->send(new OtpMail($otp));
    //         }
    //         catch(\Exception $e){
    //             \Log::error('faild to send email' . $e->getMessage());


    //         // dd($e->getMessage());
    //             return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
    //         }
    //     return redirect()->route('verify.otp.form')->with('email', $user->email);
    // }

    public function sendOtp(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $user = User::firstOrCreate(['email' => $request->email]);

    $otp = rand(100000, 999999);
    $user->otp = $otp;
    $user->otp_expires_at = Carbon::now()->addMinutes(10);

    if ($user->save()) {
        \Log::info("OTP generated and saved: $otp for user: " . $user->email);
    } else {
        \Log::error("Failed to save OTP for user: " . $user->email);
    }

    try {
        Mail::to($user->email)->send(new OtpMail($otp));
    } catch (\Exception $e) {
        \Log::error('Failed to send email: ' . $e->getMessage());
        return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
    }

    return redirect()->route('verify.otp.form')->with('email', $user->email);
}


public function showOtpForm(){
    return view('auth.verify');
}
    // public function verifyOtp(Request $request)
    // {

    //     $request->validate([
    //         'email' => 'required|email',
    //         'otp'   => 'required|numeric',
    //     ]);

    //     $user = User::Where('email', $request->email)
    //         ->where('otp', $request->otp)
    //         ->where('otp_expires_at', '>', Carbon::now())
    //         ->first();

    //     if ($user) {
    //         auth()->login($user);
    //         return redirect()->route('home');
    //     }

    //     return back()->withErrors(['otp' => 'The Otp is invalid and expried.']);
    // }


    public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required|numeric',
    ]);

    $user = User::where('email', $request->email)
        ->where('otp', $request->otp)
        ->where('otp_expires_at', '>', Carbon::now())
        ->first();

    if ($user) {
        \Log::info("OTP verified for user: " . $user->email);
        auth()->login($user);
        return redirect()->route('home');
    }

    \Log::warning("Failed OTP verification for email: " . $request->email);
    return back()->withErrors(['otp' => 'The OTP is invalid or expired.']);
}

}
