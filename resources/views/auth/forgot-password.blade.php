<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Lupa Kata Sandi – SIPELAS</title>
  <meta name="description" content="Lupa kata sandi SIPELAS – Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; font-family: 'Inter', sans-serif; }

    :root {
      --navy:      #1a4068;
      --navy-dark: #153454;
      --navy-text: #1a4068;
      --blue-link: #2563eb;
      --input-bg:  #cdd5dc;
      --gray-600:  #475569;
      --gray-800:  #1e293b;
      --white:     #ffffff;
      --radius-input: 10px;
      --radius-btn:   10px;
    }

    /* ===== LAYOUT 2-PANEL ===== */
    .page {
      display: flex;
      min-height: 100vh;
    }

    /* ===== PANEL KIRI ===== */
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
      font-weight: 400;
    }

    /* ===== PANEL KANAN ===== */
    .right-panel {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--white);
      padding: 48px 32px;
    }

    .form-box {
      width: 100%;
      max-width: 420px;
    }

    /* Logo di atas form */
    .form-logo {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin-bottom: 20px;
    }
    .form-logo-icon {
      width: 52px; height: 52px;
      display: flex; align-items: center; justify-content: center;
    }
    .form-logo-icon svg { width: 52px; height: 52px; }
    .form-logo-text .logo-name {
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--navy-text);
      letter-spacing: .03em;
      line-height: 1;
    }
    .form-logo-text .logo-sub {
      font-size: .72rem;
      color: var(--navy-text);
      opacity: .65;
      margin-top: 3px;
    }

    .form-divider {
      border: none;
      border-top: 1.5px solid #e2e8f0;
      margin: 20px 0 28px;
    }

    /* ===== FORM FIELDS ===== */
    .form-group {
      margin-bottom: 18px;
    }
    .form-group label {
      display: block;
      font-size: .88rem;
      font-weight: 600;
      color: var(--gray-800);
      margin-bottom: 8px;
    }
    .input-wrap {
      position: relative;
    }
    .form-input {
      width: 100%;
      padding: 14px 16px;
      background: var(--input-bg);
      border: none;
      border-radius: var(--radius-input);
      font-size: .92rem;
      color: var(--gray-800);
      font-family: 'Inter', sans-serif;
      outline: none;
      transition: background .18s, box-shadow .18s;
    }
    .form-input::placeholder { color: #7f8fa4; }
    .form-input:focus {
      background: #bec8d1;
      box-shadow: 0 0 0 3px rgba(26,64,104,.18);
    }
    .is-invalid {
      border: 1.5px solid #ef4444 !important;
      background-color: #fef2f2 !important;
    }
    .error-message {
      color: #dc2626;
      font-size: 0.82rem;
      margin-top: 6px;
      font-weight: 500;
    }
    /* password with eye icon */
    .form-input.has-icon { padding-right: 46px; }
    .eye-btn {
      position: absolute;
      right: 14px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none;
      cursor: pointer;
      color: #6b7280;
      display: flex; align-items: center;
      padding: 4px;
      transition: color .18s;
    }
    .eye-btn:hover { color: var(--navy); }
    .eye-btn svg { width: 18px; height: 18px; }

    /* ===== SUBMIT BUTTON ===== */
    .btn-submit {
      width: 100%;
      padding: 15px;
      background: var(--navy);
      color: white;
      border: none;
      border-radius: var(--radius-btn);
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      margin-top: 8px;
      transition: background .18s, transform .15s;
      letter-spacing: .02em;
    }
    .btn-submit:hover { background: var(--navy-dark); transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }

    /* ===== FOOTER LINK ===== */
    .form-footer {
      text-align: center;
      margin-top: 22px;
      font-size: .88rem;
      color: var(--gray-600);
    }
    .form-footer a {
      color: var(--blue-link);
      text-decoration: none;
      font-weight: 600;
    }
    .form-footer a:hover { text-decoration: underline; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 680px) {
      .page { flex-direction: column; }
      .left-panel { display: none; }
      .right-panel { padding: 36px 20px; min-height: 100vh; }
    }
  </style>
</head>
<body>
<div class="page">

  <!-- ===== PANEL KIRI ===== -->
  <div class="left-panel">
    <h1>SIPELAS</h1>
    <p>
      Sistem Informasi Pelayanan Masyarakat<br>
      Kelurahan Sambongpari
    </p>
  </div>

  <!-- ===== PANEL KANAN ===== -->
  <div class="right-panel">
    <div class="form-box">

      <!-- Logo -->
      <div class="form-logo">
        <div class="form-logo-icon"><img src="{{ asset('images/logo_tasikmalaya.png') }}" alt="Logo Tasikmalaya" style="width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));"></div>
        <div class="form-logo-text">
          <div class="logo-name">SIPELAS</div>
          <div class="logo-sub">Sistem Informasi Pelayanan Masyarakat</div>
        </div>
      </div>

      <hr class="form-divider" />



      <!-- Status Alert -->
      @if (session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; font-size: .88rem; font-weight: 500; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
      @endif

      <!-- Form Forgot Password -->
      <form action="{{ route('password.verify') }}" method="POST" id="forgotPasswordForm">
        @csrf

        <!-- Email -->
        <div class="form-group">
          <label for="email">Email</label>
          <div class="input-wrap">
            <input
              type="email"
              id="email"
              name="email"
              class="form-input @error('email') is-invalid @enderror"
              placeholder="Masukan Email Anda"
              value="{{ old('email') }}"
              required
              autocomplete="email"
              autofocus
            />
          </div>
          @error('email')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-submit">Kirim Link</button>
      </form>

      <!-- Footer link -->
      <div class="form-footer">
        Kembali ke <a href="{{ route('login') }}">halaman Masuk</a>
      </div>

    </div>
  </div>

</div>

</body>
</html>


