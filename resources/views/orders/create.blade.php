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

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-slate-800">Services</h3>
                <button type="button" onclick="addItem()" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                    + Add Service
                </button>
            </div>

            <div id="items-container" class="space-y-4">
                <div class="item-row flex flex-wrap items-end gap-4 p-4 bg-slate-50 rounded-xl">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Service *</label>
                        <select name="items[0][service_id]" required class="service-select w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                            <option value="">Select service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price_per_kilo }}">
                                    {{ $service->name }} - ₱{{ number_format($service->price_per_kilo, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-32">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Kilos *</label>
                        <input type="number" name="items[0][kilos]" step="0.1" min="0.1" required class="kilos-input w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none" placeholder="0.0">
                    </div>
                    <div class="w-32">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Subtotal</label>
                        <p class="subtotal px-4 py-3 text-lg font-semibold text-slate-800">₱0.00</p>
                    </div>
                    <button type="button" onclick="removeItem(this)" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg mb-1" style="display:none;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            @error('items')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
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

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Total Amount</label>
                    <p id="totalAmount" class="px-4 py-3 text-2xl font-bold text-teal-600">₱0.00</p>
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
    let itemIndex = 1;
    const services = @json($services);

    function addItem() {
        const container = document.getElementById('items-container');
        const template = `
            <div class="item-row flex flex-wrap items-end gap-4 p-4 bg-slate-50 rounded-xl">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Service *</label>
                    <select name="items[${itemIndex}][service_id]" required class="service-select w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                        <option value="">Select service</option>
                        ${services.map(s => `<option value="${s.id}" data-price="${s.price_per_kilo}">${s.name} - ₱${parseFloat(s.price_per_kilo).toFixed(2)}/kg</option>`).join('')}
                    </select>
                </div>
                <div class="w-32">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Kilos *</label>
                    <input type="number" name="items[${itemIndex}][kilos]" step="0.1" min="0.1" required class="kilos-input w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none" placeholder="0.0">
                </div>
                <div class="w-32">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Subtotal</label>
                    <p class="subtotal px-4 py-3 text-lg font-semibold text-slate-800">₱0.00</p>
                </div>
                <button type="button" onclick="removeItem(this)" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', template);
        itemIndex++;
        attachListeners();
    }

    function removeItem(btn) {
        btn.closest('.item-row').remove();
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.service-select');
            const kilosInput = row.querySelector('.kilos-input');
            const subtotalEl = row.querySelector('.subtotal');
            
            const selectedOption = select.options[select.selectedIndex];
            const price = parseFloat(selectedOption?.dataset?.price || 0);
            const kilos = parseFloat(kilosInput.value || 0);
            const subtotal = price * kilos;
            
            subtotalEl.textContent = `₱${subtotal.toFixed(2)}`;
            total += subtotal;
        });
        document.getElementById('totalAmount').textContent = `₱${total.toFixed(2)}`;
    }

    function attachListeners() {
        document.querySelectorAll('.service-select, .kilos-input').forEach(el => {
            el.removeEventListener('change', calculateTotal);
            el.removeEventListener('input', calculateTotal);
            el.addEventListener('change', calculateTotal);
            el.addEventListener('input', calculateTotal);
        });
    }

    document.addEventListener('DOMContentLoaded', attachListeners);
</script>
@endpush
@endsection
