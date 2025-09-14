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
              $st  = optional($p->statusPrijava)->Naziv;
              $cls = match($st) {
                'Na čekanju' => 'bg-warning text-dark',
                'Prihvaćena' => 'bg-success',
                'Odbijena'   => 'bg-danger',
                'Otkazana'   => 'bg-secondary',
                default      => 'bg-dark',
              };

              $isOwner = auth()->id() === $p->user_id;

              
              $startAt = $p->degustacija?->Datum;

              
              $tooLate = $startAt ? (now()->gte($startAt) || now()->diffInHours($startAt) < 24) : false;

              
              $canCancel = $isOwner && in_array($st, ['Na čekanju','Prihvaćena']) && ! $tooLate;

              
              $reason = null;
              if (! $canCancel) {
                if (! $isOwner) {
                  $reason = 'Nije vaša prijava.';
                } elseif ($st === 'Otkazana') {
                  $reason = 'Već otkazana.';
                } elseif ($st === 'Odbijena') {
                  $reason = 'Odbijena — otkaz nije potreban.';
                } elseif ($tooLate) {
                  $reason = $startAt && now()->gte($startAt)
                      ? 'Degustacija je već počela.'
                      : 'Manje od 24h do početka.';
                } else {
                  $reason = 'U ovom statusu nije moguće otkazati.';
                }
              }
            @endphp

            <tr>
              <td>{{ $p->degustacija->Naziv }}</td>
              <td>{{ $p->degustacija?->Datum?->format('d.m.Y. H:i') }}</td>
              <td>
                @php
                  $st = optional($p->statusPrijava)->Naziv;
                  $isOwner = auth()->id() === $p->user_id;
                @endphp

                @if($isOwner && $st === 'Na čekanju')
                  <form method="POST" action="{{ route('prIjave.update', $p) }}" class="d-flex gap-2 align-items-center">
                    @csrf
                    @method('PUT')
                    <select name="degustacioni_paket_id" class="form-select form-select-sm" required>
                      @foreach($p->degustacija->paketi as $pk)
                        <option value="{{ $pk->id }}" {{ (int)$pk->id === (int)$p->degustacioni_paket_id ? 'selected' : '' }}>
                          {{ $pk->NazivPaketa }} — {{ number_format($pk->Cena,0,',','.') }} RSD
                        </option>
                      @endforeach
                    </select>
                    <button class="btn btn-sm btn-amber">Sačuvaj</button>
                  </form>
                @else
                  {{ $p->degustacioniPaket->NazivPaketa ?? '—' }}
                @endif
              </td>
              <td><span class="badge {{ $cls }}">{{ $st ?? '—' }}</span></td>
              <td class="text-end">
                @if($canCancel)
                  <form action="{{ route('prIjave.destroy', $p) }}" method="POST"
                        onsubmit="return confirm('Otkazati prijavu?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Otkaži</button>
                  </form>
                @else
                  <span class="text-muted small">{{ $reason }}</span>
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
