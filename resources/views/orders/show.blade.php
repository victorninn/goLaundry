@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
<div class="max-w-4xl space-y-6">
    <!-- Order Header -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $order->order_number }}</h2>
                <p class="text-slate-500">Received {{ $order->date_received->format('F d, Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @include('components.status-badge', ['status' => $order->status])
                @if($order->status !== 'claimed')
                    <a href="{{ route('orders.edit', $order) }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                        Edit Order
                    </a>
                @endif
            </div>
        </div>

        <div class="grid sm:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-slate-500 mb-1">Customer</p>
                <a href="{{ route('customers.show', $order->customer) }}" class="font-medium text-teal-600 hover:text-teal-700">
                    {{ $order->customer->name }}
                </a>
                @if($order->customer->phone)
                    <p class="text-sm text-slate-500">{{ $order->customer->phone }}</p>
                @endif
            </div>
            <div>
                <p class="text-sm text-slate-500 mb-1">Expected Release</p>
                <p class="font-medium text-slate-800">{{ $order->date_release?->format('F d, Y') ?? 'Not set' }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500 mb-1">Total Weight</p>
                <p class="font-medium text-slate-800">{{ number_format($order->total_kilos, 2) }} kg</p>
            </div>
        </div>

        @if($order->notes)
            <div class="mt-6 pt-6 border-t border-slate-100">
                <p class="text-sm text-slate-500 mb-1">Notes</p>
                <p class="text-slate-700">{{ $order->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Services</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Kilos</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Price/kg</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $item->service->name }}</td>
                            <td class="px-6 py-4 text-right text-slate-600">{{ number_format($item->kilos, 2) }}</td>
                            <td class="px-6 py-4 text-right text-slate-600">₱{{ number_format($item->price_per_kilo, 2) }}</td>
                            <td class="px-6 py-4 text-right font-medium text-slate-800">₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-semibold text-slate-700">Total</td>
                        <td class="px-6 py-4 text-right text-xl font-bold text-teal-600">₱{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Payment</h3>
        
        <div class="grid sm:grid-cols-3 gap-6 mb-6">
            <div class="p-4 bg-slate-50 rounded-xl">
                <p class="text-sm text-slate-500 mb-1">Total Amount</p>
                <p class="text-xl font-bold text-slate-800">₱{{ number_format($order->total_amount, 2) }}</p>
            </div>
            <div class="p-4 bg-emerald-50 rounded-xl">
                <p class="text-sm text-emerald-600 mb-1">Amount Paid</p>
                <p class="text-xl font-bold text-emerald-700">₱{{ number_format($order->amount_paid, 2) }}</p>
            </div>
            <div class="p-4 {{ $order->balance > 0 ? 'bg-rose-50' : 'bg-slate-50' }} rounded-xl">
                <p class="text-sm {{ $order->balance > 0 ? 'text-rose-600' : 'text-slate-500' }} mb-1">Balance</p>
                <p class="text-xl font-bold {{ $order->balance > 0 ? 'text-rose-700' : 'text-slate-800' }}">₱{{ number_format($order->balance, 2) }}</p>
            </div>
        </div>

        @if($order->balance > 0 && $order->status !== 'claimed')
            <form action="{{ route('orders.payment', $order) }}" method="POST" class="flex items-end gap-4">
                @csrf
                <div class="flex-1 max-w-xs">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Record Payment</label>
                    <input type="number" name="amount" step="0.01" min="0.01" max="{{ $order->balance }}" required 
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        placeholder="Enter amount">
                </div>
                <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                    Record Payment
                </button>
            </form>
        @endif
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('orders.index') }}" class="text-slate-600 hover:text-slate-800">
            ← Back to Orders
        </a>
        @if(in_array($order->status, ['pending', 'cancelled']))
            <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                    Delete Order
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
