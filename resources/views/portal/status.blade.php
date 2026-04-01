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
            @php
                $statusSteps  = ['pending', 'washing', 'drying', 'ready', 'claimed'];
                $stepIcons    = [
                    'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'washing' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                    'drying'  => 'M12 3v1m0 16v1m8.66-13l-.866.5M4.206 16.5l-.866.5M19.794 16.5l-.866-.5M4.206 7.5l-.866-.5M21 12h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707',
                    'ready'   => 'M5 13l4 4L19 7',
                    'claimed' => 'M3 3h2l.4 2M7 13h10l4-9H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
                ];
                $currentIndex = array_search($order->status, $statusSteps);
                if ($currentIndex === false) $currentIndex = -1;
            @endphp

            {{-- DESKTOP: horizontal stepper --}}
            <div class="hidden sm:flex items-center justify-center mb-6">
                @foreach($statusSteps as $index => $step)
                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                {{ $index < $currentIndex  ? 'bg-teal-500 text-white' :
                                   ($index === $currentIndex ? 'bg-teal-500 text-white ring-4 ring-teal-100' : 'bg-slate-200 text-slate-400') }}">
                                @if($index < $currentIndex)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stepIcons[$step] }}"/>
                                    </svg>
                                @endif
                            </div>
                            <p class="text-xs mt-2 whitespace-nowrap
                                {{ $index <= $currentIndex ? 'text-teal-600 font-semibold' : 'text-slate-400' }}">
                                {{ ucfirst($step) }}
                            </p>
                        </div>
                        @if($index < count($statusSteps) - 1)
                            <div class="w-10 h-1 mx-1 mb-5 {{ $index < $currentIndex ? 'bg-teal-500' : 'bg-slate-200' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- MOBILE: vertical timeline --}}
            <div class="sm:hidden mb-6">
                @foreach($statusSteps as $index => $step)
                    @php
                        $done    = $index < $currentIndex;
                        $current = $index === $currentIndex;
                        $pending = $index > $currentIndex;
                    @endphp
                    <div class="flex items-start gap-4">
                        {{-- icon + line --}}
                        <div class="flex flex-col items-center">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
                                {{ $done    ? 'bg-teal-500 text-white' :
                                   ($current ? 'bg-teal-500 text-white ring-4 ring-teal-100' : 'bg-slate-200 text-slate-400') }}">
                                @if($done)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stepIcons[$step] }}"/>
                                    </svg>
                                @endif
                            </div>
                            @if($index < count($statusSteps) - 1)
                                <div class="w-0.5 flex-1 min-h-[2rem] my-1 {{ $done ? 'bg-teal-400' : 'bg-slate-200' }}"></div>
                            @endif
                        </div>
                        {{-- label --}}
                        <div class="pb-4 pt-1.5">
                            <p class="text-sm font-semibold
                                {{ $done    ? 'text-teal-600' :
                                   ($current ? 'text-teal-700' : 'text-slate-400') }}">
                                {{ ucfirst($step) }}
                            </p>
                            @if($current)
                                <p class="text-xs text-teal-500 mt-0.5">Current status</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                @include('components.status-badge', ['status' => $order->status])
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