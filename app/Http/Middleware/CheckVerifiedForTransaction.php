<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Users;
use Symfony\Component\HttpFoundation\Response;

class CheckVerifiedForTransaction
{
    /**
     * Handle an incoming request.
     * Check if user is verified for buying/selling transactions.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in via session
        if (!$request->session()->has('user_id')) {
            // If it's an AJAX request, return JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to continue.',
                    'redirect' => '/login'
                ], 401);
            }
            return redirect('/login')->with('error', 'Please log in to continue.');
        }

        // Get user from database
        $userId = $request->session()->get('user_id');
        $user = Users::find($userId);

        if (!$user) {
            // User not found, clear session and redirect
            $request->session()->flush();
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to continue.',
                    'redirect' => '/login'
                ], 401);
            }
            return redirect('/login')->with('error', 'Please log in to continue.');
        }

        // Check identity verification status
        if ($user->identity_status === 'unverified') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your identity first before you can buy or sell cards.'
                ], 403);
            }
            return redirect()->back()->with('warning', 'Please verify your identity first before you can buy or sell cards.');
        }

        if ($user->identity_status === 'pending') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your identity verification is pending approval. You will be able to buy and sell cards once verified.'
                ], 403);
            }
            return redirect()->back()->with('warning', 'Your identity verification is pending approval. You will be able to buy and sell cards once verified.');
        }

        if ($user->identity_status === 'rejected') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your identity verification was rejected. Please resubmit your documents to continue.'
                ], 403);
            }
            return redirect()->back()->with('warning', 'Your identity verification was rejected. Please resubmit your documents to continue.');
        }

        // Only allow verified users to proceed
        if ($user->identity_status !== 'verified') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Identity verification is required for this action.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Identity verification is required for this action.');
        }

        return $next($request);
    }
}
