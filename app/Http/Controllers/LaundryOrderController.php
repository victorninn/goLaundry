<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaundryOrderRequest;
use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\LaundryOrderItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaundryOrderController extends Controller
{
    protected function getBusinessId()
    {
        return auth()->user()->business_id;
    }

    public function index(Request $request)
    {
        $query = LaundryOrder::byBusiness($this->getBusinessId())
            ->with('customer');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20);
        $statuses = LaundryOrder::getStatuses();

        return view('orders.index', compact('orders', 'statuses'));
    }

    public function create()
    {
        $customers = Customer::byBusiness($this->getBusinessId())->get();
        $services = Service::byBusiness($this->getBusinessId())->active()->get();

        return view('orders.create', compact('customers', 'services'));
    }

    public function store(LaundryOrderRequest $request)
    {
        $businessId = $this->getBusinessId();

        DB::beginTransaction();
        try {
            // Create order
            $order = LaundryOrder::create([
                'business_id' => $businessId,
                'customer_id' => $request->customer_id,
                'order_number' => LaundryOrder::generateOrderNumber($businessId),
                'date_received' => $request->date_received,
                'date_release' => $request->date_release,
                'notes' => $request->notes,
                'amount_paid' => $request->amount_paid ?? 0,
                'status' => $request->status ?? 'pending',
            ]);

            // Create order items
            foreach ($request->items as $item) {
                $service = Service::find($item['service_id']);
                
                $orderItem = LaundryOrderItem::create([
                    'laundry_order_id' => $order->id,
                    'service_id' => $item['service_id'],
                    'kilos' => $item['kilos'],
                    'price_per_kilo' => $service->price_per_kilo,
                    'subtotal' => $item['kilos'] * $service->price_per_kilo,
                ]);

                // Deduct product stock
                $orderItem->deductProductStock();
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order created successfully. Order #' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating order: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(LaundryOrder $order)
    {
        $this->authorizeBusinessAccess($order);

        $order->load(['customer', 'items.service']);

        return view('orders.show', compact('order'));
    }

    public function edit(LaundryOrder $order)
    {
        $this->authorizeBusinessAccess($order);

        if ($order->status === 'claimed') {
            return back()->with('error', 'Cannot edit claimed orders.');
        }

        $order->load('items.service');
        $customers = Customer::byBusiness($this->getBusinessId())->get();
        $services = Service::byBusiness($this->getBusinessId())->active()->get();
        $statuses = LaundryOrder::getStatuses();

        return view('orders.edit', compact('order', 'customers', 'services', 'statuses'));
    }

    public function update(Request $request, LaundryOrder $order)
    {
        $this->authorizeBusinessAccess($order);

        $validated = $request->validate([
            'status' => 'required|in:pending,washing,drying,folding,ready,claimed,cancelled',
            'amount_paid' => 'nullable|numeric|min:0',
            'date_release' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    public function updateStatus(Request $request, LaundryOrder $order)
    {
        $this->authorizeBusinessAccess($order);

        $validated = $request->validate([
            'status' => 'required|in:pending,washing,drying,folding,ready,claimed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status updated successfully.');
    }

    public function recordPayment(Request $request, LaundryOrder $order)
    {
        $this->authorizeBusinessAccess($order);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $order->increment('amount_paid', $validated['amount']);

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function destroy(LaundryOrder $order)
    {
        $this->authorizeBusinessAccess($order);

        if (!in_array($order->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Can only delete pending or cancelled orders.');
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    protected function authorizeBusinessAccess($order)
    {
        if ($order->business_id !== $this->getBusinessId()) {
            abort(403);
        }
    }
}
