<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AdminPengajuanController extends Controller
{
    public function markNotifRead()
    {
        PengajuanSurat::where('status', 'menunggu')->where('is_read_by_admin', \Illuminate\Support\Facades\DB::raw('false'))->update(['is_read_by_admin' => true]);
        return response()->json(['success' => true]);
    }

    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $displayName = $admin->name ?? explode('@', $admin->email)[0];

        $query = PengajuanSurat::with('warga')
            ->where(function($q) {
                $q->where('status', 'menunggu')
                  ->orWhere(function($q2) {
                      $q2->where('status', 'disetujui')
                         ->where('is_verified_by_lurah', false);
                  });
            })
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_surat', $request->jenis);
        }

        if ($request->filled('periode')) {
            $now = Carbon::now();
            if ($request->periode == 'hari-ini') {
                $query->whereDate('created_at', $now->toDateString());
            } elseif ($request->periode == 'minggu-ini') {
                $query->whereBetween('created_at', [$now->startOfWeek()->toDateString(), $now->endOfWeek()->toDateString()]);
            } elseif ($request->periode == 'bulan-ini') {
                $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('warga', function($qUser) use ($search) {
                    $qUser->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('nik', 'like', "%{$search}%");
                });
            });
        }

        $pengajuans = $query->paginate(10)->withQueryString();

        return view('admin.daftar-pengajuan', compact('displayName', 'pengajuans'));
    }

    public function riwayat(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $displayName = $admin->name ?? explode('@', $admin->email)[0];

        $query = PengajuanSurat::with('warga')
            ->where(function($q) {
                $q->where('status', 'ditolak')
                  ->orWhere(function($q2) {
                      $q2->where('status', 'disetujui')
                         ->where('is_verified_by_lurah', true);
                  });
            })
            ->orderBy('updated_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_surat', $request->jenis);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('updated_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('warga', function($qUser) use ($search) {
                    $qUser->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('nik', 'like', "%{$search}%");
                });
            });
        }

        $pengajuans = $query->paginate(10)->withQueryString();

        return view('admin.riwayat-pengajuan', compact('displayName', 'pengajuans'));
    }

    public function show($id)
    {
        $admin = Auth::guard('admin')->user();
        $displayName = $admin->name ?? explode('@', $admin->email)[0];

        $pengajuan = PengajuanSurat::with(['warga', 'detailSku', 'detailSktm', 'detailDomisili'])->findOrFail($id);

        return view('admin.detail-pengajuan', compact('displayName', 'pengajuan'));
    }

    public function proses(Request $request, $id)
    {
        $pengajuan = PengajuanSurat::with('warga')->findOrFail($id);
        
        if ($request->action == 'setujui') {
            $updateData = [
                'status' => 'disetujui',
                'token_validasi' => Str::uuid()->toString()
            ];
            
            if ($request->filled('nomor_surat')) {
                $updateData['nomor_surat'] = $request->nomor_surat;
            } else {
                $bulanRomawi = ['01'=>'I','02'=>'II','03'=>'III','04'=>'IV','05'=>'V','06'=>'VI','07'=>'VII','08'=>'VIII','09'=>'IX','10'=>'X','11'=>'XI','12'=>'XII'];
                $bulan = $bulanRomawi[date('m')];
                $tahun = date('Y');
                
                $noUrut = str_pad($pengajuan->id, 3, '0', STR_PAD_LEFT);
                
                $kodeSurat = [
                    'sku' => '503', 'sktm' => '400.9', 'sktm-sekolah' => '400.9', 'domisili' => '470',
                    'belum-menikah' => '474.2', 'kelahiran' => '474.1', 'kematian' => '474.3',
                    'pengantar-nikah' => '474.2', 'pindah' => '475'
                ];
                $kode = $kodeSurat[$pengajuan->jenis_surat] ?? '400';
                
                $updateData['nomor_surat'] = $kode . '/' . $noUrut . '/Kel.Sbp/' . $bulan . '/' . $tahun;
            }

            $pengajuan->update($updateData);

            return redirect()->route('admin.daftar-pengajuan')->with('success', 'Pengajuan berhasil disetujui, menunggu validasi Lurah.');
        } elseif ($request->action == 'tolak') {
            $request->validate(['keterangan' => 'required|string']);
            $pengajuan->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->keterangan
            ]);

            if ($pengajuan->warga) {
                try {
                    \Illuminate\Support\Facades\Mail::to($pengajuan->warga->email)->send(new \App\Mail\NotifikasiPengajuan($pengajuan, 'ditolak'));
                } catch (\Exception $e) { }
            }

            return redirect()->route('admin.riwayat-pengajuan')->with('success', 'Pengajuan telah ditolak.');
        } elseif ($request->action == 'verifikasi') {
            $pengajuan->update(['is_verified_by_lurah' => true]);

            if ($pengajuan->warga) {
                try {
                    \Illuminate\Support\Facades\Mail::to($pengajuan->warga->email)->send(new \App\Mail\NotifikasiPengajuan($pengajuan, 'disetujui'));
                } catch (\Exception $e) { }
            }

            return redirect()->route('admin.daftar-pengajuan')->with('success', 'Dokumen berhasil diverifikasi (TTE) oleh Lurah.');
        }
        
        return back();
    }

    public function sendEmailNotification(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required',
            'action' => 'required|in:disetujui,ditolak'
        ]);

        $pengajuan = PengajuanSurat::with('warga')->find($request->pengajuan_id);
        if ($pengajuan && $pengajuan->warga) {
            try {
                \Illuminate\Support\Facades\Mail::to($pengajuan->warga->email)->send(new \App\Mail\NotifikasiPengajuan($pengajuan, $request->action));
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()]);
            }
        }
        return response()->json(['success' => false, 'error' => 'Data tidak ditemukan']);
    }

    public function exportExcel(Request $request)
    {
        $query = PengajuanSurat::with('warga')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('warga', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('jenis')) {
            $query->where('jenis_surat', $request->jenis);
        }

        $pengajuans = $query->get();

        $fileName = 'Data_Pengajuan_Surat_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'NIK', 'Pemohon', 'Jenis Surat', 'Status', 'Tanggal Pengajuan', 'Nomor Surat', 'Tanggal Diproses'];

        $callback = function() use($pengajuans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($pengajuans as $index => $pengajuan) {
                $jenisSuratMap = [
                    'sku' => 'Surat Keterangan Usaha',
                    'sktm' => 'Surat Keterangan Tidak Mampu',
                    'sktm-sekolah' => 'Surat Keterangan Tidak Mampu (Sekolah)',
                    'domisili' => 'Surat Keterangan Domisili',
                ];
                $jenis = $jenisSuratMap[$pengajuan->jenis_surat] ?? ucwords(str_replace('-', ' ', $pengajuan->jenis_surat));
                
                fputcsv($file, [
                    $index + 1,
                    $pengajuan->warga->nik ?? '-',
                    $pengajuan->warga->name ?? explode('@', $pengajuan->warga->email)[0],
                    $jenis,
                    strtoupper($pengajuan->status),
                    \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y, H:i'),
                    $pengajuan->nomor_surat ?? '-',
                    $pengajuan->status != 'menunggu' ? \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d F Y, H:i') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
