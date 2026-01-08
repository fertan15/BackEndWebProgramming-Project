<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Models\CardSets;
use App\Models\Listings;
use App\Models\Wishlists;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display all card sets
     */
    public function showCardSets()
    {
        // Load sets with dynamic count of related cards
        $cardSets = CardSets::withCount('cards')
            ->orderBy('release_date', 'desc')
            ->get();
        
        return view('card_sets', [
            'card_set' => $cardSets
        ]);
    }

    /**
     * Display cards from a specific set
     */
    public function showCards(Request $request, $setId)
    {
        // Find the card set
        $cardSet = CardSets::findOrFail($setId);
        
        // Get all cards for this set
        $cards = Cards::where('card_set_id', $setId)
                    ->orderBy('id')
                    ->get();
        
        // Get wishlist card IDs for the current user
        $userId = $request->session()->get('user_id');
        $wishlistCardIds = [];
        
        if ($userId) {
            $wishlistCardIds = Wishlists::where('user_id', $userId)
                                       ->pluck('card_id')
                                       ->toArray();
        }
        
        return view('cards', [
            'cards' => $cards,
            'cardSet' => $cardSet,
            'wishlistCardIds' => $wishlistCardIds
        ]);
    }

    /**
     * Display all cards (no filter)
     */
    public function showAllCards(Request $request)
    {
        $userId = $request->session()->get('user_id');
        $cards = Cards::with('cardSet')->orderBy('card_set_id')->orderBy('id')->get();
        
        // Get wishlist card IDs for the current user
        $wishlistCardIds = [];
        if ($userId) {
            $wishlistCardIds = Wishlists::where('user_id', $userId)
                                        ->pluck('card_id')
                                        ->toArray();
        }
        
        return view('cards', [
            'cards' => $cards,
            'cardSet' => null,
            'wishlistCardIds' => $wishlistCardIds
        ]);
    }

    /**
     * Display detailed view of a single card (Steam Market style)
     */
    public function showCardDetail(Request $request, $cardId)
    {
        // Get the card with its relationships
        $card = Cards::with(['cardSet', 'listings' => function($query) {
                        $query->where('is_active', 1)->with('seller');
                    }])
                    ->findOrFail($cardId);
        
        $userId = $request->session()->get('user_id');
        
        // Check if card is in user's wishlist
        $isInWishlist = false;
        if ($userId) {
            $isInWishlist = Wishlists::where('user_id', $userId)
                                    ->where('card_id', $cardId)
                                    ->exists();
        }
        
        // Get active listings for this card
        $listings = Listings::where('card_id', $cardId)
                        ->where('is_active', 1)
                        ->with('seller')
                        ->orderBy('price', 'asc')
                        ->get();

        // History Listings
            $history = Listings::where('card_id', $cardId)
                            ->where('is_active', 0)
                            ->with('seller')
                            ->orderBy('price', 'asc')
                            ->get();
        
        // Get price history data (placeholder for now)
        $priceHistory = $this->generateMockPriceHistory($card->estimated_market_price);
        
        // Get related cards from the same set
        $relatedCards = Cards::where('card_set_id', $card->card_set_id)
                           ->where('id', '!=', $cardId)
                           ->limit(4)
                           ->get();
        
        return view('card_detail', [
            'card' => $card,
            'isInWishlist' => $isInWishlist,
            'listings' => $listings,
            'history' => $history,
            'priceHistory' => $priceHistory,
            'relatedCards' => $relatedCards
        ]);
    }

    /**
     * Generate mock price history data for the chart
     * TODO: Replace with real transaction data later
     */
    private function generateMockPriceHistory($basePrice)
    {
        $dates = [];
        $prices = [];
        
        // mockupnya buat sekitar 30 hari
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates[] = $date->format('M d');
            
            // randomizer 15% up or down
            $variation = (rand(-15, 15) / 100);
            $prices[] = round($basePrice * (1 + $variation), 2);
        }
        
        return [
            'dates' => $dates,
            'prices' => $prices
        ];
    }

    public function savelisting(Request $request) {
        Listings::create([
            'card_id' => $request->cardid,
            'seller_id' => $request->sellerid,
            'price' => $request->price,
            'condition_text' => $request->condition,
            'description' => '',
            'quantity' => $request->quantity,
            'is_active' => 1
        ]);
        return redirect()->back();
    }

}