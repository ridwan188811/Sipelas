<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Detail Pengajuan – SIPELAS</title>
  <meta name="description" content="Detail Pengajuan SIPELAS – Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    /* ===== RESET & BASE ===== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; }
    body { font-family: 'Inter', sans-serif; background: #f0f4f8; color: #1e293b; min-height: 100vh; display: flex; flex-direction: column; }

    /* ===== VARIABLES ===== */
    :root {
      --navy:        #1a3558;
      --navy-dark:   #152c48;
      --blue:        #1d4ed8;
      --green:       #16a34a;
      --green-dark:  #15803d;
      --red:         #dc2626;
      --red-dark:    #b91c1c;
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
    .header { position: sticky; top: 0; z-index: 50; background: var(--navy); height: var(--header-h); display: flex; align-items: center; padding: 0 20px; gap: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.2); flex-shrink: 0; }
    .page-body { display: flex; flex: 1; min-height: 0; }

    /* =============================================
       SIDEBAR
    ============================================= */
    .sidebar { width: var(--sidebar-w); background: var(--white); border-right: 1px solid var(--gray-200); display: flex; flex-direction: column; flex-shrink: 0; position: sticky; top: var(--header-h); height: calc(100vh - var(--header-h)); overflow-y: auto; transition: width .28s, transform .28s; z-index: 30; }
    .sidebar.collapsed { width: 0; overflow: hidden; border-right: none; }
    .sidebar-nav { flex: 1; padding: 20px 12px; display: flex; flex-direction: column; gap: 4px; }
    .nav-item { display: flex; align-items: center; gap: 11px; padding: 11px 16px; border-radius: 9px; color: var(--gray-600); text-decoration: none; font-size: .9rem; font-weight: 500; white-space: nowrap; transition: background .18s, color .18s; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; color: var(--navy); transition: color .18s; }
    .nav-item:hover { background: var(--gray-100); color: var(--gray-800); }
    .nav-item:hover svg { color: var(--navy); }
    .nav-item.active { background: var(--navy); color: var(--white); font-weight: 600; }
    .nav-item.active svg { color: var(--white); }

    /* =============================================
       HEADER PARTS
    ============================================= */
    .hamburger-btn { background: none; border: none; cursor: pointer; padding: 7px; display: flex; align-items: center; justify-content: center; border-radius: 7px; color: white; transition: background .18s; flex-shrink: 0; }
    .hamburger-btn:hover { background: rgba(255,255,255,.12); }
    .hamburger-btn svg { width: 22px; height: 22px; }
    .header-brand { display: flex; align-items: center; gap: 10px; flex: 1; }
    .header-brand-icon { width: 34px; height: 34px; background: transparent; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    .header-brand-text { color: white; line-height: 1.2; }
    .header-brand-text .app-name { font-size: 1.05rem; font-weight: 700; letter-spacing: .02em; }
    .header-brand-text .app-sub  { font-size: .62rem; opacity: .65; font-weight: 400; }
    .header-actions { display: flex; align-items: center; gap: 8px; }
    .notif-btn { position: relative; background: none; border: none; cursor: pointer; padding: 7px; color: rgba(255,255,255,.9); border-radius: 7px; display: flex; }
    .notif-btn:hover { background: rgba(255,255,255,.12); }
    .notif-btn svg { width: 20px; height: 20px; }
    .notif-badge { position: absolute; top: 5px; right: 5px; width: 7px; height: 7px; background: #ef4444; border-radius: 50%; border: 2px solid var(--navy); }
    .user-avatar { width: 34px; height: 34px; background: var(--gray-400); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: .88rem; cursor: pointer; flex-shrink: 0; text-decoration: none; }
    .user-avatar:hover { opacity: .85; }

    /* ===== SIDEBAR LOGOUT ===== */
    .sidebar-footer { padding: 12px; border-top: 1px solid var(--gray-200); margin-top: auto; }
    .logout-btn { display: flex; align-items: center; gap: 11px; width: 100%; padding: 11px 16px; border-radius: 9px; border: none; background: none; color: var(--gray-600); font-size: .9rem; font-weight: 500; cursor: pointer; text-decoration: none; }

    .logout-btn svg { width: 18px; height: 18px; flex-shrink: 0; color: #ef4444; }
    .logout-btn:hover { background: #fef2f2; color: #b91c1c; }

    /* =============================================
       CONTENT AREA
    ============================================= */
    .content-area { flex: 1; display: flex; flex-direction: column; min-width: 0; background: #f0f4f8; }
    .content { flex: 1; padding: 28px 28px; max-width: 1100px; margin: 0 auto; width: 100%; padding-bottom: 100px; }

    /* ===== PAGE HEADER ===== */
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .page-title h2 { font-size: 1.25rem; font-weight: 700; color: var(--gray-800); }
    .btn-kembali { background: #8e8e8e; color: white; border: none; padding: 8px 20px; border-radius: 8px; font-weight: 600; font-size: .85rem; cursor: pointer; text-decoration: none; transition: opacity .15s; }
    .btn-kembali:hover { opacity: .9; }

    /* ===== ALERT BANNERS (Dynamic based on status) ===== */
    .alert-danger-card { background: #fef2f2; border: 1px solid #fca5a5; border-radius: var(--radius); padding: 24px; display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px; }
    .alert-danger-top { display: flex; align-items: center; gap: 16px; }
    .alert-icon-danger { width: 44px; height: 44px; background: #ef4444; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; }
    .alert-text-danger { min-width: 0; }
    .alert-text-danger h3 { font-size: 1rem; font-weight: 700; color: #b91c1c; margin-bottom: 4px; }
    .alert-text-danger p { font-size: .85rem; color: var(--gray-500); }
    .alert-danger-reason { background: white; border: 1px solid #fca5a5; border-radius: 8px; padding: 16px 20px; margin-left: 60px; }
    .alert-danger-reason h4 { font-size: .75rem; font-weight: 800; color: #ef4444; text-transform: uppercase; margin-bottom: 6px; }
    .alert-danger-reason p { font-size: .85rem; color: #64748b; }

    .alert-warning-card { background: #fffdf0; border: 1px solid #fde047; border-radius: var(--radius); padding: 24px; display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px; }
    .alert-warning-top { display: flex; align-items: center; gap: 16px; }
    .alert-icon-warning { width: 44px; height: 44px; background: #eab308; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; }
    .alert-text-warning { min-width: 0; }
    .alert-text-warning h3 { font-size: 1rem; font-weight: 700; color: #ca8a04; margin-bottom: 4px; }
    .alert-text-warning p { font-size: .85rem; color: var(--gray-500); }

    .alert-success-card { background: #f0fdf4; border: 1px solid #86efac; border-radius: var(--radius); padding: 24px; display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px; }
    .alert-success-top { display: flex; align-items: center; gap: 16px; }
    .alert-icon-success { width: 44px; height: 44px; background: #22c55e; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white; }

    .alert-text-success { min-width: 0; }
    .alert-text-success h3 { font-size: 1rem; font-weight: 700; color: #166534; margin-bottom: 4px; }
    .alert-text-success p { font-size: .85rem; color: var(--gray-500); }

    /* ===== MAIN LAYOUT ===== */
    .grid-2col { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start; min-width: 0; }
    .left-col { display: flex; flex-direction: column; gap: 24px; min-width: 0; }
    .right-col { display: flex; flex-direction: column; gap: 24px; min-width: 0; }

    /* ===== SECTION CARDS ===== */
    .section-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow-sm); min-width: 0; }
    .section-card-title { font-size: 1rem; font-weight: 700; color: var(--navy); margin-bottom: 22px; display: flex; align-items: center; gap: 10px; min-width: 0; }
    .section-card-title::before { content: ''; display: block; width: 4px; height: 18px; background: var(--navy); border-radius: 4px; flex-shrink: 0; }

    /* ===== KEY-VALUE LIST ===== */
    .kv-list { display: flex; flex-direction: column; gap: 20px; min-width: 0; }
    .kv-item { display: grid; grid-template-columns: 200px 1fr; gap: 16px; font-size: .9rem; min-width: 0; }
    .kv-key { color: var(--gray-600); font-weight: 600; min-width: 0; }
    .kv-value { color: var(--gray-800); font-weight: 500; line-height: 1.4; word-break: break-word; min-width: 0; }

    /* Badges */
    .badge-yellow { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: .75rem; font-weight: 700; background: #fef08a; color: #854d0e; }
    .badge-green { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: .75rem; font-weight: 700; background: #d1fae5; color: #065f46; }
    .badge-red { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: .75rem; font-weight: 700; background: #fecaca; color: #991b1b; }


    /* Document List Flex Column */
    .doc-list { display: flex; flex-direction: column; gap: 16px; }
    .doc-item { display: flex; align-items: center; gap: 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; transition: border-color .2s, background .2s; cursor: pointer; }
    .doc-item:hover { border-color: #cbd5e1; background: white; }
    .doc-icon { width: 44px; height: 44px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--gray-400); flex-shrink: 0; }
    .doc-icon svg { width: 20px; height: 20px; }
    .doc-info { min-width: 0; flex: 1; }
    .doc-info h4 { font-size: .9rem; font-weight: 700; color: var(--gray-800); margin-bottom: 4px; text-transform: capitalize; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .doc-info p { font-size: .75rem; color: var(--gray-500); display: flex; align-items: center; white-space: nowrap; overflow: hidden; }
    .preview-dokumen { color: var(--gray-500); text-decoration: none; display: inline-block; max-width: calc(100% - 70px); overflow: hidden; text-overflow: ellipsis; }

    /* VERTICAL STEPPER */
    .stepper-v { display: flex; flex-direction: column; padding-top: 8px; min-width: 0; }
    .step-v-item { display: flex; gap: 20px; position: relative; padding-bottom: 32px; min-width: 0; }
    .step-v-item:last-child { padding-bottom: 0; }
    .step-v-line { position: absolute; left: 15px; top: 32px; bottom: 0; width: 2px; background: #e2e8f0; z-index: 1; }
    .step-v-item:last-child .step-v-line { display: none; }
    .step-v-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2; position: relative; background: white; border: 2px solid #cbd5e1; color: transparent; flex-shrink: 0; }
    .step-v-icon.completed { background: #ecfdf5; border-color: #22c55e; color: #22c55e; }
    .step-v-icon.active { background: #fef9c3; border-color: #fde047; color: #eab308; }
    .step-v-icon.rejected { background: #fef2f2; border-color: #ef4444; color: #ef4444; }
    .step-v-text { min-width: 0; }
    .step-v-text h4 { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 4px; word-wrap: break-word; }
    .step-v-text.active h4 { color: #ca8a04; }
    .step-v-text.rejected h4 { color: #b91c1c; }
    .step-v-text p { font-size: 0.75rem; color: #64748b; }

    /* ===== INFO BOX BLUE ===== */
    .info-box-blue { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 16px; color: #0284c7; font-size: .85rem; line-height: 1.5; }

    /* Sticky Action Bar - Fixed to bottom of viewport */
    .action-bar-card { background: white; border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 32px; box-shadow: var(--shadow-sm); display: flex; justify-content: space-between; align-items: center; gap: 32px; flex-wrap: wrap; margin-top: 24px; }
    .action-bar-text { flex: 1; min-width: 250px; }
    .sticky-action-text { flex: 1; font-size: 0.85rem; color: var(--gray-500); }
    
    @media (max-width: 680px) {
      .sticky-action-bar { padding: 16px; flex-direction: column; align-items: stretch; gap: 12px; }
      .sticky-action-text { text-align: center; }
    }

    .mobile-bottom-nav { display: none; }

    .btn-decision { padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; font-size: .9rem; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: transform .15s, opacity .15s; }
    .btn-decision:hover { opacity: .9; transform: translateY(-1px); }
    .btn-decision:active { transform: translateY(0); }
    .btn-approve { background: var(--green); color: white; border: none; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2); }
    .btn-approve:hover { background: var(--green-dark); border: none; }
    .btn-reject { background: #b45309; color: white; border: none; box-shadow: 0 4px 12px rgba(180, 83, 9, 0.2); }
    .btn-reject:hover { background: #92400e; border: none; }
    .btn-publish { background: var(--blue); color: white; box-shadow: 0 4px 12px rgba(29, 78, 216, 0.2); border: none; }

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
    .modal-form-group { margin-bottom: 24px; }
    .modal-label { display: block; font-size: .85rem; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
    .modal-textarea { width: 100%; height: 120px; padding: 12px; border: 1px solid #fca5a5; border-radius: 8px; resize: none; font-family: inherit; font-size: .9rem; color: #b91c1c; outline: none; background: #fef2f2; }
    .modal-textarea::placeholder { color: #ef4444; opacity: 0.4; }
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
    .modal-form-group { margin-bottom: 24px; }
    .modal-label { display: block; font-size: .85rem; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
    .modal-textarea { width: 100%; height: 120px; padding: 12px; border: 1px solid #fca5a5; border-radius: 8px; resize: none; font-family: inherit; font-size: .9rem; color: #b91c1c; outline: none; background: #fef2f2; }
    .modal-textarea::placeholder { color: #ef4444; opacity: 0.4; }
    .modal-textarea:focus { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.1); }
    .modal-actions { display: flex; justify-content: flex-end; gap: 12px; }
    .btn-modal-cancel { background: var(--gray-500); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: .9rem; cursor: pointer; transition: opacity .15s; }
    .btn-modal-cancel:hover { opacity: .9; }
    .btn-modal-confirm { background: var(--red-dark); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: .9rem; cursor: pointer; transition: opacity .15s; }
    .btn-modal-confirm:hover { opacity: .9; }

    /* Modal Overlay & Content */
    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); display: flex; align-items: center; justify-content: center; z-index: 9999; opacity: 0; pointer-events: none; transition: opacity .2s ease; padding: 20px; backdrop-filter: blur(4px); }
    .modal-overlay.active { opacity: 1; pointer-events: auto; }
    .modal-content { background: white; border-radius: 16px; padding: 32px 28px; width: 100%; max-width: 460px; transform: translateY(20px); transition: transform .2s ease; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .modal-overlay.active .modal-content { transform: translateY(0); }
    .modal-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 10px; font-family: 'Inter', sans-serif; }
    .modal-desc { font-size: 0.92rem; color: #64748b; line-height: 1.5; margin: 0 0 24px; font-family: 'Inter', sans-serif; }

    /* =============================================
       RESPONSIVE
    ============================================= */
    @media (max-width: 900px) { 
      .grid-2col { display: flex; flex-direction: column; gap: 24px; align-items: stretch; }
      .right-col { display: flex; flex-direction: column; gap: 24px; order: -1; }
      .left-col { display: flex; flex-direction: column; gap: 24px; order: 2; }
      
      /* UI/UX Reordering for Mobile (within their respective columns) */
      .pdf-card { order: 1; }
      .card-aksi { order: 2; }
      .card-informasi-waiting { order: 3; }
      .card-langkah { order: 4; }
      .card-lacak { order: 5; }
      
      .card-info { order: 6; }
      .card-diri { order: 7; }
      .card-khusus { order: 8; }
      .card-dokumen { order: 9; }
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

      .content { padding: 20px 16px; }
      .kv-item { grid-template-columns: 120px 1fr; gap: 8px; }
      .kv-key { font-size: .85rem; }
      .alert-danger-reason { margin-left: 0; }
      
      .pdf-header { flex-direction: row; justify-content: space-between; align-items: center; }
      .btn-unduh-sm { width: auto; padding: 6px 14px; font-size: 0.8rem; }
    }
    @media (max-width: 480px) {
      .alert-success-top, .alert-danger-top, .alert-warning-top { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

@php
  use Carbon\Carbon;
  Carbon::setLocale('id');
  setlocale(LC_TIME, 'id_ID.UTF-8', 'Indonesian');

  $displayName = (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->name ?? explode('@', (Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('warga')->user())->email)[0];
@endphp

<!-- ===== HEADER ===== -->
<header class="header">
  <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle Sidebar" aria-expanded="true" onclick="toggleSidebar()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-brand">
    <div class="header-brand-icon"><img src="{{ asset('images/logo_tasikmalaya.png') }}" alt="Logo Tasikmalaya" style="width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));"></div>
    <div class="header-brand-text">
      <div class="app-name">SIPELAS</div>
      <div class="app-sub">Sistem Informasi Pelayanan Masyarakat</div>
    </div>
  </div>
  <div class="header-actions">
    <!-- Notif Bell -->
    <div style="position: relative;">
      <button class="notif-btn" id="notifBtn" onclick="document.getElementById('notifDropdown').style.display = document.getElementById('notifDropdown').style.display === 'block' ? 'none' : 'block'">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        @if($globalNotifCount > 0)
        <span class="notif-badge"></span>
        @endif
      </button>
      <div id="notifDropdown" style="display: none; position: absolute; right: 0; top: 48px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; width: 320px; box-shadow: 0 8px 24px rgba(0,0,0,.12); z-index: 200;">
        <div style="padding: 14px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
          <span style="font-weight: 700; font-size: 0.9rem; color: #1e293b;">Notifikasi</span>
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
  
  <!-- ===== SIDEBAR (Hidden by default for Admin) ===== -->
  <aside class="sidebar collapsed" id="sidebar" aria-label="Navigasi Utama">
    <nav class="sidebar-nav">
      <a href="{{ route('admin.dashboard') }}" class="nav-item" id="nav-dashboard"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg> Dashboard</a>
      <a href="{{ route('admin.daftar-pengajuan') }}" class="nav-item active" id="nav-kelola"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><polyline points="9 14 11 16 15 11"/></svg> Verifikasi Surat</a>
      <a href="{{ route('admin.riwayat-pengajuan') }}" class="nav-item" id="nav-riwayat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 1 .5 4M3 16V11H8"/></svg> Riwayat Pengajuan</a>
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
          <h2>Detail Pengajuan Surat</h2>
        </div>
        <a href="{{ $pengajuan->status == 'menunggu' ? route('admin.daftar-pengajuan') : route('admin.riwayat-pengajuan') }}" class="btn-kembali">Kembali</a>
      </div>

      <!-- Alert Banner Dynamic -->
      @if($pengajuan->status == 'menunggu')
      <div class="alert-warning-card">
        <div class="alert-warning-top">
          <div class="alert-icon-warning">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 22h14"/><path d="M5 2h14"/><path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"/><path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"/></svg>
          </div>
          <div class="alert-text-warning">
            <h3>Sedang Diproses</h3>
            <p>Periksa data dan dokumen pemohon sebelum mengambil keputusan</p>
          </div>
        </div>
      </div>
      @elseif($pengajuan->status == 'disetujui')
      <div class="alert-success-card">
        <div class="alert-success-top">
          <div class="alert-icon-success">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
          </div>
          <div class="alert-text-success">
            <h3>Pengajuan Disetujui</h3>
            <p>Disetujui oleh {{ $displayName }} — {{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d F Y, H:i') }} WIB</p>
          </div>
        </div>
      </div>
      @elseif($pengajuan->status == 'ditolak')
      <div class="alert-danger-card">
        <div class="alert-danger-top">
          <div class="alert-icon-danger">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </div>
          <div class="alert-text-danger">
            <h3>Pengajuan Ditolak</h3>
            <p>Ditolak oleh {{ $displayName }} — {{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d F Y, H:i') }} WIB</p>
          </div>
        </div>
        <div class="alert-danger-reason">
          <h4>CATATAN PENOLAKAN:</h4>
          <p style="word-break: break-word;">{{ $pengajuan->catatan_admin ?? 'Tidak ada catatan penolakan spesifik.' }}</p>
        </div>
      </div>
      @endif

      <div class="grid-2col">
        <div class="left-col">



        <!-- Card 1: Informasi Pengajuan -->
        <div class="section-card card-info">
          <h3 class="section-card-title">Informasi Pengajuan</h3>
          <div class="kv-list">
            <div class="kv-item">
              <div class="kv-key">Jenis Surat</div>
              <div class="kv-value">{{ ['sku'=>'Surat Keterangan Usaha','sktm'=>'Surat Keterangan Tidak Mampu','sktm-sekolah'=>'Surat Keterangan Tidak Mampu (Sekolah)','domisili'=>'Surat Keterangan Domisili','belum-menikah'=>'Surat Keterangan Belum Menikah','kelahiran'=>'Surat Keterangan Kelahiran','kematian'=>'Surat Keterangan Kematian','pengantar-nikah'=>'Surat Pengantar Nikah','pindah'=>'Surat Keterangan Pindah'][$pengajuan->jenis_surat] ?? ucwords(str_replace('-', ' ', $pengajuan->jenis_surat)) }}</div>
            </div>
            <div class="kv-item">
              <div class="kv-key">Tanggal Pengajuan</div>
              <div class="kv-value">{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y, H:i') }} WIB</div>
            </div>
            @if($pengajuan->status == 'disetujui')
            <div class="kv-item">
              <div class="kv-key">Tanggal Disetujui</div>
              <div class="kv-value">{{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y, H:i') }} WIB</div>
            </div>
            @elseif($pengajuan->status == 'ditolak')
            <div class="kv-item">
              <div class="kv-key">Tanggal Ditolak</div>
              <div class="kv-value">{{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y, H:i') }} WIB</div>
            </div>
            @endif
            @if(strtolower($pengajuan->status) != 'menunggu')
            <div class="kv-item">
              <div class="kv-key">Diproses Oleh</div>
              <div class="kv-value">{{ $displayName }}</div>
            </div>
            @endif
            <div class="kv-item">
              <div class="kv-key">Status</div>
              <div class="kv-value">
                @if($pengajuan->status == 'menunggu')
                <span class="badge-yellow">Menunggu</span>
                @elseif($pengajuan->status == 'disetujui')
                <span class="badge-green">Disetujui</span>
                @else
                <span class="badge-red">Ditolak</span>
                @endif
              </div>
            </div>
            @if(strtolower($pengajuan->status) == 'disetujui')
            <div class="kv-item">
              <div class="kv-key">Nomor Surat</div>
              <div class="kv-value">{{ $pengajuan->nomor_surat ?? '-' }}</div>
            </div>
            @endif
          </div>
        </div>

        <!-- Card 2: Data Diri & Isian -->
        @php
          $dataDiriKeys = ['nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'kewarganegaraan', 'agama', 'pekerjaan', 'status_pernikahan', 'rt', 'rw', 'alamat_lengkap', 'kelurahan', 'kecamatan', 'kota', 'lama_berdomisili', 'status_pemilikan___alasan', 'alamat_domisili', 'rt_domisili', 'rw_domisili', 'kelurahan_domisili', 'kecamatan_domisili', 'kota_domisili'];
          $ignoredKeys = ['id', 'warga_id', 'pengajuan_surat_id', 'created_at', 'updated_at', 'name', 'email', 'email_verified_at', 'password', 'remember_token', 'no_hp'];
          $dataDiri = [];
          $dataUsaha = [];
          
          if ($pengajuan->data_isian) {
              foreach($pengajuan->data_isian as $key => $value) {
                  if (str_starts_with($key, 'dokumen_')) continue;
                  if (in_array($key, $ignoredKeys)) continue;
                  if (in_array($key, $dataDiriKeys)) {
                      $dataDiri[$key] = $value;
                  } else {
                      if ($value !== '' && $value !== null) {
                          $dataUsaha[$key] = $value;
                      }
                  }
              }
          }
          
          $jkMap = ['l' => 'Laki-laki', 'p' => 'Perempuan'];
          $kawinMap = ['belum' => 'Belum Kawin', 'kawin' => 'Kawin', 'cerai_hidup' => 'Cerai Hidup', 'cerai_mati' => 'Cerai Mati'];
        @endphp

        @if(count($dataDiri) > 0)
        <div class="section-card card-diri">
          <h3 class="section-card-title">Data Diri Pemohon</h3>
          <div class="kv-list">
            @php
              $orderedKeys = ['nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'kewarganegaraan', 'agama', 'pekerjaan', 'status_pernikahan', 'rt', 'alamat_lengkap', 'alamat_domisili', 'lama_berdomisili', 'status_pemilikan___alasan'];
              $remainingKeys = array_diff(array_keys($dataDiri), array_merge($orderedKeys, ['tanggal_lahir', 'rw', 'kelurahan', 'kecamatan', 'kota', 'rt_domisili', 'rw_domisili', 'kelurahan_domisili', 'kecamatan_domisili', 'kota_domisili']));
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
                   <div class="kv-key">{{ $pengajuan->jenis_surat == 'domisili' ? 'Alamat Sesuai KTP' : 'Alamat' }}</div>
                   <div class="kv-value">
                      {{ strtoupper($value) }}
                      @if(isset($dataDiri['rt']) && isset($dataDiri['rw'])) RT {{ str_pad($dataDiri['rt'], 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($dataDiri['rw'], 3, '0', STR_PAD_LEFT) }} @endif
                      @if(isset($dataDiri['kelurahan'])) KEL. {{ strtoupper($dataDiri['kelurahan']) }}, @endif
                      @if(isset($dataDiri['kecamatan'])) KEC. {{ strtoupper($dataDiri['kecamatan']) }} @endif
                   </div>
                 </div>
              @elseif($key == 'alamat_domisili')
                 <div class="kv-item">
                   <div class="kv-key">Alamat Saat Ini</div>
                   <div class="kv-value">
                      {{ strtoupper($value) }}
                      @if(isset($dataDiri['rt_domisili']) && isset($dataDiri['rw_domisili'])) RT {{ str_pad($dataDiri['rt_domisili'], 3, '0', STR_PAD_LEFT) }} RW {{ str_pad($dataDiri['rw_domisili'], 3, '0', STR_PAD_LEFT) }} @endif
                      @if(isset($dataDiri['kelurahan_domisili'])) KEL. {{ strtoupper($dataDiri['kelurahan_domisili']) }}, @endif
                      @if(isset($dataDiri['kecamatan_domisili'])) KEC. {{ strtoupper($dataDiri['kecamatan_domisili']) }} @endif
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
        <div class="section-card card-khusus">
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

        <!-- Card 3: Dokumen yang Diunggah -->
        <div class="section-card card-dokumen">
          <h3 class="section-card-title">Dokumen yang Diunggah</h3>
          <div class="doc-list">
            @if($pengajuan->dokumen_pendukung)
              @foreach($pengajuan->dokumen_pendukung as $key => $path)
              <div class="doc-item" onclick="event.preventDefault(); document.getElementById('doc-link-{{ $loop->index }}').click()">
                <div class="doc-icon">
                  <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                </div>
                <div class="doc-info">
                  <h4>{{ ucwords(str_replace(['dokumen_', '_'], ['', ' '], $key)) }}</h4>
                  @php
                    $sizeMB = \Illuminate\Support\Facades\Storage::disk('public')->exists($path) ? number_format(\Illuminate\Support\Facades\Storage::disk('public')->size($path) / 1048576, 2) : '1.2';
                  @endphp
                  <p>
                    <a id="doc-link-{{ $loop->index }}" href="{{ route('preview.dokumen', ['path' => $path]) }}" class="preview-dokumen" onclick="event.stopPropagation()">{{ preg_replace('/^\d+_[^_]+_/', '', basename($path)) }}</a>
                    <span style="margin-left:4px;">&mdash; {{ $sizeMB }} MB</span>
                  </p>
                </div>
              </div>
              @endforeach
            @else
              <p style="color: var(--gray-500); font-size: 0.85rem;">Tidak ada dokumen yang diunggah.</p>
            @endif
          </div>
        </div> <!-- End section-card dokumen -->
        </div> <!-- End left-col -->

        <div class="right-col">
          <!-- Status Pengajuan (Timeline) -->
          <div class="section-card card-lacak">
            <h3 class="section-card-title">Status Pengajuan</h3>
            <div class="stepper-v">
              <!-- Step 1: Dikirim -->
              <div class="step-v-item">
                <div class="step-v-line"></div>
                <div class="step-v-icon completed">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div class="step-v-text">
                  <h4>Pengajuan Dikirim</h4>
                  <p>{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d M Y') }}</p>
                </div>
              </div>

              <!-- Step 2: Pemeriksaan -->
              @php
                $step2Class = 'active';
                if ($pengajuan->status == 'disetujui' || $pengajuan->status == 'ditolak') $step2Class = 'completed';
              @endphp
              <div class="step-v-item">
                <div class="step-v-line"></div>
                <div class="step-v-icon {{ $step2Class }}">
                  @if($step2Class == 'completed')
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  @else
                  <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                  @endif
                </div>
                <div class="step-v-text {{ $step2Class }}">
                  <h4>Sedang Diverifikasi</h4>
                  <p>{{ $step2Class == 'completed' ? \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y') : 'Menunggu' }}</p>
                </div>
              </div>

              <!-- Step 3: Keputusan -->
              @php
                $step3Class = '';
                if ($pengajuan->status == 'disetujui') { $step3Class = 'completed'; }
                elseif ($pengajuan->status == 'ditolak') { $step3Class = 'rejected'; }
              @endphp
              <div class="step-v-item">
                <div class="step-v-line"></div>
                @if($pengajuan->status == 'ditolak')
                <div class="step-v-icon rejected">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </div>
                <div class="step-v-text rejected">
                  <h4>Persetujuan Ditolak</h4>
                  <p>{{ \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y') }}</p>
                </div>
                @else
                <div class="step-v-icon {{ $step3Class }}">
                  @if($step3Class == 'completed')
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  @endif
                </div>
                <div class="step-v-text">
                  <h4>{{ $step3Class == 'completed' ? 'Persetujuan Disetujui' : 'Persetujuan' }}</h4>
                  <p>{{ $step3Class == 'completed' ? \Carbon\Carbon::parse($pengajuan->updated_at)->translatedFormat('d M Y') : 'Menunggu' }}</p>
                </div>
                @endif
              </div>

              <!-- Step 4: TTE Lurah -->
              @if($pengajuan->status != 'ditolak')
              <div class="step-v-item">
                @php $step4Class = $pengajuan->is_verified_by_lurah ? 'completed' : ''; @endphp
                <div class="step-v-icon {{ $step4Class }}">
                  @if($step4Class == 'completed')
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  @endif
                </div>
                <div class="step-v-text">
                  <h4>TTE Lurah</h4>
                  <p>{{ $pengajuan->is_verified_by_lurah ? 'Terverifikasi' : '-' }}</p>
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

          @if($pengajuan->status == 'ditolak')
          <div class="section-card card-langkah" style="border: 1px solid #fca5a5;">
            <h3 style="font-size: 1rem; font-weight: 700; color: #b91c1c; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
              <span style="display: block; width: 4px; height: 18px; background: #b91c1c; border-radius: 4px;"></span>
              Informasi Warga
            </h3>
            <div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 16px; color: #ef4444; font-size: 0.85rem; line-height: 1.5; text-align: center; font-weight: 500;">
              Warga telah menerima notifikasi penolakan beserta catatan alasannya. Warga dapat mengajukan ulang setelah memperbaiki dokumen yang bermasalah.
            </div>
          </div>
          @endif

      <!-- ACTION BAR CARD (KEPUTUSAN) -->
          @if($pengajuan->status == 'menunggu')
          <div class="section-card card-aksi">
            <h3 class="section-card-title">Ambil Keputusan</h3>
            <p style="font-size: 0.85rem; color: var(--gray-500); margin-top: -10px; margin-bottom: 24px; line-height: 1.5;">Pastikan semua data dan dokumen sudah diperiksa sebelum mengambil keputusan.</p>
            <div style="display: flex; flex-direction: column; align-items: stretch;">
              <form action="{{ route('admin.proses-pengajuan', $pengajuan->id) }}" method="POST" id="form-setujui" style="margin: 0; width: 100%;">
                @csrf
                <input type="hidden" name="action" value="setujui">
                <input type="hidden" name="nomor_surat" id="setuju-nomorsurat" value="">
                <button type="button" class="btn-decision btn-approve" style="width: 100%; justify-content: center; background: #15803d; color: white; border-radius: 8px; padding: 12px;" onclick="confirmApprove('form-setujui')">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><polyline points="20 6 9 17 4 12"></polyline></svg>
                  Setujui Pengajuan
                </button>
              </form>

              <div style="display: flex; align-items: center; margin: 20px 0; color: #94a3b8; font-size: 0.85rem;">
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                <span style="padding: 0 12px;">atau</span>
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
              </div>

              <form action="{{ route('admin.proses-pengajuan', $pengajuan->id) }}" method="POST" id="form-tolak" style="margin: 0; width: 100%;">
                @csrf
                <input type="hidden" name="action" value="tolak">
                <input type="hidden" name="keterangan" id="tolak-keterangan" value="">
                <button type="button" class="btn-decision btn-reject" style="width: 100%; justify-content: center; background: #9a3412; color: white; border-radius: 8px; padding: 12px;" onclick="tolakPengajuan()">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                  Tolak Pengajuan
                </button>
              </form>
            </div>
          </div>
          @elseif($pengajuan->status == 'disetujui' && !$pengajuan->is_verified_by_lurah)
          <div class="section-card card-aksi" style="background: #fffbeb; border-color: #fde68a;">
            <h3 class="section-card-title" style="color: #b45309;">Menunggu TTE Lurah</h3>
            <p style="font-size: 0.85rem; color: #d97706; margin-top: -10px; margin-bottom: 20px;">Surat disetujui, belum ditandatangani.</p>
            
            <form action="{{ route('admin.proses-pengajuan', $pengajuan->id) }}" method="POST" id="form-verifikasi" style="margin: 0;">
              @csrf
              <input type="hidden" name="action" value="verifikasi">
              <button type="submit" class="btn-decision btn-publish" style="width: 100%; justify-content: center;" onclick="return confirm('Apakah Anda yakin ingin memverifikasi/menandatangani surat ini secara elektronik?');">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                Verifikasi (TTE) Surat Ini
              </button>
            </form>
          </div>
          @endif


      <!-- PDF Preview Card (Hanya jika disetujui & TTE selesai) -->
      @if($pengajuan->status == 'disetujui' && $pengajuan->is_verified_by_lurah)
      <div class="pdf-card">
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
              
              <div style="float: right; text-align: center; margin-top: 20px; width: 250px; position: relative;">
                Tasikmalaya, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                a.n CAMAT MANGKUBUMI<br>
                @php
                    $qrCodeMockup = null;
                    if ($pengajuan->token_validasi) {
                        $path = route('validasi', ['token' => $pengajuan->token_validasi], false);
                        $qrUrl = rtrim(config('app.url'), '/') . $path;
                        $qrCodeMockup = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->margin(0)->generate($qrUrl));
                    }
                @endphp
                @if($qrCodeMockup)
                <div style="position: relative; z-index: 2; margin: 10px auto; width: 70px; background: white; padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                  <img src="data:image/svg+xml;base64,{{ $qrCodeMockup }}" alt="QR Code TTE" style="width: 100%; height: auto; display: block;">
                </div>
                @else
                <div style="border: 1px dashed #ccc; padding: 15px; margin: 10px 0; color: #999; position: relative; z-index: 2; background: rgba(255,255,255,0.8);">
                  QR Code TTE
                </div>
                @endif
              </div>
              <div style="clear: both;"></div>
            </div>
          </div>
        </div>
      </div>
      @endif

        </div> <!-- End right-col -->
      </div> <!-- End grid-2col -->

    </main>

      </div> <!-- End content-area -->

</div> <!-- End page-body -->


<!-- ===== MOBILE BOTTOM NAV ===== -->
<nav class="mobile-bottom-nav">
  <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
    <span>Beranda</span>
  </a>
  <a href="{{ route('admin.daftar-pengajuan') }}" class="bottom-nav-item {{ request()->routeIs('admin.daftar-pengajuan') || request()->routeIs('admin.detail-pengajuan') ? 'active' : '' }}">
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

<!-- ===== MODALS ===== -->
<!-- Custom Modal Tolak Pengajuan -->
<div class="modal-overlay" id="modalTolak">
  <div class="modal-content">
    <h3 class="modal-title">Tolak Pengajuan</h3>
    <p class="modal-desc">Isi alasan penolakan dengan jelas agar warga dapat memperbaiki dan mengajukan ulang.</p>
    
    <div class="modal-form-group">
      <label class="modal-label" style="color: #1e293b;">Catatan Penolakan <span style="color: #ef4444;">*</span></label>
      <textarea id="modalTolakInput" class="modal-textarea" style="width: 100%; min-height: 100px; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1;" placeholder="Contoh : Dokumen Surat Pengantar RT/RW tidak terbaca jelas..."></textarea>
    </div>

    <div class="custom-conf-actions" style="margin-top: 24px;">
      <button type="button" class="custom-conf-btn-cancel" onclick="closeModalTolak()">Batal</button>
      <button type="button" class="custom-conf-btn-confirm" style="background-color: #ef4444;" onclick="submitTolak()">Ya</button>
    </div>
  </div>
</div>

<!-- ===== JAVASCRIPT ===== -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
  .swal2-popup { font-family: 'Inter', sans-serif !important; border-radius: 16px !important; }
  .swal2-title { font-size: 1.1rem !important; }
  .swal2-input { border-radius: 8px !important; font-size: 0.9rem !important; border: 1.5px solid #cbd5e1 !important; }
  .swal2-input:focus { border-color: #1d4ed8 !important; box-shadow: 0 0 0 3px rgba(29,78,216,0.1) !important; }
  .swal2-confirm { background: var(--green-dark) !important; border-radius: 8px !important; font-weight: 600 !important; }
  .swal2-cancel { background: var(--gray-400) !important; border-radius: 8px !important; font-weight: 600 !important; }
</style>
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
    }
  }

  overlay.addEventListener('click', () => {
    sidebar.classList.add('collapsed');
    overlay.classList.remove('active');
    hamburgerBtn.setAttribute('aria-expanded', 'false');
  });

  // Modal Tolak
  function tolakPengajuan() {
    document.getElementById('modalTolak').classList.add('active');
  }
  function closeModalTolak() {
    document.getElementById('modalTolak').classList.remove('active');
  }
  function submitTolak() {
    const keterangan = document.getElementById('modalTolakInput').value.trim();
    if (!keterangan) {
      alert('Catatan penolakan harus diisi!');
      return;
    }
    document.getElementById('tolak-keterangan').value = keterangan;
    document.getElementById('form-tolak').submit();
  }
  document.getElementById('modalTolak').addEventListener('click', function(e) {
    if (e.target === this) closeModalTolak();
  });

  // Confirm approve without nomor surat
  function confirmApprove(formId) {
    Swal.fire({
      showConfirmButton: false,
      customClass: { popup: 'custom-conf-popup', htmlContainer: 'custom-conf-html-container' },
      html: `
        <div class="custom-conf-icon-wrapper" style="background-color: #f1f5f9;">
          <div class="custom-conf-icon-inner" style="background-color: #1a4068;">?</div>
        </div>
        <h2 class="custom-conf-title">Setujui Pengajuan?</h2>
        <p class="custom-conf-text">Apakah Anda yakin ingin menyetujui pengajuan ini?<br>Nomor surat akan digenerate otomatis oleh sistem.</p>
        <div class="custom-conf-actions">
          <button type="button" class="custom-conf-btn-cancel" onclick="Swal.close()">Batal</button>
          <button type="button" class="custom-conf-btn-confirm" style="background-color: #0284c7;" onclick="document.getElementById('${formId}').submit()">Ya</button>
        </div>
      `
    });
  }

  function confirmLogout(e, href) {
    e.preventDefault();
    Swal.fire({
      showConfirmButton: false,
      customClass: { popup: 'custom-conf-popup', htmlContainer: 'custom-conf-html-container' },
      html: `
        <div class="custom-conf-icon-wrapper" style="background-color: #fee2e2;">
          <div class="custom-conf-icon-inner" style="background-color: #dc2626;">?</div>
        </div>
        <h2 class="custom-conf-title">Keluar dari Aplikasi?</h2>
        <p class="custom-conf-text">Sesi Anda akan berakhir dan Anda harus masuk kembali.</p>
        <div class="custom-conf-actions">
          <button type="button" class="custom-conf-btn-cancel" onclick="Swal.close()">Batal</button>
          <button type="button" class="custom-conf-btn-confirm" style="background-color: #dc2626;" onclick="window.location.href = '${href}'">Ya, Keluar</button>
        </div>
      `
    });
  }
</script>
<style>
  .custom-conf-popup { border-radius: 16px !important; padding: 32px 24px 28px !important; width: 420px !important; }
  .custom-conf-html-container { margin: 0 !important; padding: 0 !important; overflow: visible !important; }
  .custom-conf-icon-wrapper { width: 72px; height: 72px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
  .custom-conf-icon-inner { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; font-weight: 700; font-family: 'Inter', sans-serif; }
  .custom-conf-title { font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 10px; font-family: 'Inter', sans-serif; text-align: center; }
  .custom-conf-text { font-size: 0.92rem; color: #64748b; line-height: 1.5; margin: 0 0 28px; font-family: 'Inter', sans-serif; text-align: center; }
  .custom-conf-actions { display: flex; gap: 12px; margin: 0; width: 100%; }
  .custom-conf-btn-cancel { flex: 1; background-color: #cbd5e1 !important; color: #1e293b !important; border: none !important; border-radius: 9999px !important; padding: 12px 0 !important; font-weight: 600 !important; font-size: 0.9rem !important; cursor: pointer; text-align: center; font-family: 'Inter', sans-serif; transition: background-color 0.2s; outline: none !important; box-shadow: none !important; }
  .custom-conf-btn-cancel:hover { background-color: #94a3b8 !important; }
  .custom-conf-btn-confirm { flex: 1; color: white !important; border: none !important; border-radius: 9999px !important; padding: 12px 0 !important; font-weight: 600 !important; font-size: 0.9rem !important; cursor: pointer; text-align: center; font-family: 'Inter', sans-serif; transition: opacity 0.2s; outline: none !important; box-shadow: none !important; }
  .custom-conf-btn-confirm:hover { opacity: 0.9 !important; }
</style>

</body>
</html>
