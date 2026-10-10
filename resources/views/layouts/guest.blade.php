<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ShopNest') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

        /* BG */
        .left-panel {
            background: linear-gradient(160deg, #0f0c29 0%, #302b63 50%, #1a1a3e 100%);
            position: relative; overflow: hidden;
        }

        /* AURORA BLOBS */
        .blob {
            position: absolute; border-radius: 50%;
            filter: blur(90px); pointer-events: none; opacity: 0.55;
        }
        .blob-1 { width: 420px; height: 420px; background: #6366f1; top: -140px; left: -100px; animation: b1 14s ease-in-out infinite; }
        .blob-2 { width: 320px; height: 320px; background: #8b5cf6; bottom: -100px; right: -80px; animation: b2 11s ease-in-out infinite; }
        .blob-3 { width: 220px; height: 220px; background: #3b82f6; top: 45%; left: 55%; animation: b3 17s ease-in-out infinite; }
        @keyframes b1 { 0%,100%{transform:translate(0,0);} 50%{transform:translate(50px,35px);} }
        @keyframes b2 { 0%,100%{transform:translate(0,0);} 50%{transform:translate(-40px,-30px);} }
        @keyframes b3 { 0%,100%{transform:translate(0,0);} 50%{transform:translate(25px,-45px);} }

        /* GLASS CARDS */
        .glass {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        /* 3D PRODUCT CARD */
        .card-3d {
            animation: tilt-float 7s ease-in-out infinite;
            border-radius: 20px;
            box-shadow: 0 30px 70px rgba(0,0,0,0.55), 0 0 0 1px rgba(255,255,255,0.06) inset;
        }
        @keyframes tilt-float {
            0%,100% { transform: perspective(900px) rotateX(5deg) rotateY(-6deg) translateY(0); }
            50%      { transform: perspective(900px) rotateX(1deg) rotateY(5deg) translateY(-14px); }
        }

        /* FLOAT SMALL */
        .fl-1 { animation: fl1 5s ease-in-out infinite; }
        .fl-2 { animation: fl2 6s ease-in-out 0.8s infinite; }
        @keyframes fl1 { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-10px);} }
        @keyframes fl2 { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-8px);} }

        /* PING */
        @keyframes ping { 75%,100%{transform:scale(2.1);opacity:0;} }
        .ping-anim { animation: ping 1.8s cubic-bezier(0,0,.2,1) infinite; }

        /* GRADIENT TEXT */
        .grad-text {
            background: linear-gradient(120deg, #a78bfa 0%, #818cf8 50%, #38bdf8 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        /* SHIMMER */
        .shimmer { background: linear-gradient(90deg,rgba(255,255,255,0) 0%,rgba(255,255,255,0.07) 50%,rgba(255,255,255,0) 100%); background-size:200% 100%; animation: shimmer 3s infinite; }
        @keyframes shimmer { 0%{background-position:200% 0;} 100%{background-position:-200% 0;} }

        /* RIGHT PANEL */
        .input-field { transition: all .25s ease; }
        .input-field:focus { box-shadow: 0 0 0 3px rgba(99,102,241,.18); }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all .25s ease;
            box-shadow: 0 4px 20px rgba(99,102,241,.4);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(99,102,241,.55); }
        .btn-primary:active { transform: translateY(0); }

        @media(max-width:1023px) { .left-panel { display: none; } }
    </style>
</head>
<body class="min-h-screen flex bg-white">

{{-- ══════════════════ LEFT PANEL ══════════════════ --}}
<div class="left-panel hidden lg:flex w-[50%] min-h-screen flex-col justify-between p-10">

    <!-- Blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    {{-- ── TOP: BRAND ── --}}
    <div class="relative z-10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:linear-gradient(135deg,#6366f1,#8b5cf6);box-shadow:0 6px 20px rgba(99,102,241,.5);">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <span class="text-white text-xl font-black tracking-tight">ShopNest</span>
            <span class="ml-auto text-xs font-semibold px-3 py-1 rounded-full"
                  style="background:rgba(99,102,241,.25);border:1px solid rgba(99,102,241,.4);color:#a5b4fc;">
                ✦ Trusted Store
            </span>
        </div>
    </div>

    {{-- ── MIDDLE: HEADLINE + 3D CARD + TOASTS ── --}}
    <div class="relative z-10 flex flex-col gap-6">

        {{-- Headline --}}
        <div>
            <p class="text-indigo-300 text-sm font-semibold tracking-widest uppercase mb-2">Premium eCommerce</p>
            <h2 class="text-4xl font-black text-white leading-tight">
                Discover.<br>Shop. <span class="grad-text">Love Every Buy.</span>
            </h2>
        </div>

        {{-- 3D Product Card --}}
        <div class="card-3d glass p-5 w-full max-w-xs mx-auto">
            {{-- Image area --}}
            <div class="rounded-xl mb-4 relative overflow-hidden shimmer flex items-center justify-center"
                 style="height:120px;background:rgba(255,255,255,0.05);">
                <svg class="w-14 h-14 text-indigo-300 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{-- Sale tag --}}
                <span class="absolute top-2.5 left-2.5 text-white text-xs font-bold px-2.5 py-1 rounded-lg"
                      style="background:linear-gradient(135deg,#f97316,#ef4444);">-40% OFF</span>
                {{-- Wish --}}
                <button class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full flex items-center justify-center"
                        style="background:rgba(255,255,255,0.15);">
                    <svg class="w-3.5 h-3.5 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            {{-- Info --}}
            <div class="flex justify-between items-start mb-2.5">
                <div>
                    <p class="text-white font-bold text-sm">Premium Wireless</p>
                    <p class="text-indigo-300 text-xs mt-0.5">Headphones Pro X</p>
                </div>
                <div class="text-right">
                    <p class="text-white font-black text-base">$89.99</p>
                    <p class="text-slate-500 text-xs line-through">$149.99</p>
                </div>
            </div>

            {{-- Stars --}}
            <div class="flex items-center gap-1.5 mb-3">
                <div class="flex gap-0.5">
                    @for($i=0;$i<5;$i++)
                    <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <span class="text-indigo-300 text-xs">4.9 · 2,847 reviews</span>
            </div>

            {{-- Stock --}}
            <div class="mb-3.5">
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-slate-400">Stock</span>
                    <span class="text-orange-400 font-semibold">Only 12 left!</span>
                </div>
                <div class="w-full h-1.5 rounded-full" style="background:rgba(255,255,255,0.1);">
                    <div class="h-1.5 rounded-full" style="width:16%;background:linear-gradient(90deg,#6366f1,#a78bfa);"></div>
                </div>
            </div>

            {{-- CTA --}}
            <button class="w-full py-2.5 rounded-xl text-white text-xs font-bold flex items-center justify-center gap-2"
                    style="background:linear-gradient(135deg,#6366f1,#8b5cf6);box-shadow:0 4px 16px rgba(99,102,241,.4);">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Add to Cart
            </button>
        </div>

        {{-- Toast notifications side by side --}}
        <div class="grid grid-cols-2 gap-3 max-w-xs mx-auto w-full">
            {{-- Shipped --}}
            <div class="glass rounded-xl p-3 fl-1 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex-shrink-0 flex items-center justify-center"
                     style="background:rgba(34,197,94,.18);">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-semibold" style="font-size:11px;">Order Shipped!</p>
                    <p class="text-slate-400" style="font-size:10px;">Arrives in 2 days</p>
                </div>
            </div>

            {{-- Live purchase --}}
            <div class="glass rounded-xl p-3 fl-2 flex items-center gap-2.5">
                <div class="relative flex-shrink-0 w-8 h-8 flex items-center justify-center">
                    <span class="ping-anim absolute inline-flex h-2.5 w-2.5 rounded-full bg-emerald-400 opacity-60"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <div>
                    <p class="text-white font-semibold" style="font-size:11px;">Ahmed just bought</p>
                    <p class="text-slate-400" style="font-size:10px;">Nike Air Max · 3m ago</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ── BOTTOM: STATS + TRUST ── --}}
    <div class="relative z-10">
        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="glass rounded-xl py-3 text-center">
                <p class="text-xl font-black" style="background:linear-gradient(120deg,#a78bfa,#60a5fa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">50K+</p>
                <p class="text-slate-400 text-xs mt-0.5">Happy Buyers</p>
            </div>
            <div class="glass rounded-xl py-3 text-center">
                <p class="text-xl font-black" style="background:linear-gradient(120deg,#a78bfa,#60a5fa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">10K+</p>
                <p class="text-slate-400 text-xs mt-0.5">Products</p>
            </div>
            <div class="glass rounded-xl py-3 text-center">
                <p class="text-xl font-black" style="background:linear-gradient(120deg,#a78bfa,#60a5fa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">4.9★</p>
                <p class="text-slate-400 text-xs mt-0.5">Avg Rating</p>
            </div>
        </div>

        {{-- Trust badges --}}
        <div class="flex items-center justify-center gap-6">
            <div class="flex items-center gap-1.5 text-slate-400" style="font-size:11px;">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                SSL Secured
            </div>
            <div class="w-px h-4" style="background:rgba(255,255,255,0.1);"></div>
            <div class="flex items-center gap-1.5 text-slate-400" style="font-size:11px;">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                </svg>
                Easy Returns
            </div>
            <div class="w-px h-4" style="background:rgba(255,255,255,0.1);"></div>
            <div class="flex items-center gap-1.5 text-slate-400" style="font-size:11px;">
                <svg class="w-3.5 h-3.5 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                Free Shipping
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════ RIGHT PANEL ══════════════════ --}}
<div class="flex-1 flex items-center justify-center p-6 lg:p-14 bg-white">
    <div class="w-full max-w-md">
        {{-- Mobile brand --}}
        <div class="lg:hidden text-center mb-8">
            <div class="inline-flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                     style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <span class="text-xl font-black text-slate-800">ShopNest</span>
            </div>
        </div>
        {{ $slot }}
    </div>
</div>

</body>
</html>
