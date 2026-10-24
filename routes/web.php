<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Peminjaman Ruangan & Layar Iklan Perpustakaan Bogor
|--------------------------------------------------------------------------
*/

// 1. Rute Beranda Utama Publik
Route::get('/beranda', [BookingController::class, 'index'])->name('booking.index');

// Redirect root / langsung ke /beranda
Route::get('/', function () {
    return redirect()->route('booking.index');
});

// Form Submit Peminjaman
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Cek Status Peminjaman dengan Kode Booking
Route::get('/cek-booking', [BookingController::class, 'checkStatus'])->name('booking.check');

// Cetak Surat Bukti Reservasi Resmi
Route::get('/booking/cetak/{code}', [BookingController::class, 'printProof'])->name('booking.print');

// Halaman Riwayat Pemesanan / Pelacakan Booking
Route::get('/riwayat', [BookingController::class, 'history'])->name('booking.history');

// API Riwayat Pemesanan (Pencarian & LocalStorage Sync)
Route::match(['get', 'post'], '/api/booking-history', [BookingController::class, 'apiHistory'])->name('api.booking.history');

// API Notifikasi Persetujuan Reservasi Pelanggan (Jendela Notifikasi)
Route::match(['get', 'post'], '/api/customer-notifications', [BookingController::class, 'customerNotifications'])->name('api.customer.notifications');

// API Cek Jadwal Slot Ruangan
Route::get('/api/room-slots', [BookingController::class, 'getRoomSlots'])->name('api.room.slots');

/*
|--------------------------------------------------------------------------
| Autentikasi Terpisah: Pelanggan (Google) & Pegawai (Email/Sandi)
|--------------------------------------------------------------------------
*/
// 1. Rute Pelanggan (Email & Sandi serta Google OAuth)
Route::get('/pelanggan/login', [GoogleController::class, 'showCustomerLoginForm'])->name('pelanggan.login');
Route::post('/pelanggan/login', [GoogleController::class, 'customerEmailLogin'])->name('pelanggan.login.submit');
Route::get('/login', [GoogleController::class, 'showCustomerLoginForm'])->name('login');
Route::post('/login', [GoogleController::class, 'customerEmailLogin']);

Route::get('/pelanggan/daftar', [GoogleController::class, 'showCustomerRegisterForm'])->name('pelanggan.register');
Route::post('/pelanggan/daftar', [GoogleController::class, 'customerRegister'])->name('pelanggan.register.submit');
Route::get('/register', [GoogleController::class, 'showCustomerRegisterForm'])->name('register');
Route::post('/register', [GoogleController::class, 'customerRegister']);
Route::get('/daftar', [GoogleController::class, 'showCustomerRegisterForm'])->name('daftar');
Route::post('/daftar', [GoogleController::class, 'customerRegister']);

// 2. Rute Pegawai & Admin (Email Kedinasan & Sandi)
Route::get('/admin/login', [GoogleController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [GoogleController::class, 'emailLogin'])->name('admin.login.submit');
Route::post('/login/email', [GoogleController::class, 'emailLogin'])->name('login.email');
Route::match(['get', 'post'], '/admin/logout', [GoogleController::class, 'adminLogout'])->name('admin.logout');

// 2.1 Rute Lupa / Reset Kata Sandi & Verifikasi OTP Gmail (Password Reset with OTP 2FA)
Route::get('/lupa-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/lupa-password', [PasswordResetController::class, 'sendOtp'])->name('password.email');
Route::get('/verifikasi-otp', [PasswordResetController::class, 'showOtpForm'])->name('password.otp');
Route::post('/verifikasi-otp', [PasswordResetController::class, 'verifyOtp'])->name('password.otp.verify');
Route::post('/kirim-ulang-otp', [PasswordResetController::class, 'resendOtp'])->name('password.otp.resend');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('password.update');
Route::get('/forgot-password', function () {
    return redirect()->route('password.request');
});

// 3. Endpoint Google OAuth Flow
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/redirect', [GoogleController::class, 'redirectToGoogle'])->name('auth.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.callback');

// 4. Logout Sesi Pelanggan
Route::match(['get', 'post'], '/logout', [GoogleController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/pelanggan/logout', [GoogleController::class, 'logout'])->name('pelanggan.logout');

/*
|--------------------------------------------------------------------------
| Portal Khusus Pelanggan (/pelanggan) & Chat (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/pelanggan', [\App\Http\Controllers\CustomerController::class, 'dashboard'])->name('pelanggan.dashboard');
    Route::post('/pelanggan/profil/kirim-otp', [\App\Http\Controllers\CustomerController::class, 'sendProfileOtp'])->name('pelanggan.profile.send_otp');
    Route::match(['post', 'put'], '/pelanggan/profil', [\App\Http\Controllers\CustomerController::class, 'updateProfile'])->name('pelanggan.profile.update');
    Route::get('/chat/conversation', [ChatController::class, 'getCustomerConversation'])->name('chat.conversation');
    Route::post('/chat/send', [ChatController::class, 'customerSendMessage'])->name('chat.send');
});

/*
|--------------------------------------------------------------------------
| Panel Admin & Pegawai Perpustakaan (Wajib Login & Role Admin/Petugas)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // 1. Dashboard Utama
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard']);

    // 2. Kelola Data Reservasi / Booking
    Route::get('/bookings', [\App\Http\Controllers\AdminController::class, 'bookings'])->name('bookings.index');
    Route::post('/bookings/{id}/status', [\App\Http\Controllers\AdminController::class, 'updateBookingStatus'])->name('bookings.status');

    // 3. Layanan Chat Pelanggan
    Route::get('/chats', [ChatController::class, 'adminIndex'])->name('chat.index');
    Route::get('/chats/search-users', [ChatController::class, 'adminSearchUsers'])->name('chat.search_users');
    Route::post('/chats/start', [ChatController::class, 'adminStartChat'])->name('chat.start');
    Route::post('/chats/{id}/reply', [ChatController::class, 'adminReplyMessage'])->name('chat.reply');
    Route::get('/chats/{id}/messages', [ChatController::class, 'adminFetchMessages'])->name('chat.messages');

    // 4. Laporan & Rekapitulasi (Harian, Mingguan, Bulanan, Tahunan)
    Route::get('/reports', [\App\Http\Controllers\AdminController::class, 'reports'])->name('reports.index');
    Route::get('/reports/print', [\App\Http\Controllers\AdminController::class, 'printReport'])->name('reports.print');

    // 5. Pemantauan Fasilitas & Ruangan
    Route::get('/rooms', [\App\Http\Controllers\AdminController::class, 'rooms'])->name('rooms.index');

    // 6. Manajemen Anggota Admin & Pengaturan Role (Khusus Super Admin)
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'usersIndex'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\AdminController::class, 'usersStore'])->name('users.store');
    Route::put('/users/{id}', [\App\Http\Controllers\AdminController::class, 'usersUpdate'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'usersDestroy'])->name('users.destroy');
});

/*
|--------------------------------------------------------------------------
| File Storage Direct Handler (Akses Berkas Lampiran/Proposal di Windows & Laragon)
|--------------------------------------------------------------------------
*/
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404, 'Berkas lampiran tidak ditemukan di server.');
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.file');

