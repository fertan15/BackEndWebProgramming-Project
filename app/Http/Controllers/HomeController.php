<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Models\CardSets;
use App\Models\Wishlists;
use Illuminate\Container\Attributes\Auth;
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


    public function showCard(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $param['cards'] = Cards::get();
        
        // Get wishlist card IDs for the current user
        if ($userId) {
            $wishlistCardIds = Wishlists::where('user_id', $userId)
                                        ->pluck('card_id')
                                        ->toArray();
            $param['wishlistCardIds'] = $wishlistCardIds;
        } else {
            $param['wishlistCardIds'] = [];
        }
        
        return view('cards', $param);
    }
    public function showCardSets()
    {
        $param['card_set'] = CardSets::get();
        return view('card_sets', $param);
    }
    public function viewprofile()
    {
        return view('view_profile');
    }
    public function showHome()
    {
        return view('home');
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
                                   ->with('card')
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
            return redirect()->route('login')->with('error', 'Please login to add items to wishlist');
        }

        // Check if card exists in wishlist
        $wishlistItem = Wishlists::where('user_id', $userId)
                                  ->where('card_id', $cardId)
                                  ->first();

        if ($wishlistItem) {
            // Remove from wishlist
            $wishlistItem->delete();
            return redirect()->back()->with('success', 'Removed from wishlist');
        } else {
            // Add to wishlist
            Wishlists::create([
                'user_id' => $userId,
                'card_id' => $cardId
            ]);
            return redirect()->back()->with('success', 'Added to wishlist');
        }
    }
}
