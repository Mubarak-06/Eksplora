@extends('layouts.app')
@section('title','Kuis EKSPLORA')
@section('content')
<section class="banner"><div><span class="eyebrow">BELAJAR SAMBIL BERMAIN</span><h1>Kuis EKSPLORA</h1><p>Uji pemahamanmu tentang ekosistem lahan basah, flora, dan fauna melalui berbagai jenis kuis yang seru!</p></div><img src="{{ asset('images/hero-wetland.webp') }}" alt="Ilustrasi habitat lahan basah"></section>
<div class="grid two quiz-grid">
@foreach([
    ['pilihan-ganda','Pilihan Ganda','Jawab pertanyaan tentang ekosistem, organisme, dan pelestariannya.','📋','5 soal · 4 pilihan'],
    ['tebak-gambar','Tebak Gambar','Kenali flora dan fauna melalui ilustrasi ciri khasnya.','🔎','5 ilustrasi · 4 pilihan'],
    ['mencocokkan','Mencocokkan','Pasangkan organisme dengan salah satu habitat yang sesuai.','🔗','5 pasangan · klik atau tap'],
    ['puzzle','Puzzle','Susun kembali potongan ilustrasi ekosistem menjadi gambar utuh.','🧩','9 potongan · tukar posisi']
] as [$slug,$name,$description,$icon,$meta])<article class="card quiz-card"><div class="quiz-art" aria-hidden="true"><span>{{ $icon }}</span><i>✦</i></div><div><span class="eyebrow">{{ $meta }}</span><h2>{{ $name }}</h2><p>{{ $description }}</p><a class="button" href="{{ route('quiz.play',$slug) }}">▶ Mulai Kuis</a></div></article>@endforeach
</div>
<aside class="info-strip"><h2>🌟 Belajar jadi<br>lebih bermakna</h2><p>Nilai dihitung dari jawabanmu. Poin kuis mengikuti hasil terbaik tiap jenis permainan, maksimal 20 poin per jenis dan 15 poin untuk puzzle.</p>@guest<a class="button secondary" href="{{ route('login') }}">Masuk & Simpan Hasil</a>@else<a class="button secondary" href="{{ route('collection') }}">Lihat Koleksi →</a>@endguest</aside>
@endsection
