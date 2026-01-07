<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Cards;
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
}