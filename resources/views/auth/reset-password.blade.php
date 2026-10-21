@extends('layouts.app')

@section('title', 'Buat Kata Sandi Baru - Perpustakaan & Galeri Kota Bogor')

@section('content')
<div class="auth-page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div class="auth-card" style="max-width: 480px; width: 100%; background: #ffffff; border-radius: 22px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0; overflow: hidden;">
        
        <!-- Header Banner -->
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 32px 24px; text-align: center; color: #ffffff; position: relative;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; background: rgba(255, 255, 255, 0.18); border-radius: 18px; margin-bottom: 12px; backdrop-filter: blur(8px);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <polyline points="9 12 11 14 15 10"></polyline>
                </svg>
            </div>
            <h2 style="font-size: 1.35rem; font-weight: 800; margin: 0 0 4px 0; color: #ffffff;">Kata Sandi Baru</h2>
            <p style="font-size: 0.85rem; color: #d1fae5; margin: 0;">Buat Kata Sandi Baru yang Aman</p>
            <div style="display: inline-block; margin-top: 10px; background: rgba(255,255,255,0.2); padding: 3px 12px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;">
                LANGKAH TERAKHIR PEMULIHAN
            </div>
        </div>

        <!-- Body -->
        <div style="padding: 32px 28px;">
            @if(session('success'))
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 16px; border-radius: 12px; font-size: 0.86rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

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

            <!-- Info Akun yang Direset -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 1.1rem;">👤</span>
                    <div>
                        <div style="font-size: 0.74rem; color: #64748b; font-weight: 600;">Mengatur Ulang Akun:</div>
                        <div style="font-size: 0.88rem; color: #0f172a; font-weight: 800;">{{ $email }}</div>
                    </div>
                </div>
                <span style="background: #dcfce7; color: #166534; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 9999px;">OTP Terverifikasi ✓</span>
            </div>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Kata Sandi Baru -->
                <div style="margin-bottom: 16px;">
                    <label for="new_password" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Kata Sandi Baru <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="new_password" required minlength="6" placeholder="Minimal 6 karakter"
                               style="width: 100%; padding: 11px 44px 11px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; font-family: inherit;" autofocus>
                        <button type="button" onclick="togglePasswordVisibility('new_password', this)" 
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

                <!-- Konfirmasi Kata Sandi Baru -->
                <div style="margin-bottom: 24px;">
                    <label for="new_password_confirmation" style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                        Konfirmasi Kata Sandi Baru <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="new_password_confirmation" required minlength="6" placeholder="Ketik ulang kata sandi baru Anda"
                               style="width: 100%; padding: 11px 44px 11px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.9rem; box-sizing: border-box; font-family: inherit;">
                        <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)" 
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

                <button type="submit" 
                        class="btn btn-primary btn-block" 
                        style="width: 100%; padding: 13px; border-radius: 9999px; font-weight: 800; font-size: 0.94rem; background: #10b981; border-color: #10b981; color: #ffffff; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                    Simpan Kata Sandi Baru &rarr;
                </button>
            </form>

            <!-- Tautan Kembali -->
            <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #f1f5f9; text-align: center;">
                <a href="{{ route('pelanggan.login') }}" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.86rem; font-weight: 700; color: #1a73e8; text-decoration: none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    <span>Kembali ke Halaman Login</span>
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        if (eyeOpen && eyeClosed) {
            eyeOpen.style.display = isPassword ? 'none' : 'block';
            eyeClosed.style.display = isPassword ? 'block' : 'none';
        }
    }
</script>
@endsection
