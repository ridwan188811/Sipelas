<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Detail Pengajuan – SIPELAS</title>
  <meta name="description" content="Detail pengajuan surat SIPELAS – Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari" />
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

    /* ===== VARIABLES ===== */
    :root {
      --navy:        #1a3558;
      --navy-dark:   #152c48;
      --sidebar-bg:  #1e4070;
      --blue:        #2563eb;
      --blue-light:  #dbeafe;
      --blue-text:   #1d4ed8;
      --green:       #16a34a;
      --amber:       #d97706;
      --red:         #dc2626;
      --gray-50:     #f8fafc;
      --gray-100:    #f1f5f9;
      --gray-200:    #e2e8f0;
      --gray-300:    #cbd5e1;
      --gray-400:    #94a3b8;
      --gray-500:    #64748b;
      --gray-600:    #475569;
      --gray-800:    #1e293b;
      --white:       #ffffff;
      --shadow-sm:   0 1px 3px rgba(0,0,0,.08);
      --shadow:      0 2px 10px rgba(0,0,0,.10);
      --radius:      10px;
      --sidebar-w:   220px;
      --header-h:    60px;
    }

    /* =============================================
       LAYOUT
    ============================================= */
    .header {
      position: sticky; top: 0; z-index: 50; background: var(--navy); height: var(--header-h);
      display: flex; align-items: center; padding: 0 20px; gap: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.2); flex-shrink: 0;
    }
    .page-body { display: flex; flex: 1; min-height: 0; }

    /* =============================================
       SIDEBAR
    ============================================= */
    .sidebar {
      width: var(--sidebar-w); background: var(--white); border-right: 1px solid var(--gray-200);
      display: flex; flex-direction: column; flex-shrink: 0; position: sticky; top: var(--header-h);
      height: calc(100vh - var(--header-h)); overflow-y: auto; transition: width .28s cubic-bezier(.4,0,.2,1), transform .28s cubic-bezier(.4,0,.2,1); z-index: 30;
    }
    .sidebar.collapsed { width: 0; overflow: hidden; border-right: none; }
    .sidebar-nav { flex: 1; padding: 20px 12px; display: flex; flex-direction: column; gap: 4px; }
    .nav-item {
      display: flex; align-items: center; gap: 11px; padding: 11px 16px; border-radius: 9px;
      color: var(--gray-600); text-decoration: none; font-size: .9rem; font-weight: 500; white-space: nowrap; transition: background .18s, color .18s;
    }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; color: var(--navy); transition: color .18s; }
    .nav-item:hover { background: var(--gray-100); color: var(--gray-800); }
    .nav-item:hover svg { color: var(--navy); }
    .nav-item.active { background: var(--navy); color: var(--white); font-weight: 600; }
    .nav-item.active svg { color: var(--white); }

    /* =============================================
       HEADER PARTS
    ============================================= */
    .hamburger-btn {
      background: none; border: none; cursor: pointer; padding: 7px; display: flex; align-items: center; justify-content: center;
      border-radius: 7px; color: white; transition: background .18s; flex-shrink: 0;
    }
    .hamburger-btn:hover { background: rgba(255,255,255,.12); }
    .hamburger-btn svg { width: 22px; height: 22px; }
    .header-brand { display: flex; align-items: center; gap: 10px; flex: 1; }
    .header-brand-icon {
      width: 34px; height: 34px; background: transparent; border-radius: 8px;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .header-brand-icon svg { width: 18px; height: 18px; fill: white; }
    .header-brand-text { color: white; line-height: 1.2; }
    .header-brand-text .app-name { font-size: 1.05rem; font-weight: 700; letter-spacing: .02em; }
    .header-brand-text .app-sub  { font-size: .62rem; opacity: .65; font-weight: 400; }

    .header-actions { display: flex; align-items: center; gap: 8px; }
    .notif-btn {
      position: relative; background: none; border: none; cursor: pointer; padding: 7px; color: rgba(255,255,255,.9);
      border-radius: 7px; transition: background .18s; display: flex;
    }
    .notif-btn:hover { background: rgba(255,255,255,.12); }
    .notif-btn svg { width: 20px; height: 20px; }
    .notif-badge {
      position: absolute; top: 5px; right: 5px; width: 7px; height: 7px; background: #ef4444; border-radius: 50%; border: 2px solid var(--navy);
    }
    .user-avatar {
      width: 34px; height: 34px; background: var(--gray-400); border-radius: 50%; display: flex; align-items: center; justify-content: center;
      font-weight: 700; color: white; font-size: .88rem; cursor: pointer; transition: opacity .18s; flex-shrink: 0; text-decoration: none;
    }

    /* ===== SIDEBAR LOGOUT ===== */
    .sidebar-footer { padding: 12px; border-top: 1px solid var(--gray-200); margin-top: auto; }
    .logout-btn {
      display: flex; align-items: center; gap: 11px; width: 100%; padding: 11px 16px; border-radius: 9px; border: none; background: none;
      color: var(--gray-600); font-size: .9rem; font-weight: 500; cursor: pointer; text-decoration: none; white-space: nowrap; transition: background .18s, color .18s;
    }
    .logout-btn svg { width: 18px; height: 18px; flex-shrink: 0; color: #ef4444; transition: color .18s; }
    .logout-btn:hover { background: #fef2f2; color: #b91c1c; }
    .logout-btn:hover svg { color: #b91c1c; }

    /* =============================================
       CONTENT AREA
    ============================================= */
    .content-area { flex: 1; display: flex; flex-direction: column; min-width: 0; }
    .content { flex: 1; padding: 28px 28px; max-width: 1100px; margin: 0 auto; width: 100%; }

    /* ===== DETAIL HEADER ===== */
    .detail-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .detail-title { font-size: 1.15rem; font-weight: 700; color: var(--gray-800); }
    .btn-kembali { background: #8b8b8b; color: white; border: none; padding: 8px 18px; border-radius: 6px; font-weight: 600; font-size: .85rem; cursor: pointer; text-decoration: none; transition: background .15s; }
    .btn-kembali:hover { background: #71717a; }

    /* ===== ALERT SUCCESS ===== */
    .alert-success-card { background: #e8f5e9; border: 1px solid #c8e6c9; border-radius: var(--radius); padding: 18px 24px; display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .alert-icon-box { width: 44px; height: 44px; background: #4ade80; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; }
    .alert-text { min-width: 0; }
    .alert-text h3 { font-size: 1rem; font-weight: 700; color: #166534; margin-bottom: 4px; }
    .alert-text p { font-size: .85rem; color: #166534; opacity: 0.85; }

    /* ===== MAIN LAYOUT ===== */
    .detail-content { display: flex; flex-direction: column; gap: 24px; min-width: 0; }

    /* ===== SECTION CARDS ===== */
    .section-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow-sm); min-width: 0; }
    .section-card-title { font-size: 1rem; font-weight: 700; color: var(--navy); margin-bottom: 22px; display: flex; align-items: center; gap: 10px; min-width: 0; }
    .section-card-title::before { content: ''; display: block; width: 4px; height: 18px; background: var(--navy); border-radius: 4px; flex-shrink: 0; }

    /* ===== KEY-VALUE LIST ===== */
    .kv-list { display: flex; flex-direction: column; gap: 16px; min-width: 0; }
    .kv-item { display: grid; grid-template-columns: 180px 1fr; gap: 16px; font-size: .9rem; min-width: 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
    .kv-item:last-child { border-bottom: none; padding-bottom: 0; }
    .kv-key { color: var(--gray-600); font-weight: 600; min-width: 0; }
    .kv-value { color: var(--gray-800); font-weight: 500; line-height: 1.4; word-break: break-word; min-width: 0; }

    /* ===== DOCUMENT LIST GRID ===== */
    .doc-list { display: flex; flex-direction: column; gap: 16px; }
    .doc-item { display: flex; align-items: center; gap: 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; transition: border-color .2s, background .2s; cursor: pointer; }
    .doc-item:hover { border-color: #cbd5e1; background: white; }
    .doc-icon { width: 44px; height: 44px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--gray-400); flex-shrink: 0; }
    .doc-icon svg { width: 20px; height: 20px; }
    .doc-info { min-width: 0; flex: 1; }
    .doc-info h4 { font-size: .9rem; font-weight: 700; color: var(--gray-800); margin-bottom: 4px; text-transform: capitalize; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .doc-info p { font-size: .75rem; color: var(--gray-500); display: flex; align-items: center; white-space: nowrap; overflow: hidden; }
    .preview-dokumen { color: var(--gray-500); text-decoration: none; display: inline-block; max-width: calc(100% - 70px); overflow: hidden; text-overflow: ellipsis; }

    /* ===== HORIZONTAL STEPPER ===== */
    .stepper-container { background: white; border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 32px 24px; position: relative; }
    .stepper { display: flex; justify-content: space-between; position: relative; max-width: 600px; margin: 0 auto; }
    .stepper::before { content: ''; position: absolute; left: 40px; right: 40px; top: 18px; height: 2px; background: #e2e8f0; z-index: 1; }
    
    .step-item { position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 12px; width: 80px; text-align: center; }
    .step-icon { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: white; border: 2px solid #e2e8f0; color: #94a3b8; transition: all 0.3s; }
    .step-icon svg { width: 18px; height: 18px; }
    
    .step-item.active .step-icon { border-color: var(--blue); color: var(--blue); box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.1); }
    .step-item.completed .step-icon { background: var(--green); border-color: var(--green); color: white; }
    .step-item.rejected .step-icon { background: var(--red); border-color: var(--red); color: white; }
    
    .step-label { font-size: .8rem; font-weight: 600; color: #64748b; line-height: 1.3; }
    .step-item.active .step-label { color: var(--blue); }
    .step-item.completed .step-label { color: var(--green); }
    .step-item.rejected .step-label { color: var(--red); }
    .step-date { font-size: 0.65rem; color: #94a3b8; margin-top: 2px; }

    /* Sticky Action Bar */
    .sticky-action-bar { position: sticky; bottom: 0; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); padding: 20px 40px; border-top: 1px solid var(--gray-200); box-shadow: 0 -10px 20px rgba(0,0,0,.03); z-index: 40; margin: 0 -40px -32px -40px; display: flex; justify-content: flex-end; align-items: center; gap: 16px; }
    .sticky-action-text { flex: 1; font-size: 0.85rem; color: var(--gray-500); }
    
    .btn-decision { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: .9rem; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: transform .15s, opacity .15s; text-decoration: none; }
    .btn-decision:hover { opacity: .9; transform: translateY(-1px); }
    .btn-decision:active { transform: translateY(0); }
    .btn-publish { background: var(--green-dark); color: white; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2); }

    /* ===== PDF PREVIEW CARD ===== */
    .pdf-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow-sm); }
    .pdf-header { background: #166534; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; }
    .pdf-header-title { color: white; font-size: 1rem; font-weight: 700; line-height: 1.3; max-width: 120px; }
    .btn-unduh-sm { background: transparent; border: 1px solid rgba(255,255,255,0.4); color: white; padding: 8px 14px; border-radius: 6px; font-size: .7rem; font-weight: 600; display: flex; flex-direction: column; align-items: center; gap: 4px; cursor: pointer; text-decoration: none; transition: background .15s; }
    .btn-unduh-sm svg { width: 16px; height: 16px; }
    .btn-unduh-sm:hover { background: rgba(255,255,255,0.1); }
    
    .pdf-body { padding: 24px; background: #f8fafc; display: flex; justify-content: center; }
    .pdf-mockup { width: 100%; max-width: 320px; background: white; box-shadow: 0 4px 12px rgba(0,0,0,0.06); padding: 28px 24px; font-family: 'Times New Roman', Times, serif; font-size: 8px; color: #111; line-height: 1.3; }
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

    /* ===== MOBILE OVERLAY ===== */
    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 25; }
    .sidebar-overlay.active { display: block; }

    /* =============================================
       RESPONSIVE
    ============================================= */
    .mobile-bottom-nav { display: none; }

    @media (max-width: 900px) { .detail-grid { grid-template-columns: 1fr; } }
    @media (max-width: 680px) {
      /* Bottom Navigation */
      .mobile-bottom-nav {
        display: flex; position: fixed; bottom: 0; left: 0; right: 0;
        background: var(--white); border-top: 1px solid var(--gray-200);
        z-index: 100; padding-bottom: env(safe-area-inset-bottom);
        box-shadow: 0 -2px 10px rgba(0,0,0,.05);
      }
      .bottom-nav-item {
        flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 10px 0; color: var(--gray-500); text-decoration: none; font-size: .65rem;
        font-weight: 600; gap: 4px; -webkit-user-select: none; user-select: none;
        -webkit-tap-highlight-color: transparent;
      }
      .bottom-nav-item svg { width: 22px; height: 22px; transition: color .2s; }
      .bottom-nav-item.active { color: var(--navy); }
      .bottom-nav-item:active { background: var(--gray-50); }

      /* Hide Desktop Sidebar & Adjust Body */
      .page-body { padding-bottom: 65px; }
      .sidebar { display: none !important; }
      .hamburger-btn { display: none !important; }
      .sidebar-overlay { display: none !important; }
      .content { padding: 18px 14px; }
      .kv-item { grid-template-columns: 120px 1fr; gap: 8px; }
      .kv-key { font-size: .85rem; }
      .action-bar-card { flex-direction: column; align-items: flex-start !important; gap: 16px; padding: 16px !important; }
      .action-bar-card > div:last-child { width: 100%; justify-content: flex-end; }
    }
  </style>
</head>
<body>

<!-- ===== HEADER ===== -->
<header class="header">
  <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu" aria-expanded="true" aria-controls="sidebar">
    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-brand">
    <div class="header-brand-icon"><img src="{{ asset('images/logo_tasikmalaya.png') }}" alt="Logo Tasikmalaya" style="width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));"></div>
    <div class="header-brand-text">
      <div class="app-name">SIPELAS</div>
      <div class="app-sub">Sistem Informasi Pelayanan Masyarakat</div>
    </div>
  </div>
  <div class="header-actions">
    <div class="notif-wrapper" style="position: relative;">
      <button class="notif-btn" id="notifBtn" aria-label="Notifikasi" onclick="const d = document.getElementById('notifDropdown'); d.style.display = d.style.display === 'none' ? 'block' : 'none';">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        @if($globalNotifCount > 0)
        <span class="notif-badge" style="position: absolute; top: 5px; right: 5px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; border: 2px solid var(--navy);"></span>
        @endif
      </button>
      <div id="notifDropdown" class="notif-dropdown" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; width: 320px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); z-index: 100; overflow: hidden; text-align: left;">
        <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
          Notifikasi
          @if($globalNotifCount > 0)
          <span style="background: #ef4444; color: #fff; font-size: 0.7rem; padding: 2px 8px; border-radius: 10px;">{{ $globalNotifCount }} Baru</span>
          @endif
        </div>
        <div style="max-height: 350px; overflow-y: auto;">
          @forelse($globalNotifList as $notif)
            @php
              $route = Auth::user()->role == 'admin' ? route('admin.detail-pengajuan', $notif->id) : route('user.detail-pengajuan', $notif->id);
              $jenis = ['sku'=>'Surat Keterangan Usaha','sktm'=>'Surat Keterangan Tidak Mampu','sktm-sekolah'=>'Surat Keterangan Tidak Mampu (Sekolah)','domisili'=>'Surat Keterangan Domisili','belum-menikah'=>'Surat Keterangan Belum Menikah','kelahiran'=>'Surat Keterangan Kelahiran','kematian'=>'Surat Keterangan Kematian','pengantar-nikah'=>'Surat Pengantar Nikah','pindah'=>'Surat Keterangan Pindah'][$notif->jenis_surat] ?? ucwords(str_replace('-', ' ', $notif->jenis_surat));
            @endphp
            <a href="{{ $route }}" style="display: block; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; text-decoration: none; transition: background .15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
              @if(Auth::user()->role == 'admin')
                <div style="font-size: 0.85rem; color: #1e293b; font-weight: 600; margin-bottom: 4px;">Pengajuan Baru: {{ $jenis }}</div>
                <div style="font-size: 0.75rem; color: #64748b;">Dari: {{ $notif->user->name ?? explode('@', $notif->user->email)[0] }}</div>
              @else
                <div style="font-size: 0.85rem; color: #1e293b; font-weight: 600; margin-bottom: 4px;">Surat {{ $jenis }} {{ ucfirst($notif->status) }}</div>
                <div style="font-size: 0.75rem; color: #64748b;">Pengajuan surat Anda telah {{ $notif->status }} oleh kelurahan.</div>
              @endif
              <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 6px;">{{ \Carbon\Carbon::parse($notif->updated_at)->translatedFormat('d M Y, H:i') }}</div>
            </a>
          @empty
            <div style="padding: 20px; text-align: center; color: #64748b; font-size: 0.85rem;">Belum ada notifikasi</div>
          @endforelse
        </div>
      </div>
    </div>
    <a href="{{ route('user.profil') }}" class="user-avatar" title="{{ Auth::user()->name ?? Auth::user()->email }}" style="text-decoration: none;">{{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email, 0, 1)) }}</a>
  </div>
  <script>
    document.addEventListener('click', function(e) {
      const notifBtn = document.getElementById('notifBtn');
      const notifDropdown = document.getElementById('notifDropdown');
      if (notifBtn && notifDropdown) {
        if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
          notifDropdown.style.display = 'none';
        }
      }
    });
  </script>
</header>

<!-- ===== PAGE BODY ===== -->
<div class="page-body">
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar collapsed" id="sidebar" aria-label="Navigasi Utama">
    <nav class="sidebar-nav">
      <a href="{{ route('user.dashboard') }}" class="nav-item" id="nav-dashboard"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg> Dashboard</a>
      <a href="{{ route('user.ajukan-surat') }}" class="nav-item" id="nav-ajukan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9"  y1="15" x2="15" y2="15"/></svg> Ajukan Surat</a>
      <a href="{{ route('user.riwayat') }}" class="nav-item active" id="nav-riwayat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg> Riwayat</a>
    </nav>
    <div class="sidebar-footer">
      <a href="{{ route('logout') }}" class="logout-btn" id="logoutBtn" onclick="confirmLogout(event, this.href);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Keluar</a>
    </div>
  </aside>

  <!-- ===== CONTENT AREA ===== -->
  <div class="content-area">
    <main class="content">

      <!-- Detail Header -->
      <div class="detail-header">
        <h2 class="detail-title">Detail Pengajuan Surat</h2>
        <a href="{{ route('user.riwayat') }}" class="btn-kembali">Kembali</a>
      </div>

      <!-- Alert Card Dynamic -->
      @if($pengajuan->status == 'menunggu')
      <div class="alert-success-card" style="background:#fffbeb; border-color:#fef3c7;">
        <div class="alert-icon-box" style="background:#f59e0b;">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
            <path d="M6 2v6h.01L10 12l-3.99 4H6v6h12v-6h-.01L14 12l3.99-4H18V2H6zm10 14.5V20H8v-3.5l4-4 4 4zm-4-5l-4-4V4h8v3.5l-4 4z"/>
          </svg>
        </div>
        <div class="alert-text">
          <h3 style="color:#b45309;">Sedang Diproses</h3>
          <p style="color:#b45309;">Pengajuan kamu sedang diverifikasi oleh petugas kelurahan. Harap tunggu.</p>
        </div>
      </div>
      @elseif($pengajuan->status == 'disetujui')
        <div class="alert-success-card">
          <div class="alert-icon-box">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
          </div>
          <div class="alert-text">
            <h3>Pengajuan Disetujui</h3>
            @if($pengajuan->is_verified_by_lurah)
              <p>Surat telah ditandatangani secara elektronik (TTE) dan <strong>siap untuk diunduh/dicetak</strong>.</p>
            @else
              <p>Berkas lolos verifikasi. Harap tunggu proses <strong>Tanda Tangan Elektronik (TTE) Lurah</strong> sebelum surat dapat dicetak.</p>
            @endif
          </div>
        </div>
      @elseif($pengajuan->status == 'ditolak')
      <div class="alert-success-card" style="background:#fef2f2; border-color:#fecaca; align-items: flex-start; padding: 24px;">
        <div class="alert-icon-box" style="background:#ef4444; border-radius: 12px; margin-top: 2px;">
          <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </div>
        <div class="alert-text" style="width: 100%;">
          <h3 style="color:#b91c1c; font-size: 1.05rem; margin-bottom: 8px;">Pengajuan Ditolak</h3>
          <p style="color:#b91c1c; margin-bottom: 16px;">Pengajuan {{ 
            [
              'sku' => 'Surat Keterangan Usaha',
              'sktm' => 'Surat Keterangan Tidak Mampu',
              'sktm-sekolah' => 'Surat Keterangan Tidak Mampu (Sekolah)',
              'domisili' => 'Surat Keterangan Domisili',
              'belum-menikah' => 'Surat Keterangan Belum Menikah',
              'kelahiran' => 'Surat Keterangan Kelahiran',
              'kematian' => 'Surat Keterangan Kematian',
              'pengantar-nikah' => 'Surat Pengantar Nikah',
              'pindah' => 'Surat Keterangan Pindah'
            ][$pengajuan->jenis_surat] ?? ucwords(str_replace('-', ' ', $pengajuan->jenis_surat))
          }} kamu ditolak oleh petugas kelurahan pada {{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d F Y, H:i') }} WIB. Silakan perbaiki dokumen dan ajukan kembali.</p>
          <div style="background: #fff; border: 1px solid #fecaca; border-radius: 8px; padding: 16px;">
            <div style="color: #ef4444; font-weight: 700; font-size: 0.85rem; margin-bottom: 8px;">ALASAN PENOLAKAN:</div>
            <p style="color: #475569; font-size: 0.9rem; word-break: break-word;">{{ $pengajuan->catatan_admin ?? 'Tidak ada keterangan spesifik.' }}</p>
          </div>
        </div>
      </div>
      @endif

      <!-- Main Layout -->
      <div class="detail-content">

        <!-- HORIZONTAL STEPPER -->
        <div class="stepper-container" style="margin-bottom: 8px;">
          <div class="stepper">
            <!-- Step 1: Dikirim -->
            <div class="step-item completed">
              <div class="step-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
              </div>
              <div class="step-label">Dikirim</div>
              <div class="step-date">{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d M Y') }}</div>
            </div>

            <!-- Step 2: Pemeriksaan -->
            @php
              $step2Class = 'active';
              if ($pengajuan->status == 'disetujui' || $pengajuan->status == 'ditolak') $step2Class = 'completed';
            @endphp
            <div class="step-item {{ $step2Class }}">
              <div class="step-icon">
                @if($step2Class == 'completed')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                @else
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                @endif
              </div>
              <div class="step-label">Pemeriksaan</div>
              <div class="step-date">
                @if($pengajuan->status != 'menunggu')
                  {{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y') }}
                @else
                  Menunggu
                @endif
              </div>
            </div>

            <!-- Step 3: Keputusan / TTE -->
            @php
              $step3Class = '';
              $step3Label = 'TTE Lurah';
              if ($pengajuan->status == 'ditolak') {
                  $step3Class = 'rejected';
                  $step3Label = 'Ditolak';
              } elseif ($pengajuan->status == 'disetujui') {
                  if ($pengajuan->is_verified_by_lurah) {
                      $step3Class = 'completed';
                  } else {
                      $step3Class = 'active';
                  }
              }
            @endphp
            <div class="step-item {{ $step3Class }}">
              <div class="step-icon">
                @if($step3Class == 'completed')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                @elseif($step3Class == 'rejected')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                @elseif($step3Class == 'active')
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                @else
                <span style="font-size: 14px;">3</span>
                @endif
              </div>
              <div class="step-label">{{ $step3Label }}</div>
              <div class="step-date">
                @if($step3Class == 'completed' || $step3Class == 'rejected')
                  {{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y') }}
                @else
                  -
                @endif
              </div>
            </div>
          </div>
        </div>
        
        <!-- Info Cards -->
        @if($pengajuan->status == 'menunggu')
        <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:16px; color:#1d4ed8; font-size:.85rem; line-height:1.5;">
          Pengajuan kamu sedang dalam proses verifikasi oleh petugas kelurahan. Proses biasanya memakan waktu 1-2 hari kerja. Kamu akan mendapat notifikasi setelah pengajuan diproses.
        </div>
        @elseif($pengajuan->status == 'disetujui' && $pengajuan->is_verified_by_lurah)
        <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:16px; color:#0284c7; font-size:.85rem; line-height:1.5; display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20" style="flex-shrink:0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
            Surat kamu telah diverifikasi dan sah. Silakan unduh surat pada preview di bawah ini.
        </div>
        @endif

          <!-- Card: Informasi Pengajuan -->
          <div class="section-card">
            <h3 class="section-card-title">Informasi Pengajuan</h3>
            <div class="kv-list">
              <div class="kv-item"><div class="kv-key">Jenis Surat</div><div class="kv-value">{{ 
                [
                  'sku' => 'Surat Keterangan Usaha',
                  'sktm' => 'Surat Keterangan Tidak Mampu',
                  'sktm-sekolah' => 'Surat Keterangan Tidak Mampu (Sekolah)',
                  'domisili' => 'Surat Keterangan Domisili',
                  'belum-menikah' => 'Surat Keterangan Belum Menikah',
                  'kelahiran' => 'Surat Keterangan Kelahiran',
                  'kematian' => 'Surat Keterangan Kematian',
                  'pengantar-nikah' => 'Surat Pengantar Nikah',
                  'pindah' => 'Surat Keterangan Pindah'
                ][$pengajuan->jenis_surat] ?? ucwords(str_replace('-', ' ', $pengajuan->jenis_surat))
              }}</div></div>
              <div class="kv-item"><div class="kv-key">Tanggal Pengajuan</div><div class="kv-value">{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y, H:i') }} WIB</div></div>
              @if($pengajuan->status == 'disetujui')
              <div class="kv-item"><div class="kv-key">Tanggal Disetujui</div><div class="kv-value">{{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d F Y, H:i') }} WIB</div></div>
              @elseif($pengajuan->status == 'ditolak')
              <div class="kv-item"><div class="kv-key">Tanggal Diproses</div><div class="kv-value">{{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d F Y, H:i') }} WIB</div></div>
              @endif
              @if(strtolower($pengajuan->status) != 'menunggu')
              <div class="kv-item"><div class="kv-key">Diproses Oleh</div><div class="kv-value">Endang Saripudin</div></div>
              @endif

              <div class="kv-item"><div class="kv-key">Status</div><div class="kv-value">
                @if(strtolower($pengajuan->status) == 'disetujui')
                  <span class="badge" style="background:#d1fae5;color:#065f46;padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:600;display:inline-block;">Disetujui</span>
                @elseif(strtolower($pengajuan->status) == 'ditolak')
                  <span class="badge" style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:600;display:inline-block;">Ditolak</span>
                @else
                  <span class="badge" style="background:#fef3c7;color:#92400e;padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:600;display:inline-block;">Menunggu</span>
                @endif
              </div></div>
              
              @if(strtolower($pengajuan->status) == 'disetujui')
              <div class="kv-item"><div class="kv-key">Nomor Surat</div><div class="kv-value">{{ $pengajuan->nomor_surat ?? '-' }}</div></div>
              @endif
            </div>
          </div>

          <!-- Card: Data Isian (Dinamis) -->
          @php
            $dataDiriKeys = ['nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'kewarganegaraan', 'agama', 'pekerjaan', 'status_pernikahan', 'rt', 'rw', 'alamat_lengkap', 'kelurahan', 'kecamatan', 'kota', 'nama_yang_menggunakan_surat', 'hubungan_dengan_pemohon', 'lama_berdomisili', 'status_pemilikan___alasan'];
            $dataDiri = [];
            $dataUsaha = [];
            
            if ($pengajuan->data_isian) {
                foreach($pengajuan->data_isian as $key => $value) {
                    // Sembunyikan field dokumen dari data isian karena sudah ada di Dokumen Pendukung
                    if (str_starts_with($key, 'dokumen_')) continue;
                    
                    if (in_array($key, $dataDiriKeys)) {
                        $dataDiri[$key] = $value;
                    } else {
                        $dataUsaha[$key] = $value;
                    }
                }
            }
            
            $jkMap = ['l' => 'Laki-laki', 'p' => 'Perempuan'];
            $kawinMap = ['belum' => 'Belum Kawin', 'kawin' => 'Kawin', 'cerai_hidup' => 'Cerai Hidup', 'cerai_mati' => 'Cerai Mati'];
          @endphp

          @if(count($dataDiri) > 0)
          <div class="section-card">
            <h3 class="section-card-title">Data Diri Pemohon</h3>
            <div class="kv-list">
              @php
                $orderedKeys = ['nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'kewarganegaraan', 'agama', 'pekerjaan', 'status_pernikahan', 'rt', 'alamat_lengkap', 'lama_berdomisili', 'status_pemilikan___alasan'];
                $remainingKeys = array_diff(array_keys($dataDiri), array_merge($orderedKeys, ['tanggal_lahir', 'rw', 'kelurahan', 'kecamatan', 'kota']));
                $finalKeys = array_merge($orderedKeys, $remainingKeys);
              @endphp
              @foreach($finalKeys as $key)
                @if(!isset($dataDiri[$key]) && $key != 'rt' && $key != 'tempat_lahir') @continue @endif
                @php $value = $dataDiri[$key] ?? ''; @endphp
                
                @if($key == 'tempat_lahir' && isset($dataDiri['tanggal_lahir']))
                   <div class="kv-item">
                     <div class="kv-key">Tempat/Tgl Lahir</div>
                     <div class="kv-value">{{ strtoupper($value) }}, {{ strtoupper(\Carbon\Carbon::parse($dataDiri['tanggal_lahir'])->translatedFormat('d F Y')) }}</div>
                   </div>
                @elseif($key == 'rt')
                   @if(isset($dataDiri['rt']) || isset($dataDiri['rw']))
                   <div class="kv-item">
                     <div class="kv-key">RT / RW</div>
                     <div class="kv-value">{{ str_pad($dataDiri['rt'] ?? '', 3, '0', STR_PAD_LEFT) }} / {{ str_pad($dataDiri['rw'] ?? '', 3, '0', STR_PAD_LEFT) }}</div>
                   </div>
                   @endif
                @elseif($key == 'alamat_lengkap')
                   <div class="kv-item">
                     <div class="kv-key">{{ $pengajuan->jenis_surat == 'domisili' ? 'Alamat Domisili' : 'Alamat' }}</div>
                     <div class="kv-value">
                        {{ strtoupper($value) }}
                        @if(isset($dataDiri['rt']) && isset($dataDiri['rw'])) RT {{ str_pad($dataDiri['rt'], 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($dataDiri['rw'], 3, '0', STR_PAD_LEFT) }} @endif
                        @if(isset($dataDiri['kelurahan'])) KEL. {{ strtoupper($dataDiri['kelurahan']) }}, @endif
                        @if(isset($dataDiri['kecamatan'])) KEC. {{ strtoupper($dataDiri['kecamatan']) }} @endif
                     </div>
                   </div>
                @elseif($key == 'jenis_kelamin')
                   <div class="kv-item">
                     <div class="kv-key">Jenis Kelamin</div>
                     <div class="kv-value">{{ strtoupper($jkMap[$value] ?? str_replace('_', ' ', $value)) }}</div>
                   </div>
                @elseif($key == 'status_pernikahan')
                   <div class="kv-item">
                     <div class="kv-key">Status Pernikahan</div>
                     <div class="kv-value">{{ strtoupper($kawinMap[$value] ?? str_replace('_', ' ', $value)) }}</div>
                   </div>
                @elseif($key == 'nama_lengkap')
                   <div class="kv-item">
                     <div class="kv-key">Nama Lengkap</div>
                     <div class="kv-value">{{ strtoupper($value) }}</div>
                   </div>
                @elseif($key == 'kewarganegaraan')
                   <div class="kv-item">
                     <div class="kv-key">Kewarnegaraan</div>
                     <div class="kv-value">{{ strtoupper($value) }}</div>
                   </div>
                @elseif($key == 'agama')
                   <div class="kv-item">
                     <div class="kv-key">Agama</div>
                     <div class="kv-value">{{ strtoupper($value) }}</div>
                   </div>
                @elseif($key == 'status_pemilikan___alasan')
                   <div class="kv-item">
                     <div class="kv-key">Status Tempat Tinggal</div>
                     <div class="kv-value">{{ strtoupper(str_replace('_', ' ', $value)) }}</div>
                   </div>
                @elseif($key == 'lama_berdomisili')
                   <div class="kv-item">
                     <div class="kv-key">Lama Berdomisili</div>
                     <div class="kv-value">
                        @if($value == 'kurang_1_tahun') KURANG DARI 1 TAHUN
                        @elseif($value == '1_5_tahun') 1 - 5 TAHUN
                        @elseif($value == 'lebih_5_tahun') LEBIH DARI 5 TAHUN
                        @else {{ strtoupper(str_replace('_', ' ', $value)) }} @endif
                     </div>
                   </div>
                @else
                   <div class="kv-item">
                     <div class="kv-key">{{ strtoupper($key) == 'NIK' ? 'NIK' : ucwords(str_replace('_', ' ', $key)) }}</div>
                     <div class="kv-value">{{ is_array($value) ? strtoupper(implode(', ', $value)) : strtoupper($value) }}</div>
                   </div>
                @endif
              @endforeach
            </div>
          </div>
          @endif

          @if(count($dataUsaha) > 0)
          <div class="section-card">
            <h3 class="section-card-title">Keterangan Khusus {{ strtoupper(str_replace('-', ' ', $pengajuan->jenis_surat)) }}</h3>
            <div class="kv-list">
              @php
                if ($pengajuan->jenis_surat == 'sku') {
                    $orderedUsahaKeys = ['bentuk_usaha', 'bidang_usaha', 'barang_usaha', 'keadaan_usaha_saat_ini', 'sejak_tahun', 'alamat_usaha', 'keperluan'];
                    $remainingUsahaKeys = array_diff(array_keys($dataUsaha), $orderedUsahaKeys);
                    $finalUsahaKeys = array_merge($orderedUsahaKeys, $remainingUsahaKeys);
                } else {
                    $finalUsahaKeys = array_keys($dataUsaha);
                }
              @endphp
              @foreach($finalUsahaKeys as $key)
                @if(!isset($dataUsaha[$key])) @continue @endif
                @php $value = $dataUsaha[$key]; @endphp
                <div class="kv-item">
                  <div class="kv-key">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                  <div class="kv-value">{{ is_array($value) ? strtoupper(implode(', ', $value)) : strtoupper(str_replace('_', ' ', $value)) }}</div>
                </div>
              @endforeach
            </div>
          </div>
          @endif

          <!-- Card: Dokumen yang Diunggah -->
          <div class="section-card">
            <h3 class="section-card-title">Dokumen yang Diunggah</h3>
            <div class="doc-list">
              @if($pengajuan->dokumen_pendukung)
                @foreach($pengajuan->dokumen_pendukung as $key => $path)
                <div class="doc-item" style="cursor: pointer; padding: 8px; border-radius: 8px; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'" onclick="event.preventDefault(); document.getElementById('doc-link-{{ $loop->index }}').click()">
                  <div class="doc-icon" style="background:#f1f5f9; border-radius:6px; width:48px; height:48px; display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                      <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                    </svg>
                  </div>
                  <div class="doc-info" style="min-width: 0; flex: 1;">
                    <h4 style="font-size:.95rem; font-weight:700; color:#1e293b; margin-bottom:2px;">{{ ucwords(str_replace(['dokumen_', '_'], ['', ' '], $key)) }}</h4>
                    @php
                      $sizeMB = \Illuminate\Support\Facades\Storage::disk('public')->exists($path) ? number_format(\Illuminate\Support\Facades\Storage::disk('public')->size($path) / 1048576, 2) : '1.2';
                    @endphp
                    <p style="font-size:.75rem; color:#64748b; display: flex; align-items: center; white-space: nowrap; overflow: hidden;">
                      <a id="doc-link-{{ $loop->index }}" href="{{ route('preview.dokumen', ['path' => $path]) }}" class="preview-dokumen" style="color:#64748b; text-decoration:none; display: inline-block; max-width: calc(100% - 70px); overflow: hidden; text-overflow: ellipsis;">{{ preg_replace('/^\d+_[^_]+_/', '', basename($path)) }}</a>
                      <span style="margin-left: 4px;">&mdash; {{ $sizeMB }} MB</span>
                    </p>
                  </div>
                </div>
                @endforeach
              @else
                <p style="font-size:.85rem; color:var(--gray-500);">Tidak ada dokumen yang dilampirkan.</p>
              @endif
            </div>
          </div>



        </div>

        <!-- Action Bar Card for Ditolak -->
        @if($pengajuan->status == 'ditolak')
        <div class="action-bar-card" style="margin-top: 24px; background: white; border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow-sm); display: flex; justify-content: space-between; align-items: center; gap: 24px;">
          <div class="action-bar-text">
            <span style="font-size: 0.95rem; color: var(--gray-700);"><strong>Langkah Selanjutnya:</strong> Perbaiki dokumen yang bermasalah sesuai catatan, lalu ajukan kembali.</span>
          </div>
          
          <div style="display: flex; gap: 12px; align-items: center;">
            <form action="{{ route('user.pengajuan.destroy', $pengajuan->id) }}" method="POST" style="margin:0;" id="deleteForm" onsubmit="window.confirmDelete(event, this)">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-decision" style="background:#fef2f2; color:#ef4444; border:1px solid #fecaca;">Hapus Pengajuan</button>
            </form>

            <a href="{{ route('user.ajukan-surat', ['resubmit_id' => $pengajuan->id, 'jenis' => $pengajuan->jenis_surat]) }}" class="btn-decision" style="background:#ef4444; color:white;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.92-10.44l5.66-5.66"/></svg>
              Ajukan Ulang
            </a>
          </div>
        </div>
        @endif

          <!-- Card: PDF Preview (Hanya jika disetujui & diverifikasi) -->
          @if($pengajuan->status == 'disetujui' && $pengajuan->is_verified_by_lurah)
          <div class="pdf-card" style="margin-top: 24px;">
            <div class="pdf-header">
              <div class="pdf-header-title">Preview Surat PDF</div>

              <a href="{{ route('cetak-surat', $pengajuan->id) }}" class="btn-unduh-sm" id="btnPreviewPdf">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                  <polyline points="7 10 12 15 17 10"></polyline>
                  <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
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
                </div>
                <div class="pdf-mockup-title">
                  <span class="title-text">SURAT KETERANGAN {{ strtoupper(str_replace('-', ' ', $pengajuan->jenis_surat)) }}</span><br>
                  <span class="title-num">Nomor: {{ $pengajuan->nomor_surat ?? '...' }}</span>
                </div>
                
                <div style="font-size: 12px; line-height: 1.5; text-align: justify; margin-top: 15px;">
                  @if($pengajuan->jenis_surat == 'sktm' || $pengajuan->jenis_surat == 'sktm-sekolah')
                  <p>Berdasarkan surat pengantar dari ketua RT {{ str_pad($pengajuan->data_isian['rt'] ?? '', 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($pengajuan->data_isian['rw'] ?? '', 3, '0', STR_PAD_LEFT) }} Nomor: ................, Kelurahan Sambongpari menerangkan bahwa:</p>
                  @else
                  <p>Yang bertanda tangan dibawah ini Lurah Sambongpari, Kecamatan Mangkubumi, Kota Tasikmalaya menerangkan dengan sebenarnya, bahwa :</p>
                  @endif
                  
                  <table class="pdf-table" style="margin-left: 20px; width: calc(100% - 20px);">
                    <tr><td width="150">Nama</td><td width="10">:</td><td>{{ strtoupper($pengajuan->data_isian['nama_lengkap'] ?? '') }}</td></tr>
                    <tr><td>NIK</td><td>:</td><td>{{ $pengajuan->data_isian['nik'] ?? '' }}</td></tr>
                    <tr><td>Jenis Kelamin</td><td>:</td><td>{{ strtoupper($jkMap[$pengajuan->data_isian['jenis_kelamin'] ?? ''] ?? str_replace('_', ' ', $pengajuan->data_isian['jenis_kelamin'] ?? '')) }}</td></tr>
                    <tr><td>Tempat/Tgl Lahir</td><td>:</td><td>{{ strtoupper($pengajuan->data_isian['tempat_lahir'] ?? '') }}, {{ isset($pengajuan->data_isian['tanggal_lahir']) ? \Carbon\Carbon::parse($pengajuan->data_isian['tanggal_lahir'])->translatedFormat('d-m-Y') : '' }}</td></tr>
                    <tr><td>Warganegara / Agama</td><td>:</td><td>{{ strtoupper($pengajuan->data_isian['kewarganegaraan'] ?? '') }} / {{ strtoupper($pengajuan->data_isian['agama'] ?? '') }}</td></tr>
                    <tr><td>Pekerjaan</td><td>:</td><td>{{ strtoupper($pengajuan->data_isian['pekerjaan'] ?? '') }}</td></tr>
                    <tr><td>Status Pernikahan</td><td>:</td><td>{{ strtoupper($kawinMap[$pengajuan->data_isian['status_pernikahan'] ?? ''] ?? str_replace('_', ' ', $pengajuan->data_isian['status_pernikahan'] ?? '')) }}</td></tr>
                    <tr><td valign="top">Alamat</td><td valign="top">:</td><td>
                        {{ strtoupper($pengajuan->data_isian['alamat_lengkap'] ?? '') }} 
                        RT {{ str_pad($pengajuan->data_isian['rt'] ?? '', 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($pengajuan->data_isian['rw'] ?? '', 3, '0', STR_PAD_LEFT) }}<br>
                        KELURAHAN {{ strtoupper($pengajuan->data_isian['kelurahan'] ?? '') }} KECAMATAN {{ strtoupper($pengajuan->data_isian['kecamatan'] ?? '') }}<br>
                        KOTA {{ strtoupper($pengajuan->data_isian['kota'] ?? '') }}
                    </td></tr>
                  </table>

                  @if($pengajuan->jenis_surat == 'sku')
                  <p style="margin-top: 10px;">Berdasarkan surat pengantar dari Ketua RT {{ str_pad($pengajuan->data_isian['rt'] ?? '', 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($pengajuan->data_isian['rw'] ?? '', 3, '0', STR_PAD_LEFT) }} Nomor ...................., Kelurahan Sambongpari, Kecamatan Mangkubumi, Kota Tasikmalaya. Adalah benar nama diatas warga kelurahan kami yang mempunyai usaha sebagai berikut :</p>
                  
                  <table class="pdf-table" style="margin-left: 20px; width: calc(100% - 20px);">
                    <tr><td width="150">Bentuk Usaha</td><td width="10">:</td><td>{{ strtoupper(str_replace('_', ' ', $pengajuan->data_isian['bentuk_usaha'] ?? '')) }}</td></tr>
                    <tr><td>Bidang Usaha</td><td>:</td><td>{{ strtoupper(str_replace('_', ' ', $pengajuan->data_isian['bidang_usaha'] ?? '')) }}</td></tr>
                    <tr><td>Barang Usaha</td><td>:</td><td>{{ strtoupper($pengajuan->data_isian['barang_usaha'] ?? '') }}</td></tr>
                    <tr><td>Keadaan Usaha Saat ini</td><td>:</td><td>{{ strtoupper(str_replace('_', ' ', $pengajuan->data_isian['keadaan_usaha_saat_ini'] ?? '')) }}</td></tr>
                    <tr><td valign="top">Alamat Usaha</td><td valign="top">:</td><td>{{ strtoupper($pengajuan->data_isian['alamat_usaha'] ?? '') }}</td></tr>
                  </table>
                  
                  <p style="margin-top: 10px;">Surat Keterangan ini dibuat untuk keperluan {{ ucfirst(str_replace('_', ' ', $pengajuan->data_isian['keperluan'] ?? '')) }}.</p>
                  <p style="margin-top: 10px;">Demikian surat keterangan ini dibuat, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
                  
                  @elseif($pengajuan->jenis_surat == 'sktm' || $pengajuan->jenis_surat == 'sktm-sekolah')
                  <p style="margin-top: 10px;">adalah benar-benar penduduk kami yang kondisi ekonominya termasuk dalam kategori tidak mampu. Surat keterangan ini dibuat untuk keperluan {{ strtolower(str_replace('_', ' ', $pengajuan->data_isian['keperluan'] ?? 'melengkapi persyaratan')) }}@if(isset($pengajuan->data_isian['nama_yang_menggunakan_surat']) || isset($pengajuan->data_isian['nama_yang_dibantu'])) atas nama <strong>{{ strtoupper($pengajuan->data_isian['nama_yang_menggunakan_surat'] ?? $pengajuan->data_isian['nama_yang_dibantu'] ?? '') }}</strong>@endif.</p>
                  <p style="margin-top: 10px;">Demikian agar dipergunakan sebagaimana mestinya.</p>
                  
                  @else
                  <p style="margin-top: 10px;">Surat Keterangan ini dibuat untuk keperluan {{ ucfirst(str_replace('_', ' ', $pengajuan->data_isian['keperluan'] ?? 'sebagaimana mestinya')) }}.</p>
                  <p style="margin-top: 10px;">Demikian surat keterangan ini dibuat, atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
                  @endif
                  
                  <div style="float: right; text-align: center; margin-top: 20px; width: 250px;">
                    Tasikmalaya, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    a.n CAMAT MANGKUBUMI<br>
                    <div style="border: 1px dashed #ccc; padding: 15px; margin: 10px 0; color: #999;">
                      QR Code TTE
                    </div>
                  </div>
                  <div style="clear: both;"></div>
                </div>
              </div>
            </div>
          </div>
          @endif
      </div> <!-- End detail-content -->

    </main>
  </div>

</div>

<!-- ===== MOBILE BOTTOM NAV ===== -->
<nav class="mobile-bottom-nav">
  <a href="{{ route('user.dashboard') }}" class="bottom-nav-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
    <span>Beranda</span>
  </a>
  <a href="{{ route('user.ajukan-surat') }}" class="bottom-nav-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9"  y1="15" x2="15" y2="15"/></svg>
    <span>Ajukan</span>
  </a>
  <a href="{{ route('user.riwayat') }}" class="bottom-nav-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg>
    <span>Riwayat</span>
  </a>
  <a href="{{ route('user.profil') }}" class="bottom-nav-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
    <span>Profil</span>
  </a>
</nav>

<!-- ===== JAVASCRIPT ===== -->
<script>
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const sidebar      = document.getElementById('sidebar');
  const overlay      = document.getElementById('sidebarOverlay');
  const isMobile     = () => window.innerWidth <= 680;

  function toggleSidebar() {
    const isCollapsed = sidebar.classList.toggle('collapsed');
    hamburgerBtn.setAttribute('aria-expanded', String(!isCollapsed));
    if (isMobile()) {
      overlay.classList.toggle('active', !isCollapsed);
      document.body.style.overflow = isCollapsed ? '' : 'hidden';
    }
  }

  function closeSidebar() {
    sidebar.classList.add('collapsed');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
    hamburgerBtn.setAttribute('aria-expanded', 'false');
  }

  hamburgerBtn.addEventListener('click', toggleSidebar);
  overlay.addEventListener('click', closeSidebar);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeSidebar();
  });

  window.addEventListener('resize', () => {
    if (!isMobile()) {
      overlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  });
</script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>.swal-custom { font-family: 'Inter', sans-serif; border-radius: 12px; } .swal2-confirm { padding: 10px 24px !important; font-weight: 600 !important; border-radius: 8px !important; } .swal2-cancel { padding: 10px 24px !important; font-weight: 600 !important; border-radius: 8px !important; color: #1e293b !important; background: #e2e8f0 !important; }</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('.preview-dokumen');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            window.open(url, '_blank');
        });
    });

    const btnPreviewPdf = document.getElementById('btnPreviewPdf');
    if(btnPreviewPdf) {
        btnPreviewPdf.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            window.open(url, '_blank');
        });
    }
});
</script>
<script src="{{ asset('js/notif.js') }}"></script>
<script src="{{ asset('js/logout.js') }}"></script>
<script>
window.confirmDelete = function(event, form) {
    event.preventDefault();
    Swal.fire({
      showConfirmButton: false,
      customClass: { 
        popup: 'custom-conf-popup', 
        htmlContainer: 'custom-conf-html-container'
      },
      html: `
        <div class="custom-conf-icon-wrapper" style="background-color: #fee2e2;">
          <div class="custom-conf-icon-inner" style="background-color: #991b1b;">?</div>
        </div>
        <h2 class="custom-conf-title">Hapus Pengajuan</h2>
        <p class="custom-conf-text">Apakah Anda yakin ingin menghapus riwayat pengajuan yang ditolak ini?<br>Tindakan ini tidak dapat dibatalkan.</p>
        <div class="custom-conf-actions">
          <button type="button" class="custom-conf-btn-cancel" onclick="Swal.close()">Batal</button>
          <button type="button" class="custom-conf-btn-confirm" style="background-color: #b91c1c !important;" onclick="document.getElementById('${form.id}').submit()" onmouseover="this.style.backgroundColor='#991b1b'" onmouseout="this.style.backgroundColor='#b91c1c'">Ya, Hapus</button>
        </div>
      `
    });
};
</script>
</body>
</html>

