@extends('layouts.app')

@section('title', 'Manage Users')
@section('page-title', 'All Users')
@section('page-description', 'Manage system users')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-end">
        <a href="{{ route('super-admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add User
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Business</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->isSuperAdmin())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">Super Admin</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-sky-100 text-sky-700">Admin</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->business?->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-500 text-sm">{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                No users found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
