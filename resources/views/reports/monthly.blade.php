@extends('layouts.app')

@section('title', 'Monthly Report')
@section('page-title', 'Monthly Report')

@section('content')
<div class="space-y-6">
    <!-- Month Selector -->
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <form action="{{ route('reports.monthly') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Month</label>
                <select name="month" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" onchange="this.form.submit()">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Year</label>
                <select name="year" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" onchange="this.form.submit()">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end gap-3">
                <div class="text-sm text-slate-600 py-2">
                    {{ $monthStart->format('F Y') }}
                </div>
                <a href="{{ route('reports.monthly.pdf', ['month' => $month, 'year' => $year]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download PDF
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

    <!-- Daily Breakdown -->
    @if($dailyBreakdown->count() > 0)
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="p-6 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800">Daily Breakdown</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Orders</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Collected</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($dailyBreakdown->sortKeys() as $date => $data)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3 text-slate-700">{{ \Carbon\Carbon::parse($date)->format('M d, Y (l)') }}</td>
                                <td class="px-6 py-3 text-right text-slate-600">{{ $data['count'] }}</td>
                                <td class="px-6 py-3 text-right font-medium text-slate-800">₱{{ number_format($data['amount'], 2) }}</td>
                                <td class="px-6 py-3 text-right text-emerald-600">₱{{ number_format($data['paid'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

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
            <h3 class="font-semibold text-slate-800">All Orders - {{ $monthStart->format('F Y') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Loads</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
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
                            <td class="px-6 py-4 text-right text-slate-600">{{ $order->total_loads ?? 0 }}</td>
                            <td class="px-6 py-4 text-right font-medium text-slate-800">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-right text-slate-600">₱{{ number_format($order->amount_paid, 2) }}</td>
                            <td class="px-6 py-4">@include('components.status-badge', ['status' => $order->status])</td>
                            <td class="px-6 py-4 text-slate-500 text-sm">{{ $order->created_at->format('M d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">No orders for this month</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('reports.index') }}" class="text-slate-600 hover:text-slate-800">&larr; Back to Daily Report</a>
    </div>
</div>
@endsection
