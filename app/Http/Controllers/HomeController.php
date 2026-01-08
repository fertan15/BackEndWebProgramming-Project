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
}