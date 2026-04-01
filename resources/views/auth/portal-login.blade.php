@extends('layouts.portal')

@section('title', 'Track Your Laundry')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8 max-w-md mx-auto">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-sky-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Track Your Laundry</h1>
        <p class="text-slate-500 mt-2">Enter your phone number to view your orders</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('portal.login') }}" method="POST" class="space-y-5">
        @csrf
        
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
            <input 
                type="text" 
                id="phone" 
                name="phone" 
                value="{{ old('phone') }}"
                required
                autofocus
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                placeholder="09171234567"
            >
        </div>

        <div>
            <label for="order_number" class="block text-sm font-medium text-slate-700 mb-2">Order Number (Optional)</label>
            <input 
                type="text" 
                id="order_number" 
                name="order_number" 
                value="{{ old('order_number') }}"
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                placeholder="ORD-20240101-0001"
            >
            <p class="mt-2 text-xs text-slate-500">Enter order number to go directly to that order</p>
        </div>

        <button type="submit" class="w-full py-3 bg-gradient-to-r from-teal-500 to-sky-500 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-sky-600 transition-all shadow-lg shadow-teal-500/25">
            Track My Laundry
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-slate-100">
        <p class="text-center text-sm text-slate-500 mb-4">Or track by order number only:</p>
        <form action="{{ route('portal.quick-track') }}" method="POST" class="flex gap-2">
            @csrf
            <input 
                type="text" 
                name="order_number" 
                required
                class="flex-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none text-sm"
                placeholder="Enter order number"
            >
            <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm font-medium">
                Track
            </button>
        </form>
    </div>

    <p class="mt-6 text-center text-slate-400 text-sm">
        <a href="{{ route('login') }}" class="hover:text-teal-600">Admin Login</a>
    </p>
</div>
@endsection
