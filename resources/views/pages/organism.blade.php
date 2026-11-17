@extends('layouts.app')
@section('title',$organism->name)
@section('content')
<nav class="breadcrumb" aria-label="Breadcrumb"><a href="{{ route('organisms',['category'=>$organism->category]) }}">Flora & Fauna</a><span>›</span><span>{{ $organism->name }}</span></nav>
<section class="card organism-hero"><img src="{{ asset($organism->image) }}" alt="Ilustrasi {{ $organism->name }}"><div><span class="tag">{{ ucfirst($organism->category) }}</span><h1>{{ $organism->name }}</h1><p class="scientific"><i>{{ $organism->scientific_name }}</i></p><p>{{ $organism->summary }}</p><div class="habitat-links">@foreach($organism->ecosystems as $e)<a class="tag" href="{{ route('ecosystem',$e) }}">{{ $e->name }} →</a>@endforeach</div></div></section>
<div class="grid two">@foreach(['traits'=>'🔎 Ciri-ciri','habitat'=>'🌏 Habitat','nutrition'=>$organism->category==='flora'?'☀ Cara Memperoleh Nutrisi':'🐟 Makanan','adaptation'=>'🌱 Adaptasi','benefits'=>'🤝 Manfaat'] as $key=>$label)<article class="card card-body"><h2>{{ $label }}</h2><p>{{ $organism->content[$key] }}</p></article>@endforeach</div>
<p class="muted">Referensi: <a href="{{ $organism->content['source'] }}" target="_blank" rel="noopener noreferrer">{{ $organism->slug==='bekantan'?'Penelitian habitat bekantan — IPB':($organism->category==='flora'?'Plants of the World Online — Kew':'FishBase') }}</a>. Ilustrasi sederhana, bukan foto identifikasi. Persebaran pada lokasi tertentu perlu survei lapangan.</p>
<div class="action-row"><a class="button secondary" href="{{ route('organisms',['category'=>$organism->category]) }}">← Kembali ke Flora & Fauna</a><x-complete activity="organism" :subject="$organism->slug" /></div>
@endsection
