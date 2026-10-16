<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Tampilkan halaman utama peminjaman untuk publik.
     */
    public function index(Request $request)
    {
        $rooms = Room::where('status', '!=', 'tidak_aktif')->get();
        $activeBookingsCount = Booking::whereIn('status', ['menunggu', 'disetujui'])->count();
        $totalFacilities = $rooms->count();

        // Ambil jadwal peminjaman disetujui terbaru untuk info ketersediaan
        $upcomingBookings = Booking::with(['room'])
            ->where('status', 'disetujui')
            ->where('booking_date', '>=', now()->toDateString())
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(6)
            ->get();

        return view('booking.index', compact('rooms', 'activeBookingsCount', 'totalFacilities', 'upcomingBookings'));
    }

    /**
     * Proses pengajuan booking baru oleh pengguna/mahasiswa.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'identity_number' => 'required|string|max:50',
            'phone_number' => 'required|string|max:25',
            'room_id' => 'required|exists:rooms,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:booking_date',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'participant_count' => 'nullable|integer|min:1',
            'purpose' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:10240', // Max 10MB
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email aktif wajib diisi.',
            'identity_number.required' => 'NIM atau NIP identitas wajib diisi.',
            'phone_number.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'room_id.required' => 'Pilih fasilitas yang ingin dipinjam.',
            'booking_date.required' => 'Tanggal mulai peminjaman wajib diisi.',
            'booking_date.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'purpose.required' => 'Keperluan atau deskripsi kegiatan/iklan wajib diisi.',
            'attachment.mimes' => 'Format file pendukung harus berupa JPG, PNG, atau PDF.',
            'attachment.max' => 'Ukuran file pendukung maksimal 10MB.',
        ]);

        $room = Room::findOrFail($request->room_id);

        $isFullDay = $request->boolean('is_full_day') || $request->get('booking_type') === 'fullday';

        // Validasi waktu untuk kategori ruangan
        if ($room->category === 'ruangan') {
            if ($isFullDay) {
                // Tentukan jam operasional penuh berdasarkan hari
                $targetDate = Carbon::parse($request->booking_date);
                $dow = $targetDate->dayOfWeek;
                if ($dow === 5) {
                    $errorMsg = 'Layanan fasilitas Perpustakaan Dan Galeri libur pada hari Jumat.';
                    if ($request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => $errorMsg], 422);
                    }
                    return back()->withInput()->withErrors(['booking_date' => $errorMsg]);
                }

                $fullDayStart = '08:00';
                $fullDayEnd = ($dow === 6 || $dow === 0) ? '20:00' : '15:00';
                $request->merge([
                    'start_time' => $fullDayStart,
                    'end_time' => $fullDayEnd,
                ]);
            }

            if (!$request->start_time || !$request->end_time) {
                $errorMsg = 'Jam mulai dan jam selesai wajib diisi untuk peminjaman ruangan.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return back()->withInput()->withErrors(['start_time' => $errorMsg]);
            }

            if (strtotime($request->start_time) >= strtotime($request->end_time)) {
                $errorMsg = 'Jam selesai harus lebih akhir daripada jam mulai.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return back()->withInput()->withErrors(['end_time' => $errorMsg]);
            }

            // Pengecekan bentrok jadwal pada ruangan yang sama
            $conflict = Booking::where('room_id', $room->id)
                ->where('booking_date', $request->booking_date)
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->where(function ($q) use ($request) {
                    $q->where(function ($sub) use ($request) {
                        $sub->where('start_time', '<', $request->end_time)
                            ->where('end_time', '>', $request->start_time);
                    });
                })->exists();

            if ($conflict) {
                $errorMsg = $isFullDay
                    ? 'Maaf, pada tanggal ini sebagian jam sudah terisi atau dalam proses verifikasi peminjam lain sehingga tidak dapat dibooking satu hari penuh. Silakan pilih jam kosong yang tersedia atau tanggal lain.'
                    : 'Maaf, jadwal pada jam tersebut di ' . $room->name . ' sudah dipesan atau sedang dalam verifikasi. Silakan pilih jam atau tanggal lain.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return back()->withInput()->withErrors(['conflict' => $errorMsg]);
            }
        }

        // Cari atau gunakan akun pengguna
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            // Perbarui identitas profil jika diisi atau diperbarui di formulir
            $user->update([
                'name' => $request->name ?: $user->name,
                'identity_number' => $request->identity_number ?: $user->identity_number,
                'phone_number' => $request->phone_number ?: $user->phone_number,
            ]);
        } else {
            $user = User::firstOrCreate(
                ['email' => strtolower(trim($request->email))],
                [
                    'name' => $request->name,
                    'identity_number' => $request->identity_number,
                    'phone_number' => $request->phone_number,
                    'role' => 'user',
                    'password' => Hash::make(Str::random(16)),
                ]
            );

            if (!$user->wasRecentlyCreated) {
                $user->update([
                    'name' => $request->name,
                    'identity_number' => $request->identity_number ?: $user->identity_number,
                    'phone_number' => $request->phone_number ?: $user->phone_number,
                ]);
            }
        }

        // Upload attachment jika ada
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $ext = $file->getClientOriginalExtension();
            $filename = 'bkg_' . time() . '_' . Str::random(8) . '.' . $ext;
            $attachmentPath = $file->storeAs('attachments', $filename, 'public');
        }

        // Generate Booking Code unik
        $bookingCode = 'BKG-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => $user->id,
            'room_id' => $room->id,
            'booking_date' => $request->booking_date,
            'end_date' => ($room->category === 'layar_iklan' && !empty($request->end_date)) ? $request->end_date : $request->booking_date,
            'start_time' => $request->start_time ?: '08:00:00',
            'end_time' => $request->end_time ?: '17:00:00',
            'participant_count' => $request->participant_count ?: 1,
            'purpose' => $request->purpose,
            'attachment' => $attachmentPath,
            'status' => 'menunggu',
        ]);

        $booking->load(['room', 'user']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan booking berhasil dikirimkan!',
                'booking' => $booking,
            ]);
        }

        return redirect()->route('booking.index')
            ->with('success_booking', $booking);
    }

    /**
     * Cek status booking berdasarkan kode booking.
     */
    public function checkStatus(Request $request)
    {
        $code = trim($request->get('code', ''));

        if (empty($code)) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Silakan masukkan kode booking.'], 400);
            }
            return back()->with('search_error', 'Silakan masukkan kode booking.');
        }

        $booking = Booking::with(['room', 'user'])
            ->where('booking_code', $code)
            ->first();

        if (!$booking) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode booking "' . $code . '" tidak ditemukan. Mohon periksa kembali kode booking Anda.'
                ], 404);
            }
            return back()->with('search_error', 'Kode booking "' . $code . '" tidak ditemukan.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'booking' => $booking,
            ]);
        }

        return view('booking.status', compact('booking'));
    }

    /**
     * Halaman Riwayat Pemesanan / Pelacakan Riwayat Booking.
     */
    public function history(Request $request)
    {
        $search = trim($request->get('search', ''));
        $statusFilter = $request->get('status', 'all');

        $query = Booking::with(['room', 'user'])->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('phone_number', 'LIKE', '%' . $search . '%')
                        ->orWhere('identity_number', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE', '%' . $search . '%');
                  })
                  ->orWhereHas('room', function ($r) use ($search) {
                      $r->where('name', 'LIKE', '%' . $search . '%');
                  });
            });
        }

        // Hitung statistik tab status
        $countQuery = clone $query;
        $allBookings = $countQuery->get();

        $stats = [
            'all' => $allBookings->count(),
            'menunggu' => $allBookings->where('status', 'menunggu')->count(),
            'disetujui' => $allBookings->where('status', 'disetujui')->count(),
            'selesai' => $allBookings->where('status', 'selesai')->count(),
            'ditolak' => $allBookings->where('status', 'ditolak')->count(),
        ];

        if ($statusFilter !== 'all' && in_array($statusFilter, ['menunggu', 'disetujui', 'selesai', 'ditolak'])) {
            $query->where('status', $statusFilter);
        }

        // Jika ada pencarian atau ada parameter all, tampilkan hasil
        $hasSearch = !empty($search) || $request->has('all') || $request->has('status');
        $bookings = $hasSearch ? $query->paginate(12)->withQueryString() : collect();

        // 6 booking terbaru untuk inspirasi/tampilan awal jika belum mencari
        $recentBookings = Booking::with(['room', 'user'])->latest()->take(6)->get();
        $rooms = Room::where('status', '!=', 'tidak_aktif')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'bookings' => $bookings,
            ]);
        }

        return view('booking.history', compact('bookings', 'search', 'statusFilter', 'stats', 'hasSearch', 'recentBookings', 'rooms'));
    }

    /**
     * API untuk mengambil daftar riwayat booking (JSON) untuk integrasi frontend / LocalStorage.
     */
    public function apiHistory(Request $request)
    {
        $search = trim($request->get('search', ''));
        $codes = $request->get('codes', []);

        if (is_string($codes)) {
            $codes = array_filter(explode(',', $codes));
        }

        $query = Booking::with(['room', 'user'])->latest();

        if (!empty($codes)) {
            $query->whereIn('booking_code', $codes);
        } elseif (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('phone_number', 'LIKE', '%' . $search . '%')
                        ->orWhere('identity_number', 'LIKE', '%' . $search . '%')
                        ->orWhere('name', 'LIKE', '%' . $search . '%');
                  });
            });
        } else {
            return response()->json([
                'success' => true,
                'bookings' => [],
                'message' => 'Masukkan kata kunci pencarian atau kode booking.',
            ]);
        }

        $bookings = $query->take(30)->get();

        return response()->json([
            'success' => true,
            'count' => $bookings->count(),
            'bookings' => $bookings->map(function ($b) {
                return [
                    'id' => $b->id,
                    'booking_code' => $b->booking_code,
                    'room_name' => $b->room->name,
                    'room_floor' => $b->room->floor,
                    'room_category' => $b->room->category,
                    'user_name' => $b->user->name,
                    'user_email' => $b->user->email,
                    'user_phone' => $b->user->phone_number,
                    'user_identity' => $b->user->identity_number,
                    'booking_date' => $b->booking_date->format('Y-m-d'),
                    'booking_date_formatted' => $b->booking_date->format('d M Y'),
                    'end_date' => $b->end_date ? $b->end_date->format('Y-m-d') : null,
                    'end_date_formatted' => $b->end_date ? $b->end_date->format('d M Y') : null,
                    'start_time' => substr($b->start_time, 0, 5),
                    'end_time' => substr($b->end_time, 0, 5),
                    'is_full_day' => (bool)$b->is_full_day,
                    'participant_count' => $b->participant_count,
                    'purpose' => $b->purpose,
                    'status' => $b->status,
                    'admin_notes' => $b->admin_notes,
                    'created_at_formatted' => $b->created_at->format('d M Y, H:i'),
                ];
            }),
        ]);
    }


    /**
     * Ambil daftar jadwal slot ketersediaan (hari dan jam) untuk fasilitas pada tanggal tertentu (API).
     */
    public function getRoomSlots(Request $request)
    {
        $roomId = $request->get('room_id', 1);
        $date = $request->get('date', now()->toDateString());

        $room = Room::find($roomId) ?? Room::first();
        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Fasilitas tidak ditemukan'], 404);
        }

        try {
            $targetDate = Carbon::parse($date);
        } catch (\Exception $e) {
            $targetDate = now();
            $date = $targetDate->toDateString();
        }

        // Nama hari bahasa Indonesia
        $dayNames = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $dayOfWeek = $targetDate->dayOfWeek; // 0=Minggu, 1=Senin, ..., 5=Jumat, 6=Sabtu
        $dayName = $dayNames[$dayOfWeek];

        // Jam operasional resmi:
        // Senin – Kamis (1–4): 08:00 – 15:00 WIB
        // Jumat (5): Libur Layanan / Tutup
        // Sabtu – Minggu (6, 0): 08:00 – 20:00 WIB
        $isOpen = true;
        $startHour = 8;
        $endHour = 15;
        $openHoursText = '08:00 – 15:00 WIB';

        if ($dayOfWeek === 5) {
            $isOpen = false;
            $openHoursText = 'Libur Layanan (Tutup)';
        } elseif ($dayOfWeek === 6 || $dayOfWeek === 0) {
            $isOpen = true;
            $startHour = 8;
            $endHour = 20;
            $openHoursText = '08:00 – 20:00 WIB';
        }

        // Query seluruh booking aktif pada tanggal ini
        if ($room->category === 'layar_iklan') {
            $existingBookings = Booking::where('room_id', $room->id)
                ->where(function ($q) use ($date) {
                    $q->where('booking_date', $date)
                      ->orWhere(function ($sub) use ($date) {
                          $sub->where('booking_date', '<=', $date)
                              ->where('end_date', '>=', $date);
                      });
                })
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->get();
        } else {
            $existingBookings = Booking::where('room_id', $room->id)
                ->where('booking_date', $date)
                ->whereIn('status', ['menunggu', 'disetujui'])
                ->get();
        }

        $slots = [];
        $availableCount = 0;
        $bookedCount = 0;
        $pendingCount = 0;

        if ($room->category === 'layar_iklan') {
            // Khusus Videotron: Berdasarkan penayangan harian
            if (!$isOpen) {
                $slots[] = [
                    'start_time' => '08:00',
                    'end_time' => '15:00',
                    'time_label' => 'Sepanjang Hari Operasional',
                    'status' => 'tutup',
                    'status_label' => 'Libur Layanan',
                    'purpose' => 'Layanan Perpustakaan & Galeri Libur pada Hari Jumat',
                    'booking_code' => null,
                ];
            } else {
                $activeCampaigns = $existingBookings->count();
                $adStatus = ($activeCampaigns >= 4) ? 'terisi' : (($activeCampaigns > 0) ? 'sebagian' : 'tersedia');
                
                $slots[] = [
                    'start_time' => '08:00',
                    'end_time' => ($dayOfWeek === 6 || $dayOfWeek === 0) ? '20:00' : '15:00',
                    'time_label' => 'Sepanjang Jam Layanan (' . $openHoursText . ')',
                    'status' => ($adStatus === 'terisi' ? 'terisi' : 'tersedia'),
                    'status_label' => ($adStatus === 'terisi' ? 'Slot Iklan Penuh' : ($activeCampaigns > 0 ? 'Tersedia (' . $activeCampaigns . ' iklan aktif)' : 'Slot Kosong / Tersedia')),
                    'purpose' => $activeCampaigns > 0 ? 'Terdapat ' . $activeCampaigns . ' materi penayangan aktif di videotron lobi.' : 'Belum ada jadwal tayang iklan pada tanggal ini, slot siap dipesan.',
                    'booking_code' => $activeCampaigns > 0 ? 'IKLAN-AKTIF' : null,
                ];

                if ($adStatus === 'terisi') {
                    $bookedCount++;
                } else {
                    $availableCount++;
                }
            }
        } else {
            // Ruangan: Breakdown per jam
            if (!$isOpen) {
                $slots[] = [
                    'start_time' => '08:00',
                    'end_time' => '15:00',
                    'time_label' => '08:00 – 15:00 WIB',
                    'status' => 'tutup',
                    'status_label' => 'Libur Layanan',
                    'purpose' => 'Layanan Perpustakaan & Galeri Tutup pada Hari Jumat',
                    'booking_code' => null,
                ];
            } else {
                for ($h = $startHour; $h < $endHour; $h++) {
                    $slotStart = sprintf('%02d:00', $h);
                    $slotEnd = sprintf('%02d:00', $h + 1);
                    $timeLabel = $slotStart . ' – ' . $slotEnd;

                    $slotStatus = 'tersedia';
                    $purpose = null;
                    $code = null;

                    foreach ($existingBookings as $b) {
                        $bStart = substr($b->start_time, 0, 5);
                        $bEnd = substr($b->end_time, 0, 5);

                        // Cek bentrok jam: bStart < slotEnd && bEnd > slotStart
                        if ($bStart < $slotEnd && $bEnd > $slotStart) {
                            if ($b->status === 'disetujui') {
                                $slotStatus = 'terisi';
                                $purpose = $b->purpose;
                                $code = substr($b->booking_code, 0, 8) . '***';
                                break;
                            } elseif ($b->status === 'menunggu') {
                                $slotStatus = 'menunggu';
                                $purpose = 'Sedang dalam proses verifikasi petugas';
                                $code = substr($b->booking_code, 0, 8) . '***';
                            }
                        }
                    }

                    if ($slotStatus === 'tersedia') {
                        $availableCount++;
                    } elseif ($slotStatus === 'terisi') {
                        $bookedCount++;
                    } elseif ($slotStatus === 'menunggu') {
                        $pendingCount++;
                    }

                    $slots[] = [
                        'start_time' => $slotStart,
                        'end_time' => $slotEnd,
                        'time_label' => $timeLabel,
                        'status' => $slotStatus,
                        'status_label' => $slotStatus === 'tersedia' ? 'Kosong / Tersedia' : ($slotStatus === 'terisi' ? 'Penuh / Terisi' : 'Menunggu Verifikasi'),
                        'purpose' => $purpose,
                        'booking_code' => $code,
                    ];
                }
            }
        }

        // 7-Hari Kalender Ikhtisar Hari (Melihat Hari yang Kosong atau Penuh)
        $weekOverview = [];
        $startDate = $targetDate->copy()->subDays(1); // 1 hari sebelum target s/d 5 hari ke depan
        for ($i = 0; $i < 7; $i++) {
            $cDate = $startDate->copy()->addDays($i);
            $cDateStr = $cDate->toDateString();
            $cDow = $cDate->dayOfWeek;
            $cDayName = $dayNames[$cDow];
            $cIsOpen = ($cDow !== 5);

            if (!$cIsOpen) {
                $dayStatus = 'libur';
                $dayLabel = 'Libur';
                $dayAvail = 0;
                $dayTotal = 0;
            } elseif ($room->category === 'layar_iklan') {
                $dayBookingsCount = Booking::where('room_id', $room->id)
                    ->where(function ($q) use ($cDateStr) {
                        $q->where('booking_date', $cDateStr)
                          ->orWhere(function ($sub) use ($cDateStr) {
                              $sub->where('booking_date', '<=', $cDateStr)
                                  ->where('end_date', '>=', $cDateStr);
                          });
                    })
                    ->whereIn('status', ['menunggu', 'disetujui'])
                    ->count();

                if ($dayBookingsCount >= 4) {
                    $dayStatus = 'penuh';
                    $dayLabel = 'Penuh';
                } elseif ($dayBookingsCount > 0) {
                    $dayStatus = 'sebagian';
                    $dayLabel = 'Tersedia (' . (4 - $dayBookingsCount) . ' slot)';
                } else {
                    $dayStatus = 'kosong';
                    $dayLabel = 'Kosong';
                }
                $dayAvail = max(0, 4 - $dayBookingsCount);
                $dayTotal = 4;
            } else {
                $cTotalHours = ($cDow === 6 || $cDow === 0) ? 12 : 7;
                $dayBookings = Booking::where('room_id', $room->id)
                    ->where('booking_date', $cDateStr)
                    ->whereIn('status', ['menunggu', 'disetujui'])
                    ->get();

                $cOccupied = 0;
                $cStartH = 8;
                $cEndH = ($cDow === 6 || $cDow === 0) ? 20 : 15;
                for ($ch = $cStartH; $ch < $cEndH; $ch++) {
                    $csStart = sprintf('%02d:00', $ch);
                    $csEnd = sprintf('%02d:00', $ch + 1);
                    foreach ($dayBookings as $db) {
                        $dbStart = substr($db->start_time, 0, 5);
                        $dbEnd = substr($db->end_time, 0, 5);
                        if ($dbStart < $csEnd && $dbEnd > $csStart) {
                            $cOccupied++;
                            break;
                        }
                    }
                }

                $dayAvail = max(0, $cTotalHours - $cOccupied);
                $dayTotal = $cTotalHours;

                if ($cOccupied === 0) {
                    $dayStatus = 'kosong';
                    $dayLabel = 'Semua Kosong';
                } elseif ($cOccupied >= $cTotalHours) {
                    $dayStatus = 'penuh';
                    $dayLabel = 'Penuh';
                } else {
                    $dayStatus = 'sebagian';
                    $dayLabel = $dayAvail . ' Jam Kosong';
                }
            }

            $monthNames = [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
                7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
            ];
            $monthNum = (int)$cDate->format('n');
            $dateFormatted = $cDate->format('j') . ' ' . ($monthNames[$monthNum] ?? $cDate->format('M'));

            $weekOverview[] = [
                'date' => $cDateStr,
                'day_name' => $cDayName,
                'date_formatted' => $dateFormatted,
                'day_number' => $cDate->format('d'),
                'month_name' => ($monthNames[$monthNum] ?? $cDate->format('M')),
                'is_open' => $cIsOpen,
                'status' => $dayStatus,
                'status_label' => $dayLabel,
                'available_count' => $dayAvail,
                'total_count' => $dayTotal,
                'is_selected' => ($cDateStr === $date),
                'is_today' => ($cDateStr === now()->toDateString()),
            ];
        }

        $targetMonthNum = (int)$targetDate->format('n');
        $targetMonthNamesFull = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $fullDateFormatted = $dayName . ', ' . $targetDate->format('j') . ' ' . ($targetMonthNamesFull[$targetMonthNum] ?? $targetDate->format('F')) . ' ' . $targetDate->format('Y');

        return response()->json([
            'success' => true,
            'room' => [
                'id' => $room->id,
                'name' => $room->name,
                'code' => $room->code,
                'category' => $room->category,
                'floor' => $room->floor,
                'capacity' => $room->capacity,
            ],
            'date' => $date,
            'day_name' => $dayName,
            'date_formatted' => $fullDateFormatted,
            'is_open' => $isOpen,
            'open_hours' => $openHoursText,
            'summary' => [
                'total_slots' => count($slots),
                'available_slots' => $availableCount,
                'booked_slots' => $bookedCount,
                'pending_slots' => $pendingCount,
                'status_hari' => (!$isOpen ? 'libur' : ($availableCount === 0 ? 'penuh' : ($bookedCount > 0 ? 'sebagian' : 'kosong'))),
            ],
            'slots' => $slots,
            'week_overview' => $weekOverview,
        ]);
    }
}
