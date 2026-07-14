<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Dashboard Admin – SIPELAS</title>
  <meta name="description" content="Dashboard Admin SIPELAS – Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari" />
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
      --header-h:    60px;
      --sidebar-w:   220px;
    }

    /* =============================================
       LAYOUT
    ============================================= */
    .header {
      position: sticky; top: 0; z-index: 50;
      background: var(--navy); height: var(--header-h);
      display: flex; align-items: center; padding: 0 20px; gap: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,.2); flex-shrink: 0;
    }
    .page-body { display: flex; flex: 1; min-height: 0; }

    /* =============================================
       SIDEBAR
    ============================================= */
    .sidebar {
      width: var(--sidebar-w); background: var(--white);
      border-right: 1px solid var(--gray-200); display: flex; flex-direction: column;
      flex-shrink: 0; position: sticky; top: var(--header-h);
      height: calc(100vh - var(--header-h)); overflow-y: auto;
      transition: width .28s, transform .28s; z-index: 30;
    }
    .sidebar.collapsed { width: 0; overflow: hidden; border-right: none; }
    .sidebar-nav { flex: 1; padding: 20px 12px; display: flex; flex-direction: column; gap: 4px; }
    .nav-item {
      display: flex; align-items: center; gap: 11px; padding: 11px 16px;
      border-radius: 9px; color: var(--gray-600); text-decoration: none;
      font-size: .9rem; font-weight: 500; white-space: nowrap; transition: background .18s, color .18s;
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
      background: none; border: none; cursor: pointer; padding: 7px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 7px; color: white; transition: background .18s; flex-shrink: 0;
    }
    .hamburger-btn:hover { background: rgba(255,255,255,.12); }
    .hamburger-btn svg { width: 22px; height: 22px; }

    .header-brand { display: flex; align-items: center; gap: 10px; flex: 1; }
    .header-brand-icon {
      width: 34px; height: 34px; background: transparent;
      border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .header-brand-icon svg { width: 18px; height: 18px; fill: white; }
    .header-brand-text { color: white; line-height: 1.2; }
    .header-brand-text .app-name { font-size: 1.05rem; font-weight: 700; letter-spacing: .02em; }
    .header-brand-text .app-sub  { font-size: .62rem; opacity: .65; font-weight: 400; }

    .header-actions { display: flex; align-items: center; gap: 8px; }
    .notif-btn {
      position: relative; background: none; border: none; cursor: pointer;
      padding: 7px; color: rgba(255,255,255,.9); border-radius: 7px; display: flex;
    }
    .notif-btn:hover { background: rgba(255,255,255,.12); }
    .notif-btn svg { width: 20px; height: 20px; }
    .notif-badge {
      position: absolute; top: 5px; right: 5px; width: 7px; height: 7px;
      background: #ef4444; border-radius: 50%; border: 2px solid var(--navy);
    }
    .user-avatar {
      width: 34px; height: 34px; background: var(--gray-400); border-radius: 50%;
      display: flex; align-items: center; justify-content: center; font-weight: 700; color: white;
      font-size: .88rem; cursor: pointer; flex-shrink: 0; text-decoration: none;
    }
    .user-avatar:hover { opacity: .85; }

    /* ===== SIDEBAR LOGOUT ===== */
    .sidebar-footer { padding: 12px; border-top: 1px solid var(--gray-200); margin-top: auto; }
    .logout-btn {
      display: flex; align-items: center; gap: 11px; width: 100%; padding: 11px 16px;
      border-radius: 9px; border: none; background: none; color: var(--gray-600);
      font-size: .9rem; font-weight: 500; cursor: pointer; text-decoration: none;
    }
    .logout-btn svg { width: 18px; height: 18px; flex-shrink: 0; color: #ef4444; }
    .logout-btn:hover { background: #fef2f2; color: #b91c1c; }

    /* =============================================
       CONTENT AREA
    ============================================= */
    .content-area { flex: 1; display: flex; flex-direction: column; min-width: 0; }
    .content { flex: 1; padding: 28px 40px; max-width: 1400px; margin: 0 auto; width: 100%; }

    /* ===== WELCOME CARD ===== */
    .welcome-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 24px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      box-shadow: var(--shadow-sm);
      margin-bottom: 24px;
    }
    .welcome-text h2 {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 6px;
    }
    .welcome-text p {
      font-size: .85rem;
      color: var(--gray-500);
    }
    .welcome-right {
      text-align: right;
    }
    .welcome-right p {
      font-size: .85rem;
      color: var(--gray-500);
      margin-bottom: 6px;
    }
    .welcome-right h3 {
      font-size: 1rem;
      font-weight: 700;
      color: var(--gray-800);
    }

    /* ===== STATS GRID ===== */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px;
      margin-bottom: 32px;
    }
    .stat-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 24px;
      display: flex;
      align-items: center;
      gap: 18px;
      box-shadow: var(--shadow-sm);
    }
    .stat-icon {
      width: 56px; height: 56px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    /* Total - Yellow */
    .stat-icon.total { background: #fef9c3; color: #facc15; }
    /* Pending - Yellow */
    .stat-icon.pending { background: #fef9c3; color: #facc15; }
    /* Approved - Green */
    .stat-icon.approved { background: #ecfdf5; color: #10b981; }
    /* Rejected - Red */
    .stat-icon.rejected { background: #fef2f2; color: #ef4444; }

    .stat-info h3 {
      font-size: 1.5rem;
      font-weight: 800;
      color: var(--gray-800);
      margin-bottom: 4px;
    }
    .stat-info p {
      font-size: .85rem;
      color: var(--gray-500);
    }

    /* ===== ADMIN GRID ===== */
    .admin-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 24px;
      align-items: start;
    }
    .admin-left { display: flex; flex-direction: column; gap: 32px; }
    .admin-right { display: flex; flex-direction: column; gap: 24px; }

    /* Section Headers */
    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
    }
    .section-header h3 {
      font-size: 1.05rem;
      font-weight: 700;
      color: var(--gray-800);
    }
    .link-semua {
      font-size: .85rem;
      font-weight: 600;
      color: var(--blue-text);
      text-decoration: none;
    }
    .link-semua:hover { text-decoration: underline; }

    /* Table Cards */
    .table-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      box-shadow: var(--shadow-sm);
      overflow: hidden;
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }
    .data-table th, .data-table td {
      padding: 16px 20px;
      border-bottom: 1px solid var(--gray-200);
    }
    .data-table th {
      background: #fafafa;
      font-size: .75rem;
      font-weight: 700;
      color: var(--gray-500);
      text-transform: uppercase;
      letter-spacing: .05em;
    }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table td {
      font-size: .85rem;
      color: var(--gray-600);
      vertical-align: middle;
    }
    
    .td-nama {
      font-weight: 700;
      color: var(--gray-800);
      display: block;
      margin-bottom: 4px;
      font-size: .9rem;
    }
    .td-nik { font-size: .75rem; color: var(--blue); }
    
    .td-tanggal { display: block; margin-bottom: 4px; color: var(--gray-800); }
    .td-sub-red { font-size: .75rem; color: #ef4444; font-weight: 600; }
    .td-sub-gray { font-size: .75rem; color: var(--gray-500); }
    .td-sub-green { font-size: .75rem; color: #10b981; font-weight: 600; }

    .btn-proses {
      background: #1d4ed8;
      color: white;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      font-weight: 600;
      font-size: .8rem;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: background .15s;
    }
    .btn-proses:hover { background: #1e3a8a; }

    .badge-green { 
      background: #6ee7b7; color: #065f46; 
      display: inline-block; padding: 4px 12px; 
      border-radius: 20px; font-size: .75rem; font-weight: 700;
    }
    .badge-red { 
      background: #fca5a5; color: #991b1b; 
      display: inline-block; padding: 4px 12px; 
      border-radius: 20px; font-size: .75rem; font-weight: 700;
    }

    /* Side Cards */
    .side-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      box-shadow: var(--shadow-sm);
    }
    .side-card-title {
      font-size: .95rem; font-weight: 700; color: var(--navy);
      padding: 20px 24px;
      display: flex; align-items: center; gap: 10px;
      border-bottom: 1px solid var(--gray-200);
    }
    .side-card-title::before {
      content: ''; display: block; width: 4px; height: 16px;
      background: var(--navy); border-radius: 4px;
    }

    /* Progress List */
    .progress-list {
      padding: 16px 24px 24px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .progress-item-title {
      font-size: .9rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 8px;
    }
    .progress-bar-bg {
      background: var(--gray-200);
      border-radius: 4px;
      height: 8px;
      width: 100%;
      overflow: hidden;
      margin-bottom: 8px;
    }
    .progress-bar-fill {
      height: 100%;
      border-radius: 4px;
    }
    .progress-bar-fill.red { background: #b91c1c; width: 65%; }
    .progress-bar-fill.yellow { background: #eab308; width: 50%; }
    .progress-bar-fill.gray { background: #94a3b8; width: 25%; }
    .progress-count {
      font-size: .8rem;
      color: var(--gray-600);
      font-weight: 600;
    }
    .progress-divider {
      border-top: 1px solid var(--gray-200);
      margin: 2px 0;
    }

    /* Activity List */
    .activity-list {
      display: flex;
      flex-direction: column;
    }
    .activity-item {
      padding: 16px 24px;
      border-bottom: 1px solid var(--gray-200);
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-title {
      font-size: .85rem;
      font-weight: 600;
      color: var(--gray-800);
      margin-bottom: 4px;
    }
    .activity-date {
      font-size: .75rem;
      color: var(--gray-500);
    }

    /* ===== MOBILE OVERLAY ===== */
    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 25; }
    .sidebar-overlay.active { display: block; }

    /* =============================================
       RESPONSIVE
    ============================================= */
    .mobile-bottom-nav { display: none; }

    @media (max-width: 1024px) {
      .stats-grid { grid-template-columns: repeat(2, 1fr); }
      .admin-grid { grid-template-columns: 1fr; }
    }
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

      /* Adjust body and hide desktop sidebar/hamburger */
      .page-body { padding-bottom: 65px; }
      .sidebar { display: none !important; }
      .hamburger-btn { display: none !important; }
      .sidebar-overlay { display: none !important; }

      .content { padding: 18px 14px; }
      .stats-grid { 
        grid-template-columns: repeat(2, 1fr); 
        gap: 14px; 
      }
      .stat-card {
        padding: 18px 16px;
        gap: 14px;
      }
      .stat-icon { width: 44px; height: 44px; }
      .stat-icon svg { width: 22px; height: 22px; }
      .stat-info h3 { font-size: 1.25rem; margin-bottom: 2px; }
      .stat-info p { font-size: .7rem; line-height: 1.2; }
      .welcome-card { flex-direction: column; align-items: flex-start; text-align: left; }
      .welcome-right { text-align: left; margin-top: 12px; }
      
      /* Card-based Table (List View) for App Feel */
      .data-table thead { display: none; }
      .data-table, .data-table tbody, .data-table tr, .data-table td { display: block; width: 100%; }
      .data-table tr {
        margin-bottom: 12px; border: 1px solid var(--gray-200); border-radius: var(--radius);
        padding: 14px; background: var(--white); position: relative; box-shadow: var(--shadow-sm);
        border-bottom: 1px solid var(--gray-200) !important;
      }
      .data-table td { padding: 4px 0; border: none; text-align: left !important; font-size: .85rem; }
      .data-table td::before { display: none; }
      
      .data-table td:nth-child(1) { font-weight: 700; color: var(--gray-800); font-size: .95rem; margin-bottom: 2px; } /* Nama */
      .data-table td:nth-child(2) { color: var(--navy); font-size: .85rem; margin-bottom: 4px; font-weight: 600; } /* Jenis */
      .data-table td:nth-child(3) { color: var(--gray-500); font-size: .75rem; margin-bottom: 10px; } /* Tanggal */
      .data-table td:nth-child(4) { margin-top: 8px; padding-top: 12px; border-top: 1px solid var(--gray-100); } /* Aksi */
      .table-card { border: none; box-shadow: none; background: transparent; }
    }
    
    @media (max-width: 440px) {
      .action-group { flex-wrap: wrap; }
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
              $route = (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->role == 'admin' ? route('admin.detail-pengajuan', $notif->id) : route('user.detail-pengajuan', $notif->id);
              $jenis = ['sku'=>'Surat Keterangan Usaha','sktm'=>'Surat Keterangan Tidak Mampu','sktm-sekolah'=>'Surat Keterangan Tidak Mampu (Sekolah)','domisili'=>'Surat Keterangan Domisili','belum-menikah'=>'Surat Keterangan Belum Menikah','kelahiran'=>'Surat Keterangan Kelahiran','kematian'=>'Surat Keterangan Kematian','pengantar-nikah'=>'Surat Pengantar Nikah','pindah'=>'Surat Keterangan Pindah'][$notif->jenis_surat] ?? ucwords(str_replace('-', ' ', $notif->jenis_surat));
            @endphp
            <a href="{{ $route }}" style="display: block; padding: 12px 16px; border-bottom: 1px solid #f1f5f9; text-decoration: none; transition: background .15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
              @if((Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->role == 'admin')
                <div style="font-size: 0.85rem; color: #1e293b; font-weight: 600; margin-bottom: 4px;">Pengajuan Baru: {{ $jenis }}</div>
                <div style="font-size: 0.75rem; color: #64748b;">Dari: {{ $notif->warga->name ?? explode('@', $notif->warga->email)[0] }}</div>
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
    <a href="{{ route('admin.profil') }}" class="user-avatar" title="{{ (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->name ?? (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->email }}" style="text-decoration: none;">{{ strtoupper(substr((Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->name ?? (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->email, 0, 1)) }}</a>
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
  
  <!-- ===== SIDEBAR (Hidden by default for Dashboard Admin to match design) ===== -->
  <aside class="sidebar collapsed" id="sidebar" aria-label="Navigasi Utama">
    <nav class="sidebar-nav">
      <a href="{{ route('admin.dashboard') }}" class="nav-item active" id="nav-dashboard"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg> Dashboard</a>
      <a href="{{ route('admin.daftar-pengajuan') }}" class="nav-item" id="nav-kelola"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><polyline points="9 14 11 16 15 11"/></svg> Verifikasi Surat</a>
      <a href="{{ route('admin.riwayat-pengajuan') }}" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg> Riwayat Pengajuan
      </a>
      <a href="{{ route('admin.laporan') }}" class="nav-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}" id="nav-laporan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 3v5h5M16 13H8M16 17H8M10 9H8"/></svg> Laporan</a>
    </nav>
    <div class="sidebar-footer">
      <a href="{{ route('logout') }}" class="logout-btn" id="logoutBtn" onclick="confirmLogout(event, this.href);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Keluar</a>
    </div>
  </aside>

  <!-- ===== CONTENT AREA ===== -->
  <div class="content-area">
    <main class="content">

      <!-- Welcome Card -->
      <div class="welcome-card">
        <div class="welcome-text">
          <h2>Selamat Datang, {{ $displayName }}</h2>
          <p>Kelurahan Sambongpari, Kec. Mangkubumi, Kota Tasikmalaya</p>
        </div>
        <div class="welcome-right">
          <p>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
          <h3>{{ (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->jabatan ?? 'Operator' }}</h3>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="stats-grid">
        <!-- Card 1 -->
        <div class="stat-card">
          <div class="stat-icon total">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
          </div>
          <div class="stat-info">
            <h3>{{ $totalPengajuan }}</h3>
            <p>Total Pengajuan</p>
          </div>
        </div>
        <!-- Card 2 -->
        <div class="stat-card">
          <div class="stat-icon pending">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
              <path d="M12 21A8 8 0 1 0 12 5a8 8 0 0 0 0 16zm1-11v4l2.5 1.5-1 1.5L11 15V10h2zM9 2h6v2H9V2z"/>
            </svg>
          </div>
          <div class="stat-info">
            <h3>{{ $menunggu }}</h3>
            <p>Menunggu Verifikasi</p>
          </div>
        </div>
        <!-- Card 3 -->
        <div class="stat-card">
          <div class="stat-icon approved">
            <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <path d="M9 12l2 2 4-4"></path>
            </svg>
          </div>
          <div class="stat-info">
            <h3>{{ $disetujui }}</h3>
            <p>Disetujui</p>
          </div>
        </div>
        <!-- Card 4 -->
        <div class="stat-card">
          <div class="stat-icon rejected">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </div>
          <div class="stat-info">
            <h3>{{ $ditolak }}</h3>
            <p>Ditolak</p>
          </div>
        </div>
      </div>

      <!-- Admin Grid Layout -->
      <div class="admin-grid">
        
        <!-- Left Column -->
        <div class="admin-left">
          
          <!-- Section: Antrean Verifikasi Surat -->
          <div class="section-container">
            <div class="section-header">
              <h3>Antrean Verifikasi Surat</h3>
              <a href="{{ route('admin.daftar-pengajuan') }}" class="link-semua">Lihat Semua</a>
            </div>
            <div class="table-card">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>NAMA</th>
                    <th>JENIS SURAT</th>
                    <th>TANGGAL MASUK</th>
                    <th>AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($menungguList as $pengajuan)
                  @php
                    $nama = $pengajuan->warga->name ?? ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $pengajuan->warga->email)[0]));
                    $nik = $pengajuan->data_isian['nik'] ?? 'Tidak ada NIK';
                    $diffInDays = \Carbon\Carbon::parse($pengajuan->created_at)->startOfDay()->diffInDays(now()->startOfDay());
                    if ($diffInDays == 0) {
                        $timeText = 'Hari ini';
                        $timeClass = 'td-sub-green';
                    } elseif ($diffInDays == 1) {
                        $timeText = 'Kemarin';
                        $timeClass = 'td-sub-gray';
                    } else {
                        $timeText = "Sudah $diffInDays Hari";
                        $timeClass = 'td-sub-red';
                    }
                  @endphp
                  <tr>
                    <td data-label="NAMA">
                      <span class="td-nama">{{ $nama }}</span>
                      <span class="td-nik">{{ $nik }}</span>
                    </td>
                    <td data-label="JENIS SURAT">{{ ['sku'=>'Surat Keterangan Usaha','sktm'=>'Surat Keterangan Tidak Mampu','sktm-sekolah'=>'Surat Keterangan Tidak Mampu (Sekolah)','domisili'=>'Surat Keterangan Domisili','belum-menikah'=>'Surat Keterangan Belum Menikah','kelahiran'=>'Surat Keterangan Kelahiran','kematian'=>'Surat Keterangan Kematian','pengantar-nikah'=>'Surat Pengantar Nikah','pindah'=>'Surat Keterangan Pindah'][$pengajuan->jenis_surat] ?? ucwords(str_replace('-', ' ', $pengajuan->jenis_surat)) }}</td>
                    <td data-label="TANGGAL MASUK">
                      <span class="td-tanggal">{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y') }}</span>
                      <span class="{{ $timeClass }}">{{ $timeText }}</span>
                    </td>
                    <td data-label="AKSI"><a href="{{ route('admin.detail-pengajuan', $pengajuan->id) }}" class="btn-proses">Proses</a></td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" style="text-align:center; padding:20px; color:#64748b;">Tidak ada pengajuan baru.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Section: Riwayat Pengajuan -->
          <div class="section-container">
            <div class="section-header">
              <h3>Riwayat Pengajuan</h3>
              <a href="{{ route('admin.riwayat-pengajuan') }}" class="link-semua">Lihat Semua</a>
            </div>
            <div class="table-card">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>NAMA</th>
                    <th>JENIS SURAT</th>
                    <th>TANGGAL DIPROSES</th>
                    <th>STATUS</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($riwayatTerbaru as $riwayat)
                  @php
                    $nama = $riwayat->warga->name ?? ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $riwayat->warga->email)[0]));
                    $nik = $riwayat->data_isian['nik'] ?? 'Tidak ada NIK';
                  @endphp
                  <tr>
                    <td data-label="NAMA">
                      <span class="td-nama">{{ $nama }}</span>
                      <span class="td-nik">{{ $nik }}</span>
                    </td>
                    <td data-label="JENIS SURAT">
                      <div style="font-weight: 500; margin-bottom: 4px;">{{ ['sku'=>'Surat Keterangan Usaha','sktm'=>'Surat Keterangan Tidak Mampu','sktm-sekolah'=>'Surat Keterangan Tidak Mampu (Sekolah)','domisili'=>'Surat Keterangan Domisili','belum-menikah'=>'Surat Keterangan Belum Menikah','kelahiran'=>'Surat Keterangan Kelahiran','kematian'=>'Surat Keterangan Kematian','pengantar-nikah'=>'Surat Pengantar Nikah','pindah'=>'Surat Keterangan Pindah'][$riwayat->jenis_surat] ?? ucwords(str_replace('-', ' ', $riwayat->jenis_surat)) }}</div>
                      @if(strtolower($riwayat->status) == 'disetujui' && $riwayat->is_verified_by_lurah)
                        <span class="badge badge-green" style="background:#d1fae5; color:#065f46; font-size: 0.7rem; padding: 2px 8px; border-radius: 20px; display:inline-block; font-weight:700;">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:10px; height:10px; margin-right:2px; display:inline-block;"><polyline points="20 6 9 17 4 12"></polyline></svg> Surat Sah
                        </span>
                      @elseif(strtolower($riwayat->status) == 'disetujui' && !$riwayat->is_verified_by_lurah)
                        <span class="badge badge-red" style="background:#fee2e2; color:#991b1b; font-size: 0.7rem; padding: 2px 8px; border-radius: 20px; display:inline-block; font-weight:700;">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:10px; height:10px; margin-right:2px; display:inline-block;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Belum Sah
                        </span>
                      @endif
                    </td>
                    <td data-label="TANGGAL DIPROSES">{{ \Carbon\Carbon::parse($riwayat->updated_at)->translatedFormat('d F Y, H:i') }} WIB</td>
                    <td data-label="STATUS">
                      @if(strtolower($riwayat->status) == 'disetujui')
                        <span class="badge-green">Disetujui</span>
                      @else
                        <span class="badge-red">Ditolak</span>
                      @endif
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" style="text-align:center; padding:20px; color:#64748b;">Belum ada riwayat.</td>
                  </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

        </div>

        <!-- Right Column -->
        <div class="admin-right">
          
          <!-- Card: Jenis Surat Terbanyak -->
          <div class="side-card">
            <h3 class="side-card-title">Jenis Surat Terbanyak</h3>
            <div class="progress-list">
              @forelse($jenisStat as $stat)
              <div>
                <div class="progress-item-title">{{ $stat->nama }}</div>
                <div class="progress-bar-bg">
                  <div class="progress-bar-fill {{ $stat->color }}" style="width: {{ $stat->persentase }}%"></div>
                </div>
                <div class="progress-count">{{ $stat->total }} Surat</div>
              </div>
              @if(!$loop->last)
              <div class="progress-divider"></div>
              @endif
              @empty
              <div style="text-align:center; color:#64748b;">Belum ada data</div>
              @endforelse
            </div>
          </div>

          <!-- Card: Aktivitas Terbaru -->
          <div class="side-card">
            <h3 class="side-card-title">Aktivitas Terbaru</h3>
            <div class="activity-list">
              @forelse($riwayatTerbaru->take(3) as $act)
              @php
                $nama = $act->warga->name ?? ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $act->warga->email)[0]));
                $jenis = ['sku'=>'Surat Keterangan Usaha','sktm'=>'Surat Keterangan Tidak Mampu','sktm-sekolah'=>'Surat Keterangan Tidak Mampu (Sekolah)','domisili'=>'Surat Keterangan Domisili','belum-menikah'=>'Surat Keterangan Belum Menikah','kelahiran'=>'Surat Keterangan Kelahiran','kematian'=>'Surat Keterangan Kematian','pengantar-nikah'=>'Surat Pengantar Nikah','pindah'=>'Surat Keterangan Pindah'][$act->jenis_surat] ?? ucwords(str_replace('-', ' ', $act->jenis_surat));
                
                if (strtolower($act->status) == 'disetujui' && $act->is_verified_by_lurah) {
                    $statusText = 'diterbitkan';
                } elseif (strtolower($act->status) == 'disetujui') {
                    $statusText = 'disetujui';
                } else {
                    $statusText = 'ditolak';
                }
              @endphp
              <div class="activity-item">
                <div class="activity-title">Surat {{ $jenis }} {{ $nama }} {{ $statusText }}</div>
                <div class="activity-date">{{ \Carbon\Carbon::parse($act->updated_at)->translatedFormat('d F Y, H:i') }} WIB</div>
              </div>
              @empty
              <div class="activity-item" style="color:#64748b; text-align:center; border:none;">Belum ada aktivitas</div>
              @endforelse
            </div>
          </div>

        </div>

      </div>

    </main>
  </div>
</div>

<!-- ===== MOBILE BOTTOM NAV ===== -->
<nav class="mobile-bottom-nav">
  <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
    <span>Beranda</span>
  </a>
  <a href="{{ route('admin.daftar-pengajuan') }}" class="bottom-nav-item {{ request()->routeIs('admin.daftar-pengajuan') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><polyline points="9 14 11 16 15 11"/></svg>
    <span>Verifikasi</span>
  </a>
  <a href="{{ route('admin.riwayat-pengajuan') }}" class="bottom-nav-item {{ request()->routeIs('admin.riwayat-pengajuan') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg>
    <span>Riwayat</span>
  </a>
  <a href="{{ route('admin.profil') }}" class="bottom-nav-item {{ request()->routeIs('admin.profil') || request()->routeIs('admin.ubah-kata-sandi') ? 'active' : '' }}">
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
</script>
@if (session('success'))
<style>
  .custom-reg-popup {
    border-radius: 16px !important;
    padding: 36px 30px !important;
    width: 400px !important;
  }
  .custom-reg-html-container {
    margin: 0 !important;
    padding: 0 !important;
    overflow: visible !important;
  }
  .custom-reg-icon-wrapper {
    width: 80px;
    height: 80px;
    background-color: #e6fceb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
  }
  .custom-reg-icon-inner {
    width: 44px;
    height: 44px;
    background-color: #15803d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .custom-reg-icon-inner svg {
    color: white;
    width: 24px;
    height: 24px;
  }
  .custom-reg-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 12px;
    font-family: 'Inter', sans-serif;
    text-align: center;
  }
  .custom-reg-text {
    font-size: 0.95rem;
    color: #64748b;
    line-height: 1.5;
    margin: 0 0 32px;
    font-family: 'Inter', sans-serif;
    text-align: center;
  }
  .custom-reg-btn {
    width: 100% !important;
    background-color: #1a4068 !important;
    color: white !important;
    border: none !important;
    border-radius: 9999px !important;
    padding: 14px 0 !important;
    font-weight: 600 !important;
    font-size: 0.95rem !important;
    box-shadow: none !important;
    cursor: pointer !important;
    outline: none !important;
    margin-top: 10px;
  }
</style>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
      showConfirmButton: false,
      customClass: { 
        popup: 'custom-reg-popup', 
        htmlContainer: 'custom-reg-html-container'
      },
      html: `
        <div class="custom-reg-icon-wrapper">
          <div class="custom-reg-icon-inner">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
        </div>
        <h2 class="custom-reg-title">Berhasil!</h2>
        <p class="custom-reg-text">{{ session('success') }}</p>
        <button type="button" class="custom-reg-btn" onclick="Swal.close()">Oke</button>
      `
    });
  });
</script>
@endif
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>.swal-custom { font-family: 'Inter', sans-serif; border-radius: 12px; } .swal2-confirm { padding: 10px 24px !important; font-weight: 600 !important; border-radius: 8px !important; } .swal2-cancel { padding: 10px 24px !important; font-weight: 600 !important; border-radius: 8px !important; color: #1e293b !important; background: #e2e8f0 !important; }</style>


<script src="{{ asset('js/notif.js') }}"></script>
<script src="{{ asset('js/logout.js') }}"></script>
</body>
</html>

