<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\Recaptcha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan formulir permintaan Lupa Kata Sandi (Input Email).
     */
    public function showForgotForm(Request $request)
    {
        $email = $request->query('email', '');
        return view('auth.forgot-password', compact('email'));
    }

    /**
     * Proses permohonan reset sandi: Generate Kode OTP 6-Digit & Kirim ke Gmail User.
     */
    public function sendOtp(Request $request)
    {
        $recaptchaRules = app()->environment('testing')
            ? ['nullable', new Recaptcha()]
            : ['nullable', new Recaptcha()];

        $request->validate([
            'email'                => 'required|email',
            'identity_number'      => 'nullable|string|max:50',
            'g-recaptcha-response' => $recaptchaRules,
        ], [
            'email.required' => 'Alamat email akun Anda wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
        ]);

        $email = strtolower(trim($request->email));
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withInput($request->only('email', 'identity_number'))
                ->withErrors(['email' => 'Alamat email tersebut tidak ditemukan di dalam sistem kami. Pastikan Anda memasukkan email yang terdaftar.']);
        }

        // Verifikasi kecocokan nomor identitas jika diisi
        if (!empty($request->identity_number) && !empty($user->identity_number)) {
            $inputNik = preg_replace('/[^a-zA-Z0-9]/', '', $request->identity_number);
            $userNik = preg_replace('/[^a-zA-Z0-9]/', '', $user->identity_number);
            if ($inputNik !== $userNik) {
                return back()->withInput($request->only('email', 'identity_number'))
                    ->withErrors(['identity_number' => 'Nomor Identitas tidak cocok dengan data akun terdaftar.']);
            }
        }

        // Generate 6 Digit Numeric OTP
        $otp = sprintf('%06d', random_int(100000, 999999));

        // Simpan / update ke database dengan Hash
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token'      => Hash::make($otp),
                'created_at' => Carbon::now(),
            ]
        );

        // Kirim email OTP HTML ke Gmail pengguna
        $mailSent = false;
        try {
            $emailHtml = view('emails.otp', [
                'user' => $user,
                'otp'  => $otp,
            ])->render();

            Mail::html($emailHtml, function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('[KODE OTP] Verifikasi Reset Kata Sandi - Perpustakaan & Galeri Kota Bogor');
            });
            $mailSent = true;
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim email OTP ke {$user->email}: " . $e->getMessage());
        }

        // Simpan email ke session
        session(['reset_email' => $user->email]);

        $redirect = redirect()->route('password.otp', ['email' => $user->email])
            ->with('success', 'Kode OTP 6-digit telah dikirimkan ke email ' . $user->email . '. Silakan periksa kotak masuk atau folder spam.');

        // Jika mailer offline / gagal (misal pengujian lokal belum menyalakan SMTP), sediakan simulasi
        if (!$mailSent) {
            $redirect->with('simulated_otp', $otp);
        }

        return $redirect;
    }

    /**
     * Tampilkan formulir input 6-Digit Kode OTP.
     */
    public function showOtpForm(Request $request)
    {
        $email = $request->query('email', session('reset_email'));

        if (!$email) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi tidak ditemukan. Silakan masukkan alamat email akun Anda.');
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return redirect()->route('password.request')
                ->with('error', 'Permintaan reset kata sandi tidak ditemukan atau sudah kedaluwarsa. Silakan ajukan ulang.');
        }

        return view('auth.verify-otp', compact('email'));
    }

    /**
     * Kirim Ulang Kode OTP (Resend OTP).
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower(trim($request->email));
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'Akun tidak ditemukan.');
        }

        // Generate OTP baru
        $otp = sprintf('%06d', random_int(100000, 999999));

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token'      => Hash::make($otp),
                'created_at' => Carbon::now(),
            ]
        );

        $mailSent = false;
        try {
            $emailHtml = view('emails.otp', [
                'user' => $user,
                'otp'  => $otp,
            ])->render();

            Mail::html($emailHtml, function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('[KODE OTP] Verifikasi Reset Kata Sandi Baru - Perpustakaan & Galeri Kota Bogor');
            });
            $mailSent = true;
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim ulang email OTP ke {$user->email}: " . $e->getMessage());
        }

        $redirect = back()->with('success', 'Kode OTP baru telah berhasil dikirimkan ke email Anda.');

        if (!$mailSent) {
            $redirect->with('simulated_otp', $otp);
        }

        return $redirect;
    }

    /**
     * Verifikasi kecocokan Kode OTP yang dimasukkan oleh pengguna.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'otp.required'   => 'Kode OTP wajib diisi.',
            'otp.digits'     => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        $email = strtolower(trim($request->email));

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record) {
            return redirect()->route('password.request')
                ->with('error', 'Permintaan reset tidak ditemukan. Silakan ajukan ulang.');
        }

        // Cek kedaluwarsa OTP (10 menit)
        if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) {
            return back()->withInput($request->only('email'))
                ->withErrors(['otp' => 'Kode OTP telah kedaluwarsa (berlaku 10 menit). Silakan klik tombol "Kirim Ulang Kode".']);
        }

        // Verifikasi kecocokan OTP
        if (!Hash::check($request->otp, $record->token)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah atau tidak sesuai. Periksa kembali kotak masuk Gmail Anda.']);
        }

        // OTP Terverifikasi! Buat token reset rahasia untuk halaman buat sandi baru
        $resetToken = Str::random(64);

        DB::table('password_reset_tokens')->where('email', $email)->update([
            'token'      => Hash::make($resetToken),
            'created_at' => Carbon::now(),
        ]);

        // Berikan sesi otorisasi bahwa email ini telah lolos 2FA OTP
        session([
            'otp_verified_email' => $email,
            'reset_token'        => $resetToken,
        ]);

        return redirect()->route('password.reset', ['token' => $resetToken, 'email' => $email])
            ->with('success', 'Kode OTP berhasil diverifikasi! Silakan tentukan kata sandi baru akun Anda.');
    }

    /**
     * Tampilkan formulir pembuatan Kata Sandi Baru setelah OTP lolos.
     */
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('password.request')
                ->with('error', 'Tautan pengaturan ulang kata sandi tidak valid atau parameter email tidak ditemukan.');
        }

        // Pastikan pengguna telah melewati tahap verifikasi OTP
        if (session('otp_verified_email') !== $email) {
            return redirect()->route('password.request')
                ->with('error', 'Akses ditolak. Anda harus memverifikasi kode OTP yang dikirimkan ke Gmail terlebih dahulu demi keamanan akun.');
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record || !Hash::check($token, $record->token)) {
            return redirect()->route('password.request')
                ->with('error', 'Token reset tidak valid atau sudah pernah digunakan.');
        }

        // Cek kedaluwarsa token reset (maksimal 60 menit)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.request')
                ->with('error', 'Sesi reset kata sandi telah kedaluwarsa. Silakan lakukan verifikasi OTP ulang.');
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Simpan kata sandi baru yang telah ditentukan oleh pengguna.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token'    => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.required'     => 'Alamat email wajib diisi.',
            'password.required'  => 'Kata sandi baru wajib diisi.',
            'password.min'       => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $email = strtolower(trim($request->email));

        // Pastikan sesi OTP cocok
        if (session('otp_verified_email') !== $email) {
            return redirect()->route('password.request')
                ->with('error', 'Akses ditolak. Verifikasi OTP tidak ditemukan atau telah kedaluwarsa.');
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Token reset kata sandi tidak sah atau sudah kedaluwarsa.']);
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.request')
                ->with('error', 'Sesi reset telah kedaluwarsa. Silakan ajukan permohonan baru.');
        }

        /** @var \App\Models\User $user */
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'Pengguna tidak ditemukan.');
        }

        // Update kata sandi pengguna
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Hapus token yang sudah digunakan
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Hapus status sesi
        session()->forget(['otp_verified_email', 'reset_token', 'reset_email']);

        // Arahkan ke login sesuai role
        $loginRoute = $user->isAdmin() ? 'admin.login' : 'pelanggan.login';

        return redirect()->route($loginRoute)
            ->with('success', 'Kata sandi akun Anda berhasil diperbarui! Silakan masuk dengan kata sandi baru.');
    }
}
