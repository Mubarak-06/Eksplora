<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.auth', ['register' => false]);
    }

    public function register()
    {
        return view('pages.auth', ['register' => true]);
    }

    public function authenticate(Request $r)
    {
        $d = $r->validate(['email' => 'required|email|max:255', 'password' => 'required|string']);
        if (! Auth::attempt($d)) {
            return back()->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])->onlyInput('email');
        }
        $r->session()->regenerate();

        return redirect()->intended(route('profile'));
    }

    public function store(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:80', 'email' => 'required|email|max:255|unique:users', 'password' => ['required', 'confirmed', Password::min(8)]]);
        Auth::login(User::create($d));
        $r->session()->regenerate();

        return redirect()->route('profile')->with('status', 'Selamat datang di EKSPLORA!');
    }

    public function update(Request $r)
    {
        $r->user()->update($r->validate(['name' => 'required|string|max:80']));

        return back()->with('status', 'Profil diperbarui.');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();

        return redirect()->route('home');
    }
}
