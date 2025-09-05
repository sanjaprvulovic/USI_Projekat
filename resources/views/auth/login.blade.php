@extends('layouts.app')

@section('content')
<style>
  /* Pivara tema – lokalno za ovu stranicu */
  .auth-wrap{min-height:80vh;display:flex;align-items:center}
  .auth-card{
    background:linear-gradient(180deg,#1a1410,#17110d);
    border:1px solid rgba(224,161,43,.25);
    box-shadow:0 10px 30px rgba(0,0,0,.5);
    border-radius:1rem; overflow:hidden
  }
  .auth-header{
    padding:1.25rem;
    background:
      radial-gradient(120% 120% at 0% 0%, rgba(224,161,43,.25), transparent),
      linear-gradient(180deg,#16120f,#1d1814);
    border-bottom:1px solid rgba(224,161,43,.25);
    text-align:center
  }
  .auth-header img{height:76px}
  .auth-title{color:#f2e7c9;margin-top:.75rem}
  .auth-body{padding:1.5rem 1.5rem 1.75rem}
  .form-label{color:#f2e7c9}
  .form-control{background:#221a14;border-color:#3a2b1f;color:#f2e7c9}
  .form-control:focus{
    background:#261e17;border-color:#e0a12b;
    box-shadow:0 0 0 .25rem rgba(224,161,43,.2); color:#fff
  }
  .input-group-text{background:#1b140f;border-color:#3a2b1f;color:#c8bfa9}
  .btn-amber{background:linear-gradient(180deg,#e0a12b,#c97a28);color:#16120f;border:none}
  .btn-amber:hover{filter:brightness(1.05)}
  .muted{color:#c8bfa9}
  a.link-amber{color:#e0a12b;text-decoration:none}
  a.link-amber:hover{color:#f2c14d}
  .invalid-feedback{color:#ffb3b3}
</style>

<div class="container auth-wrap">
  <div class="row justify-content-center w-100">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
      <div class="auth-card">
        <div class="auth-header">
          {{-- stavi svoju putanju ako je drugačija --}}
          <img src="{{ asset('images/logo.webp') }}" alt="ES Pivara">
          <h1 class="h4 auth-title">Dobrodošli</h1>
          <p class="small muted mb-0">Prijavite se za nastavak</p>
        </div>

        <div class="auth-body">
          @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
          @endif

          <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="icon ion-md-mail"></i></span>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror" required autofocus>
              </div>
              @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Lozinka</label>
              <div class="input-group">
                <span class="input-group-text"><i class="icon ion-md-lock"></i></span>
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror" required>
              </div>
              @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Zapamti me</label>
              </div>
              @if (Route::has('password.request'))
                <a class="small link-amber" href="{{ route('password.request') }}">Zaboravljena lozinka?</a>
              @endif
            </div>

            <button type="submit" class="btn btn-amber w-100 py-2 fw-semibold">
              Prijavi se
            </button>

            <p class="text-center mt-3 mb-0 muted">
              Nemaš nalog?
              <a href="{{ route('register') }}" class="link-amber">Registruj se</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
