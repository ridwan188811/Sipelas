<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $displayName = $admin->name ?? explode('@', $admin->email)[0];

        $query = PengajuanSurat::with('warga')->orderBy('created_at', 'desc');

        // Filter by month/year
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        if ($bulan != 'semua') {
            $query->whereMonth('created_at', $bulan);
        }
        if ($tahun != 'semua') {
            $query->whereYear('created_at', $tahun);
        }

        if ($request->filled('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis') && $request->jenis != 'semua') {
            $query->where('jenis_surat', $request->jenis);
        }

        $pengajuans = $query->paginate(15)->withQueryString();
        
        // Count for summary
        $totalPengajuan = $query->count();
        $totalDisetujui = (clone $query)->where('status', 'disetujui')->count();
        $totalDitolak = (clone $query)->where('status', 'ditolak')->count();

        return view('admin.laporan', compact(
            'displayName', 'pengajuans', 'bulan', 'tahun', 
            'totalPengajuan', 'totalDisetujui', 'totalDitolak'
        ));
    }

    public function cetakPdf(Request $request)
    {
        $query = PengajuanSurat::with('warga')->orderBy('created_at', 'desc');

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        if ($bulan != 'semua') {
            $query->whereMonth('created_at', $bulan);
        }
        if ($tahun != 'semua') {
            $query->whereYear('created_at', $tahun);
        }

        if ($request->filled('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis') && $request->jenis != 'semua') {
            $query->where('jenis_surat', $request->jenis);
        }

        $pengajuans = $query->get();

        $pdf = Pdf::loadView('admin.laporan-pdf', compact('pengajuans', 'bulan', 'tahun'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Laporan_Pengajuan_Surat_'.$bulan.'_'.$tahun.'.pdf');
    }
}
