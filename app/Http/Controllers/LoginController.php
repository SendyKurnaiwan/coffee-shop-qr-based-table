<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function index()
    {
        // If user is already logged in, redirect based on role
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'owner') {
                return redirect()->route('admin'); // or redirect('/admin')
            } elseif ($user->role === 'meja') {
                return redirect()->route('meja'); // or your meja route
            }

            return redirect('/'); // Default redirect
        }

        return view('login.index', []);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Force type hint for Intelephense
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Method 1: Use query builder directly (no Intelephense issues)
            if ($user->role === 'meja') {
                \Illuminate\Support\Facades\DB::table('users')
                    ->where('id', $user->id)
                    ->update(['is_occupied' => true]);
            }

            Log::info('User logged in: ' . $user->username . ' | Role: ' . $user->role);

            if ($user->role === 'owner') {
                return redirect()->intended('/admin')->with('success', 'Welcome back, Owner!');
            } elseif ($user->role === 'meja') {
                return redirect()->intended('/meja/dashboard')->with('success', 'Welcome, Meja User!');
            }

            return redirect()->intended('/');
        }

        Log::warning('Login failed for username: ' . $credentials['username']);
        return back()->with('loginError', 'Login Failed');
    }

    public function logout(Request $request)
    {
        // Method 2: Get fresh instance from database
        $userId = Auth::id();
        if ($userId) {
            $user = User::find($userId);

            if ($user && $user->role === 'meja') {
                // This should work without Intelephense errors
                $user->is_occupied = false;
                $user->save();
                Log::info('Meja user logged out: ' . $user->username);
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
