<?php

namespace App\Http\Controllers;

use App\Models\Listings;
use App\Models\Wishlists;
use App\Models\User;
use App\Models\OrderItems;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(Request $request)
    {
        $user_id = $request->session()->get('user_id');
        $user_name = $request->session()->get('user_name', 'User');

        $latestListings = Listings::with(['card', 'seller'])
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        // Get statistics for reports
        // Top seller by revenue
        $topSeller = OrderItems::selectRaw('listings.seller_id, users.name, users.username, SUM(order_items.price_at_purchase * order_items.quantity) as total_revenue')
            ->join('listings', 'order_items.listing_id', '=', 'listings.id')
            ->join('users', 'listings.seller_id', '=', 'users.id')
            ->groupBy('listings.seller_id', 'users.name', 'users.username')
            ->orderByDesc('total_revenue')
            ->first();

        // Top buyer by total spent
        $topBuyer = OrderItems::selectRaw('buyer_id, users.name, users.username, SUM(price_at_purchase * quantity) as total_spent')
            ->join('users', 'order_items.buyer_id', '=', 'users.id')
            ->groupBy('buyer_id', 'users.name', 'users.username')
            ->orderByDesc('total_spent')
            ->first();

        // Most traded card
        $mostTradedCard = OrderItems::selectRaw('listings.card_id, cards.name, cards.image_url, SUM(order_items.quantity) as total_traded')
            ->join('listings', 'order_items.listing_id', '=', 'listings.id')
            ->join('cards', 'listings.card_id', '=', 'cards.id')
            ->groupBy('listings.card_id', 'cards.name', 'cards.image_url')
            ->orderByDesc('total_traded')
            ->first();

        // Total platform revenue (all transactions)
        $totalRevenue = OrderItems::selectRaw('SUM(price_at_purchase * quantity) as total')
            ->value('total') ?? 0;

        // Total number of transactions
        $totalTransactions = OrderItems::count();

        // Active listings count
        $activeListings = Listings::where('is_active', true)->count();

        // User's own analytics (if logged in)
        $userStats = null;
        if ($user_id) {
            // User's total sales revenue
            $userSalesRevenue = OrderItems::whereHas('listing', function($q) use ($user_id) {
                    $q->where('seller_id', $user_id);
                })
                ->selectRaw('SUM(price_at_purchase * quantity) as total')
                ->value('total') ?? 0;

            // User's total purchases spent
            $userPurchasesSpent = OrderItems::where('buyer_id', $user_id)
                ->selectRaw('SUM(price_at_purchase * quantity) as total')
                ->value('total') ?? 0;

            // User's active listings
            $userActiveListings = Listings::where('seller_id', $user_id)
                ->where('is_active', true)
                ->count();

            // User's total sales count
            $userTotalSales = OrderItems::whereHas('listing', function($q) use ($user_id) {
                    $q->where('seller_id', $user_id);
                })
                ->count();

            // User's total purchases count
            $userTotalPurchases = OrderItems::where('buyer_id', $user_id)->count();

            // User's most sold card
            $userMostSoldCard = OrderItems::whereHas('listing', function($q) use ($user_id) {
                    $q->where('seller_id', $user_id);
                })
                ->selectRaw('listings.card_id, cards.name, cards.image_url, SUM(order_items.quantity) as total_sold')
                ->join('listings', 'order_items.listing_id', '=', 'listings.id')
                ->join('cards', 'listings.card_id', '=', 'cards.id')
                ->groupBy('listings.card_id', 'cards.name', 'cards.image_url')
                ->orderByDesc('total_sold')
                ->first();

            $userStats = [
                'salesRevenue' => $userSalesRevenue,
                'purchasesSpent' => $userPurchasesSpent,
                'activeListings' => $userActiveListings,
                'totalSales' => $userTotalSales,
                'totalPurchases' => $userTotalPurchases,
                'mostSoldCard' => $userMostSoldCard,
            ];
        }

        return view('home', compact('latestListings', 'user_id', 'user_name', 'topSeller', 'topBuyer', 'mostTradedCard', 'totalRevenue', 'totalTransactions', 'activeListings', 'userStats'));

    }

    //buat di home, ajax refresh listings
    public function refreshListings()
    {
        $latestListings = Listings::with(['card', 'seller'])
                            ->latest()
                            ->take(4)
                            ->get();

        return view('partials.listing_cards', ['listings' => $latestListings])->render();
    }
    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login');
    }

    /**
     * View profile page.
     */
    public function viewprofile(Request $request)
    {
        // Prefer authenticated user, fallback to session user_id used elsewhere in the app
        $user = auth()->user();
        if (!$user) {
            $userId = $request->session()->get('user_id');
            $user = $userId ? User::find($userId) : null;
        }

        return view('view_profile', compact('user'));
    }

    /**
     * Update user profile (email not editable).
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            $userId = $request->session()->get('user_id');
            $user = $userId ? User::find($userId) : null;
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'identity_type' => 'nullable|in:KTP,SIM,Passport',
            'identity_number' => 'nullable|string|max:50|unique:users,identity_number,' . $user->id,
            'identity_image_url' => 'nullable|url|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('view_profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show user's wishlist.
     */
    public function showWishlist(Request $request)
    {
        $userId = $request->session()->get('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to view your wishlist');
        }

        // Get wishlist items with card details
        $wishlistItems = Wishlists::where('user_id', $userId)
                                   ->with('card.cardSet')
                                   ->orderBy('added_at', 'desc')
                                   ->get();

        return view('wishlist', ['wishlistItems' => $wishlistItems]);
    }

    /**
     * Toggle wishlist for a card.
     */
    public function toggleWishlist(Request $request, $cardId)
    {
        $userId = $request->session()->get('user_id');
        
        if (!$userId) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Please login to add items to wishlist'
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Please login to add items to wishlist');
        }

        // Check if card exists in wishlist
        $wishlistItem = Wishlists::where('user_id', $userId)
                                  ->where('card_id', $cardId)
                                  ->first();

        if ($wishlistItem) {
            // Remove from wishlist
            $wishlistItem->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'action' => 'removed',
                    'message' => 'Removed from wishlist',
                    'in_wishlist' => false
                ]);
            }

            return redirect()->back()->with('success', 'Removed from wishlist');
        } else {
            // Add to wishlist
            Wishlists::create([
                'user_id' => $userId,
                'card_id' => $cardId
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'action' => 'added',
                    'message' => 'Added to wishlist',
                    'in_wishlist' => true
                ]);
            }

            return redirect()->back()->with('success', 'Added to wishlist');
        }
    }



    /**
     * Show settings page.
     */
    public function showSettings(Request $request)
    {
        return view('settings');
    }

    /**
     * Show inventory page with user's collected cards
     */
    public function showInventory(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Please login to view your inventory.');
        }

        $userId = auth()->id();
        
        // Get tradeable cards (is_for_trade = true)
        $tradeableCards = \App\Models\UserCollections::where('user_id', $userId)
                        ->where('is_for_trade', true)
                        ->with(['card' => function($q) {
                            $q->with('cardSet');
                        }])
                        ->orderBy('added_at', 'desc')
                        ->get();

        // Get locked cards (is_for_trade = false)
        $lockedCards = \App\Models\UserCollections::where('user_id', $userId)
                        ->where('is_for_trade', false)
                        ->with(['card' => function($q) {
                            $q->with('cardSet');
                        }])
                        ->orderBy('added_at', 'desc')
                        ->get();

        return view('inventory', [
            'tradeableCards' => $tradeableCards,
            'lockedCards' => $lockedCards
        ]);
    }

    /**
     * Create a listing for a card in user's collection
     */
    public function addListing(Request $request, $collectionId)
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Please login to add listings.');
        }

        $userId = auth()->id();
        // Block creating listings for users who are not identity verified
        $user = \App\Models\Users::find($userId);
        if ($user && $user->identity_status !== 'verified') {
            $message = 'Identity verification is required to sell cards.';
            if ($user->identity_status === 'unverified') {
                $message = 'Please verify your identity before you can sell cards.';
            } elseif ($user->identity_status === 'pending') {
                $message = 'Your identity verification is pending approval. You can sell cards once verified.';
            } elseif ($user->identity_status === 'rejected') {
                $message = 'Your identity verification was rejected. Please resubmit your documents.';
            }
            return redirect()->back()->with('warning', $message);
        }
        
        // Verify the collection belongs to the user
        $collection = \App\Models\UserCollections::where('id', $collectionId)
                            ->where('user_id', $userId)
                            ->firstOrFail();

        $request->validate([
            'price' => 'required|numeric|min:0.01'
        ]);

        try {
            // Create listing with collection's condition and quantity of 1
            \App\Models\Listings::create([
                'card_id' => $collection->card_id,
                'seller_id' => $userId,
                'price' => $request->price,
                'condition_text' => $collection->condition_text,
                'description' => '',
                'quantity' => 1,
                'is_active' => 1,
                'user_collection_id' => $collectionId
            ]);

            // Mark the collection item as listed
            $collection->update(['is_listed' => true]);

            return redirect()->route('inventory.index')->with('success', 'Listing created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create listing: ' . $e->getMessage());
        }
    }

    /**
     * Lock a card to prevent accidental listing
     */
    public function lockCard(Request $request, $collectionId)
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Please login.');
        }

        $userId = auth()->id();
        
        $collection = \App\Models\UserCollections::where('id', $collectionId)
                            ->where('user_id', $userId)
                            ->firstOrFail();

        $collection->update(['is_for_trade' => false]);

        return redirect()->route('inventory.index')->with('success', 'Card locked successfully!');
    }

    /**
     * Unlock a card to make it tradeable
     */
    public function unlockCard(Request $request, $collectionId)
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Please login.');
        }

        $userId = auth()->id();
        
        $collection = \App\Models\UserCollections::where('id', $collectionId)
                            ->where('user_id', $userId)
                            ->firstOrFail();

        $collection->update(['is_for_trade' => true]);

        return redirect()->route('inventory.index')->with('success', 'Card unlocked successfully!');
    }

    /**
     * Display transaction history (buying and selling)
     */
    public function showHistory(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Please login to view history.');
        }

        $userId = auth()->id();

        // Get buying history (purchases made by user)
        $buyingHistory = OrderItems::where('buyer_id', $userId)
                                    ->with(['listing' => function($q) {
                                        $q->with(['card', 'seller']);
                                    }])
                                    ->orderBy('purchased_at', 'desc')
                                    ->get();

        // Get selling history (sales made by user)
        $sellingHistory = OrderItems::whereHas('listing', function($q) use ($userId) {
                                        $q->where('seller_id', $userId);
                                    })
                                    ->with(['listing' => function($q) {
                                        $q->with(['card', 'seller']);
                                    }, 'buyer'])
                                    ->orderBy('purchased_at', 'desc')
                                    ->get();

        return view('history', compact('buyingHistory', 'sellingHistory'));
    }
}