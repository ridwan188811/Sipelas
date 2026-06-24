<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Validasi Surat - SIPELAS</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #1d4ed8;
      --primary-light: #eff6ff;
      --primary-border: #bfdbfe;
      --gray-50: #f8fafc;
      --gray-100: #f1f5f9;
      --gray-200: #e2e8f0;
      --gray-500: #64748b;
      --gray-800: #1e293b;
      --danger: #ef4444;
      --danger-light: #fef2f2;
      --danger-border: #fecaca;
      --white: #ffffff;
      --radius: 12px;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
    body { background: #f8fafc; color: var(--gray-800); -webkit-font-smoothing: antialiased; }
    
    .container {
      max-width: 480px;
      margin: 0 auto;
      min-height: 100vh;
      background: #fafafa;
      position: relative;
    }

    /* Navbar */
    .navbar {
      display: flex;
      align-items: center;
      padding: 16px 20px;
      background: var(--white);
      border-bottom: 1px solid var(--gray-200);
      position: sticky;
      top: 0;
      z-index: 10;
    }
    .url-bar {
      flex: 1;
      text-align: center;
      font-size: .9rem;
      font-weight: 500;
      color: var(--gray-800);
      background: var(--gray-100);
      padding: 8px 12px;
      border-radius: 8px;
    }

    .content { padding: 24px 20px; }

    /* Alerts */
    .alert {
      display: flex; gap: 12px; padding: 16px; border-radius: var(--radius); margin-bottom: 24px;
    }
    .alert-icon svg { width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; }
    .alert-title { font-weight: 600; font-size: 1rem; margin-bottom: 4px; }
    .alert-desc { font-size: .85rem; line-height: 1.5; }

    .alert-success { background: var(--primary-light); border: 1px solid var(--primary-border); color: var(--primary); }
    .alert-success .alert-title { color: #1e40af; }
    .alert-success .alert-desc { color: #1e40af; }

    .alert-danger { background: var(--danger-light); border: 1px solid var(--danger-border); color: var(--danger); }
    .alert-danger .alert-title { color: #b91c1c; }
    .alert-danger .alert-desc { color: #b91c1c; }

    /* Card */
    .card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 20px;
      margin-bottom: 24px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .data-row { margin-bottom: 16px; }
    .data-row:last-child { margin-bottom: 0; }
    .data-label { font-size: .85rem; font-weight: 600; color: var(--gray-800); margin-bottom: 4px; }
    .data-value { font-size: .95rem; color: var(--gray-500); }

    /* Title TTE */
    .tte-title {
      font-size: 1rem;
      font-weight: 600;
      text-align: center;
      margin-bottom: 16px;
      line-height: 1.4;
      color: var(--gray-800);
    }

    /* TTE Card */
    .tte-card {
      display: flex;
      align-items: center;
      gap: 12px;
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 16px;
      margin-bottom: 24px;
    }
    .tte-icon { color: var(--primary); display: flex; align-items: center; }
    .tte-icon svg { width: 20px; height: 20px; fill: currentColor; }
    .tte-name { font-weight: 600; font-size: .95rem; color: var(--gray-800); margin-bottom: 2px; }
    .tte-role { font-size: .85rem; color: var(--gray-500); }

    /* Button */
    .btn-download {
      display: flex; align-items: center; justify-content: center; gap: 8px;
      width: 100%; padding: 12px; background: var(--white);
      border: 1px solid var(--gray-800); border-radius: 8px;
      color: var(--gray-800); font-weight: 600; font-size: .95rem;
      text-decoration: none; transition: background .2s;
    }
    .btn-download:hover { background: var(--gray-50); }
    .btn-download svg { width: 18px; height: 18px; }

  </style>
</head>
<body>

<div class="container">


  <div class="content">
    @if($isValid)
      <!-- SUCCESS ALERT -->
      <div class="alert alert-success">
        <div class="alert-icon">
          <!-- Verified Badge SVG -->
          <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M23 12l-2.44-2.78.34-3.68-3.61-.82-1.89-3.18L12 3 8.6 1.54 6.71 4.72l-3.61.81.34 3.68L1 12l2.44 2.78-.34 3.69 3.61.82 1.89 3.18L12 21l3.4 1.46 1.89-3.18 3.61-.82-.34-3.68L23 12zm-12.91 4.72l-3.8-3.81 1.48-1.48 2.32 2.33 5.85-5.87 1.48 1.48-7.33 7.35z"/>
          </svg>
        </div>
        <div>
          <div class="alert-title">Dokumen terdaftar pada sistem</div>
          <div class="alert-desc">Silahkan cek kembali keaslian surat yang terdaftar pada sistem dan surat fisik.</div>
        </div>
      </div>

      <!-- DATA CARD -->
      <div class="card">
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
          $pemohon = strtoupper($pengajuan->user->name ?? explode('@', $pengajuan->user->email)[0]);
        @endphp

        <div class="data-row">
          <div class="data-label">Pemohon</div>
          <div class="data-value">{{ $pemohon }}</div>
        </div>
        <div class="data-row">
          <div class="data-label">Layanan</div>
          <div class="data-value">{{ $jenis }}</div>
        </div>
        <div class="data-row">
          <div class="data-label">No. Surat</div>
          <div class="data-value">{{ $pengajuan->nomor_surat ?? '-' }}</div>
        </div>
        <div class="data-row">
          <div class="data-label">Tanggal Terbit</div>
          <div class="data-value">{{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d F Y') }}</div>
        </div>
      </div>

      <div class="tte-title">Dokumen ini telah diverifikasi dan/atau di TTE oleh</div>

      <!-- TTE CARD -->
      <div class="tte-card" style="position: relative; padding: 20px;">
        <div class="tte-icon">
          <svg viewBox="0 0 24 24" style="width:28px; height:28px;">
            <path d="M23 12l-2.44-2.78.34-3.68-3.61-.82-1.89-3.18L12 3 8.6 1.54 6.71 4.72l-3.61.81.34 3.68L1 12l2.44 2.78-.34 3.69 3.61.82 1.89 3.18L12 21l3.4 1.46 1.89-3.18 3.61-.82-.34-3.68L23 12zm-12.91 4.72l-3.8-3.81 1.48-1.48 2.32 2.33 5.85-5.87 1.48 1.48-7.33 7.35z"/>
          </svg>
        </div>
        <div style="position: relative; z-index: 2; flex: 1;">
          <div style="font-size: 0.8rem; color: #16a34a; font-weight: 700; margin-bottom: 4px; text-transform: uppercase;">TERVERIFIKASI SAH</div>
          <div class="tte-name" style="font-size: 1.1rem;">{{ $lurahName }}</div>
          <div class="tte-role">{{ $lurahJabatan }}</div>
        </div>
        
        <!-- Cap Stempel Asli -->
        <div style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); z-index: 1; pointer-events: none;">
           <img src="{{ asset('images/stempel.png') }}" alt="Stempel Validasi" style="width: 80px; opacity: 0.85; transform: rotate(-5deg);">
        </div>
      </div>

      <!-- DOWNLOAD BUTTON -->
      <a href="{{ route('preview.dokumen', ['path' => 'surat_terbit/surat_' . $pengajuan->id . '.pdf']) }}" target="_blank" class="btn-download">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="7 10 12 15 17 10"></polyline>
          <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        Unduh Dokumen
      </a>

    @else
      <!-- FAILED / INVALID CARD (CAKRA BESAR) -->
      <div class="card" style="text-align: center; padding: 40px 20px;">
        <div style="display: inline-flex; justify-content: center; align-items: center; width: 100px; height: 100px; border-radius: 50%; background-color: var(--danger-light); border: 4px solid var(--danger); margin-bottom: 24px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width: 50px; height: 50px;">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </div>
        <h2 style="color: var(--danger); font-size: 1.5rem; font-weight: 700; margin-bottom: 12px; text-transform: uppercase;">Belum Sah / Tidak Valid</h2>
        <p style="color: var(--gray-600); font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px;">
          Maaf, dokumen persuratan ini belum diverifikasi secara resmi atau tidak terdaftar pada sistem kami.
        </p>
        <div style="background: var(--danger-light); padding: 12px; border-radius: 8px; border: 1px solid var(--danger-border); font-size: 0.85rem; color: var(--danger); text-align: left; line-height: 1.5;">
          <strong>Pemberitahuan:</strong> Surat yang belum mendapatkan persetujuan dan Tanda Tangan Elektronik (TTE) dari pihak Kelurahan dianggap sebagai <strong>Draf</strong> dan tidak dapat digunakan untuk keperluan administrasi resmi apapun.
        </div>
      </div>
    @endif
  </div>
</div>

</body>
</html>

