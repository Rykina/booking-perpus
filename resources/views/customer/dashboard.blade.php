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

        @if(session('error'))
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 14px 20px; border-radius: 14px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-size: 0.9rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 14px 20px; border-radius: 14px; margin-bottom: 24px;">
                <div style="font-weight: 700; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>Terdapat kendala saat menyimpan profil:</span>
                </div>
                <ul style="margin: 0; padding-left: 26px; font-size: 0.85rem;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile & Welcome Banner -->
        <div style="background: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; padding: 28px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); margin-bottom: 28px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 18px;">
                <div style="position: relative; cursor: pointer; flex-shrink: 0;" onclick="openEditProfileModal()" title="Klik untuk mengedit foto profil">
                    @if($user->avatar)
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width: 68px; height: 68px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; box-shadow: 0 4px 10px rgba(0,0,0,0.06);" referrerpolicy="no-referrer">
                    @else
                        <div style="width: 68px; height: 68px; border-radius: 50%; background: #1a73e8; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 800; border: 3px solid #e2e8f0;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div style="position: absolute; bottom: 0; right: 0; background: #2563eb; color: #ffffff; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.25);" title="Ubah Foto">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                    </div>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <h1 style="margin: 0; font-size: 1.45rem; font-weight: 800; color: #0f172a;">Selamat Datang, {{ $user->name }}!</h1>
                        <span style="background: #e0f2fe; color: #0369a1; font-size: 0.72rem; font-weight: 800; padding: 2px 10px; border-radius: 9999px; text-transform: uppercase;">Pelanggan Terverifikasi</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 14px; font-size: 0.85rem; color: #64748b; margin-top: 5px; flex-wrap: wrap;">
                        <span>📧 {{ $user->email }}</span>
                        @if($user->phone_number)
                            <span>•</span>
                            <span>📱 {{ $user->phone_number }}</span>
                        @endif
                        @if($user->identity_number)
                            <span>•</span>
                            <span>🪪 ID: {{ $user->identity_number }}</span>
                        @endif
                        <span>•</span>
                        <span>🗓️ {{ $user->google_id ? 'Terdaftar via Akun Google' : 'Akun Terdaftar' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <button type="button" onclick="openBookingModal()" class="btn btn-primary" style="border-radius: 9999px; padding: 10px 20px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <span>Ajukan Reservasi Baru</span>
                </button>
                <button type="button" onclick="openEditProfileModal()" class="btn btn-outline" style="border-radius: 9999px; padding: 10px 18px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <span>Edit Profil</span>
                </button>
                <button type="button" onclick="if(window.toggleCustomerChat) window.toggleCustomerChat(); else document.getElementById('chatLauncherBtn')?.click();" class="btn btn-outline" style="border-radius: 9999px; padding: 10px 18px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <span>Chat Staf Perpustakaan</span>
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
                    Lihat Ruangan
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

<!-- ========================================================================= -->
<!-- JENDELA MODAL EDIT PROFIL PELANGGAN -->
<!-- ========================================================================= -->
<div class="modal-backdrop edit-profile-modal-backdrop" id="editProfileModal" aria-hidden="true" onclick="handleEditProfileBackdrop(event)">
    <div class="modal-dialog edit-profile-modal-dialog" onclick="event.stopPropagation()">
        <!-- Header Modal -->
        <div class="edit-profile-header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 12px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a;">Edit Profil Saya</h3>
                    <p style="margin: 2px 0 0 0; font-size: 0.78rem; color: #64748b;">Perbarui data diri, nomor kontak, foto profil, atau kata sandi Anda.</p>
                </div>
            </div>
            <button type="button" class="modal-close" onclick="closeEditProfileModal()" aria-label="Tutup Jendela" style="background: none; border: none; cursor: pointer; color: #94a3b8; padding: 4px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Form Edit Profil -->
        <form action="{{ route('pelanggan.profile.update') }}" method="POST" enctype="multipart/form-data" id="formEditProfile" style="display: flex; flex-direction: column; height: 100%; margin: 0;">
            @csrf
            @method('PUT')

            <div class="edit-profile-body">
                <!-- Foto Profil / Avatar Box -->
                <div class="profile-avatar-edit-box">
                    <div id="avatarPreviewContainer" style="flex-shrink: 0;">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" id="avatarPreviewImg" class="profile-avatar-preview-img" referrerpolicy="no-referrer">
                            <div id="avatarPreviewPlaceholder" class="profile-avatar-preview-placeholder" style="display: none;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @else
                            <img src="" alt="{{ $user->name }}" id="avatarPreviewImg" class="profile-avatar-preview-img" style="display: none;">
                            <div id="avatarPreviewPlaceholder" class="profile-avatar-preview-placeholder">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <label class="edit-profile-label" style="margin-bottom: 4px;">Foto Profil</label>
                        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                            <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('avatarFileInput').click()" style="border-radius: 9999px; padding: 6px 14px; font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                <span>Pilih Foto Baru</span>
                            </button>
                            <input type="file" name="avatar_file" id="avatarFileInput" accept="image/jpeg,image/png,image/webp,image/gif" style="display: none;" onchange="previewAvatar(this)">
                        </div>
                        <span class="edit-profile-hint">Mendukung format JPG, PNG, WEBP (maks. 3 MB).</span>

                        @if($user->avatar)
                            <div style="margin-top: 8px;">
                                <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #dc2626; cursor: pointer;">
                                    <input type="checkbox" name="remove_avatar" value="1" id="checkboxRemoveAvatar">
                                    <span>Hapus foto kustom (kembali ke avatar default)</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Input Nama Lengkap -->
                <div class="edit-profile-form-group">
                    <label class="edit-profile-label">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="name" class="edit-profile-input" value="{{ old('name', $user->name) }}" required placeholder="Masukkan nama lengkap Anda">
                </div>

                <!-- Input Email -->
                <div class="edit-profile-form-group">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                        <label class="edit-profile-label" style="margin: 0;">Alamat Email <span style="color: #ef4444;">*</span></label>
                        @if($user->google_id)
                            <span style="font-size: 0.72rem; color: #2563eb; background: #eff6ff; padding: 2px 8px; border-radius: 9999px; font-weight: 700;">Google OAuth</span>
                        @endif
                    </div>
                    <input type="email" name="email" class="edit-profile-input" value="{{ old('email', $user->email) }}" {{ $user->google_id ? 'readonly' : 'required' }} placeholder="nama@email.com">
                    @if($user->google_id)
                        <span class="edit-profile-hint">Email terhubung dengan akun Google Anda dan tidak dapat diubah di sini.</span>
                    @endif
                </div>

                <!-- Input Nomor Identitas & Telepon -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="edit-profile-form-group">
                        <label class="edit-profile-label">Nomor Identitas (NIK / NIM)</label>
                        <input type="text" name="identity_number" class="edit-profile-input" value="{{ old('identity_number', $user->identity_number) }}" placeholder="Contoh: 327101xxxxxx / 220101xxx">
                        <span class="edit-profile-hint">Untuk verifikasi saat peminjaman ruangan.</span>
                    </div>
                    <div class="edit-profile-form-group">
                        <label class="edit-profile-label">Nomor Telepon / WhatsApp</label>
                        <input type="tel" name="phone_number" class="edit-profile-input" value="{{ old('phone_number', $user->phone_number) }}" placeholder="Contoh: 08123456789">
                        <span class="edit-profile-hint">Untuk konfirmasi peminjaman & notifikasi.</span>
                    </div>
                </div>

                <!-- Accordion / Bagian Ganti Kata Sandi (Opsional) -->
                <div style="margin-top: 10px; border-top: 1px dashed #e2e8f0; padding-top: 16px;">
                    <div style="margin-bottom: 12px;">
                        <span style="font-size: 0.86rem; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <span>Ganti Kata Sandi (Opsional)</span>
                        </span>
                        <span class="edit-profile-hint" style="margin-top: 2px;">Kosongkan kolom berikut jika Anda tidak ingin mengganti kata sandi akun.</span>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        <div class="edit-profile-form-group">
                            <label class="edit-profile-label">Kata Sandi Baru</label>
                            <div style="position: relative;">
                                <input type="password" name="password" id="edit_password" minlength="6" class="edit-profile-input" placeholder="Minimal 6 karakter" autocomplete="new-password" style="padding-right: 38px;" oninput="handlePasswordChangeInput()">
                                <button type="button" onclick="toggleEditPassword('edit_password', this)" title="Lihat Sandi" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 2px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                </button>
                            </div>
                        </div>
                        <div class="edit-profile-form-group">
                            <label class="edit-profile-label">Konfirmasi Sandi Baru</label>
                            <div style="position: relative;">
                                <input type="password" name="password_confirmation" id="edit_password_confirmation" minlength="6" class="edit-profile-input" placeholder="Ulangi kata sandi baru" autocomplete="new-password" style="padding-right: 38px;" oninput="handlePasswordChangeInput()">
                                <button type="button" onclick="toggleEditPassword('edit_password_confirmation', this)" title="Lihat Sandi" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 2px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Kotak Verifikasi OTP Khusus Ganti Kata Sandi -->
                    <div id="otpSectionWrapper" style="margin-top: 14px; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 14px; display: none;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <label class="edit-profile-label" style="margin: 0; font-size: 0.82rem; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                <span>Verifikasi OTP Ganti Kata Sandi <span style="color: #ef4444;">*</span></span>
                            </label>
                            <span style="font-size: 0.72rem; color: #2563eb; font-weight: 700; background: #eff6ff; padding: 2px 8px; border-radius: 9999px;">Wajib</span>
                        </div>
                        <p style="font-size: 0.76rem; color: #64748b; margin: 0 0 10px 0; line-height: 1.4;">
                            Untuk perlindungan akun, klik tombol <strong>Kirim Kode OTP</strong> di bawah untuk menerima 6 digit kode keamanan ke email Anda (<strong>{{ $user->email }}</strong>).
                        </p>
                        
                        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                            <input type="text" name="otp_code" id="edit_profile_otp" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" class="edit-profile-input" placeholder="000000" style="max-width: 150px; font-family: monospace; letter-spacing: 5px; font-weight: 800; font-size: 1.05rem; text-align: center; height: 38px; margin: 0;">
                            
                            <button type="button" id="btnSendProfileOtp" onclick="sendProfileOtp()" class="btn btn-primary" style="height: 38px; padding: 0 16px; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; flex-shrink: 0;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" id="iconSendOtp"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                <span id="textSendProfileOtp">Kirim Kode OTP</span>
                            </button>
                        </div>
                        
                        <div id="otpStatusAlert" style="margin-top: 8px; font-size: 0.78rem; display: none; line-height: 1.4;"></div>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="edit-profile-footer">
                <button type="button" class="btn btn-outline" onclick="closeEditProfileModal()" style="border-radius: 9999px; padding: 8px 18px; font-weight: 600; font-size: 0.84rem;">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary" style="border-radius: 9999px; padding: 8px 22px; font-weight: 700; font-size: 0.84rem; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditProfileModal() {
    const modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
}

function closeEditProfileModal() {
    const modal = document.getElementById('editProfileModal');
    if (modal) {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }
}

function handleEditProfileBackdrop(e) {
    if (e.target && e.target.id === 'editProfileModal') {
        closeEditProfileModal();
    }
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.size > 3 * 1024 * 1024) {
            alert('Ukuran file foto maksimal 3 MB.');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('avatarPreviewImg');
            const previewPlaceholder = document.getElementById('avatarPreviewPlaceholder');
            if (previewImg) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            }
            if (previewPlaceholder) {
                previewPlaceholder.style.display = 'none';
            }
            const removeCb = document.getElementById('checkboxRemoveAvatar');
            if (removeCb) removeCb.checked = false;
        };
        reader.readAsDataURL(file);
    }
}

function toggleEditPassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isPass = input.type === 'password';
    input.type = isPass ? 'text' : 'password';
    const openEye = btn.querySelector('.eye-open');
    const closedEye = btn.querySelector('.eye-closed');
    if (openEye && closedEye) {
        openEye.style.display = isPass ? 'none' : 'block';
        closedEye.style.display = isPass ? 'block' : 'none';
    }
}

function handlePasswordChangeInput() {
    const pwd = document.getElementById('edit_password');
    const pwdConfirm = document.getElementById('edit_password_confirmation');
    const otpWrapper = document.getElementById('otpSectionWrapper');
    const otpInput = document.getElementById('edit_profile_otp');

    const hasValue = (pwd && pwd.value.trim().length > 0) || (pwdConfirm && pwdConfirm.value.trim().length > 0);

    if (otpWrapper) {
        if (hasValue) {
            otpWrapper.style.display = 'block';
            if (otpInput) otpInput.setAttribute('required', 'required');
        } else {
            otpWrapper.style.display = 'none';
            if (otpInput) {
                otpInput.removeAttribute('required');
                otpInput.value = '';
            }
        }
    }
}

let otpCountdownTimer = null;

function sendProfileOtp() {
    const btn = document.getElementById('btnSendProfileOtp');
    const btnText = document.getElementById('textSendProfileOtp');
    const statusAlert = document.getElementById('otpStatusAlert');
    const otpInput = document.getElementById('edit_profile_otp');

    if (!btn || btn.disabled) return;

    btn.disabled = true;
    btnText.textContent = 'Mengirim...';
    statusAlert.style.display = 'block';
    statusAlert.style.color = '#2563eb';
    statusAlert.innerHTML = 'Sedang mengirimkan kode OTP ke email Anda...';

    fetch("{{ route('pelanggan.profile.send_otp') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            statusAlert.style.color = '#16a34a';
            statusAlert.innerHTML = '✓ ' + data.message;
            if (otpInput) otpInput.focus();

            // Hitung mundur 60 detik untuk kirim ulang
            let remaining = 60;
            btnText.textContent = `Kirim Ulang (${remaining}s)`;
            clearInterval(otpCountdownTimer);
            otpCountdownTimer = setInterval(() => {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(otpCountdownTimer);
                    btn.disabled = false;
                    btnText.textContent = 'Kirim Ulang OTP';
                } else {
                    btnText.textContent = `Kirim Ulang (${remaining}s)`;
                }
            }, 1000);
        } else {
            btn.disabled = false;
            btnText.textContent = 'Kirim Kode OTP';
            statusAlert.style.color = '#dc2626';
            statusAlert.innerHTML = '✕ ' + (data.message || 'Gagal mengirim OTP. Silakan coba kembali.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btnText.textContent = 'Kirim Kode OTP';
        statusAlert.style.color = '#dc2626';
        statusAlert.innerHTML = '✕ Terjadi gangguan koneksi. Pastikan internet aktif dan coba lagi.';
    });
}

const formEditProfile = document.getElementById('formEditProfile');
if (formEditProfile) {
    formEditProfile.addEventListener('submit', function(e) {
        const pwd = document.getElementById('edit_password');
        const otpInput = document.getElementById('edit_profile_otp');
        if (pwd && pwd.value.trim().length > 0) {
            if (!otpInput || otpInput.value.trim().length !== 6) {
                e.preventDefault();
                const statusAlert = document.getElementById('otpStatusAlert');
                if (statusAlert) {
                    statusAlert.style.display = 'block';
                    statusAlert.style.color = '#dc2626';
                    statusAlert.innerHTML = '✕ Silakan klik "Kirim Kode OTP" dan masukkan 6 digit kode OTP dari email Anda.';
                }
                if (otpInput) {
                    otpInput.focus();
                }
                return false;
            }
        }
    });
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeEditProfileModal();
    }
});

@if($errors->any())
document.addEventListener('DOMContentLoaded', () => {
    openEditProfileModal();
    @if($errors->has('otp_code') || $errors->has('password') || old('otp_code'))
        handlePasswordChangeInput();
        const otpWrapper = document.getElementById('otpSectionWrapper');
        if (otpWrapper) otpWrapper.style.display = 'block';
        @if($errors->has('otp_code'))
            const statusAlert = document.getElementById('otpStatusAlert');
            if (statusAlert) {
                statusAlert.style.display = 'block';
                statusAlert.style.color = '#dc2626';
                statusAlert.innerHTML = '✕ {{ $errors->first('otp_code') }}';
            }
        @endif
    @endif
});
@endif
</script>
@endsection

