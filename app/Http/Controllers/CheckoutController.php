<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Cards;
use App\Models\Listings;
use App\Models\OrderItems;
use App\Models\UserCollections;
use App\Models\WalletTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function showCheckout(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        $card = Cards::find($request->card_id);
        if (!$card) {
            return redirect('/home')->with('error', 'Card not found.');
        }

        $user = Auth::user();

        return view('checkout', [
            'card' => $card,
            'currentUser' => $user,
            'currencySymbol' => '$ '
        ]);
    }

    public function processPurchase(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Session expired. Please login again.');
        }

        $user = Auth::user();
        $card = Cards::find($request->card_id);

        if (!$card) {
            return redirect()->back()->with('error', 'Item no longer available.');
        }

        if ($user->balance < $card->estimated_market_price) {
            return redirect()->back()->with('error', 'Insufficient balance!');
        }

        try {
            DB::transaction(function () use ($user, $card) {
                $newBalance = $user->balance - $card->estimated_market_price;

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['balance' => $newBalance]);

                $user->balance = $newBalance;
            });

            return redirect('/home')->with('success', 'Purchase successful! Remaining: Rp ' . number_format($user->balance, 0, ',', '.'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Transaction error: ' . $e->getMessage());
        }
    }

    /**
     * Buy a listing - purchases card from a seller
     */
    public function buyListing(Request $request, $listingId)
    {
        $isAjax = $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';
        
        if (!Auth::check()) {
            if ($isAjax) {
                return response()->json(['error' => 'Please login to make a purchase.'], 401);
            }
            return redirect('/login')->with('error', 'Please login to make a purchase.');
        }

        $userId = Auth::id();
        $user = Auth::user();
        
        $listing = Listings::find($listingId);
        if (!$listing) {
            if ($isAjax) {
                return response()->json(['error' => 'Listing not found.'], 404);
            }
            return redirect()->back()->with('error', 'Listing not found.');
        }
        
        $quantity = (int)$request->input('quantity', 1);

        // Validate purchase
        if (!$listing->is_active || $listing->quantity <= 0) {
            if ($isAjax) {
                return response()->json(['error' => 'This listing is no longer available.'], 400);
            }
            return redirect()->back()->with('error', 'This listing is no longer available.');
        }

        if ($listing->quantity < $quantity) {
            if ($isAjax) {
                return response()->json(['error' => 'Not enough quantity available. Available: ' . $listing->quantity], 400);
            }
            return redirect()->back()->with('error', 'Not enough quantity available. Available: ' . $listing->quantity);
        }

        $totalPrice = floatval($listing->price) * $quantity;

        if ($user->balance < $totalPrice) {
            if ($isAjax) {
                return response()->json(['error' => 'Insufficient balance! You need $' . number_format($totalPrice, 2)], 400);
            }
            return redirect()->back()->with('error', 'Insufficient balance! You need Rp ' . number_format($totalPrice, 0, ',', '.'));
        }

        try {
            DB::transaction(function () use ($user, $listing, $quantity, $userId, $totalPrice) {
                // Deduct balance from buyer
                $newBalance = $user->balance - $totalPrice;
                $user->update(['balance' => $newBalance]);

                // Get seller info
                $seller = Users::find($listing->user_id);
                
                // Add balance to seller
                if ($seller) {
                    $seller->balance += $totalPrice;
                    $seller->save();
                }

                // Add card to user collection
                for ($i = 0; $i < $quantity; $i++) {
                    UserCollections::create([
                        'user_id' => $userId,
                        'card_id' => $listing->card_id,
                        'condition_text' => $listing->condition_text,
                        'is_for_trade' => false,
                        'added_at' => now(),
                    ]);
                }

                // Create order item record
                $orderItem = OrderItems::create([
                    'listing_id' => $listing->id,
                    'quantity' => $quantity,
                    'price_at_purchase' => $listing->price,
                    'buyer_id' => $userId,
                ]);

                // Create wallet transaction for buyer (debit)
                WalletTransactions::create([
                    'user_id' => $userId,
                    'reference_order_item_id' => $orderItem->id,
                    'amount' => -$totalPrice,
                    'transaction_type' => 'PURCHASE',
                    'description' => 'Purchase of ' . $quantity . ' card(s) from listing #' . $listing->id,
                ]);

                // Create wallet transaction for seller (credit)
                if ($seller) {
                    WalletTransactions::create([
                        'user_id' => $seller->id,
                        'reference_order_item_id' => $orderItem->id,
                        'amount' => $totalPrice,
                        'transaction_type' => 'SALES_REVENUE',
                        'description' => 'Sale of ' . $quantity . ' card(s) from listing #' . $listing->id,
                    ]);
                }

                // Decrease listing quantity
                $newQuantity = $listing->quantity - $quantity;
                $isActive = $newQuantity > 0 ? 1 : 0;
                
                DB::table('listings')
                    ->where('id', $listing->id)
                    ->update([
                        'quantity' => $newQuantity,
                        'is_active' => $isActive
                    ]);
            });

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase successful! The card has been added to your inventory.'
                ]);
            }
            return redirect()->back()->with('success', 'Purchase successful! The card has been added to your inventory.');
        } catch (\Exception $e) {
            if ($isAjax) {
                return response()->json(['error' => 'Purchase failed: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Purchase failed: ' . $e->getMessage());
        }
    }
}