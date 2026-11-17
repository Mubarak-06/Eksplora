<header class="navbar">
    <a class="brand" href="{{ route('home') }}"><img src="{{ asset('images/logo.svg') }}" width="44" height="44" alt="">EKSPLORA</a>
    <button class="menu-toggle icon-button" type="button" aria-controls="navigation" aria-expanded="false" aria-label="Buka navigasi">☰</button>
    <nav id="navigation" aria-label="Navigasi utama">
        @foreach([
            ['home','Beranda',['home']],['exploration','Eksplorasi',['exploration','ecosystem','about']],
            ['organisms','Flora & Fauna',['organisms','organism']],['comparison','Perbandingan',['comparison']],
            ['quizzes','Kuis',['quizzes','quiz.play']],['collection','Koleksi',['collection']],['profile','Profil',['profile']]
        ] as [$route,$label,$active])
        <a href="{{ route($route) }}" @class(['nav-link','active'=>request()->routeIs(...$active)]) @if(request()->routeIs(...$active)) aria-current="page" @endif>{{ $label }}</a>
        @endforeach
    </nav>
    <button class="icon-button search-toggle" type="button" aria-label="Buka pencarian" aria-expanded="false" aria-controls="nav-search"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="10" cy="10" r="7"/><path d="m15 15 7 7"/></svg></button>
    <a class="avatar" href="{{ auth()->check() ? route('profile') : route('login') }}" aria-label="{{ auth()->check() ? 'Profil '.auth()->user()->name : 'Masuk akun' }}">{{ auth()->check() ? mb_substr(auth()->user()->name,0,1) : '☺' }}</a>
    <form id="nav-search" class="nav-search" action="{{ route('search') }}" hidden>
        <label for="global-q">Cari ekosistem, flora, atau fauna</label><div class="inline"><input id="global-q" name="q" maxlength="100" placeholder="Misalnya: haruan" required><button class="button" type="submit">Cari</button></div>
    </form>
</header>
