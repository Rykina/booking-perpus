<!-- ========================================================================= -->
<!-- JENDELA NOTIFIKASI PERSETUJUAN RUANGAN PELANGGAN (MODAL WINDOW) -->
<!-- ========================================================================= -->
<div class="modal-backdrop notif-modal-backdrop" id="customerNotifModal" aria-hidden="true" onclick="handleNotifBackdropClick(event)">
    <div class="modal-dialog notif-modal-dialog" onclick="event.stopPropagation()">
        
        <!-- Jendela Header -->
        <div class="notif-modal-header">
            <div class="notif-header-left">
                <div class="notif-header-icon-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="notif-header-pulse" id="notifHeaderPulse" style="display: none;"></span>
                </div>
                <div class="notif-title-group">
                    <div class="notif-title-row">
                        <h3 class="notif-modal-title">Notifikasi Status Reservasi</h3>
                        <span class="notif-live-badge" id="notifLiveBadge">Live Update</span>
                    </div>
                    <p class="notif-modal-subtitle">Pantau apakah permohonan ruangan Anda sudah disetujui atau ditolak.</p>
                </div>
            </div>

            <div class="notif-header-actions">
                <button type="button" class="notif-action-icon-btn" onclick="fetchCustomerNotifications(true)" title="Segarkan Data (Refresh)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" id="notifRefreshIcon">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                </button>
                <button type="button" class="modal-close" onclick="closeNotificationModal()" aria-label="Tutup Jendela Notifikasi">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Filter Tabs Bar -->
        <div class="notif-filter-bar">
            <button type="button" class="notif-tab-btn active" data-filter="all" onclick="filterCustomerNotifs('all', this)">
                <span>Semua</span>
                <span class="notif-tab-count" id="notifTabCountAll">0</span>
            </button>
            <button type="button" class="notif-tab-btn tab-approved" data-filter="disetujui" onclick="filterCustomerNotifs('disetujui', this)">
                <span class="notif-dot dot-green"></span>
                <span>Disetujui</span>
                <span class="notif-tab-count badge-green" id="notifTabCountApproved">0</span>
            </button>
            <button type="button" class="notif-tab-btn tab-rejected" data-filter="ditolak" onclick="filterCustomerNotifs('ditolak', this)">
                <span class="notif-dot dot-red"></span>
                <span>Ditolak</span>
                <span class="notif-tab-count badge-red" id="notifTabCountRejected">0</span>
            </button>
            <button type="button" class="notif-tab-btn tab-pending" data-filter="menunggu" onclick="filterCustomerNotifs('menunggu', this)">
                <span class="notif-dot dot-amber"></span>
                <span>Menunggu</span>
                <span class="notif-tab-count badge-amber" id="notifTabCountPending">0</span>
            </button>
        </div>

        <!-- Notifikasi List Body (Scrollable) -->
        <div class="notif-modal-body" id="notifListContainer">
            <!-- Loading Skeleton (Default) -->
            <div class="notif-loading-state" id="notifLoadingState">
                <div class="notif-skeleton-card">
                    <div class="skeleton-line" style="width: 35%; height: 16px;"></div>
                    <div class="skeleton-line" style="width: 70%; height: 14px; margin-top: 8px;"></div>
                    <div class="skeleton-line" style="width: 50%; height: 12px; margin-top: 6px;"></div>
                </div>
                <div class="notif-skeleton-card">
                    <div class="skeleton-line" style="width: 40%; height: 16px;"></div>
                    <div class="skeleton-line" style="width: 65%; height: 14px; margin-top: 8px;"></div>
                    <div class="skeleton-line" style="width: 45%; height: 12px; margin-top: 6px;"></div>
                </div>
            </div>

            <!-- Tempat Render Item Notifikasi -->
            <div class="notif-items-list" id="notifItemsList" style="display: none;"></div>

            <!-- Empty State -->
            <div class="notif-empty-state" id="notifEmptyState" style="display: none;">
                <div class="notif-empty-icon">
                    <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        <line x1="2" y1="2" x2="22" y2="22"></line>
                    </svg>
                </div>
                <h4 class="notif-empty-title">Belum Ada Notifikasi Reservasi</h4>
                <p class="notif-empty-desc">
                    Setelah Anda mengajukan permohonan ruangan, pembaruan apakah ruangan <strong>disetujui</strong> atau <strong>ditolak</strong> oleh petugas perpustakaan akan langsung muncul di jendela ini.
                </p>
                <div class="notif-empty-action">
                    <button type="button" class="btn btn-primary btn-sm btn-pill" onclick="closeNotificationModal(); openBookingModal();">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Mulai Ajukan Reservasi Ruangan
                    </button>
                </div>
            </div>
        </div>

        <!-- Jendela Footer -->
        <div class="notif-modal-footer">
            <div class="notif-footer-left">
                <button type="button" class="notif-mark-read-btn" id="btnMarkAllNotifsRead" onclick="markAllNotificationsRead()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Tandai Semua Sudah Dilihat
                </button>
            </div>
            <div class="notif-footer-right">
                <a href="{{ route('booking.history') }}" class="notif-link-history" onclick="closeNotificationModal()">
                    <span>Buka Riwayat Lengkap</span>
                    <span class="arrow-sym">&rarr;</span>
                </a>
            </div>
        </div>

    </div>
</div>
