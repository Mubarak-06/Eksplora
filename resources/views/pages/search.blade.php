@extends('layouts.app')
@section('title','Pencarian')
@section('content')
<div class="page-heading"><h1>Temukan di EKSPLORA</h1><p>Cari ekosistem atau organisme yang ingin kamu kenali.</p></div>
<form class="card filters" action="{{ route('search') }}"><div class="field grow"><label for="search-q">Kata pencarian</label><input id="search-q" name="q" value="{{ $q }}" maxlength="100" required></div><button class="button">Cari</button></form>
@if($q)<h2>Hasil untuk “{{ $q }}”</h2><div class="grid three">@foreach($ecosystems as $ecosystem)<x-ecosystem-card :ecosystem="$ecosystem" />@endforeach @foreach($organisms as $organism)<x-organism-card :organism="$organism" />@endforeach</div>@if($ecosystems->isEmpty()&&$organisms->isEmpty())<div class="card empty-state">Tidak ditemukan. Coba kata lain seperti “rawa” atau “haruan”.</div>@endif @endif
@endsection
