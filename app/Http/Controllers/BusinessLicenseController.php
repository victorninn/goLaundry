<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;

class BusinessLicenseController extends Controller
{
    protected function getBusinessId()
    {
        return auth()->user()->business_id;
    }

    public function index()
    {
        $businessId = $this->getBusinessId();
        $license = License::where('business_id', $businessId)
            ->where('status', '!=', License::STATUS_PENDING)
            ->latest()
            ->first();

        return view('license.index', compact('license'));
    }

    public function activate(Request $request)
    {
        $validated = $request->validate([
            'license_key' => 'required|string',
        ]);

        $businessId = $this->getBusinessId();

        // Find the license
        $license = License::where('license_key', $validated['license_key'])
            ->where('business_id', $businessId)
            ->where('status', License::STATUS_PENDING)
            ->first();

        if (!$license) {
            return back()->with('error', 'Invalid license key or license already activated.');
        }

        // Activate the license
        $license->activate();

        return back()->with('success', 'License activated successfully!');
    }

    public function checkStatus()
    {
        $businessId = $this->getBusinessId();
        $license = License::where('business_id', $businessId)
            ->where('status', License::STATUS_ACTIVE)
            ->latest()
            ->first();

        if (!$license) {
            return response()->json([
                'valid' => false,
                'message' => 'No active license found',
            ]);
        }

        $license->checkAndUpdateStatus();

        return response()->json([
            'valid' => $license->isActive(),
            'status' => $license->status,
            'expiration_date' => $license->expiration_date?->format('M d, Y'),
            'days_remaining' => $license->days_remaining,
            'subscription_type' => $license->subscription_type,
        ]);
    }
}
