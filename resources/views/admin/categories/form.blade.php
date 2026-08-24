@extends('layouts.admin')
@php($editing = isset($category))
@section('title', $editing ? 'Edit Category' : 'Add Category')
@section('heading', $editing ? 'Edit category' : 'Add category')
@section('content')
<div class="page-heading"><div><h1>{{ $editing ? 'Edit Category' : 'Add New Category' }}</h1><p>Add categories such as Toys or Glass Toys to the homepage carousel.</p></div></div>
<form class="panel admin-form" method="POST" enctype="multipart/form-data" action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
@csrf @if($editing) @method('PUT') @endif
<label>Category image<input type="file" name="image" accept="image/jpeg,image/png,image/webp" {{ $editing ? '' : 'required' }}>@error('image')<small class="field-error">{{ $message }}</small>@enderror</label>
@if($editing)<img class="form-preview" src="{{ Storage::url($category->image_path) }}" alt="Current category image">@endif
<label>Category name<input type="text" name="name" maxlength="80" placeholder="Example: Toys" value="{{ old('name', $category->name ?? '') }}" required>@error('name')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Description <small>(optional)</small><textarea name="description" maxlength="500">{{ old('description', $category->description ?? '') }}</textarea>@error('description')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Moving line color<input type="color" name="color" value="{{ old('color', $category->color ?? '#08033D') }}" required><small>This color appears on the animated line of the category card.</small>@error('color')<small class="field-error">{{ $message }}</small>@enderror</label>
<div class="form-actions"><button class="primary-btn" type="submit">{{ $editing ? 'Update category' : 'Create category' }}</button><a href="{{ route('admin.categories.index') }}">Cancel</a></div>
</form>
@endsection
