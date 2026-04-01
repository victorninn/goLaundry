<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'business_name' => 'required|string|max:255',
            'business_phone' => 'nullable|string|max:20',
            'business_address' => 'nullable|string|max:500',
        ]);

        // Create business first
        $business = Business::create([
            'name' => $validated['business_name'],
            'phone' => $validated['business_phone'] ?? null,
            'address' => $validated['business_address'] ?? null,
        ]);

        // Create user as admin of the business
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_ADMIN,
            'business_id' => $business->id,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome! Your laundry business has been registered.');
    }
}
