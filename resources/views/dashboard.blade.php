@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Welcome back, ' . Auth::user()->name . '! Here\'s what\'s happening.')

@section('content')

<!-- Stats Row -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">

    <!-- Total Revenue -->
    <div class="stat-card">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">$48,295</p>
                <p class="text-xs mt-2 flex items-center gap-1 text-emerald-600 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    +12.5% from last month
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <!-- Mini chart bar -->
        <div class="flex items-end gap-1 h-8">
            @foreach([40,65,45,80,55,90,70,85,60,95,75,100] as $h)
                <div class="flex-1 rounded-sm opacity-30 hover:opacity-60 transition-opacity"
                     style="height:{{ $h }}%; background:linear-gradient(to top,#6366f1,#8b5cf6);"></div>
            @endforeach
        </div>
    </div>

    <!-- Total Orders -->
    <div class="stat-card">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Orders</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">1,847</p>
                <p class="text-xs mt-2 flex items-center gap-1 text-emerald-600 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    +8.2% from last month
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#06b6d4,#0891b2);">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <div class="flex items-end gap-1 h-8">
            @foreach([55,70,50,85,60,75,90,65,80,70,88,95] as $h)
                <div class="flex-1 rounded-sm opacity-30 hover:opacity-60 transition-opacity"
                     style="height:{{ $h }}%; background:linear-gradient(to top,#06b6d4,#0891b2);"></div>
            @endforeach
        </div>
    </div>

    <!-- Total Products -->
    <div class="stat-card">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Products</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">3,142</p>
                <p class="text-xs mt-2 flex items-center gap-1 text-emerald-600 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    +24 added this week
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
        <div class="flex items-end gap-1 h-8">
            @foreach([60,75,55,80,65,90,70,85,75,88,82,100] as $h)
                <div class="flex-1 rounded-sm opacity-30 hover:opacity-60 transition-opacity"
                     style="height:{{ $h }}%; background:linear-gradient(to top,#f59e0b,#d97706);"></div>
            @endforeach
        </div>
    </div>

    <!-- Customers -->
    <div class="stat-card">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Customers</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">9,628</p>
                <p class="text-xs mt-2 flex items-center gap-1 text-rose-500 font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    -2.1% from last month
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#10b981,#059669);">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <div class="flex items-end gap-1 h-8">
            @foreach([80,65,90,55,75,70,85,60,95,70,80,88] as $h)
                <div class="flex-1 rounded-sm opacity-30 hover:opacity-60 transition-opacity"
                     style="height:{{ $h }}%; background:linear-gradient(to top,#10b981,#059669);"></div>
            @endforeach
        </div>
    </div>

</div>

<!-- Middle row: Recent Orders + Top Categories -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-7">

    <!-- Recent Orders table -->
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
                <h2 class="font-semibold text-gray-800">Recent Orders</h2>
                <p class="text-xs text-gray-400 mt-0.5">Latest 5 transactions</p>
            </div>
            <a href="#" class="text-xs text-indigo-600 font-medium hover:text-indigo-800 transition">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['#SN-8821','Ali Hassan','3 items','$249.00','Delivered'],
                        ['#SN-8820','Sara Khan','1 item','$89.99','Processing'],
                        ['#SN-8819','Usman Malik','5 items','$412.50','Shipped'],
                        ['#SN-8818','Ayesha Raza','2 items','$155.00','Pending'],
                        ['#SN-8817','Bilal Ahmed','1 item','$35.00','Cancelled'],
                    ] as [$id,$name,$items,$amount,$status])
                    <tr>
                        <td class="font-semibold text-indigo-600">{{ $id }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                    {{ strtoupper(substr($name,0,1)) }}
                                </div>
                                {{ $name }}
                            </div>
                        </td>
                        <td class="text-gray-500">{{ $items }}</td>
                        <td class="font-semibold">{{ $amount }}</td>
                        <td>
                            @php
                                $colors = ['Delivered'=>'#dcfce7:#16a34a','Processing'=>'#dbeafe:#2563eb','Shipped'=>'#fef9c3:#ca8a04','Pending'=>'#fee2e2:#dc2626','Cancelled'=>'#f3f4f6:#6b7280'];
                                [$bg,$fg] = explode(':',$colors[$status]);
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold"
                                  style="background:{{ $bg }};color:{{ $fg }}">{{ $status }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick info panel -->
    <div class="space-y-5">

        <!-- Categories quick access -->
        <div class="bg-white rounded-2xl p-5" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-800">Categories</h2>
                <a href="{{ route('admin.categories.index') }}"
                   class="text-xs text-indigo-600 font-medium hover:text-indigo-800">View all →</a>
            </div>
            <div class="space-y-2.5">
                @php
                    $cats = \App\Models\Category::withCount('children')->latest()->take(5)->get();
                @endphp
                @forelse($cats as $cat)
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                             style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-700">{{ $cat->name }}</div>
                            <div class="text-xs text-gray-400">{{ $cat->children_count }} sub-cats</div>
                        </div>
                    </div>
                    <span class="{{ $cat->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                        {{ ucfirst($cat->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-6">
                    <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <p class="text-sm text-gray-400">No categories yet</p>
                    <a href="{{ route('admin.categories.create') }}"
                       class="text-xs text-indigo-600 font-medium mt-1 inline-block">Create first →</a>
                </div>
                @endforelse
            </div>
            @if($cats->count())
            <a href="{{ route('admin.categories.create') }}"
               class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-medium text-indigo-600 border border-indigo-200 hover:bg-indigo-50 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add New Category
            </a>
            @endif
        </div>

        <!-- Quick actions -->
        <div class="bg-white rounded-2xl p-5" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
            <h2 class="font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.categories.create') }}"
                   class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-indigo-50 transition text-center group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition"
                         style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-gray-600">Add Category</span>
                </a>
                <a href="#"
                   class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-amber-50 transition text-center group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition"
                         style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <span class="text-xs font-medium text-gray-600">Add Product</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-green-50 transition text-center group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition"
                         style="background:linear-gradient(135deg,#10b981,#059669);">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-gray-600">My Profile</span>
                </a>
                <a href="#"
                   class="flex flex-col items-center gap-2 p-3 rounded-xl hover:bg-cyan-50 transition text-center group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center group-hover:scale-110 transition"
                         style="background:linear-gradient(135deg,#06b6d4,#0891b2);">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-gray-600">Reports</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bottom: Activity feed + Platform stats -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
    @foreach([
        ['Sales Today','$2,840','↑ 18% vs yesterday','linear-gradient(135deg,#6366f1,#8b5cf6)'],
        ['Pending Orders','24','⚡ Need attention','linear-gradient(135deg,#f59e0b,#d97706)'],
        ['Low Stock Items','7','⚠ Restock needed','linear-gradient(135deg,#ef4444,#dc2626)'],
        ['Avg. Rating','4.8 ★','Based on 1,240 reviews','linear-gradient(135deg,#10b981,#059669)'],
    ] as [$title,$val,$sub,$grad])
    <div class="rounded-2xl p-5 text-white relative overflow-hidden" style="background:{{ $grad }};box-shadow:0 8px 30px rgba(0,0,0,.15)">
        <div class="absolute inset-0 opacity-10" style="background:radial-gradient(circle at 80% 20%,white,transparent)"></div>
        <p class="text-white/70 text-sm font-medium">{{ $title }}</p>
        <p class="text-3xl font-bold mt-1 mb-2">{{ $val }}</p>
        <p class="text-white/60 text-xs">{{ $sub }}</p>
    </div>
    @endforeach
</div>

@endsection
