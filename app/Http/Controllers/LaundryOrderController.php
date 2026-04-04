<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaundryOrderRequest;
use App\Models\Customer;
use App\Models\LaundryOrder;
use App\Models\LaundryOrderItem;
use App\Models\LaundryOrderProduct;
use App\Models\Product;
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

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

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
        $products = Product::byBusiness($this->getBusinessId())->where('quantity', '>', 0)->get();

        return view('orders.create', compact('customers', 'services', 'products'));
    }

    public function store(Request $request)
    {
        $businessId = $this->getBusinessId();

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date_received' => 'required|date',
            'date_release' => 'nullable|date|after_or_equal:date_received',
            'notes' => 'nullable|string|max:1000',
            'amount_paid' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,washing,drying,folding,ready,claimed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.num_loads' => 'required|integer|min:1',
            'order_products' => 'nullable|array',
            'order_products.*.product_id' => 'nullable|exists:products,id',
            'order_products.*.quantity' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $order = LaundryOrder::create([
                'business_id' => $businessId,
                'customer_id' => $validated['customer_id'],
                'order_number' => LaundryOrder::generateOrderNumber($businessId),
                'date_received' => $validated['date_received'],
                'date_release' => $validated['date_release'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'amount_paid' => $validated['amount_paid'] ?? 0,
                'status' => $validated['status'] ?? 'pending',
            ]);

            $servicesTotal = 0;
            $productsTotal = 0;
            $totalLoads = 0;

            // Create order items (services) - per load pricing
            foreach ($validated['items'] as $item) {
                $service = Service::find($item['service_id']);
                $numLoads = (int) $item['num_loads'];
                $subtotal = $numLoads * $service->price_per_load;
                
                LaundryOrderItem::create([
                    'laundry_order_id' => $order->id,
                    'service_id' => $item['service_id'],
                    'num_loads' => $numLoads,
                    'price_per_load' => $service->price_per_load,
                    'subtotal' => $subtotal,
                ]);

                $servicesTotal += $subtotal;
                $totalLoads += $numLoads;
            }

            // Create order products
            if (!empty($validated['order_products'])) {
                foreach ($validated['order_products'] as $productItem) {
                    if (empty($productItem['product_id']) || empty($productItem['quantity'])) {
                        continue;
                    }
                    
                    $product = Product::find($productItem['product_id']);
                    
                    if ($product->quantity < $productItem['quantity']) {
                        throw new \Exception("Insufficient stock for {$product->name}");
                    }
                    
                    $subtotal = $productItem['quantity'] * $product->price;
                    
                    LaundryOrderProduct::create([
                        'laundry_order_id' => $order->id,
                        'product_id' => $productItem['product_id'],
                        'quantity' => $productItem['quantity'],
                        'unit_price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);

                    $product->deductStock($productItem['quantity']);
                    
                    $productsTotal += $subtotal;
                }
            }

            // Update order totals
            $order->update([
                'total_loads' => $totalLoads,
                'services_total' => $servicesTotal,
                'products_total' => $productsTotal,
                'total_amount' => $servicesTotal + $productsTotal,
            ]);

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

        $order->load(['customer', 'items.service', 'orderProducts.product']);

        return view('orders.show', compact('order'));
    }

    public function edit(LaundryOrder $order)
    {
        $this->authorizeBusinessAccess($order);

        if ($order->status === 'claimed') {
            return back()->with('error', 'Cannot edit claimed orders.');
        }

        $order->load(['items.service', 'orderProducts.product']);
        $customers = Customer::byBusiness($this->getBusinessId())->get();
        $services = Service::byBusiness($this->getBusinessId())->active()->get();
        $products = Product::byBusiness($this->getBusinessId())->get();
        $statuses = LaundryOrder::getStatuses();

        return view('orders.edit', compact('order', 'customers', 'services', 'products', 'statuses'));
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
