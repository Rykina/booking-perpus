@extends('layouts.app')

@section('title', 'Login Pegawai & Admin - Perpustakaan & Galeri Kota Bogor')

@section('content')
<div class="auth-page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-card" style="max-width: 480px; width: 100%; background: #ffffff; border-radius: 22px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0; overflow: hidden;">
        
        <!-- Header Banner Pegawai / Staf Dinas -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 32px 24px; text-align: center; color: #ffffff; position: relative;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; background: rgba(255, 255, 255, 0.12); border-radius: 18px; margin-bottom: 12px; border: 1px solid rgba(255,255,255,0.2);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h2 style="font-size: 1.35rem; font-weight: 800; margin: 0 0 4px 0; color: #ffffff;">Panel Pegawai & Petugas (Administrator)</h2>
            <p style="font-size: 0.82rem; color: #94a3b8; margin: 0;">Dinas Kearsipan dan Perpustakaan Kota Bogor</p>
            <div style="display: inline-block; margin-top: 10px; background: rgba(59, 130, 246, 0.25); color: #93c5fd; padding: 3px 12px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;">
                AKSES KHUSUS SUPER ADMIN & ADMIN
            </div>
        </div>

        <!-- Body Form Login Pegawai -->
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

            @if(Auth::check() && !Auth::user()->isAdmin())
                <div style="background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; font-size: 0.84rem; color: #1e40af; display: flex; align-items: flex-start; gap: 10px;">
                    <span style="font-size: 1.1rem; line-height: 1;">ℹ️</span>
                    <div style="line-height: 1.45;">
                        Saat ini Anda sedang terhubung dengan akun Pelanggan (<strong>{{ Auth::user()->name }}</strong>).<br>
                        Silakan masukkan akun pegawai resmi di bawah ini untuk beralih ke <strong>Panel Admin</strong>.
                    </div>
                </div>
            @endif

            <form action="{{ route('login.email') }}" method="POST">
                @csrf
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Email Kedinasan Resmi
                    </label>
                    <div style="position: relative;">
                        <input type="email" name="email" id="admin_email" value="{{ old('email') }}" required placeholder="nama@kotabogor.go.id atau email admin"
                               style="width: 100%; padding: 11px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; font-family: inherit;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="font-size: 0.82rem; font-weight: 700; color: #334155;">Kata Sandi</label>
                    </div>
                    <div style="position: relative;">
                        <input type="password" name="password" id="admin_password" required value="" placeholder="Masukkan kata sandi"
                               style="width: 100%; padding: 11px 44px 11px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; font-family: inherit;">
                        <button type="button" onclick="togglePasswordVisibility('admin_password', this)" 
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
                        style="width: 100%; padding: 13px; border-radius: 9999px; font-weight: 800; font-size: 0.92rem; background: #0f172a; border-color: #0f172a; color: #ffffff; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.25);">
                    Masuk ke Panel Pegawai &rarr;
                </button>
            </form>

            <!-- Tautan ke Portal Pelanggan -->
            <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9; text-align: center;">
                <div style="font-size: 0.82rem; color: #64748b; margin-bottom: 6px;">
                    Anda adalah Pelanggan / Masyarakat Umum?
                </div>
                <a href="{{ route('pelanggan.login') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.86rem; font-weight: 700; color: #1a73e8; text-decoration: none;">
                    <span>Masuk Lewat Portal Pelanggan (Google)</span>
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

