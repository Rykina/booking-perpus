@extends('layouts.app')

@section('title', 'Portal Pelanggan - Perpustakaan & Galeri Kota Bogor')

@section('content')
<div style="background: #f8fafc; min-height: 85vh; padding: 36px 0 60px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

        @if($isAdmin)
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 14px 20px; border-radius: 14px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 1.2rem;">🏛️</span>
                    <div>
                        <strong>Anda memiliki hak akses Administrator & Pegawai.</strong>
                        <div style="font-size: 0.82rem; color: #3b82f6;">Anda dapat mengelola permohonan, jadwal, dan membalas chat seluruh pelanggan di panel pegawai.</div>
                    </div>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm" style="border-radius: 9999px; text-decoration: none; white-space: nowrap;">
                    Buka Panel Pegawai &rarr;
                </a>
            </div>
        @endif

        @if(session('success'))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 14px 20px; border-radius: 14px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-size: 0.9rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Profile & Welcome Banner -->
        <div style="background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; padding: 28px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 28px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 18px;">
                @if($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; box-shadow: 0 4px 10px rgba(0,0,0,0.06);" referrerpolicy="no-referrer">
                @else
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: #1a73e8; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 800; border: 3px solid #e2e8f0;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <h1 style="margin: 0; font-size: 1.45rem; font-weight: 800; color: #0f172a;">Selamat Datang, {{ $user->name }}!</h1>
                        <span style="background: #e0f2fe; color: #0369a1; font-size: 0.72rem; font-weight: 800; padding: 2px 10px; border-radius: 9999px; text-transform: uppercase;">Pelanggan Terverifikasi</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px; font-size: 0.85rem; color: #64748b; margin-top: 4px;">
                        <span>📧 {{ $user->email }}</span>
                        <span>•</span>
                        <span>🗓️ Terdaftar via Akun Google</span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <button type="button" onclick="openBookingModal()" class="btn btn-primary" style="border-radius: 9999px; padding: 10px 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Ajukan Reservasi Baru
                </button>
                <button type="button" onclick="if(window.toggleCustomerChat) window.toggleCustomerChat(); else document.getElementById('chatLauncherBtn')?.click();" class="btn btn-outline" style="border-radius: 9999px; padding: 10px 18px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    Chat Staf Perpustakaan
                </button>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="border-radius: 9999px; padding: 10px 18px; font-weight: 700; border-color: #fca5a5; color: #dc2626; background: #fff5f5; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s ease;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- KPI / Stats Summary Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 28px;">
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                <div style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Permohonan Saya</div>
                <div style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $stats['total'] }}</div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Seluruh pengajuan fasilitas</div>
            </div>

            <div style="background: #ffffff; border: 1px solid #fef3c7; border-radius: 16px; padding: 18px 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                <div style="font-size: 0.78rem; font-weight: 700; color: #d97706; text-transform: uppercase;">Menunggu Verifikasi</div>
                <div style="font-size: 1.8rem; font-weight: 800; color: #d97706; margin-top: 4px;">{{ $stats['menunggu'] }}</div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Dalam pengecekan petugas</div>
            </div>

            <div style="background: #ffffff; border: 1px solid #dcfce7; border-radius: 16px; padding: 18px 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                <div style="font-size: 0.78rem; font-weight: 700; color: #16a34a; text-transform: uppercase;">Disetujui</div>
                <div style="font-size: 1.8rem; font-weight: 800; color: #16a34a; margin-top: 4px;">{{ $stats['disetujui'] }}</div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Jadwal siap digunakan</div>
            </div>

            <div style="background: #ffffff; border: 1px solid #fee2e2; border-radius: 16px; padding: 18px 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                <div style="font-size: 0.78rem; font-weight: 700; color: #dc2626; text-transform: uppercase;">Ditolak / Batal</div>
                <div style="font-size: 1.8rem; font-weight: 800; color: #dc2626; margin-top: 4px;">{{ $stats['ditolak'] }}</div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Belum memenuhi syarat / bentrok</div>
            </div>
        </div>

        <!-- Section: Daftar Reservasi Saya -->
        <div style="background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
            <div style="padding: 22px 26px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h2 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">Daftar Reservasi & Peminjaman Saya</h2>
                    <p style="margin: 4px 0 0 0; font-size: 0.82rem; color: #64748b;">Pantau status persetujuan, jadwal acara, dan catatan petugas perpustakaan.</p>
                </div>
                <a href="{{ route('booking.index') }}" class="btn btn-outline btn-sm" style="border-radius: 9999px; text-decoration: none;">
                    Lihat Katalog Fasilitas
                </a>
            </div>

            @if($bookings->isEmpty())
                <div style="text-align: center; padding: 50px 20px;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #f1f5f9; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 14px; color: #94a3b8;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; color: #0f172a; margin: 0 0 6px 0;">Belum Ada Permohonan Reservasi</h3>
                    <p style="font-size: 0.85rem; color: #64748b; max-width: 420px; margin: 0 auto 20px auto;">
                        Anda belum pernah mengajukan peminjaman ruangan atau penayangan videotron. Klik tombol di bawah untuk membuat permohonan pertama Anda.
                    </p>
                    <button type="button" onclick="openBookingModal()" class="btn btn-primary" style="border-radius: 9999px; padding: 10px 24px; font-weight: 700;">
                        Ajukan Permohonan Sekarang
                    </button>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.86rem;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #64748b; font-size: 0.74rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <th style="padding: 14px 20px;">Kode Booking</th>
                                <th style="padding: 14px 16px;">Fasilitas</th>
                                <th style="padding: 14px 16px;">Jadwal Pelaksanaan</th>
                                <th style="padding: 14px 16px;">Keperluan</th>
                                <th style="padding: 14px 16px;">Status</th>
                                <th style="padding: 14px 20px; text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $item)
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">
                                    <td style="padding: 16px 20px; font-weight: 700; color: #0f172a;">
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span>{{ $item->booking_code }}</span>
                                            <button type="button" onclick="navigator.clipboard.writeText('{{ $item->booking_code }}'); alert('Kode disalin!');" title="Salin Kode" style="background: none; border: none; cursor: pointer; color: #64748b; padding: 2px;">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                            </button>
                                        </div>
                                        <div style="font-size: 0.72rem; color: #94a3b8; font-weight: 400; margin-top: 2px;">
                                            Diajukan: {{ $item->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </td>
                                    <td style="padding: 16px 16px;">
                                        <div style="font-weight: 700; color: #1e293b;">{{ $item->room->name }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">📍 {{ $item->room->floor }}</div>
                                    </td>
                                    <td style="padding: 16px 16px;">
                                        <div style="font-weight: 600; color: #334155;">{{ $item->booking_date_formatted }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">
                                            @if($item->room->category === 'layar_iklan')
                                                s/d {{ $item->end_date_formatted }} (Tayang)
                                            @else
                                                {{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }} WIB
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 16px 16px; max-width: 250px;">
                                        <div style="color: #334155; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="{{ $item->purpose }}">
                                            {{ $item->purpose }}
                                        </div>
                                    </td>
                                    <td style="padding: 16px 16px;">
                                        @if($item->status === 'disetujui')
                                            <span class="status-badge status-disetujui">Disetujui</span>
                                        @elseif($item->status === 'menunggu')
                                            <span class="status-badge status-menunggu">Menunggu Verifikasi</span>
                                        @elseif($item->status === 'ditolak')
                                            <span class="status-badge status-ditolak">Ditolak</span>
                                        @elseif($item->status === 'selesai')
                                            <span class="status-badge status-selesai">Selesai</span>
                                        @else
                                            <span class="status-badge status-dibatalkan">{{ ucfirst($item->status) }}</span>
                                        @endif

                                        @if($item->admin_notes)
                                            <div style="margin-top: 4px; font-size: 0.72rem; color: #64748b; font-style: italic; max-width: 200px;">
                                                "{{ $item->admin_notes }}"
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 16px 20px; text-align: right; white-space: nowrap;">
                                        <div style="display: inline-flex; gap: 6px;">
                                            <a href="{{ route('booking.print', ['code' => $item->booking_code]) }}" target="_blank" class="btn btn-outline btn-sm" style="border-radius: 9999px; text-decoration: none; padding: 5px 12px; font-size: 0.78rem; font-weight: 700;" title="Cetak Surat Bukti">
                                                🖨️ Cetak
                                            </a>
                                            <a href="{{ route('booking.check', ['code' => $item->booking_code]) }}" class="btn btn-outline btn-sm" style="border-radius: 9999px; text-decoration: none; padding: 5px 12px; font-size: 0.78rem; font-weight: 700;">
                                                Cek Status
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($bookings->hasPages())
                    <div style="padding: 16px 24px; border-top: 1px solid #e2e8f0;">
                        {{ $bookings->links() }}
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>
@endsection

