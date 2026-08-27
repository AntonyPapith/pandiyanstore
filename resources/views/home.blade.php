<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#FDCCE6">
<title>Pandiyan Store | Shop Our Products</title>
<link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
<link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/seyon.css') }}?v={{ filemtime(public_path('css/seyon.css')) }}">
</head>
<body class="home-page">

<div class="bg-glow" aria-hidden="true"></div>

<!-- ============ CAROUSEL / HOME VIEW ============ -->
<main class="hero" id="carouselView">

  <header class="topbar">
    <button class="icon-btn menu-btn" id="menuBtn" type="button" aria-label="Open categories" aria-controls="categorySidebar" aria-expanded="false">
      <svg viewBox="0 0 24 24" fill="none"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    </button>
    <div class="top-actions">
      <a class="icon-btn phone-btn" href="{{ auth()->check() ? (auth()->user()->is_admin ? route('admin.dashboard') : route('customer.account')) : route('login') }}" aria-label="Account"><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"/><path d="M4 21c.8-5 3.5-7 8-7s7.2 2 8 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></a>
      <a class="icon-btn phone-btn cart-icon" href="{{ route('cart.index') }}" aria-label="Cart"><svg viewBox="0 0 24 24" fill="none"><path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 1.9-1.4L21 7H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="20" r="1.3" fill="currentColor"/><circle cx="18" cy="20" r="1.3" fill="currentColor"/></svg><span>{{ count(array_filter(session('cart', []), fn ($quantity) => $quantity > 0)) }}</span></a>
    </div>
  </header>

  <div class="brand">
    <img class="brand-logo" src="{{ asset('logo.png') }}" alt="Pandiyan Store">
    <!-- <p class="tagline">Our Ideas. Your Story. Real Impact.</p> -->
  </div>
  <form class="home-search" action="{{ route('search') }}" method="GET" role="search"><input type="search" name="q" value="{{ request('q') }}" placeholder="Search products" aria-label="Search products" required><button type="submit" aria-label="Search"><svg viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="6.5" stroke="currentColor" stroke-width="2"/><path d="m16 16 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button></form>

  <div class="fan-wrap">
    <div class="fan-stage" id="fanStage"></div>

  </div>

  <div class="hero-foot">
    <!-- <p class="hero-copy">Showcasing elegance, luxury and emotions<br>through cinematic storytelling.</p> -->
    <p class="active-description" id="activeDescription" aria-live="polite"></p>
    <p class="connect-label">Connect with Pandiyan Store</p>
    <div class="socials">
      <a class="social-btn" href="https://wa.me/916383842171" target="_blank" rel="noopener" aria-label="WhatsApp"><img src="{{ asset('logo/whatsapp.png') }}" alt=""></a>
      <a class="social-btn" href="https://www.instagram.com/pandiyanstoreapk?igsh=MTlzYzV4eHRzY2w4dQ==" target="_blank" rel="noopener" aria-label="Instagram"><img src="{{ asset('logo/instagram.png') }}" alt=""></a>
      <a class="social-btn" href="#" aria-label="Facebook"><img src="{{ asset('logo/facebook.png') }}" alt=""></a>
      <a class="social-btn" href="https://youtube.com/@pandiyanstoreapk?si=8pdU2IWji2rPGA5H" target="_blank" rel="noopener" aria-label="YouTube"><img src="{{ asset('logo/youtube.png') }}" alt=""></a>
      <!-- <a class="social-btn" href="#" aria-label="LinkedIn"><img src="{{ asset('logo/linkedin.png') }}" alt=""></a> -->
    </div>
  </div>
</main>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<aside class="category-sidebar" id="categorySidebar" aria-hidden="true">
  <div class="sidebar-head"><h2>Categories</h2><button id="closeSidebar" type="button" aria-label="Close categories">&times;</button></div>
  <nav class="sidebar-list" id="sidebarList"></nav>
  @auth
    @if(auth()->user()->is_admin)
      <a class="sidebar-add" href="{{ route('admin.dashboard') }}" hidden>Open admin panel</a>
      <a class="sidebar-add sidebar-action-link" href="{{ route('admin.categories.create') }}" hidden>+ Add category</a>
      <a class="sidebar-add sidebar-video-add" href="{{ route('admin.products.create') }}" hidden>+ Add product</a>
    @else
      <div class="sidebar-user" hidden><strong>{{ auth()->user()->name }}</strong><span>{{ auth()->user()->email }}</span><span>{{ auth()->user()->phone }}</span></div>
      <a class="sidebar-add" href="{{ route('customer.account') }}" hidden>My account</a>
      <a class="sidebar-add sidebar-action-link" href="{{ route('cart.index') }}" hidden>View cart</a>
    @endif
  @endauth
  <a class="sidebar-add sidebar-action-link" href="{{ route('contact') }}">Contact us</a>
</aside>

<!-- Content created from add.html is displayed here. -->
<!-- <section class="projects-section" id="projectsSection" aria-labelledby="projectsTitle">
  <div class="projects-heading">
    <div>
      <p class="section-kicker">Latest work</p>
      <h2 id="projectsTitle">Our Stories</h2>
    </div>
    <a class="add-content-link" href="{{ route('admin.categories.create') }}">+ Add category</a>
  </div>
  <div class="projects-grid" id="projectsGrid" aria-live="polite"></div>
  <p class="projects-empty" id="projectsEmpty">No content has been added yet.</p>
</section> -->

<!-- ============ REEL / VIDEO FEED VIEW ============ -->
<section class="reel-view" id="reelView" aria-hidden="true">
  <header class="reel-topbar">
    <button class="reel-back" id="closeReel">
      <svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <span>Back</span>
    </button>
    <div class="reel-heading">
      <span class="reel-icon" id="reelIcon"></span>
      <span class="reel-title" id="reelCategoryLabel">Category</span>
    </div>
    <span class="reel-count" id="reelCount"></span>
  </header>

  <div class="reel-scroll" id="reelScroll"></div>
</section>

<script>
window.PANDIAN_CATEGORIES = {{ Illuminate\Support\Js::from($categories->map(fn ($category) => [
  'id' => $category->id,
  'title' => $category->name,
  'description' => $category->description ?? '',
  'color' => $category->color,
  'image' => Storage::url($category->image_path),
])) }};
</script>
<script src="{{ asset('js/seyon.js') }}?v={{ filemtime(public_path('js/seyon.js')) }}"></script>
</body>
</html>
