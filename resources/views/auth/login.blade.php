@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="bg-white rounded-2xl shadow-xl p-8">
    <div class="text-center mb-8">
        <!--<div class="w-16 h-16 bg-gradient-to-br from-teal-400 to-sky-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
        </div>-->
        <div class="flex justify-center mx-auto mb-4">
            <img src="{{ Storage::url('logos/elvic-green.svg') }}" 
                alt="Logo" 
                class="h-10 w-auto">
         </div>
        <h1 class="text-2xl font-bold text-slate-800">Welcome Back</h1>
        <p class="text-slate-500 mt-2">Sign in to your laundry dashboard</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf
        
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="{{ old('email') }}"
                required
                autofocus
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                placeholder="you@example.com"
            >
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
            <input 
                type="password" 
                id="password" 
                name="password"
                required
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all"
                placeholder="Enter your password"
            >
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 text-teal-500 border-slate-300 rounded focus:ring-teal-500">
                <span class="text-sm text-slate-600">Remember me</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3 bg-gradient-to-r from-teal-500 to-sky-500 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-sky-600 transition-all shadow-lg shadow-teal-500/25">
            Sign In
        </button>
    </form>

    <div class="mt-6 text-center space-y-3">
        <p class="text-slate-500 text-sm">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-teal-600 hover:text-teal-700 font-medium">Register your business</a>
        </p>
        <p class="text-slate-400 text-sm">
            <a href="{{ route('portal.login') }}" class="hover:text-teal-600">Customer Portal</a>
        </p>
    </div>
</div>
@endsection
