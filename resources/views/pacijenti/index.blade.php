@extends('layouts.app')

@section('content')
<h2>Lista pacijenata</h2>

<a href="{{ route('pacijenti.create') }}">Dodaj pacijenta</a>

<ul>
    @foreach($pacijenti as $pacijent)
        <li>
            {{ $pacijent->ime }} {{ $pacijent->prezime }}
            <a href="{{ route('pacijenti.show', $pacijent->id) }}">Prikaži</a>
            <a href="{{ route('pacijenti.edit', $pacijent->id) }}">Izmeni</a>
            <form action="{{ route('pacijenti.destroy', $pacijent->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Obriši</button>
            </form>
        </li>
    @endforeach
</ul>
@endsection