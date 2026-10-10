@extends('layouts.admin')
@section('title', $order->order_number)
@section('order_open','true')

@section('content')
<div class="p-6 space-y-5">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 font-mono">{{ $order->order_number }}</h1>
            <p class="text-sm text-gray-400 mt-0.5">Placed {{ $order->created_at->format('M d, Y · h:i A') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.edit', $order) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Update Status
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-indigo-600 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3 flex items-center gap-2 text-emerald-700 text-sm">
        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Status bar --}}
    @php
        $steps = ['pending','processing','shipped','delivered'];
        $stepIdx = array_search($order->status, $steps);
    @endphp
    @if($order->status !== 'cancelled')
    <div class="bg-white rounded-2xl p-5" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
        <div class="flex items-center gap-0">
            @foreach($steps as $i => $step)
            <div class="flex-1 flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                    {{ $i <= $stepIdx ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-400' }}">
                    @if($i < $stepIdx)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                        {{ $i + 1 }}
                    @endif
                </div>
                <p class="text-xs mt-1 {{ $i <= $stepIdx ? 'text-indigo-600 font-semibold' : 'text-gray-400' }}">{{ ucfirst($step) }}</p>
            </div>
            @if(!$loop->last)
            <div class="flex-1 h-0.5 mb-5 {{ $i < $stepIdx ? 'bg-indigo-600' : 'bg-gray-200' }}"></div>
            @endif
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-600 font-medium">⚠ This order was cancelled.</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Order items --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700">Order Items</h3>
                </div>
                <table class="data-table">
                    <thead><tr><th>Product</th><th class="text-center">Qty</th><th class="text-right">Unit Price</th><th class="text-right">Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <p class="font-medium text-gray-800 text-sm">{{ $item->product_name }}</p>
                                @if($item->product_sku)<p class="text-xs text-gray-400 font-mono">{{ $item->product_sku }}</p>@endif
                            </td>
                            <td class="text-center font-medium text-gray-700">{{ $item->quantity }}</td>
                            <td class="text-right text-gray-600 text-sm">Rs {{ number_format($item->unit_price, 0) }}</td>
                            <td class="text-right font-semibold text-gray-800">Rs {{ number_format($item->subtotal, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 space-y-2 border-t border-gray-100">
                    <div class="flex justify-between text-sm text-gray-500"><span>Subtotal</span><span>Rs {{ number_format($order->subtotal, 0) }}</span></div>
                    @if($order->discount > 0)<div class="flex justify-between text-sm text-emerald-600"><span>Discount</span><span>− Rs {{ number_format($order->discount, 0) }}</span></div>@endif
                    @if($order->shipping_fee > 0)<div class="flex justify-between text-sm text-gray-500"><span>Shipping</span><span>Rs {{ number_format($order->shipping_fee, 0) }}</span></div>@endif
                    <div class="flex justify-between text-base font-bold text-gray-800 pt-2 border-t border-gray-100"><span>Total</span><span>Rs {{ number_format($order->total, 0) }}</span></div>
                </div>
            </div>

            {{-- Shipping info --}}
            <div class="bg-white rounded-2xl p-6" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
                <h3 class="font-semibold text-gray-700 mb-4">Shipping Details</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div><p class="text-gray-400 text-xs mb-0.5">Name</p><p class="font-medium text-gray-700">{{ $order->shipping_name ?? '—' }}</p></div>
                    <div><p class="text-gray-400 text-xs mb-0.5">Phone</p><p class="font-medium text-gray-700">{{ $order->shipping_phone ?? '—' }}</p></div>
                    <div><p class="text-gray-400 text-xs mb-0.5">City</p><p class="font-medium text-gray-700">{{ $order->shipping_city ?? '—' }}</p></div>
                    <div><p class="text-gray-400 text-xs mb-0.5">Address</p><p class="font-medium text-gray-700">{{ $order->shipping_address ?? '—' }}</p></div>
                </div>
                @if($order->notes)<div class="mt-3 p-3 bg-gray-50 rounded-xl text-sm text-gray-600"><span class="font-semibold">Notes:</span> {{ $order->notes }}</div>@endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl p-5 space-y-3" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Order Info</p>
                @php $sc = $order->status_color; $pc = $order->payment_status_color; @endphp
                <div class="flex justify-between items-center text-sm"><span class="text-gray-500">Status</span><span class="badge-{{ $sc }}">{{ ucfirst($order->status) }}</span></div>
                <div class="flex justify-between items-center text-sm"><span class="text-gray-500">Payment</span><span class="badge-{{ $pc }}">{{ ucfirst($order->payment_status) }}</span></div>
                <div class="flex justify-between items-center text-sm"><span class="text-gray-500">Method</span><span class="text-gray-700 font-medium">{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</span></div>
            </div>

            @if($order->customer)
            <div class="bg-white rounded-2xl p-5" style="box-shadow:0 2px 16px rgba(0,0,0,.06)">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Customer</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-sm font-bold shrink-0"
                         style="background:#6366f1">{{ strtoupper(substr($order->customer->name,0,1)) }}</div>
                    <div>
                        <p class="font-semibold text-sm text-gray-800">{{ $order->customer->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->customer->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.customers.show', $order->customer) }}" class="mt-3 block text-center text-xs text-indigo-600 hover:underline">View Customer →</a>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Delete this order permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 text-sm rounded-xl border border-red-200 text-red-500 hover:bg-red-50 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete Order
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
