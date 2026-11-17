<?php

use App\Http\Controllers\AuthController as Auth;
use App\Http\Controllers\LearningController as Learn;
use App\Http\Controllers\QuizController as Quiz;
use Illuminate\Support\Facades\Route;

Route::get('/', [Learn::class, 'home'])->name('home');
Route::get('/eksplorasi', [Learn::class, 'exploration'])->name('exploration');
Route::get('/tentang-lahan-basah', [Learn::class, 'about'])->name('about');
Route::get('/eksplorasi/{ecosystem}', [Learn::class, 'ecosystem'])->name('ecosystem');
Route::get('/flora-fauna', [Learn::class, 'organisms'])->name('organisms');
Route::get('/flora-fauna/{organism}', [Learn::class, 'organism'])->name('organism');
Route::get('/perbandingan', [Learn::class, 'comparison'])->name('comparison');
Route::get('/pencarian', [Learn::class, 'search'])->name('search');
Route::get('/kuis', [Quiz::class, 'index'])->name('quizzes');
Route::get('/kuis/{type}', [Quiz::class, 'play'])->name('quiz.play');
Route::post('/permainan/{type}/mulai', [Quiz::class, 'start'])->middleware('throttle:30,1')->block(10, 10)->name('quiz.start');
Route::post('/permainan/jawab', [Quiz::class, 'answer'])->block(10, 10)->name('quiz.answer');
Route::post('/permainan/selesai', [Quiz::class, 'finish'])->block(10, 10)->name('quiz.finish');
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [Auth::class, 'login'])->name('login');
    Route::post('/masuk', [Auth::class, 'authenticate'])->middleware('throttle:6,1')->name('login.store');
    Route::get('/daftar', [Auth::class, 'register'])->name('register');
    Route::post('/daftar', [Auth::class, 'store'])->middleware('throttle:6,1')->name('register.store');
});
Route::middleware('auth')->group(function () {
    Route::post('/progres', [Learn::class, 'complete'])->name('progress');
    Route::get('/koleksi', [Learn::class, 'collection'])->name('collection');
    Route::get('/profil', [Learn::class, 'profile'])->name('profile');
    Route::patch('/profil', [Auth::class, 'update'])->name('profile.update');
    Route::post('/keluar',[Auth::class, 'logout'])->name('logout');
});
