<?php

namespace App\Http\Controllers;

use App\Models\Wishlists;
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
    public function viewprofile()
    {
        return view('view_profile');
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
}