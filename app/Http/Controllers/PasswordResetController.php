<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Models\Warga;

class PasswordResetController extends Controller
{
    public function request()
    {
        return view('auth.forgot-password');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $warga = Warga::where('email', $request->email)->first();

        if (!$warga) {
            return back()->withErrors(['email' => 'Email tidak terdaftar dalam sistem (Hanya warga yang dapat mereset sandi).'])->onlyInput('email');
        }

        $url = URL::temporarySignedRoute(
            'password.reset.form',
            now()->addMinutes(30),
            ['email' => $request->email]
        );

        Mail::send('emails.lupa-kata-sandi', ['url' => $url, 'user' => $warga], function ($message) use ($warga) {
            $message->to($warga->email);
            $message->subject('Reset Kata Sandi');
        });

        return back()->with('success', 'Tautan untuk mereset kata sandi telah dikirim ke email Anda. Silakan cek kotak masuk atau folder spam.');
    }

    public function resetForm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Tautan reset kata sandi tidak valid atau sudah kedaluwarsa.');
        }

        if (!$request->email) {
            return redirect()->route('password.request');
        }
        
        return view('auth.reset-password', ['email' => $request->email]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:wargas,email',
            'password' => 'required|min:8',
            'password_confirm' => 'required|same:password',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi terlalu pendek, minimal 8 karakter.',
            'password_confirm.required' => 'Konfirmasi kata sandi wajib diisi.',
            'password_confirm.same' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $warga = Warga::where('email', $request->email)->first();
        
        if ($warga) {
            $warga->password = Hash::make($request->password);
            $warga->save();
            return redirect()->route('login')->with('success', 'Kata sandi berhasil diubah! Silakan masuk dengan kata sandi baru Anda.');
        }

        return back()->withErrors(['email' => 'Terjadi kesalahan saat memproses permintaan.'])->onlyInput('email');
    }
}
