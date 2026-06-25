<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="{{ asset('images/logo_tasikmalaya.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun SIPELAS</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f1f5f9; width: 100%;">
        <tr>
            <td align="center" style="padding: 40px 15px;">
                <!-- Main Container -->
                <table width="100%" max-width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #1a4068; padding: 40px 30px;">
                            <img src="{{ asset('images/logo_tasikmalaya.png') }}" alt="Logo" style="height: 60px; margin-bottom: 15px; display: block;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 800; letter-spacing: 1px;">SIPELAS</h1>
                            <p style="color: #cbd5e1; margin: 10px 0 0 0; font-size: 14px; line-height: 1.5;">Sistem Informasi Pelayanan Masyarakat<br>Kelurahan Sambongpari</p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #334155;">Halo <strong>{{ $user->email }}</strong>,</p>
                            
                            <p style="margin: 0 0 25px 0; font-size: 15px; color: #475569; line-height: 1.6;">
                                Terima kasih telah mendaftar di SIPELAS Kelurahan Sambongpari. Untuk memastikan keamanan akun Anda dan menyelesaikan proses pendaftaran, silakan verifikasi alamat email Anda dengan menekan tombol di bawah ini.
                            </p>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding: 10px 0 35px 0;">
                                        <a href="{{ $url }}" style="display: inline-block; padding: 14px 32px; background-color: #2563eb; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: bold; border-radius: 8px; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);">Verifikasi Email Saya</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0; font-size: 14px; color: #64748b; line-height: 1.6;">
                                Link ini akan otomatis tidak berlaku dalam waktu 60 menit. Jika Anda tidak merasa mendaftar akun di aplikasi SIPELAS, Anda bisa mengabaikan email ini.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 25px 30px; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0; font-size: 13px; color: #94a3b8;">
                                &copy; {{ date('Y') }} SIPELAS Kelurahan Sambongpari. Semua hak dilindungi.
                            </p>
                        </td>
                    </tr>
                </table>
                <!-- End Main Container -->
                
                <p style="margin: 20px 0 0 0; font-size: 12px; color: #94a3b8; text-align: center;">
                    Pesan ini dibuat otomatis oleh sistem, mohon untuk tidak membalas email ini.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
