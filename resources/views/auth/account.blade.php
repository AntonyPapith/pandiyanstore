<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#FDCCE6">
    <title>My Account | Pandiyan Store</title>
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-success.css') }}">
</head>

<body class="account-page">
    <main class="account-shell">
        <header class="account-header">
            <a class="account-logo" href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a>
            <div class="profile-icon"><svg viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.7" />
                    <path d="M4 21c.8-5 3.5-7 8-7s7.2 2 8 7" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                </svg></div>
            <h1>My Account</h1>
            <p>Manage your profile, address and orders</p>
        </header>

        @if(session('order_success'))
        <div class="order-success-overlay" id="orderSuccessPopup" role="dialog" aria-modal="true">
            <div class="confetti" aria-hidden="true">@for($i=0;$i<24;$i++)<i style="--i:{{ $i }}"></i>@endfor</div>
            <div class="order-success-popup">
                <div class="success-check">✓</div>
                <h2>Order placed!</h2>
                <p>{{ session('order_success') }}</p><button type="button" id="closeOrderSuccess">View my order</button>
            </div>
        </div>
        @endif
        @if(session('success'))<div class="account-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="account-error">{{ $errors->first() }}</div>@endif

        <section class="account-card">
            <div class="section-title"><span>Personal details</span></div>
            <form class="account-form" method="POST" action="{{ route('customer.account.update') }}">@csrf @method('PATCH')<label>Name<input type="text" name="name" value="{{ old('name',auth()->user()->name) }}" required></label><label>Email ID<input type="email" value="{{ auth()->user()->email }}" readonly></label><label>Phone number<input type="tel" name="phone" value="{{ old('phone',auth()->user()->phone) }}" required></label><button class="account-primary" type="submit">Save changes</button></form>
        </section>
        <section class="account-card">
            <div class="section-title"><span>Delivery address</span><a href="{{ route('checkout', ['edit' => 1]) }}">Edit</a></div>@if(auth()->user()->customerDetail)<div class="address-content">
                <p>{{ auth()->user()->customerDetail->address }}, {{ auth()->user()->customerDetail->area }}, {{ auth()->user()->customerDetail->city }}@if(auth()->user()->customerDetail->nearby_landmark)<small>Landmark: {{ auth()->user()->customerDetail->nearby_landmark }}</small>@endif</p>
            </div>@else<p class="no-address">No delivery address saved yet.</p>@endif
        </section>
        <section class="account-card" id="orders">
            <div class="section-title"><span>My orders</span></div>
            @forelse(auth()->user()->orders()->with('items')->latest()->get() as $order)
            <article id="order-{{ $order->id }}" class="account-order {{ (int) session('placed_order_id') === $order->id ? 'new-order' : '' }}">
                <div><strong>{{ $order->order_number }}</strong><small>{{ $order->created_at->timezone('Asia/Kolkata')->format('d M Y') }} · {{ strtoupper($order->payment_method) }} · {{ ucfirst($order->order_status) }}</small></div><strong>₹{{ number_format((float)$order->total_amount,2) }}</strong>@foreach($order->items as $item)<p>{{ $item->product_name }} × {{ $item->quantity }}</p>@endforeach
            </article>
            @empty<p class="no-address">You have not placed any orders yet.</p>@endforelse
        </section>
        <div class="account-actions"><a class="account-secondary" href="{{ route('cart.index') }}">View cart</a>
            <form method="POST" action="{{ route('customer.logout') }}">@csrf<button class="logout-link" type="submit">Log out</button></form>
        </div><a class="account-back" href="{{ route('home') }}">&larr; Back to store</a>
    </main>
    @if(session('order_success'))<script>
        document.getElementById('closeOrderSuccess').addEventListener('click', function() {
            document.getElementById('orderSuccessPopup').remove();
            document.querySelector('.new-order')?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });
    </script>@endif
</body>

</html>
