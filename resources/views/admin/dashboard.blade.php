@extends('layouts.admin')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('content')
<div class="page-heading"><div><h1>Welcome back</h1><p>Overview of your store, products, customers and orders.</p></div></div>
<div class="stat-grid dashboard-stat-grid">
    <a class="stat-card" href="{{ route('admin.categories.index') }}"><span>Categories</span><strong>{{ $categoryCount }}</strong></a>
    <a class="stat-card" href="{{ route('admin.products.index') }}"><span>Products</span><strong>{{ $productCount }}</strong></a>
    <a class="stat-card" href="{{ route('admin.orders.index') }}"><span>Orders</span><strong>{{ $orderCount }}</strong></a>
    <div class="stat-card"><span>Customers</span><strong>{{ $customerCount }}</strong></div>
    <a class="stat-card" href="{{ route('admin.orders.index') }}"><span>Total order value</span><strong class="stat-money">₹{{ number_format((float) $orderValue, 2) }}</strong></a>
    <a class="stat-card" href="{{ route('admin.orders.index') }}"><span>Payments to verify</span><strong>{{ $pendingPaymentCount }}</strong></a>
    <div class="stat-card"><span>Out of stock</span><strong>{{ $outOfStockCount }}</strong></div>
</div>
<div class="dashboard-panels">
    <section class="panel">
        <div class="panel-head"><h2>Recent orders</h2><a class="primary-btn" href="{{ route('admin.orders.index') }}">View orders</a></div>
        @forelse($recentOrders as $order)
            <a class="recent-item dashboard-order" href="{{ route('admin.orders.show', $order) }}"><div><strong>{{ $order->order_number }} · {{ $order->customer_name }}</strong><small>{{ $order->items_count }} item(s) · {{ strtoupper($order->payment_method) }} · {{ str_replace('_', ' ', $order->payment_status) }}</small></div><strong>₹{{ number_format((float) $order->total_amount, 2) }}</strong></a>
        @empty<p class="empty">No customer orders yet.</p>@endforelse
    </section>
    <section class="panel">
        <div class="panel-head"><h2>Recent categories</h2><a class="primary-btn" href="{{ route('admin.categories.create') }}">+ Add category</a></div>
        @forelse($recentCategories as $category)
            <div class="recent-item"><img src="{{ Storage::url($category->image_path) }}" alt=""><div><strong>{{ $category->name }}</strong><small>{{ $category->products_count }} product(s) · {{ $category->created_at->format('d M Y') }}</small></div></div>
        @empty<p class="empty">No categories added yet.</p>@endforelse
        <div class="dashboard-health"><span><strong>{{ $outOfStockCount }}</strong> out of stock</span><span><strong>{{ $codUnavailableCount }}</strong> without COD</span></div>
    </section>
</div>
@endsection
