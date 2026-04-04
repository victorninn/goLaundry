<header class="bg-white border-b border-slate-200 sticky top-0 z-20">
    <div class="flex items-center justify-between px-4 lg:px-6 py-4">
        <div class="lg:pl-0 pl-12 flex items-center gap-3">
            @unless(auth()->user()->isSuperAdmin())
                @php $business = auth()->user()->business; @endphp
                @if($business && $business->logo_url)
                    <img src="{{ $business->logo_url }}" alt="{{ $business->name }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200">
                @endif
                @if($business)
                    <div>
                        <h1 class="text-lg font-semibold text-slate-800">{{ $business->name }}</h1>
                        <p class="text-xs text-slate-500">@yield('page-title', 'Dashboard')</p>
                    </div>
                @else
                    <div>
                        <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                        @hasSection('page-description')
                            <p class="text-sm text-slate-500">@yield('page-description')</p>
                        @endif
                    </div>
                @endif
            @else
                <div>
                    <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('page-description')
                        <p class="text-sm text-slate-500">@yield('page-description')</p>
                    @endif
                </div>
            @endunless
        </div>

        <div class="flex items-center gap-4">
            @unless(auth()->user()->isSuperAdmin())
            <a href="{{ route('orders.create') }}" class="hidden sm:flex items-center gap-2 px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-medium">New Order</span>
            </a>
            @endunless

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-4 py-2 text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="hidden sm:inline font-medium">Logout</span>
                </button>
            </form>
        </div>
    </div>
</header>
