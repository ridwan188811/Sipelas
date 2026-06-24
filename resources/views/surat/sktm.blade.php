@extends('surat.layout')

@section('title', 'Surat Keterangan Tidak Mampu')

@section('content')

<!-- Judul Surat -->
<div class="judul-surat">
    <h4>SURAT KETERANGAN TIDAK MAMPU</h4>
    <p>Nomor: {{ $pengajuan->nomor_surat ?? '400.9.14.1/0128/08.1006/II/2026' }}</p>
</div>

<!-- Pembuka -->
<p class="text-justify indent">
    Berdasarkan surat pengantar dari ketua RT {{ str_pad($pengajuan->data_isian['rt'] ?? '008', 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($pengajuan->data_isian['rw'] ?? '003', 3, '0', STR_PAD_LEFT) }} Nomor {{ $pengajuan->data_isian['nomor_pengantar'] ?? '099/0803/02/26' }}, Kelurahan Sambongpari, Kecamatan Mangkubumi, Kota Tasikmalaya menerangkan bahwa:
</p>

<!-- Data Warga -->
<table class="tabel-data" style="margin-left: 20px; width: calc(100% - 20px);">
    <tr>
        <td class="td-label" style="padding: 1px 0;">Nama</td>
        <td class="td-titikdua" style="padding: 1px 0;">:</td>
        <td class="td-value" style="padding: 1px 0;">{{ strtoupper($pengajuan->data_isian['nama_lengkap'] ?? 'UKAY') }}</td>
    </tr>
    <tr>
        <td class="td-label" style="padding: 1px 0;">NIK</td>
        <td class="td-titikdua" style="padding: 1px 0;">:</td>
        <td class="td-value" style="padding: 1px 0;">{{ $pengajuan->data_isian['nik'] ?? '3278085111600005' }}</td>
    </tr>
    <tr>
        <td class="td-label" style="padding: 1px 0;">Jenis Kelamin</td>
        <td class="td-titikdua" style="padding: 1px 0;">:</td>
        <td class="td-value" style="padding: 1px 0;">{{ strtoupper(in_array(strtolower($pengajuan->data_isian['jenis_kelamin'] ?? ''), ['l', 'laki_laki', 'laki-laki']) ? 'Laki-Laki' : 'Perempuan') }}</td>
    </tr>
    <tr>
        <td class="td-label" style="padding: 1px 0;">Tempat, Tanggal Lahir</td>
        <td class="td-titikdua" style="padding: 1px 0;">:</td>
        <td class="td-value" style="padding: 1px 0;">
            {{ strtoupper($pengajuan->data_isian['tempat_lahir'] ?? 'TASIKMALAYA') }}, 
            {{ isset($pengajuan->data_isian['tanggal_lahir']) ? \Carbon\Carbon::parse($pengajuan->data_isian['tanggal_lahir'])->translatedFormat('d-m-Y') : '11-11-1960' }}
        </td>
    </tr>
    <tr>
        <td class="td-label" style="padding: 1px 0;">Warganegara / Agama</td>
        <td class="td-titikdua" style="padding: 1px 0;">:</td>
        <td class="td-value" style="padding: 1px 0;">{{ strtoupper($pengajuan->data_isian['kewarganegaraan'] ?? 'WNI') }} / {{ strtoupper($pengajuan->data_isian['agama'] ?? 'ISLAM') }}</td>
    </tr>
    <tr>
        <td class="td-label" style="padding: 1px 0;">Pekerjaan</td>
        <td class="td-titikdua" style="padding: 1px 0;">:</td>
        <td class="td-value" style="padding: 1px 0;">{{ strtoupper($pengajuan->data_isian['pekerjaan'] ?? 'MENGURUS RUMAH TANGGA') }}</td>
    </tr>
    <tr>
        <td class="td-label" style="padding: 1px 0;">Status Pernikahan</td>
        <td class="td-titikdua" style="padding: 1px 0;">:</td>
        <td class="td-value" style="padding: 1px 0;">{{ strtoupper(str_replace('_', ' ', $pengajuan->data_isian['status_pernikahan'] ?? 'CERAI HIDUP')) }}</td>
    </tr>
    <tr>
        <td class="td-label" style="padding: 1px 0; vertical-align:top;">Alamat</td>
        <td class="td-titikdua" style="padding: 1px 0; vertical-align:top;">:</td>
        <td class="td-value" style="padding: 1px 0;">
            {{ strtoupper($pengajuan->data_isian['alamat_lengkap'] ?? 'GG.TIGA DESA BABAKAN CIKURUBUK') }} RT.{{ str_pad($pengajuan->data_isian['rt'] ?? '008', 3, '0', STR_PAD_LEFT) }} RW.{{ str_pad($pengajuan->data_isian['rw'] ?? '003', 3, '0', STR_PAD_LEFT) }}<br>
            KELURAHAN {{ strtoupper($pengajuan->data_isian['kelurahan'] ?? 'SAMBONGPARI') }}, KECAMATAN {{ strtoupper($pengajuan->data_isian['kecamatan'] ?? 'MANGKUBUMI') }}<br>
            KOTA {{ strtoupper($pengajuan->data_isian['kota'] ?? 'TASIKMALAYA') }}
        </td>
    </tr>
</table>

<!-- Penutup -->
<p class="text-justify mt-10">
    adalah benar-benar penduduk kami yang kondisi ekonominya termasuk dalam kategori tidak mampu.<br>
    Surat keterangan ini dibuat untuk keperluan {{ strtolower(str_replace('_', ' ', $pengajuan->data_isian['keperluan'] ?? 'melengkapi persyaratan')) }}@if(isset($pengajuan->data_isian['nama_yang_menggunakan_surat']) || isset($pengajuan->data_isian['nama_yang_dibantu'])) atas nama <strong>{{ strtoupper($pengajuan->data_isian['nama_yang_menggunakan_surat'] ?? $pengajuan->data_isian['nama_yang_dibantu'] ?? '') }}</strong>@endif.
</p>
<p class="text-justify mt-10">
    Demikian agar dipergunakan sebagaimana mestinya.
</p>

<!-- Tanda Tangan -->
<div class="tanda-tangan-container" style="margin-top: 25px;">
    <div class="tanda-tangan-box" style="position: relative;">
        Tasikmalaya, {{ isset($pengajuan->updated_at) ? \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('j F Y') : '13 Februari 2026' }}<br>
        a.n CAMAT MANGKUBUMI
        
        <div class="qr-placeholder" style="margin-top: 10px; border: 1px solid #ccc; border-radius: 4px; padding: 5px; width: 230px; margin-left: auto; margin-right: auto; display: flex; align-items: center; text-align: left; background: #fafafa;">
            <table style="width: 100%; border:none;">
                <tr>
                    <td style="width: 55px; vertical-align: middle;">
                          @if(isset($qrCode) && $qrCode)
                            <img src="data:image/svg+xml;base64,{{ $qrCode }}" width="50" height="50" style="display: block;" alt="QR">
                          @else
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" width="50" height="50" style="background: repeating-linear-gradient(45deg, #000 0, #000 2px, #fff 2px, #fff 4px); display: block;" alt="QR">
                          @endif
                    </td>
                    <td style="vertical-align: middle; padding-left: 5px;">
                        <div style="font-size: 5pt; color: #777;">Ditandatangani secara elektronik oleh:</div>
                        <div style="font-size: 6pt; font-weight: bold; color: #555;">LURAH SAMBONGPARI,</div>
                        <br>
                        <div style="font-size: 6pt; color: #555;">Hj. RENY NURAENY K, S.Sos</div>
                        <div style="font-size: 5.5pt; color: #777;">Penata Tingkat I (III/d)</div>
                    </td>
                </tr>
            </table>
        </div>
        
    </div>
    <div class="clear"></div>
</div>

@endsection

