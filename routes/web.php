<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Otp;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Default route: go to home if logged in, otherwise login
Route::get('/', function () {
    if (session()->has('user_id')) {
        return redirect('/home');
    }
    return redirect('/login');
});

// LOGIN Routes (GET to show the form, POST to submit the data)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


// REGISTER Routes (FLATTENED - all paths start with /register)

// Step 1: Basic Details Form (GET /register/step1 to show the form)
Route::get('/register/step1', [AuthController::class, 'showRegisterStep1'])->name('register.step1');

// Step 1 Submission: POST to /register/step2 (moves to step 2 logic)
Route::post('/register/step2', [AuthController::class, 'storeRegisterStep1'])->name('register.storeStep1');

// Step 2: OTP Verification Form (GET /register/step2 to show the form)
// Note: This GET route and the POST route above share the URI /register/step2. This is valid.
Route::get('/register/step2', [AuthController::class, 'showRegisterStep2'])->name('register.step2');
    
// Step 2 Submission: POST to /register/step3 (moves to step 3 logic)
Route::post('/register/step3', [AuthController::class, 'storeRegisterStep2'])->name('register.storeStep2');

// Step 3: Identification/Document Upload Form (GET /register/step3 to show the form)
Route::get('/register/step3', [AuthController::class, 'showRegisterStep3'])->name('register.step3');

// Step 3 Submission: POST to /register/complete (Final Submission to complete registration)
Route::post('/register/complete', [AuthController::class, 'completeRegistration'])->name('register.complete');


// Mock Dashboard Route for successful registration redirect
Route::get('/dashboard', function () {
    return redirect('/home');
});

// Placeholder logout (GET) to quickly clear session and go to login
Route::get('/logout', [HomeController::class, 'logout'])->name('logout.get');

// HOME Routes with auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [HomeController::class, 'logout'])->name('logout');
});

Route::post('/send-otp', [Otp::class, 'sendOtp'])->name('otp.send');