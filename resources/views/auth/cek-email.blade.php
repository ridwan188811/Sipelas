<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Verifikasi Email – SIPELAS</title>
  <meta name="description" content="Verifikasi email akun SIPELAS Anda" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: 'Inter', sans-serif; }

    :root {
      --navy:      #1a4068;
      --navy-dark: #153454;
      --navy-text: #1a4068;
      --blue-link: #2563eb;
      --gray-600:  #475569;
      --gray-800:  #1e293b;
      --white:     #ffffff;
    }

    .page {
      display: flex;
      min-height: 100vh;
    }

    /* Panel Kiri */
    .left-panel {
      width: 460px;
      flex-shrink: 0;
      background: var(--navy);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 56px 48px;
      text-align: center;
    }
    .left-panel h1 {
      font-size: 2rem;
      font-weight: 800;
      color: white;
      letter-spacing: .04em;
      margin-bottom: 16px;
    }
    .left-panel p {
      font-size: .95rem;
      color: rgba(255,255,255,.72);
      line-height: 1.7;
    }

    /* Panel Kanan */
    .right-panel {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f8fafc; /* Latar belakang soft */
      padding: 48px 32px;
    }

    .card {
      width: 100%;
      max-width: 460px;
      background: var(--white);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01);
      text-align: center;
    }

    /* Ikon amplop */
    .icon-wrap {
      width: 88px;
      height: 88px;
      background: #e0f2fe;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 28px;
    }
    .icon-wrap svg {
      width: 44px;
      height: 44px;
      color: #0284c7;
    }

    h2 {
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--gray-800);
      margin-bottom: 12px;
    }

    .desc {
      font-size: .98rem;
      color: var(--gray-600);
      line-height: 1.6;
      margin-bottom: 12px;
    }

    .email-highlight {
      display: inline-block;
      font-weight: 700;
      color: var(--navy);
      background: #f1f5f9;
      padding: 4px 12px;
      border-radius: 8px;
      margin-top: 4px;
    }

    .note {
      font-size: .85rem;
      color: #94a3b8;
      margin-bottom: 32px;
      line-height: 1.6;
    }

    .divider {
      border: none;
      border-top: 1.5px dashed #e2e8f0;
      margin: 28px 0;
    }

    .resend-label {
      font-size: .9rem;
      color: var(--gray-600);
      margin-bottom: 14px;
    }

    .btn-resend {
      width: 100%;
      padding: 14px;
      background: var(--navy);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: .95rem;
      font-weight: 700;
      cursor: pointer;
      transition: all .2s;
      letter-spacing: .02em;
    }
    .btn-resend:hover { background: var(--navy-dark); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(26,64,104,0.2); }
    .btn-resend:active { transform: translateY(0); }

    .back-link {
      display: inline-block;
      margin-top: 24px;
      font-size: .9rem;
      color: var(--gray-600);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }
    .back-link:hover { color: var(--navy); }

    @media (max-width: 680px) {
      .page { flex-direction: column; }
      .left-panel { display: none; }
      .right-panel { padding: 24px 20px; background: var(--white); }
      .card { padding: 20px 10px; box-shadow: none; }
    }

    /* Custom SweetAlert styles */
    .custom-popup {
      border-radius: 16px !important;
      padding: 36px 30px !important;
      width: 400px !important;
    }
    .custom-icon-wrapper {
      width: 80px;
      height: 80px;
      background-color: #e6fceb;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 24px;
    }
    .custom-icon-inner {
      width: 44px;
      height: 44px;
      background-color: #15803d;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .custom-icon-inner svg {
      color: white;
      width: 24px;
      height: 24px;
    }
    .custom-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: #1e293b;
      margin: 0 0 12px;
      font-family: 'Inter', sans-serif;
      text-align: center;
    }
    .custom-text {
      font-size: 0.95rem;
      color: #64748b;
      line-height: 1.5;
      margin: 0 0 32px;
      font-family: 'Inter', sans-serif;
      text-align: center;
    }
    .custom-btn {
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
    }
  </style>
</head>
<body>
<div class="page">

  <!-- Panel Kiri -->
  <div class="left-panel">
    <h1>SIPELAS</h1>
    <p>
      Sistem Informasi Pelayanan Masyarakat<br>
      Kelurahan Sambongpari
    </p>
  </div>

  <!-- Panel Kanan -->
  <div class="right-panel">
    <div class="card">

      <!-- Ikon -->
      <div class="icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
          <polyline points="22,6 12,13 2,6"/>
        </svg>
      </div>

      <h2>Cek Email Anda!</h2>
      <p class="desc">
        Kami telah mengirim link verifikasi ke:<br>
        <span class="email-highlight">{{ Auth::user() ? Auth::user()->email : 'Email Anda' }}</span>
      </p>
      <p class="note">
        Silakan buka email Anda dan klik tombol <strong>"Verifikasi Email Sekarang"</strong>.<br>
        Link akan kedaluwarsa dalam 60 menit.
      </p>

      <hr class="divider" />

      <p class="resend-label">Tidak menerima email? Cek folder <em>Spam</em> atau kirim ulang.</p>

      <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-resend">Kirim Ulang Email Verifikasi</button>
      </form>

      <a href="{{ route('logout') }}" class="back-link">← Kembali ke Halaman Login</a>

    </div>
  </div>

</div>

<!-- Pop-up Kirim Ulang -->
@if (session('resent'))
<script>
  document.addEventListener("DOMContentLoaded", function() {
    let titleText = "Email Terkirim";
    let messageText = "Link verifikasi email yang baru telah berhasil dikirim. Silakan cek kotak masuk Anda.";

    Swal.fire({
      showConfirmButton: false,
      customClass: { 
        popup: 'custom-popup'
      },
      html: `
        <div class="custom-icon-wrapper">
          <div class="custom-icon-inner">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
        </div>
        <h2 class="custom-title">${titleText}</h2>
        <p class="custom-text">${messageText}</p>
        <button type="button" class="custom-btn" onclick="Swal.close()">Oke, Mengerti</button>
      `
    });
  });
</script>
@endif

</body>
</html>
