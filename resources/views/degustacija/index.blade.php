@extends('layouts.app')
@section('title','Degustacije')

@section('content')
<div class="container my-4">

  {{-- Header + dugme za novu degustaciju (samo menadžer/admin) --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Degustacije</h3>
    @can('managerOrAdmin')
      <a href="{{ route('degustacijas.create') }}" class="btn btn-amber">Nova degustacija</a>
    @endcan
  </div>

  @if($degustacijas->isEmpty())
    <div class="card beer-card">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div class="text-muted">
          Trenutno nema kreiranih degustacija.
          @can('managerOrAdmin')
            Napravi prvu klikom na „Nova degustacija“.
          @endcan
        </div>
      </div>
    </div>
  @else
    <div class="row g-3">
      @foreach($degustacijas as $d)
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 beer-card">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title mb-1">{{ $d->Naziv }}</h5>
              <div class="text-muted small mb-2">
                <i class="icon ion-md-calendar"></i>
                {{ optional($d->Datum)->format('d.m.Y. H:i') }}
                &nbsp;•&nbsp;
                <i class="icon ion-md-pin"></i>
                {{ $d->Lokacija }}
              </div>
              <div class="mb-2">
                <span class="badge bg-dark-subtle text-dark">Kapacitet: {{ $d->Kapacitet }}</span>
              </div>

              <div class="mt-auto d-flex gap-2">
                <a href="{{ route('degustacijas.show', $d) }}" class="btn btn-outline-secondary btn-sm">Detalj</a>
                @can('managerOrAdmin')
                  <a href="{{ route('degustacijas.edit', $d) }}" class="btn btn-outline-secondary btn-sm">Izmeni</a>
                @endcan
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Ako uključiš paginaciju u kontroleru, prikaži linkove: --}}
    {{-- <div class="mt-3">{{ $degustacijas->links() }}</div> --}}
  @endif
</div>
@endsection

