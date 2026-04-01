<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected function getBusinessId()
    {
        return auth()->user()->business_id;
    }

    public function index()
    {
        $services = Service::byBusiness($this->getBusinessId())
            ->with('products')
            ->latest()
            ->paginate(15);

        return view('services.index', compact('services'));
    }

    public function create()
    {
        $products = Product::byBusiness($this->getBusinessId())->get();

        return view('services.create', compact('products'));
    }

    public function store(ServiceRequest $request)
    {
        $data = $request->validated();
        $data['business_id'] = $this->getBusinessId();

        $service = Service::create($data);

        // Attach products with quantities
        if ($request->has('products')) {
            foreach ($request->products as $productData) {
                if (!empty($productData['id']) && !empty($productData['quantity_per_kilo'])) {
                    $service->products()->attach($productData['id'], [
                        'quantity_per_kilo' => $productData['quantity_per_kilo']
                    ]);
                }
            }
        }

        return redirect()->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $this->authorizeBusinessAccess($service);

        $products = Product::byBusiness($this->getBusinessId())->get();
        $service->load('products');

        return view('services.edit', compact('service', 'products'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $this->authorizeBusinessAccess($service);

        $service->update($request->validated());

        // Sync products
        $syncData = [];
        if ($request->has('products')) {
            foreach ($request->products as $productData) {
                if (!empty($productData['id']) && !empty($productData['quantity_per_kilo'])) {
                    $syncData[$productData['id']] = [
                        'quantity_per_kilo' => $productData['quantity_per_kilo']
                    ];
                }
            }
        }
        $service->products()->sync($syncData);

        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $this->authorizeBusinessAccess($service);

        // Check if service has orders
        if ($service->laundryOrderItems()->exists()) {
            return back()->with('error', 'Cannot delete service with existing orders.');
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }

    protected function authorizeBusinessAccess($service)
    {
        if ($service->business_id !== $this->getBusinessId()) {
            abort(403);
        }
    }
}
