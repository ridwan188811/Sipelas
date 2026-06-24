<!DOCTYPE html>
<html lang="id">
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'Surat Kelurahan')</title>
    <style>
        /* CSS Khusus DomPDF */
        @page {
            margin: 1.5cm 2cm; /* Margin diperkecil agar muat 1 halaman */
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt; /* Diperkecil sedikit agar muat */
            line-height: 1.3;
            color: #000;
        }

        /* ===== KOP SURAT ===== */
        .kop-surat {
            width: 100%;
            margin-bottom: 0;
        }
        .garis-kop-1 {
            border-top: 3px solid #000;
            margin-top: 5px;
        }
        .garis-kop-2 {
            border-top: 1px solid #000;
            margin-top: 2px;
            margin-bottom: 15px;
        }
        .tabel-kop {
            width: 100%;
            border-collapse: collapse;
        }
        .tabel-kop td {
            vertical-align: bottom;
        }
        .logo-container {
            width: 90px;
            text-align: left;
            vertical-align: middle !important;
        }
        .logo-container img {
            width: 80px;
            height: auto;
        }
        .teks-kop {
            text-align: center;
        }
        .teks-kop .kop-1 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }
        .teks-kop .kop-2 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }
        .teks-kop .kop-3 {
            font-size: 17pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }
        .alamat-kop {
            font-size: 10pt;
            margin-top: 5px;
        }
        .kode-pos {
            font-size: 10pt;
            text-align: right;
            vertical-align: bottom;
            padding-bottom: 0px; 
            width: 90px;
            white-space: nowrap;
        }

        /* ===== KONTEN SURAT ===== */
        .konten-surat {
            margin-top: 25px;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-surat h4 {
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
            margin-bottom: 2px;
        }
        .judul-surat p {
            margin: 0;
            font-size: 12pt;
        }

        /* Helper Utilities */
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .text-right { text-align: right; }
        .mt-10 { margin-top: 10px; }
        .mt-20 { margin-top: 20px; }
        .mb-10 { margin-bottom: 10px; }
        .indent { text-indent: 40px; }

        /* Tabel Data Diri */
        .tabel-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .tabel-data td {
            padding: 2px 0;
            vertical-align: top;
        }
        .td-label {
            width: 30%;
        }
        .td-titikdua {
            width: 3%;
            text-align: center;
        }
        .td-value {
            width: 67%;
        }

        /* Bagian Tanda Tangan */
        .tanda-tangan-container {
            width: 100%;
            margin-top: 40px;
        }
        .tanda-tangan-box {
            float: right;
            width: 300px;
            text-align: center;
        }
        .qr-placeholder {
            border: 1px dashed #999;
            padding: 20px;
            margin: 15px 0;
            color: #999;
            font-size: 10pt;
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.9);
        }
        .stempel-img {
            position: absolute;
            left: -80px;
            top: 20px;
            width: 130px;
            z-index: 1;
            opacity: 0.8;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }
        
        .clear {
            clear: both;
        }

    </style>
</head>
<body>
    
    <!-- KOP SURAT -->
    <div class="kop-surat">
        <table class="tabel-kop">
            <tr>
                <!-- Logo -->
                <td class="logo-container">
                      @php
                          // Gunakan base64 agar DomPDF 100% bisa membaca file baik di lokal maupun di Vercel
                          $logoPath = base_path('public/logo/Seal_of_the_City_of_Tasikmalaya.svg.png');
                          $logoSrc = '';
                          if(file_exists($logoPath)) {
                              $logoData = base64_encode(file_get_contents($logoPath));
                              $logoSrc = 'data:image/png;base64,' . $logoData;
                          }
                      @endphp
                      @if($logoSrc)
                      <img src="{{ $logoSrc }}" alt="Logo Tasikmalaya">
                      @endif
                </td>
                
                <!-- Teks Tengah -->
                <td class="teks-kop">
                    <div class="kop-1">PEMERINTAH KOTA TASIKMALAYA</div>
                    <div class="kop-2">KECAMATAN MANGKUBUMI</div>
                    <div class="kop-3">KELURAHAN SAMBONGPARI</div>
                    <div class="alamat-kop">
                        Jl. Mayor SL Tobing - Telp.-<br>
                        Email: sambongpari.kel@tasikmalayakota.go.id<br>
                        TASIKMALAYA
                    </div>
                </td>
                
                <!-- Kode Pos -->
                <td class="kode-pos">
                    Kode Pos 46181
                </td>
            </tr>
        </table>
    </div>
    <div class="garis-kop-1"></div>
    <div class="garis-kop-2"></div>

    <!-- KONTEN SURAT -->
    <div class="konten-surat">
        @yield('content')
    </div>

</body>
</html>

