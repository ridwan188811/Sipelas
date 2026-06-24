<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Detail Pengajuan – SIPELAS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    /* ===== RESET & BASE ===== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; }
    body {
      font-family: 'Inter', sans-serif;
      background: #f0f4f8;
      color: #1e293b;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    :root {
      --navy: #1a3558;
      --navy-dark: #152c48;
      --blue: #2563eb;
      --green: #22c55e;
      --red: #ef4444;
      --gray-50: #f8fafc;
      --gray-100: #f1f5f9;
      --gray-200: #e2e8f0;
      --gray-300: #cbd5e1;
      --gray-400: #94a3b8;
      --gray-500: #64748b;
      --gray-600: #475569;
      --gray-800: #1e293b;
      --white: #ffffff;
      --shadow-sm: 0 1px 3px rgba(0,0,0,.08);
      --radius: 12px;
      --header-h: 60px;
    }

    /* HEADER */
    .header {
      position: sticky; top: 0; z-index: 50; background: var(--navy); height: var(--header-h);
      display: flex; align-items: center; padding: 0 20px; gap: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.2); flex-shrink: 0;
    }
    .hamburger-btn { background: none; border: none; cursor: pointer; padding: 7px; color: white; border-radius: 7px; transition: background .18s; }
    .hamburger-btn:hover { background: rgba(255,255,255,.12); }
    .header-brand { display: flex; align-items: center; gap: 10px; flex: 1; }
    .header-brand-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
    .header-brand-text { color: white; line-height: 1.2; }
    .header-brand-text .app-name { font-size: 1.05rem; font-weight: 700; letter-spacing: .02em; }
    .header-brand-text .app-sub  { font-size: .62rem; opacity: .65; font-weight: 400; }
    
    .header-actions { display: flex; align-items: center; gap: 8px; }
    .notif-btn { position: relative; background: none; border: none; cursor: pointer; padding: 7px; color: rgba(255,255,255,.9); border-radius: 7px; }
    .notif-btn:hover { background: rgba(255,255,255,.12); }
    .notif-badge { position: absolute; top: 5px; right: 5px; width: 7px; height: 7px; background: #ef4444; border-radius: 50%; border: 2px solid var(--navy); }
    .user-avatar { width: 34px; height: 34px; background: var(--gray-400); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: .88rem; cursor: pointer; text-decoration: none; }

    /* LAYOUT */
    .page-body { display: flex; flex: 1; min-height: 0; }
    .sidebar { width: 220px; background: var(--white); border-right: 1px solid var(--gray-200); display: flex; flex-direction: column; flex-shrink: 0; position: sticky; top: var(--header-h); height: calc(100vh - var(--header-h)); overflow-y: auto; transition: width .28s, transform .28s; z-index: 30; }
    .sidebar.collapsed { width: 0; overflow: hidden; border-right: none; }
    .sidebar-nav { flex: 1; padding: 20px 12px; display: flex; flex-direction: column; gap: 4px; }
    .nav-item { display: flex; align-items: center; gap: 11px; padding: 11px 16px; border-radius: 9px; color: var(--gray-600); text-decoration: none; font-size: .9rem; font-weight: 500; white-space: nowrap; transition: background .18s, color .18s; }
    .nav-item:hover { background: var(--gray-100); color: var(--gray-800); }
    .nav-item.active { background: var(--navy); color: var(--white); font-weight: 600; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; color: var(--navy); }
    .nav-item.active svg { color: var(--white); }
    .sidebar-footer { padding: 12px; border-top: 1px solid var(--gray-200); margin-top: auto; }
    .logout-btn { display: flex; align-items: center; gap: 11px; width: 100%; padding: 11px 16px; border-radius: 9px; border: none; background: none; color: var(--gray-600); font-size: .9rem; font-weight: 500; cursor: pointer; text-decoration: none; white-space: nowrap; transition: background .18s, color .18s; }
    .logout-btn:hover { background: #fef2f2; color: #b91c1c; }
    .logout-btn svg { width: 18px; height: 18px; flex-shrink: 0; color: #ef4444; }

    .content-area { flex: 1; display: flex; flex-direction: column; min-width: 0; }
    .content { padding: 32px 40px; max-width: 1200px; margin: 0; width: 100%; }

    /* DETAIL HEADER */
    .detail-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .detail-title { font-size: 1.15rem; font-weight: 700; color: var(--gray-800); }
    .btn-kembali { background: #8b7373; color: white; border: none; padding: 8px 18px; border-radius: 6px; font-weight: 600; font-size: .85rem; cursor: pointer; text-decoration: none; transition: background .15s; }
    .btn-kembali:hover { background: #715e5e; }

    /* ALERT SUCCESS */
    .alert-success-card { background: #dcfce7; border: 1px solid #bbf7d0; border-radius: var(--radius); padding: 20px 24px; display: flex; align-items: center; gap: 20px; margin-bottom: 28px; }
    .alert-icon-box { width: 50px; height: 50px; background: #22c55e; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; }
    .alert-icon-box svg { width: 28px; height: 28px; }
    .alert-text h3 { font-size: 1.05rem; font-weight: 700; color: #166534; margin-bottom: 4px; }
    .alert-text p { font-size: .9rem; color: #15803d; }

    /* GRID 2 COLUMNS */
    .grid-2col { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start; min-width: 0; }
    
    .left-col { display: flex; flex-direction: column; gap: 24px; min-width: 0; }
    .right-col { display: flex; flex-direction: column; gap: 24px; min-width: 0; }

    /* SECTION CARD */
    .section-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow-sm); min-width: 0; overflow-wrap: break-word; }
    .section-card-title { font-size: 1rem; font-weight: 700; color: var(--navy); margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
    .section-card-title::before { content: ''; display: block; width: 4px; height: 20px; background: var(--navy); border-radius: 4px; flex-shrink: 0; }

    /* KV LIST */
    .kv-list { display: flex; flex-direction: column; gap: 20px; min-width: 0; }
    .kv-item { display: grid; grid-template-columns: 200px 1fr; gap: 16px; font-size: .9rem; min-width: 0; }
    .kv-key { color: var(--gray-600); font-weight: 600; min-width: 0; }
    .kv-value { color: var(--gray-800); font-weight: 500; line-height: 1.4; word-break: break-word; min-width: 0; }
    
    .badge-status { background: #86efac; color: #166534; padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 700; display: inline-block; }

    /* VERTICAL STEPPER */
    .stepper-v { display: flex; flex-direction: column; padding-top: 8px; min-width: 0; }
    .step-v-item { display: flex; gap: 20px; position: relative; padding-bottom: 32px; min-width: 0; }
    .step-v-item:last-child { padding-bottom: 0; }
    .step-v-line { position: absolute; left: 15px; top: 32px; bottom: 0; width: 2px; background: #e2e8f0; z-index: 1; }
    .step-v-item:last-child .step-v-line { display: none; }
    .step-v-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2; position: relative; background: white; border: 2px solid #cbd5e1; color: transparent; flex-shrink: 0; }
    .step-v-icon.completed { background: #ecfdf5; border-color: #22c55e; color: #22c55e; }
    .step-v-icon.active { background: #fef9c3; border-color: #fde047; color: #eab308; }
    .step-v-text { min-width: 0; }
    .step-v-text h4 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 4px; word-wrap: break-word; }
    .step-v-text.active h4 { color: #ca8a04; }
    .step-v-text p { font-size: 0.75rem; color: #64748b; }
    .info-card-blue { border: 1px solid #bae6fd; border-radius: var(--radius); padding: 16px; background: #f0f9ff; color: #0284c7; font-size: 0.85rem; line-height: 1.5; }

    /* PDF PREVIEW CARD */
    .pdf-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow-sm); }
    .pdf-header { background: #166534; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; }
    .pdf-header-title { color: white; font-size: 1rem; font-weight: 700; line-height: 1.3; max-width: 120px; }
    .btn-unduh-sm { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: white; padding: 8px 14px; border-radius: 6px; font-size: .7rem; font-weight: 600; display: flex; flex-direction: column; align-items: center; gap: 4px; cursor: pointer; text-decoration: none; transition: background .15s; }
    .btn-unduh-sm svg { width: 16px; height: 16px; }
    .btn-unduh-sm:hover { background: rgba(255,255,255,0.1); }
    .pdf-body { padding: 24px; background: #f8fafc; display: flex; justify-content: center; }
    .pdf-mockup { width: 100%; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.06); padding: 28px 24px; font-family: 'Times New Roman', Times, serif; font-size: 8px; color: #111; line-height: 1.3; }
    .pdf-mockup h1 { font-size: 11px; text-align: center; margin-bottom: 2px; font-weight: bold; }
    .pdf-mockup h2 { font-size: 10px; text-align: center; margin-bottom: 2px; font-weight: bold; }
    .pdf-mockup h3 { font-size: 10px; text-align: center; margin-bottom: 6px; font-weight: bold; }
    .pdf-mockup-address { text-align:center; font-size:6px; color:#444; border-bottom: 1px solid #111; padding-bottom:6px; margin-bottom:10px; }
    .pdf-mockup-title { text-align:center; margin-bottom:12px; }
    .pdf-mockup-title span.title-text { font-size:9px; font-weight:bold; text-decoration:underline; }
    .pdf-mockup-title span.title-num { font-size:6px; }
    .pdf-table { width: 100%; font-size:6px; margin: 8px 0; border-collapse: collapse; }
    .pdf-table td { padding: 1px 0; vertical-align: top; }
    .pdf-table td:first-child { width: 35%; }

    /* DOKUMEN */
    .doc-list { display: flex; flex-direction: column; gap: 16px; }
    .doc-item { display: flex; align-items: center; gap: 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; text-decoration: none; transition: background .2s; cursor: pointer; }
    .doc-item:hover { background: #f1f5f9; }
    .doc-icon { width: 44px; height: 44px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b; flex-shrink: 0; }
    .doc-info { min-width: 0; flex: 1; }
    .doc-info h4 { font-size: .95rem; font-weight: 700; color: #1e293b; margin-bottom: 4px; text-transform: capitalize; }
    .doc-info p { font-size: .8rem; color: #64748b; }

    /* MOBILE OVERLAY */
    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 25; }
    .sidebar-overlay.active { display: block; }
    .mobile-bottom-nav { display: none; }

    @media (max-width: 900px) { 
      .grid-2col { display: flex; flex-direction: column; gap: 24px; align-items: stretch; }
      .left-col, .right-col { display: contents; }
      
      /* UI/UX Reordering for Mobile */
      .pdf-card { order: 1; }
      .card-informasi-waiting { order: 2; }
      .card-langkah { order: 3; }
      .card-lacak { order: 4; }
      .card-info { order: 5; }
      .card-diri { order: 6; }
      .card-khusus { order: 7; }
      .card-dokumen { order: 8; }
    }
    @media (max-width: 680px) {
      .sidebar { display: none !important; }
      .hamburger-btn { display: none !important; }
      .sidebar-overlay { display: none !important; }
      .content { padding: 20px 16px; padding-bottom: 32px; }
      
      /* Header & Buttons */
      .detail-header { flex-direction: row; align-items: center; justify-content: space-between; margin-bottom: 16px; }
      .detail-title { font-size: 1rem; text-align: left; }
      .btn-kembali { width: auto; padding: 6px 12px; font-size: .8rem; border-radius: 6px; }

      /* Alert Cards */
      .alert-success-card, .alert-warning-card, .alert-danger-card { flex-direction: column; align-items: flex-start; text-align: left; padding: 20px 16px; gap: 16px; margin-bottom: 24px; }
      .alert-icon-box { margin: 0; width: 44px; height: 44px; }
      .alert-text .alasan-box { text-align: left; width: 100%; margin-top: 12px; }
      
      /* KV List */
      .kv-item { grid-template-columns: 1fr; gap: 4px; border-bottom: 1px solid var(--gray-100); padding-bottom: 12px; }
      .kv-item:last-child { border-bottom: none; padding-bottom: 0; }
      
      /* Cards & Docs */
      .section-card { padding: 20px 16px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); }
      
      /* PDF */
      .pdf-header { flex-direction: row; gap: 12px; align-items: center; justify-content: space-between; padding: 16px; }
      .pdf-header-title { max-width: none; }
      .btn-unduh-sm { width: auto; flex-direction: row; justify-content: center; padding: 8px 14px; font-size: .8rem; border-radius: 6px; gap: 6px; }
      .pdf-mockup { padding: 16px; }
    }
  </style>
</head>
<body>

<!-- HEADER -->
<header class="header">
  <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu" aria-expanded="true" aria-controls="sidebar">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-brand">
    <div class="header-brand-icon"><img src="{{ asset('images/logo_tasikmalaya.png') }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;"></div>
    <div class="header-brand-text"><div class="app-name">SIPELAS</div><div class="app-sub">Sistem Informasi Pelayanan Masyarakat</div></div>
  </div>
  <div class="header-actions">
    <div class="notif-wrapper" style="position: relative;">
      <button class="notif-btn" id="notifBtn" onclick="const d = document.getElementById('notifDropdown'); d.style.display = d.style.display === 'none' ? 'block' : 'none';">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        @if($globalNotifCount > 0)
        <span class="notif-badge"></span>
        @endif
      </button>
      <div id="notifDropdown" style="display:none;position:absolute;top:100%;right:0;width:320px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;box-shadow:var(--shadow);z-index:100;">
        <div style="padding:12px;border-bottom:1px solid #e2e8f0;font-weight:700;">Notifikasi</div>
        <div style="padding:20px;text-align:center;font-size:.85rem;color:#64748b;">Silakan buka halaman notifikasi</div>
      </div>
    </div>
    <a href="{{ route('user.profil') }}" class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email, 0, 1)) }}</a>
  </div>
</header>

<div class="page-body">
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <nav class="sidebar-nav">
      <a href="{{ route('user.dashboard') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg> Dashboard</a>
      <a href="{{ route('user.ajukan-surat') }}" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg> Ajukan Surat</a>
      <a href="{{ route('user.riwayat') }}" class="nav-item active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg> Riwayat</a>
    </nav>
    <div class="sidebar-footer">
      <a href="{{ route('logout') }}" class="logout-btn" onclick="confirmLogout(event, this.href);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Keluar</a>
    </div>
  </aside>

  <!-- CONTENT -->
  <div class="content-area">
    <main class="content">

      @php
        $jenisMap = [
          'sku' => 'SKU',
          'sktm' => 'SKTM',
          'sktm-sekolah' => 'SKTM Sekolah',
          'domisili' => 'Domisili',
          'belum-menikah' => 'Belum Menikah',
          'kelahiran' => 'Kelahiran',
          'kematian' => 'Kematian',
          'pengantar-nikah' => 'Pengantar Nikah',
          'pindah' => 'Pindah'
        ];
        $namaSurat = $jenisMap[$pengajuan->jenis_surat] ?? ucwords(str_replace('-', ' ', $pengajuan->jenis_surat));
      @endphp

      <!-- Detail Header -->
      <div class="detail-header">
        <h2 class="detail-title">Detail Pengajuan Surat</h2>
        <a href="{{ route('user.riwayat') }}" class="btn-kembali">Kembali</a>
      </div>

      <!-- Alert Card -->
      @if($pengajuan->status == 'disetujui')
      <div class="alert-success-card">
        <div class="alert-icon-box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        <div class="alert-text">
          <h3>Pengajuan Disetujui</h3>
          @if($pengajuan->is_verified_by_lurah)
          <p>Surat sudah dapat diunduh &mdash; {{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d F Y') }}</p>
          @else
          <p>Berkas lolos verifikasi. Harap tunggu proses <strong>Tanda Tangan Elektronik (TTE) Lurah</strong> sebelum surat dapat dicetak.</p>
          @endif
        </div>
      </div>
      @elseif($pengajuan->status == 'menunggu')
      <div class="alert-success-card" style="background: #fef9c3; border-color: #fde047;">
        <div class="alert-icon-box" style="background: #eab308;">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
        </div>
        <div class="alert-text">
          <h3 style="color: #854d0e;">Sedang Diproses</h3>
          <p style="color: #a16207;">Pengajuan kamu sedang diverifikasi oleh petugas kelurahan. Harap tunggu.</p>
        </div>
      </div>
      @elseif($pengajuan->status == 'ditolak')
      <div class="alert-success-card" style="background: #fef2f2; border-color: #fca5a5; align-items: flex-start;">
        <div class="alert-icon-box" style="background: #ef4444;">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </div>
        <div class="alert-text" style="flex: 1;">
          <h3 style="color: #b91c1c;">Pengajuan Ditolak</h3>
          <p style="color: #7f1d1d; line-height: 1.5; margin-bottom: 16px;">Pengajuan {{ $namaSurat }} kamu ditolak oleh petugas kelurahan pada {{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y, H:i') }}. Silakan perbaiki dokumen dan ajukan kembali.</p>
          <div class="alasan-box" style="background: white; border: 1px solid #fca5a5; border-radius: 8px; padding: 16px;">
            <h4 style="color: #ef4444; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; margin-bottom: 6px;">Alasan Penolakan:</h4>
            <p style="color: #64748b; font-size: 0.85rem; line-height: 1.5;">{{ $pengajuan->catatan_admin ?? 'Berkas tidak memenuhi persyaratan.' }}</p>
          </div>
        </div>
      </div>
      @endif

      <!-- MAIN GRID -->
      <div class="grid-2col">
        
        <!-- LEFT COLUMN -->
        <div class="left-col">
          
          <!-- Informasi Pengajuan -->
          <div class="section-card card-info">
            <h3 class="section-card-title">Informasi Pengajuan</h3>
            <div class="kv-list">
              <div class="kv-item"><div class="kv-key">Jenis Surat</div><div class="kv-value" style="font-weight: 600;">{{ $namaSurat }}</div></div>
              <div class="kv-item"><div class="kv-key">Tanggal Pengajuan</div><div class="kv-value">{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y, H:i') }}</div></div>
              @if($pengajuan->status != 'menunggu')
              <div class="kv-item"><div class="kv-key">Tanggal Diproses</div><div class="kv-value">{{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d F Y, H:i') }}</div></div>
              @endif
              <div class="kv-item"><div class="kv-key">Status</div><div class="kv-value">
                @if($pengajuan->status == 'menunggu')
                <span class="badge-status" style="background: #fef9c3; color: #854d0e;">Menunggu</span>
                @elseif($pengajuan->status == 'ditolak')
                <span class="badge-status" style="background: #fecaca; color: #991b1b;">Ditolak</span>
                @else
                <span class="badge-status">{{ ucfirst($pengajuan->status) }}</span>
                @endif
              </div></div>
              @if($pengajuan->status != 'menunggu')
              <div class="kv-item"><div class="kv-key">{{ $pengajuan->status == 'ditolak' ? 'Ditolak Oleh' : 'Disetujui Oleh' }}</div><div class="kv-value">Endang Saripudin</div></div>
              @if($pengajuan->status == 'disetujui')
              <div class="kv-item"><div class="kv-key">Nomor Surat</div><div class="kv-value">{{ $pengajuan->nomor_surat ?? '-' }}</div></div>
              @endif
              @endif
            </div>
          </div>

          <!-- Data Diri Pemohon -->
          @php
            $dataDiriKeys = ['nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'kewarganegaraan', 'agama', 'pekerjaan', 'status_pernikahan', 'rt', 'rw', 'alamat_lengkap', 'kelurahan', 'kecamatan', 'kota', 'nama_yang_menggunakan_surat', 'hubungan_dengan_pemohon', 'lama_berdomisili', 'status_pemilikan___alasan', 'alamat_domisili', 'rt_domisili', 'rw_domisili', 'kelurahan_domisili', 'kecamatan_domisili', 'kota_domisili'];
            $dataDiri = [];
            $dataUsaha = [];
            
            if ($pengajuan->data_isian) {
                foreach($pengajuan->data_isian as $key => $value) {
                    if (str_starts_with($key, 'dokumen_')) continue;
                    if (in_array($key, $dataDiriKeys)) { $dataDiri[$key] = $value; } else { $dataUsaha[$key] = $value; }
                }
            }
            $jkMap = ['l' => 'Laki-laki', 'p' => 'Perempuan'];
            $kawinMap = ['belum' => 'Belum Kawin', 'kawin' => 'Kawin', 'cerai_hidup' => 'Cerai Hidup', 'cerai_mati' => 'Cerai Mati'];
          @endphp

          @if(count($dataDiri) > 0)
          <div class="section-card card-diri">
            <h3 class="section-card-title">Data Diri Pemohon</h3>
            <div class="kv-list">
              <div class="kv-item"><div class="kv-key">NIK</div><div class="kv-value">{{ $dataDiri['nik'] ?? '-' }}</div></div>
              <div class="kv-item"><div class="kv-key">Nama Lengkap</div><div class="kv-value">{{ ucwords($dataDiri['nama_lengkap'] ?? '-') }}</div></div>
              <div class="kv-item"><div class="kv-key">Jenis Kelamin</div><div class="kv-value">{{ $jkMap[$dataDiri['jenis_kelamin'] ?? ''] ?? ucwords(str_replace('_', ' ', $dataDiri['jenis_kelamin'] ?? '-')) }}</div></div>
              <div class="kv-item"><div class="kv-key">Tempat/Tgl Lahir</div><div class="kv-value">{{ ucwords($dataDiri['tempat_lahir'] ?? '-') }}, {{ isset($dataDiri['tanggal_lahir']) ? \Carbon\Carbon::parse($dataDiri['tanggal_lahir'])->translatedFormat('d F Y') : '-' }}</div></div>
              <div class="kv-item"><div class="kv-key">Kewarganegaraan</div><div class="kv-value">{{ strtoupper($dataDiri['kewarganegaraan'] ?? 'WNI') }}</div></div>
              <div class="kv-item"><div class="kv-key">Agama</div><div class="kv-value">{{ ucwords($dataDiri['agama'] ?? '-') }}</div></div>
              <div class="kv-item"><div class="kv-key">Pekerjaan</div><div class="kv-value">{{ ucwords($dataDiri['pekerjaan'] ?? '-') }}</div></div>
              <div class="kv-item"><div class="kv-key">Status Pernikahan</div><div class="kv-value">{{ $kawinMap[$dataDiri['status_pernikahan'] ?? ''] ?? ucwords(str_replace('_', ' ', $dataDiri['status_pernikahan'] ?? '-')) }}</div></div>
              <div class="kv-item"><div class="kv-key">RT / RW</div><div class="kv-value">{{ str_pad($dataDiri['rt'] ?? '', 3, '0', STR_PAD_LEFT) }} / {{ str_pad($dataDiri['rw'] ?? '', 3, '0', STR_PAD_LEFT) }}</div></div>
              <div class="kv-item"><div class="kv-key">{{ $pengajuan->jenis_surat == 'domisili' ? 'Alamat Sesuai KTP' : 'Alamat' }}</div><div class="kv-value">{{ ucwords($dataDiri['alamat_lengkap'] ?? '-') }} RT {{ str_pad($dataDiri['rt'] ?? '', 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($dataDiri['rw'] ?? '', 3, '0', STR_PAD_LEFT) }} Kel. {{ ucwords($dataDiri['kelurahan'] ?? 'Sambongpari') }}, Kec. {{ ucwords($dataDiri['kecamatan'] ?? 'Mangkubumi') }}</div></div>
              @if(isset($dataDiri['alamat_domisili']))
              <div class="kv-item"><div class="kv-key">Alamat Saat Ini</div><div class="kv-value">{{ ucwords($dataDiri['alamat_domisili'] ?? '-') }} RT {{ str_pad($dataDiri['rt_domisili'] ?? '', 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($dataDiri['rw_domisili'] ?? '', 3, '0', STR_PAD_LEFT) }} Kel. {{ ucwords($dataDiri['kelurahan_domisili'] ?? 'Sambongpari') }}, Kec. {{ ucwords($dataDiri['kecamatan_domisili'] ?? 'Mangkubumi') }}</div></div>
              @endif
            </div>
          </div>
          @endif

          <!-- Keterangan Khusus -->
          @if(count($dataUsaha) > 0)
          <div class="section-card card-khusus">
            <h3 class="section-card-title">Keterangan Khusus {{ strtoupper(str_replace('-', ' ', $pengajuan->jenis_surat)) }}</h3>
            <div class="kv-list">
              @foreach($dataUsaha as $key => $value)
              <div class="kv-item">
                <div class="kv-key">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                <div class="kv-value">{{ is_array($value) ? ucwords(implode(', ', $value)) : ucfirst(str_replace('_', ' ', $value)) }}</div>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Dokumen yang Diunggah -->
          <div class="section-card card-dokumen">
            <h3 class="section-card-title">Dokumen yang Diunggah</h3>
            <div class="doc-list">
              @if($pengajuan->dokumen_pendukung)
                @foreach($pengajuan->dokumen_pendukung as $key => $path)
                <a href="{{ route('preview.dokumen', ['path' => $path]) }}" target="_blank" class="doc-item">
                  <div class="doc-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                      <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                    </svg>
                  </div>
                  <div class="doc-info">
                    <h4>{{ ucwords(str_replace(['dokumen_', '_'], ['', ' '], $key)) }}</h4>
                    <p>{{ preg_replace('/^\d+_[^_]+_/', '', basename($path)) }} &mdash; {{ \Illuminate\Support\Facades\Storage::disk('public')->exists($path) ? number_format(\Illuminate\Support\Facades\Storage::disk('public')->size($path) / 1048576, 2) : '1.2' }} MB</p>
                  </div>
                </a>
                @endforeach
              @endif
            </div>
          </div>

        </div> <!-- End Left Column -->

        <!-- RIGHT COLUMN -->
        <div class="right-col">
          
          <!-- Status Pengajuan (Timeline) -->
          <div class="section-card card-lacak">
            <h3 class="section-card-title">Status Pengajuan</h3>
            <div class="stepper-v">
              
              <!-- Step 1: Pengajuan Dikirim -->
              <div class="step-v-item">
                <div class="step-v-line"></div>
                <div class="step-v-icon completed">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div class="step-v-text">
                  <h4>Pengajuan Dikirim</h4>
                  <p>{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d M Y, H:i') }}</p>
                </div>
              </div>

              <!-- Step 2: Sedang Diverifikasi -->
              <div class="step-v-item">
                <div class="step-v-line"></div>
                @if($pengajuan->status == 'menunggu')
                <div class="step-v-icon active">
                  <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c.55 0 1 .45 1 1v3c0 2.2-1.25 4.09-3.05 5-1.8.91-3.05 2.8-3.05 5v3c0 .55-.45 1-1 1H7c-.55 0-1-.45-1-1v-3c0-2.2 1.25-4.09 3.05-5C10.85 13.09 12 11.2 12 9V5c0-.55-.45-1-1-1z"></path></svg>
                </div>
                <div class="step-v-text active">
                  <h4>Sedang Diverifikasi</h4>
                  <p>Menunggu petugas...</p>
                </div>
                @else
                <div class="step-v-icon completed">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div class="step-v-text">
                  <h4>Sedang Diverifikasi</h4>
                  <p>{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d M Y, H:i') }}</p>
                </div>
                @endif
              </div>

              <!-- Step 3: Persetujuan -->
              <div class="step-v-item">
                <div class="step-v-line"></div>
                @if($pengajuan->status == 'menunggu')
                <div class="step-v-icon"></div>
                <div class="step-v-text">
                  <h4>Persetujuan</h4>
                  <p>Belum diproses</p>
                </div>
                @elseif($pengajuan->status == 'ditolak')
                <div class="step-v-icon" style="background: #fef2f2; border-color: #ef4444; color: #ef4444;">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </div>
                <div class="step-v-text">
                  <h4 style="color: #b91c1c;">Persetujuan Ditolak</h4>
                  <p>{{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y, H:i') }}</p>
                </div>
                @else
                <div class="step-v-icon completed">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div class="step-v-text">
                  <h4>Persetujuan Disetujui</h4>
                  <p>{{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y, H:i') }}</p>
                </div>
                @endif
              </div>

              <!-- Step 4: Surat Diterbitkan -->
              @if($pengajuan->status != 'ditolak')
              <div class="step-v-item">
                <div class="step-v-icon {{ ($pengajuan->status == 'disetujui' && $pengajuan->is_verified_by_lurah) ? 'completed' : '' }}">
                  @if($pengajuan->status == 'disetujui' && $pengajuan->is_verified_by_lurah)
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  @endif
                </div>
                <div class="step-v-text">
                  <h4>Surat Diterbitkan</h4>
                  @if($pengajuan->status == 'disetujui' && $pengajuan->is_verified_by_lurah)
                    <p>{{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y, H:i') }}</p>
                  @elseif($pengajuan->status == 'disetujui' && !$pengajuan->is_verified_by_lurah)
                    <p>Menunggu ditandatangani Lurah</p>
                  @else
                    <p>Belum diproses</p>
                  @endif
                </div>
              </div>
              @endif

            </div>
          </div>

          @if($pengajuan->status == 'disetujui' && $pengajuan->is_verified_by_lurah)
          <div class="section-card" style="border: 1px solid #bae6fd; padding: 16px;">
            <div style="background: #f0f9ff; border-radius: 8px; padding: 12px 16px; color: #0369a1; font-size: 0.85rem; line-height: 1.5; font-weight: 500; text-align: center;">
              Surat ini telah diverifikasi (TTE) dan sah.
            </div>
          </div>
          @endif


          @if($pengajuan->status == 'menunggu')
          <!-- Informasi Card (Waiting) -->
          <div class="section-card card-informasi-waiting">
            <h3 class="section-card-title">Informasi</h3>
            <div class="info-card-blue">
              Pengajuan kamu sedang dalam proses verifikasi oleh petugas kelurahan. Proses biasanya memakan waktu 1-2 hari kerja. Kamu akan mendapat notifikasi setelah pengajuan diproses.
            </div>
          </div>
          @endif
          
          @if($pengajuan->status == 'ditolak')
          <!-- Langkah Selanjutnya (Ditolak) -->
          <div class="section-card card-langkah" style="border: 1px solid #fca5a5; padding-top: 20px;">
            <h3 style="font-size: 1rem; font-weight: 700; color: #b91c1c; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
              <span style="display: block; width: 4px; height: 20px; background: #b91c1c; border-radius: 4px;"></span>
              Langkah Selanjutnya
            </h3>
            <div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 16px; color: #f87171; font-size: 0.85rem; line-height: 1.5; margin-bottom: 20px; text-align: center;">
              Perbaiki dokumen yang bermasalah sesuai catatan petugas, lalu ajukan kembali. Pastikan foto dokumen terang, terbaca jelas, dan sesuai dengan data yang diisi.
            </div>
            <div style="display: flex; flex-direction: column; gap: 12px;">
              <a href="{{ route('user.ajukan-surat', ['ref' => $pengajuan->id]) }}" style="background: #991b1b; color: white; text-align: center; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Ajukan Ulang</a>
              
              <form action="{{ route('user.pengajuan.destroy', $pengajuan->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini secara permanen?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="width: 100%; background: #270303; color: white; text-align: center; padding: 12px; border: none; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer;">Hapus Pengajuan</button>
              </form>
            </div>
          </div>
          @endif
          <!-- Preview Surat PDF -->
          @if($pengajuan->status == 'disetujui' && $pengajuan->is_verified_by_lurah)
          <div class="pdf-card">
            <div class="pdf-header">
              <div class="pdf-header-title">Preview Surat PDF</div>
              <a href="{{ route('cetak-surat', $pengajuan->id) }}" target="_blank" class="btn-unduh-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Unduh
              </a>
            </div>
            <div class="pdf-body">
              <div class="pdf-mockup">
                <h1>PEMERINTAH KOTA TASIKMALAYA</h1>
                <h2>KECAMATAN MANGKUBUMI</h2>
                <h3>KELURAHAN SAMBONGPARI</h3>
                <div class="pdf-mockup-address">
                  Jl. Mayor SL. Tobing - Telp :<br>
                  Email: sambongpari.kel@tasikmalaya.go.id<br>
                  Kode Pos: 46181
                </div>
                <div class="pdf-mockup-title">
                  <span class="title-text">SURAT KETERANGAN {{ strtoupper(str_replace('-', ' ', $pengajuan->jenis_surat)) }}</span><br>
                  <span class="title-num">Nomor: {{ $pengajuan->nomor_surat ?? '400.9.14.1/0247/08.1006/V/2026' }}</span>
                </div>
                
                <div style="font-size: 12px; line-height: 1.5; text-align: justify; margin-top: 15px;">
                  @if($pengajuan->jenis_surat == 'sktm' || $pengajuan->jenis_surat == 'sktm-sekolah')
                  <p style="font-size: 8px;">Berdasarkan surat pengantar dari ketua RT {{ str_pad($pengajuan->data_isian['rt'] ?? '', 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($pengajuan->data_isian['rw'] ?? '', 3, '0', STR_PAD_LEFT) }} Nomor: 18/06/2026, Kelurahan Sambongpari menerangkan bahwa:</p>
                  @else
                  <p style="font-size: 8px;">Yang bertanda tangan dibawah ini Lurah Sambongpari, Kecamatan Mangkubumi, Kota Tasikmalaya menerangkan dengan sebenarnya, bahwa :</p>
                  @endif
                  
                  <table class="pdf-table" style="margin-left: 20px; width: calc(100% - 20px);">
                    <tr><td width="100">Nama</td><td width="10">:</td><td>{{ strtoupper($pengajuan->data_isian['nama_lengkap'] ?? '') }}</td></tr>
                    <tr><td>NIK</td><td>:</td><td>{{ $pengajuan->data_isian['nik'] ?? '' }}</td></tr>
                    <tr><td>Jenis Kelamin</td><td>:</td><td>{{ strtoupper($jkMap[$pengajuan->data_isian['jenis_kelamin'] ?? ''] ?? str_replace('_', ' ', $pengajuan->data_isian['jenis_kelamin'] ?? '')) }}</td></tr>
                    <tr><td>Tempat/Tgl Lahir</td><td>:</td><td>{{ strtoupper($pengajuan->data_isian['tempat_lahir'] ?? '') }}, {{ isset($pengajuan->data_isian['tanggal_lahir']) ? \Carbon\Carbon::parse($pengajuan->data_isian['tanggal_lahir'])->translatedFormat('d-m-Y') : '' }}</td></tr>
                    <tr><td>Warganegara / Agama</td><td>:</td><td>{{ strtoupper($pengajuan->data_isian['kewarganegaraan'] ?? 'WNI') }} / {{ strtoupper($pengajuan->data_isian['agama'] ?? '') }}</td></tr>
                    <tr><td>Pekerjaan</td><td>:</td><td>{{ strtoupper($pengajuan->data_isian['pekerjaan'] ?? '') }}</td></tr>
                    <tr><td>Status Pernikahan</td><td>:</td><td>{{ strtoupper($kawinMap[$pengajuan->data_isian['status_pernikahan'] ?? ''] ?? str_replace('_', ' ', $pengajuan->data_isian['status_pernikahan'] ?? '')) }}</td></tr>
                    <tr><td valign="top">{{ $pengajuan->jenis_surat == 'domisili' ? 'Alamat Sesuai KTP' : 'Alamat' }}</td><td valign="top">:</td><td>
                        {{ strtoupper($pengajuan->data_isian['alamat_lengkap'] ?? '') }} 
                        RT {{ str_pad($pengajuan->data_isian['rt'] ?? '', 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($pengajuan->data_isian['rw'] ?? '', 3, '0', STR_PAD_LEFT) }}<br>
                        KELURAHAN {{ strtoupper($pengajuan->data_isian['kelurahan'] ?? 'SAMBONGPARI') }} KECAMATAN {{ strtoupper($pengajuan->data_isian['kecamatan'] ?? 'MANGKUBUMI') }}
                    </td></tr>
                  </table>

                  @if($pengajuan->jenis_surat == 'sktm' || $pengajuan->jenis_surat == 'sktm-sekolah')
                  <p style="margin-top: 10px; font-size: 8px;">adalah benar-benar penduduk kami yang kondisi ekonominya termasuk dalam kategori tidak mampu. Surat keterangan ini dibuat untuk keperluan {{ strtolower(str_replace('_', ' ', $pengajuan->data_isian['keperluan'] ?? 'melengkapi persyaratan')) }}@if(isset($pengajuan->data_isian['nama_yang_menggunakan_surat']) || isset($pengajuan->data_isian['nama_yang_dibantu'])) atas nama <strong>{{ strtoupper($pengajuan->data_isian['nama_yang_menggunakan_surat'] ?? $pengajuan->data_isian['nama_yang_dibantu'] ?? '') }}</strong>@endif.</p>
                  <p style="margin-top: 10px; font-size: 8px;">Demikian agar dipergunakan sebagaimana mestinya.</p>
                  @else
                  <p style="margin-top: 10px; font-size: 8px;">Surat Keterangan ini dibuat untuk keperluan {{ ucfirst(str_replace('_', ' ', $pengajuan->data_isian['keperluan'] ?? 'sebagaimana mestinya')) }}.</p>
                  <p style="margin-top: 10px; font-size: 8px;">Demikian surat keterangan ini dibuat, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
                  @endif
                  
                  <div style="float: right; text-align: center; margin-top: 20px; width: 150px; font-size: 7px; position: relative;">
                    Tasikmalaya, {{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y') }}<br>
                    a.n CAMAT MANGKUBUMI<br>
                    @php
                        $qrCodeMockup = null;
                        if ($pengajuan->token_validasi) {
                            $path = route('validasi', ['token' => $pengajuan->token_validasi], false);
                            $qrUrl = rtrim(config('app.url'), '/') . $path;
                            $qrCodeMockup = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(40)->margin(0)->generate($qrUrl));
                        }
                    @endphp
                    @if($qrCodeMockup)
                    <div style="width: 40px; height: 40px; margin: 5px auto; position: relative; z-index: 2; background: white; padding: 2px; border-radius: 4px; border: 1px solid #111;">
                      <img src="data:image/svg+xml;base64,{{ $qrCodeMockup }}" alt="QR" style="width: 100%; height: auto; display: block;">
                    </div>
                    @else
                    <div style="border: 1px solid #111; border-radius: 8px; width: 40px; height: 40px; margin: 5px auto; display: flex; align-items: center; justify-content: center; position: relative; z-index: 2; background: rgba(255,255,255,0.8);">QR</div>
                    @endif
                  </div>
                  <div style="clear: both;"></div>
                </div>
              </div>
            </div>
          </div>
          @endif

        </div> <!-- End Right Column -->
      </div> <!-- End Grid -->

    </main>
  </div>
</div>



<script src="{{ asset('js/logout.js') }}"></script>
<script>
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  hamburgerBtn.addEventListener('click', () => {
    const isCol = sidebar.classList.toggle('collapsed');
    overlay.classList.toggle('active', !isCol);
  });
  overlay.addEventListener('click', () => {
    sidebar.classList.add('collapsed');
    overlay.classList.remove('active');
  });
</script>
</body>
</html>
