/**
 * SISTEM PEMINJAMAN RUANGAN & LAYAR IKLAN PERPUSTAKAAN
 * Front-end Interaction Logic
 */

document.addEventListener('DOMContentLoaded', () => {
  // Elements
  const bookingModal = document.getElementById('bookingModal');
  const statusModal = document.getElementById('statusModal');
  const ticketModal = document.getElementById('ticketModal');

  const facilitySelect = document.getElementById('room_id');
  const roomOnlyFields = document.querySelectorAll('.room-only-field');
  const screenOnlyFields = document.querySelectorAll('.screen-only-field');
  const purposeLabel = document.getElementById('purposeLabel');
  const purposeInput = document.getElementById('purpose');
  const selectedFacilityInfo = document.getElementById('selectedFacilityInfo');
  const selectedFacilityName = document.getElementById('selectedFacilityName');
  const selectedFacilityMeta = document.getElementById('selectedFacilityMeta');

  // Filter Tabs
  const filterBtns = document.querySelectorAll('.filter-btn');
  const facilityCards = document.querySelectorAll('.facility-card');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filterValue = btn.dataset.filter;

      facilityCards.forEach(card => {
        if (filterValue === 'all' || card.dataset.category === filterValue) {
          card.style.display = 'flex';
          card.style.animation = 'fadeIn 0.3s ease forwards';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // Modal Open & Close Functions
  window.openBookingModal = function (roomId = null, forceFullDay = false) {
    if (bookingModal) {
      bookingModal.classList.add('open');
      document.body.style.overflow = 'hidden';

      if (roomId && facilitySelect) {
        facilitySelect.value = roomId;
        handleFacilityChange();
      } else if (facilitySelect) {
        handleFacilityChange();
      }

      if (forceFullDay) {
        setTimeout(() => toggleBookingType('fullday'), 60);
      } else {
        setTimeout(() => toggleBookingType('hourly'), 60);
      }

      if (typeof refreshModalSlotHelper === 'function') {
        setTimeout(refreshModalSlotHelper, 80);
      }
    }
  };

  window.bookFullDay = function (roomId, date = null) {
    const bookingDateInput = document.getElementById('booking_date');
    if (bookingDateInput && date) {
      bookingDateInput.value = date;
    }
    openBookingModal(roomId, true);
  };

  window.closeBookingModal = function () {
    if (bookingModal) {
      bookingModal.classList.remove('open');
      document.body.style.overflow = '';
    }
  };

  window.openStatusModal = function () {
    if (statusModal) {
      statusModal.classList.add('open');
      document.body.style.overflow = 'hidden';
      const input = document.getElementById('status_code_input');
      if (input) setTimeout(() => input.focus(), 150);
    }
  };

  window.closeStatusModal = function () {
    if (statusModal) {
      statusModal.classList.remove('open');
      document.body.style.overflow = '';
    }
  };

  window.closeTicketModal = function () {
    if (ticketModal) {
      ticketModal.classList.remove('open');
      document.body.style.overflow = '';
    }
  };

  // Close modals on backdrop click
  [bookingModal, statusModal, ticketModal].forEach(modal => {
    if (modal) {
      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.classList.remove('open');
          document.body.style.overflow = '';
        }
      });
    }
  });

  // Handle Facility Selection Change
  function handleFacilityChange() {
    const attachmentGroup = document.getElementById('attachmentGroup');
    const attachmentLabel = document.getElementById('attachmentLabel');
    const attachmentHint = document.getElementById('attachmentHint');
    const fileLabel = document.getElementById('fileUploadName');
    const fileInput = document.getElementById('attachment');

    if (!facilitySelect) return;
    const selectedOption = facilitySelect.options[facilitySelect.selectedIndex];
    if (!selectedOption || !selectedOption.value) {
      if (selectedFacilityInfo) selectedFacilityInfo.style.display = 'none';
      if (attachmentGroup) attachmentGroup.style.display = 'none';
      return;
    }

    const roomId = selectedOption.value;
    const category = selectedOption.dataset.category;
    const name = selectedOption.dataset.name || '';
    const floor = selectedOption.dataset.floor;
    const capacity = selectedOption.dataset.capacity;

    if (selectedFacilityInfo) {
      selectedFacilityInfo.style.display = 'flex';
      if (selectedFacilityName) selectedFacilityName.textContent = name;
      const floorEl = document.getElementById('selectedFacilityFloor');
      const capEl = document.getElementById('selectedFacilityCapacity');
      const imgEl = document.getElementById('selectedFacilityImg');

      if (floorEl) floorEl.textContent = floor || 'Lantai 1';
      if (capEl) {
        capEl.textContent = category === 'layar_iklan'
          ? 'Durasi Slot Tayang Harian'
          : `Kapasitas: ${capacity} Orang`;
      }
      if (imgEl) {
        imgEl.src = selectedOption.dataset.image || '/images/rooms/auditorium/auditorium1.jpg';
        imgEl.alt = name;
      }
    }

    // Berkas pendukung HANYA muncul untuk Auditorium (id=1) dan Videotron (id=4/layar_iklan)
    const isAuditorium = (roomId == '1' || name.toLowerCase().includes('auditorium'));
    const isVideotron = (roomId == '4' || category === 'layar_iklan' || name.toLowerCase().includes('videotron') || name.toLowerCase().includes('layar'));

    if (attachmentGroup) {
      attachmentGroup.style.display = 'block';
      if (isVideotron) {
        if (attachmentLabel) attachmentLabel.textContent = 'Unggah Materi Iklan / Banner (Opsional)';
        if (attachmentHint) attachmentHint.textContent = 'Format JPG, PNG, atau PDF materi tayang (Maksimal 10MB)';
        if (fileLabel && (!fileInput || !fileInput.files.length)) {
          fileLabel.textContent = 'Klik untuk memilih file materi iklan / banner';
        }
      } else {
        // Semua Kategori Ruangan
        if (attachmentLabel) attachmentLabel.textContent = 'Unggah Surat Permohonan / Berkas Acara (Opsional)';
        if (attachmentHint) attachmentHint.textContent = 'Format PDF, JPG, atau PNG surat permohonan/resmi (Maksimal 10MB)';
        if (fileLabel && (!fileInput || !fileInput.files.length)) {
          fileLabel.textContent = 'Klik untuk memilih surat permohonan / proposal acara';
        }
      }
    }

    const section3Title = document.getElementById('section3Title');
    const labelBookingDate = document.getElementById('labelBookingDate');
    const hintBookingDate = document.getElementById('hintBookingDate');
    const endDateInput = document.getElementById('end_date');
    const bDateInput = document.getElementById('booking_date');
    const participantInput = document.getElementById('participant_count');
    const screenAudienceInput = document.getElementById('screen_participant_count');

    if (category === 'layar_iklan') {
      // 1. Sesuaikan Header & Label untuk Layanan Videotron
      if (section3Title) section3Title.textContent = 'Tanggal Pesanan & Periode Tayang Videotron';
      if (labelBookingDate) labelBookingDate.innerHTML = 'Tanggal Pesanan <span class="req">*</span>';
      if (hintBookingDate) hintBookingDate.style.display = 'block';

      // 2. Tampilkan form Videotron, sembunyikan Ruangan
      roomOnlyFields.forEach(el => el.style.display = 'none');
      screenOnlyFields.forEach(el => {
        el.style.display = el.classList.contains('form-row') ? 'grid' : 'block';
      });

      // 3. Aktifkan end_date dan sesuaikan default durasi (+7 hari)
      if (endDateInput) {
        endDateInput.disabled = false;
        if (bDateInput && bDateInput.value) {
          endDateInput.min = bDateInput.value;
          const d = new Date(bDateInput.value + 'T00:00:00');
          d.setDate(d.getDate() + 7);
          const y = d.getFullYear();
          const m = String(d.getMonth() + 1).padStart(2, '0');
          const day = String(d.getDate()).padStart(2, '0');
          if (!endDateInput.value || endDateInput.value < bDateInput.value) {
            endDateInput.value = `${y}-${m}-${day}`;
          }
        }
      }

      // 4. Estimasi Target Audiens / Penonton
      if (participantInput && screenAudienceInput) {
        if (!participantInput.value || participantInput.value === '1') {
          participantInput.value = '100';
          screenAudienceInput.value = '100';
        } else {
          screenAudienceInput.value = participantInput.value;
        }
      }

      if (purposeLabel) purposeLabel.innerHTML = 'Deskripsi Konten Iklan / Pengumuman <span class="req">*</span>';
      if (purposeInput) purposeInput.placeholder = 'Contoh: Penayangan poster informasi Seminar Nasional AI & Workshop Mahasiswa';
    } else {
      // 1. Kembalikan ke format Ruangan
      if (section3Title) section3Title.textContent = 'Tanggal & Estimasi Peserta';
      if (labelBookingDate) labelBookingDate.innerHTML = 'Tanggal Peminjaman <span class="req">*</span>';
      if (hintBookingDate) hintBookingDate.style.display = 'none';

      // 2. Tampilkan Ruangan, sembunyikan Videotron
      roomOnlyFields.forEach(el => {
        el.style.display = el.classList.contains('form-row') ? 'grid' : 'block';
      });
      screenOnlyFields.forEach(el => el.style.display = 'none');

      if (endDateInput) {
        endDateInput.disabled = true;
        if (bDateInput) endDateInput.value = bDateInput.value;
      }

      if (participantInput && (participantInput.value === '100' || !participantInput.value)) {
        participantInput.value = '1';
      }

      if (purposeLabel) purposeLabel.innerHTML = 'Keperluan Peminjaman Ruangan <span class="req">*</span>';
      if (purposeInput) purposeInput.placeholder = 'Contoh: Rapat kerja divisi ormawa / Diskusi kelompok tugas akhir';
    }
  }

  // Helper Jam Operasional Fasilitas
  window.getDayHours = function (dateStr) {
    if (!dateStr) return { start: '08:00', end: '15:00', isOpen: true, isWeekend: false, isFriday: false, label: '08:00 – 15:00 WIB' };
    const d = new Date(dateStr + 'T00:00:00');
    const dow = d.getDay(); // 0=Sunday, 1=Monday, ..., 5=Friday, 6=Saturday
    if (dow === 5) {
      return { start: '08:00', end: '15:00', isOpen: false, isWeekend: false, isFriday: true, label: 'Libur (Jumat Tutup)' };
    } else if (dow === 0 || dow === 6) {
      return { start: '08:00', end: '20:00', isOpen: true, isWeekend: true, isFriday: false, label: '08:00 – 20:00 WIB (12 Jam)' };
    } else {
      return { start: '08:00', end: '15:00', isOpen: true, isWeekend: false, isFriday: false, label: '08:00 – 15:00 WIB (7 Jam)' };
    }
  };

  // Toggle Tipe Booking: Per Jam vs Satu Hari Penuh (Full Day)
  window.toggleBookingType = function (type) {
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const bookingDate = document.getElementById('booking_date');
    const isFullDayInput = document.getElementById('is_full_day');
    const fullDayBanner = document.getElementById('fullDayInfoBanner');
    const fullDayDesc = document.getElementById('fullDayDescText');
    const fullDayTitle = document.getElementById('fullDayBannerTitle');
    const fullDayText = document.getElementById('fullDayBannerDesc');
    const slotHelper = document.getElementById('modalSlotHelperWrap');
    const radioHourly = document.getElementById('radioTypeHourly');
    const radioFullDay = document.getElementById('radioTypeFullDay');
    const cardHourly = document.getElementById('labelTypeHourly');
    const cardFullDay = document.getElementById('labelTypeFullDay');
    const labelStartTime = document.getElementById('labelStartTime');
    const labelEndTime = document.getElementById('labelEndTime');

    const dateVal = bookingDate ? bookingDate.value : todayDateStr;
    const hours = getDayHours(dateVal);

    if (fullDayDesc) {
      fullDayDesc.textContent = hours.isFriday
        ? 'Layanan tutup pada hari Jumat'
        : `${hours.start} – ${hours.end} WIB (Seharian Operasional)`;
    }

    if (type === 'fullday') {
      if (radioFullDay) radioFullDay.checked = true;
      if (cardFullDay) cardFullDay.classList.add('selected');
      if (cardHourly) cardHourly.classList.remove('selected');
      if (isFullDayInput) isFullDayInput.value = '1';

      if (startTime) {
        startTime.value = hours.start;
        startTime.readOnly = true;
        startTime.style.backgroundColor = '#f8fafc';
        startTime.style.cursor = 'not-allowed';
      }
      if (endTime) {
        endTime.value = hours.end;
        endTime.readOnly = true;
        endTime.style.backgroundColor = '#f8fafc';
        endTime.style.cursor = 'not-allowed';
      }

      if (labelStartTime) labelStartTime.innerHTML = 'Jam Mulai <span style="font-size:0.72rem; color:#2563eb; font-weight:700;">(Otomatis Full Day)</span>';
      if (labelEndTime) labelEndTime.innerHTML = 'Jam Selesai <span style="font-size:0.72rem; color:#2563eb; font-weight:700;">(Otomatis Full Day)</span>';

      if (fullDayBanner) fullDayBanner.style.display = 'flex';
      if (slotHelper) slotHelper.style.display = 'none';
      const quickDurWrap = document.getElementById('quickDurationWrap');
      if (quickDurWrap) quickDurWrap.style.display = 'none';

      if (fullDayTitle) fullDayTitle.textContent = `Reservasi 1 Hari Penuh Aktif (${hours.start} – ${hours.end} WIB)`;
      if (fullDayText) {
        if (hours.isFriday) {
          fullDayText.textContent = `Perhatian: Layanan perpustakaan libur pada hari Jumat. Silakan pilih hari selain Jumat.`;
        } else {
          fullDayText.textContent = `Fasilitas dialokasikan secara penuh dari jam ${hours.start} hingga ${hours.end} WIB untuk kegiatan Anda tanpa terbagi jadwal peminjam lain.`;
        }
      }
    } else {
      if (radioHourly) radioHourly.checked = true;
      if (cardHourly) cardHourly.classList.add('selected');
      if (cardFullDay) cardFullDay.classList.remove('selected');
      if (isFullDayInput) isFullDayInput.value = '0';

      if (startTime) {
        startTime.readOnly = false;
        startTime.style.backgroundColor = '';
        startTime.style.cursor = '';
        if (startTime.value === '08:00' && endTime && (endTime.value === '15:00' || endTime.value === '20:00')) {
          startTime.value = '09:00';
        }
      }
      if (endTime) {
        endTime.readOnly = false;
        endTime.style.backgroundColor = '';
        endTime.style.cursor = '';
        if (endTime.value === '15:00' || endTime.value === '20:00') {
          endTime.value = '11:00';
        }
      }

      if (labelStartTime) labelStartTime.innerHTML = 'Jam Mulai (Pilihan Anda) <span class="req">*</span>';
      if (labelEndTime) labelEndTime.innerHTML = 'Jam Selesai (Pilihan Anda) <span class="req">*</span>';

      if (fullDayBanner) fullDayBanner.style.display = 'none';
      const quickDurWrap = document.getElementById('quickDurationWrap');
      if (quickDurWrap) quickDurWrap.style.display = 'block';

      if (slotHelper) {
        const facilitySelect = document.getElementById('room_id');
        const opt = facilitySelect ? facilitySelect.options[facilitySelect.selectedIndex] : null;
        if (opt && opt.dataset.category !== 'layar_iklan') {
          slotHelper.style.display = 'block';
        }
      }

      if (typeof updateDurationSummary === 'function') {
        updateDurationSummary();
      }
    }
  };

  // Live Durasi & Validasi Jam Pilihan Customer
  window.updateDurationSummary = function () {
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const summaryBox = document.getElementById('durationSummaryBadge');
    const summaryText = document.getElementById('durationSummaryText');
    const isFullDayInput = document.getElementById('is_full_day');

    if (!summaryBox || !summaryText || !startTime || !endTime) return;

    if (isFullDayInput && isFullDayInput.value === '1') {
      summaryBox.style.display = 'none';
      return;
    }

    const startVal = startTime.value;
    const endVal = endTime.value;

    if (!startVal || !endVal) {
      summaryBox.style.display = 'none';
      return;
    }

    const [sh, sm] = startVal.split(':').map(Number);
    const [eh, em] = endVal.split(':').map(Number);
    const startMins = sh * 60 + sm;
    const endMins = eh * 60 + em;
    const diffMins = endMins - startMins;

    if (diffMins <= 0) {
      summaryBox.style.display = 'flex';
      summaryBox.className = 'duration-summary-pill duration-invalid';
      summaryBox.innerHTML = `
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <span>Jam selesai harus lebih akhir daripada jam mulai.</span>
      `;
      return;
    }

    const hours = Math.floor(diffMins / 60);
    const mins = diffMins % 60;
    let durText = '';
    if (hours > 0 && mins > 0) {
      durText = `${hours} Jam ${mins} Menit`;
    } else if (hours > 0) {
      durText = `${hours} Jam`;
    } else {
      durText = `${mins} Menit`;
    }

    summaryBox.style.display = 'flex';
    summaryBox.className = 'duration-summary-pill duration-valid';
    summaryBox.innerHTML = `
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
        <polyline points="22 4 12 14.01 9 11.01"></polyline>
      </svg>
      <span><strong>Jam Pilihan Anda:</strong> ${startVal} – ${endVal} WIB (Total ${durText})</span>
    `;

    // Sinkronkan active chip pada quick duration
    document.querySelectorAll('.btn-dur-chip').forEach(btn => {
      const h = parseInt(btn.dataset.hours, 10);
      btn.classList.toggle('active', h * 60 === diffMins);
    });
  };

  // Pilihan Cepat Durasi Jam
  window.setQuickDuration = function (hoursToAdd) {
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const bookingDate = document.getElementById('booking_date');
    if (!startTime || !endTime) return;

    const dateVal = bookingDate ? bookingDate.value : null;
    const dayHours = getDayHours(dateVal);
    const maxEndH = dayHours ? parseInt(dayHours.end.split(':')[0], 10) : 15;
    const maxEndMins = maxEndH * 60;

    const startVal = startTime.value || '09:00';
    const [sh, sm] = startVal.split(':').map(Number);
    let targetEndMins = (sh + hoursToAdd) * 60 + sm;

    if (targetEndMins > maxEndMins) {
      targetEndMins = maxEndMins;
    }

    const eh = Math.floor(targetEndMins / 60);
    const em = targetEndMins % 60;
    const formattedEnd = `${String(eh).padStart(2, '0')}:${String(em).padStart(2, '0')}`;

    endTime.value = formattedEnd;
    updateDurationSummary();

    document.querySelectorAll('.btn-dur-chip').forEach(btn => {
      btn.classList.toggle('active', btn.dataset.hours === String(hoursToAdd));
    });
  };

  if (facilitySelect) {
    facilitySelect.addEventListener('change', () => {
      handleFacilityChange();
      if (typeof refreshModalSlotHelper === 'function') refreshModalSlotHelper();
    });
  }

  const modalBookingDate = document.getElementById('booking_date');
  if (modalBookingDate) {
    modalBookingDate.addEventListener('change', () => {
      const isFullDay = document.getElementById('is_full_day');
      if (isFullDay && isFullDay.value === '1') {
        toggleBookingType('fullday');
      }

      // Jika Videotron dipilih, sesuaikan batas tanggal selesai tayang
      const opt = facilitySelect ? facilitySelect.options[facilitySelect.selectedIndex] : null;
      if (opt && opt.dataset.category === 'layar_iklan') {
        const endDateEl = document.getElementById('end_date');
        if (endDateEl && modalBookingDate.value) {
          endDateEl.min = modalBookingDate.value;
          if (!endDateEl.value || endDateEl.value < modalBookingDate.value) {
            const d = new Date(modalBookingDate.value + 'T00:00:00');
            d.setDate(d.getDate() + 7);
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            endDateEl.value = `${y}-${m}-${day}`;
          }
        }
      }

      if (typeof refreshModalSlotHelper === 'function') refreshModalSlotHelper();
    });
  }

  // File Upload preview label
  const fileInput = document.getElementById('attachment');
  const fileLabel = document.getElementById('fileUploadName');
  if (fileInput && fileLabel) {
    fileInput.addEventListener('change', () => {
      if (fileInput.files.length > 0) {
        fileLabel.innerHTML = `<span style="color:#16a34a;">✓ Terpilih: <strong>${fileInput.files[0].name}</strong></span>`;
      } else {
        fileLabel.textContent = 'Klik untuk memilih surat permohonan / proposal acara';
      }
    });
  }

  // Copy Booking Code
  window.copyBookingCode = function (code) {
    if (!code) return;
    navigator.clipboard.writeText(code).then(() => {
      showToast(`Kode ${code} berhasil disalin ke clipboard!`);
    }).catch(() => {
      // Fallback
      const temp = document.createElement('input');
      temp.value = code;
      document.body.appendChild(temp);
      temp.select();
      document.execCommand('copy');
      document.body.removeChild(temp);
      showToast(`Kode ${code} disalin!`);
    });
  };

  // LocalStorage Booking History Management
  window.saveBookingToLocalHistory = function (code) {
    if (!code) return;
    try {
      let raw = localStorage.getItem('library_booking_history');
      let list = raw ? JSON.parse(raw) : [];
      if (!Array.isArray(list)) list = [];
      if (!list.includes(code)) {
        list.unshift(code);
        list = list.slice(0, 25);
        localStorage.setItem('library_booking_history', JSON.stringify(list));
      }
      if (typeof updateHeaderHistoryBadge === 'function') updateHeaderHistoryBadge();
    } catch (e) {}
  };

  window.updateHeaderHistoryBadge = function () {
    const badge = document.getElementById('headerHistoryBadge');
    const mobileBadge = document.getElementById('mobileHistoryBadge');
    try {
      const raw = localStorage.getItem('library_booking_history');
      const list = raw ? JSON.parse(raw) : [];
      const count = Array.isArray(list) ? list.length : 0;
      if (count > 0) {
        if (badge) {
          badge.textContent = count;
          badge.style.display = 'inline-flex';
        }
        if (mobileBadge) {
          mobileBadge.textContent = count;
          mobileBadge.style.display = 'inline-flex';
        }
      } else {
        if (badge) badge.style.display = 'none';
        if (mobileBadge) mobileBadge.style.display = 'none';
      }
    } catch (e) {
      if (badge) badge.style.display = 'none';
      if (mobileBadge) mobileBadge.style.display = 'none';
    }
  };

  window.toggleMobileNav = function () {
    const drawer = document.getElementById('mobileNavDrawer');
    const backdrop = document.getElementById('mobileNavBackdrop');
    if (drawer && backdrop) {
      const isOpen = drawer.classList.contains('open');
      if (isOpen) {
        window.closeMobileNav();
      } else {
        drawer.classList.add('open');
        backdrop.classList.add('open');
        document.body.style.overflow = 'hidden';
      }
    }
  };

  window.closeMobileNav = function () {
    const drawer = document.getElementById('mobileNavDrawer');
    const backdrop = document.getElementById('mobileNavBackdrop');
    if (drawer) drawer.classList.remove('open');
    if (backdrop) backdrop.classList.remove('open');
    document.body.style.overflow = '';
  };

  // Dynamic ScrollSpy for Navbar Active Links
  function initNavbarScrollSpy() {
    const berandaLink = document.getElementById('navLinkBeranda');
    const fasilitasLink = document.getElementById('navLinkFasilitas');
    const jadwalLink = document.getElementById('navLinkJadwal');
    const alurLink = document.getElementById('navLinkAlur');

    if (!berandaLink) return;

    const allNavLinks = [berandaLink, fasilitasLink, jadwalLink, alurLink].filter(Boolean);

    function setActive(activeEl) {
      allNavLinks.forEach(el => el.classList.remove('active'));
      if (activeEl) {
        activeEl.classList.add('active');
      }

      // Also sync mobile navigation if open
      const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
      mobileNavLinks.forEach(mLink => {
        mLink.classList.remove('active');
        const h = mLink.getAttribute('href') || '';
        if (activeEl === berandaLink && (h === '/beranda' || h === '/' || (!h.includes('#') && !h.includes('riwayat')))) {
          mLink.classList.add('active');
        } else if (activeEl === fasilitasLink && h.includes('#fasilitas')) {
          mLink.classList.add('active');
        } else if (activeEl === jadwalLink && h.includes('#jadwal')) {
          mLink.classList.add('active');
        } else if (activeEl === alurLink && h.includes('#panduan')) {
          mLink.classList.add('active');
        }
      });
    }

    function getAbsoluteTop(el) {
      if (!el) return 0;
      const rect = el.getBoundingClientRect();
      return rect.top + (window.pageYOffset || document.documentElement.scrollTop);
    }

    function onScrollSpy() {
      const fasilitasSec = document.getElementById('fasilitas');
      const jadwalSec = document.getElementById('ketersediaan') || document.getElementById('jadwal');
      const panduanSec = document.getElementById('alur') || document.getElementById('panduan');

      if (!fasilitasSec) return;

      const scrollY = window.pageYOffset || document.documentElement.scrollTop || 0;

      // When near top of the page (< 220px), highlight Beranda
      if (scrollY < 220) {
        setActive(berandaLink);
        return;
      }

      // When scrolled near bottom of page, highlight Panduan (the final section)
      const isAtBottom = (window.innerHeight + scrollY) >= (document.documentElement.scrollHeight - 60);
      if (isAtBottom && alurLink) {
        setActive(alurLink);
        return;
      }

      const scrollPos = scrollY + 160;

      const panduanTop = getAbsoluteTop(panduanSec);
      const jadwalTop = getAbsoluteTop(jadwalSec);
      const fasilitasTop = getAbsoluteTop(fasilitasSec);

      if (panduanTop && scrollPos >= panduanTop) {
        setActive(alurLink);
      } else if (jadwalTop && scrollPos >= jadwalTop) {
        setActive(jadwalLink);
      } else if (fasilitasTop && scrollPos >= fasilitasTop) {
        setActive(fasilitasLink);
      } else {
        setActive(berandaLink);
      }
    }

    let isTicking = false;
    window.addEventListener('scroll', () => {
      if (!isTicking) {
        window.requestAnimationFrame(() => {
          onScrollSpy();
          isTicking = false;
        });
        isTicking = true;
      }
    }, { passive: true });

    // Run on initial load and resize
    window.addEventListener('resize', onScrollSpy, { passive: true });
    requestAnimationFrame(onScrollSpy);

    // In-page smooth scrolling when clicking navbar items on /beranda
    allNavLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        const href = link.getAttribute('href') || '';
        const hashIdx = href.indexOf('#');

        if (link === berandaLink && (window.location.pathname === '/beranda' || window.location.pathname === '/' || window.location.pathname === '')) {
          e.preventDefault();
          window.scrollTo({ top: 0, behavior: 'smooth' });
          setActive(berandaLink);
          if (window.history.pushState) {
            window.history.pushState(null, null, '/beranda');
          }
          return;
        }

        if (hashIdx !== -1 && (window.location.pathname === '/beranda' || window.location.pathname === '/' || window.location.pathname === '')) {
          const targetId = href.substring(hashIdx + 1);
          let targetEl = null;
          if (targetId === 'fasilitas') {
            targetEl = document.getElementById('fasilitas');
          } else if (targetId === 'jadwal') {
            targetEl = document.getElementById('ketersediaan') || document.getElementById('jadwal');
          } else if (targetId === 'panduan') {
            targetEl = document.getElementById('alur') || document.getElementById('panduan');
          } else {
            targetEl = document.getElementById(targetId);
          }

          if (targetEl) {
            e.preventDefault();
            const headerOffset = 75;
            const elementPosition = getAbsoluteTop(targetEl);
            const offsetPosition = elementPosition - headerOffset;

            window.scrollTo({
              top: offsetPosition,
              behavior: 'smooth'
            });

            setActive(link);
            if (window.history.pushState) {
              window.history.pushState(null, null, window.location.pathname + '#' + targetId);
            }
          }
        }
      });
    });

    // Handle hash on page load
    if (window.location.hash) {
      const hash = window.location.hash.replace('#', '');
      if (hash === 'fasilitas') setActive(fasilitasLink);
      else if (hash === 'jadwal' || hash === 'ketersediaan') setActive(jadwalLink);
      else if (hash === 'panduan' || hash === 'alur') setActive(alurLink);
    }
  }

  initNavbarScrollSpy();
  setTimeout(window.updateHeaderHistoryBadge, 150);


  // Toast Function
  window.showToast = function (msg) {
    let toast = document.getElementById('toastNotice');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'toastNotice';
      toast.className = 'toast-notice';
      document.body.appendChild(toast);
    }
    toast.innerHTML = `
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2">
        <path d="M20 6L9 17l-5-5"></path>
      </svg>
      <span>${msg}</span>
    `;
    toast.classList.add('show');
    setTimeout(() => {
      toast.classList.remove('show');
    }, 3500);
  };

  // Ajax Search for Booking Status
  const searchBtn = document.getElementById('btnSearchStatus');
  const searchInput = document.getElementById('status_code_input');
  const searchResultArea = document.getElementById('statusResultArea');

  if (searchBtn && searchInput && searchResultArea) {
    async function doSearch() {
      const code = searchInput.value.trim();
      if (!code) {
        searchInput.focus();
        return;
      }

      searchBtn.disabled = true;
      searchBtn.innerHTML = 'Mencari...';
      searchResultArea.innerHTML = `
        <div style="text-align: center; padding: 24px; color: var(--text-muted);">
          <div class="pulse" style="margin: 0 auto 12px;"></div>
          Memeriksa data reservasi di server...
        </div>
      `;

      try {
        const response = await fetch(`/cek-booking?code=${encodeURIComponent(code)}`, {
          headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();

        if (data.success && data.booking) {
          const b = data.booking;
          const statusClass = `status-${b.status}`;
          const isLayar = b.room.category === 'layar_iklan';

          // Format tanggal rapi tanpa format ISO (misal: "14 Okt 2026" atau "14 Okt 2026 s/d 20 Okt 2026")
          const formatIndoDate = (val) => {
            if (!val) return '';
            const s = String(val).trim();
            if (/^[0-9]{1,2}\s+[A-Za-z]{3,}\s+[0-9]{4}$/.test(s)) {
              return s;
            }
            const datePart = s.split('T')[0].split(' ')[0].trim();
            const parts = datePart.split('-');
            if (parts.length === 3) {
              const y = parts[0];
              const m = parseInt(parts[1], 10);
              const d = parseInt(parts[2], 10);
              const months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
              if (months[m]) {
                return `${d} ${months[m]} ${y}`;
              }
            }
            return datePart;
          };

          const startDateStr = b.booking_date_formatted || formatIndoDate(b.booking_date);
          const rawStart = (b.booking_date || '').toString().split('T')[0].split(' ')[0].trim();
          const rawEnd = (b.end_date || '').toString().split('T')[0].split(' ')[0].trim();
          const hasEndDate = rawEnd && rawEnd !== rawStart;
          const endDateStr = b.end_date_formatted || formatIndoDate(b.end_date);
          const bookingDateDisplay = hasEndDate ? `${startDateStr} s/d ${endDateStr}` : startDateStr;

          searchResultArea.innerHTML = `
            <div class="ticket-card" style="margin-top: 18px;">
              <div class="ticket-header">
                <div>
                  <span class="ticket-label" style="font-size: 0.75rem; text-transform: uppercase;">Kode Reservasi</span>
                  <div style="font-size: 1.35rem; font-weight: 800; color: var(--primary); font-family: monospace;">${b.booking_code}</div>
                </div>
                <div class="status-pill ${statusClass}">${b.status}</div>
              </div>

              <div class="ticket-details-grid">
                <div class="ticket-item">
                  <span class="ticket-label">Fasilitas</span>
                  <span class="ticket-value">${b.room.name}</span>
                </div>
                <div class="ticket-item">
                  <span class="ticket-label">Lokasi</span>
                  <span class="ticket-value">${b.room.floor}</span>
                </div>
                <div class="ticket-item">
                  <span class="ticket-label">Pemohon</span>
                  <span class="ticket-value">${b.user.name}</span>
                </div>
                <div class="ticket-item">
                  <span class="ticket-label">${isLayar ? 'Tanggal Pesanan' : 'Tanggal Booking'}</span>
                  <span class="ticket-value">${bookingDateDisplay}</span>
                </div>
                ${!isLayar ? `
                <div class="ticket-item">
                  <span class="ticket-label">Waktu</span>
                  <span class="ticket-value">${b.start_time.substring(0, 5)} - ${b.end_time.substring(0, 5)} WIB</span>
                </div>
                <div class="ticket-item">
                  <span class="ticket-label">Peserta</span>
                  <span class="ticket-value">${b.participant_count} Orang</span>
                </div>
                ` : `
                ${b.participant_count > 1 ? `
                <div class="ticket-item">
                  <span class="ticket-label">Target Audiens</span>
                  <span class="ticket-value">${b.participant_count} Penonton / Hari</span>
                </div>
                ` : ''}
                `}
              </div>

              <div class="ticket-purpose-section" style="margin-top: 14px; padding-top: 14px; border-top: 1px dashed #cbd5e1;">
                <span class="ticket-label" style="display: block; margin-bottom: 2px;">Keperluan:</span>
                <p class="ticket-purpose-text" style="font-size: 0.9rem; line-height: 1.5; margin: 0;">${b.purpose}</p>
              </div>

              ${b.admin_notes ? `
              <div class="ticket-admin-notes" style="margin-top: 12px; padding: 10px 14px; border-radius: 4px;">
                <span class="ticket-admin-notes-lbl" style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase;">Catatan Petugas:</span>
                <p class="ticket-admin-notes-text" style="font-size: 0.88rem; margin: 2px 0 0 0;">${b.admin_notes}</p>
              </div>
              ` : ''}

              <div style="display: flex; gap: 8px; margin-top: 14px;">
                <button type="button" class="btn btn-outline btn-sm btn-pill" onclick="copyBookingCode('${b.booking_code}')" style="flex: 1;">
                  Salin Kode
                </button>
                <a href="/booking/cetak/${b.booking_code}" target="_blank" class="btn btn-primary btn-sm btn-pill" style="flex: 1.3; text-decoration: none; text-align: center; display: inline-flex; align-items: center; justify-content: center; gap: 4px;">
                  <span>🖨️ Cetak Bukti Resmi</span>
                </a>
              </div>
            </div>
          `;
        } else {
          searchResultArea.innerHTML = `
            <div style="padding: 20px; text-align: center; color: var(--accent-rose); background: rgba(244, 63, 94, 0.1); border-radius: var(--radius-md); margin-top: 18px;">
              <strong>Data Tidak Ditemukan</strong>
              <p style="font-size: 0.85rem; margin-top: 4px; color: var(--text-muted);">${data.message || 'Periksa kembali kode reservasi Anda.'}</p>
            </div>
          `;
        }
      } catch (err) {
        searchResultArea.innerHTML = `
          <div style="padding: 16px; text-align: center; color: var(--accent-rose);">
            Terjadi kesalahan saat memeriksa data. Silakan coba kembali.
          </div>
        `;
      } finally {
        searchBtn.disabled = false;
        searchBtn.innerHTML = 'Periksa';
      }
    }

    searchBtn.addEventListener('click', doSearch);
    searchInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') doSearch();
    });
  }

  // Quick hero search trigger
  const heroSearchInput = document.getElementById('heroBookingCodeInput');
  const heroSearchBtn = document.getElementById('heroSearchBtn');
  if (heroSearchBtn && heroSearchInput) {
    function triggerHeroSearch() {
      const code = heroSearchInput.value.trim();
      if (!code) return;
      openStatusModal();
      if (searchInput) {
        searchInput.value = code;
        if (searchBtn) searchBtn.click();
      }
    }
    heroSearchBtn.addEventListener('click', triggerHeroSearch);
    heroSearchInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') triggerHeroSearch();
    });
  }

  // Initialize schedule explorer on page load
  if (typeof fetchScheduleSlots === 'function') {
    fetchScheduleSlots(currentScheduleRoomId, currentScheduleDate);
  }
});

/* ==========================================================================
   GALLERY MODAL – Foto Ruangan
   ========================================================================== */

// Photo definitions per room – key is room id or category slug
// Add actual photo paths here once photos are uploaded
const ROOM_PHOTOS = {
  // Auditorium (id=1)
  1: [
    { src: '/images/rooms/auditorium/auditorium1.jpg', caption: 'Auditorium – Tampak Depan' },
    { src: '/images/rooms/auditorium/auditorium2.jpg', caption: 'Auditorium – Area Panggung' },
    { src: '/images/rooms/auditorium/auditorium3.jpg', caption: 'Auditorium – Kursi Penonton' },
  ],
  // Ruang Multimedia (id=2)
  2: [
    { src: '/images/rooms/multimedia/multimedia1.jpg', caption: 'Ruang Multimedia – Area Komputer & Workstation' },
    { src: '/images/rooms/multimedia/multimedia2.jpg', caption: 'Ruang Multimedia – Smart Screen & Meja Presentasi' },
  ],
  // Ruang Rapat (id=3)
  3: [
    { src: '/images/rooms/rapat/ruangrapat1.jpg', caption: 'Ruang Rapat – Meja Utama' },
    { src: '/images/rooms/rapat/ruangrapat2.jpg', caption: 'Ruang Rapat – Area Presentasi' },
  ],
  // Lobi Luar (id=4)
  4: [
    { src: '/images/gedung-perpus-bogor-real.jpg', caption: 'Lobi Luar – Area Plaza & Selasar Depan' },
    { src: '/images/rooms/videotron/videotron1.jpeg', caption: 'Lobi Luar – Selasar & Pintu Masuk' },
  ],
};

let _galleryRoomId = null;
let _galleryPhotos = [];
let _galleryIndex = 0;

window.openGalleryModal = function (roomId, roomName, category) {
  _galleryRoomId = roomId;
  _galleryPhotos = ROOM_PHOTOS[roomId] || [];
  _galleryIndex = 0;

  const modal = document.getElementById('galleryModal');
  document.getElementById('galleryModalTitle').textContent = roomName;
  document.getElementById('galleryModalSubtitle').textContent =
    category === 'layar_iklan' ? 'Videotron Digital Lobi Perpustakaan' : 'Dokumentasi Interior Ruangan';

  // Book button
  const bookBtn = document.getElementById('galleryBookBtn');
  bookBtn.textContent = category === 'layar_iklan' ? 'Pesan Slot Videotron' : 'Pesan Ruangan Ini';
  bookBtn.onclick = () => { closeGalleryModal(); openBookingModal(roomId); };

  _renderGallery();

  modal.classList.add('open');
  document.body.style.overflow = 'hidden';
};

window.closeGalleryModal = function () {
  const modal = document.getElementById('galleryModal');
  if (modal) {
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }
};

window.galleryNav = function (direction) {
  if (!_galleryPhotos.length) return;
  _galleryIndex = (_galleryIndex + direction + _galleryPhotos.length) % _galleryPhotos.length;
  _showGalleryPhoto(_galleryIndex);
};

function _renderGallery() {
  const thumbsEl = document.getElementById('galleryThumbs');
  const noPhotoEl = document.getElementById('galleryNoPhoto');
  const mainImg = document.getElementById('galleryMainImg');
  const counter = document.getElementById('galleryCounter');
  const prevBtn = document.getElementById('galleryPrev');
  const nextBtn = document.getElementById('galleryNext');

  thumbsEl.innerHTML = '';

  if (!_galleryPhotos.length) {
    mainImg.style.display = 'none';
    noPhotoEl.classList.add('show');
    counter.textContent = '';
    prevBtn.style.display = 'none';
    nextBtn.style.display = 'none';
    return;
  }

  noPhotoEl.classList.remove('show');
  mainImg.style.display = 'block';
  prevBtn.style.display = _galleryPhotos.length > 1 ? 'flex' : 'none';
  nextBtn.style.display = _galleryPhotos.length > 1 ? 'flex' : 'none';

  // Build thumbnails
  _galleryPhotos.forEach((photo, idx) => {
    const img = document.createElement('img');
    img.src = photo.src;
    img.alt = photo.caption;
    img.className = 'gallery-thumb' + (idx === 0 ? ' active' : '');
    img.id = 'gthumb-' + idx;
    img.onerror = () => { img.style.display = 'none'; };
    img.addEventListener('click', () => { _galleryIndex = idx; _showGalleryPhoto(idx); });
    thumbsEl.appendChild(img);
  });

  _showGalleryPhoto(0);
}

function _showGalleryPhoto(idx) {
  const mainImg = document.getElementById('galleryMainImg');
  const counter = document.getElementById('galleryCounter');
  const photo = _galleryPhotos[idx];
  if (!photo) return;

  mainImg.src = photo.src;
  mainImg.alt = photo.caption;
  mainImg.onerror = () => {
    mainImg.style.display = 'none';
    document.getElementById('galleryNoPhoto').classList.add('show');
  };
  mainImg.onload = () => {
    mainImg.style.display = 'block';
    document.getElementById('galleryNoPhoto').classList.remove('show');
  };

  counter.textContent = (idx + 1) + ' / ' + _galleryPhotos.length;

  // Update active thumb
  document.querySelectorAll('.gallery-thumb').forEach((t, i) => {
    t.classList.toggle('active', i === idx);
  });

  _galleryIndex = idx;
}

// Close gallery on backdrop click
document.addEventListener('click', (e) => {
  const modal = document.getElementById('galleryModal');
  if (modal && e.target === modal) closeGalleryModal();
  const rulesModal = document.getElementById('rulesDetailModal');
  if (rulesModal && e.target === rulesModal) closeRulesDetailModal();
});

/* ==========================================================================
   MOCKUP INTERACTION HANDLERS
   ========================================================================== */

// 1. Cycle Card Image on Right Arrow Click
window.cycleCardPhoto = function(event, roomId) {
  if (event) {
    event.stopPropagation();
    event.preventDefault();
  }
  const card = document.getElementById(`roomCard-${roomId}`);
  const imgEl = document.getElementById(`cardImg-${roomId}`);
  if (!card || !imgEl) return;

  let photos = [];
  try {
    photos = JSON.parse(card.dataset.photos || '[]');
  } catch(e) {
    photos = [];
  }
  if (!photos.length && ROOM_PHOTOS[roomId]) {
    photos = ROOM_PHOTOS[roomId].map(p => p.src);
  }
  if (!photos.length) return;

  let currentIndex = parseInt(card.dataset.photoIndex || '0', 10);
  let nextIndex = (currentIndex + 1) % photos.length;
  card.dataset.photoIndex = nextIndex;

  imgEl.style.opacity = '0.4';
  imgEl.style.transform = 'scale(0.98)';
  setTimeout(() => {
    imgEl.src = photos[nextIndex];
    imgEl.style.opacity = '1';
    imgEl.style.transform = 'scale(1)';
  }, 120);
};

// 2. Toggle / Scroll to Schedule Explorer
window.toggleScheduleExplorer = function(scroll = true) {
  const scheduleEl = document.getElementById('ketersediaan');
  if (scheduleEl) {
    if (scroll) {
      scheduleEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
};

// 3. Detail Tata Tertib Modal
window.openRulesDetailModal = function(ruleType) {
  const modal = document.getElementById('rulesDetailModal');
  const titleEl = document.getElementById('rulesModalTitle');
  const contentEl = document.getElementById('rulesModalContent');
  if (!modal || !contentEl) return;

  const rulesMap = {
    operasional: {
      title: 'Jam Operasional Fasilitas',
      html: `
        <h4>🕒 Ketentuan Jam Operasional Fasilitas</h4>
        <p>Peminjaman ruangan pertemuan dan sarana galeri hanya dapat digunakan dalam rentang jam operasional resmi Perpustakaan Dan Galeri Kota Bogor:</p>
        <ul>
          <li><strong>Senin – Kamis:</strong> Pukul 08:00 – 15:00 WIB</li>
          <li><strong>Sabtu – Minggu:</strong> Pukul 08:00 – 20:00 WIB</li>
          <li><strong>Hari Jumat & Libur Nasional:</strong> Layanan peminjaman fasilitas tutup/libur untuk kegiatan sterilisasi dan pemeliharaan rutin sarana dan prasarana.</li>
        </ul>
        <p style="font-size: 0.85rem; color: #64748b; margin-top: 10px;">Catatan: Panitia / peminjam disarankan hadir 30 menit sebelum jadwal untuk persiapan teknis mik, proyektor, dan tata ruangan.</p>
      `
    },
    lobi: {
      title: 'Peminjaman Lobi Luar',
      html: `
        <h4>🏛️ Ketentuan Peminjaman Area Lobi Luar</h4>
        <p>Area lobi luar dan selasar depan Perpustakaan & Galeri Kota Bogor dapat dipinjam untuk kegiatan positif masyarakat:</p>
        <ul>
          <li><strong>Jenis Kegiatan:</strong> Pameran terbuka, stan bazaar literasi, pendaftaran/registrasi acara, dan aktivitas komunitas.</li>
          <li><strong>Kapasitas:</strong> Maksimal hingga 50 orang peserta/pengunjung aktif.</li>
          <li><strong>Fasilitas Tersedia:</strong> Stop kontak luar ruangan, kanopi peneduh, area loading barang, dan dekat akses parkir utama.</li>
          <li><strong>Ketertiban & Kebersihan:</strong> Peminjam wajib menjaga kebersihan selasar, tidak menghalangi jalur evakuasi, dan menata kembali area setelah acara selesai.</li>
        </ul>
      `
    },
    videotron: {
      title: 'Penayangan Videotron Lobi',
      html: `
        <h4>📺 Ketentuan Penayangan Slot Videotron Lobi</h4>
        <p>Layar digital LED Videotron indoor berlokasi di area Lobi Utama Galeri Perpustakaan Kota Bogor dan ditujukan untuk publikasi positif masyarakat:</p>
        <ul>
          <li><strong>Resolusi Standar:</strong> 1920 × 1080 piksel (Full HD, rasio aspek 16:9).</li>
          <li><strong>Format File:</strong> Gambar (JPG/PNG) atau Video MP4 (H.264 dengan audio standar).</li>
          <li><strong>Durasi Slot:</strong> Tiap materi ditayangkan dalam rotasi loop 15 hingga 30 detik per slot penayangan sepanjang operasional gedung.</li>
          <li><strong>Kebijakan Konten:</strong> Materi bebas dari unsur SARA, ujaran kebencian, rokok/alkohol, maupun pornografi. Diutamakan promosi agenda literasi, seni budaya, webinar, dan pengumuman instansi/komunitas.</li>
        </ul>
      `
    },
    pengajuan: {
      title: 'Waktu Pengajuan Minimal',
      html: `
        <h4>📅 Waktu Pengajuan & Verifikasi Berkas</h4>
        <p>Untuk memastikan ketersediaan slot ruangan dan kesiapan teknisi fasilitas, perhatikan batas waktu berikut:</p>
        <ul>
          <li><strong>Batas Pengajuan:</strong> Permohonan wajib diajukan minimal <strong>1 (satu) hari kerja</strong> sebelum tanggal pelaksanaan agenda.</li>
          <li><strong>Verifikasi Responsif:</strong> Petugas perpustakaan akan meninjau kelayakan berkas, surat pengantar, dan jadwal acara dalam waktu maksimal 1x24 jam kerja.</li>
          <li><strong>Kode Reservasi:</strong> Simpan kode unik reservasi Anda untuk memantau status persetujuan secara real-time melalui fitur <em>Cek Status</em>.</li>
        </ul>
      `
    },
    kebersihan: {
      title: 'Kebersihan & Ketertiban Ruangan',
      html: `
        <h4>✨ Kebersihan, Keamanan, & Inventaris Fasilitas</h4>
        <p>Demi kenyamanan bersama seluruh pemustaka dan pengunjung Perpustakaan & Galeri Kota Bogor:</p>
        <ul>
          <li><strong>Larangan Merokok & Makanan Berkuah:</strong> Kawasan tanpa rokok/vape. Dilarang membawa makanan berat berkuah ke area komputer multimedia maupun kursi teater auditorium.</li>
          <li><strong>Keutuhan Inventaris:</strong> Peminjam bertanggung jawab menjaga keutuhan mikrofon wireless, smart screen, kabel HDMI, proyektor, dan inventaris ruangan selama acara.</li>
          <li><strong>Pemulihan Ruang:</strong> Setelah acara usai, peminjam wajib merapikan kembali susunan meja kursi dan membuang sampah pada tempat pemilahan yang disediakan.</li>
        </ul>
      `
    }
  };

  const data = rulesMap[ruleType] || rulesMap['operasional'];
  if (titleEl) titleEl.textContent = data.title;
  contentEl.innerHTML = data.html;

  modal.classList.add('open');
  document.body.style.overflow = 'hidden';
};

window.closeRulesDetailModal = function() {
  const modal = document.getElementById('rulesDetailModal');
  if (modal) {
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }
};

/* ==========================================================================
   SCHEDULE & AVAILABILITY EXPLORER (Cek Hari & Jam Kosong)
   ========================================================================== */

let currentScheduleRoomId = 1;
// Format YYYY-MM-DD lokal
const _todayObj = new Date();
const _year = _todayObj.getFullYear();
const _month = String(_todayObj.getMonth() + 1).padStart(2, '0');
const _day = String(_todayObj.getDate()).padStart(2, '0');
const todayDateStr = `${_year}-${_month}-${_day}`;
let currentScheduleDate = todayDateStr;

window.selectScheduleRoom = function (roomId, scrollToSection = false) {
  currentScheduleRoomId = roomId;

  // Update active tab buttons
  document.querySelectorAll('.schedule-room-tab').forEach(tab => {
    tab.classList.toggle('active', parseInt(tab.dataset.roomId) === parseInt(roomId));
  });

  fetchScheduleSlots(roomId, currentScheduleDate);

  if (scrollToSection) {
    const section = document.getElementById('ketersediaan');
    if (section) {
      section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
};

window.onScheduleDateChange = function (dateVal) {
  if (!dateVal) return;
  currentScheduleDate = dateVal;
  fetchScheduleSlots(currentScheduleRoomId, dateVal);
};

window.setScheduleToday = function () {
  currentScheduleDate = todayDateStr;
  const dateInput = document.getElementById('scheduleCustomDate');
  if (dateInput) dateInput.value = todayDateStr;
  fetchScheduleSlots(currentScheduleRoomId, todayDateStr);
};

window.bookSpecificSlot = function (roomId, date, startTime, endTime) {
  openBookingModal(roomId, false);

  const bookingDateInput = document.getElementById('booking_date');
  const startTimeInput = document.getElementById('start_time');
  const endTimeInput = document.getElementById('end_time');

  if (bookingDateInput && date) bookingDateInput.value = date;
  if (startTimeInput && startTime) startTimeInput.value = startTime;

  if (endTimeInput && startTime) {
    const [sh, sm] = startTime.split(':').map(Number);
    const dayHours = getDayHours(date);
    const maxEndH = dayHours ? parseInt(dayHours.end.split(':')[0], 10) : 15;
    const endH = Math.min(sh + 2, maxEndH);
    endTimeInput.value = `${String(endH).padStart(2, '0')}:${String(sm).padStart(2, '0')}`;
  }

  // Refresh slot helper in modal & update duration pill
  setTimeout(() => {
    if (typeof updateDurationSummary === 'function') updateDurationSummary();
    if (typeof refreshModalSlotHelper === 'function') refreshModalSlotHelper();
  }, 100);
};

window.navigateScheduleDay = function (offsetDays) {
  const current = new Date(currentScheduleDate + 'T00:00:00');
  current.setDate(current.getDate() + offsetDays);
  const y = current.getFullYear();
  const m = String(current.getMonth() + 1).padStart(2, '0');
  const d = String(current.getDate()).padStart(2, '0');
  const nextDateStr = `${y}-${m}-${d}`;
  currentScheduleDate = nextDateStr;
  const dateInput = document.getElementById('scheduleCustomDate');
  if (dateInput) dateInput.value = nextDateStr;
  fetchScheduleSlots(currentScheduleRoomId, nextDateStr);
};

async function fetchScheduleSlots(roomId, date) {
  const weekGrid = document.getElementById('weekStripGrid');
  const slotsGrid = document.getElementById('slotCardsGrid');
  const detailRoomBadge = document.getElementById('detailRoomBadge');
  const detailDateDisplay = document.getElementById('detailDateDisplay');
  const detailHoursBadge = document.getElementById('detailHoursBadge');
  const valCountKosong = document.getElementById('valCountKosong');
  const valCountTerisi = document.getElementById('valCountTerisi');
  const valCountMenunggu = document.getElementById('valCountMenunggu');

  if (slotsGrid) {
    slotsGrid.innerHTML = `
      <div class="slot-loading-state">
        <div class="slot-spinner"></div>
        <div class="slot-loading-text">Memeriksa jadwal slot ketersediaan...</div>
      </div>
    `;
  }

  try {
    const res = await fetch(`/api/room-slots?room_id=${roomId}&date=${date}`, {
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();

    if (!data.success) {
      if (slotsGrid) {
        slotsGrid.innerHTML = `<div class="slot-error-state">Gagal memuat jadwal fasilitas. Silakan coba kembali.</div>`;
      }
      return;
    }

    // 1. Render Week Strip (Melihat Hari Mana yang Kosong atau Penuh)
    if (weekGrid && data.week_overview) {
      weekGrid.innerHTML = '';
      data.week_overview.forEach(day => {
        const card = document.createElement('div');
        const isSel = (day.date === data.date);
        card.className = `day-chip-card ${isSel ? 'active' : ''} ${day.is_today ? 'is-today' : ''}`;
        card.onclick = () => {
          currentScheduleDate = day.date;
          const dateInput = document.getElementById('scheduleCustomDate');
          if (dateInput) dateInput.value = day.date;
          fetchScheduleSlots(currentScheduleRoomId, day.date);
        };

        const badgeClass = `badge-${day.status}`;
        let statusDot = '';
        if (day.status === 'kosong') {
          statusDot = '<span class="day-dot dot-green"></span>';
        } else if (day.status === 'sebagian') {
          statusDot = '<span class="day-dot dot-yellow"></span>';
        } else if (day.status === 'penuh') {
          statusDot = '<span class="day-dot dot-red"></span>';
        } else {
          statusDot = '<span class="day-dot dot-gray"></span>';
        }

        card.innerHTML = `
          ${day.is_today ? '<span class="day-chip-today-pill">Hari Ini</span>' : ''}
          <span class="day-chip-name">${day.day_name}</span>
          <span class="day-chip-date">${day.day_number}</span>
          <span class="day-chip-month">${day.month_name}</span>
          <div class="day-chip-badge ${badgeClass}">
            ${statusDot}
            <span>${day.status_label}</span>
          </div>
        `;
        weekGrid.appendChild(card);
      });
    }

    // 2. Update Detail Header
    if (detailRoomBadge) {
      const span = detailRoomBadge.querySelector('span');
      if (span) span.textContent = data.room.name;
      else detailRoomBadge.textContent = data.room.name;
    }
    if (detailDateDisplay) {
      const span = detailDateDisplay.querySelector('span');
      if (span) span.textContent = data.date_formatted;
      else detailDateDisplay.textContent = data.date_formatted;
    }
    if (detailHoursBadge) {
      const span = detailHoursBadge.querySelector('span');
      const text = data.is_open ? data.open_hours : 'Libur Layanan (Tutup)';
      if (span) span.textContent = text;
      else detailHoursBadge.textContent = text;
      detailHoursBadge.classList.toggle('is-closed', !data.is_open);
    }

    // Update Counter Numbers
    if (valCountKosong) valCountKosong.textContent = data.summary.available_slots || 0;
    if (valCountTerisi) valCountTerisi.textContent = data.summary.booked_slots || 0;
    if (valCountMenunggu) valCountMenunggu.textContent = data.summary.pending_slots || 0;

    // Update Full Day buttons in Header and Footer
    const btnQuickFullDay = document.getElementById('btnQuickFullDay');
    const btnScheduleFullDayAction = document.getElementById('btnScheduleFullDayAction');
    const isLayar = data.room.category === 'layar_iklan';
    const isAvailableForFullDay = !isLayar && data.is_open && (data.summary.booked_slots === 0 && data.summary.pending_slots === 0);

    if (btnQuickFullDay) {
      if (isLayar || !data.is_open) {
        btnQuickFullDay.style.display = 'none';
      } else {
        btnQuickFullDay.style.display = 'inline-flex';
        if (!isAvailableForFullDay) {
          btnQuickFullDay.style.opacity = '0.65';
          btnQuickFullDay.title = 'Sebagian jam sudah terisi/menunggu verifikasi pada tanggal ini';
        } else {
          btnQuickFullDay.style.opacity = '1';
          btnQuickFullDay.title = 'Pesan ruangan ini satu hari penuh (seluruh jam operasional)';
        }
      }
    }

    if (btnScheduleFullDayAction) {
      if (isLayar || !data.is_open) {
        btnScheduleFullDayAction.style.display = 'none';
      } else {
        btnScheduleFullDayAction.style.display = 'inline-flex';
        if (!isAvailableForFullDay) {
          btnScheduleFullDayAction.style.opacity = '0.65';
        } else {
          btnScheduleFullDayAction.style.opacity = '1';
        }
      }
    }

    // 3. Render Slot Cards (Melihat Jam Mana yang Kosong atau Penuh)
    if (slotsGrid && data.slots) {
      slotsGrid.innerHTML = '';

      if (!data.slots.length) {
        slotsGrid.innerHTML = `
          <div class="slot-empty-state">
            Tidak ada slot waktu pada tanggal ini.
          </div>
        `;
        return;
      }

      data.slots.forEach(slot => {
        const card = document.createElement('div');
        const isLayar = data.room.category === 'layar_iklan';

        if (slot.status === 'tersedia') {
          const btnLabel = isLayar ? 'Pesan' : 'Pilih Jam';
          card.className = 'slot-card-minimal status-tersedia';
          card.onclick = () => bookSpecificSlot(data.room.id, data.date, slot.start_time, slot.end_time);

          card.innerHTML = `
            <div class="slot-min-left">
              <div class="slot-min-time">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span class="time-main">${slot.time_label}</span>
              </div>
              <span class="slot-min-sub">Durasi 1 Jam • Siap Dipesan</span>
            </div>

            <div class="slot-min-right">
              <span class="slot-min-pill pill-tersedia">
                <span class="dot-status dot-green pulse"></span>
                <span>Kosong</span>
              </span>
              <button type="button" class="btn-slot-select" title="Pilih jam ini">
                <span>${btnLabel}</span>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                  <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
              </button>
            </div>
          `;
        } else if (slot.status === 'terisi') {
          card.className = 'slot-card-minimal status-terisi is-disabled';
          const agendaText = slot.purpose ? (slot.purpose.length > 28 ? slot.purpose.substring(0, 28) + '...' : slot.purpose) : 'Terjadwal';

          card.innerHTML = `
            <div class="slot-min-left">
              <div class="slot-min-time time-busy">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span class="time-main">${slot.time_label}</span>
              </div>
              <span class="slot-min-sub sub-busy">${agendaText}</span>
            </div>

            <div class="slot-min-right">
              <span class="slot-min-pill pill-terisi">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <span>Penuh</span>
              </span>
              ${slot.booking_code ? `<span class="slot-code-mini">${slot.booking_code}</span>` : ''}
            </div>
          `;
        } else if (slot.status === 'menunggu') {
          card.className = 'slot-card-minimal status-menunggu';
          card.innerHTML = `
            <div class="slot-min-left">
              <div class="slot-min-time time-wait">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span class="time-main">${slot.time_label}</span>
              </div>
              <span class="slot-min-sub sub-wait">Dalam Proses Verifikasi</span>
            </div>

            <div class="slot-min-right">
              <span class="slot-min-pill pill-menunggu">
                <span class="dot-status dot-yellow"></span>
                <span>Menunggu</span>
              </span>
            </div>
          `;
        } else {
          // Tutup / Libur
          card.className = 'slot-card-minimal status-tutup is-disabled';
          card.innerHTML = `
            <div class="slot-min-left">
              <div class="slot-min-time time-closed">
                <span class="time-main">${slot.time_label}</span>
              </div>
              <span class="slot-min-sub">Libur Pemeliharaan</span>
            </div>

            <div class="slot-min-right">
              <span class="slot-min-pill pill-tutup">Tutup</span>
            </div>
          `;
        }
        slotsGrid.appendChild(card);
      });
    }

  } catch (err) {
    if (slotsGrid) {
      slotsGrid.innerHTML = `<div class="slot-error-state">Terjadi kesalahan saat memuat ketersediaan jam.</div>`;
    }
  }
}

// Modal Inline Slot Helper
window.refreshModalSlotHelper = async function () {
  const container = document.getElementById('modalSlotChipsContainer');
  const notice = document.getElementById('modalSlotHelperNotice');
  const facilitySelect = document.getElementById('room_id');
  const bookingDateInput = document.getElementById('booking_date');
  const startTimeInput = document.getElementById('start_time');
  const endTimeInput = document.getElementById('end_time');

  if (!container || !facilitySelect || !bookingDateInput) return;

  const roomId = facilitySelect.value;
  const date = bookingDateInput.value;
  const selectedOpt = facilitySelect.options[facilitySelect.selectedIndex];
  const category = selectedOpt ? selectedOpt.dataset.category : 'ruangan';

  // Sembunyikan untuk Videotron
  const helperSection = document.getElementById('modalSlotHelperWrap');
  if (category === 'layar_iklan') {
    if (helperSection) helperSection.style.display = 'none';
    return;
  } else {
    if (helperSection) helperSection.style.display = 'block';
  }

  if (!roomId || !date) {
    container.innerHTML = '<span style="font-size: 0.76rem; color: #94a3b8;">Pilih ruangan dan tanggal terlebih dahulu.</span>';
    return;
  }

  container.innerHTML = '<span style="font-size: 0.76rem; color: #64748b;">Memeriksa slot jam...</span>';

  try {
    const res = await fetch(`/api/room-slots?room_id=${roomId}&date=${date}`, {
      headers: { 'Accept': 'application/json' }
    });
    const data = await res.json();

    if (!data.success || !data.slots || !data.slots.length) {
      container.innerHTML = '<span style="font-size: 0.76rem; color: #94a3b8;">Tidak ada data jam.</span>';
      return;
    }

    if (!data.is_open) {
      if (notice) notice.textContent = 'Layanan Libur pada Hari Jumat';
      container.innerHTML = '<span class="modal-slot-pill slot-off">⛔ Jumat: Layanan Libur (Tutup)</span>';
      return;
    }

    if (notice) notice.textContent = 'Klik jam hijau untuk memilih waktu otomatis';

    container.innerHTML = '';
    data.slots.forEach(slot => {
      const chip = document.createElement('button');
      chip.type = 'button';

      if (slot.status === 'tersedia') {
        chip.className = 'slot-pill-btn';
        chip.innerHTML = `<span class="slot-dot"></span><span>${slot.start_time} - ${slot.end_time}</span><span class="slot-dot"></span>`;
        chip.title = `Klik untuk memilih jam ${slot.start_time} - ${slot.end_time}`;
        chip.onclick = () => {
          if (startTimeInput) startTimeInput.value = slot.start_time;
          if (endTimeInput) endTimeInput.value = slot.end_time;

          container.querySelectorAll('.slot-pill-btn').forEach(c => c.classList.remove('active-slot'));
          chip.classList.add('active-slot');
          if (typeof updateDurationSummary === 'function') updateDurationSummary();
        };
      } else {
        chip.className = 'slot-pill-btn busy-slot';
        chip.disabled = true;
        chip.innerHTML = `<span class="slot-dot"></span><span>${slot.start_time} - ${slot.end_time}</span><span class="slot-dot"></span>`;
        chip.title = slot.status === 'terisi' ? 'Jam ini sudah penuh' : 'Jam ini sedang dalam verifikasi';
      }

      container.appendChild(chip);
    });

  } catch (e) {
    container.innerHTML = '<span style="font-size: 0.76rem; color: #ef4444;">Gagal memeriksa ketersediaan jam.</span>';
  }
};

window.handleTimeDropdownChange = function () {
  const startTimeInput = document.getElementById('start_time');
  const endTimeInput = document.getElementById('end_time');
  if (startTimeInput && endTimeInput && startTimeInput.value && endTimeInput.value) {
    if (startTimeInput.value >= endTimeInput.value) {
      const [sh, sm] = startTimeInput.value.split(':').map(Number);
      const nextH = Math.min(sh + 1, 20);
      endTimeInput.value = `${String(nextH).padStart(2, '0')}:${String(sm).padStart(2, '0')}`;
    }
  }
  if (typeof updateDurationSummary === 'function') updateDurationSummary();
};

window.updatePurposeCounter = function (el) {
  const counter = document.getElementById('purposeCharCounter');
  if (counter && el) {
    counter.textContent = `${el.value.length}/500`;
  }
};

/* ==========================================================================
   FITUR JENDELA NOTIFIKASI PERSETUJUAN RUANGAN PELANGGAN
   ========================================================================== */
let _customerNotifsData = [];
let _activeNotifFilter = 'all';
let _isNotifFetching = false;

window.openNotificationModal = function () {
  const modal = document.getElementById('customerNotifModal');
  const btn = document.getElementById('btnHeaderNotifikasi');
  if (modal) {
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    if (btn) btn.classList.add('active');
    window.fetchCustomerNotifications();
  }
};

window.closeNotificationModal = function () {
  const modal = document.getElementById('customerNotifModal');
  const btn = document.getElementById('btnHeaderNotifikasi');
  if (modal) {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (btn) btn.classList.remove('active');
  }
};

window.toggleNotificationModal = function (e) {
  if (e) e.stopPropagation();
  const modal = document.getElementById('customerNotifModal');
  if (modal && modal.classList.contains('open')) {
    window.closeNotificationModal();
  } else {
    window.openNotificationModal();
  }
};

window.handleNotifBackdropClick = function (e) {
  if (e.target && e.target.id === 'customerNotifModal') {
    window.closeNotificationModal();
  }
};

window.fetchCustomerNotifications = async function (isManualRefresh = false) {
  if (_isNotifFetching) return;
  _isNotifFetching = true;

  const refreshIcon = document.getElementById('notifRefreshIcon');
  if (refreshIcon && isManualRefresh) {
    refreshIcon.style.animation = 'spin 0.8s linear infinite';
  }

  const loadingState = document.getElementById('notifLoadingState');
  const itemsList = document.getElementById('notifItemsList');
  const emptyState = document.getElementById('notifEmptyState');

  if (isManualRefresh || (_customerNotifsData.length === 0 && loadingState)) {
    if (loadingState) loadingState.style.display = 'flex';
    if (itemsList) itemsList.style.display = 'none';
    if (emptyState) emptyState.style.display = 'none';
  }

  try {
    let storedCodes = [];
    try {
      const raw = localStorage.getItem('library_booking_history');
      storedCodes = raw ? JSON.parse(raw) : [];
      if (!Array.isArray(storedCodes)) storedCodes = [];
    } catch (e) {
      storedCodes = [];
    }

    const queryParams = new URLSearchParams();
    if (storedCodes.length > 0) {
      queryParams.set('codes', storedCodes.join(','));
    }

    const res = await fetch(`/api/customer-notifications?${queryParams.toString()}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });

    if (!res.ok) throw new Error('Network error');
    const data = await res.json();

    if (data.success) {
      _customerNotifsData = data.notifications || [];
      const counts = data.counts || { total: 0, disetujui: 0, ditolak: 0, menunggu: 0 };

      // Update Tab Counts
      const countAllEl = document.getElementById('notifTabCountAll');
      const countApprovedEl = document.getElementById('notifTabCountApproved');
      const countRejectedEl = document.getElementById('notifTabCountRejected');
      const countPendingEl = document.getElementById('notifTabCountPending');

      if (countAllEl) countAllEl.textContent = counts.total;
      if (countApprovedEl) countApprovedEl.textContent = counts.disetujui;
      if (countRejectedEl) countRejectedEl.textContent = counts.ditolak;
      if (countPendingEl) countPendingEl.textContent = counts.menunggu;

      // Update Unread Notification Badges
      window.updateCustomerNotifBadgeCount();

      // Render Notification List
      window.renderCustomerNotifications();

      if (isManualRefresh && typeof showToast === 'function') {
        showToast('Notifikasi reservasi berhasil diperbarui.');
      }
    }
  } catch (err) {
    console.warn('Gagal memuat notifikasi:', err);
    if (isManualRefresh && typeof showToast === 'function') {
      showToast('Gagal memuat notifikasi, coba sesaat lagi.');
    }
  } finally {
    _isNotifFetching = false;
    if (refreshIcon) refreshIcon.style.animation = '';
    if (loadingState) loadingState.style.display = 'none';
  }
};

window.renderCustomerNotifications = function () {
  const loadingState = document.getElementById('notifLoadingState');
  const itemsList = document.getElementById('notifItemsList');
  const emptyState = document.getElementById('notifEmptyState');

  if (!itemsList) return;
  if (loadingState) loadingState.style.display = 'none';

  let filtered = _customerNotifsData;
  if (_activeNotifFilter !== 'all') {
    filtered = _customerNotifsData.filter(item => item.status === _activeNotifFilter);
  }

  if (filtered.length === 0) {
    itemsList.innerHTML = '';
    itemsList.style.display = 'none';
    if (emptyState) {
      emptyState.style.display = 'block';
      const descEl = emptyState.querySelector('.notif-empty-desc');
      if (descEl) {
        if (_activeNotifFilter === 'disetujui') {
          descEl.innerHTML = 'Belum ada permohonan ruangan yang berstatus <strong>Disetujui</strong>.';
        } else if (_activeNotifFilter === 'ditolak') {
          descEl.innerHTML = 'Tidak ada permohonan yang <strong>Ditolak</strong>.';
        } else if (_activeNotifFilter === 'menunggu') {
          descEl.innerHTML = 'Tidak ada permohonan yang sedang dalam status <strong>Menunggu</strong>.';
        } else {
          descEl.innerHTML = 'Setelah Anda mengajukan permohonan ruangan, pembaruan apakah ruangan <strong>disetujui</strong> atau <strong>ditolak</strong> oleh petugas perpustakaan akan langsung muncul di jendela ini.';
        }
      }
    }
    return;
  }

  if (emptyState) emptyState.style.display = 'none';
  itemsList.style.display = 'flex';

  let readNotifs = [];
  try {
    const raw = localStorage.getItem('customer_read_notifs');
    readNotifs = raw ? JSON.parse(raw) : [];
    if (!Array.isArray(readNotifs)) readNotifs = [];
  } catch (e) {
    readNotifs = [];
  }

  const html = filtered.map(item => {
    const notifKey = `${item.booking_code}_${item.status}_${item.updated_at_timestamp}`;
    const isRead = readNotifs.includes(notifKey);

    let badgeClass = 'badge-menunggu';
    let badgeText = 'Menunggu';
    let badgeIcon = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>`;

    if (item.status === 'disetujui') {
      badgeClass = 'badge-disetujui';
      badgeText = 'Disetujui';
      badgeIcon = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>`;
    } else if (item.status === 'ditolak') {
      badgeClass = 'badge-ditolak';
      badgeText = 'Ditolak';
      badgeIcon = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`;
    } else if (item.status === 'selesai') {
      badgeClass = 'badge-selesai';
      badgeText = 'Selesai';
      badgeIcon = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>`;
    }

    let actionButtonsHtml = '';
    if (item.status === 'disetujui') {
      actionButtonsHtml = `
        <a href="${item.print_url}" target="_blank" rel="noopener" class="btn-notif-action btn-notif-print" title="Buka dan cetak surat bukti izin reservasi resmi">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
          <span>Cetak Bukti Izin</span>
        </a>
        <button type="button" class="btn-notif-action btn-notif-detail" onclick="closeNotificationModal(); openStatusModalWithCode('${item.booking_code}')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <span>Detail Jadwal</span>
        </button>
      `;
    } else if (item.status === 'ditolak') {
      actionButtonsHtml = `
        <button type="button" class="btn-notif-action btn-notif-detail" onclick="closeNotificationModal(); openStatusModalWithCode('${item.booking_code}')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <span>Lihat Alasan Lengkap</span>
        </button>
        <button type="button" class="btn-notif-action btn-notif-chat" onclick="closeNotificationModal(); if(window.toggleCustomerChat) window.toggleCustomerChat(); else document.getElementById('chatLauncherBtn')?.click();">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
          <span>Tanya Petugas</span>
        </button>
      `;
    } else {
      actionButtonsHtml = `
        <button type="button" class="btn-notif-action btn-notif-detail" onclick="closeNotificationModal(); openStatusModalWithCode('${item.booking_code}')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          <span>Cek Posisi Verifikasi</span>
        </button>
      `;
    }

    let notesCalloutHtml = '';
    if (item.admin_notes && item.admin_notes.trim()) {
      const calloutType = item.status === 'disetujui' ? 'callout-disetujui' : (item.status === 'ditolak' ? 'callout-ditolak' : 'callout-menunggu');
      const label = item.status === 'ditolak' ? 'Catatan / Alasan Petugas:' : 'Catatan Petugas:';
      notesCalloutHtml = `
        <div class="notif-admin-notes-callout ${calloutType}">
          <strong>${label}</strong> ${escapeHtml(item.admin_notes)}
        </div>
      `;
    }

    return `
      <div class="notif-item-card status-${item.status}">
        <div class="notif-card-top">
          <div style="display: flex; align-items: center; gap: 6px;">
            <span class="notif-badge-pill ${badgeClass}">
              ${badgeIcon}
              <span>${badgeText}</span>
            </span>
            ${!isRead && (item.status === 'disetujui' || item.status === 'ditolak') ? '<span class="notif-dot dot-red" title="Pembaruan Baru"></span>' : ''}
          </div>
          <span class="notif-time-ago">${item.updated_at_human || item.created_at_human}</span>
        </div>

        <div class="notif-card-room-row">
          <div class="notif-room-name">
            <span>🏛️</span>
            <span>${escapeHtml(item.room_name)}</span>
            ${item.room_floor ? `<span style="font-size:0.75rem; font-weight:600; color:#64748b;">(${escapeHtml(item.room_floor)})</span>` : ''}
          </div>
          <button type="button" class="notif-code-copy-btn" onclick="copyBookingCode('${item.booking_code}')" title="Klik untuk menyalin kode">
            #${item.booking_code}
          </button>
        </div>

        <div class="notif-schedule-row">
          <span class="notif-schedule-tag">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            ${item.booking_date_formatted}
          </span>
          ${item.time_formatted ? `
            <span class="notif-schedule-tag">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
              ${item.time_formatted}
            </span>
          ` : ''}
        </div>

        <div class="notif-card-message">
          ${item.status_message}
        </div>

        ${notesCalloutHtml}

        <div class="notif-card-actions">
          ${actionButtonsHtml}
        </div>
      </div>
    `;
  }).join('');

  itemsList.innerHTML = html;
};

window.filterCustomerNotifs = function (status, btn) {
  _activeNotifFilter = status;
  document.querySelectorAll('.notif-tab-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  window.renderCustomerNotifications();
};

window.markAllNotificationsRead = function () {
  let readNotifs = [];
  try {
    const raw = localStorage.getItem('customer_read_notifs');
    readNotifs = raw ? JSON.parse(raw) : [];
    if (!Array.isArray(readNotifs)) readNotifs = [];
  } catch (e) {
    readNotifs = [];
  }

  _customerNotifsData.forEach(item => {
    const key = `${item.booking_code}_${item.status}_${item.updated_at_timestamp}`;
    if (!readNotifs.includes(key)) {
      readNotifs.push(key);
    }
  });

  try {
    localStorage.setItem('customer_read_notifs', JSON.stringify(readNotifs.slice(-100)));
  } catch (e) {}

  window.updateCustomerNotifBadgeCount();
  window.renderCustomerNotifications();

  if (typeof showToast === 'function') {
    showToast('Semua notifikasi ditandai sudah dilihat.');
  }
};

window.updateCustomerNotifBadgeCount = function () {
  const headerBadge = document.getElementById('headerNotifBadge');
  const pulseDot = document.getElementById('headerNotifPulseDot');
  const headerPulse = document.getElementById('notifHeaderPulse');
  const mobileBadge = document.getElementById('mobileNotifBadge');

  let readNotifs = [];
  try {
    const raw = localStorage.getItem('customer_read_notifs');
    readNotifs = raw ? JSON.parse(raw) : [];
    if (!Array.isArray(readNotifs)) readNotifs = [];
  } catch (e) {
    readNotifs = [];
  }

  const unreadItems = _customerNotifsData.filter(item => {
    const key = `${item.booking_code}_${item.status}_${item.updated_at_timestamp}`;
    return !readNotifs.includes(key) && (item.status === 'disetujui' || item.status === 'ditolak');
  });

  const unreadCount = unreadItems.length;
  const totalCount = _customerNotifsData.length;

  if (unreadCount > 0) {
    if (headerBadge) {
      headerBadge.textContent = unreadCount;
      headerBadge.classList.add('has-unread');
      headerBadge.style.display = 'inline-flex';
    }
    if (pulseDot) pulseDot.style.display = 'block';
    if (headerPulse) headerPulse.style.display = 'block';
    if (mobileBadge) {
      mobileBadge.textContent = unreadCount;
      mobileBadge.classList.add('has-unread');
      mobileBadge.style.display = 'inline-flex';
    }
  } else if (totalCount > 0) {
    if (headerBadge) {
      headerBadge.textContent = totalCount;
      headerBadge.classList.remove('has-unread');
      headerBadge.style.display = 'inline-flex';
    }
    if (pulseDot) pulseDot.style.display = 'none';
    if (headerPulse) headerPulse.style.display = 'none';
    if (mobileBadge) {
      mobileBadge.textContent = totalCount;
      mobileBadge.classList.remove('has-unread');
      mobileBadge.style.display = 'inline-flex';
    }
  } else {
    if (headerBadge) headerBadge.style.display = 'none';
    if (pulseDot) pulseDot.style.display = 'none';
    if (headerPulse) headerPulse.style.display = 'none';
    if (mobileBadge) mobileBadge.style.display = 'none';
  }
};

window.openStatusModalWithCode = function (code) {
  if (typeof openStatusModal === 'function') {
    openStatusModal();
    const input = document.getElementById('statusBookingCodeInput');
    if (input) {
      input.value = code;
      const form = document.getElementById('formCheckStatus');
      if (form) {
        if (typeof checkBookingStatus === 'function') {
          checkBookingStatus();
        } else {
          form.dispatchEvent(new Event('submit', { cancelable: true }));
        }
      }
    }
  }
};

function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

document.addEventListener('DOMContentLoaded', () => {
  window.fetchCustomerNotifications();
  setInterval(() => {
    window.fetchCustomerNotifications();
  }, 35000);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      window.closeNotificationModal();
    }
  });
});

