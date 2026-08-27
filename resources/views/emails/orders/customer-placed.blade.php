<!DOCTYPE html>
<html lang="en">
<body style="margin:0;padding:24px;background:#fce5f1;color:#08033d;font-family:Arial,sans-serif">
<div style="max-width:620px;margin:auto;padding:28px;background:#fff;border-radius:16px">
    <h1 style="margin-top:0">Thank you for your order!</h1>
    <p>Hello {{ $order->customer_name }}, your order <strong>{{ $order->order_number }}</strong> was placed successfully.</p>
    <table style="width:100%;border-collapse:collapse">
        @foreach($order->items as $item)
            <tr><td style="padding:10px 0;border-bottom:1px solid #eee"><strong>{{ $item->product_name }}</strong><br>Qty: {{ $item->quantity }}</td><td style="padding:10px 0;border-bottom:1px solid #eee;text-align:right">₹{{ number_format((float) $item->amount, 2) }}</td></tr>
        @endforeach
        <tr><td style="padding-top:14px"><strong>Total · {{ strtoupper($order->payment_method) }}</strong></td><td style="padding-top:14px;text-align:right"><strong>₹{{ number_format((float) $order->total_amount, 2) }}</strong></td></tr>
    </table>
    <p><strong>Delivery address</strong><br>{{ $order->address }}, {{ $order->area }}, {{ $order->city }}@if($order->nearby_landmark)<br>Near {{ $order->nearby_landmark }}@endif</p>
    <p>We will update you when your order progresses.</p>
    <p style="margin-bottom:0">Pandiyan Store</p>
</div>
</body>
</html>
