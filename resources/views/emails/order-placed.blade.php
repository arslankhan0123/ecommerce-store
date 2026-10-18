<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Order {{ $order->order_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f5fb;font-family:'Segoe UI',Helvetica,Arial,sans-serif;color:#0f1230;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f5fb;padding:28px 12px;">
<tr><td align="center">
<table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;">

  {{-- Header --}}
  <tr><td style="background:linear-gradient(120deg,#6a3df5,#22d3ee);background-color:#6a3df5;border-radius:18px 18px 0 0;padding:26px 32px;">
    <table role="presentation" width="100%"><tr>
      <td><img src="{{ $logo }}" alt="{{ $store['name'] }}" height="40" style="height:40px;display:block;border:0;"></td>
      <td align="right" style="color:#fff;font-size:12px;letter-spacing:.08em;text-transform:uppercase;font-weight:700;">Order #{{ $order->order_number }}</td>
    </tr></table>
  </td></tr>

  {{-- Hero --}}
  <tr><td style="background:#fff;padding:34px 32px 10px;">
    @if($forAdmin)
      <div style="display:inline-block;background:#fff1f5;color:#c2185b;font-weight:700;font-size:12px;padding:5px 12px;border-radius:999px;margin-bottom:12px;">NEW ORDER RECEIVED</div>
      <h1 style="margin:0 0 8px;font-size:26px;letter-spacing:-.5px;">{{ $order->shipping_name }} just placed an order</h1>
      <p style="margin:0;color:#6b7191;font-size:15px;line-height:1.6;">Total <b style="color:#0f1230;">{{ zorder_price($order, $order->total) }}</b> · {{ $order->payment_label }}{{ $order->payment_status === 'paid' ? ' (paid)' : '' }} · {{ $order->created_at->format('d M Y, g:i A') }}</p>
    @else
      <div style="display:inline-block;background:#eafaf3;color:#0d8f6a;font-weight:700;font-size:12px;padding:5px 12px;border-radius:999px;margin-bottom:12px;">✓ ORDER CONFIRMED</div>
      <h1 style="margin:0 0 8px;font-size:26px;letter-spacing:-.5px;">Thanks, {{ explode(' ', trim($order->shipping_name))[0] ?? 'there' }}! We've got your order.</h1>
      <p style="margin:0;color:#6b7191;font-size:15px;line-height:1.6;">We're getting it ready now. You'll get another email when it ships. Your invoice is attached as a PDF.</p>
    @endif
  </td></tr>

  {{-- Summary tiles --}}
  <tr><td style="background:#fff;padding:18px 32px;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"><tr>
      @foreach([['Order number','#'.$order->order_number],['Placed on',$order->created_at->format('d M Y')],['Payment',$order->payment_label.($order->payment_status==='paid'?' — paid':'')],['Status',ucfirst($order->status)]] as [$l,$v])
      <td width="25%" style="padding:4px;"><div style="background:#f6f7fc;border-radius:12px;padding:12px 10px;"><div style="font-size:10px;color:#9aa0bd;font-weight:700;text-transform:uppercase;letter-spacing:.06em;">{{ $l }}</div><div style="font-size:13px;font-weight:700;margin-top:3px;">{{ $v }}</div></div></td>
      @endforeach
    </tr></table>
  </td></tr>

  {{-- Items --}}
  <tr><td style="background:#fff;padding:10px 32px 0;">
    <h3 style="margin:0 0 10px;font-size:15px;">Items</h3>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
      @foreach($order->items as $it)
      <tr>
        <td style="padding:12px 0;border-bottom:1px solid #eef0f6;">
          <div style="font-weight:700;font-size:14px;">{{ $it->product_name }}</div>
          <div style="color:#6b7191;font-size:12px;margin-top:2px;">{{ zorder_price($order, $it->unit_price) }} × {{ $it->quantity }}@if($it->product_sku) · {{ $it->product_sku }}@endif</div>
        </td>
        <td align="right" style="padding:12px 0;border-bottom:1px solid #eef0f6;font-weight:800;font-size:14px;white-space:nowrap;">{{ zorder_price($order, $it->subtotal) }}</td>
      </tr>
      @endforeach
      <tr><td style="padding:10px 0 4px;color:#6b7191;font-size:14px;">Subtotal</td><td align="right" style="padding:10px 0 4px;font-size:14px;">{{ zorder_price($order, $order->subtotal) }}</td></tr>
      @if($order->discount > 0)<tr><td style="padding:4px 0;color:#6b7191;font-size:14px;">Discount</td><td align="right" style="padding:4px 0;font-size:14px;color:#0d8f6a;">− {{ zorder_price($order, $order->discount) }}</td></tr>@endif
      <tr><td style="padding:4px 0;color:#6b7191;font-size:14px;">Shipping</td><td align="right" style="padding:4px 0;font-size:14px;color:#0d8f6a;">{{ $order->shipping_fee > 0 ? zorder_price($order, $order->shipping_fee) : 'Free' }}</td></tr>
      <tr><td style="padding:12px 0 0;font-size:16px;font-weight:800;border-top:2px solid #0f1230;">Total</td><td align="right" style="padding:12px 0 0;font-size:20px;font-weight:800;color:#6a3df5;border-top:2px solid #0f1230;">{{ zorder_price($order, $order->total) }}</td></tr>
    </table>
  </td></tr>

  {{-- Delivery --}}
  <tr><td style="background:#fff;padding:24px 32px;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"><tr>
      <td width="50%" valign="top" style="padding-right:10px;">
        <div style="background:#f6f7fc;border-radius:14px;padding:16px;">
          <div style="font-size:10px;color:#9aa0bd;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">Deliver to</div>
          <div style="font-weight:700;font-size:14px;">{{ $order->shipping_name }}</div>
          <div style="color:#6b7191;font-size:13px;line-height:1.5;margin-top:3px;">{{ $order->shipping_address }}<br>{{ $order->shipping_city }}<br>{{ $order->shipping_phone }}<br>{{ $order->customer_email }}</div>
        </div>
      </td>
      <td width="50%" valign="top" style="padding-left:10px;">
        <div style="background:#f6f7fc;border-radius:14px;padding:16px;">
          <div style="font-size:10px;color:#9aa0bd;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:6px;">What's next</div>
          <div style="color:#6b7191;font-size:13px;line-height:1.7;">
            ✓ Order confirmed<br>
            ○ Packed within 24 hours<br>
            ○ Delivered in 2–5 business days<br>
            @if($order->payment_method==='cash_on_delivery')○ Pay <b style="color:#0f1230;">{{ zorder_price($order, $order->total) }}</b> in cash on delivery @endif
            @if($order->payment_method==='bank_transfer')○ Bank details will follow by email @endif
            @if($order->payment_method==='card')○ Paid by card — nothing more to do @endif
          </div>
        </div>
      </td>
    </tr></table>
    @if($order->notes)
    <div style="margin-top:14px;padding:12px 16px;border-left:3px solid #6a3df5;background:#f6f7fc;border-radius:0 12px 12px 0;color:#6b7191;font-size:13px;"><b style="color:#0f1230;">Order notes:</b> {{ $order->notes }}</div>
    @endif
  </td></tr>

  {{-- CTA --}}
  <tr><td style="background:#fff;padding:0 32px 32px;" align="center">
    @if($forAdmin)
      <a href="{{ url('/admin/orders/'.$order->id) }}" style="display:inline-block;background:#6a3df5;color:#fff;text-decoration:none;font-weight:700;padding:13px 26px;border-radius:12px;font-size:14px;">Open order in admin</a>
    @else
      <a href="{{ url('/shop') }}" style="display:inline-block;background:#6a3df5;color:#fff;text-decoration:none;font-weight:700;padding:13px 26px;border-radius:12px;font-size:14px;">Continue shopping</a>
    @endif
  </td></tr>

  {{-- Footer --}}
  <tr><td style="background:#0f1230;border-radius:0 0 18px 18px;padding:22px 32px;color:rgba(255,255,255,.7);font-size:12px;line-height:1.7;" align="center">
    <b style="color:#fff;font-size:14px;">{{ $store['name'] }}</b><br>
    {{ $store['address'] }}@if($store['phone']) · {{ $store['phone'] }}@endif @if($store['email']) · {{ $store['email'] }}@endif<br>
    <span style="color:rgba(255,255,255,.45);">Questions? Just reply to this email.</span>
  </td></tr>

</table>
</td></tr>
</table>
</body>
</html>
