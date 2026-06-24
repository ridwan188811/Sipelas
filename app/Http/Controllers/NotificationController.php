<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanSurat;

class NotificationController extends Controller
{
    public function check()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0, 'last_updated' => null, 'list' => []]);
        }

        $user = Auth::user();
        $notifList = [];
        $notifCount = 0;
        $lastUpdated = null;
        $formattedList = [];

        if ($user->role == 'admin') {
            $notifList = PengajuanSurat::with('user')->where('status', 'menunggu')->orderBy('created_at', 'desc')->take(5)->get();
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
                    'desc' => 'Dari: ' . ($notif->user->name ?? explode('@', $notif->user->email)[0]),
                    'time' => \Carbon\Carbon::parse($notif->created_at)->translatedFormat('d M Y, H:i'),
                    'url' => route('admin.detail-pengajuan', $notif->id)
                ];
            }
        } else {
            $notifList = PengajuanSurat::where('user_id', $user->id)
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->orderBy('updated_at', 'desc')
                ->take(5)
                ->get();
            $notifCount = PengajuanSurat::where('user_id', $user->id)
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->where('is_read_by_user', false)
                ->count();
                
            $latest = PengajuanSurat::where('user_id', $user->id)->orderBy('updated_at', 'desc')->first();
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
            'belum-menikah' => 'Surat Keterangan Belum Menikah',
            'kelahiran' => 'Surat Keterangan Kelahiran',
            'kematian' => 'Surat Keterangan Kematian',
            'pengantar-nikah' => 'Surat Pengantar Nikah',
            'pindah' => 'Surat Keterangan Pindah'
        ];
        return $map[$jenis] ?? ucwords(str_replace('-', ' ', $jenis));
    }
}
