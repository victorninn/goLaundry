<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected function getBusinessId()
    {
        return auth()->user()->business_id;
    }

    public function index(Request $request)
    {
        $query = Product::byBusiness($this->getBusinessId());

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->boolean('low_stock')) {
            $query->lowStock();
        }

        $products = $query->latest()->paginate(15);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['business_id'] = $this->getBusinessId();

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $this->authorizeBusinessAccess($product);

        return view('products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->authorizeBusinessAccess($product);

        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeBusinessAccess($product);

        // Check if product is assigned to any service
        if ($product->services()->exists()) {
            return back()->with('error', 'Cannot delete product that is assigned to services.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function addStock(Request $request, Product $product)
    {
        $this->authorizeBusinessAccess($product);

        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $product->addStock($validated['quantity']);

        return back()->with('success', 'Stock added successfully.');
    }

    protected function authorizeBusinessAccess($product)
    {
        if ($product->business_id !== $this->getBusinessId()) {
            abort(403);
        }
    }
}
