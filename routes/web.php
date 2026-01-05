<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CardController;  
use App\Http\Controllers\AdminController;
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
    Route::post('/logout', [HomeController::class, 'logout'])->name('logout');
});

//ini ga di middleware soalnya kalo ga login masih isah liat2
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::post('/send-otp', [Otp::class, 'sendOtp'])->name('otp.send');

//home
Route::get('/card_sets', [CardController::class, 'showCardSets'])->name('card_sets');

//buat ngambil kartu dari set mana
Route::get('/sets/{setId}/cards', [CardController::class, 'showCards'])->name('set.cards');

// buat nampilin semua kartu
Route::get('/cards', [CardController::class, 'showAllCards'])->name('cards');

// nampilin individu
Route::get('/cards/{cardId}', [CardController::class, 'showCardDetail'])->name('card.detail');

Route::get('/viewprofile', [HomeController::class, 'viewprofile'])->name('view_profile');
// Route::get('/cards',  [HomeController::class, 'showCard'])->name('cards'); 
Route::get('/wishlist', [HomeController::class, 'showWishlist'])->name('wishlist');
Route::post('/wishlist/toggle/{cardId}', [HomeController::class, 'toggleWishlist'])->name('wishlist.toggle');
Route::get('/dashboard', function () {
    // In a real app, this view would be in resources/views/dashboard.blade.php
    return "<h1>Dashboard</h1><p>You have successfully logged in or registered!</p><p>Message: Registration Complete! Welcome to PocketRader.</p>";
});

// Admin area
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/requests', [AdminController::class, 'requests'])->name('requests');
    Route::post('/requests/{id}/approve', [AdminController::class, 'approveRequest'])->name('requests.approve');
    Route::post('/requests/{id}/reject', [AdminController::class, 'rejectRequest'])->name('requests.reject');
    Route::get('/cards/create', [AdminController::class, 'createCardForm'])->name('cards.create');
    Route::post('/cards', [AdminController::class, 'storeCard'])->name('cards.store');
});
