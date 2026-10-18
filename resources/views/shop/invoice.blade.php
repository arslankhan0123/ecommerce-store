@php
    /** @var \App\Models\Order $order */
    $pdf   = $pdf ?? false;
    $store = $store ?? ['name' => 'Zelari', 'phone' => '', 'address' => '', 'email' => ''];
    // dompdf reads local files directly; browsers need a URL
    $logoSrc = $pdf ? public_path('images/logo.png') : asset('images/logo.png');
    $paid  = $order->payment_status === 'paid';
    $method = ['cash_on_delivery' => 'Cash on Delivery', 'bank_transfer' => 'Bank Transfer', 'card' => 'Card'][$order->payment_method] ?? ucwords(str_replace('_', ' ', $order->payment_method));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice {{ $order->order_number }} — {{ $store['name'] }}</title>
<style>
  @page { margin: 28px 32px; }
  * { box-sizing: border-box; }
  body { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; color: #0f1230; font-size: 12px; margin: 0; }
  .wrap { width: 100%; }
  .top { width: 100%; border-bottom: 3px solid #6a3df5; padding-bottom: 14px; margin-bottom: 18px; }
  .top td { vertical-align: top; }
  .logo { height: 44px; }
  .inv-title { font-size: 28px; font-weight: 700; letter-spacing: -1px; color: #6a3df5; text-align: right; }
  .inv-meta { text-align: right; color: #6b7191; font-size: 11px; line-height: 1.6; }
  .inv-meta b { color: #0f1230; }
  .badge { display: inline-block; padding: 3px 9px; border-radius: 999px; font-size: 10px; font-weight: 700; }
  .b-paid { background: #eafaf3; color: #0d8f6a; } .b-unpaid { background: #fff5e0; color: #b36b00; } .b-status { background: #efeaff; color: #6a3df5; }
  .cols { width: 100%; margin-bottom: 18px; }
  .cols td { vertical-align: top; width: 33.33%; padding-right: 10px; }
  .card { background: #f6f7fc; border-radius: 10px; padding: 12px 14px; height: 100%; }
  .card .lbl { font-size: 9.5px; letter-spacing: .08em; text-transform: uppercase; color: #9aa0bd; font-weight: 700; margin-bottom: 5px; }
  .card .val { font-size: 12px; line-height: 1.55; }
  .card .val b { font-size: 13px; }
  table.items { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
  table.items th { background: #0f1230; color: #fff; text-align: left; font-size: 10.5px; letter-spacing: .05em; text-transform: uppercase; padding: 9px 10px; }
  table.items th.r, table.items td.r { text-align: right; }
  table.items td { padding: 10px; border-bottom: 1px solid #e7e9f3; vertical-align: top; }
  table.items tr:nth-child(even) td { background: #fafbfe; }
  .pname { font-weight: 700; font-size: 12.5px; }
  .psku { color: #9aa0bd; font-size: 10.5px; }
  .totals { width: 100%; }
  .totals td { vertical-align: top; }
  .tbl { width: 260px; float: right; }
  .tbl td { padding: 5px 0; font-size: 12px; }
  .tbl td.l { color: #6b7191; }
  .tbl td.r { text-align: right; font-weight: 700; }
  .tbl tr.grand td { border-top: 2px solid #0f1230; padding-top: 9px; font-size: 15px; font-weight: 700; }
  .tbl tr.grand td.r { color: #6a3df5; font-size: 18px; }
  .notes { clear: both; margin-top: 22px; padding: 12px 14px; background: #f6f7fc; border-left: 3px solid #6a3df5; border-radius: 0 10px 10px 0; color: #6b7191; }
  .foot { margin-top: 28px; padding-top: 12px; border-top: 1px solid #e7e9f3; color: #9aa0bd; font-size: 10.5px; text-align: center; line-height: 1.6; }
  .thanks { margin-top: 26px; text-align: center; font-size: 13px; color: #0f1230; }
  .thanks b { color: #6a3df5; }
  @media print { .no-print { display: none !important; } body { font-size: 12px; } }
  .no-print { position: fixed; bottom: 18px; right: 18px; box-shadow: 0 12px 30px -10px rgba(106,61,245,.7); background: #6a3df5; color: #fff; border: 0; border-radius: 10px; padding: 10px 16px; font-weight: 700; cursor: pointer; font-family: inherit; }
</style>
</head>
<body>
@unless($pdf)
  <button class="no-print" onclick="window.print()">Save as PDF / Print</button>
@endunless
<div class="wrap">

  <table class="top" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <img src="{{ $logoSrc }}" class="logo" alt="{{ $store['name'] }}">
        <div style="color:#6b7191;font-size:11px;line-height:1.6;margin-top:8px;">
          {{ $store['address'] }}<br>
          @if($store['phone']){{ $store['phone'] }}<br>@endif
          @if($store['email']){{ $store['email'] }}@endif
        </div>
      </td>
      <td>
        <div class="inv-title">INVOICE</div>
        <div class="inv-meta">
          <b>#{{ $order->order_number }}</b><br>
          Date: <b>{{ $order->created_at->format('d M Y') }}</b><br>
          <span class="badge {{ $paid ? 'b-paid' : 'b-unpaid' }}">{{ $paid ? 'PAID' : 'UNPAID' }}</span>
          <span class="badge b-status">{{ strtoupper($order->status) }}</span>
        </div>
      </td>
    </tr>
  </table>

  <table class="cols" cellspacing="0" cellpadding="0">
    <tr>
      <td><div class="card"><div class="lbl">Billed to</div><div class="val"><b>{{ $order->shipping_name }}</b><br>{{ $order->customer_email }}<br>{{ $order->shipping_phone }}</div></div></td>
      <td><div class="card"><div class="lbl">Ship to</div><div class="val">{{ $order->shipping_address }}<br>{{ $order->shipping_city }}</div></div></td>
      <td style="padding-right:0"><div class="card"><div class="lbl">Payment</div><div class="val"><b>{{ $method }}</b><br>{{ $paid ? 'Payment received' : ($order->payment_method === 'cash_on_delivery' ? 'Pay on delivery' : 'Awaiting payment') }}<br>Delivery: 2–5 business days</div></div></td>
    </tr>
  </table>

  <table class="items" cellspacing="0" cellpadding="0">
    <thead>
      <tr><th style="width:38px">#</th><th>Item</th><th class="r" style="width:90px">Unit price</th><th class="r" style="width:50px">Qty</th><th class="r" style="width:100px">Amount</th></tr>
    </thead>
    <tbody>
      @foreach($order->items as $i => $it)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td><div class="pname">{{ $it->product_name }}</div>@if($it->product_sku)<div class="psku">SKU: {{ $it->product_sku }}</div>@endif</td>
        <td class="r">PKR {{ number_format($it->unit_price, 0) }}</td>
        <td class="r">{{ $it->quantity }}</td>
        <td class="r"><b>PKR {{ number_format($it->subtotal, 0) }}</b></td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <table class="totals" cellspacing="0" cellpadding="0">
    <tr>
      <td style="width:55%;color:#6b7191;font-size:11px;line-height:1.7;">
        <b style="color:#0f1230;">Thank you for shopping with {{ $store['name'] }}.</b><br>
        Items can be returned within 7 days of delivery in unused condition.<br>
        Keep this invoice for your records.
      </td>
      <td>
        <table class="tbl" cellspacing="0" cellpadding="0">
          <tr><td class="l">Subtotal</td><td class="r">PKR {{ number_format($order->subtotal, 0) }}</td></tr>
          @if($order->discount > 0)<tr><td class="l">Discount</td><td class="r" style="color:#0d8f6a">− PKR {{ number_format($order->discount, 0) }}</td></tr>@endif
          <tr><td class="l">Shipping</td><td class="r">{{ $order->shipping_fee > 0 ? 'PKR ' . number_format($order->shipping_fee, 0) : 'Free' }}</td></tr>
          <tr class="grand"><td class="l">Total</td><td class="r">PKR {{ number_format($order->total, 0) }}</td></tr>
        </table>
      </td>
    </tr>
  </table>

  @if($order->notes)
    <div class="notes"><b style="color:#0f1230">Order notes:</b> {{ $order->notes }}</div>
  @endif

  <div class="thanks">Questions about this order? Contact us and quote <b>#{{ $order->order_number }}</b>.</div>
  <div class="foot">{{ $store['name'] }} · {{ $store['address'] }} @if($store['phone'])· {{ $store['phone'] }}@endif<br>This is a computer-generated invoice and does not require a signature.</div>
</div>
@unless($pdf)
<script>if (new URLSearchParams(location.search).get('print')==='1') setTimeout(()=>window.print(), 400);</script>
@endunless
</body>
</html>
