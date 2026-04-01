@extends('layouts.app')

@section('title', 'Products')
@section('page-title', 'Products (Inventory)')
@section('page-description', 'Manage your laundry supplies inventory')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Search products..."
                class="px-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
            >
            <label class="flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg cursor-pointer">
                <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} onchange="this.form.submit()" class="w-4 h-4 text-teal-500 border-slate-300 rounded">
                <span class="text-sm text-slate-600">Low Stock</span>
            </label>
        </form>
        <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Product
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800">{{ $product->name }}</p>
                                @if($product->description)
                                    <p class="text-sm text-slate-500">{{ Str::limit($product->description, 50) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $product->unit }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold {{ $product->isLowStock() ? 'text-rose-600' : 'text-slate-800' }}">
                                    {{ number_format($product->quantity, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">₱{{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4">
                                @if($product->isLowStock())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">Low Stock</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">In Stock</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <!-- Add Stock Form -->
                                    <form action="{{ route('products.add-stock', $product) }}" method="POST" class="flex items-center gap-1">
                                        @csrf
                                        <input type="number" name="quantity" step="0.01" min="0.01" required class="w-20 px-2 py-1 text-sm border border-slate-200 rounded" placeholder="Qty">
                                        <button type="submit" class="p-1 text-emerald-600 hover:bg-emerald-50 rounded" title="Add Stock">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('products.edit', $product) }}" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                No products found. <a href="{{ route('products.create') }}" class="text-teal-600 hover:underline">Add your first product</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
