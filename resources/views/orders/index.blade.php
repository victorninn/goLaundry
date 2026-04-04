@extends('layouts.app')

@section('title', 'Orders')
@section('page-title', 'Laundry Orders')
@section('page-description', 'Manage customer orders')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <form action="{{ route('orders.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Search order # or customer..."
                class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent flex-1 min-w-[200px]"
            >
            <select name="status" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <option value="">All Statuses</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <input 
                type="date" 
                name="date_from" 
                value="{{ request('date_from') }}"
                class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
            >
            <input 
                type="date" 
                name="date_to" 
                value="{{ request('date_to') }}"
                class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
            >
            <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                Filter
            </button>
            <a href="{{ route('orders.index') }}" class="px-4 py-2 text-slate-500 hover:text-slate-700">
                Clear
            </a>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Loads</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('orders.show', $order) }}" class="text-teal-600 hover:text-teal-700 font-medium">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $order->customer->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $order->total_loads ?? 0 }} load(s)</td>
                            <td class="px-6 py-4 text-slate-800 font-medium">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                @if($order->isPaid())
                                    <span class="text-emerald-600 font-medium">Paid</span>
                                @else
                                    <span class="text-rose-600">₱{{ number_format($order->balance, 2) }} due</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('orders.update-status', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-sm border-0 bg-transparent focus:ring-0 cursor-pointer {{ \App\Models\LaundryOrder::getStatusColor($order->status) }} rounded-full px-2 py-1">
                                        @foreach($statuses as $value => $label)
                                            <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-sm">{{ $order->date_received->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('orders.show', $order) }}" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    @if($order->status === 'claimed')
                                        <a href="{{ route('orders.receipt', $order) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg" title="Receipt">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </a>
                                    @elseif($order->status !== 'claimed')
                                        <a href="{{ route('orders.edit', $order) }}" class="p-2 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                No orders found. <a href="{{ route('orders.create') }}" class="text-teal-600 hover:underline">Create your first order</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
