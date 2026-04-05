@extends('layouts.portal')

@section('title', 'My Orders')

@section('content')
<div class="space-y-6">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Your Laundry Orders</h1>
        <p class="text-slate-500">Track all your laundry with us</p>
    </div>

    <div class="space-y-4">
        @forelse($orders as $order)
            <a href="{{ route('portal.order', $order->id) }}" class="block bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <span class="font-semibold text-teal-600">{{ $order->order_number }}</span>
                    @include('components.status-badge', ['status' => $order->status])
                </div>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500">Date Received</p>
                        <p class="font-medium text-slate-800">{{ $order->date_received->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Loads</p>
                        <p class="font-medium text-slate-800">{{ number_format($order->total_loads, 1) }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Total</p>
                        <p class="font-medium text-slate-800">₱{{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>

                @if($order->date_release)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <p class="text-sm text-slate-500">Expected Release: <span class="font-medium text-slate-700">{{ $order->date_release->format('M d, Y') }}</span></p>
                    </div>
                @endif
            </a>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">No orders yet</h3>
                <p class="text-slate-500">You haven't placed any laundry orders yet</p>
            </div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection