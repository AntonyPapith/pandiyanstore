<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#FDCCE6">
    <title>{{ $category->name }} | Pandiyan Store</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seyon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product-fixes.css') }}">
</head>
<body class="product-page">
<main class="product-shell">
    <header class="product-header">
        <a class="product-back" href="{{ route('home') }}" aria-label="Back to categories" onclick="if (window.history.length > 1) { event.preventDefault(); window.history.back(); }">&larr;</a>
        <div><p>Shop category</p><h1>{{ $category->name }}</h1></div>
        <div class="product-header-actions"><a class="product-wishlist-link" href="{{ route('wishlist.index') }}" aria-label="Open wishlist">♡<span>{{ count(session('wishlist', [])) }}</span></a><a class="product-cart-link" href="{{ route('cart.index') }}" aria-label="Open cart"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 1.9-1.4L21 7H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="20" r="1.3" fill="currentColor"/><circle cx="18" cy="20" r="1.3" fill="currentColor"/></svg><span>{{ count(array_filter(session('cart', []), fn ($quantity) => $quantity > 0)) }}</span></a></div>
    </header>

    <div class="product-grid">
        @forelse($category->products as $product)
            <article class="product-card">
                <div class="product-card-media">
                    <img src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" loading="lazy">
                    <form class="wishlist-toggle" method="POST" action="{{ route('wishlist.toggle', $product) }}">@csrf<button type="submit" aria-label="Toggle {{ $product->name }} in wishlist" class="{{ isset(session('wishlist', [])[$product->id]) ? 'active' : '' }}">♥</button></form>
                    @if($product->discount_price !== null)
                        <span class="discount-badge">Sale</span>
                    @endif
                </div>
                <div class="product-card-body">
                    <h2>{{ $product->name }}</h2>
                    <div class="product-card-price">
                        <strong>₹{{ number_format((float) ($product->discount_price ?? $product->price), 2) }}</strong>
                        @if($product->discount_price !== null)<del>₹{{ number_format((float) $product->price, 2) }}</del>@endif
                    </div>
                    <p class="product-card-stock {{ $product->quantity < 1 ? 'out' : '' }}">{{ $product->quantity > 0 ? $product->quantity.' in stock' : 'Out of stock' }}</p>
                    @if($product->description)<p class="product-card-description">{{ $product->description }}</p>@endif
                    <form class="add-cart-form" method="POST" action="{{ route('cart.add', $product) }}">@csrf<input type="hidden" name="quantity" value="1"><button type="submit" {{ $product->quantity < 1 ? 'disabled' : '' }}>{{ $product->quantity > 0 ? 'Add to cart' : 'Out of stock' }}</button></form>
                </div>
            </article>
        @empty
            <div class="product-grid-empty"><p>No products added for this category yet.</p></div>
        @endforelse
    </div>
</main>
<script src="{{ asset('js/product-shine.js') }}"></script>
<script src="{{ asset('js/store-actions.js') }}?v={{ filemtime(public_path('js/store-actions.js')) }}"></script>
</body>
</html>
