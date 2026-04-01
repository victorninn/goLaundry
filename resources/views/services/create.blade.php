@extends('layouts.app')

@section('title', 'Add Service')
@section('page-title', 'Add Service')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form action="{{ route('services.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Service Name *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                    placeholder="e.g., Regular Wash, Premium Wash"
                >
                @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="3"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none resize-none"
                    placeholder="Describe this service"
                >{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label for="price_per_kilo" class="block text-sm font-medium text-slate-700 mb-2">Price per Kilo (₱) *</label>
                    <input 
                        type="number" 
                        id="price_per_kilo" 
                        name="price_per_kilo" 
                        value="{{ old('price_per_kilo') }}"
                        required
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                        placeholder="35.00"
                    >
                    @error('price_per_kilo')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-teal-500 border-slate-300 rounded focus:ring-teal-500">
                        <span class="text-sm text-slate-700">Active (available for orders)</span>
                    </label>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="pt-6 border-t border-slate-200">
                    <h3 class="font-medium text-slate-800 mb-4">Assign Products (Inventory)</h3>
                    <p class="text-sm text-slate-500 mb-4">Specify how much of each product is used per kilo of laundry</p>
                    
                    <div class="space-y-3">
                        @foreach($products as $index => $product)
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
                                <div class="flex-1">
                                    <p class="font-medium text-slate-800">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-500">Stock: {{ $product->quantity }} {{ $product->unit }}</p>
                                </div>
                                <input type="hidden" name="products[{{ $index }}][id]" value="{{ $product->id }}">
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="number" 
                                        name="products[{{ $index }}][quantity_per_kilo]"
                                        step="0.01"
                                        min="0"
                                        class="w-24 px-3 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none text-sm"
                                        placeholder="0"
                                    >
                                    <span class="text-sm text-slate-500">{{ $product->unit }}/kg</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-teal-500 text-white font-medium rounded-xl hover:bg-teal-600 transition-colors">
                    Save Service
                </button>
                <a href="{{ route('services.index') }}" class="px-6 py-3 text-slate-600 hover:text-slate-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
