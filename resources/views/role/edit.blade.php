@extends('layouts.app')
@section('title','Izmena uloge')

@section('content')
<div class="container-xxl my-4">
  <div class="card beer-card">
    <div class="card-body">
      <h5 class="mb-3" style="font-weight:800">Izmena uloge</h5>

      <form action="{{ route('roles.update', $role) }}" method="POST" class="vstack gap-3">
        @csrf @method('PUT')

        <div>
          <label class="form-label">Naziv uloge</label>
          <input type="text" name="Naziv"
                 value="{{ old('Naziv',$role->Naziv) }}"
                 class="form-control @error('Naziv') is-invalid @enderror" required>
          @error('Naziv') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
          <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Nazad</a>
          <button class="btn btn-amber">Sačuvaj</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
