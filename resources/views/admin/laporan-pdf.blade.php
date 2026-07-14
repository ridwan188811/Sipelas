@extends('surat.layout')

@section('title', 'Laporan Pengajuan Surat')

@section('content')

<style>
    .laporan-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 10pt;
    }
    .laporan-table th, .laporan-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }
    .laporan-table th {
        background-color: #f2f2f2;
        text-align: center;
        font-weight: bold;
    }
    .laporan-table td.text-center {
        text-align: center;
    }
    .judul-laporan {
        text-align: center;
        margin-bottom: 20px;
    }
    .judul-laporan h3 {
        margin: 0;
        font-size: 14pt;
        text-decoration: underline;
    }
    .judul-laporan p {
        margin: 5px 0 0 0;
        font-size: 11pt;
    }
</style>

<div class="judul-laporan">
    <h3>LAPORAN DATA PENGAJUAN SURAT</h3>
    <p>
        Bulan: {{ $bulan == 'semua' ? 'Semua Bulan' : ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'][$bulan] }} 
        | Tahun: {{ $tahun == 'semua' ? 'Semua Tahun' : $tahun }}
    </p>
</div>

<table class="laporan-table">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 15%;">Tanggal</th>
            <th style="width: 25%;">Nama Pemohon</th>
            <th style="width: 20%;">NIK</th>
            <th style="width: 20%;">Jenis Surat</th>
            <th style="width: 15%;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pengajuans as $index => $pengajuan)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y') }}</td>
            <td>{{ $pengajuan->warga->name ?? explode('@', $pengajuan->warga->email)[0] }}</td>
            <td class="text-center">{{ $pengajuan->warga->nik ?? '-' }}</td>
            <td>{{ ucwords(str_replace('-', ' ', $pengajuan->jenis_surat)) }}</td>
            <td class="text-center">{{ ucfirst($pengajuan->status) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data pengajuan surat untuk periode ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="tanda-tangan-container" style="margin-top: 40px;">
    <div class="tanda-tangan-box">
        Tasikmalaya, {{ \Carbon\Carbon::now()->translatedFormat('j F Y') }}<br>
        LURAH SAMBONGPARI
        <br><br><br><br><br>
        <span style="font-weight: bold; text-decoration: underline;">Hj. RENY NURAENY K, S.Sos</span><br>
        Penata Tingkat I (III/d)<br>
        NIP. 19741221 199411 2 001
    </div>
    <div class="clear"></div>
</div>

@endsection
