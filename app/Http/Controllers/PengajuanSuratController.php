<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;

class PengajuanSuratController extends Controller
{
    public function markNotifRead()
    {
        $wargaId = Auth::guard('warga')->id();
        PengajuanSurat::where('warga_id', $wargaId)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->where('is_read_by_user', false)
            ->update(['is_read_by_user' => true]);
        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $rules = [
            'jenis_surat' => 'required|in:sktm,sktm-sekolah,sku,domisili',
        ];

        $getFileRule = function($fieldName) use ($request) {
            // Note: Update logic requires deeper inspection for old documents.
            // For now we enforce required for new submissions, nullable if updating
            return $request->filled('resubmit_id') ? 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048' : 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        };

        if ($request->jenis_surat === 'sktm' || $request->jenis_surat === 'sktm-sekolah') {
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
            'required' => 'Data / Dokumen pendukung wajib diunggah.',
            'mimes' => 'Format file harus berupa JPG, PNG, atau PDF.',
            'max' => 'Ukuran file maksimal 2MB.'
        ]);

        $wargaId = Auth::guard('warga')->id();
        $jenis_surat = $request->jenis_surat;
        
        $dokumenPendukung = [];
        foreach ($request->allFiles() as $key => $file) {
            $path = $file->store('dokumen_pengajuan', 'public');
            $dokumenPendukung[$key] = $path;
        }

        // Jika Resubmit, hapus pengajuan lama lalu buat baru agar detail relasi terganti dengan rapi
        if ($request->filled('resubmit_id')) {
            $pengajuanLama = PengajuanSurat::where('id', $request->resubmit_id)
                ->where('warga_id', $wargaId)
                ->where('status', 'ditolak')
                ->first();

            if ($pengajuanLama) {
                // Untuk resubmit, kita hapus dulu lama (detail table berelasi cascade) lalu insert ulang
                // Atau kita update jika mau dipertahankan
                // Untuk amannya, kita setuju menggunakan data baru
                $pengajuanLama->delete();
            }
        }

        // Simpan ke database
        $pengajuanBaru = PengajuanSurat::create([
            'warga_id' => $wargaId,
            'jenis_surat' => $jenis_surat,
            'status' => 'menunggu',
        ]);

        if ($jenis_surat === 'sku') {
            $pengajuanBaru->detailSku()->create([
                'nama_usaha' => $request->nama_usaha,
                'jenis_usaha' => $request->jenis_usaha,
                'alamat_usaha' => $request->alamat_usaha,
                'lama_usaha' => $request->lama_usaha,
                'bentuk_usaha' => $request->bentuk_usaha,
                'bidang_usaha' => $request->bidang_usaha,
                'barang_usaha' => $request->barang_usaha,
                'keadaan_usaha_saat_ini' => $request->keadaan_usaha_saat_ini,
                'sejak_tahun' => $request->sejak_tahun,
                'keperluan' => $request->keperluan,
                'keterangan_tambahan' => $request->keterangan_tambahan,
                'dokumen_surat_pengantar_rt_rw' => $dokumenPendukung['dokumen_surat_pengantar_rt_rw'] ?? null,
                'dokumen_fotokopi_ktp_pemohon' => $dokumenPendukung['dokumen_fotokopi_ktp_pemohon'] ?? null,
                'dokumen_fotokopi_keluarga_kk' => $dokumenPendukung['dokumen_fotokopi_keluarga_kk'] ?? null,
                'dokumen_foto_tempat_usaha' => $dokumenPendukung['dokumen_foto_tempat_usaha'] ?? null,
            ]);
        } elseif ($jenis_surat === 'sktm' || $jenis_surat === 'sktm-sekolah') {
            $pengajuanBaru->detailSktm()->create([
                'nama_lengkap' => $request->nama_lengkap,
                'nik' => $request->nik,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'kewarganegaraan' => $request->kewarganegaraan,
                'agama' => $request->agama,
                'pekerjaan' => $request->pekerjaan,
                'status_pernikahan' => $request->status_pernikahan,
                'alamat_lengkap' => $request->alamat_lengkap,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'kelurahan' => $request->kelurahan,
                'kecamatan' => $request->kecamatan,
                'kota' => $request->kota,
                'keperluan' => $request->keperluan,
                'nama_anak' => $request->nama_anak,
                'tempat_tanggal_lahir_anak' => $request->tempat_tanggal_lahir_anak,
                'sekolah_tujuan' => $request->sekolah_tujuan,
                'nama_yang_menggunakan_surat' => $request->nama_yang_menggunakan_surat,
                'hubungan_dengan_pemohon' => $request->hubungan_dengan_pemohon,
                'keterangan_tambahan' => $request->keterangan_tambahan,
                'dokumen_surat_pengantar_rt_rw' => $dokumenPendukung['dokumen_surat_pengantar_rt_rw'] ?? null,
                'dokumen_fotokopi_ktp_pemohon' => $dokumenPendukung['dokumen_fotokopi_ktp_pemohon'] ?? null,
                'dokumen_fotokopi_keluarga_kk' => $dokumenPendukung['dokumen_fotokopi_keluarga_kk'] ?? null,
            ]);
        } elseif ($jenis_surat === 'domisili') {
            $pengajuanBaru->detailDomisili()->create([
                'kewarganegaraan' => $request->kewarganegaraan,
                'agama' => $request->agama,
                'pekerjaan' => $request->pekerjaan,
                'status_pernikahan' => $request->status_pernikahan,
                'alamat_domisili' => $request->alamat_domisili,
                'rt_domisili' => $request->rt_domisili,
                'rw_domisili' => $request->rw_domisili,
                'kelurahan_domisili' => $request->kelurahan_domisili,
                'kecamatan_domisili' => $request->kecamatan_domisili,
                'kota_domisili' => $request->kota_domisili,
                'dokumen_surat_pengantar_rt_rw' => $dokumenPendukung['dokumen_surat_pengantar_rt_rw'] ?? null,
                'dokumen_fotokopi_ktp_pemohon' => $dokumenPendukung['dokumen_fotokopi_ktp_pemohon'] ?? null,
                'dokumen_fotokopi_keluarga_kk' => $dokumenPendukung['dokumen_fotokopi_keluarga_kk'] ?? null,
                'dokumen_surat_pernyataan_domisili___pemohon' => $dokumenPendukung['dokumen_surat_pernyataan_domisili___pemohon'] ?? null,
            ]);
        }

        if ($request->ajax()) {
            session()->flash('success', 'Pengajuan surat berhasil dikirim!');
            return response()->json(['redirect' => route('user.riwayat')]);
        }
        return redirect()->route('user.riwayat')->with('success', 'Pengajuan surat berhasil dikirim!');
    }

    public function riwayat(Request $request)
    {
        $wargaId = Auth::guard('warga')->id();
        $query = PengajuanSurat::where('warga_id', $wargaId);

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
        $pengajuan = PengajuanSurat::with(['detailSku', 'detailSktm', 'detailDomisili'])->findOrFail($id);
        $wargaId = Auth::guard('warga')->id();
        
        if ($pengajuan->warga_id === $wargaId) {
            if (!$pengajuan->is_read_by_user && in_array($pengajuan->status, ['disetujui', 'ditolak'])) {
                $pengajuan->is_read_by_user = true;
                $pengajuan->save();
            }
        }
        
        return view('user.detail-pengajuan', compact('pengajuan'));
    }

    public function destroy($id)
    {
        $pengajuan = PengajuanSurat::with(['detailSku', 'detailSktm', 'detailDomisili'])->findOrFail($id);
        $wargaId = Auth::guard('warga')->id();

        if ($pengajuan->warga_id === $wargaId && $pengajuan->status === 'ditolak') {
            // Delete associated files
            // Need to collect from details
            $files = [];
            if ($pengajuan->detailSku) {
                $files = [$pengajuan->detailSku->dokumen_surat_pengantar_rt_rw, $pengajuan->detailSku->dokumen_fotokopi_ktp_pemohon, $pengajuan->detailSku->dokumen_fotokopi_keluarga_kk, $pengajuan->detailSku->dokumen_foto_tempat_usaha];
            } elseif ($pengajuan->detailSktm) {
                $files = [$pengajuan->detailSktm->dokumen_surat_pengantar_rt_rw, $pengajuan->detailSktm->dokumen_fotokopi_ktp_pemohon, $pengajuan->detailSktm->dokumen_fotokopi_keluarga_kk];
            } elseif ($pengajuan->detailDomisili) {
                $files = [$pengajuan->detailDomisili->dokumen_surat_pengantar_rt_rw, $pengajuan->detailDomisili->dokumen_fotokopi_ktp_pemohon, $pengajuan->detailDomisili->dokumen_fotokopi_keluarga_kk, $pengajuan->detailDomisili->dokumen_surat_pernyataan_domisili___pemohon];
            }

            foreach ($files as $file) {
                if ($file && \Illuminate\Support\Facades\Storage::disk('public')->exists($file)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($file);
                }
            }
            $pengajuan->delete();
            return redirect()->route('user.riwayat')->with('success', 'Riwayat pengajuan yang ditolak berhasil dihapus.');
        }

        return back()->with('error', 'Tidak dapat menghapus pengajuan ini.');
    }
}
