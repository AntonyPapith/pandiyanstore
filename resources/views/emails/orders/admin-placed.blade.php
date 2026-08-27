<!DOCTYPE html>
<html lang="en">
<body style="margin:0;padding:24px;background:#f4f1ee;color:#08033d;font-family:Arial,sans-serif">
<div style="max-width:680px;margin:auto;padding:28px;background:#fff;border-radius:16px">
    <h1 style="margin-top:0">New customer order</h1>
    <p><strong>Order:</strong> {{ $order->order_number }}<br><strong>Placed:</strong> {{ $order->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}<br><strong>Payment:</strong> {{ strtoupper($order->payment_method) }} ({{ $order->payment_status }})</p>
    <h2>Customer details</h2>
    <p><strong>{{ $order->customer_name }}</strong><br>{{ $order->customer_email }}<br>{{ $order->customer_phone }}</p>
    <p><strong>Delivery address</strong><br>{{ $order->address }}, {{ $order->area }}, {{ $order->city }}@if($order->nearby_landmark)<br>Near {{ $order->nearby_landmark }}@endif</p>
    <h2>Ordered products</h2>
    <table style="width:100%;border-collapse:collapse">
        <thead><tr><th style="padding:8px 0;text-align:left;border-bottom:2px solid #08033d">Product</th><th style="text-align:center;border-bottom:2px solid #08033d">Qty</th><th style="text-align:right;border-bottom:2px solid #08033d">Amount</th></tr></thead>
        <tbody>@foreach($order->items as $item)<tr><td style="padding:10px 0;border-bottom:1px solid #eee">{{ $item->product_name }}</td><td style="text-align:center;border-bottom:1px solid #eee">{{ $item->quantity }}</td><td style="text-align:right;border-bottom:1px solid #eee">₹{{ number_format((float) $item->amount, 2) }}</td></tr>@endforeach</tbody>
        <tfoot><tr><td style="padding-top:14px" colspan="2"><strong>Order total</strong></td><td style="padding-top:14px;text-align:right"><strong>₹{{ number_format((float) $order->total_amount, 2) }}</strong></td></tr></tfoot>
    </table>
</div>
</body>
</html>
