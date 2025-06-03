@extends('layouts.app')

@section('content')
<h2>Dodaj karton za pacijenta: {{ $pacijent->ime }} {{ $pacijent->prezime }}</h2>

<form action="{{ route('kartoni.store') }}" method="POST">
    @csrf
    <input type="hidden" name="pacijent_id" value="{{ $pacijent->id }}">
    <input type="number" step="0.1" name="visina" placeholder="Visina (cm)">
    <input type="number" step="0.1" name="tezina" placeholder="Težina (kg)">
    <input type="text" name="krvni_pritisak" placeholder="Krvni pritisak">
    <textarea name="dijagnoza" placeholder="Dijagnoza"></textarea>
    <textarea name="tretman" placeholder="Tretman"></textarea>
    <button type="submit">Sačuvaj</button>
</form>
@endsection
