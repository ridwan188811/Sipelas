<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pengajuan Surat</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f7f6; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #1a3558; padding: 30px 20px; text-align: center; color: white; }
        .header img { width: 60px; height: auto; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .header p { margin: 5px 0 0; font-size: 14px; opacity: 0.8; }
        .content { padding: 30px; }
        .greeting { font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #1e293b; }
        .message-box { padding: 20px; border-radius: 8px; margin-bottom: 25px; }
        .message-box.menunggu { background-color: #e0f2fe; border-left: 4px solid #0284c7; }
        .message-box.disetujui { background-color: #dcfce7; border-left: 4px solid #16a34a; }
        .message-box.ditolak { background-color: #fee2e2; border-left: 4px solid #ef4444; }
        .message-box p { margin: 0; font-size: 15px; color: #334155; }
        .details { margin-bottom: 30px; }
        .details h3 { font-size: 16px; margin-bottom: 15px; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; }
        .detail-row { display: flex; margin-bottom: 10px; font-size: 15px; }
        .detail-label { width: 140px; color: #64748b; font-weight: 500; }
        .detail-value { flex: 1; color: #1e293b; font-weight: 600; }
        .btn-container { text-align: center; margin-top: 30px; margin-bottom: 10px; }
        .btn { display: inline-block; padding: 12px 28px; background-color: #1a3558; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 15px; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .footer p { margin: 5px 0; }
    </style>
</head>
<body>
    @php
        $jenisSuratMap = [
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
        $jenis = $jenisSuratMap[$pengajuan->jenis_surat] ?? ucwords(str_replace('-', ' ', $pengajuan->jenis_surat));
    @endphp

    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/logo_tasikmalaya.png')) }}" alt="Logo Tasikmalaya">
            <h1>Sistem Informasi Pelayanan Masyarakat</h1>
            <p>Kelurahan Sambongpari, Kota Tasikmalaya</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Halo, {{ $pengajuan->user->name ?? explode('@', $pengajuan->user->email)[0] }}!
            </div>

            <div class="message-box {{ $jenisNotif }}">
                @if($jenisNotif === 'menunggu')
                    <p>Terima kasih. Pengajuan <strong>{{ $jenis }}</strong> Anda telah berhasil dikirim dan saat ini sedang <strong>menunggu proses verifikasi</strong> oleh petugas kelurahan.</p>
                @elseif($jenisNotif === 'disetujui')
                    <p>Kabar gembira! Pengajuan <strong>{{ $jenis }}</strong> Anda telah <strong>DISETUJUI</strong>. Silakan masuk ke Dashboard SIPELAS untuk <strong>mengunduh dan mencetak</strong> surat resmi Anda secara mandiri.</p>
                @elseif($jenisNotif === 'ditolak')
                    <p>Mohon maaf, pengajuan <strong>{{ $jenis }}</strong> Anda <strong>DITOLAK</strong> karena alasan tertentu. Silakan periksa catatan dari admin di bawah ini.</p>
                @endif
            </div>

            <div class="details">
                <h3>Detail Pengajuan</h3>
                <div class="detail-row">
                    <div class="detail-label">Jenis Surat</div>
                    <div class="detail-value">{{ $jenis }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Tanggal Pengajuan</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y, H:i') }} WIB</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status</div>
                    <div class="detail-value" style="text-transform: capitalize; color: {{ $jenisNotif === 'disetujui' ? '#16a34a' : ($jenisNotif === 'ditolak' ? '#dc2626' : '#0284c7') }};">{{ $jenisNotif }}</div>
                </div>

                @if($jenisNotif === 'disetujui' && $pengajuan->nomor_surat)
                <div class="detail-row" style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #e2e8f0;">
                    <div class="detail-label">Nomor Surat</div>
                    <div class="detail-value">{{ $pengajuan->nomor_surat }}</div>
                </div>
                @endif

                @if($jenisNotif === 'ditolak' && $pengajuan->catatan_admin)
                <div class="detail-row" style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #e2e8f0; display: block;">
                    <div class="detail-label" style="margin-bottom: 5px;">Catatan Admin:</div>
                    <div class="detail-value" style="color: #dc2626; font-style: italic; background: #fef2f2; padding: 10px; border-radius: 4px; border: 1px solid #fca5a5;">
                        "{{ $pengajuan->catatan_admin }}"
                    </div>
                </div>
                @endif
            </div>

            <div class="btn-container">
                <a href="{{ route('user.dashboard') }}" class="btn">Masuk ke Dashboard SIPELAS</a>
            </div>
            
            <p style="font-size: 14px; color: #64748b; margin-top: 30px; text-align: center;">
                Bila tombol di atas tidak berfungsi, Anda juga dapat mengakses: <br>
                <a href="{{ route('login') }}" style="color: #2563eb;">{{ route('login') }}</a>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Kelurahan Sambongpari, Kota Tasikmalaya.</p>
            <p>Pesan ini dibuat secara otomatis oleh sistem, mohon untuk tidak membalas pesan ini.</p>
        </div>
    </div>
</body>
</html>
