<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\Recaptcha;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Tampilkan halaman login untuk Pelanggan (Google OAuth).
     */
    public function showCustomerLoginForm()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            return $user->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('pelanggan.dashboard');
        }

        return view('auth.pelanggan-login');
    }

    /**
     * Login Pelanggan menggunakan Email & Kata Sandi.
     */
    public function customerEmailLogin(Request $request)
    {
        $recaptchaRules = app()->environment('testing')
            ? ['nullable', new Recaptcha()]
            : ['required', new Recaptcha()];

        $request->validate([
            'email'                => 'required|email',
            'password'             => 'required|string',
            'g-recaptcha-response' => $recaptchaRules,
        ], [
            'email.required'                => 'Email wajib diisi.',
            'password.required'             => 'Password wajib diisi.',
            'g-recaptcha-response.required' => 'Silakan centang verifikasi reCAPTCHA terlebih dahulu.',
        ]);

        $credentials = [
            'email'    => strtolower(trim($request->email)),
            'password' => $request->password,
        ];
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $request->session()->regenerate();

            // Jika yang login adalah admin/super admin/petugas, arahkan ke dashboard admin
            if ($user->isAdmin()) {
                $roleTitle = $user->isSuperAdmin() ? 'Super Admin' : ($user->role === 'petugas' ? 'Petugas' : 'Admin');
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat bertugas, ' . $user->name . ' (' . $roleTitle . ')!');
            }

            // Jika pelanggan, pastikan intended bukan rute admin
            $intended = session()->get('url.intended');
            if ($intended && !str_contains($intended, '/admin')) {
                return redirect()->intended(route('pelanggan.dashboard'))
                    ->with('success', 'Selamat datang, ' . $user->name . '! Anda berhasil masuk ke Portal Pelanggan.');
            }

            session()->forget('url.intended');
            return redirect()->route('pelanggan.dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '! Anda berhasil masuk ke Portal Pelanggan.');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau kata sandi tidak cocok dengan data kami.']);
    }

    /**
     * Tampilkan formulir pendaftaran akun untuk Pelanggan.
     */
    public function showCustomerRegisterForm()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user) {
            return $user->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('pelanggan.dashboard');
        }

        return view('auth.pelanggan-register');
    }

    /**
     * Proses pendaftaran akun Pelanggan baru (Hanya role Pelanggan/Customer).
     */
    public function customerRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'identity_number' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:30',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan masuk menggunakan akun Anda.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Proteksi: Role selalu dikunci sebagai 'customer' (hanya pelanggan)
        $user = User::create([
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'identity_number' => !empty($validated['identity_number']) ? trim($validated['identity_number']) : null,
            'phone_number' => !empty($validated['phone_number']) ? trim($validated['phone_number']) : null,
            'role' => 'customer',
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        // Otomatis login akun pelanggan yang baru mendaftar
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pelanggan.dashboard')
            ->with('success', 'Selamat datang, ' . $user->name . '! Akun pelanggan Anda berhasil didaftarkan.');
    }

    /**
     * Tampilkan halaman login resmi untuk Pegawai & Administrator.
     */
    public function showAdminLoginForm()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Jika belum login atau sedang login sebagai pelanggan, tetap tampilkan form login admin
        return view('auth.admin-login');
    }

    /**
     * Tampilkan halaman login standar (mengarahkan ke login pelanggan).
     */
    public function showLoginForm()
    {
        return $this->showCustomerLoginForm();
    }

    /**
     * Login menggunakan Email & Password (khususnya untuk Pegawai / Admin).
     */
    public function emailLogin(Request $request)
    {
        $recaptchaRules = app()->environment('testing')
            ? ['nullable', new Recaptcha()]
            : ['required', new Recaptcha()];

        $request->validate([
            'email'                => 'required|email',
            'password'             => 'required|string',
            'g-recaptcha-response' => $recaptchaRules,
        ], [
            'email.required'                => 'Email wajib diisi.',
            'password.required'             => 'Password wajib diisi.',
            'g-recaptcha-response.required' => 'Silakan centang verifikasi reCAPTCHA terlebih dahulu.',
        ]);

        $credentials = [
            'email'    => strtolower(trim($request->email)),
            'password' => $request->password,
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Pelanggan / Customer dilarang masuk melalui form login admin
            if (!$user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Akses ditolak. Halaman login ini khusus untuk Pegawai & Administrator. Pelanggan silakan masuk melalui Portal Pelanggan.']);
            }

            $request->session()->regenerate();
            $roleTitle = $user->isSuperAdmin() ? 'Super Admin' : ($user->role === 'petugas' ? 'Petugas' : 'Admin');

            $intended = session()->get('url.intended');
            if ($intended && str_contains($intended, '/admin')) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat bertugas, ' . $user->name . ' (' . $roleTitle . ')!');
            }

            session()->forget('url.intended');
            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat bertugas, ' . $user->name . ' (' . $roleTitle . ')!');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau kata sandi tidak cocok dengan data kami.']);
    }

    /**
     * Mengarahkan pengguna ke halaman autentikasi Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani callback autentikasi dari Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        // Tangani jika pengguna membatalkan persetujuan di halaman Google
        if ($request->has('error')) {
            Log::warning('Google OAuth dibatalkan oleh pengguna atau gagal:', [
                'error' => $request->get('error'),
                'error_description' => $request->get('error_description'),
            ]);

            return redirect()->route('login')->with('error', 'Login dengan Google dibatalkan atau tidak diizinkan.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            Log::error('Google OAuth Callback Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')->with('error', 'Gagal menghubungkan ke layanan Google. Silakan coba kembali.');
        }

        if (!$googleUser || empty($googleUser->getEmail())) {
            return redirect()->route('login')->with('error', 'Identitas akun Google tidak valid atau alamat email tidak ditemukan.');
        }

        // 1. Cari pengguna berdasarkan google_id terlebih dahulu
        $user = User::where('google_id', $googleUser->getId())->first();

        // 2. Jika belum ditemukan dengan google_id, cari berdasarkan email terverifikasi
        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Pengguna sudah pernah terdaftar, tautkan google_id dan perbarui avatar
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $user->avatar ?: $googleUser->getAvatar(),
                    'email_verified_at' => $user->email_verified_at ?: now(),
                ]);
            } else {
                // Pengguna baru: Buat akun baru dengan role default customer
                $user = User::create([
                    'name' => $googleUser->getName() ?: 'Pengguna Google',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'customer',
                    'email_verified_at' => now(),
                    'password' => null,
                ]);
            }
        } else {
            // Perbarui foto profil jika tersedia
            if ($googleUser->getAvatar() && $user->avatar !== $googleUser->getAvatar()) {
                $user->update([
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }
        }

        // Login pengguna dan amankan session
        Auth::login($user, true);
        $request->session()->regenerate();

        // Arahkan sesuai role
        if ($user->isAdmin()) {
            $roleTitle = $user->isSuperAdmin() ? 'Super Admin' : ($user->role === 'petugas' ? 'Petugas' : 'Admin');
            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang kembali, ' . $user->name . ' (' . $roleTitle . ')!');
        }

        $intended = session()->get('url.intended');
        if ($intended && !str_contains($intended, '/admin')) {
            return redirect()->intended(route('pelanggan.dashboard'))
                ->with('success', 'Selamat datang, ' . $user->name . '! Anda berhasil masuk ke Portal Pelanggan.');
        }

        session()->forget('url.intended');
        return redirect()->route('pelanggan.dashboard')
            ->with('success', 'Selamat datang, ' . $user->name . '! Anda berhasil masuk ke Portal Pelanggan.');
    }

    /**
     * Proses logout khusus untuk Pegawai & Admin dari Panel Admin.
     */
    public function adminLogout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar dari Panel Admin.');
    }

    /**
     * Proses logout pengguna secara aman (Pelanggan).
     */
    public function logout(Request $request)
    {
        $wasAdmin = Auth::check() && Auth::user()->isAdmin();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($wasAdmin) {
            return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout dari Panel Pegawai.');
        }

        return redirect()->route('booking.index')->with('success', 'Anda telah berhasil logout.');
    }
}

