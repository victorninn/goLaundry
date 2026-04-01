<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // ─── Businesses ──────────────────────────────────────────────────────────

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

    public function createBusiness()
    {
        return view('admin.create-business');
    }

    public function storeBusiness(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        Business::create(array_merge($validated, ['is_active' => true]));

        return redirect()->route('super-admin.businesses')
            ->with('success', 'Business created successfully.');
    }

    public function editBusiness(Business $business)
    {
        return view('admin.edit-business', compact('business'));
    }

    public function updateBusiness(Request $request, Business $business)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $business->update($validated);

        return redirect()->route('super-admin.businesses')
            ->with('success', 'Business updated successfully.');
    }

    // ─── Users ────────────────────────────────────────────────────────────────

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
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8',
            'business_id' => 'nullable|exists:businesses,id',
            'role'        => 'required|in:admin,super_admin',
        ]);

        User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'password'    => Hash::make($validated['password']),
            'role'        => $validated['role'],
            'business_id' => $validated['business_id'],
        ]);

        return redirect()->route('super-admin.users')
            ->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $businesses = Business::where('is_active', true)->get();

        return view('admin.edit-user', compact('user', 'businesses'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'password'    => 'nullable|string|min:8',
            'business_id' => 'nullable|exists:businesses,id',
            'role'        => 'required|in:admin,super_admin',
        ]);

        $user->name        = $validated['name'];
        $user->email       = $validated['email'];
        $user->role        = $validated['role'];
        $user->business_id = $validated['business_id'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('super-admin.users')
            ->with('success', 'User updated successfully.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('super-admin.users')
            ->with('success', 'User deleted successfully.');
    }
}