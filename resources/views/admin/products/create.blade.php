@extends('layouts.admin')
@section('title', 'Add Product')
@section('heading', 'Add product')
@section('body-class', 'product-create-admin')
@section('content')
<div class="page-heading product-create-heading"><div><h1>Add Product</h1><p>Add one or more products to a category.</p></div></div>
@if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
<form class="panel admin-form product-rows-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.products.store') }}">@csrf
<div class="product-fixed-fields"><label>Category<select name="category_id" required><option value="">Select category</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>@endforeach</select></label><label>Product name<input type="text" name="name" maxlength="120" value="{{ old('name') }}" required></label></div>
<div id="productRows"></div>
<div class="product-row-add"><button class="primary-btn" id="addProductRow" type="button">+ Add row</button></div>
<div class="form-actions"><button class="primary-btn" type="submit">Create products</button><a href="{{ route('admin.products.index') }}">Cancel</a></div></form>
<template id="productRowTemplate"><fieldset class="product-entry"><div class="product-entry-head"><strong>Product <span class="product-row-number"></span></strong><button class="remove-product-row" type="button">Remove</button></div><div class="product-entry-grid">
<label>Product images<input type="file" data-name="images" accept="image/jpeg,image/png,image/webp" multiple required></label>
<label>Quantity<input type="number" data-name="quantity" min="0"></label>
<label>Color<input type="text" data-name="color" maxlength="80"></label>
<label>Size<input type="text" data-name="size" maxlength="80"></label>
<label>Price<input type="number" data-name="price" min="0" step="0.01"></label>
<label>Discount price<input type="number" data-name="discount_price" min="0" step="0.01"></label>
<label class="product-cod-field"><span>Cash on delivery</span><span class="admin-check"><input type="hidden" data-name="cod_available" value="0"><input type="checkbox" data-name="cod_available" value="1"> Available</span></label>
<label class="product-description-field">Description<textarea data-name="description" maxlength="2000"></textarea></label>
</div></fieldset></template>
<script src="{{ asset('js/product-rows.js') }}?v={{ filemtime(public_path('js/product-rows.js')) }}"></script>
@endsection
