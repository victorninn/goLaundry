@extends('layouts.app')

@section('title', 'License')
@section('page-title', 'License')
@section('page-description', 'Manage your business license')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Current License Status -->
    @if($license)
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-slate-800">Current License</h3>
                @if($license->subscription_type === 'lifetime')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-700">
                        Lifetime License
                    </span>
                @elseif($license->isActive())
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-700">
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-rose-100 text-rose-700">
                        Expired
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="text-sm text-slate-500 mb-1">License Key</p>
                    <code class="text-sm font-mono text-slate-800">{{ $license->license_key }}</code>
                </div>
                
                <div class="p-4 bg-slate-50 rounded-xl">
                    <p class="text-sm text-slate-500 mb-1">Subscription Type</p>
                    <p class="font-medium text-slate-800">{{ \App\Models\License::getSubscriptionTypes()[$license->subscription_type] }}</p>
                </div>

                <div class="p-4 {{ $license->isActive() ? 'bg-emerald-50' : 'bg-rose-50' }} rounded-xl">
                    <p class="text-sm {{ $license->isActive() ? 'text-emerald-600' : 'text-rose-600' }} mb-1">Expiration Date</p>
                    <p class="font-medium {{ $license->isActive() ? 'text-emerald-700' : 'text-rose-700' }}">
                        @if($license->subscription_type === 'lifetime')
                            Never Expires
                        @elseif($license->expiration_date)
                            {{ $license->expiration_date->format('F d, Y') }}
                        @else
                            Not set
                        @endif
                    </p>
                </div>

                <div class="p-4 {{ $license->isActive() ? 'bg-teal-50' : 'bg-amber-50' }} rounded-xl">
                    <p class="text-sm {{ $license->isActive() ? 'text-teal-600' : 'text-amber-600' }} mb-1">Days Remaining</p>
                    <p class="text-2xl font-bold {{ $license->isActive() ? 'text-teal-700' : 'text-amber-700' }}">
                        @if($license->subscription_type === 'lifetime')
                            ∞
                        @elseif($license->days_remaining !== null)
                            {{ $license->days_remaining }}
                        @else
                            0
                        @endif
                    </p>
                </div>
            </div>

            @if(!$license->isActive() && $license->subscription_type !== 'lifetime')
                <div class="mt-6 p-4 bg-rose-50 border border-rose-200 rounded-xl">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-rose-800">License Expired</p>
                            <p class="text-sm text-rose-600">Please contact your administrator to renew your license.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Activate License -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-800 mb-4">{{ $license ? 'Activate New License' : 'Activate License' }}</h3>
        <p class="text-sm text-slate-500 mb-6">Enter your license key to activate your subscription.</p>

        <form action="{{ route('license.activate') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label for="license_key" class="block text-sm font-medium text-slate-700 mb-2">License Key</label>
                <input 
                    type="text" 
                    id="license_key" 
                    name="license_key" 
                    required
                    placeholder="XXXX-XXXX-XXXX-XXXX"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none font-mono uppercase"
                >
                @error('license_key')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="w-full px-6 py-3 bg-teal-500 text-white font-medium rounded-xl hover:bg-teal-600 transition-colors">
                Activate License
            </button>
        </form>
    </div>

    @if(!$license)
        <div class="text-center p-6 bg-amber-50 border border-amber-200 rounded-xl">
            <svg class="w-12 h-12 text-amber-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
            <h3 class="font-semibold text-amber-800 mb-1">No Active License</h3>
            <p class="text-sm text-amber-600">Please contact your administrator to obtain a license key.</p>
        </div>
    @endif
</div>
@endsection
