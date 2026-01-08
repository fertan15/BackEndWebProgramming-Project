<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cards; 
use App\Models\Wishlists; 

class DashboardController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $cards = Cards::where('name', 'LIKE', "%{$query}%")->get();
        
        $wishlistCardIds = [];
        if (session()->has('user_id')) {
            $wishlistCardIds = Wishlists::where('user_id', session('user_id'))
                                ->pluck('card_id')
                                ->toArray();
        }

        return view('search_results', compact('cards', 'query', 'wishlistCardIds'));
    }
}