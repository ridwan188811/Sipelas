<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Pengajuan – SIPELAS</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    /* ===== RESET & BASE ===== */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; }
    body { font-family: 'Inter', sans-serif; background: #f4f7f6; color: #1e293b; min-height: 100vh; display: flex; flex-direction: column; }
    :root { --navy: #1a3558; --navy-dark: #152c48; --blue: #1d4ed8; --green: #16a34a; --red: #dc2626; --gray-50: #f8fafc; --gray-100: #f1f5f9; --gray-200: #e2e8f0; --gray-300: #cbd5e1; --gray-400: #94a3b8; --gray-500: #64748b; --gray-600: #475569; --gray-800: #1e293b; --white: #ffffff; --shadow-sm: 0 1px 2px rgba(0,0,0,.05); --shadow: 0 4px 6px -1px rgba(0,0,0,.1); --radius: 8px; --header-h: 60px; --sidebar-w: 220px; }
    
    .header { position: sticky; top: 0; z-index: 50; background: var(--navy); height: var(--header-h); display: flex; align-items: center; padding: 0 20px; gap: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.2); flex-shrink: 0; }
    .page-body { display: flex; flex: 1; min-height: 0; }
    .sidebar { width: var(--sidebar-w); background: var(--white); border-right: 1px solid var(--gray-200); display: flex; flex-direction: column; flex-shrink: 0; position: sticky; top: var(--header-h); height: calc(100vh - var(--header-h)); overflow-y: auto; transition: width .28s; z-index: 30; }
    .sidebar.collapsed { width: 0; overflow: hidden; border-right: none; }
    .sidebar-nav { flex: 1; padding: 20px 12px; display: flex; flex-direction: column; gap: 4px; }
    .nav-item { display: flex; align-items: center; gap: 11px; padding: 11px 16px; border-radius: 9px; color: var(--gray-600); text-decoration: none; font-size: .9rem; font-weight: 500; white-space: nowrap; transition: background .18s, color .18s; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; color: var(--navy); transition: color .18s; }
    .nav-item:hover { background: var(--gray-100); color: var(--gray-800); }
    .nav-item.active { background: var(--navy); color: var(--white); font-weight: 600; }
    .nav-item.active svg { color: var(--white); }
    .sidebar-footer { padding: 12px; border-top: 1px solid var(--gray-200); margin-top: auto; }
    .logout-btn { display: flex; align-items: center; gap: 11px; width: 100%; padding: 11px 16px; border-radius: 9px; border: none; background: none; color: var(--gray-600); font-size: .9rem; font-weight: 500; cursor: pointer; text-decoration: none; }
    .logout-btn svg { width: 18px; height: 18px; color: #ef4444; }
    .logout-btn:hover { background: #fef2f2; color: #b91c1c; }
    
    .hamburger-btn { background: none; border: none; cursor: pointer; padding: 7px; border-radius: 7px; color: white; transition: background .18s; }
    .hamburger-btn:hover { background: rgba(255,255,255,.12); }
    .header-brand { display: flex; align-items: center; gap: 10px; flex: 1; }
    .header-brand-icon { width: 34px; height: 34px; background: transparent; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
    .header-brand-text { color: white; line-height: 1.2; }
    .header-brand-text .app-name { font-size: 1.05rem; font-weight: 700; }
    .header-brand-text .app-sub  { font-size: .62rem; opacity: .65; font-weight: 400; }
    .header-actions { display: flex; align-items: center; gap: 8px; }
    .user-avatar { width: 34px; height: 34px; background: var(--gray-400); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: white; font-size: .88rem; text-decoration: none; }

    .content-area { flex: 1; display: flex; flex-direction: column; min-width: 0; }
    .content { flex: 1; padding: 32px 40px; max-width: 1400px; margin: 0 auto; width: 100%; }
    
    .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; }
    .page-title h2 { font-size: 1.35rem; font-weight: 700; color: var(--gray-800); margin-bottom: 4px; }
    .page-title p { font-size: .85rem; color: var(--gray-500); }
    
    .btn-export { background: var(--red); color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: .85rem; display: flex; align-items: center; gap: 8px; cursor: pointer; text-decoration: none; box-shadow: var(--shadow-sm); }
    .btn-export:hover { opacity: .9; }
    
    .filter-bar { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 16px 20px; display: flex; gap: 15px; align-items: center; margin-bottom: 24px; flex-wrap: wrap; }
    .filter-select { padding: 10px 14px; border: 1px solid var(--gray-200); border-radius: 6px; font-size: .85rem; color: var(--gray-600); outline: none; min-width: 150px; background: #f1f5f9; }
    .btn-filter { background: var(--navy); color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; font-size: .85rem; cursor: pointer; }
    
    .summary-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 24px; }
    .summary-card { background: var(--white); padding: 20px; border-radius: var(--radius); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm); }
    .summary-card h4 { font-size: .85rem; color: var(--gray-500); margin-bottom: 8px; font-weight: 600; }
    .summary-card .value { font-size: 1.8rem; font-weight: 700; color: var(--gray-800); }
    
    .table-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius); display: flex; flex-direction: column; overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; text-align: left; }
    .data-table th, .data-table td { padding: 14px 20px; border-bottom: 1px solid var(--gray-200); font-size: .85rem; }
    .data-table th { background: #fafafa; font-size: .75rem; font-weight: 700; color: var(--gray-500); text-transform: uppercase; }
    .badge { padding: 4px 10px; border-radius: 12px; font-size: .75rem; font-weight: 600; }
    .badge-menunggu { background: #fef08a; color: #854d0e; }
    .badge-disetujui { background: #6ee7b7; color: #065f46; }
    .badge-ditolak { background: #fca5a5; color: #991b1b; }
    
    @media (max-width: 768px) {
      .content { padding: 16px; }
      .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
      .sidebar { display: none; }
    }
  </style>
</head>
<body>

<header class="header">
  <button class="hamburger-btn" id="hamburgerBtn" onclick="document.getElementById('sidebar').classList.toggle('collapsed')">
    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" style="width:24px;height:24px;"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </button>
  <div class="header-brand">
    <div class="header-brand-icon"><img src="{{ asset('images/logo_tasikmalaya.png') }}" style="width: 100%;"></div>
    <div class="header-brand-text">
      <div class="app-name">SIPELAS</div>
      <div class="app-sub">Sistem Informasi Pelayanan Masyarakat</div>
    </div>
  </div>
  <div class="header-actions">
    <a href="{{ route('admin.profil') }}" class="user-avatar">{{ strtoupper(substr($displayName, 0, 1)) }}</a>
  </div>
</header>

<div class="page-body">
  <!-- SIDEBAR -->
  <aside class="sidebar collapsed" id="sidebar">
    <nav class="sidebar-nav">
      <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
      <a href="{{ route('admin.daftar-pengajuan') }}" class="nav-item">Verifikasi Surat</a>
      <a href="{{ route('admin.riwayat-pengajuan') }}" class="nav-item">Riwayat Pengajuan</a>
      <a href="{{ route('admin.laporan') }}" class="nav-item active">Laporan</a>
    </nav>
    <div class="sidebar-footer">
      <a href="{{ route('logout') }}" class="logout-btn">Keluar</a>
    </div>
  </aside>

  <div class="content-area">
    <main class="content">
      <div class="page-header">
        <div class="page-title">
          <h2>Laporan Pengajuan Surat</h2>
          <p>Cetak dan pantau laporan bulanan pengajuan surat</p>
        </div>
        <a href="{{ route('admin.laporan.cetak-pdf', request()->all()) }}" target="_blank" class="btn-export">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
          Cetak PDF
        </a>
      </div>

      <form method="GET" action="{{ route('admin.laporan') }}" class="filter-bar">
        <select name="bulan" class="filter-select">
          <option value="semua">Semua Bulan</option>
          @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $name)
            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $name }}</option>
          @endforeach
        </select>
        
        <select name="tahun" class="filter-select">
          <option value="semua">Semua Tahun</option>
          @for($i = date('Y'); $i >= date('Y') - 5; $i--)
            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
          @endfor
        </select>

        <select name="status" class="filter-select">
          <option value="semua">Semua Status</option>
          <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
          <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
          <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
        </select>

        <select name="jenis" class="filter-select">
          <option value="semua">Semua Jenis Surat</option>
          <option value="sktm" {{ request('jenis') == 'sktm' ? 'selected' : '' }}>SKTM</option>
          <option value="sku" {{ request('jenis') == 'sku' ? 'selected' : '' }}>Surat Ket Usaha</option>
          <option value="domisili" {{ request('jenis') == 'domisili' ? 'selected' : '' }}>Surat Ket Domisili</option>
        </select>

        <button type="submit" class="btn-filter">Filter Data</button>
      </form>

      <div class="summary-cards">
        <div class="summary-card">
          <h4>Total Pengajuan</h4>
          <div class="value">{{ $totalPengajuan }}</div>
        </div>
        <div class="summary-card">
          <h4>Disetujui</h4>
          <div class="value" style="color: var(--green);">{{ $totalDisetujui }}</div>
        </div>
        <div class="summary-card">
          <h4>Ditolak</h4>
          <div class="value" style="color: var(--red);">{{ $totalDitolak }}</div>
        </div>
      </div>

      <div class="table-card">
        <div style="overflow-x: auto;">
          <table class="data-table">
            <thead>
              <tr>
                <th>NO</th>
                <th>TANGGAL</th>
                <th>NAMA PEMOHON</th>
                <th>NIK</th>
                <th>JENIS SURAT</th>
                <th>STATUS</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pengajuans as $index => $pengajuan)
              <tr>
                <td>{{ $pengajuans->firstItem() + $index }}</td>
                <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y') }}</td>
                <td>{{ $pengajuan->warga->name ?? explode('@', $pengajuan->warga->email)[0] }}</td>
                <td>{{ $pengajuan->warga->nik ?? '-' }}</td>
                <td>{{ ucwords(str_replace('-', ' ', $pengajuan->jenis_surat)) }}</td>
                <td><span class="badge badge-{{ strtolower($pengajuan->status) }}">{{ ucfirst($pengajuan->status) }}</span></td>
              </tr>
              @empty
              <tr>
                <td colspan="6" style="text-align:center; padding:30px;">Tidak ada data laporan.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        @if($pengajuans->hasPages())
        <div style="padding: 16px; display: flex; justify-content: center; background: #fafafa;">
          {{ $pengajuans->links('pagination::bootstrap-4') }}
        </div>
        @endif
      </div>

    </main>
  </div>
</div>
</body>
</html>
