@extends('layouts.app')

@section('content')
<h2>Novi pacijent</h2>

<form action="{{ route('pacijenti.store') }}" method="POST">
    @csrf
    <input type="text" name="ime" placeholder="Ime">
    <input type="text" name="prezime" placeholder="Prezime">
    <input type="text" name="jmbg" placeholder="JMBG">
    <input type="date" name="datum_rodjenja">
    <input type="text" name="pol" placeholder="Pol">
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="telefon" placeholder="Telefon">
    <textarea name="istorija_pacijenta" placeholder="Istorija pacijenta"></textarea>
    <button type="submit">Sačuvaj</button>
</form>
@endsection