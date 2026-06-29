<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Riwayat Pengajuan – SIPELAS</title>
  <meta name="description" content="Riwayat pengajuan surat SIPELAS – Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari" />
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
      position: sticky;
      top: 0;
      z-index: 50;
      background: var(--navy);
      height: var(--header-h);
      display: flex;
      align-items: center;
      padding: 0 20px;
      gap: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,.2);
      flex-shrink: 0;
    }

    .page-body {
      display: flex;
      flex: 1;
      min-height: 0;
    }

    /* =============================================
       SIDEBAR
    ============================================= */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--white);
      border-right: 1px solid var(--gray-200);
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      position: sticky;
      top: var(--header-h);
      height: calc(100vh - var(--header-h));
      overflow-y: auto;
      transition: width .28s cubic-bezier(.4,0,.2,1),
                  transform .28s cubic-bezier(.4,0,.2,1);
      z-index: 30;
    }
    .sidebar.collapsed {
      width: 0;
      overflow: hidden;
      border-right: none;
    }

    .sidebar-nav {
      flex: 1;
      padding: 20px 12px;
      display: flex;
      flex-direction: column;
      gap: 4px;
    }
    .nav-item {
      display: flex;
      align-items: center;
      gap: 11px;
      padding: 11px 16px;
      border-radius: 9px;
      color: var(--gray-600);
      text-decoration: none;
      font-size: .9rem;
      font-weight: 500;
      white-space: nowrap;
      transition: background .18s, color .18s;
    }
    .nav-item svg {
      width: 18px; height: 18px;
      flex-shrink: 0;
      color: var(--navy);
      transition: color .18s;
    }
    .nav-item:hover {
      background: var(--gray-100);
      color: var(--gray-800);
    }
    .nav-item:hover svg { color: var(--navy); }

    .nav-item.active {
      background: var(--navy);
      color: var(--white);
      font-weight: 600;
    }
    .nav-item.active svg { color: var(--white); }

    /* =============================================
       HEADER PARTS
    ============================================= */
    .hamburger-btn {
      background: none;
      border: none;
      cursor: pointer;
      padding: 7px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 7px;
      color: white;
      transition: background .18s;
      flex-shrink: 0;
    }
    .hamburger-btn:hover { background: rgba(255,255,255,.12); }
    .hamburger-btn svg { width: 22px; height: 22px; }

    .header-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      flex: 1;
    }
    .header-brand-icon {
      width: 34px; height: 34px;
      background: transparent;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .header-brand-icon svg { width: 18px; height: 18px; fill: white; }
    
    .header-brand-text { color: white; line-height: 1.2; }
    .header-brand-text .app-name { font-size: 1.05rem; font-weight: 700; letter-spacing: .02em; }
    .header-brand-text .app-sub  { font-size: .62rem; opacity: .65; font-weight: 400; }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .notif-btn {
      position: relative;
      background: none;
      border: none;
      cursor: pointer;
      padding: 7px;
      color: rgba(255,255,255,.9);
      border-radius: 7px;
      transition: background .18s;
      display: flex;
    }
    .notif-btn:hover { background: rgba(255,255,255,.12); }
    .notif-btn svg { width: 20px; height: 20px; }
    .notif-badge {
      position: absolute;
      top: 5px; right: 5px;
      width: 7px; height: 7px;
      background: #ef4444;
      border-radius: 50%;
      border: 2px solid var(--navy);
    }
    .user-avatar {
      width: 34px; height: 34px;
      background: var(--gray-400);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; color: white; font-size: .88rem;
      cursor: pointer;
      transition: opacity .18s;
      flex-shrink: 0;
    }

    /* ===== SIDEBAR LOGOUT ===== */
    .sidebar-footer {
      padding: 12px;
      border-top: 1px solid var(--gray-200);
      margin-top: auto;
    }
    .logout-btn {
      display: flex; align-items: center; gap: 11px;
      width: 100%; padding: 11px 16px;
      border-radius: 9px; border: none; background: none;
      color: var(--gray-600); font-size: .9rem; font-weight: 500;
      cursor: pointer; text-decoration: none; white-space: nowrap;
      transition: background .18s, color .18s;
    }
    .logout-btn svg { width: 18px; height: 18px; flex-shrink: 0; color: #ef4444; transition: color .18s; }
    .logout-btn:hover { background: #fef2f2; color: #b91c1c; }
    .logout-btn:hover svg { color: #b91c1c; }

    /* =============================================
       CONTENT AREA
    ============================================= */
    .content-area {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
    }
    .content {
      flex: 1;
      padding: 28px 28px;
    }

    /* ===== PAGE HEADER ===== */
    .page-header { margin-bottom: 24px; }
    .page-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 6px;
    }
    .page-subtitle {
      font-size: .9rem;
      color: var(--gray-500);
    }

    /* ===== FILTER CARD ===== */
    .filter-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 24px;
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 24px;
    }
    .filter-label {
      font-weight: 700;
      color: var(--gray-800);
      font-size: .95rem;
      white-space: nowrap;
    }
    .filter-select {
      background: #ffffff;
      color: var(--gray-800);
      border: 1px solid var(--gray-300);
      border-radius: 8px;
      padding: 14px 16px;
      font-size: .95rem;
      font-family: 'Inter', sans-serif;
      font-weight: 500;
      cursor: pointer;
      flex: 1;
      appearance: none;
      background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23475569%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E");
      background-repeat: no-repeat;
      background-position: right 16px top 50%;
      background-size: 18px auto;
      
    }
    
    .filter-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }
    .filter-select option {
      background: #ffffff;
      color: var(--gray-800);
    }

    /* ===== DATA TABLE ===== */
    .table-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
    }
    .data-table {
      width: 100%;
      border-collapse: collapse;
    }
    .data-table thead th {
      background: #f8fafc;
      padding: 14px 16px;
      font-size: .75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .06em;
      color: var(--gray-500);
      text-align: left;
      border-bottom: 1px solid var(--gray-200);
    }
    .data-table thead th:first-child { text-align: center; }
    .data-table tbody tr {
      border-bottom: 1px solid var(--gray-100);
      transition: background .14s;
    }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: #f8fafc; }
    .data-table td {
      padding: 15px 16px;
      font-size: .88rem;
      color: var(--gray-800);
      vertical-align: middle;
    }
    .data-table td:first-child {
      text-align: center;
      color: var(--gray-600);
      font-weight: 600;
      width: 60px;
    }

    /* ===== STATUS BADGES ===== */
    .badge {
      display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px;
      font-size: .75rem; font-weight: 600; text-align: center;
    }
    .badge-green { background: #d1fae5; color: #065f46; }
    .badge-amber { background: #fef3c7; color: #92400e; }
    .badge-red   { background: #fee2e2; color: #991b1b; }

    /* ===== ACTION BUTTONS ===== */
    .action-group { display: flex; gap: 8px; }
    .btn {
      padding: 8px 16px;
      border-radius: 6px;
      border: none;
      font-size: .8rem;
      font-weight: 600;
      cursor: pointer;
      transition: opacity .18s, transform .14s;
      text-decoration: none;
      display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      line-height: 1.4;
    }
    .btn:hover { opacity: .85; transform: translateY(-1px); }
    .btn-detail { background: white; color: var(--gray-600); border: 1px solid var(--gray-300); }
    .btn-detail:hover { background: var(--gray-50); border-color: var(--gray-400); color: var(--gray-800); }
    .btn-unduh  { background: #0ea5e9; color: white; } /* Matches bright blue for Cetak PDF */
    .btn-ulang  { background: #991b1b; color: white; }
    .btn-cetak  { background: var(--green); color: white; }
    .btn svg { width: 14px; height: 14px; } /* Dark red for Ajukan ulang */

    /* ===== MOBILE OVERLAY ===== */
    .sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.4);
      z-index: 25;
    }
    .sidebar-overlay.active { display: block; }

    /* =============================================
       RESPONSIVE
    ============================================= */
    .mobile-bottom-nav { display: none; }

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
      
      .filter-card { flex-direction: column; align-items: stretch; gap: 16px; padding: 16px; }

      /* Convert table to card list */
      .table-card { border: none; box-shadow: none; background: transparent; }
      .data-table thead { display: none; }
      .data-table, .data-table tbody, .data-table tr, .data-table td { display: block; width: 100%; }
      .data-table tr {
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        margin-bottom: 12px;
        padding: 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        position: relative;
      }
      .data-table tbody tr { border-bottom: 1px solid var(--gray-200); }
      .data-table td { padding: 4px 0; text-align: left !important; border: none !important; }
      .data-table td:first-child { display: none; } /* hide No */
      .data-table td:nth-child(2) { font-weight: 700; font-size: 1rem; color: var(--gray-800); padding-right: 90px !important; }
      .data-table td:nth-child(3) { font-size: .8rem; color: var(--gray-500); }
      .data-table td:nth-child(4) { position: absolute; top: 12px; right: 12px; padding: 0 !important; width: auto !important; }
      .action-group { width: 100%; flex-wrap: wrap; gap: 8px; }
      .btn { flex: 1; justify-content: center; text-align: center; }
    }
  
.form-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }

.input-control:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }


</style>
</head>
<body>

<!-- ===== HEADER ===== -->
<header class="header">
  <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu" aria-expanded="true" aria-controls="sidebar">
    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round">
      <line x1="3" y1="6"  x2="21" y2="6"/>
      <line x1="3" y1="12" x2="21" y2="12"/>
      <line x1="3" y1="18" x2="21" y2="18"/>
    </svg>
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
    <a href="{{ route('user.profil') }}" class="user-avatar" title="{{ (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->name ?? (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->email }}" style="text-decoration: none;">{{ strtoupper(substr((Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->name ?? (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->email, 0, 1)) }}</a>
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
  <aside class="sidebar" id="sidebar" aria-label="Navigasi Utama">
    <nav class="sidebar-nav">
      <a href="{{ route('user.dashboard') }}" class="nav-item" id="nav-dashboard">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/>
        </svg>
        Dashboard
      </a>
      <a href="{{ route('user.ajukan-surat') }}" class="nav-item" id="nav-ajukan">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="12" y1="18" x2="12" y2="12"/>
          <line x1="9"  y1="15" x2="15" y2="15"/>
        </svg>
        Ajukan Surat
      </a>
      <a href="{{ route('user.riwayat') }}" class="nav-item active" id="nav-riwayat">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="12 8 12 12 14 14"/>
          <path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/>
        </svg>
        Riwayat
      </a>
    </nav>
    <div class="sidebar-footer">
      <a href="{{ route('logout') }}" class="logout-btn" id="logoutBtn" onclick="confirmLogout(event, this.href);">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Keluar
      </a>
    </div>
  </aside>

  <!-- ===== CONTENT AREA ===== -->
  <div class="content-area">
    <main class="content">

      <!-- Page Header -->
      <div class="page-header">
        <h2 class="page-title">Riwayat Pengajuan</h2>
        <p class="page-subtitle">Daftar semua pengajuan surat yang pernah kamu ajukan</p>
      </div>

      <!-- Filter Card -->
      <form method="GET" action="{{ route('user.riwayat') }}" class="filter-card">
        <span class="filter-label">Filter:</span>
        <select name="jenis_surat" class="filter-select" onchange="this.form.submit()">
          <option value="">-- Semua Jenis Surat --</option>
          <option value="sktm" {{ request('jenis_surat') == 'sktm' ? 'selected' : '' }}>Surat Keterangan Tidak Mampu (SKTM)</option>
          <option value="sku" {{ request('jenis_surat') == 'sku' ? 'selected' : '' }}>Surat Keterangan Usaha (SKU)</option>
          <option value="domisili" {{ request('jenis_surat') == 'domisili' ? 'selected' : '' }}>Surat Keterangan Domisili</option>
        </select>
        <select name="status" class="filter-select" onchange="this.form.submit()">
          <option value="">-- Semua Status Pengajuan --</option>
          <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
          <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
          <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
      </form>

      <!-- History Table -->
      <div class="table-card">
        <table class="data-table" role="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Jenis Surat</th>
              <th>Tanggal Pengajuan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php
              $jenisSuratMap = [
                  'sktm' => 'Surat Keterangan Tidak Mampu',
                  'sku'  => 'Surat Keterangan Usaha',
                  'domisili' => 'Surat Keterangan Domisili'
              ];
            @endphp
            @forelse($pengajuans as $index => $pengajuan)
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $jenisSuratMap[strtolower($pengajuan->jenis_surat)] ?? strtoupper($pengajuan->jenis_surat) }}</td>
              <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y, H:i') }} WIB</td>
              <td>
                @if(strtolower($pengajuan->status) == 'disetujui')
                  <span class="badge badge-green">Disetujui</span>
                @elseif(strtolower($pengajuan->status) == 'menunggu')
                  <span class="badge badge-amber">Menunggu</span>
                @else
                  <span class="badge badge-red">Ditolak</span>
                @endif
              </td>
              <td>
                <div class="action-group">
                  @if(strtolower($pengajuan->status) == 'disetujui')
                    <a href="{{ route('user.detail-pengajuan', ['id' => $pengajuan->id]) }}" class="btn btn-detail">Detail</a>
                    
                  @elseif(strtolower($pengajuan->status) == 'menunggu')
                    <a href="{{ route('user.detail-pengajuan', ['id' => $pengajuan->id]) }}" class="btn btn-detail">Detail</a>
                  @else
                    <a href="{{ route('user.detail-pengajuan', ['id' => $pengajuan->id]) }}" class="btn btn-detail">Detail</a>
                    <a href="{{ route('user.ajukan-surat', ['resubmit_id' => $pengajuan->id]) }}" class="btn btn-ulang">Ajukan ulang</a>
                  @endif
                

                    @if(strtolower($pengajuan->status) == 'disetujui' && $pengajuan->is_verified_by_lurah)
                    <a href="{{ route('cetak-surat', $pengajuan->id) }}" target="_blank" class="btn btn-cetak">Cetak PDF</a>
                    @endif
</div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" style="text-align: center; padding: 20px;">Belum ada riwayat pengajuan surat.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

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
<script>
  @if(session('success'))
    if (!document.getElementById('custom-reg-css')) {
        const style = document.createElement('style');
        style.id = 'custom-reg-css';
        style.innerHTML = `
          .custom-reg-popup { border-radius: 16px !important; padding: 36px 30px !important; width: 400px !important; }
          .custom-reg-html-container { margin: 0 !important; padding: 0 !important; overflow: visible !important; }
          .custom-reg-icon-wrapper { width: 80px; height: 80px; background-color: #e6fceb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
          .custom-reg-icon-inner { width: 44px; height: 44px; background-color: #15803d; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
          .custom-reg-icon-inner svg { color: white; width: 24px; height: 24px; }
          .custom-reg-title { font-size: 1.3rem; font-weight: 700; color: #1e293b; margin: 0 0 12px; font-family: 'Inter', sans-serif; text-align: center; }
          .custom-reg-text { font-size: 0.95rem; color: #64748b; line-height: 1.5; margin: 0; font-family: 'Inter', sans-serif; text-align: center; }
        `;
        document.head.appendChild(style);
    }
    Swal.fire({
      showConfirmButton: false,
      timer: 3000,
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
      `
    });
  @endif
</script>

<style>.swal-custom { font-family: 'Inter', sans-serif; border-radius: 12px; } .swal2-confirm { padding: 10px 24px !important; font-weight: 600 !important; border-radius: 8px !important; } .swal2-cancel { padding: 10px 24px !important; font-weight: 600 !important; border-radius: 8px !important; color: #1e293b !important; background: #e2e8f0 !important; }
.form-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }
.input-control:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }
</style>
<script src="{{ asset('js/notif.js') }}"></script>
<script src="{{ asset('js/logout.js') }}"></script>
</body>
</html>

