@extends('layouts.app')
@section('title','Flora & Fauna')
@section('content')
<section class="banner"><div><span class="eyebrow">BERAGAM MAKHLUK, SATU RUMAH</span><h1>Flora & Fauna</h1><p>Kenali tumbuhan dan hewan lahan basah Kalimantan Selatan. Temukan ciri khas, habitat, dan cara mereka menyesuaikan diri.</p></div><img src="{{ asset('images/hero-wetland.webp') }}" alt="Ilustrasi lahan basah"></section>
<form class="card filters" action="{{ route('organisms') }}" method="get">
    <fieldset class="category-options"><legend class="sr-only">Kategori organisme</legend>@foreach(['flora'=>'🌿 Flora','fauna'=>'🐾 Fauna'] as $value=>$label)<label><input type="radio" name="category" value="{{ $value }}" @checked($category===$value)><span>{{ $label }}</span></label>@endforeach</fieldset>
    <div class="field"><label for="organism-q">Cari organisme</label><input id="organism-q" name="q" value="{{ request('q') }}" maxlength="100" placeholder="Nama umum atau nama ilmiah"></div>
    <div class="field"><label for="ecosystem-filter">Ekosistem</label><select id="ecosystem-filter" name="ecosystem"><option value="">Semua Ekosistem</option>@foreach($ecosystems as $e)<option value="{{ $e->slug }}" @selected(request('ecosystem')===$e->slug)>{{ $e->name }}</option>@endforeach</select></div><button class="button" type="submit">Tampilkan</button>
</form>
<div class="section-label"><h2>Mengenal {{ ucfirst($category) }}</h2><span>{{ $organisms->count() }} organisme ditemukan</span></div>
<div class="grid three">@forelse($organisms as $organism)<x-organism-card :organism="$organism" />@empty<div class="card empty-state"><h2>Belum ada hasil</h2><p>Coba nama lain atau pilih Semua Ekosistem.</p><a href="{{ route('organisms') }}">Atur ulang pencarian</a></div>@endforelse</div>
<p class="muted">Gambar merupakan ilustrasi pembelajaran. Kecocokan habitat bukan jaminan organisme ditemukan di setiap lokasi.</p>
@endsection
