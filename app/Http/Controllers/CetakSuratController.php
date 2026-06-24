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
        $pengajuan = PengajuanSurat::with('user')->findOrFail($id);

        // Keamanan: Hanya admin atau pemilik pengajuan yang bisa cetak
        if (Auth::user()->role !== 'admin' && Auth::user()->id !== $pengajuan->user_id) {
            abort(403, 'Anda tidak memiliki akses untuk mencetak dokumen ini.');
        }

        // Pastikan surat sudah disetujui
        if (strtolower($pengajuan->status) !== 'disetujui') {
            return redirect()->back()->with('error', 'Dokumen belum disetujui, tidak dapat dicetak.');
        }

        // Tentukan template berdasarkan jenis surat
        $view = 'surat.' . $pengajuan->jenis_surat;
        
        // Fallback jika view spesifik belum dibuat
        if (!view()->exists($view)) {
            // Bisa diarahkan ke view generic atau munculkan error
            return redirect()->back()->with('error', 'Template untuk jenis surat ini belum tersedia.');
        }

        // Nama pemohon dari data_isian atau user
        $namaPemohon = $pengajuan->data_isian['nama'] ?? $pengajuan->data_isian['nama_lengkap'] ?? $pengajuan->user->name ?? explode('@', $pengajuan->user->email)[0];
        $namaPemohonClean = preg_replace('/[^A-Za-z0-9]+/', '_', ucwords(strtolower($namaPemohon)));

        // Nama jenis surat
        $jenisMap = [
            'sku' => 'Surat_Keterangan_Usaha',
            'sktm' => 'Surat_Keterangan_Tidak_Mampu',
            'sktm-sekolah' => 'SKTM_Sekolah',
            'domisili' => 'Surat_Keterangan_Domisili',
            'belum-menikah' => 'Surat_Keterangan_Belum_Menikah',
            'kelahiran' => 'Surat_Keterangan_Kelahiran',
            'kematian' => 'Surat_Keterangan_Kematian',
            'pengantar-nikah' => 'Surat_Pengantar_Nikah',
            'pindah' => 'Surat_Keterangan_Pindah'
        ];
        $namaSurat = $jenisMap[$pengajuan->jenis_surat] ?? ucwords(str_replace('-', '_', $pengajuan->jenis_surat));

        // Siapkan nama file PDF
        $fileName = $namaSurat . '_' . trim($namaPemohonClean, '_') . '.pdf';

        // Generate QR Code if token exists
        $qrCode = null;
        if ($pengajuan->token_validasi) {
            // Gunakan absolute route otomatis agar menyesuaikan dengan domain Vercel yang sedang diakses
            $qrUrl = route('validasi', ['token' => $pengajuan->token_validasi]);
            $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(90)->margin(0)->generate($qrUrl));
        }

        // Load view dan konversi ke PDF
        $pdf = Pdf::loadView($view, compact('pengajuan', 'qrCode'));
        
        // Atur ukuran kertas
        $pdf->setPaper('A4', 'portrait');

        // Jika request ada parameter download=1, maka paksa download
        if (request()->has('download')) {
            return $pdf->download($fileName);
        }

        // Jika tidak, stream secara inline (untuk preview di iframe)
        return $pdf->stream($fileName, ['Attachment' => false]);
    }
}
