<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f7f6; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #1a3558; padding: 30px 20px; text-align: center; color: white; }
        .header img { width: 60px; height: auto; margin-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .header p { margin: 5px 0 0; font-size: 14px; opacity: 0.8; }
        .content { padding: 30px; }
        .greeting { font-size: 18px; font-weight: 600; margin-bottom: 20px; color: #1e293b; }
        .message-box { padding: 20px; border-radius: 8px; margin-bottom: 25px; background-color: #f0fdf4; border-left: 4px solid #22c55e; }
        .message-box p { margin: 0; font-size: 15px; color: #334155; }
        .btn-container { text-align: center; margin-top: 30px; margin-bottom: 10px; }
        .btn { display: inline-block; padding: 12px 28px; background-color: #2563eb; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 15px; }
        .btn:hover { background-color: #1d4ed8; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; font-size: 13px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .footer p { margin: 5px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/logo_tasikmalaya.png')) }}" alt="Logo" style="height: 60px; margin-bottom: 15px; display: block;">
            <h1>Sistem Informasi Pelayanan Masyarakat</h1>
            <p>Kelurahan Sambongpari, Kota Tasikmalaya</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Halo, {{ $user->name ?? explode('@', $user->email)[0] }}!
            </div>

            <div class="message-box">
                <p>Kami menerima permintaan untuk mereset kata sandi akun SIPELAS Anda. Silakan klik tombol di bawah ini untuk membuat kata sandi baru.</p>
            </div>
            
            <p style="font-size: 15px; color: #334155;">
                Tautan reset kata sandi ini hanya berlaku selama 30 menit. Jika Anda mengalami kesulitan, silakan hubungi admin Kelurahan.
            </p>

            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Reset Kata Sandi</a>
            </div>
            
            <p style="font-size: 14px; color: #dc2626; margin-top: 25px; text-align: center;">
                <strong>Perhatian:</strong> Jika Anda tidak pernah meminta reset kata sandi, abaikan email ini dan pastikan akun Anda tetap aman.
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
