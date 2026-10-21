<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Halaman Utama Portal Pelanggan (/pelanggan).
     */
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admin dipisahkan dari portal pelanggan, dialihkan ke panel admin
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Anda masuk sebagai Admin/Pegawai Perpustakaan. Dialihkan ke Panel Admin.');
        }

        $isAdmin = false;

        // Ambil riwayat reservasi yang diajukan oleh pengguna ini
        $bookingFilter = function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhereHas('user', function ($u) use ($user) {
                  $u->where('email', $user->email);
                  if (!empty($user->identity_number)) {
                      $u->orWhere('identity_number', $user->identity_number);
                  }
              });
        };

        $bookings = Booking::with('room')
            ->where($bookingFilter)
            ->latest()
            ->paginate(10);

        $allUserBookings = Booking::where($bookingFilter)->get();

        $stats = [
            'total' => $allUserBookings->count(),
            'menunggu' => $allUserBookings->where('status', 'menunggu')->count(),
            'disetujui' => $allUserBookings->where('status', 'disetujui')->count(),
            'ditolak' => $allUserBookings->where('status', 'ditolak')->count(),
            'selesai' => $allUserBookings->where('status', 'selesai')->count(),
        ];

        $rooms = Room::all();

        return view('customer.dashboard', compact('user', 'bookings', 'stats', 'rooms', 'isAdmin'));
    }

    /**
     * Memperbarui profil pelanggan (Nama, No. Identitas, Telepon, Foto Profil, dan Kata Sandi).
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $isGoogleUser = !empty($user->google_id);

        $rules = [
            'name' => 'required|string|max:255',
            'identity_number' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:30',
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
            'password' => 'nullable|string|min:6|confirmed',
        ];

        // Jika pengguna mendaftar secara lokal (bukan Google), email bisa diubah
        if (!$isGoogleUser) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $user->id;
        }

        $validated = $request->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
            'avatar_file.image' => 'Berkas foto harus berupa gambar valid (JPG, PNG, WEBP).',
            'avatar_file.max' => 'Ukuran berkas gambar foto maksimal 3 MB.',
            'password.min' => 'Kata sandi baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->name = trim($validated['name']);
        $user->identity_number = !empty($validated['identity_number']) ? trim($validated['identity_number']) : null;
        $user->phone_number = !empty($validated['phone_number']) ? trim($validated['phone_number']) : null;

        if (!$isGoogleUser && !empty($validated['email'])) {
            $user->email = strtolower(trim($validated['email']));
        }

        // Hapus avatar kustom jika dicentang
        if ($request->boolean('remove_avatar')) {
            $user->avatar = null;
        }

        // Upload avatar file jika ada berkas baru
        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $extension = $file->getClientOriginalExtension();
            $filename = 'avatar_' . $user->id . '_' . time() . '_' . Str::random(6) . '.' . $extension;
            $path = $file->storeAs('avatars', $filename, 'public');
            $user->avatar = asset('storage/' . $path);
        }

        // Ganti kata sandi jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('pelanggan.dashboard')
            ->with('success', 'Profil Anda berhasil diperbarui!');
    }
}

