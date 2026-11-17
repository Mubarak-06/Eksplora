@extends('layouts.app')
@section('title','Beranda')
@section('body-class','home-page')
@section('main-class','home-main')
@section('content')
<section class="home-hero" aria-labelledby="home-title">
    <img class="hero-landscape" src="{{ asset('images/hero-wetland.webp') }}" alt="Ilustrasi lahan basah dengan sungai, burung, rumah panggung, menara, dan pepohonan">
    <div class="hero-copy"><div class="hero-kicker"><img src="{{ asset('images/logo.svg') }}" alt="" width="66" height="66"><span>KENALI ALAM, JAGA MASA DEPAN</span></div>
        <h1 id="home-title">EKSPLORA</h1><h2>Jelajahi Ekosistem Lahan Basah<br>Kalimantan Selatan</h2>
        <p>Media pembelajaran interaktif untuk mengenal ekosistem, flora, dan fauna lahan basah di Kalimantan Selatan.</p>
        <a class="button hero-button" href="{{ route('exploration') }}">Mulai Jelajah <span aria-hidden="true">→</span></a>
        <span class="hero-caption">Petualangan belajar IPA untuk kelas VII</span>
    </div>
</section>
<section class="stats-panel" aria-label="Isi EKSPLORA">
    @foreach([['🌏','Ekosistem'],['🌱','Spesies'],['🏆','Jenis Kuis'],['🏅','Lencana']] as $i=>[$icon,$label])<div class="stat"><span class="stat-icon" aria-hidden="true">{{ $icon }}</span><div><strong>{{ $stats[$i] }}</strong><span>{{ $label }}</span></div></div>@endforeach
</section>
@endsection
