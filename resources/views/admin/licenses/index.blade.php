@extends('layouts.app')

@section('title', 'License Manager')
@section('page-title', 'License Manager')
@section('page-description', 'Generate and manage business licenses')

@section('content')
<div class="space-y-6">
    <!-- Generate License Card -->
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Generate New License</h3>
        
        <form action="{{ route('super-admin.licenses.store') }}" method="POST" class="flex flex-wrap items-end gap-4">
            @csrf
            
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-2">Business</label>
                <select name="business_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                    <option value="">Select business</option>
                    @foreach($businesses as $business)
                        <option value="{{ $business->id }}">{{ $business->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-48">
                <label class="block text-sm font-medium text-slate-700 mb-2">Subscription Type</label>
                <select name="subscription_type" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                    <option value="1_month">1 Month</option>
                    <option value="6_months">6 Months</option>
                    <option value="1_year">1 Year</option>
                    <option value="lifetime">Lifetime (Master)</option>
                </select>
            </div>

            <button type="submit" class="px-6 py-3 bg-purple-500 text-white font-medium rounded-xl hover:bg-purple-600 transition-colors">
                Generate License
            </button>
        </form>
    </div>

    <!-- Licenses Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">All Licenses</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">License Key</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Business</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Expiration</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($licenses as $license)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <code class="px-2 py-1 bg-slate-100 rounded text-sm font-mono">{{ $license->license_key }}</code>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $license->business->name }}</td>
                            <td class="px-6 py-4">
                                @if($license->subscription_type === 'lifetime')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Lifetime</span>
                                @elseif($license->subscription_type === '1_year')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-sky-100 text-sky-700">1 Year</span>
                                @elseif($license->subscription_type === '6_months')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-700">6 Months</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">1 Month</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                @if($license->subscription_type === 'lifetime')
                                    <span class="text-purple-600 font-medium">Never</span>
                                @elseif($license->expiration_date)
                                    {{ $license->expiration_date->format('M d, Y') }}
                                    @if($license->days_remaining !== null && $license->days_remaining > 0)
                                        <span class="text-xs text-slate-500">({{ $license->days_remaining }} days)</span>
                                    @endif
                                @else
                                    <span class="text-slate-400">Not activated</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($license->status === 'active' && !$license->isExpired())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Active</span>
                                @elseif($license->status === 'expired' || $license->isExpired())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">Expired</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($license->subscription_type !== 'lifetime')
                                        <form action="{{ route('super-admin.licenses.renew', $license) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 text-sm bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200 transition-colors">
                                                Renew
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('super-admin.licenses.destroy', $license) }}" method="POST" onsubmit="return confirm('Delete this license?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-sm text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                No licenses generated yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($licenses->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $licenses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
