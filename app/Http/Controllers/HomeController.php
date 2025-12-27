<?php

namespace App\Http\Controllers;

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
}
