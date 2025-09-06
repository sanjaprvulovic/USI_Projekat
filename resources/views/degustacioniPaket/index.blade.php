@extends('layouts.app')
@section('title','Paketi')

@section('content')
<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Degustacioni paketi</h3>
    <a href="{{ route('degustacioni-pakets.create') }}" class="btn btn-amber">Novi paket</a>
  </div>

  @if($paketi->isEmpty())
    <div class="card beer-card"><div class="card-body text-muted">
      Još uvek nema paketa.
    </div></div>
  @else
    <div class="row g-3">
      @foreach($paketi as $paket)
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 beer-card">
            <div class="card-body d-flex flex-column">
              <div class="d-flex justify-content-between">
                <strong>{{ $paket->NazivPaketa }}</strong>
                <span class="badge bg-warning text-dark">{{ number_format($paket->Cena,0,',','.') }} RSD</span>
              </div>
              @if($paket->Opis)
                <div class="small text-muted mt-2">
                  {{ \Illuminate\Support\Str::limit($paket->Opis, 140) }}
                </div>
              @endif

              <div class="mt-auto d-flex gap-2">
                <a href="{{ route('degustacioni-pakets.edit', $paket) }}" class="btn btn-outline-secondary btn-sm">Izmeni</a>
                <form method="POST" action="{{ route('degustacioni-pakets.destroy', $paket) }}"
                      onsubmit="return confirm('Obrisati paket?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-outline-danger btn-sm">Obriši</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
