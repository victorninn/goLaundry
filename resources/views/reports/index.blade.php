@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Reports')
@section('page-description', 'View daily, weekly, and monthly reports')

@section('content')
<div class="space-y-6">
    <!-- Report Type Buttons -->
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-sm font-medium text-slate-600 mr-2">View Report:</span>
            <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors text-sm font-medium">
                Daily Report
            </a>
            <a href="{{ route('reports.weekly') }}" target="_blank" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors text-sm font-medium inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Weekly Report
            </a>
            <a href="{{ route('reports.monthly') }}" target="_blank" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors text-sm font-medium inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Monthly Report
            </a>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <form action="{{ route('reports.index') }}" method="GET" class="flex items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Select Date</label>
                <input 
                    type="date" 
                    name="date" 
                    value="{{ $date }}"
                    class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                    onchange="this.form.submit()"
                >
            </div>
            <div class="flex items-end">
                <a href="{{ route('reports.export-pdf', ['date' => $date]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Total Orders</p>
            <p class="text-2xl font-bold text-slate-800">{{ $summary['total_orders'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Total Loads</p>
            <p class="text-2xl font-bold text-slate-800">{{ $summary['total_loads'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-sm text-slate-500">Total Amount</p>
            <p class="text-2xl font-bold text-slate-800">₱{{ number_format($summary['total_amount'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-sm text-emerald-600">Collected</p>
            <p class="text-2xl font-bold text-emerald-700">₱{{ number_format($summary['total_paid'], 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-sm text-rose-600">Balance Due</p>
            <p class="text-2xl font-bold text-rose-700">₱{{ number_format($summary['total_balance'], 2) }}</p>
        </div>
    </div>

    <!-- Status Breakdown -->
    @if($summary['by_status']->count() > 0)
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Orders by Status</h3>
            <div class="flex flex-wrap gap-4">
                @foreach($summary['by_status'] as $status => $count)
                    <div class="flex items-center gap-2">
                        @include('components.status-badge', ['status' => $status])
                        <span class="font-semibold text-slate-800">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Orders Table -->
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Orders for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Services</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Loads</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
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
                            <td class="px-6 py-4 text-slate-600 text-sm">
                                {{ $order->items->pluck('service.name')->join(', ') }}
                            </td>
                            <td class="px-6 py-4 text-right text-slate-600">{{ $order->total_loads ?? 0 }}</td>
                            <td class="px-6 py-4 text-right font-medium text-slate-800">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right text-slate-600">₱{{ number_format($order->amount_paid, 2) }}</td>
                            <td class="px-6 py-4">
                                @include('components.status-badge', ['status' => $order->status])
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                No orders for this date
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
