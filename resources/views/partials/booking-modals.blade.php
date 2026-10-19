@php
    $modalRooms = $rooms ?? \App\Models\Room::where('status', '!=', 'tidak_aktif')->get();
    $authCustomer = auth()->check() ? auth()->user() : null;
@endphp

<!-- ========================================================================= -->
<!-- MODAL: FORM PENGAJUAN BOOKING -->
<!-- ========================================================================= -->
<div class="modal-backdrop" id="bookingModal" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal Header with Library Silhouette Watermark -->
        <div class="modal-header">
            <div class="modal-header-watermark" aria-hidden="true">
                <svg width="110" height="55" viewBox="0 0 110 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M55 4L10 20H100L55 4Z" fill="#2563eb" fill-opacity="0.25"/>
                    <rect x="8" y="20" width="94" height="3.5" rx="1" fill="#2563eb" fill-opacity="0.3"/>
                    <rect x="16" y="23.5" width="7" height="25" rx="1.5" fill="#3b82f6" fill-opacity="0.25"/>
                    <rect x="35" y="23.5" width="7" height="25" rx="1.5" fill="#3b82f6" fill-opacity="0.25"/>
                    <rect x="52" y="23.5" width="7" height="25" rx="1.5" fill="#3b82f6" fill-opacity="0.25"/>
                    <rect x="69" y="23.5" width="7" height="25" rx="1.5" fill="#3b82f6" fill-opacity="0.25"/>
                    <rect x="87" y="23.5" width="7" height="25" rx="1.5" fill="#3b82f6" fill-opacity="0.25"/>
                    <rect x="6" y="48.5" width="98" height="4" rx="1" fill="#2563eb" fill-opacity="0.3"/>
                </svg>
            </div>
            <div class="modal-header-content">
                <div class="modal-header-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                <div class="modal-title-group">
                    <h3>Formulir Reservasi Fasilitas</h3>
                    <p>Lengkapi formulir untuk mengajukan permohonan fasilitas di Perpustakaan dan Galeri Kota Bogor.</p>
                </div>
            </div>
            <button type="button" class="modal-close" onclick="closeBookingModal()" aria-label="Tutup">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" id="formBookingPublic">
            @csrf
            <div class="modal-body">
                
                @if($errors->any())
                    <div style="background: var(--accent-rose-light); border: 1px solid var(--accent-rose); border-radius: var(--radius-md); padding: 14px; margin-bottom: 20px; color: #9f1239; font-size: 0.88rem;">
                        <strong>Perhatian:</strong>
                        <ul style="margin-left: 18px; margin-top: 6px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Section 1: Pilih Fasilitas / Ruangan -->
                <div class="form-section-block">
                    <div class="form-section-header">
                        <div class="section-header-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18"></path><path d="M5 21V10"></path><path d="M19 21V10"></path><path d="M9 21V10"></path><path d="M15 21V10"></path><path d="M2 10l10-6 10 6"></path>
                            </svg>
                        </div>
                        <h4 class="section-header-title">Pilih Fasilitas / Ruangan</h4>
                    </div>

                    <div class="form-input-icon-wrap">
                        <span class="input-leading-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18"></path><path d="M5 21V10"></path><path d="M19 21V10"></path><path d="M9 21V10"></path><path d="M15 21V10"></path><path d="M2 10l10-6 10 6"></path>
                            </svg>
                        </span>
                        <select name="room_id" id="room_id" class="form-control" required>
                            @foreach($modalRooms as $r)
                                <option value="{{ $r->id }}" 
                                        data-category="{{ $r->category }}"
                                        data-name="{{ $r->name }}"
                                        data-floor="{{ $r->floor }}"
                                        data-capacity="{{ $r->capacity }}"
                                        data-image="{{ $r->card_image }}"
                                        {{ (old('room_id', 1) == $r->id) ? 'selected' : '' }}>
                                    ({{ $r->category === 'layar_iklan' ? 'Videotron' : 'Ruangan' }}) {{ $r->name }} - {{ $r->floor }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selected Facility Preview Card -->
                    <div id="selectedFacilityInfo" class="facility-preview-card">
                        <div class="facility-preview-pin">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                        </div>
                        <img id="selectedFacilityImg" src="{{ asset('images/rooms/auditorium/auditorium1.jpg') }}" alt="Preview Ruangan" class="facility-preview-thumb">
                        <div class="facility-preview-details">
                            <h5 id="selectedFacilityName" class="facility-preview-name">Auditorium</h5>
                            <div class="facility-preview-meta-row">
                                <span class="facility-meta-chip">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 21h18"></path><path d="M5 21V10"></path><path d="M19 21V10"></path><path d="M9 21V10"></path><path d="M15 21V10"></path><path d="M2 10l10-6 10 6"></path>
                                    </svg>
                                    <span id="selectedFacilityFloor">Lantai 2</span>
                                </span>
                                <span class="facility-meta-chip">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                    </svg>
                                    <span id="selectedFacilityCapacity">Kapasitas: 72 Orang</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Data Pemohon -->
                <div class="form-section-block">
                    <div class="form-section-header" style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div class="section-header-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <h4 class="section-header-title">Data Pemohon</h4>
                        </div>
                        @if($authCustomer)
                            <span style="font-size: 0.75rem; color: #166534; background: #dcfce7; border: 1px solid #bbf7d0; padding: 3px 10px; border-radius: 9999px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Akun: {{ $authCustomer->name }}
                            </span>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Nama Lengkap Pemohon <span class="req">*</span></label>
                            <div class="form-input-icon-wrap">
                                <span class="input-leading-icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Ahmad Fauzi" value="{{ old('name', $authCustomer?->name ?? '') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="identity_number" class="form-label">NIM / NIP / No. Identitas <span class="req">*</span></label>
                            <div class="form-input-icon-wrap">
                                <span class="input-leading-icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                        <circle cx="9" cy="10" r="2"></circle>
                                        <line x1="15" y1="8" x2="17" y2="8"></line>
                                        <line x1="15" y1="12" x2="17" y2="12"></line>
                                        <line x1="7" y1="16" x2="17" y2="16"></line>
                                    </svg>
                                </span>
                                <input type="text" name="identity_number" id="identity_number" class="form-control" placeholder="Contoh: 2201010045" value="{{ old('identity_number', $authCustomer?->identity_number ?? '') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label">Email Aktif <span class="req">*</span></label>
                            <div class="form-input-icon-wrap">
                                <span class="input-leading-icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="nama@kampus.ac.id" value="{{ old('email', $authCustomer?->email ?? '') }}" required>
                            </div>
                            @if($authCustomer)
                                <div class="form-hint" style="color: #059669;">Tersinkronisasi otomatis dengan akun portal pelanggan Anda.</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone_number" class="form-label">Nomor WhatsApp / HP <span class="req">*</span></label>
                            <div class="form-input-icon-wrap">
                                <span class="input-leading-icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </span>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="081234567890" value="{{ old('phone_number', $authCustomer?->phone_number ?? '') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Tanggal & Estimasi Peserta / Layanan Videotron -->
                <div class="form-section-block">
                    <div class="form-section-header">
                        <div class="section-header-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <h4 class="section-header-title" id="section3Title">Tanggal & Estimasi Peserta</h4>
                    </div>

                    <div class="form-row">
                        <!-- Kolom 1: Tanggal Peminjaman (Ruangan) atau Tanggal Pesanan (Videotron) -->
                        <div class="form-group">
                            <label for="booking_date" class="form-label" id="labelBookingDate">Tanggal Peminjaman <span class="req">*</span></label>
                            <div class="form-input-icon-wrap">
                                <span class="input-leading-icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </span>
                                <input type="date" name="booking_date" id="booking_date" class="form-control" min="{{ date('Y-m-d') }}" value="{{ old('booking_date', date('Y-m-d')) }}">
                            </div>
                            <div class="form-hint" id="hintBookingDate" style="display: none;">Tanggal awal dimulainya pemesanan penayangan di videotron lobi.</div>
                        </div>

                        <!-- Kolom 2 untuk RUANGAN: Estimasi Jumlah Peserta -->
                        <div class="form-group room-only-field" id="wrapRoomParticipant">
                            <label for="participant_count" class="form-label" id="labelParticipantCount">Estimasi Jumlah Peserta</label>
                            <div class="form-input-icon-wrap">
                                <span class="input-leading-icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <input type="number" name="participant_count" id="participant_count" class="form-control" min="1" placeholder="1" value="{{ old('participant_count', 1) }}">
                            </div>
                            <div class="form-hint" id="hintParticipantCount">Perkiraan jumlah orang yang akan hadir di ruangan.</div>
                        </div>

                        <!-- Kolom 2 untuk VIDEOTRON: Tanggal Selesai Tayang -->
                        <div class="form-group screen-only-field" id="wrapScreenEndDate" style="display: none;">
                            <label for="end_date" class="form-label">Tanggal Selesai Tayang <span class="req">*</span></label>
                            <div class="form-input-icon-wrap">
                                <span class="input-leading-icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </span>
                                <input type="date" name="end_date" id="end_date" class="form-control" min="{{ date('Y-m-d') }}" value="{{ old('end_date', date('Y-m-d', strtotime('+7 days'))) }}" disabled>
                            </div>
                            <div class="form-hint">Maksimal penayangan standar 7–14 hari per slot.</div>
                        </div>
                    </div>

                    <!-- Row Tambahan Khusus VIDEOTRON: Estimasi Target Audiens & Format Tayang -->
                    <div class="form-row screen-only-field" id="wrapScreenAudienceRow" style="display: none; margin-top: 14px;">
                        <div class="form-group">
                            <label for="screen_participant_count" class="form-label" id="labelScreenAudience">
                                Estimasi Target Audiens / Penonton
                            </label>
                            <div class="form-input-icon-wrap">
                                <span class="input-leading-icon">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </span>
                                <input type="number" id="screen_participant_count" class="form-control" min="1" placeholder="Contoh: 500 penonton / hari" value="{{ old('participant_count', 100) }}" oninput="document.getElementById('participant_count').value = this.value">
                            </div>
                            <div class="form-hint">Perkiraan pengunjung lobi perpustakaan yang ditargetkan melihat tayangan per hari.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sistem Penayangan Videotron</label>
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: var(--radius-sm); padding: 10px 14px; font-size: 0.82rem; color: #475569; display: flex; align-items: flex-start; gap: 10px; min-height: 44px;">
                                <span style="font-size: 1.15rem; line-height: 1.2;">📺</span>
                                <div>
                                    <strong style="color: #0f172a; display: block; margin-bottom: 2px;">Looping Konten Otomatis</strong>
                                    Ditayangkan berulang secara kontinu di videotron lobi utama selama jam operasional perpustakaan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Durasi Reservasi (Khusus Ruangan) -->
                <div class="form-section-block room-only-field">
                    <div class="form-section-header">
                        <div class="section-header-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <h4 class="section-header-title">Durasi Reservasi</h4>
                    </div>

                    <input type="hidden" name="is_full_day" id="is_full_day" value="0">
                    <div class="booking-type-grid">
                        <!-- Option Card 1: Kustom -->
                        <label class="booking-type-card selected" id="labelTypeHourly">
                            <input type="radio" name="booking_type" value="hourly" id="radioTypeHourly" checked onchange="toggleBookingType('hourly')">
                            <div class="type-card-indicator"></div>
                            <div class="type-card-body">
                                <div class="type-card-title-row">
                                    <div class="type-card-icon-circle blue-circle">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                    <span class="type-card-title-text">Pilih Jam Sendiri (Kustom)</span>
                                </div>
                                <div class="type-card-desc">Atur sendiri waktu reservasi sesuai kebutuhan.</div>
                            </div>
                        </label>

                        <!-- Option Card 2: Full Day -->
                        <label class="booking-type-card" id="labelTypeFullDay">
                            <input type="radio" name="booking_type" value="fullday" id="radioTypeFullDay" onchange="toggleBookingType('fullday')">
                            <div class="type-card-indicator"></div>
                            <div class="type-card-body">
                                <div class="type-card-title-row">
                                    <span class="star-icon">⭐</span>
                                    <span class="type-card-title-text">Satu Hari Penuh (Full Day)</span>
                                    <span class="type-recom-chip">PILIHAN FAVORIT</span>
                                </div>
                                <div class="type-card-fullday-sub">
                                    <div class="type-card-icon-circle amber-circle">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </div>
                                    <span id="fullDayDescText">08:00 – 15:00 WIB (Seharian Operasional)</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Panduan Ketersediaan Jam Hari Ini -->
                    <div class="modal-slot-guide-box" id="modalSlotHelperWrap">
                        <div class="modal-slot-guide-top">
                            <div class="modal-slot-guide-title-wrap">
                                <div class="modal-slot-guide-title">
                                    <span class="guide-dot"></span>
                                    <strong>Panduan Ketersediaan Jam Hari Ini</strong>
                                </div>
                                <div class="modal-slot-guide-desc" id="modalSlotHelperNotice">Klik jam hijau untuk memilih waktu, otomatis terisi pada formulir.</div>
                            </div>
                            <div class="slot-avail-badge">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span>Tersedia</span>
                            </div>
                        </div>
                        <div id="modalSlotChipsContainer" class="modal-slot-pills-grid">
                            <!-- Injected by JS -->
                        </div>
                    </div>

                    <!-- Jam Mulai & Jam Selesai Row -->
                    <div id="hourlyTimeFieldsRow" style="margin-top: 14px;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="start_time" class="form-label" id="labelStartTime">Jam Mulai <span class="req">*</span></label>
                                <div class="form-input-icon-wrap">
                                    <span class="input-leading-icon">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </span>
                                    <select name="start_time" id="start_time" class="form-control" required onchange="handleTimeDropdownChange()">
                                        <option value="" disabled>Pilih waktu mulai</option>
                                        <option value="08:00" {{ old('start_time') == '08:00' ? 'selected' : '' }}>08:00</option>
                                        <option value="09:00" {{ old('start_time', '09:00') == '09:00' ? 'selected' : '' }}>09:00</option>
                                        <option value="10:00" {{ old('start_time') == '10:00' ? 'selected' : '' }}>10:00</option>
                                        <option value="11:00" {{ old('start_time') == '11:00' ? 'selected' : '' }}>11:00</option>
                                        <option value="12:00" {{ old('start_time') == '12:00' ? 'selected' : '' }}>12:00</option>
                                        <option value="13:00" {{ old('start_time') == '13:00' ? 'selected' : '' }}>13:00</option>
                                        <option value="14:00" {{ old('start_time') == '14:00' ? 'selected' : '' }}>14:00</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="end_time" class="form-label" id="labelEndTime">Jam Selesai <span class="req">*</span></label>
                                <div class="form-input-icon-wrap">
                                    <span class="input-leading-icon">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </span>
                                    <select name="end_time" id="end_time" class="form-control" required onchange="handleTimeDropdownChange()">
                                        <option value="" disabled>Pilih waktu selesai</option>
                                        <option value="09:00" {{ old('end_time') == '09:00' ? 'selected' : '' }}>09:00</option>
                                        <option value="10:00" {{ old('end_time') == '10:00' ? 'selected' : '' }}>10:00</option>
                                        <option value="11:00" {{ old('end_time', '11:00') == '11:00' ? 'selected' : '' }}>11:00</option>
                                        <option value="12:00" {{ old('end_time') == '12:00' ? 'selected' : '' }}>12:00</option>
                                        <option value="13:00" {{ old('end_time') == '13:00' ? 'selected' : '' }}>13:00</option>
                                        <option value="14:00" {{ old('end_time') == '14:00' ? 'selected' : '' }}>14:00</option>
                                        <option value="15:00" {{ old('end_time') == '15:00' ? 'selected' : '' }}>15:00</option>
                                        <option value="16:00" {{ old('end_time') == '16:00' ? 'selected' : '' }}>16:00</option>
                                        <option value="17:00" {{ old('end_time') == '17:00' ? 'selected' : '' }}>17:00</option>
                                        <option value="18:00" {{ old('end_time') == '18:00' ? 'selected' : '' }}>18:00</option>
                                        <option value="19:00" {{ old('end_time') == '19:00' ? 'selected' : '' }}>19:00</option>
                                        <option value="20:00" {{ old('end_time') == '20:00' ? 'selected' : '' }}>20:00</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Keperluan Peminjaman Ruangan -->
                <div class="form-section-block">
                    <div class="form-section-header">
                        <div class="section-header-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <h4 class="section-header-title" id="purposeLabel">Keperluan Peminjaman Ruangan <span class="req">*</span></h4>
                    </div>

                    <div class="textarea-count-wrap">
                        <textarea name="purpose" id="purpose" rows="3" maxlength="500" placeholder="Contoh: Rapat kerja divisi ormawa / Diskusi kelompok tugas akhir" required oninput="updatePurposeCounter(this)">{{ old('purpose') }}</textarea>
                        <span class="char-counter-text" id="purposeCharCounter">0/500</span>
                    </div>
                </div>

                <!-- Section 6: Unggah Surat Permohonan / Berkas Acara (Opsional) -->
                <div class="form-section-block" id="attachmentGroup">
                    <div class="form-section-header">
                        <div class="section-header-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <h4 class="section-header-title">
                            <span id="attachmentSectionTitle">Unggah Surat Permohonan / Berkas Acara</span>
                            <span class="section-header-optional">(Opsional)</span>
                        </h4>
                    </div>

                    <label class="modal-dropzone-box" for="attachment">
                        <div class="modal-dropzone-icon">
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <div class="modal-dropzone-title" id="fileUploadName">Klik untuk memilih surat permohonan / proposal acara</div>
                        <div class="modal-dropzone-hint" id="attachmentHint">Format PDF, JPG, atau PNG surat permohonan/resmi (Maksimal 10MB)</div>
                        <input type="file" name="attachment" id="attachment" accept=".jpg,.jpeg,.png,.pdf" style="display: none;">
                    </label>
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn-submit-reservation" id="btnSubmitBooking">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        <span>Kirim Permohonan Reservasi</span>
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: CEK STATUS BOOKING -->
<!-- ========================================================================= -->
<div class="modal-backdrop" id="statusModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 520px;">
        <div class="modal-header">
            <div class="modal-title-group">
                <h3>Cek Status Reservasi</h3>
                <p>Pantau persetujuan peminjaman ruangan atau layar iklan Anda.</p>
            </div>
            <button type="button" class="modal-close" onclick="closeStatusModal()" aria-label="Tutup">&times;</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label for="status_code_input" class="form-label">Kode Booking Anda</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="status_code_input" class="form-control" placeholder="Contoh: BKG-2024-001" style="font-family: monospace; letter-spacing: 0.05em; text-transform: uppercase;">
                    <button type="button" class="btn btn-primary btn-pill" id="btnSearchStatus" style="padding: 10px 22px;">
                        Periksa
                    </button>
                </div>
            </div>

            <!-- Hasil Pencarian -->
            <div id="statusResultArea"></div>

            <div style="margin-top: 20px; padding-top: 16px; border-top: 1px dashed #e2e8f0; text-align: center;">
                <span style="font-size: 0.82rem; color: #64748b;">Lupa kode atau ingin melihat seluruh pesanan?</span>
                <a href="{{ route('booking.history') }}" style="display: inline-block; margin-left: 6px; font-size: 0.82rem; font-weight: 700; color: #2563eb; text-decoration: none;">
                    Buka Riwayat Pemesanan &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: BUKTI PENGAJUAN SUKSES (TICKET MODAL) -->
<!-- ========================================================================= -->
@if(session('success_booking'))
    @php
        $b = session('success_booking');
    @endphp
    <div class="modal-backdrop open" id="ticketModal" aria-hidden="false">
        <div class="modal-dialog" style="max-width: 540px;">
            <div class="modal-header">
                <div class="modal-title-group">
                    <h3 style="color: var(--accent-emerald); display: flex; align-items: center; gap: 8px;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Pengajuan Berhasil Dikirim!
                    </h3>
                    <p>Permohonan reservasi Anda telah tercatat di sistem Perpustakaan Dan Galeri Kota Bogor.</p>
                </div>
                <button type="button" class="modal-close" onclick="closeTicketModal()" aria-label="Tutup">&times;</button>
            </div>

            <div class="modal-body">
                <div class="ticket-card">
                    <div class="ticket-header">
                        <div>
                            <span class="ticket-label" style="font-size: 0.75rem; text-transform: uppercase;">Bukti Reservasi Fasilitas</span>
                            <div class="ticket-value" style="font-size: 1.15rem; font-weight: 800;">{{ $b->room->name }}</div>
                        </div>
                        <div class="status-pill status-menunggu">Menunggu Verifikasi</div>
                    </div>

                    <div class="ticket-code-wrap">
                        <span class="ticket-label" style="font-size: 0.75rem; text-transform: uppercase; display: block; margin-bottom: 4px; font-weight: 700;">KODE BOOKING ANDA:</span>
                        <div class="ticket-code">{{ $b->booking_code }}</div>
                        <button type="button" class="btn btn-outline btn-pill btn-sm" style="margin-top: 8px;" onclick="copyBookingCode('{{ $b->booking_code }}')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                            Salin Kode Booking
                        </button>
                    </div>

                    <div class="ticket-details-grid">
                        <div class="ticket-item">
                            <span class="ticket-label">Pemohon</span>
                            <span class="ticket-value">{{ $b->user->name }}</span>
                        </div>
                        <div class="ticket-item">
                            <span class="ticket-label">NIM / NIP</span>
                            <span class="ticket-value">{{ $b->user->identity_number }}</span>
                        </div>
                        <div class="ticket-item">
                            <span class="ticket-label">{{ $b->room->category === 'layar_iklan' ? 'Tanggal Pesanan' : 'Tanggal' }}</span>
                            <span class="ticket-value">{{ $b->booking_date_formatted }}</span>
                        </div>
                        @if($b->room->category === 'layar_iklan')
                            <div class="ticket-item">
                                <span class="ticket-label">Periode Tayang</span>
                                <span class="ticket-value">s/d {{ $b->end_date ? $b->end_date_formatted : '-' }}</span>
                            </div>
                            @if($b->participant_count > 1)
                                <div class="ticket-item">
                                    <span class="ticket-label">Target Audiens</span>
                                    <span class="ticket-value">{{ number_format($b->participant_count) }} Penonton / Hari</span>
                                </div>
                            @endif
                        @else
                            <div class="ticket-item">
                                <span class="ticket-label">Waktu</span>
                                <span class="ticket-value">
                                    @if($b->is_full_day)
                                        <span class="ticket-fullday-badge">⭐ 1 Hari Penuh</span> ({{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB)
                                    @else
                                        <span class="ticket-custom-badge">⏱️ Jam Pilihan</span> {{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="ticket-info-alert" style="background: rgba(16, 185, 129, 0.12); border-left: 3px solid var(--accent-emerald); padding: 12px 16px; border-radius: var(--radius-sm); font-size: 0.85rem; color: #15803d; margin-bottom: 20px;">
                    <strong>Petunjuk:</strong> Harap simpan atau salin kode booking di atas. Anda dapat menggunakan kode tersebut sewaktu-waktu di menu <em>Cek Status</em> untuk memantau proses verifikasi.
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('booking.print', ['code' => $b->booking_code]) }}" target="_blank" class="btn btn-outline btn-pill btn-block" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        <span>Cetak Bukti Resmi</span>
                    </a>
                    <button type="button" class="btn btn-primary btn-pill btn-block" onclick="closeTicketModal()">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof saveBookingToLocalHistory === 'function') {
                saveBookingToLocalHistory('{{ $b->booking_code }}');
            }
        });
    </script>
@endif
