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
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}?v={{ filemtime(public_path('css/admin-sidebar.css')) }}">
</head>
<body class="@yield('body-class')">
<div class="admin-shell">
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- <button class="admin-sidebar-close" id="adminSidebarClose" type="button" aria-label="Close admin menu">&times;</button> -->
        <a class="admin-logo" href="{{ route('admin.dashboard') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"><strong class="admin-logo-mark" aria-hidden="true">PS</strong></a>
        <nav>
            <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><svg viewBox="0 0 24 24" fill="none"><path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" stroke="currentColor" stroke-width="1.8"/></svg><span>Dashboard</span></a>
            <a class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><svg viewBox="0 0 24 24" fill="none"><path d="M4 5h7v6H4zM13 5h7v6h-7zM4 13h7v6H4zM13 13h7v6h-7z" stroke="currentColor" stroke-width="1.8"/></svg><span>Categories</span></a>
            <a class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><svg viewBox="0 0 24 24" fill="none"><path d="M4 8.5 12 4l8 4.5v9L12 22l-8-4.5v-9Z" stroke="currentColor" stroke-width="1.8"/><path d="m4 8.5 8 4.5 8-4.5M12 13v9" stroke="currentColor" stroke-width="1.8"/></svg><span>Products</span></a>
            <a class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M4 21c.8-5 3.5-7 8-7s7.2 2 8 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><span>Admin Details</span></a>
            <a class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><svg viewBox="0 0 24 24" fill="none"><path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z" stroke="currentColor" stroke-width="1.8"/><path d="M9 8h6M9 12h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><span>Order Details</span></a>
            <a href="{{ route('home') }}" target="_blank"><svg viewBox="0 0 24 24" fill="none"><path d="m3 11 9-7 9 7M5 10v10h14V10M9 20v-6h6v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg><span>View website</span></a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="logout-btn" type="submit"><svg viewBox="0 0 24 24" fill="none"><path d="M10 5H5v14h5M14 8l4 4-4 4M8 12h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg><span>Log out</span></button></form>
    </aside>
    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop" aria-hidden="true"></div>
    <main class="admin-main">
        <header class="admin-topbar"><button type="button" id="sidebarToggle" aria-label="Toggle menu"><svg viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button><details class="admin-account-menu"><summary aria-label="Open admin account menu"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M4 21c.8-5 3.5-7 8-7s7.2 2 8 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></summary><div class="admin-account-dropdown"><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->email }}</span><a href="{{ route('admin.profile.edit') }}">Admin details</a><form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit">Log out</button></form></div></details></header>
        <section class="admin-content">
            @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
            @yield('content')
        </section>
    </main>
</div>
<script src="{{ asset('js/admin-sidebar.js') }}?v={{ filemtime(public_path('js/admin-sidebar.js')) }}"></script>
</body>
</html>
