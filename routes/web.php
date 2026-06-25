<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ValidasiController;

// Public Validasi Route (Tanpa Login)
Route::get('/validasi/{token}', [ValidasiController::class, 'show'])->name('validasi');

// Authentication Routes (Hanya untuk tamu/belum login)
Route::get('/setup-admin', function () {
    $user = \App\Models\User::updateOrCreate(
        ['email' => 'admin@gmail.com'],
        [
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]
    );
    \Illuminate\Support\Facades\Auth::login($user);
    return redirect()->route('admin.dashboard')->with('success', 'Berhasil login sebagai Admin!');
});

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'request'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'verifyEmail'])->middleware('guest')->name('password.verify');
Route::get('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'resetForm'])->name('password.reset.form');
Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])->name('password.update');

// Rute Verifikasi Email (Tanpa Auth untuk verify, hapus 'signed' agar bisa diuji via HP/IP yang berbeda)
Route::get('/email/verifikasi/{id}/{hash}', [\App\Http\Controllers\VerifikasiEmailController::class, 'verify'])->middleware(['throttle:6,1'])->name('verification.verify');

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verifikasi', [\App\Http\Controllers\VerifikasiEmailController::class, 'show'])->name('verification.notice');
    Route::post('/email/verifikasi/kirim-ulang', [\App\Http\Controllers\VerifikasiEmailController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');
});

Route::post('/email/resend-custom', [\App\Http\Controllers\VerifikasiEmailController::class, 'resendCustom'])->middleware('throttle:6,1')->name('verification.resend.custom');

Route::middleware(['auth'])->group(function () {
    Route::get('/check-notif', [\App\Http\Controllers\NotificationController::class, 'check'])->name('check-notif');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/mark-notif-read', [\App\Http\Controllers\AdminPengajuanController::class, 'markNotifRead'])->name('mark-notif-read');
        Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/verifikasi-surat', [\App\Http\Controllers\AdminPengajuanController::class, 'index'])->name('daftar-pengajuan');
        Route::get('/riwayat-pengajuan', [\App\Http\Controllers\AdminPengajuanController::class, 'riwayat'])->name('riwayat-pengajuan');
        Route::get('/profil', function () { return view('admin.profil-admin'); })->name('profil');
        Route::get('/ubah-kata-sandi', function () {
            return view('admin.ubah-kata-sandi');
        })->name('ubah-kata-sandi');
        Route::post('/update-kata-sandi', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'password' => 'required|min:8',
                'password_confirm' => 'required|same:password',
            ], [
                'password.required' => 'Kata sandi wajib diisi.',
                'password.min' => 'Kata sandi terlalu pendek, minimal 8 karakter.',
                'password_confirm.required' => 'Konfirmasi kata sandi wajib diisi.',
                'password_confirm.same' => 'Konfirmasi kata sandi tidak cocok.',
            ]);
            $user = \Illuminate\Support\Facades\Auth::user();
            if (\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'Kata sandi baru tidak boleh sama dengan kata sandi saat ini.']);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $user->save();
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('success', 'Kata sandi berhasil diubah! Silakan masuk kembali.');
        })->name('update-kata-sandi');
        Route::post('/profil', function (\Illuminate\Http\Request $request) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'jabatan' => 'nullable|string|max:100',
            ]);
            
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->has('jabatan')) $user->jabatan = $request->jabatan;
            $user->save();
            return back()->with('success', 'Profil berhasil diperbarui!');
        })->name('profil.update');
        Route::get('/detail-pengajuan/{id}', [\App\Http\Controllers\AdminPengajuanController::class, 'show'])->name('detail-pengajuan');
        Route::post('/detail-pengajuan/{id}/proses', [\App\Http\Controllers\AdminPengajuanController::class, 'proses'])->name('proses-pengajuan');
        Route::post('/send-email-notification', [\App\Http\Controllers\AdminPengajuanController::class, 'sendEmailNotification'])->name('send-email-notification');
        Route::get('/export-excel', [\App\Http\Controllers\AdminPengajuanController::class, 'exportExcel'])->name('export-excel');

    });

    // User Routes
    Route::prefix('user')->name('user.')->middleware('verified')->group(function () {
        Route::post('/mark-notif-read', [\App\Http\Controllers\PengajuanSuratController::class, 'markNotifRead'])->name('mark-notif-read');
        Route::get('/dashboard', [\App\Http\Controllers\UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/ajukan-surat', function (\Illuminate\Http\Request $request) {
            if (!\Illuminate\Support\Facades\Auth::user()->isProfileComplete()) {
                return redirect()->route('user.profil')->with('error', 'Silakan lengkapi profil biodata KTP Anda terlebih dahulu sebelum mengajukan surat.');
            }
            $resubmitData = null;
            if ($request->filled('resubmit_id')) {
                $resubmitData = \App\Models\PengajuanSurat::where('id', $request->resubmit_id)
                    ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->where('status', 'ditolak')
                    ->first();
            }
            return view('user.ajukan-surat', compact('resubmitData'));
        })->name('ajukan-surat');
        Route::post('/ajukan-surat', [\App\Http\Controllers\PengajuanSuratController::class, 'store'])->name('ajukan-surat.submit');
        Route::get('/riwayat', [\App\Http\Controllers\PengajuanSuratController::class, 'riwayat'])->name('riwayat');
        Route::get('/profil', function () { return view('user.profil-warga'); })->name('profil');
        Route::get('/ubah-kata-sandi', function () {
            return view('user.ubah-kata-sandi');
        })->name('ubah-kata-sandi');
        Route::post('/update-kata-sandi', function (\Illuminate\Http\Request $request) {
            $request->validate([
                'password' => 'required|min:8',
                'password_confirm' => 'required|same:password',
            ], [
                'password.required' => 'Kata sandi wajib diisi.',
                'password.min' => 'Kata sandi terlalu pendek, minimal 8 karakter.',
                'password_confirm.required' => 'Konfirmasi kata sandi wajib diisi.',
                'password_confirm.same' => 'Konfirmasi kata sandi tidak cocok.',
            ]);
            $user = \Illuminate\Support\Facades\Auth::user();
            if (\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'Kata sandi baru tidak boleh sama dengan kata sandi saat ini.']);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $user->save();
            \Illuminate\Support\Facades\Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('success', 'Kata sandi berhasil diubah! Silakan masuk kembali.');
        })->name('update-kata-sandi');
        Route::post('/profil', function (\Illuminate\Http\Request $request) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $request->validate([
                'name' => 'required|string|max:255',
                'no_hp' => 'nullable|string|max:20',
                'nik' => 'nullable|string|max:16|unique:users,nik,' . $user->id,
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
            ], [
                'required' => 'Kolom :attribute wajib diisi.',
                'email' => 'Format email tidak valid.',
                'unique' => ':attribute sudah terdaftar, silakan gunakan yang lain.',
                'max' => 'Kolom :attribute maksimal :max karakter.',
                'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
                'in' => 'Pilihan :attribute tidak valid.',
                'name.required' => 'Nama lengkap wajib diisi.',
                'email.required' => 'Email wajib diisi.',
            ]);
            
            $user->fill($request->only([
                'name', 'no_hp', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'kewarganegaraan', 'agama', 'pekerjaan', 'status_pernikahan', 'alamat_lengkap',
                'rt', 'rw', 'kelurahan', 'kecamatan', 'kota'
            ]));
            
            $user->save();
            return back()->with('success', 'Profil berhasil diperbarui!');
        })->name('profil.update');
        Route::get('/detail-pengajuan/{id}', [\App\Http\Controllers\PengajuanSuratController::class, 'show'])->name('detail-pengajuan');
        Route::delete('/pengajuan/{id}', [\App\Http\Controllers\PengajuanSuratController::class, 'destroy'])->name('pengajuan.destroy');

    });
    
    // Shared Routes (Cetak PDF, Preview, & Notif)
    Route::get('/preview-dokumen', function (Request $request) {
        if (!$request->has('path')) abort(404);
        $path = $request->query('path');
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists($fullPath)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $mime = 'application/octet-stream';
        
        if ($ext === 'pdf') {
            $mime = 'application/pdf';
        } elseif (in_array($ext, ['jpg', 'jpeg'])) {
            $mime = 'image/jpeg';
        } elseif ($ext === 'png') {
            $mime = 'image/png';
        }

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.basename($fullPath).'"'
        ]);
    })->name('preview.dokumen');

    Route::get('/pengajuan/{id}/cetak-surat', [\App\Http\Controllers\CetakSuratController::class, 'cetakPdf'])->name('cetak-surat');
    Route::get('/check-notif', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) return response()->json(['count' => 0, 'list' => []]);
        
        if ($user->role === 'admin') {
            $notifList = \App\Models\PengajuanSurat::with('user')->where('status', 'menunggu')->orderBy('created_at', 'desc')->take(5)->get();
            $notifCount = \App\Models\PengajuanSurat::where('status', 'menunggu')->count();
        } else {
            $notifList = \App\Models\PengajuanSurat::where('user_id', $user->id)
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
            $notifCount = \App\Models\PengajuanSurat::where('user_id', $user->id)
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->where('is_read_by_user', false)
                ->count();
        }
        $lastUpdated = \App\Models\PengajuanSurat::max('updated_at');

        return response()->json([
            'count' => $notifCount,
            'last_updated' => $lastUpdated,
            'list' => $notifList->map(function ($notif) use ($user) {
                $route = $user->role === 'admin' ? route('admin.detail-pengajuan', $notif->id) : route('user.detail-pengajuan', $notif->id);
                $jenisMap = ['sku'=>'Surat Keterangan Usaha','sktm'=>'Surat Keterangan Tidak Mampu','sktm-sekolah'=>'Surat Keterangan Tidak Mampu (Sekolah)','domisili'=>'Surat Keterangan Domisili','belum-menikah'=>'Surat Keterangan Belum Menikah','kelahiran'=>'Surat Keterangan Kelahiran','kematian'=>'Surat Keterangan Kematian','pengantar-nikah'=>'Surat Pengantar Nikah','pindah'=>'Surat Keterangan Pindah'];
                $jenis = $jenisMap[$notif->jenis_surat] ?? ucwords(str_replace('-', ' ', $notif->jenis_surat));
                
                if ($user->role === 'admin') {
                    $title = "Pengajuan Baru: $jenis";
                    $desc = "Dari: " . ($notif->user->name ?? explode('@', $notif->user->email)[0]);
                } else {
                    $title = "Surat $jenis " . ucfirst($notif->status);
                    $desc = "Pengajuan surat Anda telah {$notif->status} oleh kelurahan.";
                }
                
                return [
                    'id' => $notif->id,
                    'url' => $route,
                    'title' => $title,
                    'desc' => $desc,
                    'time' => \Carbon\Carbon::parse($notif->updated_at)->translatedFormat('d M Y, H:i')
                ];
            })
        ]);
    })->name('check-notif');
});

Route::get('/setup-database', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
        return 'Database successfully migrated and seeded!';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::get('/delete-users', function () {
    try {
        $count = \App\Models\User::where('role', '!=', 'admin')->delete();
        return "Berhasil menghapus $count akun pengguna (selain admin).";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

