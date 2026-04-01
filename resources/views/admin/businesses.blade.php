@extends('layouts.app')

@section('title', 'Manage Businesses')
@section('page-title', 'All Businesses')
@section('page-description', 'Manage registered laundry businesses')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-end">
        <a href="{{ route('super-admin.businesses.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Business
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Business</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Customers</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($businesses as $business)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800">{{ $business->name }}</p>
                                <p class="text-sm text-slate-500">{{ $business->address ?? 'No address' }}</p>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $business->owner?->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $business->phone ?? '-' }}<br>
                                <span class="text-sm">{{ $business->email ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $business->customers_count }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $business->laundry_orders_count }}</td>
                            <td class="px-6 py-4">
                                @if($business->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('super-admin.businesses.edit', $business) }}"
                                       class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('super-admin.businesses.toggle', $business) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm {{ $business->is_active ? 'text-rose-600 hover:text-rose-700' : 'text-emerald-600 hover:text-emerald-700' }} font-medium">
                                            {{ $business->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                No businesses registered yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($businesses->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $businesses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection