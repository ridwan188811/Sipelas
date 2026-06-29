<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanSurat;
use App\Models\User; // We need this to get Lurah's name, or we can just hardcode if it's not stored
use Carbon\Carbon;

class ValidasiController extends Controller
{
    public function show($token)
    {
        $pengajuan = PengajuanSurat::with('warga')->where('token_validasi', $token)->first();

        $isValid = false;
        if ($pengajuan && strtolower($pengajuan->status) === 'disetujui' && $pengajuan->is_verified_by_lurah) {
            $isValid = true;
        }

        // TTE data (Dummy or hardcoded based on user requirements)
        $lurahName = 'Reny Nuraeny K';
        $lurahJabatan = 'Lurah Sambongpari';

        return view('validasi', compact('pengajuan', 'isValid', 'lurahName', 'lurahJabatan'));
    }
}
