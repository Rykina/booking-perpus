@extends('layouts.app')

@section('title', 'Login Pelanggan - Perpustakaan & Galeri Kota Bogor')

@section('content')
<div class="auth-page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-card" style="max-width: 480px; width: 100%; background: #ffffff; border-radius: 22px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0; overflow: hidden;">
        
        <!-- Header Banner Pelanggan -->
        <div style="background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); padding: 32px 24px; text-align: center; color: #ffffff; position: relative;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; background: rgba(255, 255, 255, 0.18); border-radius: 18px; margin-bottom: 12px; backdrop-filter: blur(8px);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h2 style="font-size: 1.35rem; font-weight: 800; margin: 0 0 4px 0; color: #ffffff;">Portal Pelanggan</h2>
            <p style="font-size: 0.85rem; color: #e0e7ff; margin: 0;">Perpustakaan & Galeri Kota Bogor</p>
            <div style="display: inline-block; margin-top: 10px; background: rgba(255,255,255,0.2); padding: 3px 12px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;">
                LAYANAN RESERVASI & KONSULTASI MASYARAKAT
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

            <!-- Form Login Pelanggan dengan Email & Kata Sandi -->
            <form action="{{ route('pelanggan.login.submit') }}" method="POST">
                @csrf
                
                <div style="margin-bottom: 16px;">
                    <label for="customer_email" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Alamat Email
                    </label>
                    <div style="position: relative;">
                        <input type="email" name="email" id="customer_email" value="{{ old('email') }}" required placeholder="nama@gmail.com atau email terdaftar"
                               style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; font-family: inherit;">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label for="customer_password" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Kata Sandi
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="customer_password" required placeholder="Masukkan kata sandi akun"
                               style="width: 100%; padding: 11px 44px 11px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; font-family: inherit;">
                        <button type="button" onclick="togglePasswordVisibility('customer_password', this)" 
                                title="Lihat / Sembunyikan Sandi"
                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 4px; display: flex; align-items: center; justify-content: center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-open">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: #64748b; cursor: pointer;">
                        <input type="checkbox" name="remember" value="1" checked style="width: 16px; height: 16px; accent-color: #1a73e8;">
                        <span>Ingat sesi saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Google reCAPTCHA v2 -->
                <div style="margin-bottom: 20px; display: flex; flex-direction: column; align-items: center;">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    @error('g-recaptcha-response')
                        <span style="color: #dc2626; font-size: 0.82rem; font-weight: 600; margin-top: 6px; display: block; text-align: center;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" 
                        class="btn btn-primary btn-block" 
                        style="width: 100%; padding: 13px; border-radius: 9999px; font-weight: 800; font-size: 0.94rem; background: #1a73e8; border-color: #1a73e8; color: #ffffff; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);">
                    Masuk ke Portal Pelanggan &rarr;
                </button>
            </form>

            <!-- Divider: Atau masuk dengan Google -->
            <div style="display: flex; align-items: center; margin: 24px 0 20px 0; gap: 12px;">
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                <span style="font-size: 0.74rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Atau Masuk Lebih Cepat</span>
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            </div>

            <!-- Google Login Button -->
            <a href="{{ route('auth.google') }}" 
               id="btnGoogleOAuthLogin"
               style="display: flex; align-items: center; justify-content: center; gap: 12px; width: 100%; padding: 12px 20px; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 9999px; font-size: 0.92rem; font-weight: 700; color: #1e293b; text-decoration: none; box-shadow: 0 2px 6px rgba(0,0,0,0.03); transition: all 0.2s ease; box-sizing: border-box;">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.8-2.4 3.65v3.03h3.88c2.27-2.09 3.665-5.17 3.665-9.12z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.03c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.13C3.28 21.44 7.33 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.28 14.29c-.25-.72-.38-1.49-.38-2.29s.13-1.57.38-2.29V6.58H1.25C.45 8.18 0 9.98 0 12s.45 3.82 1.25 5.42l4.03-3.13z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.28 2.56 1.25 6.58l4.03 3.13c.95-2.83 3.6-4.96 6.72-4.96z"/>
                </svg>
                <span>Masuk dengan Google</span>
            </a>

            <!-- Info Keamanan -->
            <div style="margin-top: 20px; padding: 12px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: flex-start; gap: 10px; color: #64748b; font-size: 0.78rem; line-height: 1.45;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" style="flex-shrink: 0; margin-top: 1px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                <span>Portal resmi reservasi fasilitas dan videotron Perpustakaan & Galeri Kota Bogor. Sesi Anda terlindungi dengan enkripsi aman.</span>
            </div>

            <!-- Tautan ke Halaman Registrasi Pelanggan -->
            <div style="margin-top: 22px; padding-top: 18px; border-top: 1px solid #f1f5f9; text-align: center;">
                <div style="font-size: 0.84rem; color: #64748b; margin-bottom: 6px;">
                    Belum memiliki akun pelanggan?
                </div>
                <a href="{{ route('pelanggan.register') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.88rem; font-weight: 700; color: #1a73e8; text-decoration: none;">
                    <span>Daftar Akun Baru Sekarang</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
            </div>

            <!-- Tautan ke Portal Pegawai -->
            <div style="margin-top: 14px; text-align: center;">
                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 4px;">
                    Pegawai / Staf Dinas Perpustakaan?
                </div>
                <a href="{{ route('admin.login') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 700; color: #0f172a; text-decoration: none;">
                    <span>Masuk ke Panel Pegawai Dinas</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </a>
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

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const openIcon = btn.querySelector('.eye-open');
        const closedIcon = btn.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            if (openIcon) openIcon.style.display = 'none';
            if (closedIcon) closedIcon.style.display = 'inline';
        } else {
            input.type = 'password';
            if (openIcon) openIcon.style.display = 'inline';
            if (closedIcon) closedIcon.style.display = 'none';
        }
    }
</script>
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endsection
