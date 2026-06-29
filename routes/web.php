<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ValidasiController;

Route::get('/validasi/{token}', [ValidasiController::class, 'show'])->name('validasi');

Route::get('/setup-admin', function () {
    $admin = \App\Models\Admin::updateOrCreate(
        ['email' => 'admin@gmail.com'],
        [
            'name' => 'Admin SIPELAS',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'email_verified_at' => now(),
        ]
    );
    \Illuminate\Support\Facades\Auth::guard('admin')->login($admin);
    return redirect()->route('admin.dashboard')->with('success', 'Berhasil login sebagai Admin!');
});

Route::middleware('guest:warga,admin')->group(function () {
    Route::get('/', function () { return view('auth.login'); })->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::get('/confirm-password-change/{user}', function (Request $request, $user) {
    if (! $request->hasValidSignature()) {
        return redirect()->route('login')->with('error', 'Link konfirmasi tidak valid atau sudah kedaluwarsa.');
    }
    
    $warga = \App\Models\Warga::find($user);
    if($warga) {
        $warga->password = base64_decode($request->query('hash'));
        $warga->save();
    } else {
        $admin = \App\Models\Admin::find($user);
        if($admin) {
            $admin->password = base64_decode($request->query('hash'));
            $admin->save();
        }
    }
    return redirect()->route('login')->with('success', 'Kata sandi berhasil diubah! Silakan masuk dengan kata sandi baru Anda.');
})->name('password.confirm.change');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'request'])->middleware('guest:warga,admin')->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'verifyEmail'])->middleware('guest:warga,admin')->name('password.verify');
Route::get('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'resetForm'])->name('password.reset.form');
Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])->name('password.update');

Route::get('/email/verifikasi/{id}/{hash}', [\App\Http\Controllers\VerifikasiEmailController::class, 'verify'])->middleware(['throttle:6,1'])->name('verification.verify');

Route::middleware('auth:warga')->group(function () {
    Route::get('/email/verifikasi', [\App\Http\Controllers\VerifikasiEmailController::class, 'show'])->name('verification.notice');
    Route::post('/email/verifikasi/kirim-ulang', [\App\Http\Controllers\VerifikasiEmailController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');
});

Route::post('/email/resend-custom', [\App\Http\Controllers\VerifikasiEmailController::class, 'resendCustom'])->middleware('throttle:6,1')->name('verification.resend.custom');

Route::get('/check-notif', [\App\Http\Controllers\NotificationController::class, 'check'])->name('check-notif');

// Shared Routes
Route::get('/preview-dokumen', function (Request $request) {
    if (!$request->has('path')) abort(404);
    $path = $request->query('path');
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) abort(404, 'Dokumen tidak ditemukan.');
    $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
    $mime = 'application/octet-stream';
    if ($ext === 'pdf') $mime = 'application/pdf';
    elseif (in_array($ext, ['jpg', 'jpeg'])) $mime = 'image/jpeg';
    elseif ($ext === 'png') $mime = 'image/png';
    return response()->file($fullPath, ['Content-Type' => $mime, 'Content-Disposition' => 'inline; filename="'.basename($fullPath).'"']);
})->name('preview.dokumen');

Route::get('/pengajuan/{id}/cetak-surat', [\App\Http\Controllers\CetakSuratController::class, 'cetakPdf'])->name('cetak-surat');

// Admin Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/mark-notif-read', [\App\Http\Controllers\AdminPengajuanController::class, 'markNotifRead'])->name('mark-notif-read');
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/verifikasi-surat', [\App\Http\Controllers\AdminPengajuanController::class, 'index'])->name('daftar-pengajuan');
    Route::get('/riwayat-pengajuan', [\App\Http\Controllers\AdminPengajuanController::class, 'riwayat'])->name('riwayat-pengajuan');
    Route::get('/profil', function () { return view('admin.profil-admin'); })->name('profil');
    Route::get('/ubah-kata-sandi', function () { return view('admin.ubah-kata-sandi'); })->name('ubah-kata-sandi');
    Route::post('/update-kata-sandi', function (\Illuminate\Http\Request $request) {
        $request->validate(['password' => 'required|min:8', 'password_confirm' => 'required|same:password']);
        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
        if (\Illuminate\Support\Facades\Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['password' => 'Kata sandi baru tidak boleh sama dengan kata sandi saat ini.']);
        }
        $newHash = \Illuminate\Support\Facades\Hash::make($request->password);
        $payload = base64_encode($newHash);
        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute('password.confirm.change', now()->addMinutes(30), ['user' => $admin->id, 'hash' => $payload]);
        \Illuminate\Support\Facades\Mail::send('emails.konfirmasi-ubah-sandi', ['url' => $url, 'user' => $admin], function ($message) use ($admin) {
            $message->to($admin->email);
            $message->subject('Konfirmasi Perubahan Kata Sandi');
        });
        \Illuminate\Support\Facades\Auth::guard('admin')->logout();
        return redirect()->route('login')->with('success', 'Link konfirmasi telah dikirim ke email Anda.');
    })->name('update-kata-sandi');
    Route::post('/profil', function (\Illuminate\Http\Request $request) {
        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'jabatan' => 'nullable|string|max:100',
        ]);
        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->has('jabatan')) $admin->jabatan = $request->jabatan;
        $admin->save();
        return back()->with('success', 'Profil berhasil diperbarui!');
    })->name('profil.update');
    Route::get('/detail-pengajuan/{id}', [\App\Http\Controllers\AdminPengajuanController::class, 'show'])->name('detail-pengajuan');
    Route::post('/detail-pengajuan/{id}/proses', [\App\Http\Controllers\AdminPengajuanController::class, 'proses'])->name('proses-pengajuan');
    Route::post('/send-email-notification', [\App\Http\Controllers\AdminPengajuanController::class, 'sendEmailNotification'])->name('send-email-notification');
    Route::get('/export-excel', [\App\Http\Controllers\AdminPengajuanController::class, 'exportExcel'])->name('export-excel');
});

// User Routes
Route::middleware(['auth:warga', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::post('/mark-notif-read', [\App\Http\Controllers\PengajuanSuratController::class, 'markNotifRead'])->name('mark-notif-read');
    Route::get('/dashboard', [\App\Http\Controllers\UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/ajukan-surat', function (\Illuminate\Http\Request $request) {
        if (!\Illuminate\Support\Facades\Auth::guard('warga')->user()->isProfileComplete()) {
            return redirect()->route('user.profil')->with('error', 'Silakan lengkapi profil biodata KTP Anda terlebih dahulu sebelum mengajukan surat.');
        }
        $resubmitData = null;
        if ($request->filled('resubmit_id')) {
            $resubmitData = \App\Models\PengajuanSurat::where('id', $request->resubmit_id)
                ->where('warga_id', \Illuminate\Support\Facades\Auth::guard('warga')->id())
                ->where('status', 'ditolak')
                ->first();
        }
        return view('user.ajukan-surat', compact('resubmitData'));
    })->name('ajukan-surat');
    Route::post('/ajukan-surat', [\App\Http\Controllers\PengajuanSuratController::class, 'store'])->name('ajukan-surat.submit');
    Route::get('/riwayat', [\App\Http\Controllers\PengajuanSuratController::class, 'riwayat'])->name('riwayat');
    Route::get('/profil', function () { return view('user.profil-warga'); })->name('profil');
    Route::get('/ubah-kata-sandi', function () { return view('user.ubah-kata-sandi'); })->name('ubah-kata-sandi');
    Route::post('/update-kata-sandi', function (\Illuminate\Http\Request $request) {
        $request->validate(['password' => 'required|min:8', 'password_confirm' => 'required|same:password']);
        $warga = \Illuminate\Support\Facades\Auth::guard('warga')->user();
        if (\Illuminate\Support\Facades\Hash::check($request->password, $warga->password)) {
            return back()->withErrors(['password' => 'Kata sandi baru tidak boleh sama dengan kata sandi saat ini.']);
        }
        $newHash = \Illuminate\Support\Facades\Hash::make($request->password);
        $payload = base64_encode($newHash);
        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute('password.confirm.change', now()->addMinutes(30), ['user' => $warga->id, 'hash' => $payload]);
        \Illuminate\Support\Facades\Mail::send('emails.konfirmasi-ubah-sandi', ['url' => $url, 'user' => $warga], function ($message) use ($warga) {
            $message->to($warga->email);
            $message->subject('Konfirmasi Perubahan Kata Sandi');
        });
        \Illuminate\Support\Facades\Auth::guard('warga')->logout();
        return redirect()->route('login')->with('success', 'Link konfirmasi telah dikirim ke email Anda.');
    })->name('update-kata-sandi');
    Route::post('/profil', function (\Illuminate\Http\Request $request) {
        $warga = \Illuminate\Support\Facades\Auth::guard('warga')->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:16|unique:wargas,nik,' . $warga->id,
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:l,p',
            'kewarganegaraan' => 'nullable|string|max:100',
            'agama' => 'nullable|string|max:50',
            'pekerjaan' => 'nullable|string|max:100',
            'status_pernikahan' => 'nullable|string|max:50',
            'alamat_lengkap' => 'nullable|string|max:500',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'kelurahan' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kota' => 'nullable|string|max:100',
        ]);
        $warga->fill($request->only(['name', 'no_hp', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'kewarganegaraan', 'agama', 'pekerjaan', 'status_pernikahan', 'alamat_lengkap', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kota']));
        $warga->save();
        return back()->with('success', 'Profil berhasil diperbarui!');
    })->name('profil.update');
    Route::get('/detail-pengajuan/{id}', [\App\Http\Controllers\PengajuanSuratController::class, 'show'])->name('detail-pengajuan');
    Route::delete('/pengajuan/{id}', [\App\Http\Controllers\PengajuanSuratController::class, 'destroy'])->name('pengajuan.destroy');
});

Route::get('/setup-database', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true]);
        return 'Database successfully migrated!';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});
