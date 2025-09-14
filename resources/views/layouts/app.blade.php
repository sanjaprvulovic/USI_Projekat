<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Degustacije')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@500;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Ikonice + Notyf -->
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <!-- Tema “Pivara” -->
    <style>
      :root{
        --beer-dark:#1a1410;            
        --beer-dark-2:#17110d;
        --beer-ink:#2a1c15;             
        --beer-amber:#e0a12b;           
        --beer-amber-2:#c97a28;
        --beer-amber-light:#fde9b8;     
        --beer-amber-light-2:#f8d996;
        --beer-muted:#6c5840;           
      }

      /* Globalno */
      html,body{ height:100% }
      body{
        font-family:"Nunito",system-ui,-apple-system,Segoe UI,Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif;
        color:var(--beer-ink);
        background: linear-gradient(180deg, var(--beer-amber-light), var(--beer-amber-light-2));
      }

      /* NAVBAR */
      .beer-navbar{
        background:linear-gradient(180deg,var(--beer-dark),var(--beer-dark-2));
        border-bottom:1px solid rgba(224,161,43,.25);
        min-height:82px;
      }
      .beer-brand img{ height:56px; filter:drop-shadow(0 2px 6px rgba(0,0,0,.35)); }

      .beer-nav-link{
        color:#e9e3d6 !important; opacity:.9;
        font-weight:800; text-transform:uppercase; letter-spacing:.4px;
        font-size:1.05rem; padding:.95rem 1rem !important;
      }
      .beer-nav-link:hover{ color:var(--beer-amber) !important; opacity:1 }
      .beer-nav-link.active{ color:var(--beer-amber) !important }
      .beer-nav-link.active::after{
        content:""; position:absolute; left:1rem; right:1rem; bottom:.6rem;
        height:3px; background:linear-gradient(90deg,var(--beer-amber),var(--beer-amber-2)); border-radius:8px;
      }
      .navbar-toggler{ border-color:rgba(224,161,43,.45) }
      .navbar-toggler:focus{ box-shadow:0 0 0 .2rem rgba(224,161,43,.25) }

      /* Dugmad */
      .btn-amber{
        background:linear-gradient(180deg,var(--beer-amber),var(--beer-amber-2));
        color:#16120f; border:none; font-weight:900; padding:.6rem 1rem; border-radius:999px;
      }
      .btn-amber:hover{ filter:brightness(1.07); color:#16120f }

      /* Gost – veći logo */
      .beer-navbar-guest .beer-logo{ height:100px }

      /* Kartice za sadržaj (po želji) */
      .beer-card{
        background:#fff; border:1px solid rgba(26,20,16,.08); border-radius:18px;
        box-shadow:0 8px 20px rgba(26,20,16,.06);
      }

      /* Footer */
      .beer-footer{
        background:linear-gradient(180deg,var(--beer-dark),var(--beer-dark-2));
        color:#e9e3d6; border-top:1px solid rgba(224,161,43,.25);
      }
      .beer-footer a{ color:#e0d9c7; text-decoration:none }
      .beer-footer a:hover{ color:var(--beer-amber) }
      .beer-social a{
        display:inline-flex; align-items:center; justify-content:center;
        width:40px; height:40px; margin-right:.5rem;
        border:1px solid rgba(224,161,43,.35); border-radius:50%;
        color:var(--beer-amber); font-size:1.15rem;
      }
      .beer-social a:hover{ background:rgba(224,161,43,.12) }
    </style>

    <!-- Vite (ostavi kako si tražila) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @livewireStyles
  </head>

  <body>
    <div id="app" class="d-flex flex-column min-vh-100">
      @include('layouts.nav')

      <main class="py-4 flex-grow-1">
        @yield('content')
      </main>

      @includeIf('layouts.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    @stack('scripts')

    @if (session()->has('success'))
    <script>
      var notyf = new Notyf({dismissible: true});
      notyf.success(@json(session('success')));
    </script>
    @endif
  </body>
</html>
