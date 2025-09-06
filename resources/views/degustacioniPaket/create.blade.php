@extends('layouts.app')
@section('title','Novi degustacioni paket')

@section('content')
<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card beer-card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Novi degustacioni paket</h5>
          <a href="{{ route('degustacioni-pakets.index') }}" class="btn btn-outline-secondary btn-sm">Nazad</a>
        </div>

        <div class="card-body">
          <form method="POST" action="{{ route('degustacioni-pakets.store') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label fw-semibold" for="NazivPaketa">Naziv paketa</label>
              <input type="text" id="NazivPaketa" name="NazivPaketa"
                     value="{{ old('NazivPaketa') }}"
                     class="form-control @error('NazivPaketa') is-invalid @enderror" required>
              @error('NazivPaketa') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold" for="Cena">Cena (RSD)</label>
              <input type="number" min="0" step="1" id="Cena" name="Cena"
                     value="{{ old('Cena') }}"
                     class="form-control @error('Cena') is-invalid @enderror" required>
              @error('Cena') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold" for="Opis">Opis (opciono)</label>
              <textarea id="Opis" name="Opis" rows="4"
                        class="form-control @error('Opis') is-invalid @enderror"
                        placeholder="Šta je uključeno u paket…">{{ old('Opis') }}</textarea>
              @error('Opis') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-amber">Sačuvaj</button>
              <a href="{{ route('degustacioni-pakets.index') }}" class="btn btn-outline-secondary">Otkaži</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
