<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

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
}

