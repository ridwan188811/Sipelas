<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $warga = Auth::guard('warga')->user();
        
        $displayName = $warga->name;
        if (!$displayName) {
            $displayName = explode('@', $warga->email)[0];
            $displayName = ucwords(str_replace(['.', '_', '-'], ' ', $displayName));
        }

        $totalPengajuan = PengajuanSurat::where('warga_id', $warga->id)->count();
        $menunggu = PengajuanSurat::where('warga_id', $warga->id)->where('status', 'menunggu')->count();
        $disetujui = PengajuanSurat::where('warga_id', $warga->id)->where('status', 'disetujui')->count();
        $ditolak = PengajuanSurat::where('warga_id', $warga->id)->where('status', 'ditolak')->count();

        $recentPengajuans = PengajuanSurat::where('warga_id', $warga->id)
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
