<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Ubah Kata Sandi – SIPELAS</title>
  <meta name="description" content="Ubah kata sandi pengguna SIPELAS – Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari" />
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
    .user-avatar:hover { opacity: 0.85; }

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
    
    .info-box-blue {
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      border-radius: 8px;
      padding: 16px;
      color: #1e40af;
      font-size: .85rem;
      line-height: 1.5;
      margin-bottom: 24px;
    }

    .form-group { margin-bottom: 20px; }
    .form-group label {
      display: flex; justify-content: space-between; align-items: center;
      font-size: .9rem; font-weight: 600;
      color: var(--gray-800); margin-bottom: 8px;
    }
    .input-wrap { position: relative; }
    .form-input { 
      width: 100%; padding: 14px 16px; border-radius: 10px;
      border: none; background: #cdd5dc; color: var(--gray-800);
      font-size: .92rem; font-family: 'Inter', sans-serif;
      outline: none; transition: background .18s, box-shadow .18s;
    }
    .form-input::placeholder { color: #7f8fa4; }
    .form-input:focus { 
      background: #bec8d1;
      box-shadow: 0 0 0 3px rgba(26,64,104,.18);
    }
    .form-input.is-invalid { border-color: #ef4444; background-color: #fef2f2; }
    .error-message { color: #dc2626; font-size: .82rem; margin-top: 6px; font-weight: 500; }
    
    .eye-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; display: flex; align-items: center; padding: 4px; transition: color .18s; }
    .eye-btn:hover { color: var(--navy); }
    .eye-btn svg { width: 18px; height: 18px; }
    
    .btn-simpan { background: var(--blue); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-size: .95rem; font-weight: 600; cursor: pointer; transition: background .15s; margin-top: 10px; }
    .btn-simpan:hover { background: #1e40af; }

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
      <a href="{{ route('user.riwayat') }}" class="nav-item" id="nav-riwayat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg> Riwayat</a>
    </nav>
    <div class="sidebar-footer">
      <a href="{{ route('logout') }}" class="logout-btn" id="logoutBtn" onclick="confirmLogout(event, this.href);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Keluar</a>
    </div>
  </aside>

  <!-- ===== CONTENT AREA ===== -->
  <div class="content-area">
    <main class="content">

      <h2 class="detail-title">Profil Saya</h2>

      <!-- HEADER CARD -->
      <div class="profile-header-card">
        <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email, 0, 1)) }}</div>
        <div class="header-info">
          <div class="header-name">{{ Auth::user()->name ?? Auth::user()->email }}</div>
          <div class="header-email">{{ Auth::user()->email }}</div>
          <div class="header-joined">Bergabung sejak {{ Auth::user()->created_at->translatedFormat('d F Y') }}</div>
        </div>
        <div class="header-card-actions">
          <a href="{{ route('user.profil') }}" class="btn-action-small">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px; height:16px;">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            Profil Saya
          </a>
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

      <div class="section-card">
        <h3 class="section-card-title">Ubah Kata Sandi</h3>
        <div class="profile-body">
          
          <div class="info-box-blue">
            Setelah menyimpan kata sandi baru, Anda akan otomatis keluar dan diminta untuk masuk kembali menggunakan kata sandi yang baru.
          </div>

          <form action="{{ route('user.update-kata-sandi') }}" method="POST">
            @csrf
            
            <div class="form-group">
              <label for="password">Kata Sandi Baru</label>
              <div class="input-wrap">
                <input type="password" id="password" name="password" class="form-input @error('password') is-invalid @enderror" placeholder="Masukan Kata Sandi Baru Anda" required autocomplete="new-password">
                <button type="button" class="eye-btn" data-target="password">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
              @error('password') <div class="error-message">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
              <label for="password_confirm">Konfirmasi Kata Sandi Baru</label>
              <div class="input-wrap">
                <input type="password" id="password_confirm" name="password_confirm" class="form-input @error('password_confirm') is-invalid @enderror" placeholder="Masukan Konfirmasi Kata Sandi Baru Anda" required autocomplete="new-password">
                <button type="button" class="eye-btn" data-target="password_confirm">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
              @error('password_confirm') <div class="error-message">{{ $message }}</div> @enderror
            </div>
            
            <button type="submit" class="btn-simpan">Simpan Kata Sandi Baru</button>
          </form>

        </div>
      </div>

    </main>
  </div>
</div>

<!-- Mobile Bottom Navigation (Warga) -->
<nav class="mobile-bottom-nav">
  <a href="{{ route('user.dashboard') }}" class="bottom-nav-item" id="bottom-nav-dashboard"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg><span>Dashboard</span></a>
  <a href="{{ route('user.ajukan-surat') }}" class="bottom-nav-item" id="bottom-nav-ajukan"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9"  y1="15" x2="15" y2="15"/></svg><span>Ajukan</span></a>
  <a href="{{ route('user.riwayat') }}" class="bottom-nav-item" id="bottom-nav-riwayat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg><span>Riwayat</span></a>
  <a href="{{ route('user.profil') }}" class="bottom-nav-item active" id="bottom-nav-profil"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg><span>Profil Saya</span></a>
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

  // Eye toggle
  document.querySelectorAll('.eye-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.dataset.target);
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      const icon = btn.querySelector('svg');
      icon.innerHTML = isHidden
        ? `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`
        : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
    });
  });
</script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>.swal-custom { font-family: 'Inter', sans-serif; border-radius: 12px; } .swal2-confirm { padding: 10px 24px !important; font-weight: 600 !important; border-radius: 8px !important; } .swal2-cancel { padding: 10px 24px !important; font-weight: 600 !important; border-radius: 8px !important; color: #1e293b !important; background: #e2e8f0 !important; }</style>

<script src="{{ asset('js/logout.js') }}"></script>
</body>
</html>

