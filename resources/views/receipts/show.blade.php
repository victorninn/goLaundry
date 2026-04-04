@extends('layouts.app')

@section('title', 'Receipt - ' . $order->order_number)
@section('page-title', 'Order Receipt')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Print/Download Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('orders.show', $order) }}" class="text-slate-600 hover:text-slate-800">
            &larr; Back to Order
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Receipt
            </button>
            <a href="{{ route('orders.receipt.pdf', $order) }}" class="px-4 py-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 transition-colors inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    <!-- Receipt Card -->
    <div id="receipt-content" class="bg-white rounded-xl border border-slate-200 overflow-hidden p-8">
        <!-- Receipt Header -->
        <div class="p-8 text-center border-b border-slate-200">
            <div class="flex items-center justify-center gap-4 mb-4">
                @if($order->business->logo_url)
                    <img src="{{ $order->business->logo_url }}" alt="{{ $order->business->name }}" class="w-16 h-16 rounded-xl object-cover">
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">{{ $order->business->name }}</h1>
                    @if($order->business->address)
                        <p class="text-sm text-slate-500">{{ $order->business->address }}</p>
                    @endif
                </div>
            </div>
            
            <div class="text-sm text-slate-500 space-y-1">
                @if($order->business->phone)
                    <p>Phone: {{ $order->business->phone }}</p>
                @endif
                @if($order->business->tin)
                    <p>TIN: {{ $order->business->tin }}</p>
                @endif
                @if($order->business->business_registration_number)
                    <p>Reg. No: {{ $order->business->business_registration_number }}</p>
                @endif
            </div>
            
            <div class="mt-4 pt-4 border-t border-dashed border-slate-300">
                <p class="text-lg font-bold text-slate-800">OFFICIAL RECEIPT</p>
            </div>
        </div>

        <!-- Order Info -->
        <div class="px-8 py-4 border-b border-slate-100">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-slate-500">Receipt #:</p>
                    <p class="font-semibold text-slate-800">{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-slate-500">Date:</p>
                    <p class="font-semibold text-slate-800">{{ $order->date_received->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Customer:</p>
                    <p class="font-semibold text-slate-800">{{ $order->customer->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-slate-500">Status:</p>
                    <p class="font-semibold text-emerald-600">Claimed</p>
                </div>
            </div>
        </div>

        <!-- Services Table -->
        <div class="px-8 py-4">
            <h3 class="text-sm font-semibold text-slate-500 uppercase mb-3">Services</h3>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="py-2 text-left font-semibold text-slate-700">Service</th>
                        <th class="py-2 text-center font-semibold text-slate-700">Loads</th>
                        <th class="py-2 text-right font-semibold text-slate-700">Price/Load</th>
                        <th class="py-2 text-right font-semibold text-slate-700">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-b border-slate-100">
                            <td class="py-2 text-slate-700">{{ $item->service->name }}</td>
                            <td class="py-2 text-center text-slate-600">{{ $item->num_loads }}</td>
                            <td class="py-2 text-right text-slate-600">₱{{ number_format($item->price_per_load, 2) }}</td>
                            <td class="py-2 text-right font-medium text-slate-800">₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="py-2 text-right font-semibold text-slate-700">Services Total:</td>
                        <td class="py-2 text-right font-bold text-slate-800">₱{{ number_format($order->services_total ?? $order->items->sum('subtotal'), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Products Table -->
        @if($order->orderProducts->count() > 0)
            <div class="px-8 py-4 border-t border-slate-100">
                <h3 class="text-sm font-semibold text-slate-500 uppercase mb-3">Products</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="py-2 text-left font-semibold text-slate-700">Product</th>
                            <th class="py-2 text-center font-semibold text-slate-700">Qty</th>
                            <th class="py-2 text-right font-semibold text-slate-700">Price</th>
                            <th class="py-2 text-right font-semibold text-slate-700">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderProducts as $orderProduct)
                            <tr class="border-b border-slate-100">
                                <td class="py-2 text-slate-700">{{ $orderProduct->product->name }}</td>
                                <td class="py-2 text-center text-slate-600">{{ $orderProduct->quantity }}</td>
                                <td class="py-2 text-right text-slate-600">₱{{ number_format($orderProduct->unit_price, 2) }}</td>
                                <td class="py-2 text-right font-medium text-slate-800">₱{{ number_format($orderProduct->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="py-2 text-right font-semibold text-slate-700">Products Total:</td>
                            <td class="py-2 text-right font-bold text-slate-800">₱{{ number_format($order->products_total ?? $order->orderProducts->sum('subtotal'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif

        <!-- Grand Total -->
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-slate-600">Total Amount:</span>
                <span class="text-lg font-bold text-slate-800">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-slate-600">Amount Paid:</span>
                <span class="text-lg font-bold text-emerald-600">₱{{ number_format($order->amount_paid, 2) }}</span>
            </div>
            @if($order->balance > 0)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Balance:</span>
                    <span class="text-lg font-bold text-rose-600">₱{{ number_format($order->balance, 2) }}</span>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="px-8 py-6 text-center border-t border-dashed border-slate-300">
            <p class="text-sm text-slate-500">Thank you for your business!</p>
            <p class="text-xs text-slate-400 mt-2">Generated on {{ now()->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #receipt-content, #receipt-content * { visibility: visible; }
        #receipt-content { position: absolute; left: 0; top: 0; width: 100%; border: none !important; border-radius: 0 !important; }
        .no-print { display: none !important; }
    }
</style>
@endpush
@endsection
