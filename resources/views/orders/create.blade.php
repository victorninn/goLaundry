@extends('layouts.app')

@section('title', 'New Order')
@section('page-title', 'Create New Order')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6" id="orderForm">
        @csrf

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Customer & Schedule</h3>
            
            <div class="grid sm:grid-cols-3 gap-6">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-slate-700 mb-2">Customer *</label>
                    <select 
                        id="customer_id" 
                        name="customer_id" 
                        required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                    >
                        <option value="">Select customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', request('customer_id')) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} {{ $customer->phone ? "({$customer->phone})" : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="date_received" class="block text-sm font-medium text-slate-700 mb-2">Date Received *</label>
                    <input 
                        type="date" 
                        id="date_received" 
                        name="date_received" 
                        value="{{ old('date_received', today()->format('Y-m-d')) }}"
                        required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                    >
                    @error('date_received')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="date_release" class="block text-sm font-medium text-slate-700 mb-2">Expected Release</label>
                    <input 
                        type="date" 
                        id="date_release" 
                        name="date_release" 
                        value="{{ old('date_release') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                    >
                    @error('date_release')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-slate-800">Services (Laundry)</h3>
                <button type="button" onclick="addServiceItem()" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                    + Add Service
                </button>
            </div>

            <div id="services-container" class="space-y-4">
                <div class="service-row flex flex-wrap items-end gap-4 p-4 bg-slate-50 rounded-xl">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Service *</label>
                        <select name="items[0][service_id]" required class="service-select w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                            <option value="">Select service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price_per_load }}" data-weight="{{ $service->load_weight }}">
                                    {{ $service->name }} - ₱{{ number_format($service->price_per_load, 2) }}/load ({{ number_format($service->load_weight, 0) }}kg)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-32">
                        <label class="block text-sm font-medium text-slate-700 mb-2">No. of Loads *</label>
                        <input type="number" name="items[0][num_loads]" min="1" required class="loads-input w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none" placeholder="1">
                    </div>
                    <div class="w-32">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Subtotal</label>
                        <p class="service-subtotal px-4 py-3 text-lg font-semibold text-slate-800">₱0.00</p>
                    </div>
                    <button type="button" onclick="removeServiceItem(this)" class="remove-service-btn p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg mb-1" style="display:none;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-slate-200 flex justify-end">
                <div class="text-right">
                    <span class="text-sm text-slate-500">Services Total:</span>
                    <span id="servicesTotal" class="ml-2 text-lg font-bold text-slate-800">₱0.00</span>
                </div>
            </div>

            @error('items')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <!-- Products Section -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-slate-800">Products (Optional)</h3>
                    <p class="text-sm text-slate-500">Add detergent, fabric conditioner, or other products</p>
                </div>
                <button type="button" onclick="addProductItem()" class="text-sm text-sky-600 hover:text-sky-700 font-medium">
                    + Add Product
                </button>
            </div>

            <div id="products-container" class="space-y-4">
            </div>
            
            <div id="products-empty" class="text-center py-8 text-slate-400">
                <p>No products added. Click "+ Add Product" to include products in this order.</p>
            </div>
            
            <div class="mt-4 pt-4 border-t border-slate-200 flex justify-end">
                <div class="text-right">
                    <span class="text-sm text-slate-500">Products Total:</span>
                    <span id="productsTotal" class="ml-2 text-lg font-bold text-slate-800">₱0.00</span>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Payment Summary</h3>
            
            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label for="amount_paid" class="block text-sm font-medium text-slate-700 mb-2">Amount Paid</label>
                    <input 
                        type="number" 
                        id="amount_paid" 
                        name="amount_paid" 
                        value="{{ old('amount_paid', 0) }}"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                    >
                </div>

                <div class="bg-gradient-to-br from-teal-50 to-sky-50 rounded-xl p-4">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Services:</span>
                            <span id="summaryServices" class="font-medium">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Products:</span>
                            <span id="summaryProducts" class="font-medium">₱0.00</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-slate-200">
                            <span class="font-semibold text-slate-800">Total Amount:</span>
                            <span id="totalAmount" class="text-xl font-bold text-teal-600">₱0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="2"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none resize-none"
                    placeholder="Special instructions or notes"
                >{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-3 bg-teal-500 text-white font-medium rounded-xl hover:bg-teal-600 transition-colors">
                Create Order
            </button>
            <a href="{{ route('orders.index') }}" class="px-6 py-3 text-slate-600 hover:text-slate-800">
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let serviceIndex = 1;
    let productIndex = 0;
    const services = @json($services);
    const products = @json($products);

    function addServiceItem() {
        const container = document.getElementById('services-container');
        const template = `
            <div class="service-row flex flex-wrap items-end gap-4 p-4 bg-slate-50 rounded-xl">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Service *</label>
                    <select name="items[${serviceIndex}][service_id]" required class="service-select w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                        <option value="">Select service</option>
                        ${services.map(s => `<option value="${s.id}" data-price="${s.price_per_load}" data-weight="${s.load_weight}">${s.name} - ₱${parseFloat(s.price_per_load).toFixed(2)}/load (${parseFloat(s.load_weight).toFixed(0)}kg)</option>`).join('')}
                    </select>
                </div>
                <div class="w-32">
                    <label class="block text-sm font-medium text-slate-700 mb-2">No. of Loads *</label>
                    <input type="number" name="items[${serviceIndex}][num_loads]" min="1" required class="loads-input w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none" placeholder="1">
                </div>
                <div class="w-32">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Subtotal</label>
                    <p class="service-subtotal px-4 py-3 text-lg font-semibold text-slate-800">₱0.00</p>
                </div>
                <button type="button" onclick="removeServiceItem(this)" class="remove-service-btn p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        serviceIndex++;
        attachListeners();
        updateRemoveButtons();
    }

    function removeServiceItem(btn) {
        btn.closest('.service-row').remove();
        calculateTotal();
        updateRemoveButtons();
    }

    function addProductItem() {
        const container = document.getElementById('products-container');
        document.getElementById('products-empty').style.display = 'none';
        
        const template = `
            <div class="product-row flex flex-wrap items-end gap-4 p-4 bg-sky-50 rounded-xl">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Product</label>
                    <select name="order_products[${productIndex}][product_id]" class="product-select w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-transparent outline-none">
                        <option value="">Select product</option>
                        ${products.map(p => `<option value="${p.id}" data-price="${p.price}" data-stock="${p.quantity}" data-unit="${p.unit}">${p.name} - ₱${parseFloat(p.price).toFixed(2)} (${parseFloat(p.quantity).toFixed(0)} ${p.unit} in stock)</option>`).join('')}
                    </select>
                </div>
                <div class="w-28">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Qty</label>
                    <input type="number" name="order_products[${productIndex}][quantity]" min="1" class="qty-input w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-transparent outline-none" placeholder="1">
                </div>
                <div class="w-32">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Subtotal</label>
                    <p class="product-subtotal px-4 py-3 text-lg font-semibold text-slate-800">₱0.00</p>
                </div>
                <button type="button" onclick="removeProductItem(this)" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        productIndex++;
        attachListeners();
    }

    function removeProductItem(btn) {
        btn.closest('.product-row').remove();
        calculateTotal();
        
        if (document.querySelectorAll('.product-row').length === 0) {
            document.getElementById('products-empty').style.display = 'block';
        }
    }

    function updateRemoveButtons() {
        const serviceRows = document.querySelectorAll('.service-row');
        serviceRows.forEach((row) => {
            const btn = row.querySelector('.remove-service-btn');
            if (btn) {
                btn.style.display = serviceRows.length > 1 ? 'block' : 'none';
            }
        });
    }

    function calculateTotal() {
        let servicesTotal = 0;
        let productsTotal = 0;

        document.querySelectorAll('.service-row').forEach(row => {
            const select = row.querySelector('.service-select');
            const loadsInput = row.querySelector('.loads-input');
            const subtotalEl = row.querySelector('.service-subtotal');
            
            const selectedOption = select.options[select.selectedIndex];
            const price = parseFloat(selectedOption?.dataset?.price || 0);
            const loads = parseInt(loadsInput.value || 0);
            const subtotal = price * loads;
            
            subtotalEl.textContent = `₱${subtotal.toFixed(2)}`;
            servicesTotal += subtotal;
        });

        document.querySelectorAll('.product-row').forEach(row => {
            const select = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');
            const subtotalEl = row.querySelector('.product-subtotal');
            
            const selectedOption = select.options[select.selectedIndex];
            const price = parseFloat(selectedOption?.dataset?.price || 0);
            const qty = parseInt(qtyInput.value || 0);
            const subtotal = price * qty;
            
            subtotalEl.textContent = `₱${subtotal.toFixed(2)}`;
            productsTotal += subtotal;
        });

        const total = servicesTotal + productsTotal;

        document.getElementById('servicesTotal').textContent = `₱${servicesTotal.toFixed(2)}`;
        document.getElementById('productsTotal').textContent = `₱${productsTotal.toFixed(2)}`;
        document.getElementById('summaryServices').textContent = `₱${servicesTotal.toFixed(2)}`;
        document.getElementById('summaryProducts').textContent = `₱${productsTotal.toFixed(2)}`;
        document.getElementById('totalAmount').textContent = `₱${total.toFixed(2)}`;
    }

    function attachListeners() {
        document.querySelectorAll('.service-select, .loads-input, .product-select, .qty-input').forEach(el => {
            el.removeEventListener('change', calculateTotal);
            el.removeEventListener('input', calculateTotal);
            el.addEventListener('change', calculateTotal);
            el.addEventListener('input', calculateTotal);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        attachListeners();
        updateRemoveButtons();
    });
</script>
@endpush
@endsection
