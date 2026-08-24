@extends('layouts.admin')
@section('title', 'Categories')
@section('heading', 'Categories')
@section('content')
<div class="page-heading"><div><h1>Categories</h1><p>Create and manage homepage product-category cards.</p></div><a class="primary-btn" href="{{ route('admin.categories.create') }}">+ Add category</a></div>
<div class="panel table-panel"><table><thead><tr><th>Image</th><th>Category</th><th>Products</th><th>Actions</th></tr></thead><tbody>
@forelse($categories as $category)<tr><td><img class="table-thumb" src="{{ Storage::url($category->image_path) }}" alt=""></td><td><strong>{{ $category->name }}</strong><small>{{ Str::limit($category->description, 65) }}</small></td><td>{{ $category->products_count }}</td><td class="actions"><a href="{{ route('admin.categories.edit', $category) }}">Edit</a><form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category and all its products?')">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>
@empty<tr><td colspan="4" class="empty">No categories added yet.</td></tr>@endforelse
</tbody></table></div>{{ $categories->links() }}
@endsection
