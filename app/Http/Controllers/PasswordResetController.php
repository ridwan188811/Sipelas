<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PasswordResetController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function request()
    {
        return view('auth.forgot-password');
    }

    /**
     * Verify email and redirect to reset form.
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar dalam sistem.'])->onlyInput('email');
        }

        return redirect()->route('password.reset.form', ['email' => $request->email]);
    }

    /**
     * Display the password reset view.
     */
    public function resetForm(Request $request)
    {
        if (!$request->email) {
            return redirect()->route('password.request');
        }
        
        return view('auth.reset-password', ['email' => $request->email]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
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

        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('login')->with('success', 'Kata sandi berhasil diubah! Silakan masuk dengan kata sandi baru Anda.');
        }

        return back()->withErrors(['email' => 'Terjadi kesalahan saat memproses permintaan.'])->onlyInput('email');
    }
}
