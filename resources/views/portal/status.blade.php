@extends('layouts.portal')

@section('title', 'Order Status')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Order Header -->
        <div class="bg-gradient-to-r from-teal-500 to-sky-500 p-6 text-white text-center">
            <p class="text-sm opacity-90 mb-1">Order Number</p>
            <h1 class="text-2xl font-bold">{{ $order->order_number }}</h1>
            @if($order->business)
                <p class="text-sm opacity-90 mt-2">{{ $order->business->name }}</p>
            @endif
        </div>

        <!-- Status Progress -->
        <div class="p-6 border-b border-slate-100">
            <div class="flex items-center justify-center mb-6">
                @php
                    $statusSteps = ['pending', 'washing', 'drying', 'ready', 'claimed'];
                    $currentIndex = array_search($order->status, $statusSteps);
                    if ($currentIndex === false) $currentIndex = -1;
                @endphp
                
                @foreach($statusSteps as $index => $step)
                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $index <= $currentIndex ? 'bg-teal-500 text-white' : 'bg-slate-200 text-slate-400' }}">
                                @if($index < $currentIndex)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </div>
                            <p class="text-xs mt-2 {{ $index <= $currentIndex ? 'text-teal-600 font-medium' : 'text-slate-400' }}">
                                {{ ucfirst($step) }}
                            </p>
                        </div>
                        @if($index < count($statusSteps) - 1)
                            <div class="w-8 h-1 mx-1 {{ $index < $currentIndex ? 'bg-teal-500' : 'bg-slate-200' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <p class="text-sm text-slate-500">Current Status</p>
                <div class="mt-2">
                    @include('components.status-badge', ['status' => $order->status])
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500">Date Received</p>
                    <p class="font-semibold text-slate-800">{{ $order->date_received->format('M d, Y') }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm text-slate-500">Expected Release</p>
                    <p class="font-semibold text-slate-800">{{ $order->date_release?->format('M d, Y') ?? 'TBD' }}</p>
                </div>
            </div>

            <!-- Services -->
            <div>
                <h3 class="font-semibold text-slate-800 mb-3">Services</h3>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <div>
                                <p class="font-medium text-slate-800">{{ $item->service->name }}</p>
                                <p class="text-sm text-slate-500">{{ number_format($item->kilos, 1) }} kg × ₱{{ number_format($item->price_per_kilo, 2) }}</p>
                            </div>
                            <p class="font-semibold text-slate-800">₱{{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="border-t border-slate-200 pt-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-slate-600">Total Amount</span>
                    <span class="font-semibold text-slate-800">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-slate-600">Amount Paid</span>
                    <span class="font-semibold text-emerald-600">₱{{ number_format($order->amount_paid, 2) }}</span>
                </div>
                @if($order->balance > 0)
                    <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                        <span class="font-medium text-rose-600">Balance Due</span>
                        <span class="font-bold text-rose-600">₱{{ number_format($order->balance, 2) }}</span>
                    </div>
                @else
                    <div class="flex items-center justify-center pt-4">
                        <span class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Fully Paid
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(session('portal_customer_id'))
        <div class="text-center mt-6">
            <a href="{{ route('portal.track') }}" class="text-teal-600 hover:text-teal-700 font-medium">
                ← View All Orders
            </a>
        </div>
    @else
        <div class="text-center mt-6">
            <a href="{{ route('portal.login') }}" class="text-teal-600 hover:text-teal-700 font-medium">
                ← Track Another Order
            </a>
        </div>
    @endif
</div>
@endsection
