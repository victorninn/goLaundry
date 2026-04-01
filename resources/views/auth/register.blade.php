@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-sky-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800">Register Your Business</h1>
        <p class="text-slate-500 mt-2">Start managing your laundry shop today</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="space-y-5">
        @csrf

        <div class="p-4 bg-slate-50 rounded-xl space-y-4">
            <p class="text-sm font-semibold text-slate-700">Business Information</p>
            
            <div>
                <label for="business_name" class="block text-sm font-medium text-slate-700 mb-2">Business Name *</label>
                <input 
                    type="text" 
                    id="business_name" 
                    name="business_name" 
                    value="{{ old('business_name') }}"
                    required
                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                    placeholder="Fresh & Clean Laundry"
                >
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="business_phone" class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                    <input 
                        type="text" 
                        id="business_phone" 
                        name="business_phone" 
                        value="{{ old('business_phone') }}"
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                        placeholder="09123456789"
                    >
                </div>
                <div>
                    <label for="business_address" class="block text-sm font-medium text-slate-700 mb-2">Address</label>
                    <input 
                        type="text" 
                        id="business_address" 
                        name="business_address" 
                        value="{{ old('business_address') }}"
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                        placeholder="123 Main St"
                    >
                </div>
            </div>
        </div>
        
        <div class="p-4 bg-slate-50 rounded-xl space-y-4">
            <p class="text-sm font-semibold text-slate-700">Admin Account</p>

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Your Name *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                    placeholder="John Doe"
                >
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                    placeholder="you@example.com"
                >
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password *</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        required
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                        placeholder="Min 8 characters"
                    >
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Confirm *</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation"
                        required
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                        placeholder="Repeat password"
                    >
                </div>
            </div>
        </div>

        <button type="submit" class="w-full py-3 bg-gradient-to-r from-teal-500 to-sky-500 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-sky-600 transition-all shadow-lg shadow-teal-500/25">
            Create Account
        </button>
    </form>

    <p class="mt-6 text-center text-slate-500 text-sm">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-teal-600 hover:text-teal-700 font-medium">Sign in</a>
    </p>
</div>
@endsection
