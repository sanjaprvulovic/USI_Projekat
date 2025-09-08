@extends('layouts.app')
@section('title','Degustacije')

@section('content')
<div class="container-xxl my-4">

  {{-- Naslov + "Nova degustacija" (samo menadžer/admin) --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0" style="font-weight:800;color:#2a1c15">Degustacije</h3>
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
        @php
          $hasRemaining = method_exists($d,'remainingCapacity');
          $remaining    = $hasRemaining ? $d->remainingCapacity() : null;
          $isFull       = method_exists($d,'isFull') ? $d->isFull() : false;
          $paketiCount  = $d->relationLoaded('paketi') ? $d->paketi->count() : null;
        @endphp

        <div class="col-md-6 col-lg-4">
          <div class="card h-100 beer-card">
            <div class="card-body d-flex flex-column">

              <h5 class="card-title mb-1" style="font-weight:800">{{ $d->Naziv }}</h5>

              <div class="text-muted small mb-2">
                <i class="icon ion-md-calendar"></i>
                {{ optional($d->Datum)->format('d.m.Y. H:i') }}
                &nbsp;•&nbsp;
                <i class="icon ion-md-pin"></i>
                {{ $d->Lokacija }}
              </div>

              <div class="d-flex flex-wrap gap-2 mb-2">
                <span class="badge bg-dark-subtle text-dark">Kapacitet: {{ $d->Kapacitet }}</span>

                @if(!is_null($paketiCount))
                  <span class="badge bg-secondary">Paketa: {{ $paketiCount }}</span>
                @endif

                @if(isset($d->aktivne_prijave_count))
                  <span class="badge bg-info text-dark">Aktivnih: {{ $d->aktivne_prijave_count }}</span>
                @endif

                @if($hasRemaining && !is_null($remaining))
                  <span class="badge bg-success">Preostalo: {{ $remaining }}</span>
                @endif

                @if($isFull)
                  <span class="badge bg-danger">Popunjeno</span>
                @endif

                @if($d->statusDegustacija)
                  <span class="badge bg-warning text-dark">Status: {{ $d->statusDegustacija->Naziv }}</span>
                @endif
              </div>

              <div class="mt-auto d-flex gap-2">
                <a href="{{ route('degustacijas.show', $d) }}" class="btn btn-outline-secondary btn-sm">
                  Detalj
                </a>
                @can('managerOrAdmin')
                  <a href="{{ route('degustacijas.edit', $d) }}" class="btn btn-outline-secondary btn-sm">
                    Izmeni
                  </a>
                @endcan
                @can('admin')
                  <form action="{{ route('degustacijas.destroy', $d) }}" method="POST"
                        onsubmit="return confirm('Obrisati degustaciju?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">Obriši</button>
                  </form>
                @endcan
              </div>

            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
