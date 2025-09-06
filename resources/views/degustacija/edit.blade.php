@extends('layouts.app')

@section('title', 'Izmena degustacije')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-8">
      <div class="beer-card p-4 p-lg-5">

        <h1 class="h3 mb-4 fw-bold">Izmena degustacije</h1>

        {{-- greške validacije --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('degustacijas.update', $degustacija) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="Naziv" class="form-label">Naziv</label>
            <input type="text" id="Naziv" name="Naziv"
                   class="form-control @error('Naziv') is-invalid @enderror"
                   value="{{ old('Naziv', $degustacija->Naziv) }}" required>
            @error('Naziv') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="Datum" class="form-label">Datum i vreme</label>
            <input type="datetime-local" id="Datum" name="Datum"
                   class="form-control @error('Datum') is-invalid @enderror"
                   value="{{ old('Datum', optional($degustacija->Datum)->format('Y-m-d\TH:i')) }}" required>
            @error('Datum') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="Lokacija" class="form-label">Lokacija</label>
            <input type="text" id="Lokacija" name="Lokacija"
                   class="form-control @error('Lokacija') is-invalid @enderror"
                   value="{{ old('Lokacija', $degustacija->Lokacija) }}" required>
            @error('Lokacija') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label for="Kapacitet" class="form-label">Kapacitet</label>
            <input type="number" id="Kapacitet" name="Kapacitet" min="1"
                   class="form-control @error('Kapacitet') is-invalid @enderror"
                   value="{{ old('Kapacitet', $degustacija->Kapacitet) }}" required>
            @error('Kapacitet') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Status degustacije (ako si ga poslala iz kontrolera) --}}
          @isset($statuses)
            <div class="mb-4">
              <label for="status_degustacija_id" class="form-label">Status</label>
              <select id="status_degustacija_id" name="status_degustacija_id"
                      class="form-select @error('status_degustacija_id') is-invalid @enderror" required>
                @foreach($statuses as $id => $naziv)
                  <option value="{{ $id }}"
                    @selected(old('status_degustacija_id', $degustacija->status_degustacija_id) == $id)>
                    {{ $naziv }}
                  </option>
                @endforeach
              </select>
              @error('status_degustacija_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          @endisset

          <div class="d-flex gap-2">
            <a href="{{ route('degustacijas.index') }}" class="btn btn-outline-secondary">Nazad</a>
            <button type="submit" class="btn btn-amber">Sačuvaj izmene</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection
