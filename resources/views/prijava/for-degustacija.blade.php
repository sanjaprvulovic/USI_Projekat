@extends('layouts.app')
@section('title', 'Prijave — ' . $degustacija->Naziv)

@section('content')
<div class="container-xxl my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0" style="font-weight:800">Prijave — {{ $degustacija->Naziv }}</h3>
    <a href="{{ route('degustacijas.show', $degustacija) }}" class="btn btn-outline-secondary">Nazad na degustaciju</a>
  </div>

  @if($prijave->isEmpty())
    <div class="alert alert-warning">Nema prijava za ovu degustaciju.</div>
  @else
    <div class="table-responsive beer-card p-2">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Korisnik</th>
            <th>Email</th>
            <th>Paket</th>
            <th>Status</th>
            <th class="text-end">Akcije</th>
          </tr>
        </thead>
        <tbody>
          @foreach($prijave as $p)
            <tr>
              <td>{{ $p->user->name }} {{ $p->user->surname }}</td>
              <td>{{ $p->user->email }}</td>
              <td>{{ $p->degustacioniPaket->NazivPaketa ?? '—' }}</td>
              <td>
                <span class="badge bg-dark">{{ $p->statusPrijava->Naziv ?? '—' }}</span>
              </td>
              <td class="text-end">
                <form action="{{ route('prIjavas.approve', $p) }}" method="POST" class="d-inline">
                  @csrf @method('PUT')
                  <button class="btn btn-sm btn-success">Odobri</button>
                </form>

                <form action="{{ route('prIjavas.reject', $p) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Odbiti prijavu?')">
                  @csrf @method('PUT')
                  <button class="btn btn-sm btn-outline-danger">Odbij</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
