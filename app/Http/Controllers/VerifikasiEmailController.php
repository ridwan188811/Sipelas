<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

class VerifikasiEmailController extends Controller
{
    public function show(Request $request)
    {
        $warga = Auth::guard('warga')->user();
        if ($warga && $warga->hasVerifiedEmail()) {
            return redirect()->route('user.dashboard');
        }
        
        $unverifiedEmail = $warga ? $warga->email : '';
        
        Auth::guard('warga')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with([
            'verify_notice_popup' => 'Akun Anda belum diverifikasi. Silakan cek email Anda.',
            'unverified_email' => $unverifiedEmail
        ]);
    }

    public function verify(Request $request, $id, $hash)
    {
        $warga = \App\Models\Warga::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($warga->getEmailForVerification()))) {
            abort(403);
        }

        if ($warga->hasVerifiedEmail()) {
            return redirect()->route('login')->with('verify_already_done', 'Halo! Email Anda sudah berhasil diverifikasi sebelumnya. Anda tidak perlu mengklik tombol di email lagi dan sudah bisa langsung login ke aplikasi.');
        }

        $warga->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($warga));

        return redirect()->route('login')->with('register_verified', 'Email berhasil diverifikasi! Silakan login menggunakan akun Anda.');
    }

    public function resendCustom(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $warga = \App\Models\Warga::where('email', $request->email)->first();
        
        if ($warga && !$warga->hasVerifiedEmail()) {
            $warga->sendEmailVerificationNotification();
        }
        
        return redirect()->route('login')->with('verify_notice_popup', 'Jika email terdaftar, link verifikasi telah dikirim ulang ke email Anda.');
    }
}
