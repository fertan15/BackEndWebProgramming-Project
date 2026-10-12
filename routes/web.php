<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\CardController;  
use App\Http\Controllers\AdminController;
use App\Http\Controllers\chatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Otp;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;

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
Route::fallback(function () {
    return redirect('/home'); // or any other default page
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

// Redirect to verification from profile
Route::get('/verify-identity', [AuthController::class, 'redirectToVerification'])->name('verify.identity');

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
Route::post('/savelistings', [CardController::class, 'savelisting'])->middleware('verified.transaction')->name('savelistings');

Route::get('/viewprofile', [HomeController::class, 'viewprofile'])->name('view_profile');
Route::post('/viewprofile', [HomeController::class, 'updateProfile'])->name('update_profile');
// Route::get('/cards',  [HomeController::class, 'showCard'])->name('cards'); 
Route::get('/wishlist', [HomeController::class, 'showWishlist'])->middleware('verified')->name('wishlist');
Route::post('/wishlist/toggle/{cardId}', [HomeController::class, 'toggleWishlist'])->middleware('verified')->name('wishlist.toggle');
Route::get('/inventory', [HomeController::class, 'showInventory'])->name('inventory.index');
Route::post('/inventory/add-listing/{collectionId}', [HomeController::class, 'addListing'])->middleware('verified.transaction')->name('inventory.addListing');
Route::post('/inventory/lock/{collectionId}', [HomeController::class, 'lockCard'])->name('inventory.lock');
Route::post('/inventory/unlock/{collectionId}', [HomeController::class, 'unlockCard'])->name('inventory.unlock');
Route::get('/dashboard', function () {
    // In a real app, this view would be in resources/views/dashboard.blade.php
    return "<h1>Dashboard</h1><p>You have successfully logged in or registered!</p><p>Message: Registration Complete! Welcome to PocketRader.</p>";
});
Route::get('/chat', [chatController::class, 'chat'])->name('chat');
Route::post('/chat/start', [chatController::class, 'start'])->name('chat.start');
Route::get('/chat/{chat}/messages', [chatController::class, 'messages'])->name('chat.messages');
Route::post('/chat/{chat}/message', [chatController::class, 'sendMessage'])->name('chat.message.send');

// Notifications routes
// Index is accessible to everyone (guests see system-wide, users see personal + system-wide)
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

// Actions require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
    // buat orders (history)
    Route::get('/orders', [CheckoutController::class, 'history'])->name('orders');
    Route::get('/orders/{orderItem}/invoice', [CheckoutController::class, 'invoice'])->name('orders.invoice');
    // Transaction history (buying and selling)
    Route::get('/history', [HomeController::class, 'showHistory'])->name('history');
});

//route top up
Route::get('/topup', [TopUpController::class, 'show'])->name('topup.show');
Route::post('/topup/snap', [TopUpController::class, 'getSnapToken'])->name('topup.snap');
Route::post('/topup/notification', [TopUpController::class, 'handleNotification']);

//route dashboard
Route::get('/search', [DashboardController::class, 'search'])->name('search.results');


//route checkout
Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->middleware('verified.transaction')->name('checkout.show');
Route::get('/checkout/listing/{listingId}', [CheckoutController::class, 'showCheckoutListing'])->middleware('verified.transaction')->name('checkout.listing');
Route::post('/purchase/process', [CheckoutController::class, 'processPurchase'])->middleware('verified.transaction')->name('purchase.process');
Route::post('/buy-listing/{listingId}', [CheckoutController::class, 'buyListing'])->middleware('verified.transaction')->name('buy.listing');
Route::post('/cancel-listing/{listingId}', [CardController::class, 'cancelListing'])->name('cancel.listing');

// Settings route
Route::get('/settings', [HomeController::class, 'showSettings'])->name('settings');

// Admin area
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'userDetail'])->name('users.detail');
    Route::put('/users/{id}/ban', [AdminController::class, 'banUser'])->name('users.ban');
    Route::get('/requests', [AdminController::class, 'requests'])->name('requests');
    Route::post('/requests/{id}/approve', [AdminController::class, 'approveRequest'])->name('requests.approve');
    Route::post('/requests/{id}/reject', [AdminController::class, 'rejectRequest'])->name('requests.reject');
    Route::get('/card-sets/create', [AdminController::class, 'createCardSetForm'])->name('card_sets.create');
    Route::post('/card-sets', [AdminController::class, 'storeCardSet'])->name('card_sets.store');
    Route::delete('/card-sets/{id}', [AdminController::class, 'deleteCardSet'])->name('card_sets.delete');
    Route::get('/cards/create', [AdminController::class, 'createCardForm'])->name('cards.create');
    Route::post('/cards', [AdminController::class, 'storeCard'])->name('cards.store');
    Route::delete('/cards/{id}', [AdminController::class, 'deleteCard'])->name('cards.delete');
});

// ajax buat refresh listings di home
Route::get('/listings/refresh', [App\Http\Controllers\HomeController::class, 'refreshListings'])->name('listings.refresh');
