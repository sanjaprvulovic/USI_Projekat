@extends('layouts.app')
@section('title', $degustacija->Naziv)

@section('content')
<div class="container-xxl my-4">

  {{-- ====== Header sa osnovnim informacijama + akcije ====== --}}
  <div class="card beer-card mb-4">
    <div class="card-body d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
      <div>
        <h2 class="mb-1" style="font-weight:800">{{ $degustacija->Naziv }}</h2>

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
            <span class="badge bg-warning text-dark me-2">
              Status: {{ $degustacija->statusDegustacija->Naziv }}
            </span>
          @endif

          {{-- Preostala mesta / Popunjeno (ako postoje metode u modelu) --}}
          @php
            $hasRemaining = method_exists($degustacija,'remainingCapacity');
            $remaining = $hasRemaining ? $degustacija->remainingCapacity() : null;
            $isFull = method_exists($degustacija,'isFull') ? $degustacija->isFull() : false;
          @endphp
          @if($hasRemaining && !is_null($remaining))
            <span class="badge bg-success">Preostalo: {{ $remaining }}</span>
          @endif
          @if($isFull)
            <span class="badge bg-danger ms-2">Popunjeno</span>
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
          <a href="{{ route('prIjavas.forDegustacija', $degustacija) }}" class="btn btn-outline-secondary">
            Prijave
          </a>
        @endcan

        @can('admin')
          <form action="{{ route('degustacijas.destroy', $degustacija) }}" method="POST"
                onsubmit="return confirm('Obrisati degustaciju?')" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">Obriši</button>
          </form>
        @endcan
      </div>
    </div>
  </div>

  @php
    // Pribavi listu paketa dodeljenih ovoj degustaciji (radi i sa pivot modelom)
    $paketiLista = collect();
    if (isset($paketi)) {
        $paketiLista = collect($paketi); // ako ih je kontroler poslao
    } elseif (method_exists($degustacija, 'paketi')) {
        $paketiLista = $degustacija->paketi; // belongsToMany
    } elseif (method_exists($degustacija, 'degustacijaPakets')) {
        $paketiLista = $degustacija->degustacijaPakets->map(fn($dp) => $dp->degustacioniPaket)->filter();
    }
  @endphp

  <div class="row g-4">
    {{-- ====== Levo: lista dodeljenih paketa ====== --}}
    <div class="col-lg-8">
      <div class="card beer-card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Dostupni degustacioni paketi</h5>
          @can('managerOrAdmin')
            <a href="{{ route('degustacijas.paketi', $degustacija) }}" class="btn btn-sm btn-outline-secondary">
              Uredi pakete
            </a>
          @endcan
        </div>

        <div class="card-body">
          @if($paketiLista->isEmpty())
            <div class="alert alert-warning mb-0">
              Trenutno nisu dodeljeni paketi za ovu degustaciju.
              @can('managerOrAdmin') Dodeli pakete klikom na „Uredi pakete“. @endcan
            </div>
          @else
            <div class="row g-3">
              @foreach($paketiLista as $paket)
                <div class="col-md-6">
                  <div class="h-100 border rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-start">
                      <strong>{{ $paket->NazivPaketa }}</strong>
                      <span class="badge bg-warning text-dark">
                        {{ number_format($paket->Cena, 0, ',', '.') }} RSD
                      </span>
                    </div>
                    @if(!empty($paket->Opis))
                      <div class="mt-2 text-muted">
                        {{ \Illuminate\Support\Str::limit($paket->Opis, 160) }}
                      </div>
                    @endif
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- ====== Desno: forma za prijavu (samo klijent) ====== --}}
    <div class="col-lg-4">
      <div class="card beer-card">
        <div class="card-body p-4">
          <h5 class="mb-3" style="font-weight:800">Prijava</h5>

          @auth
            @can('client')
              @if($paketiLista->isEmpty())
                <div class="alert alert-warning mb-0">Za ovu degustaciju još nisu dodeljeni paketi.</div>
              @elseif($isFull)
                <div class="alert alert-danger mb-0">
                  Nažalost, kapacitet je popunjen. Prijava nije moguća.
                </div>
              @else
                <form method="POST" action="{{ route('prIjavas.store') }}" class="vstack gap-3">
                  @csrf
                  <input type="hidden" name="degustacija_id" value="{{ $degustacija->id }}">

                  <div>
                    <label class="form-label">Paket</label>
                    <select name="degustacioni_paket_id"
                            class="form-select @error('degustacioni_paket_id') is-invalid @enderror" required>
                      <option value="" selected disabled>Odaberi paket…</option>
                      @foreach($paketiLista as $pk)
                        <option value="{{ $pk->id }}">
                          {{ $pk->NazivPaketa }} — {{ number_format($pk->Cena,0,',','.') }} RSD
                        </option>
                      @endforeach
                    </select>
                    @error('degustacioni_paket_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <button class="btn btn-amber w-100">Prijavi se</button>
                </form>
              @endif
            @else
              <div class="alert alert-warning mb-0">Prijave podnosi korisnik u ulozi <strong>Klijent</strong>.</div>
            @endcan
          @else
            <a href="{{ route('login') }}" class="btn btn-amber w-100">Uloguj se da se prijaviš</a>
          @endauth
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
