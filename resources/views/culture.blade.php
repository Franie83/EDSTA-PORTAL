@extends('layouts.app')

@section('title', 'Culture - ' . ($settings['site_name'] ?? 'Edo Tourism'))
@section('content')

<!-- ── CULTURE HERO ───────────────────────────────── -->
<section class="py-5" style="background: linear-gradient(rgba(10,26,51,0.8), rgba(10,26,51,0.8)), url('{{ !empty($settings['hero_image']) ? \App\Helpers\Helpers::imageUrl($settings['hero_image']) : "https://images.unsplash.com/photo-1580654712603-eb43273aff33?auto=format&fit=crop&q=80&w=1600" }}') center/cover no-repeat;">
  <div class="container py-4">
    <div class="text-center text-white">
      <div class="section-badge d-inline-block mb-3"><i class="bi bi-people-fill me-1"></i>Culture & People</div>
      <h1 class="display-5 fw-bold mb-3" style="font-family:'Playfair Display',serif;">The Living Culture of Edo State</h1>
      <p class="lead text-white-50 mx-auto" style="max-width:720px;">Languages spoken, dishes shared, and traditions worn. Discover the people behind the Cradle of Black Civilization.</p>
    </div>
  </div>
</section>

<!-- ── LANGUAGES ──────────────────────────────────── -->
@if($languages->count())
<section class="py-5 bg-blue-100">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-badge"><i class="bi bi-translate me-1"></i>Languages</div>
      <h2 class="section-title mb-2">Languages of Edo State</h2>
      <p class="text-muted mx-auto" style="max-width:640px;">From the Bini lingua franca of Benin City to the dialects of Etsako, Esan, and Akoko-Edo — each tongue carries centuries of tradition.</p>
    </div>
    <div class="row g-4">
      @foreach($languages as $lang)
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
              <h5 class="fw-bold mb-2">{{ $lang->name }}</h5>
              @if($lang->speakers)<div class="small text-muted mb-2"><i class="bi bi-people me-1"></i>{{ $lang->speakers }}</div>@endif
              @if($lang->area)<div class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $lang->area }}</div>@endif
              @if($lang->lga_list)<div class="small text-muted mb-2"><i class="bi bi-map me-1"></i>{{ $lang->lga_list }}</div>@endif
              @if($lang->notes)<p class="small mt-2 mb-0">{{ $lang->notes }}</p>@endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ── CUISINE ────────────────────────────────────── -->
@if($cuisines->count())
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-badge"><i class="bi bi-egg-fried me-1"></i>Cuisine</div>
      <h2 class="section-title mb-2">Traditional Edo Dishes</h2>
      <p class="text-muted mx-auto" style="max-width:640px;">From the viscous draw of Ogbolor soup to the smoke of black soup — a table spread across the three senatorial districts.</p>
    </div>
    <div class="row g-4">
      @foreach($cuisines as $c)
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
              <h5 class="fw-bold mb-2">{{ $c->name }}</h5>
              @if($c->area)<div class="small text-muted mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $c->area }}</div>@endif
              @if($c->description)<p class="small mb-2">{{ $c->description }}</p>@endif
              @if($c->ingredients)<div class="small text-muted mb-1"><strong>Ingredients:</strong> {{ $c->ingredients }}</div>@endif
              @if($c->commonly_eaten_with)<div class="small text-muted mb-0"><strong>Eaten with:</strong> {{ $c->commonly_eaten_with }}</div>@endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ── ATTIRE ─────────────────────────────────────── -->
@if($attires->count())
<section class="py-5 bg-blue-100">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-badge"><i class="bi bi-palette me-1"></i>Attire</div>
      <h2 class="section-title mb-2">Traditional Attire by Ethnic Group</h2>
      <p class="text-muted mx-auto" style="max-width:640px;">Coral beads, woven wrappers, and beaded crowns — each ensemble tells the story of status, ceremony, and craft.</p>
    </div>
    @foreach($attires as $ethnicGroup => $group)
      <div class="mb-5">
        <h4 class="fw-bold mb-4" style="font-family:'Playfair Display',serif;color:var(--blue-900);">{{ $ethnicGroup }}</h4>
        <div class="row g-4">
          @foreach($group as $attire)
            <div class="col-md-6">
              <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center mb-2">
                    <h5 class="fw-bold mb-0 me-2">{{ ucfirst($attire->gender) }} Attire</h5>
                    <span class="badge bg-info text-dark">{{ $attire->gender }}</span>
                  </div>
                  @if($attire->attire_items)<div class="small text-muted mb-2"><i class="bi bi-bag me-1"></i>{{ $attire->attire_items }}</div>@endif
                  @if($attire->description)<p class="small mb-2">{{ $attire->description }}</p>@endif
                  @if($attire->occasions)<div class="small text-muted mb-1"><strong>Worn at:</strong> {{ $attire->occasions }}</div>@endif
                  @if($attire->notes)<div class="small text-muted mb-0">{{ $attire->notes }}</div>@endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
</section>
@endif

<!-- ── EMPTY STATE ────────────────────────────────── -->
@if($languages->isEmpty() && $cuisines->isEmpty() && $attires->isEmpty())
<section class="py-5">
  <div class="container text-center py-5">
    <i class="bi bi-people fs-1 d-block mb-3 text-muted"></i>
    <h3 class="fw-bold mb-2">Culture content coming soon</h3>
    <p class="text-muted mb-0">The Edo State Tourism Agency is curating the languages, cuisine, and attire of our people.</p>
  </div>
</section>
@endif

@endsection