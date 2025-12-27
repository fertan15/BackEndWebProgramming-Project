<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * AuthController
 * Handles the display logic for the login and multi-step registration views.
 * Since this is a front-end only setup, we are only returning views.
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
        // --- START: New Session Logic ---
        // For demonstration, we'll assume validation passes and save email/phone to session.
        $request->session()->put('register.email', $request->input('email'));
        $request->session()->put('register.phone', $request->input('phone'));
        // --- END: New Session Logic ---
        
        // Redirect to the next step (Step 2)
        return redirect()->route('register.step2');
    }

    /**
     * Display the second registration step (OTP Verification).
     */
    public function showRegisterStep2()
    {
        // In a real app, this would only be accessible after step 1 data is valid
        // We do not need to pass data to the view, as the view will access the session directly.
        return view('auth.register_step2');
    }
    
    /**
     * Handle the submission of the second registration step (OTP Verification).
     */
    public function storeRegisterStep2(Request $request)
    {
        // For now, we are skipping validation and data saving.
        
        // Redirect to the next step (Step 3)
        return redirect()->route('register.step3');
    }

    /**
     * Display the third registration step (Identity Verification).
     */
    public function showRegisterStep3()
    {
        // In a real app, this would only be accessible after step 2 is complete
        return view('auth.register_step3');
    }

    /**
     * Handle the final registration submission (or mock dashboard redirect).
     */
    public function completeRegistration(Request $request)
    {
        // In a real app, you would handle file uploads and save user data here
        // For now, we'll just redirect to a mock dashboard
        
        // Clear registration data from session upon completion
        $request->session()->forget(['register.email', 'register.phone']);

        return redirect('/dashboard');
    }
}