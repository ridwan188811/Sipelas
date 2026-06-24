<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

class VerifikasiEmailController extends Controller
{
    public function show(Request $request)
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }
        
        $unverifiedEmail = $request->user()->email;
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with([
            'verify_notice_popup' => 'Akun Anda belum diverifikasi. Silakan cek email Anda.',
            'unverified_email' => $unverifiedEmail
        ]);
    }

    /**
     * Proses link verifikasi dari email
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = \App\Models\User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('verify_already_done', 'Halo! Email Anda sudah berhasil diverifikasi sebelumnya. Anda tidak perlu mengklik tombol di email lagi dan sudah bisa langsung login ke aplikasi.');
        }

        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));

        // Redirect ke halaman login dengan pop-up Pendaftaran Berhasil
        return redirect()->route('login')->with('register_verified', 'Email berhasil diverifikasi! Silakan login menggunakan akun Anda.');
    }

    /**
     * Kirim ulang email verifikasi (Khusus untuk Pop-up tanpa login)
     */
    public function resendCustom(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if ($user && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
        
        return redirect()->route('login')->with('verify_notice_popup', 'Jika email terdaftar, link verifikasi telah dikirim ulang ke email Anda.');
    }
}
