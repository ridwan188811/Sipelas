<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Menentukan nama dari email jika field name masih kosong
        $displayName = $user->name;
        if (!$displayName) {
            $displayName = explode('@', $user->email)[0];
            $displayName = ucwords(str_replace(['.', '_', '-'], ' ', $displayName));
        }

        // Statistik surat
        $totalPengajuan = PengajuanSurat::where('user_id', $user->id)->count();
        $menunggu = PengajuanSurat::where('user_id', $user->id)->where('status', 'menunggu')->count();
        $disetujui = PengajuanSurat::where('user_id', $user->id)->where('status', 'disetujui')->count();
        $ditolak = PengajuanSurat::where('user_id', $user->id)->where('status', 'ditolak')->count();

        // Mengambil riwayat terbaru, maksimal 5 data
        $recentPengajuans = PengajuanSurat::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('user.dashboard-user', compact(
            'displayName',
            'totalPengajuan',
            'menunggu',
            'disetujui',
            'ditolak',
            'recentPengajuans'
        ));
    }
}
