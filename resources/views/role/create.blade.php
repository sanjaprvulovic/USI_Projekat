
@extends('layouts.app')

@section('content')
<div class="content">
    <h1>Nova rola</h1>
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <div class="form">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <table>
                    <tr>
                        <td><label for="">Naziv:role</label></td>
                        <td><input type="text" name="Naziv"></td>
                    </tr>
                    <tr><td><button type="submit" class="dugme">Potvrdi</button></td></tr>
                </table>
            </form>
        </div>
</div>
@endsection

