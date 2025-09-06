@extends('layouts.app')
@section('title', $degustacija->Naziv)

@section('content')
<div class="container my-4">

  {{-- Header kartica sa osnovnim informacijama --}}
  <div class="card beer-card mb-4">
    <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
      <div>
        <h2 class="mb-1">{{ $degustacija->Naziv }}</h2>

        <div class="text-muted">
          <i class="icon ion-md-calendar"></i>
          {{ optional($degustacija->Datum)->format('d.m.Y. H:i') }}
          &nbsp;•&nbsp;
          <i class="icon ion-md-pin"></i>
          {{ $degustacija->Lokacija }}
        </div>

        <div class="mt-2">
          <span class="badge bg-dark-subtle text-dark me-2">
            Kapacitet: <strong>{{ $degustacija->Kapacitet }}</strong>
          </span>
          @if($degustacija->statusDegustacija)
            <span class="badge bg-warning text-dark">
              Status: {{ $degustacija->statusDegustacija->Naziv }}
            </span>
          @endif
        </div>
      </div>

      {{-- Akcije desno --}}
      <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('degustacijas.index') }}" class="btn btn-outline-secondary">Nazad</a>

        @can('managerOrAdmin')
          <a href="{{ route('degustacijas.edit', $degustacija) }}" class="btn btn-outline-secondary">
            Izmeni
          </a>
          <a href="{{ route('degustacijas.paketi', $degustacija) }}" class="btn btn-amber">
            Dodeli pakete
          </a>
        @endcan

        @can('admin')
          <form action="{{ route('degustacijas.destroy', $degustacija) }}" method="POST"
                onsubmit="return confirm('Obrisati degustaciju?')" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">Obriši</button>
          </form>
        @endcan

        @can('client')
          {{-- Klijent ide na formu za prijavu, paket bira tamo --}}
          <a href="{{ route('prIjavas.create', ['degustacija' => $degustacija->id]) }}" class="btn btn-amber">
            Prijavi se
          </a>
        @endcan
      </div>
    </div>
  </div>

  {{-- Paketi dodeljeni ovoj degustaciji --}}
  <div class="card beer-card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Dostupni degustacioni paketi</h5>
      @can('managerOrAdmin')
        <a href="{{ route('degustacijas.paketi', $degustacija) }}" class="btn btn-sm btn-outline-secondary">
          Uredi pakete
        </a>
      @endcan
    </div>

    <div class="card-body">
      @php
        // sigurnosna provera da relacija postoji
        $paketi = method_exists($degustacija, 'paketi') ? $degustacija->paketi : collect();
      @endphp

      @if($paketi->isEmpty())
        <div class="alert alert-warning mb-0">
          Trenutno nisu dodeljeni paketi za ovu degustaciju.
          @can('managerOrAdmin')
            &nbsp;Dodeli pakete klikom na „Uredi pakete“.
          @endcan
        </div>
      @else
        <div class="row g-3">
          @foreach($paketi as $paket)
            <div class="col-md-6 col-lg-4">
              <div class="h-100 border rounded-3 p-3">
                <div class="d-flex justify-content-between align-items-start">
                  <strong>{{ $paket->NazivPaketa }}</strong>
                  <span class="badge bg-warning text-dark">
                    {{ number_format($paket->Cena, 0, ',', '.') }} RSD
                  </span>
                </div>
                @if(!empty($paket->Opis))
                  <div class="mt-2 text-muted">
                    {{ \Illuminate\Support\Str::limit($paket->Opis, 140) }}
                  </div>
                @endif
                @can('client')
                  <div class="mt-3">
                    <a href="{{ route('prIjavas.create', ['degustacija' => $degustacija->id]) }}"
                       class="btn btn-sm btn-amber w-100">
                      Odaberi ovaj paket u prijavi
                    </a>
                  </div>
                @endcan
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

</div>
@endsection
