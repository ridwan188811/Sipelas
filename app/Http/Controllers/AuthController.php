<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warga;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:wargas',
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

        $warga = Warga::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $warga->sendEmailVerificationNotification();

        return redirect()->route('login')->with([
            'verify_notice_popup' => 'Pendaftaran berhasil! Silakan cek email Anda untuk memverifikasi akun.',
            'unverified_email' => $warga->email
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil! Selamat datang di Dashboard Admin.');
        }

        if (Auth::guard('warga')->attempt($credentials)) {
            $request->session()->regenerate();
            if (session()->get('url.intended') == url('/check-notif')) {
                session()->forget('url.intended');
            }
            return redirect()->route('user.dashboard')->with('success', 'Login berhasil! Selamat datang di SIPELAS.');
        }

        return back()->withErrors([
            'email' => 'Email atau Kata sandi salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if(Auth::guard('admin')->check()) { Auth::guard('admin')->logout(); }
        if(Auth::guard('warga')->check()) { Auth::guard('warga')->logout(); }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
