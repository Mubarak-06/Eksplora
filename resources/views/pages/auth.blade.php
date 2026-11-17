@extends('layouts.app')
@section('title',$register?'Daftar':'Masuk')
@section('content')
<div class="auth-layout"><section class="auth-intro"><img src="{{ asset('images/logo.svg') }}" width="90" height="90" alt=""><span class="eyebrow">PETUALANGANMU MENUNGGU</span><h1>Kenali alam.<br>Kumpulkan pengalaman.</h1><p>Simpan progres belajar, raih lencana, dan jadilah penjaga lahan basah.</p><img class="rounded" src="{{ asset('images/hero-wetland.webp') }}" alt="Ilustrasi lingkungan lahan basah"></section>
<section class="card auth-card"><h2>{{ $register?'Buat Akun EKSPLORA':'Selamat Datang Kembali!' }}</h2><p>{{ $register?'Mulai perjalanan belajarmu hari ini.':'Masuk untuk melanjutkan petualanganmu.' }}</p>
@if(!$register)<p class="guest-note">Masuk diperlukan untuk menyimpan progres, melihat koleksi, dan mengelola profil.</p>@endif
<form method="post" action="{{ route($register?'register.store':'login.store') }}">@csrf
@if($register)<div class="field"><label for="name">Nama pengguna</label><input id="name" name="name" value="{{ old('name') }}" maxlength="80" autocomplete="nickname" required></div>@endif
<div class="field"><label for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required></div>
<div class="field"><label for="password">Kata sandi {{ $register?'(minimal 8 karakter)':'' }}</label><input type="password" id="password" name="password" autocomplete="{{ $register?'new-password':'current-password' }}" @if($register) minlength="8" @endif required></div>
@if($register)<div class="field"><label for="password_confirmation">Konfirmasi kata sandi</label><input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required></div>@endif
<button class="button full" type="submit">{{ $register?'Daftar':'Masuk' }} →</button>
</form><p>{{ $register?'Sudah punya akun?':'Belum punya akun?' }} <a href="{{ route($register?'login':'register') }}">{{ $register?'Masuk':'Daftar sekarang' }}</a></p></section></div>
@endsection
