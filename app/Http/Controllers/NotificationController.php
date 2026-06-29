<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanSurat;

class NotificationController extends Controller
{
    public function check()
    {
        $isAdmin = Auth::guard('admin')->check();
        $isWarga = Auth::guard('warga')->check();

        if (!$isAdmin && !$isWarga) {
            return response()->json(['count' => 0, 'last_updated' => null, 'list' => []]);
        }

        $notifList = [];
        $notifCount = 0;
        $lastUpdated = null;
        $formattedList = [];

        if ($isAdmin) {
            $notifList = PengajuanSurat::with('warga')->where('status', 'menunggu')->orderBy('created_at', 'desc')->take(5)->get();
            $notifCount = PengajuanSurat::where('status', 'menunggu')->where('is_read_by_admin', false)->count();
            
            $latest = PengajuanSurat::where('status', 'menunggu')->orderBy('created_at', 'desc')->first();
            if ($latest) $lastUpdated = $latest->created_at->toDateTimeString();
            else {
                $latestAny = PengajuanSurat::orderBy('updated_at', 'desc')->first();
                $lastUpdated = $latestAny ? $latestAny->updated_at->toDateTimeString() : null;
            }

            foreach ($notifList as $notif) {
                $jenis = $this->formatJenis($notif->jenis_surat);
                $formattedList[] = [
                    'title' => 'Pengajuan Baru: ' . $jenis,
                    'desc' => 'Dari: ' . ($notif->warga->name ?? explode('@', $notif->warga->email)[0]),
                    'time' => \Carbon\Carbon::parse($notif->created_at)->translatedFormat('d M Y, H:i'),
                    'url' => route('admin.detail-pengajuan', $notif->id)
                ];
            }
        } else {
            $wargaId = Auth::guard('warga')->id();
            $notifList = PengajuanSurat::where('warga_id', $wargaId)
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
            $notifCount = PengajuanSurat::where('warga_id', $wargaId)
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->where('is_read_by_user', false)
                ->count();
                
            $latest = PengajuanSurat::where('warga_id', $wargaId)->orderBy('updated_at', 'desc')->first();
            $lastUpdated = $latest ? $latest->updated_at->toDateTimeString() : null;

            foreach ($notifList as $notif) {
                $jenis = $this->formatJenis($notif->jenis_surat);
                $formattedList[] = [
                    'title' => 'Surat ' . $jenis . ' ' . ucfirst($notif->status),
                    'desc' => 'Pengajuan surat Anda telah ' . $notif->status . ' oleh kelurahan.',
                    'time' => \Carbon\Carbon::parse($notif->updated_at)->translatedFormat('d M Y, H:i'),
                    'url' => route('user.riwayat', ['focus' => $notif->id])
                ];
            }
        }

        return response()->json([
            'count' => $notifCount,
            'last_updated' => $lastUpdated,
            'list' => $formattedList
        ]);
    }

    private function formatJenis($jenis)
    {
        $map = [
            'sku' => 'Surat Keterangan Usaha',
            'sktm' => 'Surat Keterangan Tidak Mampu',
            'sktm-sekolah' => 'Surat Keterangan Tidak Mampu (Sekolah)',
            'domisili' => 'Surat Keterangan Domisili',
        ];
        return $map[$jenis] ?? ucwords(str_replace('-', ' ', $jenis));
    }
}
