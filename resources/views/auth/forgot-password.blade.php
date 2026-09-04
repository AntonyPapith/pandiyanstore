<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Forgot password | Pandiyan Store</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body class="login-page">
    <div class="login-card"><a href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a>
        <h1>Forgot password</h1>
        <p>Enter your User ID email. We will send an OTP to that email.</p>@if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif<form method="POST" action="{{ route('password.otp.send') }}">@csrf<label>User ID (Email ID)<input type="email" name="email" value="{{ old('email') }}" required autofocus></label><button class="primary-btn" type="submit">Send OTP</button></form><a class="back-home" href="{{ route('login') }}">&larr; Back to login</a>
    </div>
</body>

</html>