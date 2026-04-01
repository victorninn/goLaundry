@extends('layouts.app')

@section('title', 'Manage Businesses')
@section('page-title', 'All Businesses')
@section('page-description', 'Manage registered laundry businesses')

@section('content')
<div class="space-y-6">
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
                                <form action="{{ route('super-admin.businesses.toggle', $business) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-sm {{ $business->is_active ? 'text-rose-600 hover:text-rose-700' : 'text-emerald-600 hover:text-emerald-700' }}">
                                        {{ $business->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
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
