@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview of your laundry business')

@section('content')
<div class="space-y-6">
    <!-- Business Header with Logo 
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            @if($business && $business->logo_url)
                <img src="{{ $business->logo_url }}" alt="{{ $business->name }} Logo" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
            @else
                <div class="w-16 h-16 rounded-xl bg-teal-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            @endif
            <div class="flex-1">
                <h2 class="text-xl font-bold text-slate-800">{{ $business->name ?? 'My Business' }}</h2>
                @if($business && $business->address)
                    <p class="text-sm text-slate-500">{{ $business->address }}</p>
                @endif
            </div>
            <a href="{{ route('orders.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-teal-500 text-white font-medium rounded-xl hover:bg-teal-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Order
            </a>
        </div>
    </div>-->

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Today's Orders</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $todayOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Today's Revenue</p>
                    <p class="text-2xl font-bold text-slate-800">₱{{ number_format($todayRevenue, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Customers</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $customersCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 border border-slate-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500">This Month</p>
                    <p class="text-2xl font-bold text-slate-800">₱{{ number_format($monthlyRevenue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Order Status Summary -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="font-semibold text-slate-800 mb-4">Active Orders by Status</h2>
            <div class="space-y-3">
                @php
                    $statusConfig = [
                        'pending' => ['label' => 'Pending', 'color' => 'bg-slate-400'],
                        'washing' => ['label' => 'Washing', 'color' => 'bg-blue-500'],
                        'drying' => ['label' => 'Drying', 'color' => 'bg-amber-500'],
                        'folding' => ['label' => 'Folding', 'color' => 'bg-purple-500'],
                        'ready' => ['label' => 'Ready', 'color' => 'bg-emerald-500'],
                    ];
                @endphp
                @foreach($statusConfig as $status => $config)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full {{ $config['color'] }}"></div>
                            <span class="text-sm text-slate-600">{{ $config['label'] }}</span>
                        </div>
                        <span class="font-semibold text-slate-800">{{ $ordersByStatus[$status] ?? 0 }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="font-semibold text-slate-800 mb-4">Low Stock Alerts</h2>
            @if($lowStockProducts->isEmpty())
                <div class="text-center py-8 text-slate-400">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm">All products are well stocked!</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($lowStockProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-rose-50 rounded-lg">
                            <div>
                                <p class="font-medium text-rose-800">{{ $product->name }}</p>
                                <p class="text-xs text-rose-600">{{ $product->quantity }} {{ $product->unit }} remaining</p>
                            </div>
                            <a href="{{ route('products.edit', $product) }}" class="text-rose-600 hover:text-rose-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h2 class="font-semibold text-slate-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('orders.create') }}" class="flex flex-col items-center gap-2 p-4 bg-teal-50 rounded-xl hover:bg-teal-100 transition-colors">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="text-sm font-medium text-teal-700">New Order</span>
                </a>
                <a href="{{ route('customers.create') }}" class="flex flex-col items-center gap-2 p-4 bg-sky-50 rounded-xl hover:bg-sky-100 transition-colors">
                    <svg class="w-8 h-8 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span class="text-sm font-medium text-sky-700">Add Customer</span>
                </a>
                <a href="{{ route('reports.index') }}" class="flex flex-col items-center gap-2 p-4 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm font-medium text-amber-700">View Reports</span>
                </a>
                <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-2 p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="text-sm font-medium text-purple-700">Inventory</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl border border-slate-200">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Recent Orders</h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-teal-600 hover:text-teal-700">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Loads</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('orders.show', $order) }}" class="text-teal-600 hover:text-teal-700 font-medium">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $order->customer->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $order->total_loads ?? 0 }}</td>
                            <td class="px-6 py-4 text-slate-800 font-medium">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                @include('components.status-badge', ['status' => $order->status])
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-sm">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                No orders yet. <a href="{{ route('orders.create') }}" class="text-teal-600 hover:underline">Create your first order</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
