<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#FDCCE6">
    <title>Delivery Address | Pandiyan Store</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
</head>
<body class="checkout-page">
<main class="checkout-shell">
    <a class="checkout-logo" href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a>
    <div class="checkout-heading"><span>Step 1 of 2</span><h1>Delivery address</h1><p>Order total</p><strong class="payment-total">₹{{ number_format($total,2) }}</strong></div>
    @if($errors->any())<div class="checkout-error">{{ $errors->first() }}</div>@endif
    <form class="address-form" method="POST" action="{{ route('checkout.address') }}">
        @csrf
        <label>Name<input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" required></label>
        <label>Email ID<input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" autocomplete="email" required></label>
        <label>Phone number<input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" autocomplete="tel" required></label>
        <div class="form-row"><label>City<input type="text" name="city" value="{{ old('city',$detail?->city) }}" required></label><label>Area<input type="text" name="area" value="{{ old('area',$detail?->area) }}" required></label></div>
        <label>Nearby landmark <small>(optional)</small><input type="text" name="nearby_landmark" value="{{ old('nearby_landmark',$detail?->nearby_landmark) }}"></label>
        <label>Full address<textarea name="address" rows="4" required>{{ old('address',$detail?->address) }}</textarea></label>
        <button class="checkout-primary" type="submit">Continue to payment <span>&rarr;</span></button>
    </form>
    <a class="checkout-back" href="{{ route('cart.index') }}">&larr; Back to cart</a>
</main>
</body>
</html>
