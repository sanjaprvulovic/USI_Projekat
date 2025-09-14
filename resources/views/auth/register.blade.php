@extends('layouts.app')

@section('content')
<style>
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
    <div class="col-12 col-md-10 col-lg-8 col-xl-6">
      <div class="auth-card">
        <div class="auth-header">
          <img src="{{ asset('images/logo.webp') }}" alt="ES Pivara">
          <h1 class="h4 auth-title">Kreiraj nalog</h1>
          <p class="small muted mb-0">Pridruži se degustacijama i odaberi svoj paket</p>
        </div>

        <div class="auth-body">
          <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Ime</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="icon ion-md-person"></i></span>
                  <input id="name" type="text" name="name" value="{{ old('name') }}"
                         class="form-control @error('name') is-invalid @enderror" required autofocus>
                </div>
                @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label for="surname" class="form-label">Prezime</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="icon ion-md-person"></i></span>
                  <input id="surname" type="text" name="surname" value="{{ old('surname') }}"
                         class="form-control @error('surname') is-invalid @enderror" required>
                </div>
                @error('surname') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>

              <div class="col-12">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="icon ion-md-mail"></i></span>
                  <input id="email" type="email" name="email" value="{{ old('email') }}"
                         class="form-control @error('email') is-invalid @enderror" required>
                </div>
                @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>

              <div class="col-12">
                <label for="phone" class="form-label">Telefon</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="icon ion-md-call"></i></span>
                  <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                         class="form-control @error('phone') is-invalid @enderror" required>
                </div>
                @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label for="password" class="form-label">Lozinka</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="icon ion-md-lock"></i></span>
                  <input id="password" type="password" name="password"
                         class="form-control @error('password') is-invalid @enderror" required>
                </div>
                @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
              </div>

              <div class="col-md-6">
                <label for="password-confirm" class="form-label">Potvrdi lozinku</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="icon ion-md-lock"></i></span>
                  <input id="password-confirm" type="password" name="password_confirmation" class="form-control" required>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-amber w-100 mt-4 py-2 fw-semibold">
              Registruj se
            </button>

            <p class="text-center mt-3 mb-0 muted">
              Već imaš nalog?
              <a href="{{ route('login') }}" class="link-amber">Prijavi se</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
