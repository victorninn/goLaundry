@extends('layouts.app')

@section('title', $customer->name)
@section('page-title', $customer->name)
@section('page-description', 'Customer details and order history')

@section('content')
<div class="space-y-6">
    <!-- Customer Info Card -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-sky-500 rounded-xl flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-slate-800">{{ $customer->name }}</h2>
                    <p class="text-slate-500">{{ $customer->phone ?? 'No phone' }} • {{ $customer->email ?? 'No email' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('orders.create', ['customer_id' => $customer->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Order
                </a>
                <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                    Edit
                </a>
            </div>
        </div>
        @if($customer->address)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-sm text-slate-500">Address</p>
                <p class="text-slate-700">{{ $customer->address }}</p>
            </div>
        @endif
    </div>

    <!-- Order History -->
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Order History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kilos</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customer->laundryOrders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('orders.show', $order) }}" class="text-teal-600 hover:text-teal-700 font-medium">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $order->date_received->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ number_format($order->total_kilos, 1) }} kg</td>
                            <td class="px-6 py-4 text-slate-800 font-medium">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                @include('components.status-badge', ['status' => $order->status])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                No orders yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
