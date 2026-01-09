<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Users;
use Symfony\Component\HttpFoundation\Response;

class CheckIdentityVerification
{
    /**
     * Handle an incoming request.
     * Check if user is logged in (for wishlist access).
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

        // For wishlist, only login required - allow all identity statuses
        return $next($request);
    }
}