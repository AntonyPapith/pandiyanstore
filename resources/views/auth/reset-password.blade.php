<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>New password | Pandiyan Store</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body class="login-page">
    <div class="login-card"><a href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a>
        <h1>Create new password</h1>
        <p>Choose a new password for your account.</p>@if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif<form method="POST" action="{{ route('password.reset.save') }}">@csrf<label>User ID (Email ID)<input type="email" value="{{ $email }}" readonly></label><label>New password<input type="password" name="password" autocomplete="new-password" required></label><label>Confirm new password<input type="password" name="password_confirmation" autocomplete="new-password" required></label><button class="primary-btn" type="submit">Save new password</button></form>
    </div>
</body>

</html>