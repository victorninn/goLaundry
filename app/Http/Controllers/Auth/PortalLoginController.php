<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LaundryOrder;
use Illuminate\Http\Request;

class PortalLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.portal-login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'order_number' => 'nullable|string',
        ]);

        // Find customer by phone number
        $customer = Customer::where('phone', $validated['phone'])->first();

        if (!$customer) {
            return back()->withErrors([
                'phone' => 'No customer found with this phone number.',
            ]);
        }

        // Store customer info in session for portal access
        session([
            'portal_customer_id' => $customer->id,
            'portal_customer_name' => $customer->name,
            'portal_business_id' => $customer->business_id,
        ]);

        // If order number provided, redirect to specific order
        if (!empty($validated['order_number'])) {
            $order = LaundryOrder::where('order_number', $validated['order_number'])
                ->where('customer_id', $customer->id)
                ->first();
            
            if ($order) {
                return redirect()->route('portal.order', $order->id);
            }
        }

        return redirect()->route('portal.track');
    }

    public function logout(Request $request)
    {
        session()->forget(['portal_customer_id', 'portal_customer_name', 'portal_business_id']);
        return redirect()->route('portal.login');
    }
}
