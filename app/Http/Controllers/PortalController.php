<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function track()
    {
        $customerId = session('portal_customer_id');
        
        if (!$customerId) {
            return redirect()->route('portal.login');
        }

        $orders = LaundryOrder::where('customer_id', $customerId)
            ->with('items.service')
            ->latest()
            ->paginate(10);

        return view('portal.track', compact('orders'));
    }

    public function showOrder($id)
    {
        $customerId = session('portal_customer_id');
        
        if (!$customerId) {
            return redirect()->route('portal.login');
        }

        $order = LaundryOrder::where('id', $id)
            ->where('customer_id', $customerId)
            ->with(['items.service', 'business'])
            ->firstOrFail();

        return view('portal.status', compact('order'));
    }

    public function quickTrack(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string',
        ]);

        $order = LaundryOrder::where('order_number', $validated['order_number'])
            ->with(['items.service', 'business', 'customer'])
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        return view('portal.status', compact('order'));
    }
}
