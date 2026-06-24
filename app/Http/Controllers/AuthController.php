<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'password_confirm' => 'required|same:password',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email salah.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi terlalu pendek, minimal 8 karakter.',
            'password_confirm.required' => 'Konfirmasi kata sandi wajib diisi.',
            'password_confirm.same' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'warga',
        ]);

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        // Redirect ke halaman login dengan popup
        return redirect()->route('login')->with([
            'verify_notice_popup' => 'Pendaftaran berhasil! Silakan cek email Anda untuk memverifikasi akun.',
            'unverified_email' => $user->email
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Hapus redirect ke intended jika tersimpan url notif
            if (session()->get('url.intended') == url('/check-notif')) {
                session()->forget('url.intended');
            }

            // Selalu paksa arahkan ke dashboard masing-masing sesuai role untuk mencegah bug session intended url menyilang
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang di Dashboard Admin.');
            }
            
            return redirect()->route('user.dashboard')->with('success', 'Login berhasil! Selamat datang di SIPELAS.');
        }

        return back()->withErrors([
            'password' => 'Kata sandi salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
