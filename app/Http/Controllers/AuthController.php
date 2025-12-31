<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * AuthController
 * Handles the display logic for the login and multi-step registration views.
 */
class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLogin()
    {
        return view('auth.login');
    }
    
    /**
     * Handle the POST request for login form submission.
     */
    public function login(Request $request)
    {
        // Placeholder auth: store minimal session and redirect to home
        // In a real app, validate credentials and fetch user from DB.
        $email = $request->input('email');
        $name = $request->input('name') ?? ($email ? explode('@', $email)[0] : 'User');

        $request->session()->put('user_id', 1);
        $request->session()->put('user_name', $name);

        return redirect('/home');
    }

    /**
     * Display the first registration step (Basic Details).
     */
    public function showRegisterStep1()
    {
        // In a real app, you might load saved form data from the session here
        return view('auth.register_step1');
    }

    /**
     * Handle the submission of the first registration step (Basic Details).
     */
    public function storeRegisterStep1(Request $request)
    {
        // // Validate input
        // $validated = $request->validate([
        //     'email' => 'required|email|unique:users,email',
        //     'phone' => 'required|unique:users,phone_number',
        //     'name' => 'required|string',
        //     'password' => 'required|min:8'
        // ]);

        // Create temporary user in DB with pending status (not fully verified yet)
        $user = Users::create([
            'username' => $request->input('username'), // Using email as username
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone'),
            'name' => $request->input('fullname'),
            'password_hash' => Hash::make($request->input('password')),
            'account_status' => 'verify' // Mark as needing verification
        ]);
        dump("User created with ID: " . $user->id);

        // Store user ID in session for the next step
        $request->session()->put('register.user_id', $user->id);
        $request->session()->put('register.phone', $user->phone_number);
        $request->session()->put('register.email', $user->email);
        $request->session()->put('register.name', $user->name);

        
        // Redirect to the next step (Step 2)
        return redirect()->route('register.step2');
    }

    /**
     * Display the second registration step (OTP Verification).
     */
    public function showRegisterStep2()
    {
        // Check if user_id exists in session (came from step 1)
        if (!session()->has('register.user_id')) {
            return redirect()->route('register.step1')->with('error', 'Please complete Step 1 first.');
        }

        
        return view('auth.register_step2');
    }
    
    /**
     * Handle the submission of the second registration step (OTP Verification).
     */
    public function storeRegisterStep2(Request $request)
    {
        // Validate OTP input
        $request->validate([
            'otp_code' => 'required|string|size:6'
        ]);

        $userId = session('register.user_id');
        
        if (!$userId) {
            return redirect()->route('register.step1')->with('error', 'Session expired. Please start over.');
        }

        $user = Users::find($userId);
        
        if (!$user) {
            return redirect()->route('register.step1')->with('error', 'User not found.');
        }

        // Verify OTP
        $submittedOtp = $request->input('otp_code');
        
        // Check if OTP matches and hasn't expired
        if ($user->otp_code !== $submittedOtp) {
            return back()->with('error', 'Invalid OTP code. Please try again.');
        }

        if ($user->otp_expires_at && Carbon::now()->isAfter($user->otp_expires_at)) {
            return back()->with('error', 'OTP code has expired. Please request a new one.');
        }

        // OTP verified successfully - clear OTP from DB
        Users::where('id', $userId)->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'account_status' => 'active' // Update status to pending verification
        ]);

        // Mark as OTP verified in session
        $request->session()->put('register.otp_verified', true);
        
        // Redirect to the next step (Step 3)
        return redirect()->route('register.step3');
    }

    /**
     * Display the third registration step (Identity Verification).
     */
    public function showRegisterStep3()
    {
        // Check if OTP was verified
        if (!session('register.otp_verified')) {
            return redirect()->route('register.step2')->with('error', 'Please verify OTP first.');
        }
        
        return view('auth.register_step3');
    }

    /**
     * Handle the final registration submission (or mock dashboard redirect).
     */
    public function completeRegistration(Request $request)
    {
        $userId = session('register.user_id');
        
        if (!$userId) {
            return redirect()->route('register.step1')->with('error', 'Session expired.');
        }

        $user = Users::find($userId);
        
        if (!$user) {
            return redirect()->route('register.step1')->with('error', 'User not found.');
        }

        // Handle file uploads for identity verification if needed
        // For now, mark account as active
        Users::where('id', $userId)->update([
            'account_status' => 'active'
        ]);

        // Log the user in
        Auth::login($user);

        // Clear registration data from session upon completion
        $request->session()->forget(['register.email', 'register.phone', 'register.user_id', 'register.otp_verified']);

        return redirect('/home');
    }
}