@extends('layouts.app')
@section('title','Perbandingan Ekosistem')
@section('content')
<section class="banner"><div><span class="eyebrow">AMATI PERSAMAAN DAN PERBEDAANNYA</span><h1>Perbandingan Ekosistem</h1><p>Setiap ekosistem punya cerita. Pilih dua habitat dan temukan apa yang membuatnya berbeda.</p></div><img src="{{ asset('images/sungai.webp') }}" alt="Ilustrasi aliran sungai"></section>
<form class="card comparison-form" action="{{ route('comparison') }}" method="get">
    <div class="field"><label for="first">Ekosistem pertama</label><select id="first" name="first">@foreach($ecosystems as $e)<option value="{{ $e->slug }}" @selected($first->id===$e->id)>{{ $e->name }}</option>@endforeach</select></div>
    <button type="button" class="icon-button swap" aria-label="Tukar posisi ekosistem">⇄</button>
    <div class="field"><label for="second">Ekosistem kedua</label><select id="second" name="second">@foreach($ecosystems as $e)<option value="{{ $e->slug }}" @selected($second->id===$e->id)>{{ $e->name }}</option>@endforeach</select></div><button class="button" type="submit">Bandingkan</button>
</form>
<div class="grid two comparison-cards">@foreach([$first,$second] as $e)<article class="card"><img class="card-cover" src="{{ asset(file_exists(public_path('images/'.$e->slug.'.webp')) ? 'images/'.$e->slug.'.webp' : $e->image) }}" alt="Ilustrasi {{ $e->name }}"><div class="card-body"><h2>{{ $e->name }}</h2><p>{{ $e->summary }}</p></div></article>@endforeach</div>
<div class="card table-wrapper"><table class="comparison-table"><caption>Perbandingan {{ $first->name }} dan {{ $second->name }}</caption><thead><tr><th scope="col">Aspek</th><th scope="col">{{ $first->name }}</th><th scope="col">{{ $second->name }}</th></tr></thead><tbody>@foreach(['water'=>'💧 Kondisi Air','vegetation'=>'🌿 Vegetasi','fauna'=>'🐾 Fauna','characteristics'=>'⛰ Karakteristik'] as $key=>$label)<tr><th scope="row">{{ $label }}</th>@foreach([$first,$second] as $e)<td>{{ $key==='fauna'?($e->organisms()->where('category','fauna')->pluck('name')->join(', ') ?: 'Contoh fauna lokal masih perlu verifikasi.'): $e->content[$key] }}</td>@endforeach</tr>@endforeach</tbody></table></div>
<div class="action-row"><x-complete activity="comparison" subject="first" :first="$first->slug" :second="$second->slug" label="Simpan Pembelajaran Perbandingan" /></div>
@endsection
