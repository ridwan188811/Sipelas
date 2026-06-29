<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CetakSuratController extends Controller
{
    public function cetakPdf($id)
    {
        $pengajuan = PengajuanSurat::with(['warga', 'detailSku', 'detailSktm', 'detailDomisili'])->findOrFail($id);

        $isAdmin = Auth::guard('admin')->check();
        $isWarga = Auth::guard('warga')->check() && Auth::guard('warga')->id() === $pengajuan->warga_id;

        if (!$isAdmin && !$isWarga) {
            abort(403, 'Anda tidak memiliki akses untuk mencetak dokumen ini.');
        }

        if (strtolower($pengajuan->status) !== 'disetujui') {
            return redirect()->back()->with('error', 'Dokumen belum disetujui, tidak dapat dicetak.');
        }

        $view = 'surat.' . $pengajuan->jenis_surat;
        
        if (!view()->exists($view)) {
            return redirect()->back()->with('error', 'Template untuk jenis surat ini belum tersedia.');
        }

        $namaPemohon = $pengajuan->warga->name ?? explode('@', $pengajuan->warga->email)[0];
        $namaPemohonClean = preg_replace('/[^A-Za-z0-9]+/', '_', ucwords(strtolower($namaPemohon)));

        $jenisMap = [
            'sku' => 'Surat_Keterangan_Usaha',
            'sktm' => 'Surat_Keterangan_Tidak_Mampu',
            'sktm-sekolah' => 'SKTM_Sekolah',
            'domisili' => 'Surat_Keterangan_Domisili',
        ];
        $namaSurat = $jenisMap[$pengajuan->jenis_surat] ?? ucwords(str_replace('-', '_', $pengajuan->jenis_surat));

        $fileName = $namaSurat . '_' . trim($namaPemohonClean, '_') . '.pdf';

        $qrCode = null;
        if ($pengajuan->token_validasi) {
            $qrUrl = route('validasi', ['token' => $pengajuan->token_validasi]);
            $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(90)->margin(0)->generate($qrUrl));
        }

        $pdf = Pdf::loadView($view, compact('pengajuan', 'qrCode'));
        $pdf->setPaper('A4', 'portrait');

        if (request()->has('download')) {
            return $pdf->download($fileName);
        }

        return $pdf->stream($fileName, ['Attachment' => false]);
    }
}
