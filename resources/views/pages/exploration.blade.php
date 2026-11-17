@extends('layouts.app')
@section('title','Eksplorasi')
@section('content')
<div class="page-heading"><span class="eyebrow">PETUALANGAN DIMULAI DI SINI</span><h1>Jelajahi Ekosistem Lahan Basah</h1><p>Pilih salah satu ekosistem untuk mulai belajar mengenal karakteristik, flora, fauna, dan keunikannya!</p></div>
@php
    // Coordinates refer to the complete map image, independent of database ordering.
    $habitatZones = [
        'rawa-gambut' => ['x'=>23, 'y'=>36, 'icon'=>'🌱', 'color'=>'#72513a', 'cue'=>'Air gelap dan hutan rapat'],
        'hutan-mangrove' => ['x'=>77, 'y'=>30, 'icon'=>'🌳', 'color'=>'#32744b', 'cue'=>'Akar tunjang dan tepian berlumpur'],
        'rawa-pasang-surut' => ['x'=>48, 'y'=>55, 'icon'=>'🌊', 'color'=>'#237e7b', 'cue'=>'Alur air bercabang dan genangan terhubung'],
        'rawa-lebak' => ['x'=>46, 'y'=>82, 'icon'=>'🌾', 'color'=>'#67852b', 'cue'=>'Genangan tenang, rumput rawa, dan teratai'],
        'sungai' => ['x'=>85, 'y'=>69, 'icon'=>'💧', 'color'=>'#226eaa', 'cue'=>'Aliran utama lebar dengan tepi yang jelas'],
    ];
@endphp
<section class="exploration-atlas" aria-label="Peta ilustrasi lima zona ekosistem">
    <figure class="habitat-figure">
        <div class="habitat-map">
            <img class="habitat-map-image" src="{{ asset('images/exploration-wetland-map.webp') }}" width="1672" height="941" alt="Peta ilustratif lima habitat: rawa gambut berair gelap di kiri atas, mangrove berakar tunjang di kanan atas, alur pasang surut di tengah, rawa lebak berteratai di bawah, dan sungai lebar di kanan.">
            @foreach($habitatZones as $slug=>$zone)
                @php($ecosystem = $ecosystems->firstWhere('slug', $slug))
                @if($ecosystem)
                <a class="habitat-pin" data-ecosystem="{{ $slug }}" style="--map-x:{{ $zone['x'] }}%;--map-y:{{ $zone['y'] }}%;--zone-color:{{ $zone['color'] }}" href="{{ route('ecosystem',$ecosystem) }}" aria-label="Jelajahi {{ $ecosystem->name }}: {{ $zone['cue'] }}">
                    <span class="habitat-pin-label"><span aria-hidden="true">{{ $zone['icon'] }}</span>{{ $ecosystem->name }}</span>
                    <span class="habitat-pin-dot" aria-hidden="true">{{ $loop->iteration }}</span>
                </a>
                @endif
            @endforeach
        </div>
        <figcaption class="habitat-caption"><span>Peta ilustrasi edukatif &middot; bukan peta geografis presisi</span><span>Pilih penanda atau nama habitat untuk menjelajah.</span></figcaption>
    </figure>
    <ul class="habitat-guide" aria-label="Kenali ciri lima habitat pada peta">
        @foreach($habitatZones as $slug=>$zone)
            @php($ecosystem = $ecosystems->firstWhere('slug', $slug))
            @if($ecosystem)
            <li><a href="{{ route('ecosystem',$ecosystem) }}" style="--zone-color:{{ $zone['color'] }}"><span class="habitat-guide-number" aria-hidden="true">{{ $loop->iteration }}</span><span><strong>{{ $ecosystem->name }}</strong><small>{{ $zone['cue'] }}</small></span><span class="habitat-guide-arrow" aria-hidden="true">&#8599;</span></a></li>
            @endif
        @endforeach
    </ul>
</section>
<section class="info-strip"><h2>&#9432; Tentang<br>Lahan Basah</h2><p>Lahan basah adalah wilayah daratan yang tergenang air, baik secara permanen maupun musiman, dan menjadi habitat beragam makhluk hidup.</p><a class="button" href="{{ route('about') }}">Pelajari Lebih Lanjut &rarr;</a></section>
@endsection
