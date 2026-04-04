@extends('layouts.app')

@section('title', 'Create Business')
@section('page-title', 'Create Business')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form action="{{ route('super-admin.businesses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Business Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="owner_name" class="block text-sm font-medium text-slate-700 mb-2">Owner Name</label>
                <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                @error('owner_name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label for="tin" class="block text-sm font-medium text-slate-700 mb-2">TIN (Tax ID)</label>
                    <input type="text" id="tin" name="tin" value="{{ old('tin') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none"
                        placeholder="000-000-000-000">
                    @error('tin')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="business_registration_number" class="block text-sm font-medium text-slate-700 mb-2">Business Reg. Number</label>
                    <input type="text" id="business_registration_number" name="business_registration_number" value="{{ old('business_registration_number') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                    @error('business_registration_number')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                    @error('phone')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                    @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-slate-700 mb-2">Address</label>
                <textarea id="address" name="address" rows="2"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none resize-none">{{ old('address') }}</textarea>
                @error('address')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="logo" class="block text-sm font-medium text-slate-700 mb-2">Logo (Optional)</label>
                <input type="file" id="logo" name="logo" accept="image/*"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none">
                <p class="mt-1 text-xs text-slate-500">Max 2MB. Supported: JPEG, PNG, GIF</p>
                @error('logo')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-teal-500 text-white font-medium rounded-xl hover:bg-teal-600 transition-colors">
                    Create Business
                </button>
                <a href="{{ route('super-admin.businesses') }}" class="px-6 py-3 text-slate-600 hover:text-slate-800">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
