@extends('layouts.admin')

@section('title', 'Kelola Anggota Admin & Hak Akses - Perpustakaan Kota Bogor')

@section('content')
<div style="padding-bottom: 40px;">

    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: #64748b; margin-bottom: 16px;">
        <a href="{{ route('admin.dashboard') }}" style="color: #64748b; text-decoration: none;">Dashboard</a>
        <span>/</span>
        <span style="color: #0f172a; font-weight: 700;">Kelola Anggota Admin</span>
    </div>

    <!-- Header Section -->
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.55rem; font-weight: 800; color: #0f172a; margin: 0 0 4px 0; display: flex; align-items: center; gap: 10px;">
                <span>Kelola Anggota Admin & Peran</span>
                <span style="background: #f3e8ff; color: #7e22ce; font-size: 0.72rem; font-weight: 800; padding: 2px 10px; border-radius: 9999px; border: 1px solid #e9d5ff;">
                    👑 Khusus Super Admin
                </span>
            </h1>
            <p style="margin: 0; font-size: 0.86rem; color: #64748b;">
                Tambahkan staf admin perpustakaan baru, kelola kredensial akun, serta tentukan tingkatan peran (Super Admin atau Admin).
            </p>
        </div>
        <div>
            <button type="button" onclick="openAddAdminModal()" style="display: inline-flex; align-items: center; gap: 8px; background: #7c3aed; color: #ffffff; border: none; padding: 11px 20px; border-radius: 9999px; font-size: 0.86rem; font-weight: 700; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>+ Tambah Anggota Admin</span>
            </button>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 14px 18px; border-radius: 14px; font-size: 0.88rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 14px 18px; border-radius: 14px; font-size: 0.88rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 14px 18px; border-radius: 14px; font-size: 0.88rem; margin-bottom: 20px;">
            <div style="font-weight: 700; margin-bottom: 6px;">Terdapat kesalahan pada isian formulir:</div>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Metric KPI Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.02);">
            <div style="font-size: 0.78rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Seluruh Pengguna</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $totalUsers }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Akun terdaftar dalam database</div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e9d5ff; border-radius: 16px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.02); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #7c3aed;"></div>
            <div style="font-size: 0.78rem; font-weight: 700; color: #7c3aed; text-transform: uppercase;">Super Admin (Pimpinan)</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #7c3aed; margin-top: 4px;">{{ $totalSuperAdmins }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Kelola peran & seluruh fitur</div>
        </div>

        <div style="background: #ffffff; border: 1px solid #bae6fd; border-radius: 16px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.02); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #0284c7;"></div>
            <div style="font-size: 0.78rem; font-weight: 700; color: #0284c7; text-transform: uppercase;">Admin Petugas</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #0284c7; margin-top: 4px;">{{ $totalRegularAdmins }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Verifikasi reservasi & chat</div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.02); position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: #64748b;"></div>
            <div style="font-size: 0.78rem; font-weight: 700; color: #475569; text-transform: uppercase;">Pelanggan / Peminjam</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin-top: 4px;">{{ $totalCustomers }}</div>
            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Masyarakat & Mahasiswa</div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px 20px; box-shadow: 0 1px 4px rgba(0,0,0,0.02); margin-bottom: 20px;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center; flex: 1; max-width: 700px;">
                <div style="flex: 1; min-width: 220px; position: relative;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau NIP..."
                           style="width: 100%; padding: 9px 14px 9px 36px; border-radius: 9999px; border: 1px solid #cbd5e1; font-size: 0.84rem; box-sizing: border-box; font-family: inherit;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.2" style="position: absolute; left: 12px; top: 12px;">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>

                <div style="min-width: 170px;">
                    <select name="role" onchange="this.form.submit()" style="width: 100%; padding: 9px 14px; border-radius: 9999px; border: 1px solid #cbd5e1; font-size: 0.84rem; background: #ffffff; font-family: inherit; cursor: pointer;">
                        <option value="">Semua Peran (Role)</option>
                        <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>👑 Super Admin</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>🛡️ Admin Biasa</option>
                        <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>👤 Pelanggan (Customer)</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" style="background: #1e293b; color: #ffffff; border: none; padding: 9px 18px; border-radius: 9999px; font-size: 0.82rem; font-weight: 700; cursor: pointer;">
                    Terapkan
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" style="background: #f1f5f9; color: #475569; padding: 9px 14px; border-radius: 9999px; font-size: 0.82rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table of Admin Users -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,0.02);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.86rem;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0; color: #475569; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="padding: 14px 18px;">Anggota Petugas</th>
                        <th style="padding: 14px 18px;">Kontak & NIP</th>
                        <th style="padding: 14px 18px;">Peran (Role)</th>
                        <th style="padding: 14px 18px;">Waktu Didaftarkan</th>
                        <th style="padding: 14px 18px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adminUsers as $staff)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s ease;">
                            <td style="padding: 14px 18px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if($staff->avatar)
                                        <img src="{{ $staff->avatar }}" alt="{{ $staff->name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1.5px solid #cbd5e1;" referrerpolicy="no-referrer">
                                    @else
                                        <div style="width: 38px; height: 38px; border-radius: 50%; background: {{ $staff->isSuperAdmin() ? '#7c3aed' : ($staff->isAdmin() ? '#1e293b' : '#64748b') }}; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem;">
                                            {{ strtoupper(substr($staff->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                                            <span>{{ $staff->name }}</span>
                                            @if($staff->id === Auth::id())
                                                <span style="background: #e2e8f0; color: #334155; font-size: 0.68rem; padding: 1px 6px; border-radius: 9999px;">Anda</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 0.78rem; color: #64748b;">{{ $staff->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 14px 18px;">
                                <div style="color: #334155; font-weight: 600;">
                                    {{ $staff->phone_number ?: 'Nomor HP: -' }}
                                </div>
                                <div style="font-size: 0.78rem; color: #94a3b8;">
                                    NIP/Identitas: {{ $staff->identity_number ?: '-' }}
                                </div>
                            </td>
                            <td style="padding: 14px 18px;">
                                @if($staff->isSuperAdmin())
                                    <span style="background: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; font-size: 0.74rem; font-weight: 800; padding: 4px 12px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;">
                                        👑 Super Admin
                                    </span>
                                @elseif($staff->isAdmin())
                                    <span style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 0.74rem; font-weight: 800; padding: 4px 12px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;">
                                        🛡️ Admin
                                    </span>
                                @else
                                    <span style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-size: 0.74rem; font-weight: 800; padding: 4px 12px; border-radius: 9999px; display: inline-flex; align-items: center; gap: 4px;">
                                        👤 Pelanggan
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 14px 18px; color: #64748b; font-size: 0.8rem;">
                                {{ $staff->created_at ? $staff->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td style="padding: 14px 18px; text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 8px;">
                                    <button type="button" 
                                            onclick="openEditAdminModal({{ json_encode($staff) }})"
                                            style="background: #f1f5f9; color: #0f172a; border: 1px solid #cbd5e1; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;"
                                            title="Ubah Data & Peran">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        <span>Edit</span>
                                    </button>

                                    @if($staff->id !== Auth::id())
                                        <form action="{{ route('admin.users.destroy', $staff->id) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin {{ $staff->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    style="background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;"
                                                    title="Hapus Akun">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    @else
                                        <span style="font-size: 0.72rem; color: #94a3b8; font-style: italic;">Akun Anda</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8;">
                                <div style="font-size: 2rem; margin-bottom: 8px;">👥</div>
                                <div style="font-weight: 700; color: #475569;">Tidak ada data anggota admin yang sesuai.</div>
                                <div style="font-size: 0.8rem; margin-top: 4px;">Coba atur ulang kata kunci pencarian Anda.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($adminUsers->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0;">
                {{ $adminUsers->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Tambah Anggota Admin Baru -->
<div id="addAdminModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="max-width: 540px; width: 100%; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2); animation: modalIn 0.2s ease;">
        <div style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); padding: 20px 24px; color: #ffffff; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #ffffff;">+ Tambah Anggota Admin Baru</h3>
                <p style="margin: 3px 0 0 0; font-size: 0.8rem; color: #e9d5ff;">Buat akun petugas atau super admin perpustakaan.</p>
            </div>
            <button type="button" onclick="closeAddAdminModal()" style="background: rgba(255,255,255,0.2); border: none; color: #ffffff; width: 32px; height: 32px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" style="padding: 24px;">
            @csrf

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">Nama Lengkap Staf / Pegawai <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" required placeholder="Contoh: Rian Prasetyo, S.Kom"
                       style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">Email Kedinasan Resmi <span style="color: #ef4444;">*</span></label>
                <input type="email" name="email" required placeholder="staf.perpus@kotabogor.go.id"
                       style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">Tingkatan Peran (Role) <span style="color: #ef4444;">*</span></label>
                    <select name="role" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit; background: #ffffff; cursor: pointer;">
                        <option value="customer" selected>👤 Pelanggan (Masyarakat / Mahasiswa)</option>
                        <option value="admin">🛡️ Admin (Petugas Biasa)</option>
                        <option value="super_admin">👑 Super Admin (Pimpinan)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">Nomor HP / WhatsApp</label>
                    <input type="text" name="phone_number" placeholder="0812xxxxxxxx"
                           style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">NIP / Nomor Identitas Pegawai (Opsional)</label>
                <input type="text" name="identity_number" placeholder="Contoh: 19850101 201001 1 002"
                       style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 22px;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">Kata Sandi Awal <span style="color: #ef4444;">*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="add_password" required minlength="6" placeholder="Minimal 6 karakter"
                               style="width: 100%; padding: 10px 38px 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
                        <button type="button" onclick="toggleUserModalPassword('add_password', this)" title="Lihat / Sembunyikan"
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 2px; display: flex; align-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">Konfirmasi Sandi <span style="color: #ef4444;">*</span></label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="add_password_confirmation" required minlength="6" placeholder="Ketik ulang sandi"
                               style="width: 100%; padding: 10px 38px 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
                        <button type="button" onclick="toggleUserModalPassword('add_password_confirmation', this)" title="Lihat / Sembunyikan"
                                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 2px; display: flex; align-items: center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeAddAdminModal()" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 18px; border-radius: 9999px; font-weight: 600; font-size: 0.86rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: #7c3aed; color: #ffffff; border: none; padding: 10px 22px; border-radius: 9999px; font-weight: 700; font-size: 0.86rem; cursor: pointer; box-shadow: 0 4px 10px rgba(124, 58, 237, 0.25);">
                    Simpan Data Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Anggota / Pengguna -->
<div id="editAdminModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="max-width: 540px; width: 100%; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2); animation: modalIn 0.2s ease;">
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 20px 24px; color: #ffffff; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h3 style="margin: 0; font-size: 1.2rem; font-weight: 800; color: #ffffff;">Edit Data & Peran Pengguna</h3>
                <p style="margin: 3px 0 0 0; font-size: 0.8rem; color: #94a3b8;">Ubah peran akun antara Super Admin, Admin, atau Pelanggan.</p>
            </div>
            <button type="button" onclick="closeEditAdminModal()" style="background: rgba(255,255,255,0.2); border: none; color: #ffffff; width: 32px; height: 32px; border-radius: 50%; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>

        <form id="editAdminForm" method="POST" style="padding: 24px;">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                <input type="text" name="name" id="edit_name" required
                       style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">Email Kedinasan / Akun <span style="color: #ef4444;">*</span></label>
                <input type="email" name="email" id="edit_email" required
                       style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">Tingkatan Peran (Role) <span style="color: #ef4444;">*</span></label>
                    <select name="role" id="edit_role" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit; background: #ffffff; cursor: pointer;">
                        <option value="customer">👤 Pelanggan (Masyarakat / Mahasiswa)</option>
                        <option value="admin">🛡️ Admin (Petugas Biasa)</option>
                        <option value="super_admin">👑 Super Admin (Pimpinan)</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">Nomor HP / WhatsApp</label>
                    <input type="text" name="phone_number" id="edit_phone_number"
                           style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
                </div>
            </div>

            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">NIP / Nomor Identitas Pegawai (Opsional)</label>
                <input type="text" name="identity_number" id="edit_identity_number"
                       style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
            </div>

            <div style="margin-bottom: 22px;">
                <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 5px;">
                    Kata Sandi Baru (Kosongkan jika tidak ingin mengubah)
                </label>
                <div style="position: relative;">
                    <input type="password" name="password" id="edit_password" minlength="6" placeholder="Masukkan sandi baru (min 6 karakter) jika ingin reset"
                           autocomplete="new-password"
                           style="width: 100%; padding: 10px 38px 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; box-sizing: border-box; font-family: inherit;">
                    <button type="button" onclick="toggleUserModalPassword('edit_password', this)" title="Lihat / Sembunyikan Sandi"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94a3b8; padding: 2px; display: flex; align-items: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-open"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="eye-closed" style="display: none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                    </button>
                </div>
                <div style="font-size: 0.74rem; color: #64748b; margin-top: 4px;">Minimal 6 karakter. Kosongkan jika kata sandi akun ini tidak ingin diubah.</div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeEditAdminModal()" style="background: #f1f5f9; color: #475569; border: none; padding: 10px 18px; border-radius: 9999px; font-weight: 600; font-size: 0.86rem; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: #0f172a; color: #ffffff; border: none; padding: 10px 22px; border-radius: 9999px; font-weight: 700; font-size: 0.86rem; cursor: pointer;">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleUserModalPassword(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const openIcon = btn.querySelector('.eye-open');
        const closedIcon = btn.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            if (openIcon) openIcon.style.display = 'none';
            if (closedIcon) closedIcon.style.display = 'inline';
        } else {
            input.type = 'password';
            if (openIcon) openIcon.style.display = 'inline';
            if (closedIcon) closedIcon.style.display = 'none';
        }
    }

    function openAddAdminModal() {
        const pAdd = document.getElementById('add_password');
        const pAddConf = document.getElementById('add_password_confirmation');
        if (pAdd) { pAdd.value = ''; pAdd.type = 'password'; }
        if (pAddConf) { pAddConf.value = ''; pAddConf.type = 'password'; }
        const modal = document.getElementById('addAdminModal');
        if (modal) modal.style.display = 'flex';
    }

    function closeAddAdminModal() {
        const modal = document.getElementById('addAdminModal');
        if (modal) modal.style.display = 'none';
    }

    function openEditAdminModal(staff) {
        const form = document.getElementById('editAdminForm');
        form.action = "{{ url('admin/users') }}/" + staff.id;

        document.getElementById('edit_name').value = staff.name || '';
        document.getElementById('edit_email').value = staff.email || '';
        document.getElementById('edit_phone_number').value = staff.phone_number || '';
        document.getElementById('edit_identity_number').value = staff.identity_number || '';

        const editPassword = document.getElementById('edit_password');
        if (editPassword) {
            editPassword.value = '';
            editPassword.type = 'password';
        }

        const roleSelect = document.getElementById('edit_role');
        if (staff.role === 'super_admin' || staff.role === 'superadmin') {
            roleSelect.value = 'super_admin';
        } else if (staff.role === 'admin' || staff.role === 'petugas') {
            roleSelect.value = 'admin';
        } else {
            roleSelect.value = 'customer';
        }

        const modal = document.getElementById('editAdminModal');
        if (modal) modal.style.display = 'flex';
    }

    function closeEditAdminModal() {
        const editPassword = document.getElementById('edit_password');
        if (editPassword) editPassword.value = '';
        const modal = document.getElementById('editAdminModal');
        if (modal) modal.style.display = 'none';
    }

    // Close modal on click outside
    window.addEventListener('click', function(e) {
        const addModal = document.getElementById('addAdminModal');
        const editModal = document.getElementById('editAdminModal');
        if (e.target === addModal) closeAddAdminModal();
        if (e.target === editModal) closeEditAdminModal();
    });
</script>
@endsection

