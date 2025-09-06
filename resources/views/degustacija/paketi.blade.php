@extends('layouts.app')
@section('title', 'Dodeli pakete — '.$degustacija->Naziv)

@section('content')
<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-lg-9">
      <div class="card beer-card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-0">{{ $degustacija->Naziv }}</h5>
            <small class="text-muted">{{ optional($degustacija->Datum)->format('d.m.Y H:i') }} • {{ $degustacija->Lokacija }}</small>
          </div>
          <a href="{{ route('degustacijas.index') }}" class="btn btn-sm btn-outline-secondary">Nazad</a>
        </div>

        <form method="POST" action="{{ route('degustacijas.paketi.update', $degustacija) }}" class="p-3">
          @csrf @method('PUT')

          <div class="row g-3">
            @foreach($sviPaketi as $paket)
              <div class="col-md-6">
                <div class="form-check p-3 border rounded-3 h-100">
                  <input class="form-check-input" type="checkbox"
                         name="paketi[]" id="paket{{ $paket->id }}" value="{{ $paket->id }}"
                         {{ in_array($paket->id, $odabrani) ? 'checked' : '' }}>
                  <label class="form-check-label" for="paket{{ $paket->id }}">
                    <strong>{{ $paket->NazivPaketa }}</strong><br>
                    <span class="text-muted">{{ \Illuminate\Support\Str::limit($paket->Opis, 120) }}</span><br>
                    <span class="badge bg-warning text-dark mt-1">{{ number_format($paket->Cena,0,',','.') }} RSD</span>
                  </label>
                </div>
              </div>
            @endforeach
          </div>

          <div class="mt-4 d-flex gap-2">
            <button class="btn btn-amber">Sačuvaj</button>
            <a href="{{ route('degustacijas.show', $degustacija) }}" class="btn btn-outline-secondary">Otkaži</a>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection
