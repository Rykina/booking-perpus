<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Dashboard Utama Admin & Pegawai Perpustakaan.
     */
    public function dashboard(Request $request)
    {
        // 1. KPI Metrik Utama
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'menunggu')->count();
        $approvedBookings = Booking::where('status', 'disetujui')->count();
        $rejectedBookings = Booking::where('status', 'ditolak')->count();
        $completedBookings = Booking::where('status', 'selesai')->count();

        // 2. Metrik Chat & Pengguna
        $totalConversations = ChatConversation::count();
        $unreadMessagesCount = ChatMessage::where('is_read', false)
            ->whereHas('conversation', function ($q) {
                // Pesan dari customer
                $q->whereColumn('chat_messages.sender_id', 'chat_conversations.user_id');
            })->count();

        $totalCustomers = User::where('role', 'customer')->orWhere('role', 'user')->count();
        $totalFacilities = Room::count();

        // 3. Pengajuan Reservasi Terbaru yang Butuh Verifikasi
        $recentPendingBookings = Booking::with(['room', 'user'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. Jadwal Penggunaan Hari Ini
        $today = now()->toDateString();
        $todayBookings = Booking::with(['room', 'user'])
            ->where('booking_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->where('end_date', '>=', $today)
                  ->orWhereNull('end_date');
            })
            ->where('status', 'disetujui')
            ->orderBy('start_time')
            ->get();

        // 5. Percakapan Chat Terbaru
        $recentChats = ChatConversation::with(['user', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->take(5)
            ->get();

        // 6. Ringkasan Ruangan
        $rooms = Room::all();

        return view('admin.dashboard', compact(
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'rejectedBookings',
            'completedBookings',
            'totalConversations',
            'unreadMessagesCount',
            'totalCustomers',
            'totalFacilities',
            'recentPendingBookings',
            'todayBookings',
            'recentChats',
            'rooms'
        ));
    }

    /**
     * Halaman Kelola Data Reservasi & Booking.
     */
    public function bookings(Request $request)
    {
        $search = trim($request->get('search', ''));
        $status = $request->get('status', 'all');
        $roomId = $request->get('room_id', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = Booking::with(['room', 'user'])->latest();

        // Pencarian teks
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'LIKE', '%' . $search . '%')
                  ->orWhere('purpose', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('phone_number', 'LIKE', '%' . $search . '%')
                        ->orWhere('identity_number', 'LIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('room', function ($r) use ($search) {
                      $r->where('name', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        // Filter Status
        if ($status !== 'all' && in_array($status, ['menunggu', 'disetujui', 'ditolak', 'selesai', 'dibatalkan'])) {
            $query->where('status', $status);
        }

        // Filter Ruangan
        if ($roomId !== 'all' && is_numeric($roomId)) {
            $query->where('room_id', $roomId);
        }

        // Filter Tanggal
        if ($dateFrom) {
            $query->where('booking_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('booking_date', '<=', $dateTo);
        }

        // Status Counts
        $statusCounts = [
            'all' => Booking::count(),
            'menunggu' => Booking::where('status', 'menunggu')->count(),
            'disetujui' => Booking::where('status', 'disetujui')->count(),
            'ditolak' => Booking::where('status', 'ditolak')->count(),
            'selesai' => Booking::where('status', 'selesai')->count(),
        ];

        $bookings = $query->paginate(15)->withQueryString();
        $rooms = Room::all();

        return view('admin.bookings.index', compact('bookings', 'rooms', 'statusCounts', 'search', 'status', 'roomId', 'dateFrom', 'dateTo'));
    }

    /**
     * Memperbarui Status Reservasi (Setujui / Tolak / Selesai / Batal).
     */
    public function updateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,disetujui,ditolak,selesai,dibatalkan',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::with('room')->findOrFail($id);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        // Jika disetujui, periksa bentrok jadwal untuk ruangan
        if ($newStatus === 'disetujui' && $oldStatus !== 'disetujui' && $booking->room->category === 'ruangan') {
            $conflict = Booking::where('room_id', $booking->room_id)
                ->where('id', '!=', $booking->id)
                ->where('booking_date', $booking->booking_date->toDateString())
                ->where('status', 'disetujui')
                ->where(function ($q) use ($booking) {
                    $q->where(function ($sub) use ($booking) {
                        $sub->where('start_time', '<', $booking->end_time)
                            ->where('end_time', '>', $booking->start_time);
                    });
                })->exists();

            if ($conflict) {
                return back()->with('error', 'Gagal menyetujui: Terdapat jadwal reservasi lain yang sudah disetujui pada jam dan ruangan yang sama!');
            }
        }

        $booking->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes ?: $booking->admin_notes,
        ]);

        $statusLabels = [
            'disetujui' => 'disetujui',
            'ditolak' => 'ditolak',
            'selesai' => 'diselesaikan',
            'dibatalkan' => 'dibatalkan',
            'menunggu' => 'dikembalikan ke status verifikasi',
        ];

        $msg = 'Status reservasi #' . $booking->booking_code . ' berhasil ' . ($statusLabels[$newStatus] ?? 'diperbarui') . '.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg, 'booking' => $booking]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Halaman Laporan & Rekapitulasi (Harian, Mingguan, Bulanan, Tahunan).
     */
    public function reports(Request $request)
    {
        $period = $request->get('period', 'bulanan'); // harian, mingguan, bulanan, tahunan, kustom
        $dateParam = $request->get('date', now()->toDateString());
        $monthParam = (int)$request->get('month', now()->month);
        $yearParam = (int)$request->get('year', now()->year);
        $roomId = $request->get('room_id', 'all');
        $status = $request->get('status', 'all');
        $startDateParam = $request->get('start_date');
        $endDateParam = $request->get('end_date');

        $query = Booking::with(['room', 'user']);

        // Tentukan rentang tanggal berdasarkan periode
        $startDate = null;
        $endDate = null;
        $periodLabel = '';

        if ($period === 'harian') {
            $startDate = Carbon::parse($dateParam)->startOfDay();
            $endDate = Carbon::parse($dateParam)->endOfDay();
            $periodLabel = 'Harian: ' . $startDate->translatedFormat('d F Y');
        } elseif ($period === 'mingguan') {
            $refDate = Carbon::parse($dateParam);
            $startDate = $refDate->copy()->startOfWeek();
            $endDate = $refDate->copy()->endOfWeek();
            $periodLabel = 'Mingguan: ' . $startDate->format('d M') . ' s/d ' . $endDate->format('d M Y');
        } elseif ($period === 'bulanan') {
            $startDate = Carbon::createFromDate($yearParam, $monthParam, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($yearParam, $monthParam, 1)->endOfMonth();
            $periodLabel = 'Bulanan: ' . $startDate->translatedFormat('F Y');
        } elseif ($period === 'tahunan') {
            $startDate = Carbon::createFromDate($yearParam, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($yearParam, 12, 31)->endOfYear();
            $periodLabel = 'Tahunan: Tahun ' . $yearParam;
        } elseif ($period === 'kustom') {
            $startDate = $startDateParam ? Carbon::parse($startDateParam)->startOfDay() : now()->subDays(30)->startOfDay();
            $endDate = $endDateParam ? Carbon::parse($endDateParam)->endOfDay() : now()->endOfDay();
            $periodLabel = 'Rentang Khusus: ' . $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');
        }

        $query->whereBetween('booking_date', [$startDate->toDateString(), $endDate->toDateString()]);

        if ($roomId !== 'all' && is_numeric($roomId)) {
            $query->where('room_id', $roomId);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('booking_date')->orderBy('start_time')->get();

        // Statistik Laporan
        $totalBookings = $bookings->count();
        $approvedCount = $bookings->where('status', 'disetujui')->count();
        $completedCount = $bookings->where('status', 'selesai')->count();
        $rejectedCount = $bookings->where('status', 'ditolak')->count();
        $pendingCount = $bookings->where('status', 'menunggu')->count();
        $totalParticipants = $bookings->whereIn('status', ['disetujui', 'selesai'])->sum('participant_count');

        // Statistik per Fasilitas
        $rooms = Room::all();
        $facilityStats = [];
        foreach ($rooms as $r) {
            $rBookings = $bookings->where('room_id', $r->id);
            $facilityStats[] = [
                'name' => $r->name,
                'category' => $r->category,
                'total' => $rBookings->count(),
                'approved' => $rBookings->whereIn('status', ['disetujui', 'selesai'])->count(),
                'participants' => $rBookings->whereIn('status', ['disetujui', 'selesai'])->sum('participant_count'),
            ];
        }

        return view('admin.reports.index', compact(
            'bookings',
            'rooms',
            'period',
            'dateParam',
            'monthParam',
            'yearParam',
            'roomId',
            'status',
            'startDateParam',
            'endDateParam',
            'periodLabel',
            'startDate',
            'endDate',
            'totalBookings',
            'approvedCount',
            'completedCount',
            'rejectedCount',
            'pendingCount',
            'totalParticipants',
            'facilityStats'
        ));
    }

    /**
     * Tampilan Cetak / Print Laporan Resmi (Siap Cetak / Print to PDF).
     */
    public function printReport(Request $request)
    {
        $period = $request->get('period', 'bulanan');
        $dateParam = $request->get('date', now()->toDateString());
        $monthParam = (int)$request->get('month', now()->month);
        $yearParam = (int)$request->get('year', now()->year);
        $roomId = $request->get('room_id', 'all');
        $status = $request->get('status', 'all');
        $startDateParam = $request->get('start_date');
        $endDateParam = $request->get('end_date');

        $query = Booking::with(['room', 'user']);

        if ($period === 'harian') {
            $startDate = Carbon::parse($dateParam)->startOfDay();
            $endDate = Carbon::parse($dateParam)->endOfDay();
            $periodLabel = 'Harian (' . $startDate->translatedFormat('d F Y') . ')';
        } elseif ($period === 'mingguan') {
            $refDate = Carbon::parse($dateParam);
            $startDate = $refDate->copy()->startOfWeek();
            $endDate = $refDate->copy()->endOfWeek();
            $periodLabel = 'Mingguan (' . $startDate->format('d M') . ' s/d ' . $endDate->format('d M Y') . ')';
        } elseif ($period === 'bulanan') {
            $startDate = Carbon::createFromDate($yearParam, $monthParam, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($yearParam, $monthParam, 1)->endOfMonth();
            $periodLabel = 'Bulan ' . $startDate->translatedFormat('F Y');
        } elseif ($period === 'tahunan') {
            $startDate = Carbon::createFromDate($yearParam, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($yearParam, 12, 31)->endOfYear();
            $periodLabel = 'Tahun ' . $yearParam;
        } else {
            $startDate = $startDateParam ? Carbon::parse($startDateParam)->startOfDay() : now()->subDays(30)->startOfDay();
            $endDate = $endDateParam ? Carbon::parse($endDateParam)->endOfDay() : now()->endOfDay();
            $periodLabel = $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');
        }

        $query->whereBetween('booking_date', [$startDate->toDateString(), $endDate->toDateString()]);

        if ($roomId !== 'all' && is_numeric($roomId)) {
            $query->where('room_id', $roomId);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('booking_date')->orderBy('start_time')->get();

        $selectedRoom = ($roomId !== 'all' && is_numeric($roomId)) ? Room::find($roomId) : null;

        return view('admin.reports.print', compact(
            'bookings',
            'periodLabel',
            'selectedRoom',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Halaman Pemantauan Status Fasilitas & Ruangan.
     */
    public function rooms(Request $request)
    {
        $rooms = Room::withCount([
            'bookings as total_bookings',
            'bookings as pending_bookings' => function ($q) {
                $q->where('status', 'menunggu');
            },
            'bookings as approved_bookings' => function ($q) {
                $q->where('status', 'disetujui');
            }
        ])->get();

        $today = now()->toDateString();
        $todayActiveBookings = Booking::with('user')
            ->where('booking_date', $today)
            ->where('status', 'disetujui')
            ->get();

        return view('admin.rooms.index', compact('rooms', 'todayActiveBookings'));
    }

    /**
     * Helper untuk memeriksa hak akses Super Admin.
     */
    protected function checkSuperAdmin(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && $user->isSuperAdmin();
    }

    /**
     * Tampilkan daftar anggota staf admin (Khusus Super Admin).
     */
    public function usersIndex(Request $request)
    {
        if (!$this->checkSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Fitur kelola anggota admin hanya dapat diakses oleh Super Admin.');
        }

        $query = User::query();

        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $roleFilter = $request->get('role');
            if ($roleFilter === 'super_admin') {
                $query->whereIn('role', ['super_admin', 'superadmin']);
            } elseif ($roleFilter === 'admin') {
                $query->whereIn('role', ['admin', 'petugas']);
            } elseif ($roleFilter === 'customer') {
                $query->whereIn('role', ['customer', 'user']);
            }
        }

        $adminUsers = $query->orderByRaw("CASE 
            WHEN role IN ('super_admin', 'superadmin') THEN 1 
            WHEN role IN ('admin', 'petugas') THEN 2 
            ELSE 3 END")
            ->orderBy('name', 'asc')
            ->paginate(15)
            ->withQueryString();

        $totalSuperAdmins = User::whereIn('role', ['super_admin', 'superadmin'])->count();
        $totalRegularAdmins = User::whereIn('role', ['admin', 'petugas'])->count();
        $totalCustomers = User::whereIn('role', ['customer', 'user'])->count();
        $totalUsers = User::count();

        return view('admin.users.index', compact('adminUsers', 'totalSuperAdmins', 'totalRegularAdmins', 'totalCustomers', 'totalUsers'));
    }

    /**
     * Tambahkan anggota baru (Khusus Super Admin).
     */
    public function usersStore(Request $request)
    {
        if (!$this->checkSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Fitur kelola anggota admin hanya dapat diakses oleh Super Admin.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,super_admin,customer',
            'identity_number' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:30',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'role.required' => 'Pilih peran (role) untuk pengguna.',
            'role.in' => 'Peran yang dipilih tidak valid (Super Admin, Admin, atau Pelanggan).',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'identity_number' => $validated['identity_number'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'email_verified_at' => now(),
        ]);

        $roleLabel = match ($user->role) {
            'super_admin', 'superadmin' => 'Super Admin',
            'admin', 'petugas' => 'Admin',
            default => 'Pelanggan',
        };

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna baru {$user->name} berhasil ditambahkan sebagai {$roleLabel}!");
    }

    /**
     * Perbarui data dan peran (role) anggota/pengguna (Khusus Super Admin).
     */
    public function usersUpdate(Request $request, $id)
    {
        if (!$this->checkSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Fitur kelola anggota admin hanya dapat diakses oleh Super Admin.');
        }

        /** @var \App\Models\User $user */
        $user = User::findOrFail($id);
        $currentAdminId = (int) Auth::id();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,super_admin,customer',
            'password' => 'nullable|string|min:6',
            'identity_number' => 'nullable|string|max:50',
            'phone_number' => 'nullable|string|max:30',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'role.required' => 'Pilih peran untuk pengguna.',
            'role.in' => 'Peran yang dipilih tidak valid (Super Admin, Admin, atau Pelanggan).',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
        ]);

        // Proteksi: Super Admin tidak dapat menurunkan rolenya jika dia satu-satunya Super Admin
        if ($currentAdminId && $user->id === $currentAdminId && $validated['role'] !== 'super_admin') {
            $otherSuperAdmins = User::whereIn('role', ['super_admin', 'superadmin'])
                ->where('id', '!=', $user->id)
                ->count();
            if ($otherSuperAdmins === 0) {
                return back()->with('error', 'Tidak dapat mengubah peran Anda sendiri karena Anda adalah satu-satunya Super Admin aktif.');
            }
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'role' => $validated['role'],
            'identity_number' => $validated['identity_number'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
        ];

        $pwdUpdated = false;
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
            $pwdUpdated = true;
        }

        $user->update($updateData);

        $msg = "Data dan hak akses anggota {$user->name} berhasil diperbarui!";
        if ($pwdUpdated) {
            $msg .= " Kata sandi akun baru telah berhasil diperbarui.";
        }

        return redirect()->route('admin.users.index')
            ->with('success', $msg);
    }

    /**
     * Hapus akun anggota admin (Khusus Super Admin).
     */
    public function usersDestroy($id)
    {
        if (!$this->checkSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Fitur kelola anggota admin hanya dapat diakses oleh Super Admin.');
        }

        /** @var \App\Models\User $user */
        $user = User::findOrFail($id);
        $currentAdminId = (int) Auth::id();

        if ($currentAdminId && $user->id === $currentAdminId) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->isSuperAdmin()) {
            $superAdminCount = User::whereIn('role', ['super_admin', 'superadmin'])->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Tidak dapat menghapus Super Admin terakhir di sistem.');
            }
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun admin {$userName} telah berhasil dihapus dari sistem.");
    }
}

