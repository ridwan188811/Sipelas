<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        $displayName = $admin->name;
        if (!$displayName) {
            $displayName = explode('@', $admin->email)[0];
            $displayName = ucwords(str_replace(['.', '_', '-'], ' ', $displayName));
        }

        $totalPengajuan = PengajuanSurat::count();
        $menunggu = PengajuanSurat::where('status', 'menunggu')->count();
        $disetujui = PengajuanSurat::where('status', 'disetujui')->count();
        $ditolak = PengajuanSurat::where('status', 'ditolak')->count();

        $menungguList = PengajuanSurat::with('warga')
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $riwayatTerbaru = PengajuanSurat::with('warga')
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
            
        $jenisStatRaw = PengajuanSurat::select('jenis_surat', \DB::raw('count(*) as total'))
                  ->groupBy('jenis_surat')
                  ->orderBy('total', 'desc')
                  ->get();
        
        $jenisStat = [];
        $colors = ['red', 'yellow', 'gray'];
        foreach($jenisStatRaw as $index => $stat) {
            $percentage = $totalPengajuan > 0 ? round(($stat->total / $totalPengajuan) * 100) : 0;
            $color = $colors[$index % count($colors)];
            $jenisStat[] = (object)[
                'nama' => ucwords(str_replace('-', ' ', $stat->jenis_surat)),
                'total' => $stat->total,
                'persentase' => $percentage,
                'color' => $color
            ];
        }

        return view('admin.dashboard-admin', compact(
            'displayName',
            'totalPengajuan',
            'menunggu',
            'disetujui',
            'ditolak',
            'menungguList',
            'riwayatTerbaru',
            'jenisStat'
        ));
    }
}
