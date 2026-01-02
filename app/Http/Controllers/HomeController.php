<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Models\CardSets;
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


    public function showCard()
    {
        $param['cards'] = Cards::get();
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
}
