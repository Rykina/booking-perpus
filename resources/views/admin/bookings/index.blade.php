@extends('layouts.admin')

@section('title', 'Data Reservasi Fasilitas - Panel Pegawai Perpustakaan')

@section('content')
<div>
    
    <!-- Header Title -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h1 style="font-size: 1.55rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0;">
                Kelola Data Reservasi Fasilitas
            </h1>
            <p style="color: #64748b; font-size: 0.88rem; margin: 0;">
                Verifikasi permohonan pinjam Auditorium, Ruang Multimedia, Ruang Rapat, dan Videotron Kota Bogor.
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.reports.index') }}" class="btn" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 8px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                <span>Lihat Laporan Rekap</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 18px; border-radius: 14px; font-size: 0.88rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 18px; border-radius: 14px; font-size: 0.88rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Status Tabs -->
    <div style="display: flex; gap: 8px; margin-bottom: 16px; overflow-x: auto; padding-bottom: 4px;">
        @php
            $currentStatus = request()->get('status', 'all');
        @endphp
        <a href="{{ route('admin.bookings.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" 
           style="padding: 8px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'all' ? 'background: #1a73e8; color: #ffffff;' : 'background: #ffffff; color: #64748b; border: 1px solid #e2e8f0;' }}">
            Semua Data ({{ $statusCounts['all'] }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except('status', 'page'), ['status' => 'menunggu'])) }}" 
           style="padding: 8px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'menunggu' ? 'background: #f59e0b; color: #ffffff;' : 'background: #ffffff; color: #d97706; border: 1px solid #fed7aa;' }}">
            Menunggu Verifikasi ({{ $statusCounts['menunggu'] }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except('status', 'page'), ['status' => 'disetujui'])) }}" 
           style="padding: 8px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'disetujui' ? 'background: #10b981; color: #ffffff;' : 'background: #ffffff; color: #16a34a; border: 1px solid #bbf7d0;' }}">
            Disetujui ({{ $statusCounts['disetujui'] }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except('status', 'page'), ['status' => 'ditolak'])) }}" 
           style="padding: 8px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'ditolak' ? 'background: #ef4444; color: #ffffff;' : 'background: #ffffff; color: #dc2626; border: 1px solid #fecaca;' }}">
            Ditolak ({{ $statusCounts['ditolak'] }})
        </a>
        <a href="{{ route('admin.bookings.index', array_merge(request()->except('status', 'page'), ['status' => 'selesai'])) }}" 
           style="padding: 8px 16px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; text-decoration: none; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'selesai' ? 'background: #64748b; color: #ffffff;' : 'background: #ffffff; color: #64748b; border: 1px solid #e2e8f0;' }}">
            Selesai ({{ $statusCounts['selesai'] }})
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
        <form action="{{ route('admin.bookings.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto; gap: 12px; align-items: end;">
            <input type="hidden" name="status" value="{{ $currentStatus }}">

            <div>
                <label style="display: block; font-size: 0.76rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Pencarian Kata Kunci</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Kode, nama, email, nomor HP..." 
                       style="width: 100%; padding: 8px 14px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-size: 0.76rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Pilih Fasilitas</label>
                <select name="room_id" style="width: 100%; padding: 8px 14px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
                    <option value="all">Semua Fasilitas</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}" {{ $roomId == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.76rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-size: 0.76rem; font-weight: 700; color: #475569; margin-bottom: 4px;">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="padding: 9px 18px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; white-space: nowrap;">
                    Terapkan
                </button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline" style="padding: 9px 14px; border-radius: 8px; font-size: 0.85rem; text-decoration: none;">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.86rem; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="padding: 12px 16px;">Kode & Pemohon</th>
                        <th style="padding: 12px 16px;">Fasilitas / Lokasi</th>
                        <th style="padding: 12px 16px;">Tanggal & Jam</th>
                        <th style="padding: 12px 16px;">Keperluan & Berkas</th>
                        <th style="padding: 12px 16px;">Status</th>
                        <th style="padding: 12px 16px; text-align: right;">Aksi & Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        @php
                            $statusBg = [
                                'menunggu' => 'background: #fef3c7; color: #d97706; border: 1px solid #fde68a;',
                                'disetujui' => 'background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;',
                                'ditolak' => 'background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;',
                                'selesai' => 'background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;',
                                'dibatalkan' => 'background: #f3f4f6; color: #9ca3af; border: 1px solid #e5e7eb;',
                            ];
                        @endphp
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">
                            
                            <!-- Pemohon & Kode -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div style="font-weight: 800; color: #1a73e8; font-family: monospace; font-size: 0.9rem;">
                                    {{ $b->booking_code }}
                                </div>
                                <div style="font-weight: 700; color: #0f172a; margin-top: 2px;">{{ $b->user->name }}</div>
                                <div style="font-size: 0.78rem; color: #64748b;">{{ $b->user->email }}</div>
                                <div style="font-size: 0.76rem; color: #475569; margin-top: 2px;">
                                    WA: <strong>{{ $b->user->phone_number }}</strong>
                                </div>
                                @if($b->user->identity_number)
                                    <div style="font-size: 0.74rem; color: #94a3b8;">NIM/Identitas: {{ $b->user->identity_number }}</div>
                                @endif
                            </td>

                            <!-- Fasilitas -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div style="font-weight: 700; color: #1e293b;">{{ $b->room->name }}</div>
                                <div style="font-size: 0.76rem; color: #64748b;">{{ $b->room->floor }}</div>
                                <div style="font-size: 0.74rem; color: #94a3b8; margin-top: 4px;">
                                    Kategori: {{ $b->room->category === 'layar_iklan' ? 'Videotron' : 'Ruangan' }}
                                </div>
                            </td>

                            <!-- Jadwal -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <div style="font-weight: 700; color: #0f172a;">
                                    {{ $b->booking_date_formatted }}
                                    @if($b->end_date && $b->end_date != $b->booking_date)
                                        s/d {{ $b->end_date_formatted }}
                                    @endif
                                </div>
                                @if($b->room->category !== 'layar_iklan')
                                    <div style="font-size: 0.8rem; color: #475569; margin-top: 2px;">
                                        ⏰ {{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB
                                    </div>
                                    <div style="font-size: 0.74rem; color: #64748b; margin-top: 2px;">
                                        👥 {{ $b->participant_count }} Peserta
                                    </div>
                                @else
                                    <div style="font-size: 0.76rem; color: #1a73e8; font-weight: 600; margin-top: 2px;">
                                        📺 Penayangan Videotron Lobi
                                    </div>
                                    @if($b->participant_count > 1)
                                        <div style="font-size: 0.74rem; color: #64748b; margin-top: 2px;">
                                            👁️ Est. {{ number_format($b->participant_count) }} Target Audiens
                                        </div>
                                    @endif
                                @endif
                            </td>

                            <!-- Keperluan & Berkas -->
                            <td style="padding: 14px 16px; vertical-align: top; max-width: 260px;">
                                <div style="font-size: 0.82rem; color: #334155; line-height: 1.45; word-break: break-word;">
                                    {{ Str::limit($b->purpose, 90) }}
                                </div>
                                @if($b->attachment)
                                    <div style="margin-top: 6px;">
                                        <a href="{{ asset('storage/' . $b->attachment) }}" target="_blank" 
                                           style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.76rem; color: #1a73e8; font-weight: 700; text-decoration: none;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                                            <span>Lihat Berkas Proposal</span>
                                        </a>
                                    </div>
                                @endif
                                @if($b->admin_notes)
                                    <div style="margin-top: 6px; padding: 4px 8px; background: #fef2f2; border-radius: 6px; font-size: 0.74rem; color: #b91c1c;">
                                        <strong>Catatan Admin:</strong> {{ $b->admin_notes }}
                                    </div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td style="padding: 14px 16px; vertical-align: top;">
                                <span style="display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: 0.74rem; font-weight: 800; text-transform: uppercase; {{ $statusBg[$b->status] ?? '' }}">
                                    {{ $b->status }}
                                </span>
                            </td>

                            <!-- Aksi & Verifikasi -->
                            <td style="padding: 14px 16px; vertical-align: top; text-align: right; white-space: nowrap;">
                                <div style="display: flex; gap: 6px; justify-content: flex-end; flex-wrap: wrap;">
                                    
                                    @if($b->status === 'menunggu')
                                        <!-- Quick Approve -->
                                        <form action="{{ route('admin.bookings.status', $b->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="disetujui">
                                            <button type="submit" class="btn" style="background: #10b981; color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-size: 0.76rem; font-weight: 700; border: none; cursor: pointer;">
                                                Setujui
                                            </button>
                                        </form>

                                        <!-- Quick Reject -->
                                        <button type="button" class="btn" onclick="openRejectModal({{ $b->id }}, '{{ $b->booking_code }}')" style="background: #ef4444; color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-size: 0.76rem; font-weight: 700; border: none; cursor: pointer;">
                                            Tolak
                                        </button>
                                    @elseif($b->status === 'disetujui')
                                        <!-- Complete -->
                                        <form action="{{ route('admin.bookings.status', $b->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="btn" style="background: #64748b; color: #ffffff; padding: 6px 12px; border-radius: 9999px; font-size: 0.76rem; font-weight: 700; border: none; cursor: pointer;">
                                                Selesaikan
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Chat button with this user -->
                                    <a href="{{ route('admin.chat.index') }}" title="Chat dengan pemohon" 
                                       style="width: 30px; height: 30px; border-radius: 50%; background: #eff6ff; color: #1a73e8; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #bfdbfe;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                    </a>

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <div style="font-size: 2rem; margin-bottom: 8px;">📭</div>
                                Tidak ada data reservasi fasilitas yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tolak Pengajuan dengan Catatan -->
<div id="rejectBookingModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #ffffff; border-radius: 18px; max-width: 480px; width: 100%; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">Tolak Permohonan Reservasi</h3>
        <p style="font-size: 0.84rem; color: #64748b; margin: 0 0 16px 0;">
            Berikan alasan penolakan untuk kode booking <strong id="rejectModalCode"></strong> agar pemohon memahami perbaikan yang dibutuhkan.
        </p>

        <form id="rejectBookingForm" method="POST">
            @csrf
            <input type="hidden" name="status" value="ditolak">
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #334155; margin-bottom: 6px;">
                    Alasan Penolakan / Catatan Petugas
                </label>
                <textarea name="admin_notes" id="rejectAdminNotes" rows="3" required placeholder="Contoh: Jadwal bentrok dengan agenda resmi kedinasan Kota Bogor, atau berkas proposal belum ditandatangani..." 
                          style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid #cbd5e1; font-size: 0.85rem; box-sizing: border-box; font-family: inherit;"></textarea>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeRejectModal()" class="btn btn-outline" style="padding: 8px 16px; border-radius: 8px; font-size: 0.85rem;">
                    Batal
                </button>
                <button type="submit" class="btn" style="background: #ef4444; color: #ffffff; padding: 8px 18px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer;">
                    Konfirmasi Penolakan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(bookingId, code) {
        document.getElementById('rejectModalCode').textContent = '#' + code;
        document.getElementById('rejectBookingForm').action = `/admin/bookings/${bookingId}/status`;
        document.getElementById('rejectAdminNotes').value = '';
        const modal = document.getElementById('rejectBookingModal');
        modal.style.display = 'flex';
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectBookingModal');
        modal.style.display = 'none';
    }
</script>
@endpush
@endsection

