@extends('layouts.admin')
@section('title', 'Admin Details')
@section('heading', 'Admin details')
@section('content')
<div class="page-heading"><div><h1>Admin Details</h1><p>Edit the administrator account information.</p></div></div>
<form class="panel admin-form" method="POST" action="{{ route('admin.profile.update') }}">@csrf @method('PATCH')
<label>Name<input type="text" name="name" value="{{ old('name', $admin->name) }}" required>@error('name')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Email ID<input type="email" name="email" value="{{ old('email', $admin->email) }}" required>@error('email')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Phone number <small>(optional)</small><input type="tel" name="phone" value="{{ old('phone', $admin->phone) }}">@error('phone')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>New password <small>(leave empty to keep the current password)</small><input type="password" name="password" autocomplete="new-password">@error('password')<small class="field-error">{{ $message }}</small>@enderror</label>
<label>Confirm new password<input type="password" name="password_confirmation" autocomplete="new-password"></label>
<div class="form-actions"><button class="primary-btn" type="submit">Save admin details</button><a href="{{ route('admin.dashboard') }}">Cancel</a></div>
</form>
@endsection
