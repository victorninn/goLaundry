<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

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
            'tin' => 'nullable|string|max:20',
            'business_registration_number' => 'nullable|string|max:20',
            'owner_name' => 'required|string|max:255',
        ]);

        $business->update($validated);

        return back()->with('success', 'Business information updated successfully.');
    }
}
