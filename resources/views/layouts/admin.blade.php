<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#FDCCE6">
    <title>@yield('title', 'Admin') | Pandiyan Store</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar" id="adminSidebar">
        <button class="admin-sidebar-close" id="adminSidebarClose" type="button" aria-label="Close admin menu">&times;</button>
        <a class="admin-logo" href="{{ route('admin.dashboard') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a>
        <nav>
            <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Categories</a>
            <a class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">Products</a>
            <a class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}">Admin Details</a>
            <a class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">Order Details</a>
            <a href="{{ route('home') }}" target="_blank">View website</a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="logout-btn" type="submit">Log out</button></form>
    </aside>
    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop" aria-hidden="true"></div>
    <main class="admin-main">
        <header class="admin-topbar"><button type="button" id="sidebarToggle" aria-label="Toggle menu">☰</button><div><strong>@yield('heading', 'Admin panel')</strong><span>{{ auth()->user()->email }}</span></div></header>
        <section class="admin-content">
            @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
            @yield('content')
        </section>
    </main>
</div>
<script src="{{ asset('js/admin-sidebar.js') }}"></script>
</body>
</html>
