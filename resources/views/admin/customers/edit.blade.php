@extends('layouts.admin')
@section('title', 'Edit user')
@section('content')
<div class="page-heading"><div><h1>Edit user</h1><p>Update customer contact details.</p></div></div><form class="panel admin-form" method="POST" action="{{ route('admin.customers.update', $customer) }}">@csrf @method('PATCH')<label>Name<input name="name" value="{{ old('name', $customer->name) }}" required></label><label>Phone<input name="phone" value="{{ old('phone', $customer->phone) }}"></label><button class="primary-btn">Save changes</button><a href="{{ route('admin.customers.show', $customer) }}">Cancel</a></form>
@endsection
