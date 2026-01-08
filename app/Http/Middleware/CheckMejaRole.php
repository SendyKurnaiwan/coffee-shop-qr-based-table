<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMejaRole
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors([
                'error' => 'Please login to access this page.'
            ]);
        }

        $user = Auth::user();
        // Check if user has 'meja' role
        if ($user->role !== 'meja') {
            // For AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access. Meja role required.'
                ], 403);
            }

            // For web requests - redirect to home page
            return redirect('/')->with([
                'error' => 'You do not have permission to access the admin area.'
            ]);
        }
        return $next($request);
    }
}
