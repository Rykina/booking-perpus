@extends('layouts.app')

@section('title', 'Lupa Kata Sandi - Perpustakaan & Galeri Kota Bogor')

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
            <h2 style="font-size: 1.35rem; font-weight: 800; margin: 0 0 4px 0; color: #ffffff;">Lupa Kata Sandi?</h2>
            <p style="font-size: 0.85rem; color: #e0e7ff; margin: 0;">Atur Ulang Kata Sandi Akun Anda</p>
            <div style="display: inline-block; margin-top: 10px; background: rgba(255,255,255,0.2); padding: 3px 12px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;">
                PEMULIHAN AKSES AKUN RESMI
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

            @if($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 12px; font-size: 0.86rem; margin-bottom: 20px;">
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('success'))
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 16px; border-radius: 12px; font-size: 0.86rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <p style="font-size: 0.86rem; color: #64748b; line-height: 1.5; margin: 0 0 20px 0;">
                Masukkan alamat email yang terdaftar pada akun Anda. Sistem akan mengirimkan <strong>6 digit kode OTP verifikasi</strong> ke email/Gmail Anda untuk mencegah penyadapan dan memastikan kepemilikan akun sebelum kata sandi dapat diubah.
            </p>

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                
                <!-- Email Input -->
                <div style="margin-bottom: 16px;">
                    <label for="reset_email" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Alamat Email Akun Terdaftar <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="email" name="email" id="reset_email" value="{{ old('email', $email ?? '') }}" required placeholder="nama@email.com"
                               style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; font-family: inherit;" autofocus>
                    </div>
                </div>

                <!-- Nomor Identitas (Opsional untuk Verifikasi Tambahan) -->
                <div style="margin-bottom: 20px;">
                    <label for="reset_nik" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Nomor Identitas (NIK / NIM) <span style="font-weight: 400; color: #94a3b8;">(Opsional)</span>
                    </label>
                    <div style="position: relative;">
                        <input type="text" name="identity_number" id="reset_nik" value="{{ old('identity_number') }}" placeholder="Contoh: 327101xxxxxx / 220101xxx"
                               style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; font-family: inherit;">
                    </div>
                    <span style="display: block; font-size: 0.75rem; color: #94a3b8; margin-top: 4px;">
                        Membantu memvalidasi kepemilikan akun identitas terdaftar Anda.
                    </span>
                </div>

                @if(config('services.recaptcha.site_key'))
                    <div style="margin-bottom: 20px; display: flex; flex-direction: column; align-items: center;">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        @error('g-recaptcha-response')
                            <span style="color: #dc2626; font-size: 0.82rem; font-weight: 600; margin-top: 6px; display: block; text-align: center;">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <button type="submit" 
                        class="btn btn-primary btn-block" 
                        style="width: 100%; padding: 13px; border-radius: 9999px; font-weight: 800; font-size: 0.94rem; background: #1a73e8; border-color: #1a73e8; color: #ffffff; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);">
                    Kirim Kode OTP ke Email &rarr;
                </button>
            </form>

            <!-- Tautan Kembali ke Login -->
            <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #f1f5f9; text-align: center;">
                <div style="font-size: 0.84rem; color: #64748b; margin-bottom: 6px;">
                    Sudah ingat kata sandi Anda?
                </div>
                <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
                    <a href="{{ route('pelanggan.login') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.86rem; font-weight: 700; color: #1a73e8; text-decoration: none;">
                        <span>Masuk Portal Pelanggan</span>
                    </a>
                    <span style="color: #cbd5e1;">|</span>
                    <a href="{{ route('admin.login') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.86rem; font-weight: 700; color: #0f172a; text-decoration: none;">
                        <span>Masuk Panel Pegawai</span>
                    </a>
                </div>
            </div>

            <!-- Back to Home -->
            <div style="margin-top: 16px; text-align: center;">
                <a href="{{ route('booking.index') }}" style="color: #94a3b8; font-size: 0.8rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Kembali ke Beranda Utama</span>
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
