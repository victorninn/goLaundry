@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-description', 'Manage your customer database')

@section('content')
@php
    $business = auth()->user()->business;
    $licenseExpired = $business && !$business->hasValidLicense();
@endphp

<div class="space-y-6">
    @if($licenseExpired)
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <p class="font-semibold text-rose-800">License Expired</p>
                    <p class="text-sm text-rose-600">Your business license has expired. Please contact the administrator to renew your license. Customer management is currently disabled.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 {{ $licenseExpired ? 'opacity-50 pointer-events-none' : '' }}">
        <form action="{{ route('customers.index') }}" method="GET" class="flex-1 max-w-md">
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search customers..."
                    class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                    {{ $licenseExpired ? 'disabled' : '' }}
                >
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </form>
        @if(!$licenseExpired)
            <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Customer
            </a>
        @else
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-slate-300 text-slate-500 rounded-lg cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Customer
            </span>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden {{ $licenseExpired ? 'opacity-50' : '' }}">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('customers.show', $customer) }}" class="font-medium text-slate-800 hover:text-teal-600 {{ $licenseExpired ? 'pointer-events-none' : '' }}">
                                    {{ $customer->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $customer->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $customer->email ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ $customer->laundry_orders_count }} orders
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if(!$licenseExpired)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('customers.edit', $customer) }}" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Delete this customer?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-slate-400 text-sm">Disabled</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                @if($licenseExpired)
                                    License expired. Renew to manage customers.
                                @else
                                    No customers found. <a href="{{ route('customers.create') }}" class="text-teal-600 hover:underline">Add your first customer</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
