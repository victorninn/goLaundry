<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\Business;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::with('business')
            ->latest()
            ->paginate(20);

        $businesses = Business::where('is_active', true)->get();

        return view('admin.licenses.index', compact('licenses', 'businesses'));
    }

    public function create()
    {
        $businesses = Business::where('is_active', true)
            ->doesntHave('activeLicense')
            ->get();
        
        $subscriptionTypes = License::getSubscriptionTypes();

        return view('admin.licenses.create', compact('businesses', 'subscriptionTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'subscription_type' => 'required|in:1_month,6_months,1_year,lifetime',
        ]);

        $license = License::create([
            'business_id' => $validated['business_id'],
            'license_key' => License::generateLicenseKey(),
            'subscription_type' => $validated['subscription_type'],
            'expiration_date' => License::calculateExpirationDate($validated['subscription_type']),
            'status' => License::STATUS_PENDING,
        ]);

        return redirect()->route('super-admin.licenses.index')
            ->with('success', 'License generated successfully. Key: ' . $license->license_key);
    }

    public function generateKey(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'subscription_type' => 'required|in:1_month,6_months,1_year,lifetime',
        ]);

        $license = License::create([
            'business_id' => $validated['business_id'],
            'license_key' => License::generateLicenseKey(),
            'subscription_type' => $validated['subscription_type'],
            'expiration_date' => null, // Will be set on activation
            'status' => License::STATUS_PENDING,
        ]);

        return response()->json([
            'success' => true,
            'license_key' => $license->license_key,
            'message' => 'License key generated successfully',
        ]);
    }

    public function renew(License $license)
    {
        $license->renew();

        return back()->with('success', 'License renewed successfully. New expiration: ' . $license->expiration_date->format('M d, Y'));
    }

    public function destroy(License $license)
    {
        $license->delete();

        return back()->with('success', 'License deleted successfully.');
    }
}
