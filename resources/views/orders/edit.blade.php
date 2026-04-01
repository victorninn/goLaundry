@extends('layouts.app')

@section('title', 'Edit Order')
@section('page-title', 'Edit Order')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="mb-6 pb-6 border-b border-slate-100">
            <h2 class="text-xl font-semibold text-slate-800">{{ $order->order_number }}</h2>
            <p class="text-slate-500">Customer: {{ $order->customer->name }}</p>
        </div>

        <form action="{{ route('orders.update', $order) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-2">Status *</label>
                    <select 
                        id="status" 
                        name="status" 
                        required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                    >
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $order->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date_release" class="block text-sm font-medium text-slate-700 mb-2">Expected Release</label>
                    <input 
                        type="date" 
                        id="date_release" 
                        name="date_release" 
                        value="{{ old('date_release', $order->date_release?->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                    >
                </div>
            </div>

            <div>
                <label for="amount_paid" class="block text-sm font-medium text-slate-700 mb-2">Amount Paid</label>
                <input 
                    type="number" 
                    id="amount_paid" 
                    name="amount_paid" 
                    value="{{ old('amount_paid', $order->amount_paid) }}"
                    step="0.01"
                    min="0"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                >
                <p class="mt-1 text-sm text-slate-500">Total: ₱{{ number_format($order->total_amount, 2) }}</p>
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="3"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none resize-none"
                >{{ old('notes', $order->notes) }}</textarea>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-teal-500 text-white font-medium rounded-xl hover:bg-teal-600 transition-colors">
                    Update Order
                </button>
                <a href="{{ route('orders.show', $order) }}" class="px-6 py-3 text-slate-600 hover:text-slate-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
