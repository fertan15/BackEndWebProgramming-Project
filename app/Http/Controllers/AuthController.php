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
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Find user by email
        $user = Users::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->with('error', 'Invalid email or password.');
        }

        // Try to verify password
        $passwordValid = false;
        
        try {
            // Try Bcrypt verification first
            $passwordValid = Hash::check($credentials['password'], $user->password_hash);
        } catch (\Exception $e) {
            // If Bcrypt fails, try plain text comparison (for testing/legacy data)
            $passwordValid = ($credentials['password'] === $user->password_hash);
        }

        if (!$passwordValid) {
            return back()->with('error', 'Invalid email or password.');
        }

        // Check if account is banned
        if ($user->account_status === 'banned') {
            return back()->with('error', 'Your account has been banned. Please contact support for assistance.');
        }

        // Check if account is active
        if ($user->account_status !== 'active') {
            return back()->with('error', 'Your account is not active. Please complete registration.');
        }

        // Log the user in using Auth
        Auth::login($user);

        // Set session variables for the application
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_name', $user->name);
        $request->session()->put('is_admin', (bool) $user->is_admin);

        // Check if user's identity was rejected and notify them
        if ($user->identity_status === 'rejected') {
            return redirect('/home')->with('warning', 'Your identity verification was rejected. Please resubmit your documents in your profile.');
        }

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
        // Validate input
        // $validated = $request->validate([
        //     'email' => 'required|email',
        //     'phone' => 'required',
        //     'fullname' => 'required|string',
        //     'username' => 'required|string',
        //     'password' => 'required|min:8|confirmed'
        // ]);

        // Check if email already exists with 'pending' or 'verified' identity_status
        $existingUser = Users::where('email', $request->input('email'))
            ->whereIn('identity_status', ['pending', 'verified'])
            ->first();

        if ($existingUser) {
            return back()->withErrors(['email' => 'This email is already registered. Please use a different email.'])->withInput();
        }

        // Check if email exists with 'unverified' or 'rejected' status
        $unverifiedUser = Users::where('email', $request->input('email'))
            ->whereIn('identity_status', ['unverified', 'rejected'])
            ->first();

        if ($unverifiedUser) {
            // Check if password matches
            if (Hash::check($request->input('password'), $unverifiedUser->password_hash)) {
                // Password matches - allow them to continue registration
                $user = $unverifiedUser;
                
                // If rejected, skip to step 3 to resubmit documents
                if ($user->identity_status === 'rejected') {
                    $request->session()->put('register.user_id', $user->id);
                    $request->session()->put('register.phone', $user->phone_number);
                    $request->session()->put('register.email', $user->email);
                    $request->session()->put('register.name', $user->name);
                    $request->session()->put('register.otp_verified', true); // Skip OTP for rejected users
                    return redirect()->route('register.step3')->with('info', 'Your identity was rejected. Please resubmit your documents.');
                }
            } else {
                // Password doesn't match - email is already in use
                return back()->withErrors(['email' => 'This email has already been used. Please use a different email.'])->withInput();
            }
        } else {
            // Check if username is already taken
            $existingUsername = Users::where('username', $request->input('username'))->first();
            if ($existingUsername) {
                return back()->withErrors(['username' => 'This username is already taken. Please choose a different username.'])->withInput();
            }

            // Create new user in DB with unverified status
            $user = Users::create([
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone'),
                'name' => $request->input('fullname'),
                'password_hash' => Hash::make($request->input('password')),
                'account_status' => 'verify', // Mark as needing verification
                'identity_status' => 'unverified' // Initial identity status
            ]);
        }

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
        
        $userId = session('register.user_id');
        if (!$userId) {
            return redirect()->route('register.step1')->with('error', 'Session expired.');
        }

        $user = Users::find($userId);
        if (!$user) {
            return redirect()->route('register.step1')->with('error', 'User not found.');
        }

        // If identity already verified, redirect to home
        if ($user->identity_status === 'verified') {
            return redirect('/home');
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

        // Validate identity information
        $validated = $request->validate([
            'id_type' => 'required|string|in:driver_license,national_id,passport',
            'id_number' => 'required|string',
            'id_document' => 'required|file|mimes:png,jpg,jpeg,pdf|max:10240', // 10MB max
            'agree_terms' => 'required|accepted'
        ]);

        // Map form values to database enum values
        $identityTypeMap = [
            'driver_license' => 'SIM',
            'national_id' => 'KTP',
            'passport' => 'Passport'
        ];

        $identityType = $identityTypeMap[$request->input('id_type')];
        $identityNumber = $request->input('id_number');

        // Check if identity number already exists
        $existingIdentity = Users::where('identity_number', $identityNumber)
            ->where('id', '!=', $userId)
            ->first();
        
        if ($existingIdentity) {
            return back()->withErrors(['id_number' => 'This ID number is already registered.'])->withInput();
        }

        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('id_document')) {
            $file = $request->file('id_document');
            $fileName = time() . '_' . $userId . '_' . $file->getClientOriginalName();
            
            // Store file in public/register directory
            $file->move(public_path('register'), $fileName);
            
            // Store relative path for database
            $imagePath = 'register/' . $fileName;
        }

        // Update user with identity information and change status to pending
        Users::where('id', $userId)->update([
            'identity_type' => $identityType,
            'identity_number' => $identityNumber,
            'identity_card_url' => $imagePath,
            'identity_status' => 'pending', // Change from unverified to pending
            'account_status' => 'active' // Mark account as active (identity verification pending)
        ]);

        // Log the user in
        Auth::login($user);

        // Set session variables for the application
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_name', $user->name);

        // Clear registration data from session upon completion
        $request->session()->forget(['register.email', 'register.phone', 'register.user_id', 'register.otp_verified']);

        return redirect('/home')->with('success', 'Registration complete! Your identity verification is pending approval.');
    }
}