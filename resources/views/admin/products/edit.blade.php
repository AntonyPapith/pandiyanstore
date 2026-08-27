@extends('layouts.admin')
@section('title', 'Edit Product')
@section('heading', 'Edit product')
@section('content')
<div class="page-heading"><div><h1>Edit Product</h1><p>Update this product's information, price and stock.</p></div></div>
<form class="panel admin-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.products.update', $product) }}">@csrf @method('PUT')
<label>Category<select name="category_id" required><option value="">Select category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select>@error('category_id')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Product name<input type="text" name="name" maxlength="120" value="{{ old('name', $product->name) }}" required>@error('name')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Replace product image <small>(optional)</small><img class="table-thumb" src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}"><input type="file" name="image" accept="image/jpeg,image/png,image/webp"><small>Leave empty to retain the existing image.</small>@error('image')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Quantity<input type="number" name="quantity" min="0" value="{{ old('quantity', $product->quantity) }}">@error('quantity')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Color<input type="text" name="color" maxlength="80" value="{{ old('color', $product->color) }}">@error('color')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Size<input type="text" name="size" maxlength="80" value="{{ old('size', $product->size) }}">@error('size')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Price<input type="number" name="price" min="0" step="0.01" value="{{ old('price', $product->price) }}">@error('price')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Discount price<input type="number" name="discount_price" min="0" step="0.01" value="{{ old('discount_price', $product->discount_price !== null ? max(0, (float) $product->price - (float) $product->discount_price) : null) }}">@error('discount_price')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Description<textarea name="description" maxlength="2000">{{ old('description', $product->description) }}</textarea>@error('description')<small class="field-error">{{ $message }}</small>@enderror</label>
<div class="form-actions"><button class="primary-btn" type="submit">Update product</button><a href="{{ route('admin.products.index') }}">Cancel</a></div></form>
@endsection
