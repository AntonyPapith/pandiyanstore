@extends('layouts.admin')
@section('title', 'Products')
@section('heading', 'Products')
@section('content')
<div class="page-heading"><div><h1>Products</h1><p>Manage the products shown inside each category.</p></div><a class="primary-btn" href="{{ route('admin.products.create') }}">+ Add product</a></div>
<div class="panel table-panel"><table><thead><tr><th>Image</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Action</th></tr></thead><tbody>
@forelse($products as $product)<tr><td><img class="table-thumb" src="{{ Storage::url($product->image_path) }}" alt=""></td><td><strong>{{ $product->name }}</strong></td><td>{{ $product->category?->name ?? 'Category unavailable' }}</td><td>₹{{ number_format((float) ($product->discount_price ?? $product->price), 2) }}</td><td>{{ $product->quantity }}</td><td class="actions"><a href="{{ route('admin.products.edit', $product) }}">Edit</a><form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>
@empty<tr><td colspan="6" class="empty">No products added yet.</td></tr>@endforelse
</tbody></table></div>{{ $products->links() }}
@endsection
