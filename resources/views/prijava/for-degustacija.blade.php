@extends('layouts.app')
@section('title', 'Prijave — '.$degustacija->Naziv)

@section('content')
<div class="container-xxl my-4">
  <div class="card beer-card mb-3">
    <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
      <div>
        <h3 class="mb-1">{{ $degustacija->Naziv }}</h3>
        <div class="text-muted">
          <i class="icon ion-md-calendar"></i>
          {{ optional($degustacija->Datum)->format('d.m.Y. H:i') }}
          &nbsp;•&nbsp;
          <i class="icon ion-md-pin"></i>
          {{ $degustacija->Lokacija }}
        </div>
        <div class="mt-2">
          @php $statusDeg = optional($degustacija->statusDegustacija)->Naziv; @endphp
          <span class="badge bg-warning text-dark">Status: {{ $statusDeg ?? '—' }}</span>
          <span class="badge bg-dark-subtle text-dark ms-2">Kapacitet: {{ $degustacija->Kapacitet }}</span>
        </div>
      </div>

      
      <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('degustacijas.show', $degustacija) }}" class="btn btn-outline-secondary">Nazad</a>

        @can('managerOrAdmin')
          @php
            $now = now();
            $hasDate = !is_null($degustacija->Datum);
            $started = $hasDate ? $now->gte($degustacija->Datum) : false;

            $canFinish = $statusDeg !== 'Završena' && $statusDeg !== 'Otkazana' && $started;
            $canCancel = $statusDeg !== 'Otkazana' && $statusDeg !== 'Završena' && $hasDate && !$started;
          @endphp

          @if($canFinish)
            <form method="POST" action="{{ route('degustacijas.finish', $degustacija) }}" class="d-inline">
              @csrf @method('PUT')
              <button class="btn btn-amber">Označi kao završena</button>
            </form>
          @endif

          @if($canCancel)
            <form method="POST" action="{{ route('degustacijas.cancel', $degustacija) }}" class="d-inline"
                  onsubmit="return confirm('Otkazati degustaciju?')">
              @csrf @method('PUT')
              <button class="btn btn-outline-danger">Otkaži degustaciju</button>
            </form>
          @endif
        @endcan
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  
  <div class="table-responsive beer-card p-2">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Korisnik</th>
          <th>Paket</th>
          <th>Status</th>
          <th>Prisutan</th>
          <th class="text-end">Akcije</th>
        </tr>
      </thead>
      <tbody>
        @forelse($prijave as $p)
          @php $status = optional($p->statusPrijava)->Naziv; @endphp
          <tr>
            <td>{{ $p->user->name }} {{ $p->user->surname }}</td>
            <td>{{ $p->degustacioniPaket->NazivPaketa ?? '—' }}</td>
            <td><span class="badge bg-dark">{{ $status ?? '—' }}</span></td>

            
            <td>
              @if($status === 'Prihvaćena')
                @can('manager')
                  <form method="POST" action="{{ route('prIjavas.checkIn', $p) }}">
                    @csrf @method('PUT')
                    <button class="btn btn-sm {{ $p->prisutan ? 'btn-success' : 'btn-outline-secondary' }}">
                      {{ $p->prisutan ? 'Prisutan' : 'Označi prisutnim' }}
                    </button>
                    @if($p->prisutan)
                      <div class="small text-muted">od {{ optional($p->checked_in_at)->format('d.m.Y. H:i') }}</div>
                    @endif
                  </form>
                @else
                  
                  @if($p->prisutan)
                    <span class="badge bg-success">Prisutan</span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                @endcan
              @else
                <span class="text-muted">—</span>
              @endif
            </td>

            
            <td class="text-end">
              @if($status === 'Na čekanju')
                @can('manager')
                  <form method="POST" action="{{ route('prIjavas.approve', $p) }}" class="d-inline">
                    @csrf @method('PUT')
                    <button class="btn btn-sm btn-amber">Odobri</button>
                  </form>

                  <form method="POST" action="{{ route('prIjavas.reject', $p) }}" class="d-inline"
                        onsubmit="return confirm('Odbiti prijavu?')">
                    @csrf @method('PUT')
                    <button class="btn btn-sm btn-outline-danger">Odbij</button>
                  </form>
                @else
                  <span class="text-muted">—</span>
                @endcan
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-muted">Nema prijava.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
