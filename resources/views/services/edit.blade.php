@extends('layouts.app')

@section('title', 'Edit Service')
@section('page-title', 'Edit Service')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form action="{{ route('services.update', $service) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Service Name *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $service->name) }}"
                    required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
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
                >{{ old('description', $service->description) }}</textarea>
                @error('description')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="p-4 bg-teal-50 rounded-xl">
                <h4 class="font-medium text-teal-800 mb-4">Pricing per Load</h4>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="price_per_load" class="block text-sm font-medium text-slate-700 mb-2">Price per Load (₱) *</label>
                        <input 
                            type="number" 
                            id="price_per_load" 
                            name="price_per_load" 
                            value="{{ old('price_per_load', $service->price_per_load) }}"
                            required
                            step="0.01"
                            min="0"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                        >
                        @error('price_per_load')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="load_weight" class="block text-sm font-medium text-slate-700 mb-2">Load Weight (kg) *</label>
                        <input 
                            type="number" 
                            id="load_weight" 
                            name="load_weight" 
                            value="{{ old('load_weight', $service->load_weight) }}"
                            required
                            step="0.5"
                            min="1"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                        >
                        @error('load_weight')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $service->is_active ? 'checked' : '' }} class="w-5 h-5 text-teal-500 border-slate-300 rounded focus:ring-teal-500">
                    <span class="text-sm text-slate-700">Active (available for orders)</span>
                </label>
            </div>

            @if($products->count() > 0)
                <div class="pt-6 border-t border-slate-200">
                    <h3 class="font-medium text-slate-800 mb-4">Assign Products (Inventory)</h3>
                    
                    <div class="space-y-3">
                        @foreach($products as $index => $product)
                            @php
                                $assigned = $service->products->firstWhere('id', $product->id);
                                $qtyPerLoad = $assigned ? $assigned->pivot->quantity_per_load : '';
                            @endphp
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
                                <div class="flex-1">
                                    <p class="font-medium text-slate-800">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-500">Stock: {{ $product->quantity }} {{ $product->unit }}</p>
                                </div>
                                <input type="hidden" name="products[{{ $index }}][id]" value="{{ $product->id }}">
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="number" 
                                        name="products[{{ $index }}][quantity_per_load]"
                                        value="{{ $qtyPerLoad }}"
                                        step="0.01"
                                        min="0"
                                        class="w-24 px-3 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none text-sm"
                                        placeholder="0"
                                    >
                                    <span class="text-sm text-slate-500">{{ $product->unit }}/load</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-teal-500 text-white font-medium rounded-xl hover:bg-teal-600 transition-colors">
                    Update Service
                </button>
                <a href="{{ route('services.index') }}" class="px-6 py-3 text-slate-600 hover:text-slate-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
