@extends('layouts.app')
@section('title','Nova degustacija')

@section('content')
<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card beer-card">
        <div class="card-header"><h5 class="mb-0">Nova degustacija</h5></div>
        <div class="card-body">
          <form method="POST" action="{{ route('degustacijas.store') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold" for="Naziv">Naziv</label>
              <input id="Naziv" name="Naziv" value="{{ old('Naziv') }}" class="form-control @error('Naziv') is-invalid @enderror" required>
              @error('Naziv') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold" for="Datum">Datum i vreme</label>
              <input type="datetime-local" id="Datum" name="Datum"
                     value="{{ old('Datum', now()->addDays(3)->format('Y-m-d\TH:i')) }}"
                     class="form-control @error('Datum') is-invalid @enderror" required>
              @error('Datum') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold" for="Lokacija">Lokacija</label>
              <input id="Lokacija" name="Lokacija" value="{{ old('Lokacija') }}" class="form-control @error('Lokacija') is-invalid @enderror" required>
              @error('Lokacija') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold" for="Kapacitet">Kapacitet</label>
              <input type="number" min="1" step="1" id="Kapacitet" name="Kapacitet"
                     value="{{ old('Kapacitet', 20) }}" class="form-control @error('Kapacitet') is-invalid @enderror" required>
              @error('Kapacitet') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-amber">Sačuvaj</button>
              <a href="{{ route('degustacijas.index') }}" class="btn btn-outline-secondary">Otkaži</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
