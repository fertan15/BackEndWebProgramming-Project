<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Cards;
use App\Models\Listings;
use App\Models\OrderItems;
use App\Models\UserCollections;
use App\Models\WalletTransactions;
use App\Models\Notification;
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

        // Require identity verification to access checkout
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->identity_status !== 'verified') {
            $message = 'Identity verification is required to buy cards.';
            if ($currentUser->identity_status === 'unverified') {
                $message = 'Please verify your identity before you can buy cards.';
            } elseif ($currentUser->identity_status === 'pending') {
                $message = 'Your identity verification is pending approval. You can buy cards once verified.';
            } elseif ($currentUser->identity_status === 'rejected') {
                $message = 'Your identity verification was rejected. Please resubmit your documents.';
            }
            return redirect()->back()->with('warning', $message);
        }

        $card = Cards::find($request->card_id);
        if (!$card) {
            return redirect('/home')->with('error', 'Card not found.');
        }

        $user = $currentUser;

        return view('checkout', [
            'card' => $card,
            'currentUser' => $user,
            'currencySymbol' => '$ '
        ]);
    }

    public function showCheckoutListing(Request $request, $listingId)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        // Require identity verification to access checkout
        $currentUser = Auth::user();
        if ($currentUser && $currentUser->identity_status !== 'verified') {
            $message = 'Identity verification is required to buy cards.';
            if ($currentUser->identity_status === 'unverified') {
                $message = 'Please verify your identity before you can buy cards.';
            } elseif ($currentUser->identity_status === 'pending') {
                $message = 'Your identity verification is pending approval. You can buy cards once verified.';
            } elseif ($currentUser->identity_status === 'rejected') {
                $message = 'Your identity verification was rejected. Please resubmit your documents.';
            }
            return redirect('/home')->with('warning', $message);
        }

        $listing = Listings::find($listingId);
        if (!$listing) {
            return redirect('/cards')->with('error', 'Listing not found.');
        }

        if (!$listing->is_active || $listing->quantity <= 0) {
            return redirect('/cards')->with('error', 'This listing is no longer available.');
        }

        $card = $listing->card;
        if (!$card) {
            return redirect('/cards')->with('error', 'Card not found.');
        }

        $user = $currentUser;

        return view('checkout', [
            'card' => $card,
            'listing' => $listing,
            'currentUser' => $user,
            'currencySymbol' => '$ ',
            'quantity' => 1,
            'totalPrice' => $listing->price
        ]);
    }

    public function processPurchase(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Session expired. Please login again.');
        }

        $user = Auth::user();
        // Block purchase for users who are not identity verified
        if ($user && $user->identity_status !== 'verified') {
            $message = 'Identity verification is required to buy cards.';
            if ($user->identity_status === 'unverified') {
                $message = 'Please verify your identity before you can buy cards.';
            } elseif ($user->identity_status === 'pending') {
                $message = 'Your identity verification is pending approval. You can buy cards once verified.';
            } elseif ($user->identity_status === 'rejected') {
                $message = 'Your identity verification was rejected. Please resubmit your documents.';
            }
            return redirect()->back()->with('warning', $message);
        }
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
                
                // Create notification for successful purchase
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'PURCHASE',
                    'title' => '✓ Purchase Successful',
                    'message' => 'You have successfully purchased ' . $card->name . ' for $' . number_format($card->estimated_market_price, 2),
                    'action_url' => route('inventory.index'),
                    'is_read' => false,
                ]);
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
        
        // Block purchase for users who are not identity verified
        if ($user && $user->identity_status !== 'verified') {
            $message = 'Identity verification is required to buy cards.';
            if ($user->identity_status === 'unverified') {
                $message = 'Please verify your identity before you can buy cards.';
            } elseif ($user->identity_status === 'pending') {
                $message = 'Your identity verification is pending approval. You can buy cards once verified.';
            } elseif ($user->identity_status === 'rejected') {
                $message = 'Your identity verification was rejected. Please resubmit your documents.';
            }
            if ($isAjax) {
                return response()->json(['success' => false, 'message' => $message], 403);
            }
            return redirect()->back()->with('warning', $message);
        }
        
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
                $seller = Users::find($listing->seller_id);
                
                // Add balance to seller
                if ($seller) {
                    DB::table('users')
                        ->where('id', $seller->id)
                        ->update(['balance' => DB::raw('balance + ' . $totalPrice)]);

                }else{
                    dd('Seller not found');
                }

                // Add card to user collection
                // If listing has user_collection_id, transfer ownership instead of creating new
                if ($listing->user_collection_id) {
                    // Transfer the collection item to the buyer
                    $collectionItem = UserCollections::find($listing->user_collection_id);
                    if ($collectionItem) {
                        $collectionItem->update([
                            'user_id' => $userId,
                            'is_for_trade' => false,
                            'is_listed' => false,
                            'added_at' => now(),
                        ]);
                    } else {
                        // Fallback: create new if collection item not found
                        UserCollections::create([
                            'user_id' => $userId,
                            'card_id' => $listing->card_id,
                            'condition_text' => $listing->condition_text,
                            'is_for_trade' => false,
                            'is_listed' => false,
                            'added_at' => now(),
                        ]);
                    }
                } else {
                    // Create new collection item (for listings not from inventory)
                    for ($i = 0; $i < $quantity; $i++) {
                        UserCollections::create([
                            'user_id' => $userId,
                            'card_id' => $listing->card_id,
                            'condition_text' => $listing->condition_text,
                            'is_for_trade' => false,
                            'is_listed' => false,
                            'added_at' => now(),
                        ]);
                    }
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

                // If listing is sold out, mark seller's collection item as not listed
                if ($isActive == 0) {
                    DB::table('user_collections')
                        ->where('user_id', $listing->user_id)
                        ->where('card_id', $listing->card_id)
                        ->where('is_listed', true)
                        ->update(['is_listed' => false]);
                }
                
                // Create notification for buyer
                Notification::create([
                    'user_id' => $userId,
                    'type' => 'PURCHASE',
                    'title' => '✓ Purchase Successful',
                    'message' => 'You have successfully purchased ' . $quantity . 'x ' . $listing->card->name . ' for $' . number_format($totalPrice, 2),
                    'action_url' => route('inventory.index'),
                    'is_read' => false,
                ]);
                
                // Create notification for seller
                if ($seller) {
                    Notification::create([
                        'user_id' => $seller->id,
                        'type' => 'SALES',
                        'title' => '✓ Item Sold',
                        'message' => $user->username . ' has purchased ' . $quantity . 'x ' . $listing->card->name . ' for $' . number_format($totalPrice, 2),
                        'action_url' => route('history'),
                        'is_read' => false,
                    ]);
                }
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

    public function history()
    {
        // Get orders for the logged-in user
        // Eager load 'listing' and 'listing.card' to prevent N+1 queries
        $orders = OrderItems::where('buyer_id', Auth::id())
            ->with(['listing.card', 'listing.seller'])
            ->orderBy('purchased_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show invoice for an order item (buyer or seller can view)
     */
    public function invoice($orderItemId)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Please login to view invoices.');
        }

        $orderItem = OrderItems::with(['listing.card', 'listing.seller', 'buyer'])
            ->findOrFail($orderItemId);

        $currentUserId = Auth::id();
        // Only buyer or seller can view
        if ($orderItem->buyer_id !== $currentUserId && $orderItem->listing->seller_id !== $currentUserId) {
            abort(403, 'Unauthorized to view this invoice.');
        }

        $total = $orderItem->price_at_purchase * $orderItem->quantity;

        return view('orders.invoice', [
            'order' => $orderItem,
            'total' => $total,
        ]);
    }
}