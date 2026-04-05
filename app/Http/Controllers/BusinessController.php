<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    public function edit()
    {
        $business = auth()->user()->business;

        if (!$business) {
            abort(404, 'No business assigned.');
        }

        return view('admin.business-edit', compact('business'));
    }

    public function update(Request $request)
    {
        $business = auth()->user()->business;

        if (!$business) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'tin' => 'nullable|string|max:50',
            'business_registration_number' => 'nullable|string|max:100',
            'owner_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($business->logo) {
                Storage::disk('public')->delete($business->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $business->update($validated);

        return back()->with('success', 'Business information updated successfully.');
    }
}
