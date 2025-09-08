@extends('layouts.app')
@section('title','Moje prijave')

@section('content')
<div class="container-xxl">
  <h1 class="h4 fw-bold mb-3">Moje prijave</h1>

  @if($prijave->isEmpty())
    <div class="text-muted">Još uvek nemate prijava.</div>
  @else
    <div class="table-responsive beer-card p-2">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Degustacija</th>
            <th>Datum</th>
            <th>Paket</th>
            <th>Status</th>
            <th class="text-end">Akcija</th>
          </tr>
        </thead>
        <tbody>
          @foreach($prijave as $p)
            @php
              $st = optional($p->statusPrijava)->Naziv;
              $cls = match($st) {
                'Na čekanju' => 'bg-warning text-dark',
                'Prihvaćena' => 'bg-success',
                'Odbijena'   => 'bg-danger',
                'Otkazana'   => 'bg-secondary',
                default      => 'bg-dark',
              };
            @endphp
            <tr>
              <td>{{ $p->degustacija->Naziv }}</td>
              <td>{{ optional($p->degustacija->Datum)->format('d.m.Y. H:i') }}</td>
              <td>{{ $p->degustacioniPaket->NazivPaketa ?? '—' }}</td>
              <td>
                <span class="badge {{ $cls }}">{{ $st ?? '—' }}</span>
              </td>
              <td class="text-end">
                @php
                    $st = optional($p->statusPrijava)->Naziv;
                    $isOwner = auth()->id() === $p->user_id;
                @endphp

                @if($isOwner && $st !== 'Otkazana')
                    <form action="{{ route('prIjave.destroy', $p) }}" method="POST"
                        onsubmit="return confirm('Otkazati prijavu?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Otkaži</button>
                    </form>
                @endif
              </td>

            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
