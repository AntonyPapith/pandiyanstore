<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#FDCCE6">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment | Pandiyan Store</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gpay.css') }}">
</head>

<body class="checkout-page">
    <main class="checkout-shell payment-shell">
        <a class="checkout-logo" href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a>
        <div class="checkout-heading"><span>Step 2 of 2</span>
            <h1>Choose payment</h1>
            <p>Amount to pay</p><strong class="payment-total">₹{{ number_format($total,2) }}</strong>
        </div>

        <section class="delivery-card">
            <div class="delivery-card-head">
                <h2>Delivering to</h2><a href="{{ route('checkout', ['edit' => 1]) }}">Edit</a>
            </div>
            <strong>{{ auth()->user()->name }}</strong>
            <p>{{ auth()->user()->email }} · {{ auth()->user()->phone }}</p>
            <p>{{ $detail->address }}, {{ $detail->area }}, {{ $detail->city }}@if($detail->nearby_landmark), near {{ $detail->nearby_landmark }}@endif</p>
        </section>

        @if($errors->any())<p class="checkout-error">{{ $errors->first() }}</p>@endif
        @if(!$upiUrl)<p class="checkout-error">Online payment is temporarily unavailable. Cash on delivery is still available.</p>@endif

        <form class="payment-form" id="paymentForm" method="POST" action="{{ route('orders.store') }}">@csrf<input type="hidden" name="upi_reference" value="{{ $upiReference }}">
            <fieldset>
                <legend>Payment method</legend>
                <label class="payment-option {{ !$codAvailable ? 'is-disabled' : '' }}" @if(!$codAvailable) aria-disabled="true" @endif><input type="radio" name="payment_method" value="cod" @checked($codAvailable) @disabled(!$codAvailable)><span class="payment-radio"></span><span class="payment-icon">₹</span><span class="payment-copy"><strong>Cash on delivery</strong><small>{{ $codAvailable ? 'Pay when your order arrives' : 'Cash on delivery not available in this product' }}</small></span></label>
                <label class="payment-option"><input type="radio" name="payment_method" value="upi" @checked(!$codAvailable)><span class="payment-radio"></span><span class="payment-icon gpay"><b>G</b></span><span class="payment-copy"><strong>Google Pay</strong><small>Pay securely using the GPay app</small></span></label>
            </fieldset>
            <p class="checkout-error" id="paymentError" hidden></p>
            <button class="checkout-primary" id="paymentButton" type="submit">{{ $codAvailable ? 'Confirm order' : 'Open GPay / UPI' }} <span>&rarr;</span></button>
        </form>
        <a class="checkout-back" href="{{ route('checkout', ['edit' => 1]) }}">&larr; Edit delivery address</a>
    </main>
    <script>window.PANDIAN_UPI = {{ Illuminate\Support\Js::from(['url' => $upiUrl]) }};</script>
    <script src="{{ asset('js/payment.js') }}?v={{ filemtime(public_path('js/payment.js')) }}"></script>
</body>

</html>
