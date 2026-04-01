<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function businesses()
    {
        $businesses = Business::with('owner')
            ->withCount(['customers', 'laundryOrders'])
            ->latest()
            ->paginate(20);

        return view('admin.businesses', compact('businesses'));
    }

    public function toggleBusinessStatus(Business $business)
    {
        $business->update(['is_active' => !$business->is_active]);

        return back()->with('success', 'Business status updated.');
    }

    public function users()
    {
        $users = User::with('business')
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function createAdmin()
    {
        $businesses = Business::where('is_active', true)->get();

        return view('admin.create-admin', compact('businesses'));
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'business_id' => 'nullable|exists:businesses,id',
            'role' => 'required|in:admin,super_admin',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'business_id' => $validated['business_id'],
        ]);

        return redirect()->route('super-admin.users')
            ->with('success', 'User created successfully.');
    }
}
