<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi Reset Kata Sandi</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #334155;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9; padding: 40px 15px;">
        <tr>
            <td align="center">
                <!-- Card Container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 520px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); padding: 32px 24px; text-align: center; color: #ffffff;">
                            <div style="font-size: 28px; margin-bottom: 8px;">🔐</div>
                            <h1 style="margin: 0; font-size: 20px; font-weight: 800; letter-spacing: -0.3px; color: #ffffff;">
                                Perpustakaan & Galeri Kota Bogor
                            </h1>
                            <p style="margin: 6px 0 0 0; font-size: 13px; color: #e0e7ff; font-weight: 500;">
                                Sistem Layanan Reservasi & Fasilitas Publik
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            <p style="margin: 0 0 16px 0; font-size: 15px; font-weight: 600; color: #0f172a;">
                                Halo, {{ $user->name }}
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; color: #475569;">
                                Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Untuk melindungi akun Anda dari akses yang tidak sah, gunakan 6 digit kode OTP verifikasi berikut:
                            </p>

                            <!-- OTP Box -->
                            <div style="background: #f0f7ff; border: 2px dashed #93c5fd; border-radius: 14px; padding: 22px; text-align: center; margin: 24px 0;">
                                <div style="font-size: 12px; font-weight: 700; color: #1d4ed8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">
                                    Kode OTP Verifikasi Anda
                                </div>
                                <div style="font-family: 'Courier New', Courier, monospace; font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #1a73e8; margin: 6px 0;">
                                    {{ $otp }}
                                </div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 6px;">
                                    Berlaku selama <strong>10 menit</strong>
                                </div>
                            </div>

                            <!-- Security Warnings -->
                            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 14px 16px; margin: 22px 0;">
                                <div style="display: flex; align-items: flex-start; gap: 8px;">
                                    <span style="font-size: 16px;">⚠️</span>
                                    <div style="font-size: 12.5px; line-height: 1.5; color: #991b1b;">
                                        <strong>Peringatan Keamanan:</strong> Jangan pernah memberikan kode OTP ini kepada siapa pun, termasuk pihak yang mengaku sebagai petugas perpustakaan atau admin sistem.
                                    </div>
                                </div>
                            </div>

                            <p style="margin: 20px 0 0 0; font-size: 13px; line-height: 1.5; color: #64748b;">
                                Jika Anda tidak pernah meminta pengaturan ulang kata sandi ini, abaikan email ini. Akun Anda tetap aman dan kata sandi Anda tidak akan berubah.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 28px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                                Email ini dikirim secara otomatis oleh Sistem Otomasi Perpustakaan & Galeri Kota Bogor.<br>
                                Jl. Kapten Muslihat No. 21, Kota Bogor, Jawa Barat.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
