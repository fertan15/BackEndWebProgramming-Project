<?php

namespace App\Http\Controllers;

use App\Models\Wishlists;
use App\Models\User;
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

        return view('home', compact('user_id', 'user_name'));
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
}