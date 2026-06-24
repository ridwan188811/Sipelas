<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Masuk – SIPELAS</title>
  <meta name="description" content="Login ke SIPELAS – Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari" />
  
  <!-- Favicon & Open Graph (Logo untuk Link/Share WhatsApp) -->
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta property="og:title" content="SIPELAS - Kelurahan Sambongpari">
  <meta property="og:description" content="Sistem Informasi Pelayanan Masyarakat Kelurahan Sambongpari, Kota Tasikmalaya.">
  <meta property="og:image" content="{{ asset('images/logo_tasikmalaya.png') }}">
  <meta property="og:url" content="{{ url()->current() }}">
  
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

      <!-- Form Login -->
      <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
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
            />
          </div>
          @error('email')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <!-- Kata Sandi -->
        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <div class="input-wrap">
            <input
              type="password"
              id="password"
              name="password"
              class="form-input has-icon @error('password') is-invalid @enderror"
              placeholder="Masukan Kata Sandi Anda"
              required
              autocomplete="current-password"
            />
            <button type="button" class="eye-btn" aria-label="Tampilkan kata sandi" data-target="password">
              <svg id="eye-icon-password" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
          @error('password')
            <div class="error-message">{{ $message }}</div>
          @enderror
          <div style="text-align: right; margin-top: 8px;">
            <a href="{{ route('password.request') }}" style="font-size: .85rem; color: var(--blue-link); text-decoration: none; font-weight: 500;">Lupa Kata Sandi?</a>
          </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-submit">Masuk</button>

      </form>

      <!-- Footer link -->
      <div class="form-footer">
        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
      </div>

    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
  .custom-reg-btn-outline {
    width: 100% !important;
    background-color: transparent !important;
    color: #475569 !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 9999px !important;
    padding: 14px 0 !important;
    font-weight: 600 !important;
    font-size: 0.95rem !important;
    box-shadow: none !important;
    cursor: pointer !important;
    outline: none !important;
    margin-top: 10px !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    transition: background-color 0.2s;
  }
  .custom-reg-btn-outline:hover { background-color: #f1f5f9 !important; }
</style>

@if(session('register_success'))
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
        <h2 class="custom-reg-title">Pendaftaran Akun Berhasil</h2>
        <p class="custom-reg-text">{{ session('register_success') }}</p>
        <button type="button" class="custom-reg-btn" onclick="Swal.close()">Oke</button>
      `
    });
  });
</script>
@endif

@if(session('success'))
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

@if(session('verified_success'))
<script>
  document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
      title: 'Selamat!',
      text: "{{ session('verified_success') }}",
      icon: 'success',
      confirmButtonText: 'Login Sekarang',
      confirmButtonColor: '#1a4068',
      background: '#ffffff'
    });
  });
</script>
@endif
@if(session('register_verified'))
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
        <h2 class="custom-reg-title">Pendaftaran Berhasil!</h2>
        <p class="custom-reg-text">{{ session('register_verified') }}</p>
        <button type="button" class="custom-reg-btn" onclick="Swal.close()">Oke</button>
      `
    });
  });
</script>
@endif

@if(session('verify_already_done'))
<script>
  document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
      showConfirmButton: true,
      confirmButtonText: 'Login Sekarang',
      customClass: { 
        popup: 'custom-reg-popup', 
        htmlContainer: 'custom-reg-html-container',
        confirmButton: 'custom-reg-btn'
      },
      html: `
        <div class="custom-reg-icon-wrapper" style="background-color: #f0fdf4;">
          <div class="custom-reg-icon-inner" style="background-color: #16a34a;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          </div>
        </div>
        <h2 class="custom-reg-title" style="font-size: 1.2rem;">Sudah Diverifikasi</h2>
        <p class="custom-reg-text" style="color: #475569;">{{ session('verify_already_done') }}</p>
      `
    });
  });
</script>
@endif

@if(session('verify_notice_popup'))
<script>
  document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
      showConfirmButton: true,
      showCancelButton: true,
      confirmButtonText: 'Oke, Saya Mengerti',
      cancelButtonText: 'Kirim Ulang Email',
      customClass: {
        popup: 'custom-reg-popup',
        htmlContainer: 'custom-reg-html-container',
        confirmButton: 'custom-reg-btn',
        cancelButton: 'custom-reg-btn-outline',
        actions: 'swal2-actions-stacked'
      },
      buttonsStyling: false,
      html: `
        <div class="custom-reg-icon-wrapper" style="background-color: #eff6ff;">
          <div class="custom-reg-icon-inner" style="background-color: #2563eb;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
              <polyline points="22,6 12,13 2,6"/>
            </svg>
          </div>
        </div>
        <h2 class="custom-reg-title">Cek Email Anda</h2>
        <p class="custom-reg-text" style="margin-bottom: 0;">{{ session('verify_notice_popup') }}</p>
      `
    }).then((result) => {
      if (result.dismiss === Swal.DismissReason.cancel) {
        let savedEmail = "{{ session('unverified_email') }}";
        
        if (savedEmail) {
            Swal.fire({
                title: 'Mengirim Ulang...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('verification.resend.custom') }}';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'email';
            emailInput.value = savedEmail;
            form.appendChild(emailInput);

            document.body.appendChild(form);
            form.submit();
        } else {
            Swal.fire({
              title: 'Kirim Ulang Email',
              input: 'email',
              inputLabel: 'Masukkan alamat email akun Anda',
              inputPlaceholder: 'nama@email.com',
              showCancelButton: true,
              confirmButtonText: 'Kirim',
              cancelButtonText: 'Batal',
              customClass: {
                confirmButton: 'custom-reg-btn',
                cancelButton: 'custom-reg-btn-outline',
                popup: 'custom-reg-popup'
              },
              buttonsStyling: false,
              preConfirm: (email) => {
                if (!email) {
                  Swal.showValidationMessage('Email tidak boleh kosong');
                }
                return email;
              }
            }).then((res) => {
              if (res.isConfirmed) {
                Swal.fire({
                    title: 'Mengirim Ulang...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('verification.resend.custom') }}';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                const emailInput = document.createElement('input');
                emailInput.type = 'hidden';
                emailInput.name = 'email';
                emailInput.value = res.value;
                form.appendChild(emailInput);

                document.body.appendChild(form);
                form.submit();
              }
            });
        }
      }
    });
  });
</script>
@endif

<script>
  // Toggle show/hide password
  document.querySelectorAll('.eye-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.dataset.target;
      const input = document.getElementById(targetId);
      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      btn.setAttribute('aria-label', isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
      // Ganti icon
      const icon = btn.querySelector('svg');
      if (isPassword) {
        icon.innerHTML = `
          <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
          <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
          <line x1="1" y1="1" x2="23" y2="23"/>`;
      } else {
        icon.innerHTML = `
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
          <circle cx="12" cy="12" r="3"/>`;
      }
    });
  });
</script>
</body>
</html>


