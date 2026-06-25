<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Perubahan Kata Sandi</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f7f6; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #1a3558; padding: 30px 20px; text-align: center; color: white; }
        .header img { width: 60px; height: auto; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .header p { margin: 5px 0 0; font-size: 14px; opacity: 0.8; }
        .content { padding: 30px; }
        .greeting { font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #1e293b; }
        .message-box { padding: 20px; border-radius: 8px; margin-bottom: 25px; background-color: #fffbeb; border-left: 4px solid #f59e0b; }
        .message-box p { margin: 0; font-size: 15px; color: #334155; }
        .btn-container { text-align: center; margin-top: 30px; margin-bottom: 10px; }
        .btn { display: inline-block; padding: 12px 28px; background-color: #f59e0b; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 15px; }
        .btn:hover { background-color: #d97706; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .footer p { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo_tasikmalaya.png') }}" alt="Logo Tasikmalaya">
            <h1>Sistem Informasi Pelayanan Masyarakat</h1>
            <p>Kelurahan Sambongpari, Kota Tasikmalaya</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Halo, {{ $user->name ?? explode('@', $user->email)[0] }}!
            </div>

            <div class="message-box">
                <p>Kami menerima permintaan untuk mengubah kata sandi akun SIPELAS Anda. Untuk alasan keamanan, kata sandi Anda belum diubah sampai Anda mengonfirmasinya.</p>
            </div>
            
            <p style="font-size: 15px; color: #334155;">
                Jika ini memang Anda, silakan klik tombol di bawah ini untuk menyelesaikan proses perubahan kata sandi Anda. Tautan ini hanya berlaku selama 30 menit.
            </p>

            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Konfirmasi Kata Sandi Baru</a>
            </div>
            
            <p style="font-size: 14px; color: #dc2626; margin-top: 25px; text-align: center;">
                <strong>Perhatian:</strong> Jika Anda tidak pernah merasa mengubah kata sandi, abaikan email ini dan akun Anda akan tetap aman dengan kata sandi yang lama.
            </p>
            
            <p style="font-size: 13px; color: #64748b; margin-top: 30px; text-align: center; word-break: break-all;">
                Atau salin dan tempel tautan berikut ke browser Anda: <br>
                <a href="{{ $url }}" style="color: #2563eb;">{{ $url }}</a>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Kelurahan Sambongpari, Kota Tasikmalaya.</p>
            <p>Pesan ini dibuat secara otomatis oleh sistem keamanan kami.</p>
        </div>
    </div>
</body>
</html>
