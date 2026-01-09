<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Models\CardSets;
use App\Models\Listings;
use App\Models\Wishlists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // History - Get purchase history from order_items
        $history = \App\Models\OrderItems::whereHas('listing', function($q) use ($cardId) {
                        $q->where('card_id', $cardId);
                    })
                    ->with([
                        'listing' => function($q) {
                            $q->with('seller');
                        },
                        'buyer' => function($q) {
                            $q->select('id', 'username', 'email');
                        }
                    ])
                    ->orderBy('purchased_at', 'desc')
                    ->get();
        
        // Get REAL price history data from actual transactions
        $priceHistory = $this->getRealPriceHistory($cardId, $card->estimated_market_price);
        
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
     * Get real price history from actual transactions
     */
    private function getRealPriceHistory($cardId, $basePrice)
    {
        // Get transactions for this card over the last 30 days
        $transactions = \App\Models\OrderItems::whereHas('listing', function($q) use ($cardId) {
                            $q->where('card_id', $cardId);
                        })
                        ->where('purchased_at', '>=', now()->subDays(30))
                        ->select(
                            DB::raw('DATE(purchased_at) as date'),
                            DB::raw('AVG(price_at_purchase) as avg_price')
                        )
                        ->groupBy('date')
                        ->orderBy('date', 'asc')
                        ->get();

        $dates = [];
        $prices = [];
        
        // If we have transaction data, use it
        if ($transactions->isNotEmpty()) {
            // Create a map of dates with actual data
            $transactionMap = [];
            foreach ($transactions as $transaction) {
                $transactionMap[$transaction->date] = (float) $transaction->avg_price;
            }
            
            // Fill in all 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dateKey = $date->format('Y-m-d');
                $dates[] = $date->format('M d');
                
                // If we have a transaction for this date, use it
                if (isset($transactionMap[$dateKey])) {
                    $prices[] = round($transactionMap[$dateKey], 2);
                } else {
                    // Use the last known price or estimated market price
                    if (!empty($prices)) {
                        // Carry forward the last price
                        $prices[] = $prices[count($prices) - 1];
                    } else {
                        // Use estimated market price if no prior data
                        $prices[] = round($basePrice, 2);
                    }
                }
            }
        } else {
            // No transaction data available - show estimated market price as flat line
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $dates[] = $date->format('M d');
                $prices[] = round($basePrice, 2);
            }
        }
        
        return [
            'dates' => $dates,
            'prices' => $prices,
            'hasRealData' => $transactions->isNotEmpty()
        ];
    }

    public function savelisting(Request $request) {
        // Enforce identity verification for sellers
        $userId = $request->session()->get('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Please login to create listings.');
        }

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

        Listings::create([
            'card_id' => $request->cardid,
            'seller_id' => $userId,
            'price' => $request->price,
            'condition_text' => $request->condition,
            'description' => '',
            'quantity' => $request->quantity,
            'is_active' => 1
        ]);
        return redirect()->back();
    }

    /**
     * Cancel a listing
     */
    public function cancelListing(Request $request, $listingId)
    {
        $userId = $request->session()->get('user_id');
        if (!$userId) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $listing = Listings::findOrFail($listingId);

        // Verify the user is the seller
        if ($listing->seller_id !== (int)$userId) {
            return redirect()->back()->with('error', 'You are not authorized to cancel this listing.');
        }

        try {
            // Update listing status to cancelled
            $listing->update([
                    'is_active' => false,
                'quantity' => 0
            ]);

            // Mark the user's collection item as not listed
            \App\Models\UserCollections::where('user_id', $userId)
                ->where('card_id', $listing->card_id)
                ->where('is_listed', true)
                ->update(['is_listed' => false]);

            return redirect()->back()->with('success', 'Listing cancelled successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel listing: ' . $e->getMessage());
        }
    }

}