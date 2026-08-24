<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Register | Pandiyan Store</title><link rel="icon" type="image/png" href="{{ asset('logo.png') }}"><link rel="stylesheet" href="{{ asset('css/admin.css') }}"></head>
<body class="login-page"><div class="login-card"><a href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a><h1>Create account</h1><p>Register to place your order.</p>
@if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('customer.store') }}">@csrf
<label>Name<input type="text" name="name" value="{{ old('name') }}" autocomplete="name" required></label><label>Phone number<input type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel" required></label><label>Email ID<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></label>
<label>Password<div class="password-wrap"><input id="registerPassword" type="password" name="password" autocomplete="new-password" required><button class="password-eye" type="button" data-password="registerPassword" aria-label="Show password">◉</button></div></label>
<label>Confirm password<div class="password-wrap"><input id="confirmPassword" type="password" name="password_confirmation" autocomplete="new-password" required><button class="password-eye" type="button" data-password="confirmPassword" aria-label="Show password">◉</button></div></label>
<button class="primary-btn" type="submit">Register</button></form><p class="auth-switch">Already registered? <a href="{{ route('login') }}">Login</a></p></div>
<script src="{{ asset('js/password-toggle.js') }}"></script></body></html>
