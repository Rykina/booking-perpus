@extends('layouts.admin')

@section('title', 'Laporan & Rekapitulasi Reservasi - Perpustakaan Kota Bogor')

@section('content')
<div>
    
    <!-- Header -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h1 style="font-size: 1.55rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                Laporan & Rekapitulasi Reservasi
            </h1>
            <p style="color: #64748b; font-size: 0.88rem; margin: 0;">
                Rekapitulasi penggunaan fasilitas Perpustakaan dan Galeri Kota Bogor (Harian, Mingguan, Bulanan, dan Tahunan).
            </p>
        </div>
        <div>
            <a href="{{ route('admin.reports.print', request()->all()) }}" target="_blank" 
               class="btn btn-primary" 
               style="padding: 10px 20px; border-radius: 9999px; font-weight: 700; font-size: 0.86rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 10px rgba(26, 115, 232, 0.3); text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Cetak Laporan Resmi (PDF)</span>
            </a>
        </div>
    </div>

    <!-- Period Filter Navigation Pills -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
        
        <div style="display: flex; gap: 8px; margin-bottom: 16px; flex-wrap: wrap;">
            <a href="{{ route('admin.reports.index', ['period' => 'harian', 'date' => now()->toDateString()]) }}" 
               style="padding: 7px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; {{ $period === 'harian' ? 'background: #1a73e8; color: #ffffff;' : 'background: #f1f5f9; color: #475569;' }}">
                Laporan Harian
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'mingguan', 'date' => now()->toDateString()]) }}" 
               style="padding: 7px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; {{ $period === 'mingguan' ? 'background: #1a73e8; color: #ffffff;' : 'background: #f1f5f9; color: #475569;' }}">
                Laporan Mingguan
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'bulanan', 'month' => now()->month, 'year' => now()->year]) }}" 
               style="padding: 7px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; {{ $period === 'bulanan' ? 'background: #1a73e8; color: #ffffff;' : 'background: #f1f5f9; color: #475569;' }}">
                Laporan Bulanan
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'tahunan', 'year' => now()->year]) }}" 
               style="padding: 7px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; {{ $period === 'tahunan' ? 'background: #1a73e8; color: #ffffff;' : 'background: #f1f5f9; color: #475569;' }}">
                Laporan Tahunan
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'kustom']) }}" 
               style="padding: 7px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; {{ $period === 'kustom' ? 'background: #1a73e8; color: #ffffff;' : 'background: #f1f5f9; color: #475569;' }}">
                Rentang Khusus (Custom)
            </a>
        </div>

        <!-- Filter Parameters Form -->
        <form action="{{ route('admin.reports.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) auto; gap: 12px; align-items: end;">
            <input type="hidden" name="period" value="{{ $period }}">

            @if($period === 'harian' || $period === 'mingguan')
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Pilih Tanggal</label>
                    <input type="date" name="date" value="{{ $dateParam }}" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
                </div>
            @elseif($period === 'bulanan')
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Bulan</label>
                    <select name="month" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $monthParam == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create(2026, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Tahun</label>
                    <select name="year" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
                        @for($y = now()->year - 2; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ $yearParam == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            @elseif($period === 'tahunan')
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Pilih Tahun</label>
                    <select name="year" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
                        @for($y = now()->year - 2; $y <= now()->year + 2; $y++)
                            <option value="{{ $y }}" {{ $yearParam == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            @elseif($period === 'kustom')
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDateParam ?: now()->subDays(30)->toDateString() }}" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDateParam ?: now()->toDateString() }}" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
                </div>
            @endif

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Fasilitas</label>
                <select name="room_id" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
                    <option value="all">Semua Fasilitas</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}" {{ $roomId == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Status</label>
                <select name="status" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="menunggu" {{ $status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" style="padding: 9px 20px; border-radius: 8px; font-weight: 700; font-size: 0.85rem;">
                    Tampilkan Rekap
                </button>
            </div>
        </form>

    </div>

    <!-- Active Report Summary Header -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 16px;">
            <div>
                <span style="font-size: 0.76rem; font-weight: 700; color: #1a73e8; text-transform: uppercase;">Laporan Aktif</span>
                <h2 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 2px 0 0 0;">
                    {{ $periodLabel }}
                </h2>
            </div>
            <div style="font-size: 0.82rem; color: #64748b;">
                Rentang Data: <strong>{{ $startDate->format('d M Y') }}</strong> s/d <strong>{{ $endDate->format('d M Y') }}</strong>
            </div>
        </div>

        <!-- 5 Summary Pills -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 12px; text-align: center;">
                <div style="font-size: 0.74rem; font-weight: 700; color: #64748b;">TOTAL PENGAJUAN</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $totalBookings }}</div>
            </div>
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 14px; border-radius: 12px; text-align: center;">
                <div style="font-size: 0.74rem; font-weight: 700; color: #166534;">DISETUJUI</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #15803d; margin-top: 4px;">{{ $approvedCount }}</div>
            </div>
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 14px; border-radius: 12px; text-align: center;">
                <div style="font-size: 0.74rem; font-weight: 700; color: #1e40af;">SELESAI TERLAKSANA</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #1d4ed8; margin-top: 4px;">{{ $completedCount }}</div>
            </div>
            <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 14px; border-radius: 12px; text-align: center;">
                <div style="font-size: 0.74rem; font-weight: 700; color: #991b1b;">DITOLAK</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #b91c1c; margin-top: 4px;">{{ $rejectedCount }}</div>
            </div>
            <div style="background: #faf5ff; border: 1px solid #e9d5ff; padding: 14px; border-radius: 12px; text-align: center;">
                <div style="font-size: 0.74rem; font-weight: 700; color: #6b21a8;">TOTAL PESERTA</div>
                <div style="font-size: 1.5rem; font-weight: 800; color: #7c3aed; margin-top: 4px;">{{ number_format($totalParticipants) }}</div>
            </div>
        </div>
    </div>

    <!-- Facility Breakdown Table -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
        <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 12px 0;">
            Statistik Penggunaan Berdasarkan Fasilitas
        </h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.74rem; text-transform: uppercase;">
                        <th style="padding: 10px 14px;">Nama Fasilitas</th>
                        <th style="padding: 10px 14px;">Kategori</th>
                        <th style="padding: 10px 14px; text-align: center;">Total Pengajuan</th>
                        <th style="padding: 10px 14px; text-align: center;">Disetujui / Terlaksana</th>
                        <th style="padding: 10px 14px; text-align: center;">Akumulasi Peserta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facilityStats as $fs)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 14px; font-weight: 700; color: #0f172a;">{{ $fs['name'] }}</td>
                            <td style="padding: 12px 14px; color: #64748b;">{{ $fs['category'] === 'layar_iklan' ? 'Videotron Lobi' : 'Ruangan Pertemuan' }}</td>
                            <td style="padding: 12px 14px; text-align: center; font-weight: 700;">{{ $fs['total'] }}</td>
                            <td style="padding: 12px 14px; text-align: center; color: #16a34a; font-weight: 700;">{{ $fs['approved'] }}</td>
                            <td style="padding: 12px 14px; text-align: center; color: #7c3aed; font-weight: 700;">{{ number_format($fs['participants']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Bookings Log Table -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0;">
                Rincian Rekapitulasi Reservasi ({{ $bookings->count() }} Data)
            </h3>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.74rem; text-transform: uppercase;">
                        <th style="padding: 10px 14px;">No</th>
                        <th style="padding: 10px 14px;">Kode</th>
                        <th style="padding: 10px 14px;">Pemohon & Kontak</th>
                        <th style="padding: 10px 14px;">Fasilitas</th>
                        <th style="padding: 10px 14px;">Tanggal Pelaksanaan</th>
                        <th style="padding: 10px 14px;">Waktu</th>
                        <th style="padding: 10px 14px;">Peserta</th>
                        <th style="padding: 10px 14px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $idx => $b)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 14px; color: #94a3b8;">{{ $idx + 1 }}</td>
                            <td style="padding: 12px 14px; font-family: monospace; font-weight: 700; color: #1a73e8;">{{ $b->booking_code }}</td>
                            <td style="padding: 12px 14px;">
                                <div style="font-weight: 700; color: #0f172a;">{{ $b->user->name }}</div>
                                <div style="font-size: 0.74rem; color: #64748b;">{{ $b->user->phone_number }} &bull; {{ $b->user->email }}</div>
                            </td>
                            <td style="padding: 12px 14px; font-weight: 600; color: #1e293b;">{{ $b->room->name }}</td>
                            <td style="padding: 12px 14px; font-weight: 700; color: #0f172a;">{{ $b->booking_date_formatted }}</td>
                            <td style="padding: 12px 14px; font-size: 0.78rem; color: #475569;">
                                {{ $b->room->category === 'layar_iklan' ? 'Videotron Lobi' : substr($b->start_time, 0, 5) . ' - ' . substr($b->end_time, 0, 5) . ' WIB' }}
                            </td>
                            <td style="padding: 12px 14px; font-weight: 700; color: #7c3aed;">
                                {{ $b->room->category === 'layar_iklan' ? '-' : $b->participant_count . ' Orang' }}
                            </td>
                            <td style="padding: 12px 14px;">
                                <span style="display: inline-block; padding: 3px 8px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; background: #e2e8f0; color: #334155;">
                                    {{ $b->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 36px; color: #94a3b8;">
                                Tidak ada data reservasi pada rentang periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

