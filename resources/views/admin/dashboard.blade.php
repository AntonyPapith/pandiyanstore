@extends('layouts.admin')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('content')
<div class="page-heading"><div><h1>Welcome back</h1><p>Manage the content displayed on your website.</p></div></div>
<div class="stat-grid">
    <a class="stat-card" href="{{ route('admin.categories.index') }}"><span>Categories</span><strong>{{ $categoryCount }}</strong></a>
    <a class="stat-card" href="{{ route('admin.products.index') }}"><span>Products</span><strong>{{ $productCount }}</strong></a>
</div>
<div class="panel">
    <div class="panel-head"><h2>Recent categories</h2><a class="primary-btn" href="{{ route('admin.categories.create') }}">+ Add category</a></div>
    @forelse($recentCategories as $category)
        <div class="recent-item"><img src="{{ Storage::url($category->image_path) }}" alt=""><div><strong>{{ $category->name }}</strong><small>{{ $category->created_at->format('d M Y') }}</small></div></div>
    @empty<p class="empty">No categories added yet.</p>@endforelse
</div>
@endsection
