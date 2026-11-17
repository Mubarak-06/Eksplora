@extends('layouts.app')
@section('title',ucwords(str_replace('-',' ',$type)))
@section('content')
<div class="page-heading"><span class="eyebrow">KUIS EKSPLORA</span><h1>{{ ucwords(str_replace('-',' ',$type)) }}</h1><p>{{ $type==='puzzle'?'Klik atau tap dua potongan untuk menukar posisinya. Susun hingga menjadi gambar contoh.':'Pilih jawaban, periksa, lalu lanjutkan. Kamu bisa menggunakan keyboard atau layar sentuh.' }}</p></div>
<section id="game" class="card game" data-start="{{ route('quiz.start',$type) }}" data-answer="{{ route('quiz.answer') }}" data-finish="{{ route('quiz.finish') }}" data-login="{{ route('login') }}" data-retry="{{ route('quiz.play',$type) }}" data-collection="{{ route('collection') }}">
<p id="game-status" role="status" aria-live="polite">Siap untuk mencoba?</p><div id="game-content"></div><button id="game-start" class="button" type="button">Mulai Permainan</button>
</section>
@guest<p class="guest-note">Mode tamu: hasil tidak disimpan permanen. <a href="{{ route('login') }}">Masuk</a> untuk menyimpan hasil dan memperoleh lencana.</p>@endguest
<div class="action-row"><a class="button secondary" href="{{ route('quizzes') }}">← Kembali ke Kuis</a></div>
@endsection
@push('scripts')<script src="{{ asset('js/quiz.js') }}" defer></script>@endpush
