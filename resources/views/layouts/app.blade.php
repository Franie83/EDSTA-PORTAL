<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', ($settings['site_name'] ?? 'Edo Odyssey') . ' – Official Edo State Tourism Platform')</title>
  <meta name="description" content="{{ $settings['meta_description'] ?? 'Explore Edo State cultural heritage and tourism' }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/static/css/main.css">
  @yield('head')
  <style>
    :root {
      --gold-400: #e0b83a;
      --gold-500: #c9a227;
      --gold-600: #b08a1f;
      --blue-800: #0d2447;
      --blue-900: #0a1a33;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .main-navbar { box-shadow: 0 4px 20px rgba(0,0,0,0.1); background: linear-gradient(135deg, var(--blue-900) 0%, var(--blue-800) 100%); }
    .main-navbar .nav-link { position: relative; transition: var(--transition); padding: 0.5rem 1rem; border-radius: 8px; color: rgba(255,255,255,0.8); font-weight: 500; }
    .main-navbar .nav-link::after { content: ''; position: absolute; bottom: 0; left: 50%; width: 0; height: 2px; background: var(--gold-500); transition: var(--transition); transform: translateX(-50%); border-radius: 2px; }
    .main-navbar .nav-link:hover::after, .main-navbar .nav-link.active::after { width: 60%; }
    .main-navbar .nav-link:hover { background: rgba(201, 162, 39, 0.1); color: #fff; }
    .main-navbar .nav-link.active { color: var(--gold-400) !important; }
    
    .mega-dropdown { border: none; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); padding: 0.5rem; min-width: 220px; animation: slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .mega-dropdown .dropdown-item { border-radius: 8px; padding: 0.6rem 1rem; transition: var(--transition); font-weight: 500; }
    .mega-dropdown .dropdown-item:hover { background: rgba(26, 58, 107, 0.05); color: var(--blue-800); transform: translateX(4px); }
    .mega-dropdown .dropdown-item i { transition: var(--transition); }
    .mega-dropdown .dropdown-item:hover i { color: var(--gold-500) !important; transform: scale(1.1); }
    
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    
    #flash-container { position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 400px; width: 100%; }
    .flash-toast { border: none; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.15); padding: 1rem 1.25rem; animation: slideInRight 0.4s ease-out; backdrop-filter: blur(10px); background: rgba(255,255,255,0.95); }
    .flash-toast .btn-close { font-size: 0.7rem; }
    .flash-toast i { font-size: 1.2rem; }
    @keyframes slideInRight { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: translateX(0); } }
    
    .footer-links a { transition: var(--transition); display: inline-block; }
    .footer-links a:hover { color: var(--gold-400) !important; transform: translateX(4px); }
    .site-footer .text-gold { transition: var(--transition); }
    .site-footer .text-gold:hover { transform: translateY(-2px); }
    
    .search-form .form-control { border-radius: 20px 0 0 20px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1); color: #fff; transition: var(--transition); }
    .search-form .form-control:focus { background: rgba(255,255,255,0.2); border-color: var(--gold-500); box-shadow: none; }
    .search-form .form-control::placeholder { color: rgba(255,255,255,0.6); }
    .search-form .btn { border-radius: 0 20px 20px 0; }
    
    .avatar-initials-sm { width: 32px; height: 32px; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.75rem; background: var(--gold-500); color: #fff; }
    .dropdown-menu .dropdown-item { transition: var(--transition); border-radius: 8px; padding: 0.5rem 1rem; }
    .dropdown-menu .dropdown-item:hover { background: rgba(201, 162, 39, 0.1); transform: translateX(4px); }

    /* ENHANCED LOGO STYLING */
    .nav-logo-img {
      height: 50px;
      width: 50px;
      object-fit: contain;
      border-radius: 4px;
      background-color: #fff;
      padding: 3px;
      border: 2px solid var(--gold-500);
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
      cursor: pointer; /* Force pointer cursor */
    }

    .nav-logo-right {
      height: 50px !important;
      width: auto !important;
      max-width: 80px;
      object-fit: contain;
    }

    .nav-logo-img:hover, .nav-logo-right:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(201, 162, 39, 0.4);
      border-color: var(--gold-400);
    }

    /* Modal Styling */
    .modal-logo-img {
      max-height: 80vh;
      max-width: 100%;
      object-fit: contain;
      border-radius: 8px;
    }

    @media (max-width: 768px) {
      #flash-container { top: 70px; right: 10px; left: 10px; max-width: 100%; }
      .main-navbar .nav-link::after { display: none; }
      .mega-dropdown { border-radius: 8px; margin-top: 0.5rem; }
      .nav-logo-img, .nav-logo-right { height: 40px; width: 40px; }
    }
    
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: var(--gold-500); border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--gold-600); }
    /* ══════════ Global Background Image (from CMS hero_image) ══════════ */
    body {
        background-color: #f5f7fa;
        background-image: url('{{ \App\Helpers\Helpers::imageUrl(\App\Models\CmsSetting::get('hero_image', '')) }}');
        background-size: cover;
        background-position: center center;
        background-attachment: fixed;
        background-repeat: no-repeat;
    }
    /* Semi-transparent overlay so content stays readable */
    body.has-hero::before {
        content: "";
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(180deg, rgba(255,255,255,0.85) 0%, rgba(255,255,255,0.92) 100%);
        z-index: -1;
        pointer-events: none;
    }
    /* Exempt home page hero section — it has its own full-bleed background */
    body:has(.hero-section)::before {
        background: linear-gradient(180deg, rgba(255,255,255,0.85) 0%, rgba(255,255,255,0.92) 60%, rgba(255,255,255,0.95) 100%);
    }
    /* Admin panel unaffected — different layout */
    .admin-body {
        background-image: none !important;
    }
    /* ---------- DARK MODE ---------- */
    [data-theme="dark"] {
      --bs-body-bg: #0f172a;
      --bs-body-color: #cbd5e1;
      --bs-card-bg: #1e293b;
      --bs-border-color: #334155;
    }
    [data-theme="dark"] body {
      background-color: #0f172a !important;
      background-image: none !important; /* Remove the light background image */
      color: #cbd5e1 !important;
    }
    [data-theme="dark"] body.has-hero::before {
      display: none; /* Remove the white overlay */
    }
    [data-theme="dark"] .card, [data-theme="dark"] .mega-dropdown {
      background-color: #1e293b !important;
      color: #cbd5e1 !important;
      border-color: #334155 !important;
    }
    [data-theme="dark"] .text-dark, [data-theme="dark"] .text-muted, [data-theme="dark"] .text-blue {
      color: #94a3b8 !important;
    }
    [data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3, [data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6 {
      color: #f8fafc !important;
    }
    [data-theme="dark"] .bg-white, [data-theme="dark"] .bg-light, [data-theme="dark"] .bg-gray-50, [data-theme="dark"] .bg-blue-100 {
      background-color: #1e293b !important;
      color: #cbd5e1 !important;
    }
    [data-theme="dark"] .form-control, [data-theme="dark"] .form-select {
      background-color: #0f172a !important;
      color: #f8fafc !important;
      border-color: #334155 !important;
    }
    [data-theme="dark"] .hero-overlay {
      background: rgba(0, 0, 0, 0.7) !important;
    }
    /* Toggle Button Style */
    .theme-toggle-btn {
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      color: var(--gold-400);
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: var(--transition);
    }
    .theme-toggle-btn:hover {
      background: rgba(201, 162, 39, 0.2);
      transform: scale(1.05);
    }
  </style>
</head>
<body class="@yield('body_class')">

<!-- STICKY HEADER (Top Banner + Navbar) -->
<div class="sticky-top">

  <!-- TOP BANNER -->
  <div class="top-banner py-1 d-none d-md-block" style="background: #081628; border-bottom: 1px solid rgba(255,255,255,0.05);">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="d-flex gap-3 small text-white-50">
        <span><i class="bi bi-telephone-fill me-1 text-gold"></i>{{ $settings['contact_phone'] ?? '+234 (0) 52 255 000' }}</span>
        <span><i class="bi bi-envelope-fill me-1 text-gold"></i>{{ $settings['contact_email'] ?? 'info@edsta.edo.gov.ng' }}</span>
      </div>
      <div class="d-flex gap-3 small">
        @if(!empty($settings['social_facebook']))<a href="{{ $settings['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="text-white-50 transition-hover" title="Facebook"><i class="bi bi-facebook"></i></a>@endif
        @if(!empty($settings['social_twitter']))<a href="{{ $settings['social_twitter'] }}" target="_blank" rel="noopener noreferrer" class="text-white-50 transition-hover" title="Twitter-x"><i class="bi bi-twitter-x"></i></a>@endif
        @if(!empty($settings['social_instagram']))<a href="{{ $settings['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="text-white-50 transition-hover" title="Instagram"><i class="bi bi-instagram"></i></a>@endif
        @if(!empty($settings['social_youtube']))<a href="{{ $settings['social_youtube'] }}" target="_blank" rel="noopener noreferrer" class="text-white-50 transition-hover" title="Youtube"><i class="bi bi-youtube"></i></a>@endif
        @if(!empty($settings['social_linkedin']))<a href="{{ $settings['social_linkedin'] }}" target="_blank" rel="noopener noreferrer" class="text-white-50 transition-hover" title="LinkedIn"><i class="bi bi-linkedin"></i></a>@endif
        @if(!empty($settings['social_tiktok']))<a href="{{ $settings['social_tiktok'] }}" target="_blank" rel="noopener noreferrer" class="text-white-50 transition-hover" title="TikTok"><i class="bi bi-tiktok"></i></a>@endif
        @if(!empty($settings['social_whatsapp']))<a href="{{ $settings['social_whatsapp'] }}" target="_blank" rel="noopener noreferrer" class="text-white-50 transition-hover" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>@endif
      </div>
    </div>
  </div>

  <!-- MAIN NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark main-navbar py-2">
    <div class="container">
      
      <!-- LEFT LOGO -->
      <div class="navbar-brand d-flex align-items-center gap-3">
        @if(isset($settings['site_logo']) && $settings['site_logo'])
          <!-- Left Logo purely triggers modal -->
          <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="nav-logo-img" data-bs-toggle="modal" data-bs-target="#leftLogoModal" title="View Logo">
        @else
          <div class="brand-icon"><i class="bi bi-compass-fill"></i></div>
        @endif
        <a href="{{ route('main.home') }}" class="text-decoration-none">
          <div class="brand-title" style="letter-spacing: 0.5px; color: #fff;">{{ strtoupper($settings["site_name"] ?? "EDO ODYSSEY") }}</div>
          <div class="brand-subtitle text-gold" style="font-size: 0.65rem; letter-spacing: 1px;">OFFICIAL TOURISM PLATFORM</div>
        </a>
      </div>

      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav mx-auto gap-2">
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('main.home') ? 'active' : '' }}" href="{{ route('main.home') }}">Home</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Explore</a>
            <ul class="dropdown-menu mega-dropdown">
              <li><a class="dropdown-item" href="{{ route('attractions.list_attractions') }}"><i class="bi bi-building me-2 text-primary"></i>Attractions</a></li>
              <li><a class="dropdown-item" href="{{ route('guides.list_guides') }}"><i class="bi bi-person-badge me-2 text-primary"></i>Tour Guides</a></li>
              <li><a class="dropdown-item" href="{{ route('hotels.list_hotels') }}"><i class="bi bi-building-fill me-2 text-primary"></i>Hotels</a></li>
              <li><a class="dropdown-item" href="{{ route('restaurants.list_restaurants') }}"><i class="bi bi-cup-hot me-2 text-primary"></i>Restaurants</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.list_events') }}">Events</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.list_news') }}">News</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('main.about') }}">About</a></li>
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('main.staff') ? 'active' : '' }}" href="{{ route('main.staff') }}">Our Team</a></li>
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('main.culture') ? 'active' : '' }}" href="{{ route('main.culture') }}">Culture</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('main.contact') }}">Contact</a></li>
        </ul>

        <form class="d-flex me-3 search-form" action="{{ route('search.results') }}" method="get">
          <div class="input-group input-group-sm">
            <input class="form-control nav-search" type="search" name="q" placeholder="Search Edo..." value="{{ request('q') }}">
            <button class="btn btn-gold btn-sm" type="submit"><i class="bi bi-search"></i></button>
          </div>
        </form>

        <!-- User Dropdown & Right Logo Container -->
        <div class="d-flex align-items-center gap-3">
          <button class="theme-toggle-btn me-2" id="themeToggle" title="Toggle Dark Mode">
            <i class="bi bi-moon-fill" id="themeIcon"></i>
          </button>
            @auth
            <div class="dropdown">
              <button class="btn btn-outline-gold dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" style="border-radius: 20px;">
                @if(Auth::user()->avatar_url)
                  <img src="{{ \App\Helpers\Helpers::imageUrl(Auth::user()->avatar_url) }}" class="rounded-circle" width="28" height="28" alt="">
                @else
                  <div class="avatar-initials-sm">{{ Auth::user()->first_name[0] }}{{ Auth::user()->last_name[0] }}</div>
                @endif
                <span class="d-none d-lg-inline fw-semibold">{{ Auth::user()->first_name }}</span>
                @if(isset($unread_notifs) && $unread_notifs > 0)
                  <span class="badge bg-danger rounded-pill">{{ $unread_notifs }}</span>
                @endif
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 12px;">
                <li class="dropdown-header">{{ Auth::user()->full_name }}<br><small class="text-muted">{{ Auth::user()->role }}</small></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.index') }}"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard</a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard.profile') }}"><i class="bi bi-person me-2 text-primary"></i>Profile</a></li>
                @if(Auth::user()->is_admin)
                <li><a class="dropdown-item text-primary fw-semibold" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-check me-2"></i>Admin Panel</a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="{{ route('auth.logout') }}"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
              </ul>
            </div>
            @else
            <div class="d-flex gap-2 align-items-center">
              <button class="theme-toggle-btn me-2" id="themeToggle" title="Toggle Dark Mode">
                <i class="bi bi-moon-fill" id="themeIcon"></i>
              </button>
              <a href="{{ route('auth.login') }}" class="btn btn-outline-gold btn-sm px-3" style="border-radius: 20px;">Login</a>
              <a href="{{ route('auth.register') }}" class="btn btn-gold btn-sm px-3" style="border-radius: 20px;">Register</a>
            </div>
            @endauth

            <!-- Right Logo (Modal Trigger) -->
            @if(isset($settings['site_logo_right']) && $settings['site_logo_right'])
                <div class="d-none d-lg-block">
                    <img src="{{ asset('storage/' . $settings['site_logo_right']) }}" alt="Right Logo" class="nav-logo-right" style="border-radius: 4px !important; width: auto !important; height: 50px !important; object-fit: contain;" style="border-radius: 4px !important;" data-bs-toggle="modal" data-bs-target="#rightLogoModal" title="View Coat of Arms">
                </div>
            @endif
        </div>

      </div>
    </div>
  </nav>
</div>
<!-- END STICKY HEADER -->

<!-- FLASH MESSAGES -->
<div id="flash-container">
  @foreach(['success','danger','warning','info'] as $type)
    @if(session($type))
    <div class="flash-toast alert alert-{{ $type }} alert-dismissible fade show mb-2" role="alert">
      <div class="d-flex align-items-center gap-2">
        <i class="bi bi-{{ $type=='success' ? 'check-circle-fill text-success' : ($type=='danger' ? 'x-circle-fill text-danger' : ($type=='warning' ? 'exclamation-triangle-fill text-warning' : 'info-circle-fill text-info')) }}"></i>
        <div class="flex-grow-1 small fw-semibold">{{ session($type) }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
    @endif
  @endforeach
</div>

<!-- PAGE CONTENT -->
@yield('content')

<!-- FOOTER -->
<footer class="site-footer mt-5">
  <div class="footer-top py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            @if(isset($settings['site_logo']) && $settings['site_logo'])
              <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="nav-logo-img" style="border-radius: 4px !important;" data-bs-toggle="modal" data-bs-target="#leftLogoModal">
            @else
              <div class="brand-icon"><i class="bi bi-compass-fill"></i></div>
            @endif
            <div>
              <div class="brand-title text-white">{{ strtoupper($settings["site_name"] ?? "EDO ODYSSEY") }}</div>
              <div class="brand-subtitle" style="font-size:0.65rem">Official Tourism Platform</div>
            </div>
          </div>
          <p class="text-white-50 small">{{ isset($settings['about_agency']) ? Str::limit($settings['about_agency'], 200) : 'Discover the rich cultural heritage of Edo State.' }}</p>
          <div class="d-flex gap-3 mt-3">
            @if(!empty($settings['social_facebook']))<a href="{{ $settings['social_facebook'] }}" target="_blank" rel="noopener noreferrer" class="text-gold fs-5 transition-hover" title="Facebook"><i class="bi bi-facebook"></i></a>@endif
            @if(!empty($settings['social_twitter']))<a href="{{ $settings['social_twitter'] }}" target="_blank" rel="noopener noreferrer" class="text-gold fs-5 transition-hover" title="Twitter-x"><i class="bi bi-twitter-x"></i></a>@endif
            @if(!empty($settings['social_instagram']))<a href="{{ $settings['social_instagram'] }}" target="_blank" rel="noopener noreferrer" class="text-gold fs-5 transition-hover" title="Instagram"><i class="bi bi-instagram"></i></a>@endif
            @if(!empty($settings['social_youtube']))<a href="{{ $settings['social_youtube'] }}" target="_blank" rel="noopener noreferrer" class="text-gold fs-5 transition-hover" title="Youtube"><i class="bi bi-youtube"></i></a>@endif
            @if(!empty($settings['social_linkedin']))<a href="{{ $settings['social_linkedin'] }}" target="_blank" rel="noopener noreferrer" class="text-gold fs-5 transition-hover" title="LinkedIn"><i class="bi bi-linkedin"></i></a>@endif
            @if(!empty($settings['social_tiktok']))<a href="{{ $settings['social_tiktok'] }}" target="_blank" rel="noopener noreferrer" class="text-gold fs-5 transition-hover" title="TikTok"><i class="bi bi-tiktok"></i></a>@endif
            @if(!empty($settings['social_whatsapp']))<a href="{{ $settings['social_whatsapp'] }}" target="_blank" rel="noopener noreferrer" class="text-gold fs-5 transition-hover" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>@endif
          </div>
        </div>
        <div class="col-lg-2 col-6">
          <h6 class="text-gold fw-bold mb-3 text-uppercase letter-spacing">Explore</h6>
          <ul class="list-unstyled footer-links">
            <li><a href="{{ route('attractions.list_attractions') }}">Attractions</a></li>
            <li><a href="{{ route('guides.list_guides') }}">Tour Guides</a></li>
            <li><a href="{{ route('hotels.list_hotels') }}">Hotels</a></li>
            <li><a href="{{ route('restaurants.list_restaurants') }}">Restaurants</a></li>
            <li><a href="{{ route('events.list_events') }}">Events</a></li>
          </ul>
        </div>
        <div class="col-lg-2 col-6">
          <h6 class="text-gold fw-bold mb-3 text-uppercase letter-spacing">EDSTA</h6>
          <ul class="list-unstyled footer-links">
            <li><a href="{{ route('main.about') }}">About EDSTA</a></li>
            <li><a href="{{ route('main.staff') }}">Our Team</a></li>
            <li><a href="{{ route('news.list_news') }}">Tourism News</a></li>
            <li><a href="{{ route('main.faq') }}">FAQs</a></li>
            <li><a href="{{ route('main.contact') }}">Contact</a></li>
            <li><a href="{{ route('main.privacy') }}">Privacy Policy</a></li>
          </ul>
        </div>
        <div class="col-lg-4">
          @if(!app()->isProduction())
          <h6 class="text-gold fw-bold mb-3 text-uppercase letter-spacing">Quick Login (Demo)</h6>
          <div class="d-flex flex-wrap gap-1 mb-3">
            <a href="{{ route('auth.quick_login', 'superadmin') }}" class="btn btn-outline-gold btn-sm"><i class="bi bi-shield-star me-1"></i>Super Admin</a>
            <a href="{{ route('auth.quick_login', 'tourist') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-person me-1"></i>Tourist</a>
            <a href="{{ route('auth.quick_login', 'guide') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-person-badge me-1"></i>Guide</a>
            <a href="{{ route('auth.quick_login', 'hotel') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-building me-1"></i>Hotel</a>
            <a href="{{ route('auth.quick_login', 'restaurant') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-cup-hot me-1"></i>Restaurant</a>
            <a href="{{ route('auth.quick_login', 'touroperator') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-bus-front me-1"></i>Tour Operator</a>
            <a href="{{ route('auth.quick_login', 'clublounge') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-music-note-beamed me-1"></i>Club / Lounge</a>
          </div>
          @endif
          <h6 class="text-gold fw-bold mb-2 text-uppercase letter-spacing" style="font-size:0.7rem">Contact</h6>
          <p class="text-white-50 small mb-1"><i class="bi bi-geo-alt me-1"></i>{{ $settings['contact_address'] ?? 'EDSTA HQ, Government House Road, GRA, Benin City' }}</p>
          <p class="text-white-50 small mb-1"><i class="bi bi-telephone me-1"></i>{{ $settings['contact_phone'] ?? '+234 (0) 52 255 000' }}</p>
          <p class="text-white-50 small"><i class="bi bi-envelope me-1"></i>{{ $settings['contact_email'] ?? 'info@edsta.edo.gov.ng' }}</p>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom py-2 text-center">
    <small class="text-white-50">{{ $settings['footer_text'] ?? '© 2026 Edo State Tourism Agency. All rights reserved.' }}</small>
  </div>
</footer>

<!-- LEFT LOGO MODAL -->
@if(isset($settings['site_logo']) && $settings['site_logo'])
<div class="modal fade" id="leftLogoModal" tabindex="-1" aria-labelledby="leftLogoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; background: #fff;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="leftLogoModalLabel" style="font-family: 'Playfair Display', serif; color: var(--blue-800);">Edo Odyssey Logo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-4">
        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Site Logo" class="modal-logo-img shadow-sm p-3" style="background: #f8f9fa; border-radius: 12px;">
      </div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        <button type="button" class="btn btn-gold px-4" data-bs-dismiss="modal" style="border-radius: 20px;">Close</button>
      </div>
    </div>
  </div>
</div>
@endif

<!-- RIGHT LOGO MODAL -->
@if(isset($settings['site_logo_right']) && $settings['site_logo_right'])
<div class="modal fade" id="rightLogoModal" tabindex="-1" aria-labelledby="rightLogoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; background: #fff;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="rightLogoModalLabel" style="font-family: 'Playfair Display', serif; color: var(--blue-800);">Official Seal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-4">
        <img src="{{ asset('storage/' . $settings['site_logo_right']) }}" alt="Coat of Arms" class="modal-logo-img shadow-sm p-3" style="background: #f8f9fa; border-radius: 12px;">
      </div>
      <div class="modal-footer border-0 justify-content-center pb-4">
        <button type="button" class="btn btn-gold px-4" data-bs-dismiss="modal" style="border-radius: 20px;">Close</button>
      </div>
    </div>
  </div>
</div>
@endif

<!-- BOOTSTRAP JS (Placed right before closing body to guarantee availability) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/static/js/main.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss flash messages
    const flashContainer = document.getElementById('flash-container');
    if (flashContainer) {
      const alerts = flashContainer.querySelectorAll('.flash-toast');
      alerts.forEach(function(alert, index) {
        setTimeout(function() {
          const bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        }, 5000 + (index * 500));
      });
    }
  });
</script>

@yield('scripts')
<script>
  // -- DARK MODE LOGIC --
  document.addEventListener('DOMContentLoaded', function() {
    const html = document.documentElement;
    const toggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    // Check local storage or system preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      html.setAttribute('data-theme', 'dark');
      if (themeIcon) themeIcon.className = 'bi bi-sun-fill';
    }

    if (toggleBtn) {
      toggleBtn.addEventListener('click', function() {
        if (html.getAttribute('data-theme') === 'dark') {
          html.removeAttribute('data-theme');
          localStorage.setItem('theme', 'light');
          themeIcon.className = 'bi bi-moon-fill';
        } else {
          html.setAttribute('data-theme', 'dark');
          localStorage.setItem('theme', 'dark');
          themeIcon.className = 'bi bi-sun-fill';
        }
      });
    }
  });
</script>
</body>
</html>