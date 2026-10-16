@extends('layouts.app')

@section('title', 'Login Akun - Perpustakaan & Galeri Kota Bogor')

@section('content')
<div class="auth-page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-card" style="max-width: 480px; width: 100%; background: #ffffff; border-radius: 20px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0; overflow: hidden;">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); padding: 30px 24px; text-align: center; color: #ffffff; position: relative;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 54px; height: 54px; background: rgba(255, 255, 255, 0.15); border-radius: 16px; margin-bottom: 12px; backdrop-filter: blur(8px);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18"></path>
                    <path d="M5 21V10"></path>
                    <path d="M19 21V10"></path>
                    <path d="M9 21V10"></path>
                    <path d="M15 21V10"></path>
                    <path d="M2 10l10-6 10 6"></path>
                </svg>
            </div>
            <h2 style="font-size: 1.3rem; font-weight: 800; margin: 0 0 4px 0;">Perpustakaan & Galeri Kota Bogor</h2>
            <p style="font-size: 0.84rem; color: #e0e7ff; margin: 0;">Portal Layanan Reservasi & Panel Pegawai</p>
        </div>

        <!-- Body -->
        <div style="padding: 28px 28px;">
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

            <!-- Switcher Tabs -->
            <div style="display: flex; background: #f1f5f9; padding: 4px; border-radius: 9999px; margin-bottom: 24px;">
                <button type="button" id="tabBtnGoogle" onclick="switchLoginTab('google')" 
                        style="flex: 1; padding: 8px 12px; border-radius: 9999px; border: none; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.2s ease; background: #ffffff; color: #0f172a; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                    Pelanggan (Google)
                </button>
                <button type="button" id="tabBtnStaff" onclick="switchLoginTab('staff')" 
                        style="flex: 1; padding: 8px 12px; border-radius: 9999px; border: none; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.2s ease; background: transparent; color: #64748b;">
                    Pegawai / Admin
                </button>
            </div>

            <!-- TAB 1: Pelanggan (Google OAuth) -->
            <div id="loginSectionGoogle">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Masuk dengan Akun Google</h3>
                    <p style="font-size: 0.84rem; color: #64748b; line-height: 1.45; margin: 0;">
                        Masuk instan untuk mengajukan permohonan fasilitas, melacak status, dan berkonsultasi via chat.
                    </p>
                </div>

                <!-- Google Login Button -->
                <a href="{{ route('auth.google') }}" 
                   id="btnGoogleOAuthLogin"
                   style="display: flex; align-items: center; justify-content: center; gap: 12px; width: 100%; padding: 12px 20px; background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 9999px; font-size: 0.92rem; font-weight: 700; color: #1e293b; text-decoration: none; box-shadow: 0 2px 6px rgba(0,0,0,0.04); transition: all 0.2s ease; box-sizing: border-box;">
                    <svg width="20" height="20" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.8-2.4 3.65v3.03h3.88c2.27-2.09 3.665-5.17 3.665-9.12z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.03c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.13C3.28 21.44 7.33 24 12 24z"/>
                        <path fill="#FBBC05" d="M5.28 14.29c-.25-.72-.38-1.49-.38-2.29s.13-1.57.38-2.29V6.58H1.25C.45 8.18 0 9.98 0 12s.45 3.82 1.25 5.42l4.03-3.13z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.28 2.56 1.25 6.58l4.03 3.13c.95-2.83 3.6-4.96 6.72-4.96z"/>
                    </svg>
                    <span>Masuk dengan Google</span>
                </a>

                <div style="margin-top: 20px; padding-top: 18px; border-top: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 10px; color: #64748b; font-size: 0.76rem; line-height: 1.45;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" style="flex-shrink: 0; margin-top: 1px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <span>Autentikasi resmi dan terenkripsi melalui Google OAuth 2.0. Kami tidak pernah meminta kata sandi akun Google Anda.</span>
                </div>
            </div>

            <!-- TAB 2: Pegawai / Admin (Email & Password) -->
            <div id="loginSectionStaff" style="display: none;">
                <div style="text-align: center; margin-bottom: 18px;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Login Pegawai & Petugas</h3>
                    <p style="font-size: 0.82rem; color: #64748b; margin: 0;">Gunakan kredensial kedinasan resmi Anda.</p>
                </div>

                <form action="{{ route('login.email') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #334155; margin-bottom: 4px;">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', 'admin@kotabogor.go.id') }}" required placeholder="nama@kotabogor.go.id"
                               style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 18px;">
                        <label style="display: block; font-size: 0.78rem; font-weight: 700; color: #334155; margin-bottom: 4px;">Kata Sandi</label>
                        <input type="password" name="password" required value="password" placeholder="••••••••"
                               style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box;">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="width: 100%; padding: 11px; border-radius: 9999px; font-weight: 700; font-size: 0.9rem;">
                        Masuk ke Panel Pegawai
                    </button>
                </form>

                <div style="margin-top: 14px; text-align: center; font-size: 0.75rem; color: #94a3b8;">
                    Kredensial Default Pegawai: <code>admin@kotabogor.go.id</code> / <code>password</code>
                </div>
            </div>

            <!-- Back to home -->
            <div style="margin-top: 20px; text-align: center;">
                <a href="{{ route('booking.index') }}" style="color: #64748b; font-size: 0.82rem; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    function switchLoginTab(tab) {
        const secGoogle = document.getElementById('loginSectionGoogle');
        const secStaff = document.getElementById('loginSectionStaff');
        const btnGoogle = document.getElementById('tabBtnGoogle');
        const btnStaff = document.getElementById('tabBtnStaff');

        if (tab === 'google') {
            secGoogle.style.display = 'block';
            secStaff.style.display = 'none';
            btnGoogle.style.background = '#ffffff';
            btnGoogle.style.color = '#0f172a';
            btnGoogle.style.boxShadow = '0 1px 3px rgba(0,0,0,0.08)';
            btnStaff.style.background = 'transparent';
            btnStaff.style.color = '#64748b';
            btnStaff.style.boxShadow = 'none';
        } else {
            secGoogle.style.display = 'none';
            secStaff.style.display = 'block';
            btnStaff.style.background = '#ffffff';
            btnStaff.style.color = '#0f172a';
            btnStaff.style.boxShadow = '0 1px 3px rgba(0,0,0,0.08)';
            btnGoogle.style.background = 'transparent';
            btnGoogle.style.color = '#64748b';
            btnGoogle.style.boxShadow = 'none';
        }
    }
</script>
@endsection
