@extends('layouts.admin')
@section('title','Edit Customer')
@section('cust_open','true')

@section('content')
<div class="p-6 max-w-3xl mx-auto space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Customer</h1>
            <p class="text-sm text-gray-400 mt-0.5">Update customer information</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.customers.update', $customer) }}" id="customerForm">
        @method('PUT')
        @csrf
        <div class="bg-white rounded-2xl p-6 space-y-5" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
            <h2 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Personal Info</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="form-input @error('name') border-red-400 @enderror">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="form-input @error('email') border-red-400 @enderror">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">City</label>
                    <input type="text" name="city" value="{{ old('city', $customer->city) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Country</label>
                    <input type="text" name="country" value="{{ old('country', $customer->country) }}" class="form-input">
                </div>
            </div>
            <div>
                <label class="form-label">Address</label>
                <textarea name="address" rows="2" class="form-input">{{ old('address', $customer->address) }}</textarea>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white rounded-2xl p-5 space-y-3" style="box-shadow:0 2px 16px rgba(0,0,0,.06)"
             x-data="{ s: '{{ old('status','active') }}' }">
            <h2 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Status</h2>
            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition"
                   :class="s==='active' ? 'border-emerald-400 bg-emerald-50' : 'border-gray-200'">
                <input type="radio" name="status" value="active" form="customerForm" x-on:change="s='active'" {{ old('status','active')==='active' ? 'checked' : '' }} class="text-emerald-600 w-4 h-4">
                <div><p class="text-sm font-semibold text-gray-700">Active</p><p class="text-xs text-gray-400">Customer can place orders</p></div>
            </label>
            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition"
                   :class="s==='inactive' ? 'border-red-300 bg-red-50' : 'border-gray-200'">
                <input type="radio" name="status" value="inactive" form="customerForm" x-on:change="s='inactive'" {{ old('status')==='inactive' ? 'checked' : '' }} class="text-red-500 w-4 h-4">
                <div><p class="text-sm font-semibold text-gray-700">Inactive</p><p class="text-xs text-gray-400">Account disabled</p></div>
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary px-6 py-2.5 text-sm">Update Customer</button>
            <a href="{{ route('admin.customers.index') }}" class="px-6 py-2.5 text-sm rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition">Cancel</a>
        </div>
    </form>
</div>
@endsection
