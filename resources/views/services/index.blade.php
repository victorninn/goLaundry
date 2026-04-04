@extends('layouts.app')

@section('title', 'Services')
@section('page-title', 'Services')
@section('page-description', 'Manage your laundry service types')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div></div>
        <a href="{{ route('services.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Service
        </a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($services as $service)
            <div class="bg-white rounded-xl border border-slate-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    @if(!$service->is_active)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Inactive</span>
                    @endif
                </div>
                
                <h3 class="font-semibold text-slate-800 mb-1">{{ $service->name }}</h3>
                <p class="text-sm text-slate-500 mb-4">{{ $service->description ?? 'No description' }}</p>
                
                <div class="flex items-center justify-between mb-4">
                    <span class="text-2xl font-bold text-teal-600">₱{{ number_format($service->price_per_load, 2) }}</span>
                    <span class="text-sm text-slate-500">per load</span>
                </div>

                @if($service->products->count() > 0)
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs text-slate-500 mb-2">Products used:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($service->products as $product)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-slate-100 text-slate-600">
                                    {{ $product->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('services.edit', $service) }}" class="flex-1 text-center py-2 text-sm text-teal-600 hover:bg-teal-50 rounded-lg transition-colors">
                        Edit
                    </a>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this service?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 text-sm text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl border border-slate-200 p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">No services yet</h3>
                <p class="text-slate-500 mb-4">Create your first service to start accepting orders</p>
                <a href="{{ route('services.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600">
                    Add Service
                </a>
            </div>
        @endforelse
    </div>

    @if($services->hasPages())
        <div class="mt-6">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
