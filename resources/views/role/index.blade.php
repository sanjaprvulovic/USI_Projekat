
@extends('layouts.app')

@section('content')
     <table>
            <thead>
                <th>ID</th>
                <th>Naziv</th>
                <th colspan="2">Opcije</th>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->Naziv }}</td>
                    <td class="opt">
                        <a href="{{ route("roles.edit", $role->id) }}" class="dugme">Izmeni</a>
                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dugme">Obriši</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

@endsection

