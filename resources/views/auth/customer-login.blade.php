<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Login | Pandiyan Store</title><link rel="icon" type="image/png" href="{{ asset('logo.png') }}"><link rel="stylesheet" href="{{ asset('css/admin.css') }}"></head>
<body class="login-page"><div class="login-card"><a href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a><h1>Welcome back</h1><p>Login to continue your order.</p>
@if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif @if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('login.store') }}">@csrf
<label>User ID (Email ID)<input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus></label>
<label>Password<div class="password-wrap"><input id="loginPassword" type="password" name="password" autocomplete="current-password" required><button class="password-eye" type="button" data-password="loginPassword" aria-label="Show password">◉</button></div></label>
<label class="remember"><input type="checkbox" name="remember" value="1"> Remember me</label><button class="primary-btn" type="submit">Login</button></form>
<p class="auth-switch">New customer? <a href="{{ route('customer.register') }}">Create account</a></p><a class="back-home" href="{{ route('home') }}">&larr; Back to store</a></div>
<script src="{{ asset('js/password-toggle.js') }}"></script></body></html>
