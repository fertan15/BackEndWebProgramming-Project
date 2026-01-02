<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Anda</title>
    <style>
        /* CSS Reset & Mobile Styles */
        body { margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        table { border-spacing: 0; }
        td { padding: 0; }
        img { border: 0; }
        
        /* Responsive: Agar lebar max 100% di HP */
        @media screen and (max-width: 600px) {
            .container { width: 100% !important; }
            .content { padding: 20px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6;">

    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6; padding: 40px 0;">
        <tr>
            <td align="center">
                
                <table role="presentation" class="container" width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
                    
                    <tr>
                        <td align="center" style="padding: 40px 0 20px 0; background-color: #ffffff;">
                            <div style="width: 60px; height: 60px; background-color: #e0e7ff; border-radius: 50%; line-height: 60px; color: #4f46e5; font-size: 30px; font-weight: bold; text-align: center;">
                                🔒
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td class="content" style="padding: 0 40px; text-align: center;">
                            <h1 style="color: #1f2937; font-size: 24px; margin-bottom: 10px; font-weight: bold;">Kode Verifikasi</h1>
                            <p style="color: #6b7280; font-size: 16px; line-height: 24px; margin-top: 0;">
                                Halo, <strong style="color: #374151;">{{ session('register.name') }}</strong>.
                                <br>
                                Gunakan kode di bawah ini untuk memverifikasi akun Anda.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 30px 40px;">
                            <table role="presentation" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="background-color: #f3f4f6; border-radius: 8px; border: 2px dashed #4f46e5; padding: 15px 40px;">
                                        <span style="font-family: monospace; font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #4f46e5; display: block;">
                                            {{ session('otp') }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <p style="color: #ef4444; font-size: 13px; margin-top: 20px;">
                                *Kode ini akan kadaluarsa dalam 5 menit.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td class="content" style="padding: 0 40px 40px 40px; text-align: center;">
                            <p style="color: #6b7280; font-size: 14px; line-height: 22px; margin: 0;">
                                Jika Anda tidak meminta kode ini, silakan abaikan email ini atau hubungi support kami demi keamanan akun Anda.
                            </p>
                            
                            <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 30px 0;">
                            
                            <p style="color: #9ca3af; font-size: 12px;">
                                &copy; pockeTrader. All rights reserved.<br>
                            </p>
                        </td>
                    </tr>

                </table>
                </td>
        </tr>
    </table>

</body>
</html>