<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#FDCCE6"><title>Cart | Pandiyan Store</title><link rel="icon" type="image/png" href="{{ asset('logo.png') }}"><link rel="stylesheet" href="{{ asset('css/seyon.css') }}"></head>
<body class="cart-page"><main class="cart-shell">
<header class="cart-header"><a href="{{ $continueShoppingUrl }}">&larr; Continue shopping</a><h1>Your Cart</h1><a href="{{ route('wishlist.index') }}" aria-label="Open wishlist">♥ Wishlist ({{ count(session('wishlist', [])) }})</a></header>
<p class="cart-alert cart-quantity-alert" id="cartMessage" role="alert" hidden></p>
@if(session('cart_success'))<p class="cart-alert">{{ session('cart_success') }}</p>@endif
<div class="cart-layout">
<section class="cart-items">
@forelse($products as $product)
<article class="cart-item" data-product-id="{{ $product->id }}" data-unit-price="{{ $product->cart_price }}">
    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}">
    <div class="cart-item-info"><h2>{{ $product->name }}</h2><strong>₹{{ number_format($product->cart_price,2) }}</strong>
        <div class="cart-controls">
            <form class="quantity-form" method="POST" action="{{ route('cart.update',$product) }}">@csrf @method('PATCH')<label>Qty <input type="number" name="quantity" min="1" max="{{ $product->quantity }}" value="{{ $product->cart_quantity }}" aria-label="Quantity for {{ $product->name }}"></label></form>
            <form method="POST" action="{{ route('cart.remove',$product) }}">@csrf @method('DELETE')<button class="cart-remove" type="submit" aria-label="Remove {{ $product->name }}" title="Remove"><svg viewBox="0 0 24 24" fill="none"><path d="M4 7h16M9 7V4h6v3m3 0-1 14H7L6 7m4 4v6m4-6v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></button></form>
            <strong class="cart-line-total">₹{{ number_format($product->cart_amount,2) }}</strong>
        </div>
    </div>
</article>
@empty<div class="cart-empty"><p>Your cart is empty.</p><a href="{{ route('home') }}">Browse categories</a></div>@endforelse
</section>
<aside class="cart-summary"><h2>Order summary</h2><div><span>Total amount</span><strong id="cartTotal">₹{{ number_format($total,2) }}</strong></div>@if($products->isNotEmpty())<a class="place-order" href="{{ route('checkout') }}">Place order</a>@endif</aside>
</div></main><script src="{{ asset('js/cart.js') }}?v={{ filemtime(public_path('js/cart.js')) }}"></script></body></html>
