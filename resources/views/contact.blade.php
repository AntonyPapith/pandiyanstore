<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#FDCCE6">
    <title>Contact Us | Pandiyan Store</title>
    <link rel="icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact-address.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact-type.css') }}">
</head>
<body><main class="contact-shell">
    <a class="contact-logo" href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Pandiyan Store"></a>
    <header><h1>Get in touch</h1><p>We are happy to help with products, orders and delivery questions.</p></header>
    <section class="contact-card"><h2>Customer service</h2>
        @if($admin?->phone)<a href="tel:{{ preg_replace('/\s+/', '', $admin->phone) }}"><strong>Phone number</strong><span>{{ $admin->phone }}</span></a>@endif
        @if($admin?->email)<a href="mailto:{{ $admin->email }}"><strong>Email ID</strong><span>{{ $admin->email }}</span></a>@endif
        <div class="contact-address"><strong>Pandiyan Store</strong><span>252 A, City Pazzer<br>Aruppukottai<br>Virudhunagar District – 626101</span></div>
    </section>
    <section class="contact-card location-card"><h2>Our location</h2><div class="contact-map"><iframe title="Pandiyan Store location" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3934.992619659084!2d78.0932165!3d9.5093699!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b01310046cd47f7%3A0x34a611d956fbf46d!2sPandian%20Store!5e0!3m2!1sen!2sin!4v1787398883478!5m2!1sen!2sin" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe></div><a class="map-link" href="https://www.google.com/maps/search/?api=1&query=Pandian%20Store%20Aruppukottai" target="_blank" rel="noopener">Open in Google Maps</a></section>
    <section class="contact-card"><h2>Connect with Pandiyan Store</h2><div class="contact-socials">
        @foreach($socials as $name => $url)<a href="{{ $url }}" target="_blank" rel="noopener"><img src="{{ asset('logo/'.$name.'.png') }}" alt=""><span>{{ ucfirst($name) }}</span></a>@endforeach
    </div></section>
    <a class="contact-back" href="{{ route('home') }}">&larr; Back to store</a>
</main></body></html>
