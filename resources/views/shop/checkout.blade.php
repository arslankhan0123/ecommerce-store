@extends('layouts.store')
@section('title', 'Checkout')

@push('styles')
<style>
.co{padding:34px 0 60px;background:var(--surface)}
.co-grid{display:grid;grid-template-columns:1fr 420px;gap:28px;align-items:start}
@media(max-width:992px){.co-grid{grid-template-columns:1fr}}
.steps{display:flex;align-items:center;gap:10px;margin-bottom:26px;flex-wrap:wrap}
.step{display:flex;align-items:center;gap:8px;font-size:13px;font-weight:700;color:var(--faint)}
.step i{width:30px;height:30px;border-radius:50%;background:#fff;display:grid;place-items:center;font-size:12px;font-style:normal}
.step.on{color:var(--p)}.step.on i{background:var(--grad);color:#fff;box-shadow:0 8px 18px -8px rgba(106,61,245,.9)}
.step.done{color:var(--ok)}.step.done i{background:var(--ok);color:#fff}
.step-line{flex:1;max-width:70px;height:2px;background:var(--line-2)}
.card-z{background:#fff;border:1px solid var(--line);border-radius:var(--r-lg);padding:26px;margin-bottom:18px}
.card-z h3{font-size:17px;font-weight:800;margin:0 0 20px;display:flex;align-items:center;gap:10px}
.card-z h3 i{width:34px;height:34px;border-radius:10px;background:var(--grad);color:#fff;display:grid;place-items:center;font-size:14px}
.alert-z{background:#fdecef;border:1px solid #ffc7d4;color:#b4124b;border-radius:14px;padding:14px 18px;font-size:14px;margin-bottom:18px;display:flex;gap:10px;align-items:flex-start}
.payopt{display:flex;align-items:flex-start;gap:14px;border:1.5px solid var(--line-2);border-radius:14px;padding:16px;cursor:pointer;transition:.2s;margin-bottom:10px;position:relative;background:#fff}
.payopt:has(input:checked){border-color:var(--p);background:var(--p-soft);box-shadow:var(--glow)}
.payopt input{accent-color:var(--p);margin-top:3px;width:18px;height:18px}
.payopt .ic{width:44px;height:44px;border-radius:12px;background:var(--surface);display:grid;place-items:center;color:var(--p);font-size:18px;flex-shrink:0}
.payopt:has(input:checked) .ic{background:var(--grad);color:#fff}
.payopt b{font-size:14.5px;display:block}
.payopt small{color:var(--muted);font-size:13px}
.payopt .tag{position:absolute;right:14px;top:14px;font-size:11px;font-weight:800;background:#e6f9f2;color:#0d8f6a;padding:3px 9px;border-radius:999px}
/* summary */
.sum{background:#fff;border:1px solid var(--line);border-radius:var(--r-lg);padding:24px;position:sticky;top:calc(var(--head-h) + 70px)}
@media(max-width:992px){.sum{position:static}}
.sum h3{font-size:17px;font-weight:800;margin:0 0 16px}
.sitems{max-height:300px;overflow:auto;display:flex;flex-direction:column;gap:12px;padding-right:4px}
.sitem{display:flex;gap:12px;align-items:center}
.sitem .im{width:56px;height:56px;border-radius:12px;background:var(--surface);display:grid;place-items:center;overflow:hidden;flex-shrink:0;position:relative}
.sitem .im img{width:100%;height:100%;object-fit:contain;padding:4px}
.sitem .im i{color:#c8cce3}
.sitem .im .q{position:absolute;top:-6px;right:-6px;min-width:20px;height:20px;border-radius:10px;background:var(--ink);color:#fff;font-size:11px;font-weight:800;display:grid;place-items:center;padding:0 5px}
.sitem .nm{font-size:13.5px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.sitem .pr{font-size:14px;font-weight:800;flex-shrink:0}
.sum hr{border:0;border-top:1px dashed var(--line-2);margin:14px 0}
.sum .row2{display:flex;justify-content:space-between;font-size:14px;padding:6px 0;color:var(--muted)}
.sum .row2 b{color:var(--ink)}
.sum .tot{display:flex;justify-content:space-between;align-items:center;padding-top:6px}
.sum .tot span{font-size:16px;font-weight:800}
.sum .tot b{font-size:28px;font-weight:800;letter-spacing:-1px;background:var(--grad);-webkit-background-clip:text;background-clip:text;color:transparent}
.place{width:100%;height:54px;font-size:16px;border-radius:14px;margin-top:16px}
.secure{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:12px;font-size:12px;color:var(--faint)}
</style>
@endpush

@section('content')
<section class="co">
  <div class="wrap">
    <div class="steps">
      <div class="step done"><i><i class="fa-solid fa-check"></i></i> Cart</div><span class="step-line"></span>
      <div class="step on"><i>2</i> Checkout</div><span class="step-line"></span>
      <div class="step"><i>3</i> Done</div>
    </div>

    @if(session('checkout_error'))<div class="alert-z"><i class="fa-solid fa-circle-exclamation"></i><div>{{ session('checkout_error') }}</div></div>@endif
    @if($errors->any())<div class="alert-z"><i class="fa-solid fa-circle-exclamation"></i><div><b>Please fix the following:</b><ul style="margin:6px 0 0;padding-left:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div></div>@endif

    <form action="{{ route('checkout.store') }}" method="POST" id="coForm">
      @csrf
      <div class="co-grid">
        <div>
          <div class="card-z reveal">
            <h3><i class="fa-solid fa-user"></i> Contact & Delivery</h3>
            <div class="row g-3">
              <div class="col-sm-6 fld"><label>Full name <b>*</b></label><input class="inp {{ $errors->has('name')?'err':'' }}" name="name" value="{{ old('name') }}" required placeholder="Your full name">@error('name')<div class="ferr">{{ $message }}</div>@enderror</div>
              <div class="col-sm-6 fld"><label>Email <b>*</b></label><input class="inp {{ $errors->has('email')?'err':'' }}" type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">@error('email')<div class="ferr">{{ $message }}</div>@enderror</div>
              <div class="col-sm-6 fld"><label>Phone <b>*</b></label><input class="inp {{ $errors->has('phone')?'err':'' }}" type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+92 3xx xxxxxxx">@error('phone')<div class="ferr">{{ $message }}</div>@enderror</div>
              <div class="col-sm-6 fld"><label>City <b>*</b></label><input class="inp {{ $errors->has('city')?'err':'' }}" name="city" value="{{ old('city') }}" required placeholder="Lahore">@error('city')<div class="ferr">{{ $message }}</div>@enderror</div>
              <div class="col-12 fld"><label>Delivery address <b>*</b></label><textarea class="inp {{ $errors->has('address')?'err':'' }}" name="address" rows="3" required placeholder="House, street, area…">{{ old('address') }}</textarea>@error('address')<div class="ferr">{{ $message }}</div>@enderror</div>
              <div class="col-12 fld"><label>Order notes <span style="color:var(--faint);font-weight:600">(optional)</span></label><textarea class="inp" name="notes" rows="2" placeholder="Delivery instructions, landmark…">{{ old('notes') }}</textarea></div>
            </div>
          </div>

          <div class="card-z reveal" data-delay="1">
            <h3><i class="fa-solid fa-wallet"></i> Payment Method</h3>
            <label class="payopt">
              <input type="radio" name="payment_method" value="cash_on_delivery" {{ old('payment_method','cash_on_delivery')==='cash_on_delivery'?'checked':'' }} required>
              <span class="ic"><i class="fa-solid fa-money-bill-wave"></i></span>
              <span><b>Cash on Delivery</b><small>Pay in cash when your order arrives at your door.</small></span>
              <span class="tag">Popular</span>
            </label>
            <label class="payopt">
              <input type="radio" name="payment_method" value="bank_transfer" {{ old('payment_method')==='bank_transfer'?'checked':'' }}>
              <span class="ic"><i class="fa-solid fa-building-columns"></i></span>
              <span><b>Bank Transfer</b><small>We'll email bank details after you place the order. Ships once payment clears.</small></span>
            </label>
          </div>
        </div>

        @php $subtotal = array_sum(array_map(fn($i)=>$i['price']*$i['quantity'],$cart)); $shipping = 0; @endphp
        <aside class="sum reveal" data-delay="2">
          <h3>Order Summary</h3>
          <div class="sitems">
            @foreach($cart as $it)
            @php $img = zimg($it['image'] ?? null) ?? zcat_image(null,(int)($it['product_id'] ?? 0),200); @endphp
            <div class="sitem">
              <div class="im"><img src="{{ $img }}" alt="" style="{{ ($it['image'] ?? null) ? '' : 'object-fit:cover;padding:0' }}"><span class="q">{{ $it['quantity'] }}</span></div>
              <div style="flex:1;min-width:0"><div class="nm">{{ $it['name'] }}</div><small style="color:var(--muted)">PKR {{ number_format($it['price'],0) }} × {{ $it['quantity'] }}</small></div>
              <div class="pr">PKR {{ number_format($it['price']*$it['quantity'],0) }}</div>
            </div>
            @endforeach
          </div>
          <hr>
          <div class="row2"><span>Subtotal</span><b>PKR {{ number_format($subtotal,0) }}</b></div>
          <div class="row2"><span>Shipping</span><b style="color:var(--ok)">{{ $shipping>0 ? 'PKR '.number_format($shipping,0) : 'Free' }}</b></div>
          <hr>
          <div class="tot"><span>Total</span><b>PKR {{ number_format($subtotal+$shipping,0) }}</b></div>
          <button type="submit" class="btn-g place" id="placeBtn"><i class="fa-solid fa-lock"></i> Place Order</button>
          <div class="secure"><i class="fa-solid fa-shield-halved"></i> Prices & stock are re-verified on our server</div>
          <div style="display:flex;justify-content:center;gap:6px;margin-top:10px"><span class="pay visa">VISA</span><span class="pay mc">MC</span><span class="pay cod">COD</span><span class="pay bank">BANK</span></div>
          <a href="{{ route('cart.index') }}" style="display:block;text-align:center;margin-top:14px;font-size:13px;font-weight:700;color:var(--muted)"><i class="fa-solid fa-arrow-left"></i> Back to cart</a>
        </aside>
      </div>
    </form>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.getElementById('coForm').addEventListener('submit',function(){const b=document.getElementById('placeBtn');b.disabled=true;b.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Placing order…';});
</script>
@endpush
