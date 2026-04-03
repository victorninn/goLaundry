<!-- Mobile Menu Toggle -->
<button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-white rounded-lg shadow-md">
    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

<!-- Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-200">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="p-6 border-b border-slate-100">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                  <!--<div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-sky-500 rounded-xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>-->

                <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                    <img src="{{ asset('favicon.svg') }}">
                </div>
                <div>
                    <span class="font-bold text-slate-800">goLaundry</span>
                    <span class="text-xs text-slate-500 block">{{ auth()->user()->business?->name ?? 'Laundry Tracker' }}</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            @unless(auth()->user()->isSuperAdmin())
            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('orders.*') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="font-medium">Orders</span>
            </a>

            <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('customers.*') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="font-medium">Customers</span>
            </a>

            <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('services.*') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                <span class="font-medium">Services</span>
            </a>

            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('products.*') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="font-medium">Products</span>
            </a>

            <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('reports.*') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="font-medium">Reports</span>
            </a>

            <div class="pt-4 mt-4 border-t border-slate-100">
                <a href="{{ route('business.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('business.*') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="font-medium">Business Settings</span>
                </a>
            </div>
            @endunless

            @if(auth()->user()->isSuperAdmin())
            <div class="pt-4 mt-4 border-t border-slate-100">
                <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Super Admin</p>
                
                <a href="{{ route('super-admin.businesses') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('super-admin.businesses*') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="font-medium">Businesses</span>
                </a>

                <a href="{{ route('super-admin.users') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('super-admin.users*') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="font-medium">Users</span>
                </a>
            </div>
            @endif
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center gap-3 px-4 py-3 bg-slate-50 rounded-xl">
                <div class="w-10 h-10 bg-gradient-to-br from-slate-300 to-slate-400 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-700 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Sidebar Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/50 lg:hidden hidden"></div>

<script>
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    sidebarToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    });

    overlay?.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    });
</script>
