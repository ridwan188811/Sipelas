<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Riwayat Pengajuan – SIPELAS</title>
  <meta name="description" content="Daftar Pengajuan SIPELAS – Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <style>
    /* ===== RESET & BASE ===== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; }
    body {
      font-family: 'Inter', sans-serif;
      background: #f4f7f6; /* Warna latar sedikit lebih terang seperti di screenshot */
      color: #1e293b;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ===== VARIABLES ===== */
    :root {
      --navy:        #1a3558;
      --navy-dark:   #152c48;
      --blue:        #1d4ed8;
      --green:       #16a34a;
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
      --shadow-sm:   0 1px 2px rgba(0,0,0,.05);
      --shadow:      0 4px 6px -1px rgba(0,0,0,.1);
      --radius:      8px;
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
    .content { flex: 1; padding: 32px 40px; max-width: 1400px; margin: 0 auto; width: 100%; }

    /* ===== PAGE HEADER ===== */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 24px;
    }
    .page-title h2 {
      font-size: 1.35rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 4px;
    }
    .page-title p {
      font-size: .85rem;
      color: var(--gray-500);
    }
    .btn-export {
      background: var(--navy);
      color: white;
      border: none;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: 600;
      font-size: .85rem;
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      text-decoration: none;
      box-shadow: var(--shadow-sm);
    }
    .btn-export:hover { background: var(--navy-dark); }
    .btn-export svg { width: 18px; height: 18px; }

    /* ===== FILTER BAR ===== */
    .filter-bar {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      padding: 16px 20px;
      display: flex;
      gap: 20px;
      align-items: center;
      margin-bottom: 24px;
      flex-wrap: wrap;
    }
    .search-input-group {
      position: relative;
      flex: 1;
      min-width: 250px;
    }
    .search-input-group svg {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray-500);
      width: 16px;
      height: 16px;
    }
    .search-input {
      width: 100%;
      padding: 10px 16px 10px 40px;
      border: 1px solid var(--gray-200);
      border-radius: 6px;
      font-size: .85rem;
      color: var(--gray-800);
      background: #f1f5f9;
      outline: none;
    }
    .search-input:focus { border-color: var(--gray-300); }
    
    .filter-select {
      padding: 10px 36px 10px 16px;
      border: 1px solid var(--gray-200);
      border-radius: 6px;
      font-size: .85rem;
      color: var(--gray-600);
      background: #f1f5f9 url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%2364748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>') no-repeat right 12px center;
      appearance: none;
      outline: none;
      min-width: 170px;
      cursor: pointer;
    }
    
    .filter-date {
      padding: 10px 36px 10px 14px;
      border: 1px solid var(--gray-200);
      border-radius: 6px;
      font-size: .85rem;
      color: var(--gray-600);
      background: #f1f5f9 url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%2364748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>') no-repeat right 12px center;
      outline: none;
      font-family: 'Inter', sans-serif;
      width: 140px;
      cursor: pointer;
    }
    
    .filter-divider {
      width: 1px;
      height: 28px;
      background: var(--gray-300);
    }

    .filter-pills {
      display: flex;
      gap: 12px;
    }
    .filter-pill {
      padding: 8px 18px;
      border: 1px solid var(--gray-200);
      border-radius: 20px;
      font-size: .8rem;
      font-weight: 600;
      color: var(--gray-500);
      background: var(--white);
      cursor: pointer;
      text-decoration: none;
      transition: all .2s;
    }
    .filter-pill:hover { border-color: var(--gray-300); color: var(--gray-700); }
    .filter-pill.active {
      background: var(--gray-800);
      border-color: var(--gray-800);
      color: var(--white);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* ===== TABLE CARD ===== */
    .table-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: var(--radius);
      display: flex;
      flex-direction: column;
    }
    .table-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 24px;
      border-bottom: 1px solid var(--gray-200);
    }
    .table-header-left h3 {
      font-size: 1rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 4px;
    }
    .table-header-left p {
      font-size: .75rem;
      color: var(--gray-500);
    }
    .table-header-right {
      font-size: .85rem;
      color: var(--gray-600);
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .table-header-right select {
      padding: 4px 8px;
      border: 1px solid var(--gray-200);
      border-radius: 4px;
      font-size: .85rem;
      outline: none;
      color: var(--gray-800);
    }

    /* Table styles */
    .data-table-wrapper { overflow-x: auto; }
    .data-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }
    .data-table th, .data-table td {
      padding: 18px 24px;
      border-bottom: 1px solid var(--gray-200);
      vertical-align: middle;
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
    
    .data-table td { font-size: .85rem; color: var(--gray-600); }
    .data-table td.col-no { width: 60px; text-align: center; color: var(--gray-500); }
    
    .td-nama { font-weight: 700; color: var(--gray-800); display: block; margin-bottom: 4px; font-size: .9rem; }
    .td-nik { font-size: .75rem; color: var(--blue); }
    
    .td-tanggal { display: block; margin-bottom: 4px; color: var(--gray-800); }
    .td-waktu { font-size: .75rem; color: var(--gray-500); }

    /* Badges */
    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: .75rem;
      font-weight: 700;
    }
    .badge-yellow { background: #fef08a; color: #854d0e; }
    .badge-green { background: #6ee7b7; color: #065f46; }
    .badge-red { background: #fca5a5; color: #991b1b; }

    /* Action Buttons */
    .action-group { display: flex; gap: 8px; }
    .btn {
      padding: 8px 16px;
      border-radius: 6px;
      font-size: .8rem;
      font-weight: 600;
      border: none;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background .15s, opacity .15s;
    }
    .btn:hover { opacity: .9; }
    .btn-proses { background: var(--blue); color: white; }
    .btn-detail { background: white; color: var(--gray-600); border: 1px solid var(--gray-300); }
    .btn-detail:hover { background: var(--gray-50); border-color: var(--gray-400); color: var(--gray-800); }
    .btn-cetak { background: var(--green); color: white; }
    .btn svg { width: 14px; height: 14px; }

    /* Table Footer */
    .table-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px 24px;
      border-top: 1px solid var(--gray-200);
      background: #fafafa;
      border-bottom-left-radius: var(--radius);
      border-bottom-right-radius: var(--radius);
    }
    .table-info {
      font-size: .85rem;
      color: var(--gray-600);
    }
    /* Pagination Overrides */
    .pagination {
      display: flex;
      list-style: none;
      padding: 0;
      margin: 0;
      gap: 6px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .page-item .page-link {
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 34px;
      height: 34px;
      padding: 0 10px;
      border: 1px solid var(--gray-200);
      border-radius: 6px;
      background: var(--white);
      color: var(--gray-600);
      text-decoration: none;
      font-size: .85rem;
      font-weight: 500;
      transition: all .15s;
    }
    .page-item.active .page-link {
      background: var(--blue);
      border-color: var(--blue);
      color: var(--white);
      font-weight: 600;
    }
    .page-item.disabled .page-link {
      background: var(--gray-50);
      color: var(--gray-400);
      border-color: var(--gray-200);
      cursor: not-allowed;
    }
    .page-item:not(.active):not(.disabled) .page-link:hover {
      background: var(--gray-50);
      border-color: var(--gray-300);
      color: var(--gray-800);
    }

    /* ===== MOBILE OVERLAY ===== */
    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 25; }
    .sidebar-overlay.active { display: block; }

    /* =============================================
       RESPONSIVE
    ============================================= */
    .mobile-bottom-nav { display: none; }

    @media (max-width: 1024px) {
      .filter-bar { flex-direction: column; align-items: stretch; }
      .filter-divider { display: none; }
      .filter-pills { flex-wrap: wrap; }
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
      .page-header { flex-direction: column; align-items: flex-start; gap: 16px; }
      
      .table-header { flex-direction: column; align-items: flex-start; gap: 12px; }
      
      /* Card-based Table (List View) for App Feel */
      .data-table thead { display: none; }
      .data-table, .data-table tbody, .data-table tr, .data-table td { display: block; width: 100%; }
      .data-table tr {
        margin-bottom: 12px; border: 1px solid var(--gray-200); border-radius: var(--radius);
        padding: 14px; background: var(--white); position: relative; box-shadow: var(--shadow-sm);
      }
      .data-table td { padding: 4px 0; border: none; text-align: left !important; font-size: .85rem; }
      .data-table td::before { display: none; } /* Reset */
      
      .data-table td:nth-child(1) { display: none; } /* Hide NO */
      .data-table td:nth-child(2) { font-weight: 700; color: var(--gray-800); font-size: .95rem; margin-bottom: 2px; padding-right: 90px !important; } /* Nama */
      .data-table td:nth-child(3) { color: var(--navy); font-size: .85rem; margin-bottom: 4px; font-weight: 600; } /* Jenis */
      .data-table td:nth-child(4) { color: var(--gray-500); font-size: .75rem; margin-bottom: 10px; } /* Tanggal */
      .data-table td:nth-child(5) { position: absolute; top: 14px; right: 14px; padding: 0; width: auto !important; } /* Status Badge */
      .data-table td:nth-child(6) { margin-top: 8px; padding-top: 12px; border-top: 1px solid var(--gray-100); } /* Aksi */
      .table-card { border: none; box-shadow: none; background: transparent; }

      .table-footer { flex-direction: column; gap: 16px; align-items: flex-start; }
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
  
  <!-- ===== SIDEBAR (Hidden by default for Admin to match full width design) ===== -->
  <aside class="sidebar collapsed" id="sidebar" aria-label="Navigasi Utama">
    <nav class="sidebar-nav">
      <a href="{{ route('admin.dashboard') }}" class="nav-item" id="nav-dashboard"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg> Dashboard</a>
      <a href="{{ route('admin.daftar-pengajuan') }}" class="nav-item" id="nav-kelola"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><polyline points="9 14 11 16 15 11"/></svg> Verifikasi Surat</a>
      <a href="{{ route('admin.riwayat-pengajuan') }}" class="nav-item active" id="nav-riwayat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg> Riwayat Pengajuan</a>
      <a href="{{ route('admin.laporan') }}" class="nav-item {{ request()->routeIs('admin.laporan') ? 'active' : '' }}" id="nav-laporan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/><path d="M14 3v5h5M16 13H8M16 17H8M10 9H8"/></svg> Laporan</a>

    </nav>
    <div class="sidebar-footer">
      <a href="{{ route('logout') }}" class="logout-btn" id="logoutBtn" onclick="confirmLogout(event, this.href);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Keluar</a>
    </div>
  </aside>

  <!-- ===== CONTENT AREA ===== -->
  <div class="content-area">
    <main class="content">

      <!-- Page Header -->
      <div class="page-header">
        <div class="page-title">
          <h2>Riwayat Pengajuan</h2>
          <p>Daftar riwayat pengajuan surat warga yang telah diproses</p>
        </div>
        <a href="{{ route('admin.export-excel', request()->all()) }}" class="btn-export">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="17 8 12 3 7 8"></polyline>
            <line x1="12" y1="3" x2="12" y2="15"></line>
          </svg>
          Export Excel
        </a>
      </div>

      <!-- Filter Bar -->
      <form method="GET" action="{{ route('admin.riwayat-pengajuan') }}" class="filter-bar">
        <!-- Search -->
        <div class="search-input-group">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" name="search" class="search-input" placeholder="Cari Nama warga, NIK atau nomor surat" value="{{ request('search') }}" onchange="this.form.submit()">
        </div>
        
        <!-- Selects -->
        <select name="jenis" class="filter-select" onchange="this.form.submit()">
          <option value="">Semua Jenis Surat</option>
          <option value="sktm" {{ request('jenis') == 'sktm' ? 'selected' : '' }}>SKTM</option>
          <option value="sku" {{ request('jenis') == 'sku' ? 'selected' : '' }}>Surat Ket Usaha</option>
          <option value="domisili" {{ request('jenis') == 'domisili' ? 'selected' : '' }}>Surat Ket Domisili</option>
        </select>
        
        <div class="date-filter-group" style="display: flex; align-items: center; gap: 8px;">
          <input type="text" name="start_date" class="filter-date" placeholder="Tanggal Awal" value="{{ request('start_date') }}">
          <span style="color: var(--gray-500); font-weight: 500;">-</span>
          <input type="text" name="end_date" class="filter-date" placeholder="Tanggal Akhir" value="{{ request('end_date') }}">
        </div>

        <div class="filter-divider"></div>

        <!-- Filter Pills -->
        <div class="filter-pills">
          <input type="hidden" name="status" id="statusFilter" value="{{ request('status') }}">
          <button type="button" class="filter-pill {{ !request('status') ? 'active' : '' }}" onclick="document.getElementById('statusFilter').value=''; this.form.submit()">Semua</button>
          <button type="button" class="filter-pill {{ request('status') == 'disetujui' ? 'active' : '' }}" onclick="document.getElementById('statusFilter').value='disetujui'; this.form.submit()">Disetujui</button>
          <button type="button" class="filter-pill {{ request('status') == 'ditolak' ? 'active' : '' }}" onclick="document.getElementById('statusFilter').value='ditolak'; this.form.submit()">Ditolak</button>
        </div>
      </form>

      <!-- Table Card -->
      <div class="table-card">
        <!-- Table Header -->
        <div class="table-header">
          <div class="table-header-left">
            <h3>Data Pengajuan Surat</h3>
            <p>Menampilkan {{ $pengajuans->total() }} Pengajuan</p>
          </div>
          <div class="table-header-right">
            <!-- Selector jumlah data opsional -->
          </div>
        </div>

        <!-- Table Content -->
        <div class="data-table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th class="col-no">NO</th>
                <th>NAMA</th>
                <th>JENIS SURAT</th>
                <th>TANGGAL MASUK</th>
                <th>STATUS</th>
                <th>AKSI</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pengajuans as $index => $pengajuan)
              @php
                $nama = $pengajuan->warga->name ?? ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $pengajuan->warga->email)[0]));
                $nik = $pengajuan->data_isian['nik'] ?? 'Tidak ada NIK';
              @endphp
              <tr>
                <td class="col-no">{{ $pengajuans->firstItem() + $index }}</td>
                <td data-label="NAMA">
                  <span class="td-nama">{{ $nama }}</span>
                  <span class="td-nik">{{ $nik }}</span>
                </td>
                <td data-label="JENIS SURAT">
                  <div style="font-weight: 500; margin-bottom: 4px;">{{ ['sku'=>'Surat Keterangan Usaha','sktm'=>'Surat Keterangan Tidak Mampu','sktm-sekolah'=>'Surat Keterangan Tidak Mampu (Sekolah)','domisili'=>'Surat Keterangan Domisili','belum-menikah'=>'Surat Keterangan Belum Menikah','kelahiran'=>'Surat Keterangan Kelahiran','kematian'=>'Surat Keterangan Kematian','pengantar-nikah'=>'Surat Pengantar Nikah','pindah'=>'Surat Keterangan Pindah'][$pengajuan->jenis_surat] ?? ucwords(str_replace('-', ' ', $pengajuan->jenis_surat)) }}</div>
                  @if(strtolower($pengajuan->status) == 'disetujui' && $pengajuan->is_verified_by_lurah)
                    <span class="badge badge-green" style="background:#d1fae5; color:#065f46; font-size: 0.7rem; padding: 2px 8px;">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="width:10px; height:10px; margin-right:2px; display:inline-block;"><polyline points="20 6 9 17 4 12"></polyline></svg> Surat Sah
                    </span>
                  @endif
                </td>
                <td data-label="TANGGAL MASUK">
                  <span class="td-tanggal">{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y') }}</span>
                  <span class="td-waktu">{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('H:i') }} WIB</span>
                </td>
                <td data-label="STATUS">
                  @if(strtolower($pengajuan->status) == 'disetujui')
                    <span class="badge badge-green">Disetujui</span>
                  @elseif(strtolower($pengajuan->status) == 'ditolak')
                    <span class="badge badge-red">Ditolak</span>
                  @endif
                </td>
                <td data-label="AKSI">
                  <div class="action-group">
                    <a href="{{ route('admin.detail-pengajuan', ['id' => $pengajuan->id]) }}" class="btn btn-detail">Detail</a>
                    
                  

                    @if(strtolower($pengajuan->status) == 'disetujui')
                    <a href="{{ route('cetak-surat', $pengajuan->id) }}" target="_blank" class="btn btn-cetak">Cetak PDF</a>
                      @if(!$pengajuan->is_verified_by_lurah)
                      <form action="{{ route('admin.proses-pengajuan', $pengajuan->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menerbitkan surat resmi untuk {{ $nama }}?');">
                          @csrf
                          <button type="submit" name="action" value="verifikasi" class="btn" style="background:#0284c7; color:white;">
                              Terbitkan Surat
                          </button>
                      </form>
                      @endif
                    @endif
</div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" style="text-align:center; padding:30px; color:#64748b;">Belum ada riwayat pengajuan surat.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Table Footer (Pagination) -->
        @if($pengajuans->hasPages())
        <div class="table-footer" style="display:flex; justify-content:center; padding:16px;">
          {{ $pengajuans->links('pagination::bootstrap-4') }}
        </div>
        @else
        <div class="table-footer">
          <div class="table-info">Menampilkan {{ $pengajuans->count() }} data</div>
        </div>
        @endif

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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  flatpickr(".filter-date", {
    locale: "id",
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "d-m-Y",
    allowInput: true,
    onChange: function(selectedDates, dateStr, instance) {
      instance.element.form.submit();
    }
  });
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

  @if(session('send_email_notif'))
    document.addEventListener("DOMContentLoaded", function() {
        @php $notif = session('send_email_notif'); @endphp
        fetch("{{ route('admin.send-email-notification') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                pengajuan_id: "{{ $notif['id'] ?? '' }}",
                action: "{{ $notif['action'] ?? '' }}"
            })
        }).then(r => r.json()).then(data => {
            console.log('Notifikasi email status:', data);
        }).catch(e => console.error('Error notifikasi email:', e));
    });
  @endif

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
<script src="{{ asset('js/notif.js') }}"></script>
<script src="{{ asset('js/logout.js') }}"></script>
</body>
</html>

