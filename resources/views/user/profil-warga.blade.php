<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Profil Saya – SIPELAS</title>
  <meta name="description" content="Profil pengguna SIPELAS – Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari" />
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
      font-size: .88rem; cursor: pointer; flex-shrink: 0;
    }

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
    .content { flex: 1; padding: 28px 40px; max-width: 1200px; margin: 0 auto; width: 100%; }

    .detail-title { font-size: 1.15rem; font-weight: 700; color: var(--gray-800); margin-bottom: 24px; }

    /* ===== SECTION CARDS ===== */
    .section-card {
      background: var(--white); border: 1px solid var(--gray-200);
      border-radius: var(--radius); box-shadow: var(--shadow-sm);
    }
    .section-card-title {
      font-size: 1rem; font-weight: 700; color: var(--navy);
      padding: 24px 24px 20px;
      display: flex; align-items: center; gap: 10px;
      border-bottom: 1px solid var(--gray-200);
    }
    .section-card-title::before {
      content: ''; display: block; width: 4px; height: 18px;
      background: var(--navy); border-radius: 4px;
    }

    /* ===== NEW PROFILE HEADER ===== */
    .profile-header-card {
      background: var(--white); border: 1px solid var(--gray-200);
      border-radius: var(--radius); box-shadow: var(--shadow-sm);
      padding: 24px 32px; display: flex; align-items: center; gap: 24px;
      margin-bottom: 24px;
    }
    .header-avatar {
      width: 80px; height: 80px; border-radius: 50%;
      background: var(--navy); color: white;
      display: flex; align-items: center; justify-content: center;
      font-size: 2.2rem; font-weight: 700; flex-shrink: 0;
    }
    .header-info {
      flex: 1;
    }
    .header-name {
      font-size: 1.25rem; font-weight: 700; color: var(--gray-800); margin-bottom: 4px; text-transform: uppercase;
    }
    .header-email {
      font-size: .95rem; color: var(--gray-500); margin-bottom: 8px;
    }
    .header-joined {
      display: inline-block; padding: 4px 10px; background: var(--blue-light);
      color: var(--blue-text); border-radius: 20px; font-size: .75rem; font-weight: 600;
    }

    .header-card-actions { margin-left: auto; display: flex; gap: 8px; align-items: center; }
    .btn-action-small {
      display: flex; align-items: center; gap: 6px; padding: 8px 16px;
      border-radius: 6px; font-size: .85rem; font-weight: 600; text-decoration: none;
      border: 1px solid var(--gray-300); color: var(--gray-700); transition: all .2s; background: var(--white);
    }
    .btn-action-small:hover { background: var(--gray-100); color: var(--gray-900); }
    .btn-action-small.logout { border-color: #fca5a5; color: #b91c1c; background: #fff5f5; }
    .btn-action-small.logout:hover { background: #fef2f2; border-color: #ef4444; }

    .profile-body { padding: 24px; }
    .form-group { margin-bottom: 20px; }
    .form-group label {
      display: block; font-size: .9rem; font-weight: 600;
      color: var(--gray-800); margin-bottom: 8px;
    }
    .input-readonly {
      width: 100%; padding: 14px 16px; border-radius: 8px;
      border: none; background: #828282; color: white;
      font-size: .95rem; font-family: 'Inter', sans-serif;
      outline: none;
    }
    .input-control {
      width: 100%; padding: 12px 14px; border: 1px solid var(--gray-300);
      border-radius: 6px; font-size: .9rem; font-family: 'Inter', sans-serif;
      color: var(--gray-800); outline: none; background-color: var(--white);
      transition: all 0.2s ease;
    }
    .input-control::placeholder { color: var(--gray-400); }
    .input-control:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-light); }
    
    select.input-control {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 6l5 5 5-5'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      background-size: 14px 14px;
      padding-right: 40px;
    }
    select.input-control:disabled {
      background-image: none;
    }

    input[type="date"].input-control {
      appearance: none;
      -webkit-appearance: none;
      min-width: 0;
      max-width: 100%;
      min-height: 45px; /* Ensures consistent height with other inputs */
      text-align: left !important;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='22' height='22' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      background-size: 20px 20px;
      padding-right: 44px;
      position: relative;
    }
    input[type="date"].input-control::-webkit-datetime-edit,
    input[type="date"].input-control::-webkit-date-and-time-value {
      text-align: left !important;
      padding: 0 !important;
      justify-content: flex-start !important;
      display: flex !important;
    }
    input[type="date"].input-control::-webkit-calendar-picker-indicator {
      position: absolute;
      top: 0; right: 0; bottom: 0; left: 0;
      width: 100%; height: 100%;
      opacity: 0;
      cursor: pointer;
    }

    .form-grid {
      display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
    }
    .form-col-full { grid-column: 1 / -1; }
    .btn-submit {
      background: var(--navy); color: white; border: none; padding: 12px 24px;
      border-radius: 6px; font-weight: 600; cursor: pointer; transition: opacity .2s;
    }
    .btn-submit:hover { opacity: .9; }

    /* ===== MODAL ===== */
    .modal-overlay {
      display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5);
      z-index: 1000; align-items: center; justify-content: center; padding: 20px;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
      background: white; border-radius: 12px; width: 100%; max-width: 450px;
      box-shadow: 0 10px 25px rgba(0,0,0,.15); overflow: hidden;
      animation: modalIn .3s ease;
    }
    @keyframes modalIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center; }
    .modal-title { font-size: 1.15rem; font-weight: 700; color: var(--navy); }
    .modal-close { background: none; border: none; cursor: pointer; color: var(--gray-500); padding: 4px; border-radius: 6px; }
    .modal-close:hover { background: var(--gray-100); color: var(--gray-800); }
    .modal-body-pad { padding: 24px; }
    .modal-footer { padding: 16px 24px; background: var(--gray-50); border-top: 1px solid var(--gray-200); display: flex; justify-content: flex-end; gap: 12px; }
    .btn-cancel { padding: 10px 16px; border: 1px solid var(--gray-300); background: white; border-radius: 8px; font-weight: 600; cursor: pointer; color: var(--gray-700); }
    .btn-cancel:hover { background: var(--gray-50); }

    /* ===== MOBILE OVERLAY ===== */
    .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 25; }
    .sidebar-overlay.active { display: block; }

    /* =============================================
       RESPONSIVE
    ============================================= */
    .mobile-bottom-nav { display: none; }

    @media (max-width: 680px) {
      .profile-header-card { flex-direction: column; text-align: center; gap: 16px; }
      .header-card-actions { margin-left: 0; width: 100%; justify-content: center; flex-wrap: wrap; }
      .form-grid { grid-template-columns: 1fr; gap: 16px; }
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
    }
  
.form-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }

.filter-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }


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
  
  <!-- ===== SIDEBAR (Hidden by default for Profile View) ===== -->
  <aside class="sidebar collapsed" id="sidebar" aria-label="Navigasi Utama">
    <nav class="sidebar-nav">
      <a href="{{ route('user.dashboard') }}" class="nav-item" id="nav-dashboard"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg> Dashboard</a>
      <a href="{{ route('user.ajukan-surat') }}" class="nav-item" id="nav-ajukan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9"  y1="15" x2="15" y2="15"/></svg> Ajukan Surat</a>
      <a href="{{ route('user.riwayat') }}" class="nav-item" id="nav-riwayat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg> Riwayat</a>
    </nav>
    <div class="sidebar-footer">
      <a href="{{ route('logout') }}" class="logout-btn" id="logoutBtn" onclick="confirmLogout(event, this.href);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Keluar</a>
    </div>
  </aside>

  <!-- ===== CONTENT AREA ===== -->
  <div class="content-area">
    <main class="content">

      <div class="detail-title">Profil Saya</div>
      
      @if(session('error'))
      <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fca5a5;">
        <strong>Perhatian!</strong> {{ session('error') }}
      </div>
      @endif
      @if ($errors->any())
      <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fca5a5;">
        <strong style="display:block; margin-bottom: 5px;">Gagal menyimpan profil:</strong>
        <ul style="margin: 0; padding-left: 20px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <!-- HEADER CARD -->
      <div class="profile-header-card">
        <div class="header-avatar">{{ strtoupper(substr((Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->name ?? (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->email, 0, 1)) }}</div>
        <div class="header-info">
          <div class="header-name">{{ (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->name ?? (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->email }}</div>
          <div class="header-email">{{ (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->email }}</div>
          <div class="header-joined">Bergabung sejak {{ (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->created_at->translatedFormat('d F Y') }}</div>
        </div>
        <div class="header-card-actions">
          <button type="button" class="btn-action-small" style="cursor: pointer;" onclick="openPasswordModal()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px; height:16px;">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            Ubah Kata Sandi
          </button>
          <a href="{{ route('logout') }}" class="btn-action-small logout" onclick="confirmLogout(event, this.href);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px; height:16px;">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            Keluar
          </a>
        </div>
      </div>

      <!-- BIODATA FORM -->
      <form id="biodataForm" action="{{ route('user.profil.update') }}" method="POST">
        @csrf
        <div class="section-card" style="margin-bottom: 40px;">
          <div style="padding: 24px 24px 20px; border-bottom: 1px solid var(--gray-200); display: flex; justify-content: space-between; align-items: center;">
            <h3 class="section-card-title" style="border: none; padding: 0;">Biodata KTP (Wajib dilengkapi)</h3>
            <button type="button" id="btnEditProfile" onclick="enableProfileEdit()" title="Ubah Biodata" style="background: var(--blue-light); border: none; cursor: pointer; color: var(--blue-text); display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; transition: opacity .2s; display: none;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </button>
          </div>
          <div class="profile-body">
            <div class="form-grid">
              <div class="form-group form-col-full">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="input-control" value="{{ old('name', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->name) }}" placeholder="Masukkan nama lengkap sesuai KTP" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label>NIK</label>
                <input type="text" name="nik" class="input-control" value="{{ old('nik', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->nik) }}" placeholder="Masukkan 16 digit NIK" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label>No. WhatsApp / HP</label>
                <input type="text" name="no_hp" class="input-control" value="{{ old('no_hp', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->no_hp) }}" placeholder="Contoh: 081234567890" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
              </div>
              <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="input-control" value="{{ old('tempat_lahir', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->tempat_lahir) }}" placeholder="Contoh: Tasikmalaya" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="input-control" value="{{ old('tanggal_lahir', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->tanggal_lahir ? \Carbon\Carbon::parse((Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->tanggal_lahir)->format('Y-m-d') : '') }}" required>
              </div>
              <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="input-control" required>
                  <option value="" disabled {{ old('jenis_kelamin', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->jenis_kelamin) ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                  <option value="l" {{ old('jenis_kelamin', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->jenis_kelamin) == 'l' ? 'selected' : '' }}>Laki-laki</option>
                  <option value="p" {{ old('jenis_kelamin', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->jenis_kelamin) == 'p' ? 'selected' : '' }}>Perempuan</option>
                </select>
              </div>
              <div class="form-group">
                <label>Kewarganegaraan</label>
                <input type="text" name="kewarganegaraan" class="input-control" value="{{ old('kewarganegaraan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kewarganegaraan ?? 'WNI') }}" placeholder="Contoh: WNI" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label>Agama</label>
                <select name="agama" class="input-control" required>
                  <option value="" disabled {{ old('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) ? '' : 'selected' }}>Pilih Agama</option>
                  <option value="islam" {{ old('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'islam' ? 'selected' : '' }}>Islam</option>
                  <option value="kristen" {{ old('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'kristen' ? 'selected' : '' }}>Kristen</option>
                  <option value="katolik" {{ old('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'katolik' ? 'selected' : '' }}>Katolik</option>
                  <option value="hindu" {{ old('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'hindu' ? 'selected' : '' }}>Hindu</option>
                  <option value="budha" {{ old('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'budha' ? 'selected' : '' }}>Budha</option>
                  <option value="konghucu" {{ old('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                </select>
              </div>
              <div class="form-group">
                <label>Pekerjaan</label>
                <input type="text" name="pekerjaan" class="input-control" value="{{ old('pekerjaan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->pekerjaan) }}" placeholder="Contoh: Wiraswasta" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label>Status Pernikahan</label>
                <select name="status_pernikahan" class="input-control" required>
                  <option value="" disabled {{ old('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) ? '' : 'selected' }}>Pilih Status Pernikahan</option>
                  <option value="belum" {{ old('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'belum' ? 'selected' : '' }}>Belum Kawin</option>
                  <option value="kawin" {{ old('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'kawin' ? 'selected' : '' }}>Kawin</option>
                  <option value="cerai_hidup" {{ old('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                  <option value="cerai_mati" {{ old('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                </select>
              </div>
              <div class="form-group form-col-full">
                <label>Alamat Lengkap</label>
                <textarea name="alamat_lengkap" class="input-control" rows="2" placeholder="Masukkan nama jalan, kampung, atau perumahan" required>{{ old('alamat_lengkap', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->alamat_lengkap) }}</textarea>
              </div>
              <div class="form-group">
                <label>RT</label>
                <input type="text" name="rt" class="input-control" value="{{ old('rt', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->rt) }}" placeholder="Contoh: 001" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label>RW</label>
                <input type="text" name="rw" class="input-control" value="{{ old('rw', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->rw) }}" placeholder="Contoh: 005" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
              </div>
              <div class="form-group form-col-full">
                <label>Kelurahan</label>
                <input type="text" name="kelurahan" class="input-control" value="{{ old('kelurahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kelurahan ?? 'Sambongpari') }}" placeholder="Contoh: Sambongpari" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label>Kecamatan</label>
                <input type="text" name="kecamatan" class="input-control" value="{{ old('kecamatan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kecamatan ?? 'Mangkubumi') }}" placeholder="Contoh: Mangkubumi" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>
              <div class="form-group">
                <label>Kabupaten / Kota</label>
                <input type="text" name="kota" class="input-control" value="{{ old('kota', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kota ?? 'Tasikmalaya') }}" placeholder="Contoh: Tasikmalaya" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>
            </div>
            <div id="submitBtnContainer" style="margin-top: 24px; text-align: right; display: none;">
              <button type="button" class="btn-cancel" onclick="disableProfileEdit()" style="margin-right: 12px;">Batal</button>
              <button type="submit" class="btn-submit">Simpan Profil</button>
            </div>
          </div>
        </div>
      </form>

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
  <a href="{{ route('user.riwayat') }}" class="bottom-nav-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg>
    <span>Riwayat</span>
  </a>
  <a href="{{ route('user.profil') }}" class="bottom-nav-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
    <span>Profil</span>
  </a>
</nav>

<!-- MODAL UBAH KATA SANDI -->
<div class="modal-overlay" id="passwordModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3 class="modal-title">Ubah Kata Sandi</h3>
      <button class="modal-close" onclick="closePasswordModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:20px;height:20px;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
    <form action="{{ route('user.update-kata-sandi') }}" method="POST">
      @csrf
      <div class="modal-body-pad">
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px; color: #1e40af; font-size: .85rem; line-height: 1.5; margin-bottom: 20px;">
          Setelah menyimpan kata sandi baru, Anda akan otomatis keluar dan diminta untuk masuk kembali.
        </div>
        
        <div class="form-group" style="text-align: left;">
          <label style="display:block; margin-bottom:8px; font-weight:600; font-size:.9rem;">Kata Sandi Baru</label>
          <input type="password" name="password" class="input-control" placeholder="Masukkan kata sandi baru" required>
          @error('password') <div style="color: #dc2626; font-size: .8rem; margin-top: 4px; text-align: left;">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom:0; text-align: left;">
          <label style="display:block; margin-bottom:8px; font-weight:600; font-size:.9rem;">Konfirmasi Kata Sandi Baru</label>
          <input type="password" name="password_confirm" class="input-control" placeholder="Ulangi kata sandi baru" required>
          @error('password_confirm') <div style="color: #dc2626; font-size: .8rem; margin-top: 4px; text-align: left;">{{ $message }}</div> @enderror
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn-submit">Simpan Sandi</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openPasswordModal() {
    document.getElementById('passwordModal').classList.add('active');
  }
  function closePasswordModal() {
    document.getElementById('passwordModal').classList.remove('active');
  }
  
  @if($errors->has('password') || $errors->has('password_confirm'))
    openPasswordModal();
  @endif
</script>

<!-- ===== JAVASCRIPT ===== -->
<script>
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const sidebar      = document.getElementById('sidebar');
  const overlay      = document.getElementById('sidebarOverlay');
  const isMobile     = () => window.innerWidth <= 680;

  // Profil Warga by default has collapsed sidebar to match the full-width layout in design
  // Hamburger menu just toggles it like a drawer
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

  // Profile Edit Toggle Logic
  document.addEventListener('DOMContentLoaded', function() {
    const isProfileIncomplete = {{ (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->nik ? 'false' : 'true' }};
    const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
    
    if (!isProfileIncomplete && !hasErrors) {
      disableProfileEdit();
    } else {
      enableProfileEdit();
    }
  });

  function disableProfileEdit() {
    const inputs = document.querySelectorAll('#biodataForm .input-control');
    inputs.forEach(input => {
      if (input.tagName === 'SELECT') {
        input.disabled = true;
      } else {
        input.readOnly = true;
      }
      input.style.backgroundColor = '#f1f5f9';
      input.style.borderColor = 'transparent';
      input.style.color = '#64748b';
      input.style.cursor = 'not-allowed';
      input.style.pointerEvents = 'none';
    });
    document.getElementById('submitBtnContainer').style.display = 'none';
    document.getElementById('btnEditProfile').style.display = 'flex';
  }

  function enableProfileEdit() {
    const inputs = document.querySelectorAll('#biodataForm .input-control');
    inputs.forEach(input => {
      if (input.tagName === 'SELECT') {
        input.disabled = false;
      } else {
        input.readOnly = false;
      }
      input.style.backgroundColor = '';
      input.style.borderColor = '';
      input.style.color = '';
      input.style.cursor = '';
      input.style.pointerEvents = '';
    });
    document.getElementById('submitBtnContainer').style.display = 'block';
    document.getElementById('btnEditProfile').style.display = 'none';
  }
</script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
  .custom-reg-popup {
    border-radius: 16px !important;
    padding: 36px 30px !important;
    width: 400px !important;
  }
  .custom-reg-html-container {
    margin: 0 !important;
  }
  .custom-reg-icon-wrapper {
    width: 64px;
    height: 64px;
    background-color: #dcfce7;
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
    transition: background-color 0.2s;
  }
  .custom-reg-btn:hover { background: #152c48 !important; }
</style>
<script>
  @if(session('success'))
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
  @endif
</script>
<script src="{{ asset('js/notif.js') }}"></script>

<script src="{{ asset('js/logout.js') }}"></script>
</body>
</html>

