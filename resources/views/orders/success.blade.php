<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#FDCCE6">
    <title>Order Placed | Pandiyan Store</title>
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
</head>

<body class="checkout-page">
    <main class="checkout-shell payment-shell"><a class="checkout-logo" href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a>
        <div class="order-success-icon">✓</div>
        <div class="checkout-heading">
            <h1>{{ session('order_success', 'Your order was placed successfully!') }}</h1>
            <p>Thank you for shopping with Pandiyan Store. This order is now available in My Account.</p>
        </div>
        <section class="delivery-card">
            <div class="delivery-card-head">
                <h2>Order {{ $order->order_number }}</h2><strong>₹{{ number_format((float)$order->total_amount,2) }}</strong>
            </div>
            <p>{{ strtoupper($order->payment_method) }} · {{ ucfirst($order->order_status) }}</p>@foreach($order->items as $item)<p><strong>{{ $item->product_name }}</strong> × {{ $item->quantity }} — ₹{{ number_format((float)$item->amount,2) }}</p>@endforeach
        </section>
        @php
        $message = rawurlencode("Thank you for ordering from Pandiyan Store. Order {$order->order_number} was placed successfully. Total ₹{$order->total_amount}. Products: ".$order->items->map(fn($item) => $item->product_name.' x '.$item->quantity)->join(', '));
        $formatIndianWhatsApp = function ($phone) {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        $digits = ltrim($digits, '0');
        return strlen($digits) === 10 ? '91'.$digits : $digits;
        };
        $customerWhatsApp = $formatIndianWhatsApp($order->customer_phone);
        $adminWhatsApp = $formatIndianWhatsApp($adminPhone);
        @endphp
        <p class="order-message">WhatsApp message prepared for customer +{{ $customerWhatsApp }}. Press Send after WhatsApp opens.</p><a class="checkout-primary" href="https://wa.me/{{ $customerWhatsApp }}?text={{ $message }}" target="_blank" rel="noopener">Send customer thank-you message</a>@if($adminWhatsApp)<a class="checkout-primary" href="https://wa.me/{{ $adminWhatsApp }}?text={{ $message }}" target="_blank" rel="noopener">Send order details to admin</a>@endif<a class="checkout-back" href="{{ route('customer.account') }}">View order in My Account</a>
    </main>
</body>

</html>