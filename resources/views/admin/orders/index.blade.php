@extends('layouts.admin')
@section('title', 'Orders')
@section('heading', 'Orders')
@section('content')
<div class="page-heading"><div><h1>Customer Orders</h1><p>View customers, payments, and ordered products.</p></div></div><div class="panel table-panel"><table><thead><tr><th>S.No</th><th>Order</th><th>Customer</th><th>Items</th><th>Payment</th><th>Total</th><th></th></tr></thead><tbody>@forelse($orders as $order)<tr><td class="serial-cell">{{ $orders->firstItem() + $loop->index }}</td><td><strong>{{ $order->order_number }}</strong><small>{{ $order->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</small></td><td>{{ $order->customer_name }}<small>{{ $order->customer_phone }}</small></td><td>{{ $order->items_count }}</td><td>{{ strtoupper($order->payment_method) }}</td><td>₹{{ number_format((float)$order->total_amount,2) }}</td><td class="actions"><a href="{{ route('admin.orders.show',$order) }}">View</a></td></tr>@empty<tr><td colspan="7" class="empty">No orders placed yet.</td></tr>@endforelse</tbody></table></div>{{ $orders->links() }}
@endsection
