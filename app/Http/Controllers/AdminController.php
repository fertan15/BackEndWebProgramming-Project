<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Cards;
use App\Models\CardSets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function __construct()
    {
        // Simple gate: require login + admin flag
        $this->middleware(function ($request, $next) {
            $userId = $request->session()->get('user_id');
            $isAdmin = (bool) $request->session()->get('is_admin');

            if (!$userId) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            if (!$isAdmin) {
                return Redirect::to('/home')->with('error', 'Admin access required.');
            }

            return $next($request);
        });
    }

    public function index()
    {
        return redirect()->route('admin.users');
    }

    public function users()
    {
        $users = Users::orderByDesc('created_at')->get();

        return view('admin.users', [
            'users' => $users,
        ]);
    }

    public function createCardSetForm()
    {
        return view('admin.card_sets_create');
    }

    public function storeCardSet(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:card_sets,name',
            'release_date' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
            'image' => 'required|image|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'set_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destination = public_path('images/card_set');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $image->move($destination, $filename);
            $imagePath = 'images/card_set/' . $filename;
        }

        CardSets::create([
            'name' => $validated['name'],
            'release_date' => $validated['release_date'] ?? null,
            'description' => $validated['description'] ?? null,
            'image_url' => $imagePath,
        ]);

        return redirect()
            ->route('admin.card_sets.create')
            ->with('success', 'Card set created successfully.');
    }

    public function createCardForm()
    {
        $cardSets = CardSets::orderBy('name')->get();

        return view('admin.cards_create', [
            'cardSets' => $cardSets,
        ]);
    }

    public function requests()
    {
        $pendingUsers = Users::where(function ($q) {
                $q->where('identity_status', 'pending')
                  ->orWhereNull('identity_status');
            })
            ->orWhere('account_status', 'verify')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.requests', [
            'pendingUsers' => $pendingUsers,
        ]);
    }

    public function approveRequest($id)
    {
        $user = Users::findOrFail($id);

        $user->identity_status = 'approved';
        $user->account_status = 'active';
        $user->save();

        return redirect()->route('admin.requests')->with('success', 'User verification approved.');
    }

    public function rejectRequest($id)
    {
        $user = Users::findOrFail($id);

        $user->identity_status = 'rejected';
        $user->account_status = $user->account_status ?: 'verify';
        $user->save();

        return redirect()->route('admin.requests')->with('success', 'User verification rejected.');
    }

    public function storeCard(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'card_set_id' => 'required|exists:card_sets,id',
            'card_type' => 'required|string|max:100',
            'rarity' => 'required|string|max:100',
            'edition' => 'nullable|string|max:100',
            'estimated_market_price' => 'nullable|numeric|min:0',
            'image' => 'required|image|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'card_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destination = public_path('images/cards');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $image->move($destination, $filename);
            $imagePath = 'images/cards/' . $filename;
        }

        Cards::create([
            'name' => $validated['name'],
            'card_set_id' => $validated['card_set_id'],
            'card_type' => $validated['card_type'],
            'rarity' => $validated['rarity'],
            'edition' => $validated['edition'] ?? null,
            'estimated_market_price' => $validated['estimated_market_price'] ?? 0,
            'image_url' => $imagePath,
        ]);

        // Keep card set count in sync with actual cards
        $setId = $validated['card_set_id'];
        $actualCount = Cards::where('card_set_id', $setId)->count();
        CardSets::where('id', $setId)->update(['total_cards' => $actualCount]);

        return redirect()
            ->route('admin.cards.create')
            ->with('success', 'Card created successfully.');
    }
}
