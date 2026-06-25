<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends Controller
{
    public function markNotifRead()
    {
        $user = Auth::user();
        PengajuanSurat::where('user_id', $user->id)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->where('is_read_by_user', false)
            ->update(['is_read_by_user' => true]);
        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $pengajuanLama = null;
        if ($request->filled('resubmit_id')) {
            $pengajuanLama = PengajuanSurat::where('id', $request->resubmit_id)
                ->where('user_id', Auth::id())
                ->first();
        }

        $rules = [
            'jenis_surat' => 'required|in:sktm,sku,domisili',
        ];

        $getFileRule = function($fieldName) use ($pengajuanLama) {
            if ($pengajuanLama && isset($pengajuanLama->dokumen_pendukung[$fieldName])) {
                return 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
            }
            return 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        };

        if ($request->jenis_surat === 'sktm') {
            $rules['dokumen_surat_pengantar_rt_rw'] = $getFileRule('dokumen_surat_pengantar_rt_rw');
            $rules['dokumen_fotokopi_ktp_pemohon'] = $getFileRule('dokumen_fotokopi_ktp_pemohon');
            $rules['dokumen_fotokopi_keluarga_kk'] = $getFileRule('dokumen_fotokopi_keluarga_kk');
        } elseif ($request->jenis_surat === 'sku') {
            $rules['dokumen_surat_pengantar_rt_rw'] = $getFileRule('dokumen_surat_pengantar_rt_rw');
            $rules['dokumen_fotokopi_ktp_pemohon'] = $getFileRule('dokumen_fotokopi_ktp_pemohon');
            $rules['dokumen_fotokopi_keluarga_kk'] = $getFileRule('dokumen_fotokopi_keluarga_kk');
            $rules['dokumen_foto_tempat_usaha'] = $getFileRule('dokumen_foto_tempat_usaha');
        } elseif ($request->jenis_surat === 'domisili') {
            $rules['dokumen_surat_pengantar_rt_rw'] = $getFileRule('dokumen_surat_pengantar_rt_rw');
            $rules['dokumen_fotokopi_ktp_pemohon'] = $getFileRule('dokumen_fotokopi_ktp_pemohon');
            $rules['dokumen_fotokopi_keluarga_kk'] = $getFileRule('dokumen_fotokopi_keluarga_kk');
            $rules['dokumen_surat_pernyataan_domisili___pemohon'] = $getFileRule('dokumen_surat_pernyataan_domisili___pemohon');
        }

        $request->validate($rules, [
            'required' => 'File/Dokumen pendukung wajib diunggah (periksa kembali bagian dokumen).',
            'mimes' => 'Format file harus berupa JPG, PNG, atau PDF.',
            'max' => 'Ukuran file maksimal 2MB.'
        ]);

        $user = Auth::user();
        $jenis_surat = $request->jenis_surat;
        
        // Ambil semua data input kecuali file dan metadata form (csrf, jenis_surat, resubmit_id)
        $inputData = $request->except(['_token', 'jenis_surat', 'dokumen', 'resubmit_id']);
        
        $dokumenPendukung = [];

        // Handle file uploads dengan aman
        foreach ($request->allFiles() as $key => $file) {
            // Gunakan hashName() bawaan Laravel agar nama file aman dan tidak bisa dimanipulasi
            $path = $file->store('dokumen_pengajuan', 'public');
            $dokumenPendukung[$key] = $path;
        }

        if ($request->filled('resubmit_id')) {
            $pengajuan = PengajuanSurat::where('id', $request->resubmit_id)
                ->where('user_id', $user->id)
                ->where('status', 'ditolak')
                ->first();

            if ($pengajuan) {
                $pengajuan->update([
                    'jenis_surat' => $jenis_surat,
                    'status' => 'menunggu',
                    'data_isian' => $inputData,
                    'dokumen_pendukung' => count($dokumenPendukung) > 0 ? array_merge($pengajuan->dokumen_pendukung ?? [], $dokumenPendukung) : $pengajuan->dokumen_pendukung,
                    'catatan_admin' => null,
                ]);

                if ($request->ajax()) {
                    session()->flash('success', 'Pengajuan ulang berhasil dikirim!');
                    return response()->json(['redirect' => route('user.riwayat')]);
                }
                return redirect()->route('user.riwayat')->with('success', 'Pengajuan ulang berhasil dikirim!');
            }
        }

        // Simpan ke database
        $pengajuanBaru = PengajuanSurat::create([
            'user_id' => $user->id,
            'jenis_surat' => $jenis_surat,
            'status' => 'menunggu',
            'data_isian' => $inputData,
            'dokumen_pendukung' => count($dokumenPendukung) > 0 ? $dokumenPendukung : null,
        ]);

        if ($request->ajax()) {
            session()->flash('success', 'Pengajuan surat berhasil dikirim!');
            return response()->json(['redirect' => route('user.riwayat')]);
        }
        return redirect()->route('user.riwayat')->with('success', 'Pengajuan surat berhasil dikirim!');
    }

    public function riwayat(Request $request)
    {
        $query = PengajuanSurat::where('user_id', Auth::id());

        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengajuans = $query->orderBy('created_at', 'desc')->get();
        
        return view('user.riwayat', compact('pengajuans'));
    }

    public function show($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);
        
        // Jika warga membuka detail suratnya sendiri dan statusnya disetujui/ditolak
        if (Auth::user()->role === 'warga' && $pengajuan->user_id === Auth::id()) {
            if (!$pengajuan->is_read_by_user && in_array($pengajuan->status, ['disetujui', 'ditolak'])) {
                $pengajuan->is_read_by_user = true;
                $pengajuan->save();
            }
        }
        
        return view('user.detail-pengajuan', compact('pengajuan'));
    }

    public function destroy($id)
    {
        $pengajuan = PengajuanSurat::findOrFail($id);

        // Hanya warga pemilik pengajuan yang bisa menghapus, dan hanya jika statusnya ditolak (atau mungkin menunggu)
        // User requested: "saat ditolak aktifkan"
        if (Auth::user()->role === 'warga' && $pengajuan->user_id === Auth::id() && $pengajuan->status === 'ditolak') {
            // Hapus file dokumen pendukung jika ada
            if ($pengajuan->dokumen_pendukung) {
                foreach ($pengajuan->dokumen_pendukung as $file) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($file)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($file);
                    }
                }
            }
            $pengajuan->delete();
            return redirect()->route('user.riwayat')->with('success', 'Riwayat pengajuan yang ditolak berhasil dihapus.');
        }

        return back()->with('error', 'Tidak dapat menghapus pengajuan ini.');
    }
}
