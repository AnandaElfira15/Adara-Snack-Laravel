<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Memproses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            // Memperbarui sesi setelah login
            $request->session()->regenerate();

            // Mengarahkan pengguna sesuai role
            $user = Auth::user();

            if ($user->role === 'user') {
                return redirect()->route('products.index');
            }

            if (in_array($user->role, ['admin', 'staff'])) {
                return redirect()->route('dashboard');
            }

            // Keluar jika role tidak dikenali
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Role akun tidak dikenali.',
                ]);
        }

        // Jika email atau password salah
        return back()
            ->withErrors([
                'email' => 'Email atau password tidak sesuai.',
            ])
            ->onlyInput('email');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil keluar.');
    }
}
