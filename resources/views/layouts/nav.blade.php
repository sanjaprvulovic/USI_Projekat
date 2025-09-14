@guest
<nav class="navbar navbar-dark beer-navbar beer-navbar-guest py-4 sticky-top">
  <div class="container d-flex justify-content-center">
    <a class="navbar-brand beer-brand" href="{{ route('root') }}">
      <img class="beer-logo" src="{{ asset('images/logo_white_letters.png') }}" alt="ES Pivara">
    </a>
  </div>
</nav>
@endguest


@auth
@php
  $active = fn($pat) => request()->routeIs($pat) ? 'active' : '';
@endphp

<nav class="navbar navbar-expand-lg navbar-dark beer-navbar sticky-top">
  <div class="container-xxl">

    
    <a class="navbar-brand beer-brand order-1 me-2 me-lg-3" href="{{ route('degustacijas.index') }}">
      <img src="{{ asset('images/logo_white_letters.png') }}" alt="ES Pivara" style="height:56px">
    </a>

    
    <button class="navbar-toggler order-4 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    
    <div class="collapse navbar-collapse order-2 ms-lg-auto me-lg-4 flex-grow-0" id="mainNav">
      <ul class="navbar-nav align-items-lg-center mb-2 mb-lg-0">
        <li class="nav-item position-relative">
          <a class="nav-link beer-nav-link {{ $active('degustacijas.*') }}"
             href="{{ route('degustacijas.index') }}">Degustacije</a>
        </li>

        @can('client')
        <li class="nav-item position-relative">
          <a class="nav-link beer-nav-link {{ $active('prIjavas.*') }}"
             href="{{ route('prIjavas.index') }}">Moje prijave</a>
        </li>
        @endcan

        @can('manager')
        <li class="nav-item position-relative">
          <a class="nav-link beer-nav-link {{ $active('degustacijas.create') }}"
             href="{{ route('degustacijas.create') }}">Nova degustacija</a>
        </li>
        @endcan

        {{-- @can('admin')
        <li class="nav-item position-relative">
          <a class="nav-link beer-nav-link {{ $active('degustacioni-pakets.*') }}"
             href="{{ route('degustacioni-pakets.index') }}">Paketi</a>
        </li>
        <li class="nav-item position-relative">
          <a class="nav-link beer-nav-link {{ $active('roles.*') }}"
             href="{{ route('roles.index') }}">Uloge</a>
        </li>
        @endcan --}}

        @can('admin')
        <li class="nav-item position-relative">
            <a class="nav-link beer-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
            href="{{ route('admin.users.index') }}">Korisnici</a>
        </li>
        <li class="nav-item position-relative">
          <a class="nav-link beer-nav-link {{ $active('degustacioni-pakets.*') }}"
             href="{{ route('degustacioni-pakets.index') }}">Paketi</a>
        </li>
        @endcan
      </ul>
    </div>

    
    <div class="order-3 d-flex align-items-center ms-lg-0">
      <span class="navbar-text me-2 d-none d-lg-inline" style="color:#e9e3d6; font-weight:800">
        {{ auth()->user()->name }}
      </span>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-amber btn-sm">
          <i class="icon ion-md-exit"></i> Odjava
        </button>
      </form>
    </div>

  </div>
</nav>
@endauth
