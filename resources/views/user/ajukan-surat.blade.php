<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
@php
    $val = function($field, $default = '') use ($resubmitData) {
        if ($resubmitData && isset($resubmitData->data_isian[$field])) {
            return $resubmitData->data_isian[$field];
        }
        return $default;
    };
    $hasFile = function($field) use ($resubmitData) {
        return $resubmitData && isset($resubmitData->dokumen_pendukung[$field]);
    };
@endphp
  <title>Ajukan Surat - SIPELAS</title>
  <meta name="description" content="Form pengajuan surat SIPELAS - Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari" />
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
       LAYOUT: Header full-width, lalu body = sidebar + content
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
       SIDEBAR - persistent kiri, background putih
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
      transition: width .28s cubic-bezier(.4,0,.2,1), transform .28s cubic-bezier(.4,0,.2,1);
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

    /* Active state */
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
    .user-avatar:hover { opacity: .85; }

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
    .logout-btn svg {
      width: 18px; height: 18px; flex-shrink: 0; color: #ef4444; transition: color .18s;
    }
    .logout-btn:hover { background: #fef2f2; color: #b91c1c; }
    .logout-btn:hover svg { color: #b91c1c; }

    /* =============================================
       CONTENT AREA (kanan sidebar)
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

    /* ===== AJUKAN SURAT UI ===== */
    .page-header {
      margin-bottom: 24px;
    }
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
    .form-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: 8px; /* Sudut sedikit melengkung seperti di desain */
      padding: 24px;
      box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .form-group {
      margin-bottom: 0; /* Hanya satu elemen saat ini */
    }
    .form-label {
      display: block;
      font-size: .95rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 12px;
    }
    .form-select {
      width: 100%;
      padding: 14px 16px;
      background: #3f3f46; /* Dark grey sesuai desain */
      color: white;
      border: none;
      border-radius: 6px;
      font-size: .95rem;
      font-family: 'Inter', sans-serif;
      outline: none;
      appearance: none;
      cursor: pointer;
      /* Custom dropdown arrow icon */
      background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23FFFFFF%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E");
      background-repeat: no-repeat;
      background-position: right 16px top 50%;
      background-size: 18px auto;
      transition: box-shadow 0.2s;
    }
    .form-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }
    .form-select option {
      background: #ffffff;
      color: #1e293b;
    }

    /* ===== FORM SKTM (EXTENDED) ===== */
    .alert-info {
      background-color: #eff6ff;
      border: 1px solid #bfdbfe;
      color: #1e3a8a;
      padding: 12px 16px;
      border-radius: 6px;
      font-size: .88rem;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 16px;
      margin-bottom: 8px;
    }
    .alert-info svg { width: 20px; height: 20px; flex-shrink: 0; }
    .text-required {
      font-size: .8rem;
      color: var(--gray-600);
      margin-bottom: 16px;
      display: block;
    }
    .text-red { color: var(--red); }
    
    .form-section {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: 8px;
      margin-bottom: 24px;
      box-shadow: 0 1px 2px rgba(0,0,0,0.05);
      overflow: hidden;
    }
    .form-section-header {
      background: var(--navy);
      color: white;
      padding: 14px 20px;
      font-weight: 600;
      font-size: .95rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .form-section-header svg { width: 18px; height: 18px; }
    .form-section-body {
      padding: 24px;
    }
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .form-col-full {
      grid-column: 1 / -1;
    }
    .form-input-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
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
    .user-avatar:hover { opacity: .85; }

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
    .logout-btn svg {
      width: 18px; height: 18px; flex-shrink: 0; color: #ef4444; transition: color .18s;
    }
    .logout-btn:hover { background: #fef2f2; color: #b91c1c; }
    .logout-btn:hover svg { color: #b91c1c; }

    /* =============================================
       CONTENT AREA (kanan sidebar)
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

    /* ===== AJUKAN SURAT UI ===== */
    .page-header {
      margin-bottom: 24px;
    }
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
    .form-card {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: 8px; /* Sudut sedikit melengkung seperti di desain */
      padding: 24px;
      box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .form-group {
      margin-bottom: 0; /* Hanya satu elemen saat ini */
    }
    .form-label {
      display: block;
      font-size: .95rem;
      font-weight: 700;
      color: var(--gray-800);
      margin-bottom: 12px;
    }
    .form-select {
      width: 100%;
      padding: 14px 16px;
      background: #ffffff;
      color: var(--gray-800);
      border: 1px solid var(--gray-300);
      border-radius: 8px;
      font-size: .95rem;
      font-family: 'Inter', sans-serif;
      font-weight: 500;
      outline: none;
      appearance: none;
      cursor: pointer;
      /* Custom dropdown arrow icon */
      background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23475569%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E");
      background-repeat: no-repeat;
      background-position: right 16px top 50%;
      background-size: 18px auto;
      
    }
    
    .form-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }
    .form-select option {
      background: #ffffff;
      color: #1e293b;
    }

    /* ===== FORM SKTM (EXTENDED) ===== */
    .alert-info {
      background-color: #eff6ff;
      border: 1px solid #bfdbfe;
      color: #1e3a8a;
      padding: 12px 16px;
      border-radius: 6px;
      font-size: .88rem;
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 16px;
      margin-bottom: 8px;
    }
    .alert-info svg { width: 20px; height: 20px; flex-shrink: 0; }
    .text-required {
      font-size: .8rem;
      color: var(--gray-600);
      margin-bottom: 16px;
      display: block;
    }
    .text-red { color: var(--red); }
    
    .form-section {
      background: var(--white);
      border: 1px solid var(--gray-200);
      border-radius: 8px;
      margin-bottom: 24px;
      box-shadow: 0 1px 2px rgba(0,0,0,0.05);
      overflow: hidden;
    }
    .form-section-header {
      background: var(--navy);
      color: white;
      padding: 14px 20px;
      font-weight: 600;
      font-size: .95rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .form-section-header svg { width: 18px; height: 18px; }
    .form-section-body {
      padding: 24px;
    }
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    .form-col-full {
      grid-column: 1 / -1;
    }
    .form-input-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .input-label {
      font-size: .88rem;
      font-weight: 600;
      color: var(--gray-800);
    }
    .input-control {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid var(--gray-300);
      border-radius: 6px;
      font-size: .9rem;
      font-family: 'Inter', sans-serif;
      color: var(--gray-800);
      outline: none;
      
    }
    .input-control:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }
    .input-control::placeholder {
      color: var(--gray-400);
    }
    textarea.input-control {
      resize: vertical;
      min-height: 80px;
    }
    select.input-control {
      appearance: none;
      background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23475569%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E");
      background-repeat: no-repeat;
      background-position: right 14px top 50%;
      background-size: 16px auto;
      padding-right: 36px;
    }
    input[type="date"].input-control {
      appearance: none;
      -webkit-appearance: none;
      min-width: 0;
      max-width: 100%;
      min-height: 45px;
      text-align: left !important;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='22' height='22' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      background-size: 20px 20px;
      padding-right: 44px;
      position: relative;
      box-sizing: border-box;
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
    .file-drop-area {
      border: 2px dashed var(--gray-300);
      border-radius: 8px;
      padding: 30px;
      text-align: center;
      cursor: pointer;
      transition: border-color .2s, background .2s;
    }
    .file-drop-area:hover {
      border-color: var(--blue);
      background: #f8fafc;
    }
    .file-drop-icon {
      color: var(--gray-500);
      margin-bottom: 10px;
    }
    .file-drop-icon svg { width: 32px; height: 32px; }
    .file-drop-text {
      font-size: .85rem;
      color: var(--gray-500);
    }

    .form-actions {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-top: 32px;
    }
    .btn-action {
      width: 100%;
      padding: 14px;
      border-radius: 6px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      border: none;
      transition: opacity .2s;
    }
    .btn-action:hover { opacity: .9; }
    .btn-submit-form {
      background: var(--navy);
      color: white;
    }
    .btn-cancel-form {
      background: var(--gray-400);
      color: white;
    }

    /* ===== MOBILE OVERLAY ===== */
    .sidebar-overlay {
      display: none;
      position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 25;
    }
    .sidebar-overlay.active { display: block; }

    /* =============================================
       RESPONSIVE & MOBILE APP-LIKE UI
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

      /* Adjust body and hide desktop sidebar/hamburger */
      .page-body { padding-bottom: 65px; }
      .sidebar { display: none !important; }
      .hamburger-btn { display: none !important; }
      .sidebar-overlay { display: none !important; }
      .content { padding: 18px 14px; }
      
      /* Make form grid 1 column on mobile */
      .form-grid { grid-template-columns: 1fr; gap: 16px; }
    }
  
.filter-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }


</style>
  
</head>
<body>

<!-- ===== HEADER ===== -->
<header class="header">
  <!-- Hamburger -->
  <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu" aria-expanded="true" aria-controls="sidebar">
    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round">
      <line x1="3" y1="6"  x2="21" y2="6"/>
      <line x1="3" y1="12" x2="21" y2="12"/>
      <line x1="3" y1="18" x2="21" y2="18"/>
    </svg>
  </button>

  <!-- Brand -->
  <div class="header-brand">
    <div class="header-brand-icon"><img src="{{ asset('images/logo_tasikmalaya.png') }}" alt="Logo Tasikmalaya" style="width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));"></div>
    <div class="header-brand-text">
      <div class="app-name">SIPELAS</div>
      <div class="app-sub">Sistem Informasi Pelayanan Masyarakat</div>
    </div>
  </div>

  <!-- Actions -->
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
      <a href="{{ route('user.ajukan-surat') }}" class="nav-item active" id="nav-ajukan">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
          <line x1="12" y1="18" x2="12" y2="12"/>
          <line x1="9"  y1="15" x2="15" y2="15"/>
        </svg>
        Ajukan Surat
      </a>
      <a href="{{ route('user.riwayat') }}" class="nav-item" id="nav-riwayat">
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

      <!-- Header Ajukan Surat -->
      <div class="page-header">
        <h2 class="page-title">Ajukan Surat</h2>
        <p class="page-subtitle">Pilih jenis surat yang ingin diajukan, kemudian isi form dengan lengap dan benar</p>
      </div>

      @if($resubmitData)
      <div style="background-color: #fffbeb; color: #b45309; padding: 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fcd34d; display: flex; gap: 12px; align-items: flex-start;">
        <svg style="width: 24px; height: 24px; flex-shrink: 0; margin-top: 2px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        <div>
          <strong style="display:block; margin-bottom: 4px; font-size: 1.05rem;">Pengajuan Sebelumnya Ditolak</strong>
          <p style="margin: 0; font-size: 0.95rem; line-height: 1.5;">Catatan Admin: <b>{{ $resubmitData->catatan_admin ?? 'Tidak ada catatan spesifik.' }}</b></p>
          <p style="margin: 6px 0 0 0; font-size: 0.85rem; opacity: 0.9;">Silakan perbaiki data atau dokumen yang salah di bawah ini. Anda tidak perlu mengunggah ulang dokumen yang sudah benar.</p>
        </div>
      </div>
      @endif

      @if ($errors->any())
      <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fca5a5;">
        <strong style="display:block; margin-bottom: 5px;">Gagal mengirim pengajuan! Periksa kembali isian Anda:</strong>
        <ul style="margin: 0; padding-left: 20px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      <script>
        document.addEventListener("DOMContentLoaded", () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
      </script>
      @endif
      <!-- Form Card -->
      <div class="form-card">
        <div class="form-group">
          <label for="jenis_surat" class="form-label">Pilih Jenis Surat</label>
          <select id="jenis_surat" name="jenis_surat" class="form-select" onchange="toggleForm(this.value)">
            <option value="" disabled {{ !request('jenis') ? 'selected' : '' }}>-- Pilih Jenis Surat --</option>
            <option value="sktm" {{ request('jenis') == 'sktm' ? 'selected' : '' }}>Surat Keterangan Tidak Mampu (SKTM)</option>
            <option value="sku" {{ request('jenis') == 'sku' ? 'selected' : '' }}>Surat Keterangan Usaha (SKU)</option>
            <option value="domisili" {{ request('jenis') == 'domisili' ? 'selected' : '' }}>Surat Keterangan Domisili</option>
          </select>
        </div>
      </div>

      <div id="form-sktm" class="dynamic-form" style="display: none;">
        <!-- Informasi Alert SKTM -->
        <div class="alert-info">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
          <div>
            <strong>Informasi Penting:</strong> Khusus untuk SKTM, <strong>Data Pemohon</strong> harus diisi menggunakan data dari <strong>Kepala Keluarga</strong>, bukan data pembuat pengajuan.
          </div>
        </div>
        <span class="text-required"><span class="text-red">*</span> kolom wajib di isi</span>

        <!-- Form SKTM -->
        <form action="{{ route('user.ajukan-surat.submit') }}" method="POST" enctype="multipart/form-data" id="sktmForm" onsubmit="event.preventDefault(); confirmSubmit(this.id);">
          @csrf
          @if(request('resubmit_id'))
          <input type="hidden" name="resubmit_id" value="{{ request('resubmit_id') }}">
          @endif
          <input type="hidden" name="jenis_surat" value="sktm">
        
        <!-- SECTION 1: Data Pemohon -->
        <div class="form-section">
          <div class="form-section-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Data Pemohon (Kepala Keluarga)
          </div>
          <div class="form-section-body">
            <div class="form-grid">
              
              <div class="form-input-group form-col-full">
                <label class="input-label">Nama Lengkap <span class="text-red">*</span></label>
                <input type="text" name="nama_lengkap" class="input-control" value="" placeholder="Masukkan nama lengkap Kepala Keluarga" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>

              <div class="form-input-group">
                <label class="input-label">NIK <span class="text-red">*</span></label>
                <input type="text" name="nik" class="input-control" value="" placeholder="Contoh: 3206012345678901" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
              </div>

              <div class="form-input-group">
                <label class="input-label">Jenis Kelamin <span class="text-red">*</span></label>
                <select name="jenis_kelamin" class="input-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="l">Laki-laki</option>
                  <option value="p">Perempuan</option>
                </select>
              </div>

              <div class="form-input-group">
                <label class="input-label">Tempat Lahir <span class="text-red">*</span></label>
                <input type="text" name="tempat_lahir" class="input-control" value="" placeholder="Contoh: Tasikmalaya" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>

              <div class="form-input-group">
                <label class="input-label">Tanggal Lahir <span class="text-red">*</span></label>
                <input type="date" name="tanggal_lahir" class="input-control" value="" required>
              </div>

              <div class="form-input-group">
                <label class="input-label">Kewarganegaraan <span class="text-red">*</span></label>
                <input type="text" name="kewarganegaraan" class="input-control" value="" placeholder="Contoh: WNI" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>

              <div class="form-input-group">
                <label class="input-label">Agama <span class="text-red">*</span></label>
                <select name="agama" class="input-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="islam">Islam</option>
                  <option value="kristen">Kristen</option>
                  <option value="katolik">Katolik</option>
                  <option value="hindu">Hindu</option>
                  <option value="budha">Budha</option>
                  <option value="konghucu">Konghucu</option>
                </select>
              </div>

              <div class="form-input-group">
                <label class="input-label">Pekerjaan <span class="text-red">*</span></label>
                <input type="text" name="pekerjaan" class="input-control" value="" placeholder="Contoh: Wiraswasta / Petani" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>

              <div class="form-input-group">
                <label class="input-label">Status Pernikahan <span class="text-red">*</span></label>
                <select name="status_pernikahan" class="input-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="belum">Belum Kawin</option>
                  <option value="kawin">Kawin</option>
                  <option value="cerai_hidup">Cerai Hidup</option>
                  <option value="cerai_mati">Cerai Mati</option>
                </select>
              </div>

              <div class="form-input-group form-col-full">
                <label class="input-label">Alamat Lengkap <span class="text-red">*</span></label>
                <textarea name="alamat_lengkap" class="input-control" rows="2" placeholder="Contoh: Jl. Pahlawan No. 12, Kp. Babakan" required></textarea>
              </div>

              <div class="form-input-group">
                <label class="input-label">RT <span class="text-red">*</span></label>
                <input type="text" name="rt" class="input-control" value="" placeholder="Contoh: 001" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
              </div>

              <div class="form-input-group">
                <label class="input-label">RW <span class="text-red">*</span></label>
                <input type="text" name="rw" class="input-control" value="" placeholder="Contoh: 005" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
              </div>

              <div class="form-input-group form-col-full">
                <label class="input-label">Kelurahan <span class="text-red">*</span></label>
                <input type="text" name="kelurahan" class="input-control" value="" placeholder="Contoh: Mangkubumi" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>

              <div class="form-input-group">
                <label class="input-label">Kecamatan <span class="text-red">*</span></label>
                <input type="text" name="kecamatan" class="input-control" value="" placeholder="Contoh: Mangkubumi" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>

              <div class="form-input-group">
                <label class="input-label">Kota <span class="text-red">*</span></label>
                <input type="text" name="kota" class="input-control" value="" placeholder="Contoh: Kota Tasikmalaya" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>

            </div>
          </div>
        </div>

        <!-- SECTION 2: Keperluan Pengajuan -->
        <div class="form-section">
          <div class="form-section-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            Keperluan Pengajuan SKTM
          </div>
          <div class="form-section-body">
            <div class="form-grid">
              
              <div class="form-input-group form-col-full">
                <label class="input-label">Keperluan Surat <span class="text-red">*</span></label>
                <select name="keperluan" id="sktm_keperluan_select" class="input-control" required onchange="toggleLainnya(this, 'sktm_keperluan_lainnya', 'sktm_keperluan_lainnya_input')">
                  <option value="">-- Pilih Keperluan --</option>
                  <option value="pendidikan">Pendidikan / Sekolah</option>
                  <option value="kesehatan">Kesehatan / Rumah Sakit</option>
                  <option value="aktivasi_kis">Aktivasi Kis</option>
                  <option value="lainnya">Lainnya</option>
                </select>
              </div>

              <div id="sktm_keperluan_lainnya" class="form-input-group form-col-full" style="display:none; margin-top: 10px;">
                <label class="input-label">Tuliskan Keperluan <span class="text-red">*</span></label>
                <input type="text" id="sktm_keperluan_lainnya_input" data-name="keperluan" class="input-control" placeholder="Tuliskan keperluan Anda secara spesifik">
              </div>

              <div class="form-input-group form-col-full">
                <label class="input-label">Nama yang Menggunakan Surat <span class="text-red">*</span></label>
                <input type="text" name="nama_yang_menggunakan_surat" class="input-control" placeholder="Contoh: KYRA RAYNA ADELIA" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
              </div>

              <div class="form-input-group form-col-full">
                <label class="input-label">Hubungan dengan Pemohon <span class="text-red">*</span></label>
                <select name="hubungan_dengan_pemohon" class="input-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="anak">Anak</option>
                  <option value="istri">Istri</option>
                  <option value="suami">Suami</option>
                  <option value="sendiri">Diri Sendiri</option>
                </select>
              </div>

              <div class="form-input-group form-col-full">
                <label class="input-label">Keterangan Tambahan <span class="text-red">*</span></label>
                <textarea name="keterangan_tambahan" class="input-control" placeholder="Tuliskan keterangan tambahan jika diperlukan..." required></textarea>
              </div>

            </div>
          </div>
        </div>

        <!-- SECTION 3: Dokumen Pendukung -->
        <div class="form-section">
          <div class="form-section-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
            Dokumen Pendukung
          </div>
          <div class="form-section-body">
            <div class="form-grid">
              
              <div class="form-input-group form-col-full">
                <label class="input-label">Surat Pengantar RT/RW <span class="text-red">*</span></label>
                <label class="file-drop-area">
                  <input type="file" name="dokumen_surat_pengantar_rt_rw" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_surat_pengantar_rt_rw') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF - maks. 2MB)'">
                  <div class="file-drop-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                  </div>
                  <div class="file-drop-text">@if($hasFile('dokumen_surat_pengantar_rt_rw')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF - maks. 2MB) @endif</div>
                </label>
              </div>

              <div class="form-input-group form-col-full">
                <label class="input-label">Fotokopi KTP Pemohon <span class="text-red">*</span></label>
                <label class="file-drop-area">
                  <input type="file" name="dokumen_fotokopi_ktp_pemohon" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_fotokopi_ktp_pemohon') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF - maks. 2MB)'">
                  <div class="file-drop-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                  </div>
                  <div class="file-drop-text">@if($hasFile('dokumen_fotokopi_ktp_pemohon')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF - maks. 2MB) @endif</div>
                </label>
              </div>

              <div class="form-input-group form-col-full">
                <label class="input-label">Fotokopi Keluarga (KK) <span class="text-red">*</span></label>
                <label class="file-drop-area">
                  <input type="file" name="dokumen_fotokopi_keluarga_kk" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_fotokopi_keluarga_kk') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF - maks. 2MB)'">
                  <div class="file-drop-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                  </div>
                  <div class="file-drop-text">@if($hasFile('dokumen_fotokopi_keluarga_kk')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF - maks. 2MB) @endif</div>
                </label>
              </div>

            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="form-actions">
          <button type="submit" class="btn-action btn-submit-form">Kirim</button>
        </div>

        </form>
      </div> <!-- End of form-sktm -->

<div id="form-sku" class="dynamic-form" style="display: none;">
        <!-- Informasi Alert SKU -->
        <div class="alert-info">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
          
          Pastikan data yang Anda isi sesuai dengan KTP dan data/dokumen usaha yang dimiliki.
        </div>
        <span class="text-required"><span class="text-red">*</span> kolom wajib di isi</span>

        <!-- Form SKU -->
        <form action="{{ route('user.ajukan-surat.submit') }}" method="POST" enctype="multipart/form-data" id="skuForm" onsubmit="event.preventDefault(); confirmSubmit(this.id);">
          @csrf
          @if(request('resubmit_id'))
          <input type="hidden" name="resubmit_id" value="{{ request('resubmit_id') }}">
          @endif
          <input type="hidden" name="jenis_surat" value="sku">
          
          <!-- SECTION 1: Data Pemilik Usaha -->
          <div class="form-section">
            <div class="form-section-header">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
              Data Pemilik Usaha
            </div>
            <div class="form-section-body">
            <div class="form-grid">
                
                <div class="form-input-group form-col-full">
                  <label class="input-label">Nama Lengkap <span class="text-red">*</span></label>
                  <input type="text" name="nama_lengkap" class="input-control" value="{{ $val('nama_lengkap', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->name) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">NIK <span class="text-red">*</span></label>
                  <input type="text" name="nik" class="input-control" value="{{ $val('nik', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->nik) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Jenis Kelamin <span class="text-red">*</span></label>
                  <select name="jenis_kelamin" class="input-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="l" {{ $val('jenis_kelamin', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->jenis_kelamin) == 'l' ? 'selected' : '' }}>Laki-laki</option>
                  <option value="p" {{ $val('jenis_kelamin', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->jenis_kelamin) == 'p' ? 'selected' : '' }}>Perempuan</option>
                </select>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Tempat Lahir <span class="text-red">*</span></label>
                  <input type="text" name="tempat_lahir" class="input-control" value="{{ $val('tempat_lahir', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->tempat_lahir) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Tanggal Lahir <span class="text-red">*</span></label>
                  <input type="date" name="tanggal_lahir" class="input-control" value="{{ $val('tanggal_lahir', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->tanggal_lahir ? \Carbon\Carbon::parse((Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->tanggal_lahir)->format('Y-m-d') : '') }}" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Kewarganegaraan <span class="text-red">*</span></label>
                  <input type="text" name="kewarganegaraan" class="input-control" value="{{ $val('kewarganegaraan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kewarganegaraan) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Agama <span class="text-red">*</span></label>
                  <select name="agama" class="input-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="islam" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'islam' ? 'selected' : '' }}>Islam</option>
                  <option value="kristen" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'kristen' ? 'selected' : '' }}>Kristen</option>
                  <option value="katolik" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'katolik' ? 'selected' : '' }}>Katolik</option>
                  <option value="hindu" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'hindu' ? 'selected' : '' }}>Hindu</option>
                  <option value="budha" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'budha' ? 'selected' : '' }}>Budha</option>
                  <option value="konghucu" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                </select>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Pekerjaan <span class="text-red">*</span></label>
                  <input type="text" name="pekerjaan" class="input-control" value="{{ $val('pekerjaan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->pekerjaan) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Status Pernikahan <span class="text-red">*</span></label>
                  <select name="status_pernikahan" class="input-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="belum" {{ $val('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'belum' ? 'selected' : '' }}>Belum Kawin</option>
                  <option value="kawin" {{ $val('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'kawin' ? 'selected' : '' }}>Kawin</option>
                  <option value="cerai_hidup" {{ $val('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                  <option value="cerai_mati" {{ $val('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                </select>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Alamat Lengkap <span class="text-red">*</span></label>
                  <textarea name="alamat_lengkap" class="input-control" rows="2" required>{{ $val('alamat_lengkap', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->alamat_lengkap) }}</textarea>
                </div>

                <div class="form-input-group">
                  <label class="input-label">RT <span class="text-red">*</span></label>
                  <input type="text" name="rt" class="input-control" value="{{ $val('rt', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->rt) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">RW <span class="text-red">*</span></label>
                  <input type="text" name="rw" class="input-control" value="{{ $val('rw', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->rw) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Kelurahan <span class="text-red">*</span></label>
                  <input type="text" name="kelurahan" class="input-control" value="{{ $val('kelurahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kelurahan) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Kecamatan <span class="text-red">*</span></label>
                  <input type="text" name="kecamatan" class="input-control" value="{{ $val('kecamatan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kecamatan) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Kota <span class="text-red">*</span></label>
                  <input type="text" name="kota" class="input-control" value="{{ $val('kota', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kota) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

              </div>
            </div>
          </div>

          <!-- SECTION 2: Data Usaha -->
          <div class="form-section">
            <div class="form-section-header">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M4 10h16M10 4v16"/></svg>
              Data Usaha
            </div>
            <div class="form-section-body">
              <div class="form-grid">
                
                <!-- Nama usaha dihapus sesuai sampel surat -->

                <div class="form-input-group">
                  <label class="input-label">Bentuk Usaha <span class="text-red">*</span></label>
                  <select name="bentuk_usaha" class="input-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="perorangan">Perorangan</option>
                    <option value="cv">CV</option>
                    <option value="pt">PT</option>
                  </select>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Bidang Usaha <span class="text-red">*</span></label>
                  <select name="bidang_usaha" class="input-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="perdagangan">Perdagangan</option>
                    <option value="jasa">Jasa</option>
                    <option value="industri">Industri</option>
                    <option value="pertanian">Pertanian</option>
                  </select>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Barang Usaha <span class="text-red">*</span></label>
                  <input type="text" name="barang_usaha" class="input-control" placeholder="Contoh: WARUNG MAKANAN DAN KENDARAAN RODA 4" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Keadaan Usaha Saat Ini <span class="text-red">*</span></label>
                  <select name="keadaan_usaha_saat_ini" class="input-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="cukup_lancar">Cukup Lancar</option>
                    <option value="lancar">Lancar</option>
                    <option value="kurang_lancar">Kurang Lancar</option>
                  </select>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Sejak Tahun <span class="text-red">*</span></label>
                  <input type="text" name="sejak_tahun" class="input-control" placeholder="Contoh: 2020" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Alamat Usaha <span class="text-red">*</span></label>
                  <textarea name="alamat_usaha" class="input-control" placeholder="Contoh: Jl. Mayor SL. Tobing No. 12" required></textarea>
                </div>

              </div>
            </div>
          </div>

          <!-- SECTION 3: Keperluan Pengajuan SKU -->
          <div class="form-section">
            <div class="form-section-header">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              Keperluan Pengajuan SKU
            </div>
            <div class="form-section-body">
              <div class="form-grid">
                
                <div class="form-input-group form-col-full">
                  <label class="input-label">Keperluan Surat <span class="text-red">*</span></label>
                  <select name="keperluan" id="sku_keperluan_select" class="input-control" required onchange="toggleLainnya(this, 'sku_keperluan_lainnya', 'sku_keperluan_lainnya_input')">
                    <option value="">-- Pilih Keperluan --</option>
                    <option value="bank">Pengajuan Pinjaman Bank</option>
                    <option value="pajak">Keperluan Pajak</option>
                    <option value="izin">Izin Usaha</option>
                    <option value="lainnya">Lainnya</option>
                  </select>
                </div>

                <div id="sku_keperluan_lainnya" class="form-input-group form-col-full" style="display:none; margin-top: 10px;">
                  <label class="input-label">Tuliskan Keperluan <span class="text-red">*</span></label>
                  <input type="text" id="sku_keperluan_lainnya_input" data-name="keperluan" class="input-control" placeholder="Tuliskan keperluan Anda secara spesifik">
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Keterangan Tambahan <span class="text-red">*</span></label>
                  <textarea name="keterangan_tambahan" class="input-control" placeholder="Tuliskan keterangan tambahan jika diperlukan..." required></textarea>
                </div>

              </div>
            </div>
          </div>

          <!-- SECTION 4: Dokumen Pendukung -->
          <div class="form-section">
            <div class="form-section-header">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
              Dokumen Pendukung
            </div>
            <div class="form-section-body">
              <div class="form-grid">
                
                <div class="form-input-group form-col-full">
                  <label class="input-label">Surat Pengantar RT/RW <span class="text-red">*</span></label>
                  <label class="file-drop-area">
                  <input type="file" name="dokumen_surat_pengantar_rt_rw" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_surat_pengantar_rt_rw') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB)'">
                    <div class="file-drop-icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    <div class="file-drop-text">@if($hasFile('dokumen_surat_pengantar_rt_rw')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB) @endif</div>
                </label>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Fotokopi KTP Pemohon <span class="text-red">*</span></label>
                  <label class="file-drop-area">
                  <input type="file" name="dokumen_fotokopi_ktp_pemohon" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_fotokopi_ktp_pemohon') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB)'">
                    <div class="file-drop-icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    <div class="file-drop-text">@if($hasFile('dokumen_fotokopi_ktp_pemohon')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB) @endif</div>
                </label>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Fotokopi Keluarga (KK) <span class="text-red">*</span></label>
                  <label class="file-drop-area">
                  <input type="file" name="dokumen_fotokopi_keluarga_kk" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_fotokopi_keluarga_kk') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB)'">
                    <div class="file-drop-icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    <div class="file-drop-text">@if($hasFile('dokumen_fotokopi_keluarga_kk')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB) @endif</div>
                </label>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Foto Tempat Usaha <span class="text-red">*</span></label>
                  <label class="file-drop-area">
                  <input type="file" name="dokumen_foto_tempat_usaha" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_foto_tempat_usaha') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB)'">
                    <div class="file-drop-icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    <div class="file-drop-text">@if($hasFile('dokumen_foto_tempat_usaha')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB) @endif</div>
                </label>
                </div>

              </div>
            </div>
          </div>

          <!-- Buttons -->
          <div class="form-actions">
            <button type="submit" class="btn-action btn-submit-form">Kirim</button>
          </div>

        </form>
      </div> <!-- End of form-sku -->

<div id="form-domisili" class="dynamic-form" style="display: none;">
        <!-- Informasi Alert SKD -->
        <div class="alert-info">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
          
          Surat Keterangan Domisili menerangkan bahwa pemohon adalah benar-benar penduduk/warga RT/RW dan berdomisili/bertempat tinggal di alamat tersebut.
        </div>
        <span class="text-required"><span class="text-red">*</span> kolom wajib di isi</span>

        <!-- Form SKD -->
        <form action="{{ route('user.ajukan-surat.submit') }}" method="POST" enctype="multipart/form-data" id="domisiliForm" onsubmit="event.preventDefault(); confirmSubmit(this.id);">
          @csrf
          @if(request('resubmit_id'))
          <input type="hidden" name="resubmit_id" value="{{ request('resubmit_id') }}">
          @endif
          <input type="hidden" name="jenis_surat" value="domisili">
          
          <!-- SECTION 1: Data Pemohon -->
          <div class="form-section">
            <div class="form-section-header">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
              Data Pemohon
            </div>
            <div class="form-section-body">
            <div class="form-grid">
                
                <div class="form-input-group form-col-full">
                  <label class="input-label">Nama Lengkap <span class="text-red">*</span></label>
                  <input type="text" name="nama_lengkap" class="input-control" value="{{ $val('nama_lengkap', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->name) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">NIK <span class="text-red">*</span></label>
                  <input type="text" name="nik" class="input-control" value="{{ $val('nik', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->nik) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Jenis Kelamin <span class="text-red">*</span></label>
                  <select name="jenis_kelamin" class="input-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="l" {{ $val('jenis_kelamin', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->jenis_kelamin) == 'l' ? 'selected' : '' }}>Laki-laki</option>
                  <option value="p" {{ $val('jenis_kelamin', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->jenis_kelamin) == 'p' ? 'selected' : '' }}>Perempuan</option>
                </select>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Tempat Lahir <span class="text-red">*</span></label>
                  <input type="text" name="tempat_lahir" class="input-control" value="{{ $val('tempat_lahir', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->tempat_lahir) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Tanggal Lahir <span class="text-red">*</span></label>
                  <input type="date" name="tanggal_lahir" class="input-control" value="{{ $val('tanggal_lahir', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->tanggal_lahir ? \Carbon\Carbon::parse((Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->tanggal_lahir)->format('Y-m-d') : '') }}" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Kewarganegaraan <span class="text-red">*</span></label>
                  <input type="text" name="kewarganegaraan" class="input-control" value="{{ $val('kewarganegaraan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kewarganegaraan) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Agama <span class="text-red">*</span></label>
                  <select name="agama" class="input-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="islam" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'islam' ? 'selected' : '' }}>Islam</option>
                  <option value="kristen" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'kristen' ? 'selected' : '' }}>Kristen</option>
                  <option value="katolik" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'katolik' ? 'selected' : '' }}>Katolik</option>
                  <option value="hindu" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'hindu' ? 'selected' : '' }}>Hindu</option>
                  <option value="budha" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'budha' ? 'selected' : '' }}>Budha</option>
                  <option value="konghucu" {{ $val('agama', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->agama) == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                </select>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Pekerjaan <span class="text-red">*</span></label>
                  <input type="text" name="pekerjaan" class="input-control" value="{{ $val('pekerjaan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->pekerjaan) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Status Pernikahan <span class="text-red">*</span></label>
                  <select name="status_pernikahan" class="input-control" required>
                  <option value="">-- Pilih --</option>
                  <option value="belum" {{ $val('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'belum' ? 'selected' : '' }}>Belum Kawin</option>
                  <option value="kawin" {{ $val('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'kawin' ? 'selected' : '' }}>Kawin</option>
                  <option value="cerai_hidup" {{ $val('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'cerai_hidup' ? 'selected' : '' }}>Cerai Hidup</option>
                  <option value="cerai_mati" {{ $val('status_pernikahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->status_pernikahan) == 'cerai_mati' ? 'selected' : '' }}>Cerai Mati</option>
                </select>
                </div>

              </div>
            </div>
          </div>

          <!-- SECTION 2: Alamat Sesuai KTP -->
          <div class="form-section">
            <div class="form-section-header">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
              Alamat Sesuai KTP
            </div>
            <div class="form-section-body">
              <div class="form-grid">
                
                <div class="form-input-group form-col-full">
                  <label class="input-label">Alamat Lengkap <span class="text-red">*</span></label>
                  <textarea name="alamat_lengkap" class="input-control" rows="2" required>{{ $val('alamat_lengkap', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->alamat_lengkap) }}</textarea>
                </div>

                <div class="form-input-group">
                  <label class="input-label">RT <span class="text-red">*</span></label>
                  <input type="text" name="rt" class="input-control" value="{{ $val('rt', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->rt) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">RW <span class="text-red">*</span></label>
                  <input type="text" name="rw" class="input-control" value="{{ $val('rw', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->rw) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Kelurahan <span class="text-red">*</span></label>
                  <input type="text" name="kelurahan" class="input-control" value="{{ $val('kelurahan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kelurahan) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Kecamatan <span class="text-red">*</span></label>
                  <input type="text" name="kecamatan" class="input-control" value="{{ $val('kecamatan', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kecamatan) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Kota <span class="text-red">*</span></label>
                  <input type="text" name="kota" class="input-control" value="{{ $val('kota', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->kota) }}" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

              </div>
            </div>
          </div>

          <!-- SECTION 3: Alamat Saat Ini (Domisili) -->
          <div class="form-section">
            <div class="form-section-header">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
              Alamat Saat Ini (Domisili)
            </div>
            <div class="form-section-body">
              <div class="form-grid">
                
                <div class="form-input-group form-col-full">
                  <label class="input-label">Alamat Lengkap <span class="text-red">*</span></label>
                  <textarea name="alamat_domisili" class="input-control" rows="2" placeholder="Contoh: Jl. Mayor SL. Tobing No. 12" required></textarea>
                </div>

                <div class="form-input-group">
                  <label class="input-label">RT <span class="text-red">*</span></label>
                  <input type="text" name="rt_domisili" class="input-control" placeholder="Contoh: 001" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">RW <span class="text-red">*</span></label>
                  <input type="text" name="rw_domisili" class="input-control" placeholder="Contoh: 005" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Kelurahan <span class="text-red">*</span></label>
                  <input type="text" name="kelurahan_domisili" class="input-control" placeholder="Contoh: Sambongpari" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Kecamatan <span class="text-red">*</span></label>
                  <input type="text" name="kecamatan_domisili" class="input-control" placeholder="Contoh: Mangkubumi" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

                <div class="form-input-group">
                  <label class="input-label">Kota <span class="text-red">*</span></label>
                  <input type="text" name="kota_domisili" class="input-control" placeholder="Contoh: Kota Tasikmalaya" oninput="this.value = this.value.replace(/[0-9]/g, '');" required>
                </div>

              </div>
            </div>
          </div>

          <!-- SECTION 4: Dokumen Pendukung -->
          <div class="form-section">
            <div class="form-section-header">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
              Dokumen Pendukung
            </div>
            <div class="form-section-body">
              <div class="form-grid">
                
                <div class="form-input-group form-col-full">
                  <label class="input-label">Surat Pengantar RT/RW <span class="text-red">*</span></label>
                  <label class="file-drop-area">
                  <input type="file" name="dokumen_surat_pengantar_rt_rw" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_surat_pengantar_rt_rw') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB)'">
                    <div class="file-drop-icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    <div class="file-drop-text">@if($hasFile('dokumen_surat_pengantar_rt_rw')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB) @endif</div>
                </label>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Fotokopi KTP Pemohon <span class="text-red">*</span></label>
                  <label class="file-drop-area">
                  <input type="file" name="dokumen_fotokopi_ktp_pemohon" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_fotokopi_ktp_pemohon') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB)'">
                    <div class="file-drop-icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    <div class="file-drop-text">@if($hasFile('dokumen_fotokopi_ktp_pemohon')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB) @endif</div>
                </label>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Fotokopi Keluarga (KK) <span class="text-red">*</span></label>
                  <label class="file-drop-area">
                  <input type="file" name="dokumen_fotokopi_keluarga_kk" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_fotokopi_keluarga_kk') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB)'">
                    <div class="file-drop-icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    <div class="file-drop-text">@if($hasFile('dokumen_fotokopi_keluarga_kk')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB) @endif</div>
                </label>
                </div>

                <div class="form-input-group form-col-full">
                  <label class="input-label">Surat Pernyataan Domisili <span class="text-red">*</span></label>
                  <label class="file-drop-area">
                  <input type="file" name="dokumen_surat_pernyataan_domisili___pemohon" class="file-input" accept=".jpg,.jpeg,.png,.pdf" {{ $hasFile('dokumen_surat_pernyataan_domisili___pemohon') ? '' : 'required' }} style="display:none;" onchange="this.parentElement.querySelector('.file-drop-text').textContent = this.files[0] ? this.files[0].name : 'Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB)'">
                    <div class="file-drop-icon">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    </div>
                    <div class="file-drop-text">@if($hasFile('dokumen_surat_pernyataan_domisili___pemohon')) <span style="color:var(--blue);font-weight:600;">Sudah ada file sebelumnya. Klik untuk mengganti (opsional).</span> @else Klik untuk unggah file (JPG, PNG, PDF — maks. 2MB) @endif</div>
                </label>
                </div>

              </div>
            </div>
          </div>

          <!-- Buttons -->
          <div class="form-actions">
            <button type="submit" class="btn-action btn-submit-form">Kirim</button>
          </div>

        </form>
      </div> <!-- End of form-domisili -->

    </main>
  </div>
</div>

<!-- ===== MOBILE BOTTOM NAV ===== -->
<nav class="mobile-bottom-nav">
  <a href="{{ route('user.dashboard') }}" class="bottom-nav-item">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
    <span>Beranda</span>
  </a>
  <a href="{{ route('user.ajukan-surat') }}" class="bottom-nav-item active">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9"  y1="15" x2="15" y2="15"/></svg>
    <span>Ajukan</span>
  </a>
  <a href="{{ route('user.riwayat') }}" class="bottom-nav-item">
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

  // Fungsi dinamis ganti form
  function toggleForm(val) {
    const forms = document.querySelectorAll('.dynamic-form');
    forms.forEach(f => f.style.display = 'none');
    
    if(val === 'sktm') {
      document.getElementById('form-sktm').style.display = 'block';
    } else if(val === 'sku') {
      document.getElementById('form-sku').style.display = 'block';
    } else if(val === 'domisili') {
      document.getElementById('form-domisili').style.display = 'block';
    }
  }

  // Pre-select based on URL param
  document.addEventListener('DOMContentLoaded', function() {
    const jenisParam = new URLSearchParams(window.location.search).get('jenis');
    if(jenisParam) {
      document.getElementById('jenis_surat').value = jenisParam;
      toggleForm(jenisParam);
    }
  });
</script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>.swal-custom { font-family: 'Inter', sans-serif; border-radius: 12px; } .swal2-confirm { padding: 10px 24px !important; font-weight: 600 !important; border-radius: 8px !important; } .swal2-cancel { padding: 10px 24px !important; font-weight: 600 !important; border-radius: 8px !important; color: #1e293b !important; background: #e2e8f0 !important; }
.filter-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }


</style>


<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

<!-- Auto Compress Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Jika file lebih dari 2MB
            if (file.size > 2 * 1024 * 1024) {
                if (file.type.startsWith('image/')) {
                    Swal.fire({
                        title: 'Mengompres Gambar...',
                        text: 'Ukuran file lebih dari 2MB. Sistem sedang mengecilkan ukuran gambar secara otomatis.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    try {
                        const compressedFile = await compressImage(file, 2); // target 2MB
                        
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        input.files = dataTransfer.files;

                        Swal.fire({
                            icon: 'success',
                            title: 'Selesai',
                            text: 'Gambar otomatis dikompresi menjadi ' + (compressedFile.size / 1024 / 1024).toFixed(2) + ' MB',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        const labelText = input.parentElement.querySelector('.file-drop-text');
                        if (labelText) {
                            labelText.innerHTML = `<span style="color:#15803d; font-weight:600;">File siap: ${compressedFile.name}</span>`;
                        }
                    } catch (err) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal mengompres gambar. Silakan kompres manual di bawah 2MB.' });
                        input.value = '';
                    }
                } else {
                    Swal.fire({ icon: 'warning', title: 'File Terlalu Besar', text: 'Maksimal ukuran file PDF atau dokumen lainnya adalah 2MB. Harap perkecil file Anda.' });
                    input.value = '';
                    const labelText = input.parentElement.querySelector('.file-drop-text');
                    if (labelText) labelText.textContent = 'Klik untuk unggah file (JPG, PNG, PDF - maks. 2MB)';
                }
            } else {
                const labelText = input.parentElement.querySelector('.file-drop-text');
                if (labelText) {
                    labelText.innerHTML = `<span style="color:#1d4ed8; font-weight:600;">File siap: ${file.name}</span>`;
                }
            }
        });
    });

    function compressImage(file, targetMB) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = event => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    
                    const MAX_DIM = 1800;
                    if (width > height && width > MAX_DIM) {
                        height *= MAX_DIM / width;
                        width = MAX_DIM;
                    } else if (height > MAX_DIM) {
                        width *= MAX_DIM / height;
                        height = MAX_DIM;
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    let quality = 0.8;
                    const compress = () => {
                        canvas.toBlob(blob => {
                            if (blob.size > targetMB * 1024 * 1024 && quality > 0.1) {
                                quality -= 0.15;
                                compress();
                            } else {
                                const newFile = new File([blob], file.name, { type: file.type, lastModified: Date.now() });
                                resolve(newFile);
                            }
                        }, file.type, quality);
                    };
                    compress();
                };
                img.onerror = error => reject(error);
            };
            reader.onerror = error => reject(error);
        });
    }
});

  function confirmSubmit(formId) {
    Swal.fire({
      showConfirmButton: false,
      customClass: { 
        popup: 'custom-conf-popup', 
        htmlContainer: 'custom-conf-html-container'
      },
      html: `
        <div class="custom-conf-icon-wrapper">
          <div class="custom-conf-icon-inner">?</div>
        </div>
        <h2 class="custom-conf-title">Konfirmasi Pengiriman</h2>
        <p class="custom-conf-text">Apakah Anda yakin data yang diisi sudah benar?<br>Data yang telah dikirim tidak dapat diubah kembali.</p>
        <div class="custom-conf-actions">
          <button type="button" class="custom-conf-btn-cancel" onclick="Swal.close()">Batal</button>
          <button type="button" class="custom-conf-btn-confirm" onclick="submitFormAjax('${formId}')">Ya, Kirim</button>
        </div>
      `
    });
  }

  function submitFormAjax(formId) {
    const form = document.getElementById(formId);
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    Swal.fire({
        title: 'Mengirim...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async response => {
        if (response.ok) {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                const data = await response.json();
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
            } else if (response.redirected) {
                window.location.href = response.url;
                return;
            }
            window.location.href = '{{ route("user.riwayat") }}';
        } else if (response.status === 422) {
            const data = await response.json();
            let errorMessage = '';
            for (let field in data.errors) {
                errorMessage += data.errors[field].join('\\n') + '\\n';
            }
            Swal.fire('Validasi Gagal', errorMessage, 'error');
        } else {
            Swal.fire('Error', 'Terjadi kesalahan pada server. Coba lagi.', 'error');
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire('Error', 'Gagal mengirim data. Periksa koneksi Anda dan coba lagi.', 'error');
    });
  }

  function toggleLainnya(selectElement, inputContainerId, inputId) {
    if(selectElement.value === 'lainnya') {
        document.getElementById(inputContainerId).style.display = 'flex';
        document.getElementById(inputId).required = true;
        document.getElementById(inputId).name = selectElement.name;
        selectElement.removeAttribute('name');
    } else {
        document.getElementById(inputContainerId).style.display = 'none';
        document.getElementById(inputId).required = false;
        selectElement.name = document.getElementById(inputId).getAttribute('data-name');
        document.getElementById(inputId).removeAttribute('name');
    }
  }
</script>
<style>
  .custom-conf-popup { border-radius: 16px !important; padding: 32px 24px 28px !important; width: 420px !important; }
  .custom-conf-html-container { margin: 0 !important; padding: 0 !important; overflow: visible !important; }
  .custom-conf-icon-wrapper { width: 72px; height: 72px; background-color: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
  .custom-conf-icon-inner { width: 40px; height: 40px; background-color: #1a4068; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; font-weight: 700; font-family: 'Inter', sans-serif; }
  .custom-conf-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 10px; font-family: 'Inter', sans-serif; text-align: center; }
  .custom-conf-text { font-size: 0.92rem; color: #64748b; line-height: 1.5; margin: 0 0 28px; font-family: 'Inter', sans-serif; text-align: center; }
  .custom-conf-actions { display: flex; gap: 12px; margin: 0; width: 100%; }
  .custom-conf-btn-cancel { flex: 1; background-color: #cbd5e1 !important; color: #1e293b !important; border: none !important; border-radius: 9999px !important; padding: 12px 0 !important; font-weight: 600 !important; font-size: 0.9rem !important; cursor: pointer; text-align: center; font-family: 'Inter', sans-serif; transition: background-color 0.2s; outline: none !important; box-shadow: none !important; }
  .custom-conf-btn-cancel:hover { background-color: #94a3b8 !important; }
  .custom-conf-btn-confirm { flex: 1; background-color: #0284c7 !important; color: white !important; border: none !important; border-radius: 9999px !important; padding: 12px 0 !important; font-weight: 600 !important; font-size: 0.9rem !important; cursor: pointer; text-align: center; font-family: 'Inter', sans-serif; transition: background-color 0.2s; outline: none !important; box-shadow: none !important; }
  .custom-conf-btn-confirm:hover { background-color: #0369a1 !important; }

.filter-select:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 2px var(--blue-light); }



    
    
</style>
<script src="{{ asset('js/notif.js') }}"></script>
<script src="{{ asset('js/logout.js') }}"></script>

@if($resubmitData)
<script>
(function() {
    try {
        // 1. Pilih Jenis Surat
        const selectJenis = document.getElementById('jenis_surat');
        const jenisSurat = "{{ $resubmitData->jenis_surat }}";
        if(selectJenis) {
            selectJenis.value = jenisSurat;
            if (typeof toggleForm === 'function') {
                toggleForm(jenisSurat);
            } else {
                selectJenis.dispatchEvent(new Event('change'));
            }
        }

        // 2. Isi data form otomatis
        let rawData = @json($resubmitData->data_isian);
        let dataIsian = typeof rawData === 'string' ? JSON.parse(rawData) : rawData;
        
        if(dataIsian) {
            const activeForm = document.getElementById('form-' + jenisSurat);
            if (activeForm) {
                for (const key in dataIsian) {
                    const el = activeForm.querySelector(`[name="${key}"]`);
                    if (el) {
                        el.value = dataIsian[key];
                    }
                }
            }
        }
    } catch (e) {
        console.error("Auto-populate error:", e);
    }
})();
</script>
@endif

</body>
</html>

