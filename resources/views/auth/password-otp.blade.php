<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Verify OTP | Pandiyan Store</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body class="login-page">
    <div class="login-card"><a href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a>
        <h1>Verify OTP</h1>
        <p>Enter the six-digit OTP sent to your email.</p>@if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif @if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif<form method="POST" action="{{ route('password.otp.verify') }}">@csrf<label>User ID (Email ID)<input type="email" value="{{ $email }}" readonly></label><label>OTP<input type="text" name="otp" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required autofocus></label><button class="primary-btn" type="submit">Verify OTP</button></form>
    </div>
</body>

</html>