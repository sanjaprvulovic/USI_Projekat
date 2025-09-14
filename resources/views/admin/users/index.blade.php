@extends('layouts.app')
@section('title', 'Dodela uloga korisnicima')

@section('content')
<div class="container-xxl my-4">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger mb-3">
      <ul class="mb-0">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card beer-card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Dodela uloga korisnicima</h5>
      <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary">Uloge</a>
    </div>

    <div class="table-responsive p-2">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th style="width:28%">Ime i prezime</th>
            <th style="width:28%">Email</th>
            <th>Uloga</th>
            <th class="text-end">Akcija</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $u)
            <tr>
              <td>{{ $u->name }} {{ $u->surname }}</td>
              <td>{{ $u->email }}</td>
              <td>
                <form action="{{ route('admin.users.updateRole', $u) }}" method="POST" class="d-flex gap-2">
                  @csrf
                  @method('PUT')

                  <select name="role_id" class="form-select form-select-sm" style="min-width:220px">
                    <option value="">— bez uloge —</option>
                    @foreach($roles as $r)
                      <option value="{{ $r->id }}" @selected(optional($u->role)->id === $r->id)>
                        {{ $r->Naziv }}
                      </option>
                    @endforeach
                  </select>

                  <button class="btn btn-sm btn-amber">Sačuvaj</button>
                </form>
              </td>
              <td class="text-end">
               
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
