@extends('layouts.app')

@section('title', 'Verifikasi Kode OTP - Perpustakaan & Galeri Kota Bogor')

@section('content')
<div class="auth-page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-card" style="max-width: 480px; width: 100%; background: #ffffff; border-radius: 22px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0; overflow: hidden;">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); padding: 32px 24px; text-align: center; color: #ffffff; position: relative;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; background: rgba(255, 255, 255, 0.18); border-radius: 18px; margin-bottom: 12px; backdrop-filter: blur(8px);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    <circle cx="12" cy="16" r="1.5"></circle>
                </svg>
            </div>
            <h2 style="font-size: 1.35rem; font-weight: 800; margin: 0 0 4px 0; color: #ffffff;">Verifikasi Kode OTP</h2>
            <p style="font-size: 0.85rem; color: #e0e7ff; margin: 0;">Periksa Kotak Masuk Email / Gmail Anda</p>
            <div style="display: inline-block; margin-top: 10px; background: rgba(255,255,255,0.2); padding: 3px 12px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;">
                AUTENTIKASI 2 LANGKAH (2FA)
            </div>
        </div>

        <!-- Body -->
        <div style="padding: 32px 28px;">
            
            @if(session('error'))
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 12px; font-size: 0.86rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 16px; border-radius: 12px; font-size: 0.86rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 12px; font-size: 0.86rem; margin-bottom: 20px;">
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Masked Email Info Banner -->
            @php
                $emailParts = explode('@', $email);
                $namePart = $emailParts[0] ?? '';
                $domainPart = $emailParts[1] ?? '';
                $len = strlen($namePart);
                if ($len <= 2) {
                    $maskedName = $namePart . '***';
                } else {
                    $maskedName = substr($namePart, 0, 2) . str_repeat('*', max(3, $len - 2));
                }
                $maskedEmail = $maskedName . '@' . $domainPart;
            @endphp

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px; margin-bottom: 24px; text-align: center;">
                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">Kami telah mengirimkan 6 digit kode OTP ke:</div>
                <div style="font-size: 0.96rem; font-weight: 700; color: #0f172a; word-break: break-all;">
                    {{ $maskedEmail }}
                </div>
                <div style="font-size: 0.76rem; color: #94a3b8; margin-top: 4px;">
                    Berlaku selama 10 menit. Cek folder Inbox atau Spam email Anda.
                </div>
            </div>

            @if(session('simulated_otp'))
                <!-- Simulasi Lokal Banner (Membantu jika SMTP Mailer Lokal belum dikonfigurasi) -->
                <div style="background: #fffbeb; border: 1px dashed #f59e0b; color: #92400e; padding: 12px 16px; border-radius: 12px; font-size: 0.82rem; margin-bottom: 22px; text-align: center;">
                    <span style="font-weight: 700;">Simulasi Mode Lokal (SMTP Dev):</span><br>
                    Kode OTP akun Anda adalah: <strong style="font-size: 1.15rem; letter-spacing: 2px; color: #b45309;">{{ session('simulated_otp') }}</strong>
                </div>
            @endif

            <!-- Form Verifikasi OTP -->
            <form action="{{ route('password.otp.verify') }}" method="POST" id="formVerifyOtp">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="otp" id="fullOtpInput" value="">

                <label style="display: block; font-size: 0.84rem; font-weight: 700; color: #334155; margin-bottom: 10px; text-align: center;">
                    Masukkan 6 Digit Kode OTP
                </label>

                <!-- 6 Digit Inputs Box -->
                <div style="display: flex; gap: 8px; justify-content: center; margin-bottom: 24px;" id="otpBoxesContainer">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" 
                               inputmode="numeric" 
                               pattern="[0-9]*" 
                               maxlength="1" 
                               class="otp-digit-box" 
                               data-index="{{ $i }}"
                               autocomplete="off"
                               style="width: 46px; height: 54px; text-align: center; font-size: 1.4rem; font-weight: 800; border-radius: 12px; border: 2px solid #cbd5e1; background: #ffffff; color: #0f172a; outline: none; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-sizing: border-box; font-family: monospace;">
                    @endfor
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        id="btnSubmitOtp"
                        class="btn btn-primary" 
                        style="width: 100%; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 0.92rem; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Verifikasi Kode OTP</span>
                </button>
            </form>

            <!-- Kirim Ulang OTP Section -->
            <div style="margin-top: 24px; text-align: center; padding-top: 18px; border-top: 1px solid #f1f5f9;">
                <p style="font-size: 0.82rem; color: #64748b; margin: 0 0 10px 0;">
                    Belum menerima kode OTP di Gmail?
                </p>

                <form action="{{ route('password.otp.resend') }}" method="POST" id="formResendOtp" style="display: inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" 
                            id="btnResendOtp" 
                            disabled 
                            style="background: none; border: none; font-size: 0.82rem; font-weight: 700; color: #94a3b8; cursor: not-allowed; transition: all 0.2s; padding: 4px 8px; border-radius: 8px;">
                        Kirim Ulang Kode (<span id="resendCountdown">60</span>s)
                    </button>
                </form>
            </div>

            <!-- Ganti Email / Kembali ke Login -->
            <div style="margin-top: 16px; text-align: center;">
                <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: #1a73e8; text-decoration: none; font-weight: 600;">
                    ← Gunakan Alamat Email Lain
                </a>
            </div>

            <div style="margin-top: 12px; text-align: center;">
                <a href="{{ route('pelanggan.login') }}" style="font-size: 0.78rem; color: #64748b; text-decoration: none;">
                    Kembali ke Halaman Masuk
                </a>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const boxes = document.querySelectorAll('.otp-digit-box');
    const fullOtpInput = document.getElementById('fullOtpInput');
    const form = document.getElementById('formVerifyOtp');
    const resendBtn = document.getElementById('btnResendOtp');
    const countdownSpan = document.getElementById('resendCountdown');

    // Auto-focus box pertama
    if (boxes.length > 0) {
        boxes[0].focus();
    }

    function updateFullOtp() {
        let otp = '';
        boxes.forEach(box => {
            otp += box.value.trim();
        });
        fullOtpInput.value = otp;
        return otp;
    }

    boxes.forEach((box, index) => {
        // Focus state styling
        box.addEventListener('focus', function () {
            this.style.borderColor = '#1a73e8';
            this.style.boxShadow = '0 0 0 4px rgba(26, 115, 232, 0.15)';
            this.select();
        });

        box.addEventListener('blur', function () {
            this.style.borderColor = '#cbd5e1';
            this.style.boxShadow = 'none';
        });

        // Input handler
        box.addEventListener('input', function (e) {
            const val = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = val ? val.charAt(0) : '';

            const otp = updateFullOtp();

            if (e.target.value && index < boxes.length - 1) {
                boxes[index + 1].focus();
            }

            // Auto submit jika 6 digit sudah terisi lengkap
            if (otp.length === 6) {
                form.submit();
            }
        });

        // Backspace handler
        box.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace') {
                if (!this.value && index > 0) {
                    boxes[index - 1].focus();
                    boxes[index - 1].value = '';
                    updateFullOtp();
                }
            } else if (e.key === 'ArrowLeft' && index > 0) {
                boxes[index - 1].focus();
            } else if (e.key === 'ArrowRight' && index < boxes.length - 1) {
                boxes[index + 1].focus();
            }
        });

        // Paste support
        box.addEventListener('paste', function (e) {
            e.preventDefault();
            const pastedData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            if (pastedData) {
                const chars = pastedData.slice(0, 6).split('');
                chars.forEach((char, i) => {
                    if (boxes[i]) {
                        boxes[i].value = char;
                    }
                });
                const otp = updateFullOtp();
                const nextIndex = Math.min(chars.length, boxes.length - 1);
                boxes[nextIndex].focus();

                if (otp.length === 6) {
                    form.submit();
                }
            }
        });
    });

    form.addEventListener('submit', function (e) {
        const otp = updateFullOtp();
        if (otp.length !== 6) {
            e.preventDefault();
            alert('Silakan masukkan 6 digit kode OTP dengan lengkap.');
            // Cari box pertama yang kosong
            for (let i = 0; i < boxes.length; i++) {
                if (!boxes[i].value) {
                    boxes[i].focus();
                    break;
                }
            }
        }
    });

    // Countdown 60 detik untuk Kirim Ulang OTP
    let timeLeft = 60;
    const timer = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(timer);
            resendBtn.disabled = false;
            resendBtn.style.color = '#1a73e8';
            resendBtn.style.cursor = 'pointer';
            resendBtn.textContent = 'Kirim Ulang Kode OTP';
        } else {
            countdownSpan.textContent = timeLeft;
        }
    }, 1000);
});
</script>
@endsection
