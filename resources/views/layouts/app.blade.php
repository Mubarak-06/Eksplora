<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EKSPLORA') · Ekosistem Lahan Basah</title>
    <meta name="description" content="Media pembelajaran interaktif ekosistem lahan basah Kalimantan Selatan untuk siswa kelas VII.">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="@yield('body-class')">
<a class="skip-link" href="#main">Lewati ke konten</a>
<div class="site-shell">
    <x-navbar />
    <main id="main" class="@yield('main-class', 'container')">
        @if(session('status'))<div class="notice" role="status">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="notice error" role="alert"><strong>Periksa kembali isianmu.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @yield('content')
    </main>
    @unless(request()->routeIs('home'))<footer>🌿 EKSPLORA <span>Belajar mengenal, tumbuh untuk menjaga.</span><small>IPA · SMP/MTs Kelas VII · Kalimantan Selatan</small></footer>@endunless
</div>
@stack('scripts')
</body>
</html>
