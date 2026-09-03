@extends('layouts.admin')
@section('title', 'Products')
@section('heading', 'Products')
@section('content')
<div class="page-heading"><div><h1>Products</h1><p>Manage the products shown inside each category.</p></div><a class="primary-btn" href="{{ route('admin.products.create') }}">+ Add product</a></div>
<form class="admin-filter" id="productFilter" method="GET" action="{{ route('admin.products.index') }}" autocomplete="off"><label>Product name<input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search product name"></label><label>Category<select name="category_id"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select></label><a class="filter-reset" id="clearProductFilter" href="{{ route('admin.products.index') }}" @if(($filters['q'] ?? '') === '' && ($filters['category_id'] ?? '') === '') hidden @endif>Clear</a></form>
<div id="productResults" aria-live="polite">
<div class="panel table-panel"><table><thead><tr><th>S.No</th><th>Image</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Action</th></tr></thead><tbody>
@forelse($products as $product)<tr><td class="serial-cell">{{ $products->firstItem() + $loop->index }}</td><td><img class="table-thumb" src="{{ Storage::url($product->image_path) }}" alt=""></td><td><strong>{{ $product->name }}</strong></td><td>{{ $product->category?->name ?? 'Category unavailable' }}</td><td>₹{{ number_format((float) ($product->discount_price ?? $product->price), 2) }}</td><td>{{ $product->quantity }}</td><td class="actions"><a href="{{ route('admin.products.edit', $product) }}">Edit</a><form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>
@empty<tr><td colspan="7" class="empty">No products match this filter.</td></tr>@endforelse
</tbody></table></div>{{ $products->links() }}
</div>
<script src="{{ asset('js/admin-product-filter.js') }}?v={{ filemtime(public_path('js/admin-product-filter.js')) }}"></script>
@endsection
