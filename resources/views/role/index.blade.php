@extends('layouts.app')
@section('title','Uloge')

@section('content')
<div class="container-xxl my-4">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0" style="font-weight:800;color:#2a1c15">Uloge</h3>
    <a href="{{ url('/admin/users') }}" class="btn btn-outline-secondary">Korisnici</a>
  </div>

  <div class="row g-3">

    
    <div class="col-lg-8">
      <div class="card beer-card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th style="width:80px">ID</th>
                  <th>Naziv</th>
                  <th class="text-end" style="width:220px">Opcije</th>
                </tr>
              </thead>
              <tbody>
                @forelse($roles as $r)
                  <tr>
                    <td>{{ $r->id }}</td>
                    <td class="fw-semibold">{{ $r->Naziv }}</td>
                    <td class="text-end">
                      <a href="{{ route('roles.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Izmeni</a>

                      <form action="{{ route('roles.destroy', $r) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Obrisati ulogu „{{ $r->Naziv }}”?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Obriši</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-muted">Još uvek nema definisanih uloga.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    
    <div class="col-lg-4">
      <div class="card beer-card">
        <div class="card-body">
          <h5 class="mb-3" style="font-weight:800">Dodaj novu ulogu</h5>

          <form action="{{ route('roles.store') }}" method="POST" class="vstack gap-3">
            @csrf
            <div>
              <label class="form-label">Naziv uloge</label>
              <input type="text" name="Naziv"
                     value="{{ old('Naziv') }}"
                     class="form-control @error('Naziv') is-invalid @enderror"
                     placeholder="npr. Administrator, Klijent, Menadžer događaja" required>
              @error('Naziv') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button class="btn btn-amber w-100">Sačuvaj</button>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
