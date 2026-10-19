@extends('layouts.admin')

@section('title', 'Inbox Chat Pelanggan - Admin Perpustakaan & Galeri Kota Bogor')

@section('content')
<div class="admin-chat-container" style="background: #f8fafc; min-height: 85vh; padding: 24px 0 40px 0;">
    <div class="container" style="max-width: 1300px; margin: 0 auto; padding: 0 16px;">
        
        <!-- Header Info -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 8px; background: #eff6ff; color: #1d4ed8; padding: 4px 12px; border-radius: 9999px; font-size: 0.78rem; font-weight: 700; margin-bottom: 6px;">
                    <span style="width: 8px; height: 8px; background: #2563eb; border-radius: 50%;"></span>
                    PANEL ADMINISTRATOR & PETUGAS
                </div>
                <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin: 0;">Konsultasi & Layanan Chat Pelanggan</h1>
                <p style="color: #64748b; font-size: 0.88rem; margin: 4px 0 0 0;">Kelola dan tanggapi pertanyaan, verifikasi berkas, serta hubungi peminjam secara langsung.</p>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <button type="button" 
                        onclick="openNewChatModal()" 
                        class="btn btn-primary" 
                        style="border-radius: 9999px; padding: 9px 20px; font-size: 0.86rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 3px 12px rgba(26, 115, 232, 0.3);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Mulai Chat Baru</span>
                </button>
                <a href="{{ route('booking.index') }}" class="btn btn-outline" style="border-radius: 9999px; padding: 8px 18px; font-size: 0.84rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Lihat Beranda Publik
                </a>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 18px; border-radius: 12px; font-size: 0.88rem; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Chat Workspace Layout -->
        <div style="display: grid; grid-template-columns: 360px 1fr; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05); min-height: 660px; overflow: hidden;" id="adminChatGrid">
            
            <!-- Left Sidebar: Conversations List & Search -->
            <div style="border-right: 1px solid #e2e8f0; background: #ffffff; display: flex; flex-direction: column;">
                
                <!-- Sidebar Top Toolbar -->
                <div style="padding: 16px 18px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-weight: 800; color: #0f172a; font-size: 0.98rem;">Percakapan</span>
                        <span id="convCountBadge" style="background: #eff6ff; color: #1d4ed8; font-size: 0.74rem; font-weight: 800; padding: 2px 8px; border-radius: 9999px;">
                            {{ $conversations->count() }}
                        </span>
                    </div>
                    <button type="button" 
                            onclick="openNewChatModal()" 
                            class="btn btn-sm" 
                            title="Pilih Pengguna untuk Chat Baru"
                            style="background: #eff6ff; color: #1a73e8; border: 1px solid #bfdbfe; border-radius: 9999px; padding: 4px 12px; font-size: 0.76rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; cursor: pointer; transition: all 0.2s ease;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>+ Chat Baru</span>
                    </button>
                </div>

                <!-- Instant Search Bar for Conversations -->
                <div style="padding: 10px 16px; border-bottom: 1px solid #f1f5f9; background: #ffffff;">
                    <div style="position: relative;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.2" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%);">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" 
                               id="conversationSearchInput" 
                               placeholder="Cari percakapan / nama..." 
                               oninput="filterConversationList(this.value)"
                               style="width: 100%; padding: 8px 30px 8px 34px; border-radius: 9999px; border: 1.5px solid #e2e8f0; font-size: 0.82rem; outline: none; transition: all 0.2s ease; box-sizing: border-box; background: #f8fafc; font-family: inherit;">
                        <button type="button" 
                                id="btnClearConvSearch" 
                                onclick="clearConversationSearch()" 
                                style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 2px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Conversation Items List -->
                <div style="overflow-y: auto; flex: 1; max-height: 570px;" id="adminConversationList">
                    @forelse($conversations as $conv)
                        @php
                            $isSelected = $selectedConversation && $selectedConversation->id === $conv->id;
                            $unreadCount = $conv->unreadCountForAdmin();
                            $lastMsg = $conv->latestMessage;
                            $userName = $conv->user ? $conv->user->name : 'Pelanggan #' . $conv->id;
                            $userEmail = $conv->user ? $conv->user->email : '';
                            $userPhone = $conv->user ? $conv->user->phone_number : '';
                        @endphp
                        <a href="{{ route('admin.chat.index', ['conversation_id' => $conv->id]) }}" 
                           class="conv-item-link"
                           data-name="{{ strtolower($userName) }}"
                           data-email="{{ strtolower($userEmail) }}"
                           data-phone="{{ strtolower($userPhone) }}"
                           style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-bottom: 1px solid #f8fafc; text-decoration: none; color: inherit; transition: all 0.15s ease; background: {{ $isSelected ? '#eff6ff' : 'transparent' }}; border-left: {{ $isSelected ? '4px solid #1a73e8' : '4px solid transparent' }};">
                            
                            <!-- Customer Avatar -->
                            <div style="position: relative; flex-shrink: 0;">
                                @if($conv->user && $conv->user->avatar)
                                    <img src="{{ $conv->user->avatar }}" alt="{{ $userName }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;" referrerpolicy="no-referrer">
                                @else
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem;">
                                        {{ strtoupper(substr($userName, 0, 1)) }}
                                    </div>
                                @endif
                                @if($unreadCount > 0)
                                    <span style="position: absolute; top: -2px; right: -2px; background: #ef4444; color: #ffffff; font-size: 0.68rem; font-weight: 800; min-width: 18px; height: 18px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; border: 2px solid #ffffff;">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>

                            <!-- Conversation Info -->
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px;">
                                    <div style="font-weight: 700; font-size: 0.88rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $userName }}
                                    </div>
                                    <span style="font-size: 0.72rem; color: #94a3b8; flex-shrink: 0;">
                                        {{ $conv->last_message_at ? $conv->last_message_at->format('H:i') : '' }}
                                    </span>
                                </div>
                                <div style="font-size: 0.78rem; color: {{ $unreadCount > 0 ? '#1e293b' : '#64748b' }}; font-weight: {{ $unreadCount > 0 ? '700' : '400' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $lastMsg ? $lastMsg->message : 'Belum ada pesan' }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div id="emptyConversationState" style="padding: 40px 20px; text-align: center; color: #94a3b8; font-size: 0.86rem;">
                            <div style="width: 52px; height: 52px; border-radius: 50%; background: #eff6ff; color: #1a73e8; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </div>
                            <h4 style="margin: 0 0 6px 0; font-size: 0.95rem; font-weight: 700; color: #334155;">Belum Ada Percakapan</h4>
                            <p style="margin: 0 0 16px 0; font-size: 0.8rem; line-height: 1.45; color: #64748b;">
                                Anda dapat memilih pelanggan untuk memulai chat pertama tanpa menunggu mereka mengirim pesan.
                            </p>
                            <button type="button" onclick="openNewChatModal()" class="btn btn-primary btn-sm" style="border-radius: 9999px; padding: 8px 18px; font-weight: 700; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>Pilih Pengguna & Chat</span>
                            </button>
                        </div>
                    @endforelse

                    <!-- No Match Result Card (Shown when client search returns 0 matches) -->
                    <div id="noConvMatchCard" style="display: none; padding: 32px 18px; text-align: center; color: #64748b;">
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" style="margin-bottom: 8px;">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <div style="font-size: 0.84rem; font-weight: 700; color: #334155; margin-bottom: 4px;">Percakapan Tidak Ditemukan</div>
                        <p style="font-size: 0.78rem; margin: 0 0 12px 0;">Pengguna mungkin belum pernah membuka chat sebelumnya.</p>
                        <button type="button" onclick="openNewChatModalWithQuery()" class="btn btn-outline btn-sm" style="border-radius: 9999px; font-size: 0.78rem; font-weight: 700; padding: 6px 14px;">
                            Cari di Seluruh Pengguna &rarr;
                        </button>
                    </div>
                </div>

            </div>

            <!-- Right Main: Current Thread OR Friendly Empty Prompt -->
            <div style="display: flex; flex-direction: column; background: #ffffff;">
                
                @if($selectedConversation)
                    <!-- Thread Header -->
                    <div style="padding: 16px 24px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; background: #ffffff; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            @if($selectedConversation->user && $selectedConversation->user->avatar)
                                <img src="{{ $selectedConversation->user->avatar }}" alt="{{ $selectedConversation->user->name }}" style="width: 46px; height: 46px; border-radius: 50%; object-fit: cover;" referrerpolicy="no-referrer">
                            @else
                                <div style="width: 46px; height: 46px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem;">
                                    {{ strtoupper(substr($selectedConversation->user ? $selectedConversation->user->name : 'P', 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a;">
                                        {{ $selectedConversation->user ? $selectedConversation->user->name : 'Pelanggan #' . $selectedConversation->id }}
                                    </h3>
                                    <span style="background: #e2e8f0; color: #475569; font-size: 0.72rem; padding: 2px 8px; border-radius: 9999px; font-weight: 600;">
                                        {{ ucfirst($selectedConversation->user ? $selectedConversation->user->role : 'Customer') }}
                                    </span>
                                </div>
                                <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <span>{{ $selectedConversation->user ? $selectedConversation->user->email : '-' }}</span>
                                    @if($selectedConversation->user && $selectedConversation->user->phone_number)
                                        <span>&bull;</span>
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $selectedConversation->user->phone_number)) }}" 
                                           target="_blank" 
                                           rel="noopener"
                                           title="Chat via WhatsApp"
                                           style="color: #15803d; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                            <span>WA: {{ $selectedConversation->user->phone_number }}</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #10b981; font-weight: 700; background: #ecfdf5; padding: 4px 10px; border-radius: 9999px; border: 1px solid #a7f3d0;">
                                <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></span>
                                Terhubung
                            </span>
                        </div>
                    </div>

                    <!-- Messages Stream -->
                    <div id="adminMessagesContainer" style="flex: 1; padding: 24px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 14px; max-height: 480px;">
                        @forelse($messages as $msg)
                            @php
                                $isFromAdmin = $msg->sender && $msg->sender->isAdmin();
                            @endphp
                            <div style="display: flex; flex-direction: column; align-items: {{ $isFromAdmin ? 'flex-end' : 'flex-start' }};">
                                <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                    <span style="font-weight: 600;">{{ $isFromAdmin ? 'Anda (' . $msg->sender->name . ')' : $msg->sender->name }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                                <div style="max-width: 75%; padding: 12px 18px; border-radius: 16px; font-size: 0.9rem; line-height: 1.5; word-break: break-word; background: {{ $isFromAdmin ? '#1a73e8' : '#ffffff' }}; color: {{ $isFromAdmin ? '#ffffff' : '#1e293b' }}; border: {{ $isFromAdmin ? 'none' : '1px solid #e2e8f0' }}; box-shadow: 0 1px 3px rgba(0,0,0,0.04); {{ $isFromAdmin ? 'border-bottom-right-radius: 4px;' : 'border-bottom-left-radius: 4px;' }}">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #94a3b8; font-size: 0.88rem; padding: 50px 20px;">
                                <div style="width: 48px; height: 48px; border-radius: 50%; background: #eff6ff; color: #1a73e8; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px auto;">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                </div>
                                <div style="font-weight: 700; color: #334155; margin-bottom: 4px;">Percakapan baru siap dimulai!</div>
                                <p style="margin: 0; font-size: 0.82rem; color: #64748b;">
                                    Ketik pesan pertama Anda di bawah untuk menyapa {{ $selectedConversation->user ? $selectedConversation->user->name : 'pelanggan' }}.
                                </p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Reply Input Footer -->
                    <div style="padding: 16px 20px; border-top: 1px solid #e2e8f0; background: #ffffff;">
                        <form id="adminReplyForm" onsubmit="handleAdminSendReply(event)">
                            @csrf
                            <input type="hidden" id="conversationId" value="{{ $selectedConversation->id }}">
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <input type="text" 
                                       id="adminMessageInput" 
                                       placeholder="Ketik balasan Anda untuk {{ $selectedConversation->user ? $selectedConversation->user->name : 'pelanggan' }}..." 
                                       required
                                       autofocus
                                       style="flex: 1; padding: 12px 18px; border-radius: 9999px; border: 1.5px solid #cbd5e1; outline: none; font-size: 0.9rem; transition: border-color 0.2s ease; font-family: inherit;">
                                
                                <button type="submit" 
                                        id="btnAdminSend" 
                                        class="btn btn-primary" 
                                        style="border-radius: 9999px; padding: 12px 24px; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; flex-shrink: 0; box-shadow: 0 2px 10px rgba(26, 115, 232, 0.3);">
                                    <span>Kirim</span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>

                @else
                    <!-- Right Side Empty State -->
                    <div style="flex: 1; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #94a3b8; padding: 50px 30px; text-align: center;">
                        <div style="width: 70px; height: 70px; border-radius: 22px; background: #eff6ff; color: #1a73e8; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; box-shadow: 0 4px 14px rgba(26, 115, 232, 0.15);">
                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                        <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0 0 8px 0;">Mulai Chat Langsung ke Pengguna</h3>
                        <p style="font-size: 0.88rem; color: #64748b; max-width: 440px; margin: 0 0 22px 0; line-height: 1.5;">
                            Anda tidak perlu menunggu pelanggan mengirim pesan lebih dulu. Pilih pengguna terdaftar untuk membuka percakapan dan mengirimkan pesan atau konfirmasi.
                        </p>
                        <button type="button" 
                                onclick="openNewChatModal()" 
                                class="btn btn-primary" 
                                style="border-radius: 9999px; padding: 12px 28px; font-weight: 700; font-size: 0.92rem; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(26, 115, 232, 0.35);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Pilih Pengguna & Mulai Chat</span>
                        </button>
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: PILIH & CARI PENGGUNA UNTUK MEMULAI CHAT BARU -->
<!-- ========================================================================= -->
<div id="modalNewChat" class="chat-modal-backdrop" onclick="handleModalBackdropClick(event)">
    <div class="chat-modal-dialog" onclick="event.stopPropagation()">
        
        <!-- Modal Header -->
        <div style="padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; background: #ffffff;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(26, 115, 232, 0.3);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.12rem; font-weight: 800; color: #0f172a;">Mulai Chat dengan Pengguna</h3>
                    <p style="margin: 2px 0 0 0; font-size: 0.8rem; color: #64748b;">Pilih pengguna terdaftar untuk membuka ruang percakapan.</p>
                </div>
            </div>
            <button type="button" 
                    onclick="closeNewChatModal()" 
                    aria-label="Tutup" 
                    style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #64748b; transition: all 0.2s ease;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Search Bar Input in Modal -->
        <div style="padding: 16px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <div style="position: relative;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" 
                       id="modalUserSearchInput" 
                       placeholder="Cari nama, email, NIK/NIM, atau nomor WhatsApp..." 
                       oninput="handleModalSearchInput(this.value)"
                       style="width: 100%; padding: 11px 36px 11px 40px; border-radius: 12px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; outline: none; background: #ffffff; box-sizing: border-box; font-family: inherit; transition: border-color 0.2s ease;">
                <button type="button" 
                        id="btnClearModalSearch" 
                        onclick="clearModalSearch()" 
                        style="display: none; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 2px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px; font-size: 0.74rem; color: #64748b;">
                <span id="userSearchResultsCount">Menampilkan daftar pengguna</span>
                <span>💡 Klik "Chat &rarr;" untuk memulai</span>
            </div>
        </div>

        <!-- Users List Stream -->
        <div id="modalUsersListContainer" style="flex: 1; overflow-y: auto; max-height: 380px; padding: 8px 16px;">
            @forelse($availableUsers as $user)
                @php
                    $hasConv = isset($existingConversations[$user->id]);
                    $convId = $existingConversations[$user->id] ?? null;
                @endphp
                <div class="user-row-card" 
                     data-name="{{ strtolower($user->name) }}"
                     data-email="{{ strtolower($user->email) }}"
                     data-phone="{{ strtolower($user->phone_number ?? '') }}"
                     data-identity="{{ strtolower($user->identity_number ?? '') }}"
                     style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; border-radius: 14px; margin-bottom: 6px; border: 1px solid #f1f5f9; transition: all 0.15s ease; background: #ffffff;">
                    
                    <div style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1;">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; flex-shrink: 0;" referrerpolicy="no-referrer">
                        @else
                            <div style="width: 42px; height: 42px; border-radius: 50%; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; flex-shrink: 0;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div style="min-width: 0; flex: 1;">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <div style="font-weight: 700; font-size: 0.9rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $user->name }}
                                </div>
                                <span style="font-size: 0.68rem; font-weight: 700; padding: 1px 7px; border-radius: 9999px; {{ $user->isAdmin() ? 'background: #f1f5f9; color: #475569;' : 'background: #ecfdf5; color: #047857;' }}">
                                    {{ $user->role_label }}
                                </span>
                                @if($hasConv)
                                    <span style="font-size: 0.68rem; font-weight: 700; background: #eff6ff; color: #1d4ed8; padding: 1px 7px; border-radius: 9999px;">
                                        💬 Ada Percakapan
                                    </span>
                                @endif
                            </div>
                            <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <span>{{ $user->email }}</span>
                                @if($user->phone_number)
                                    <span>&bull; {{ $user->phone_number }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            onclick="startChatWithUser({{ $user->id }}, '{{ addslashes($user->name) }}')" 
                            class="btn btn-primary btn-sm"
                            style="border-radius: 9999px; padding: 6px 16px; font-size: 0.78rem; font-weight: 700; flex-shrink: 0; margin-left: 10px;">
                        <span>Chat &rarr;</span>
                    </button>
                </div>
            @empty
                <div style="text-align: center; color: #94a3b8; padding: 40px 0; font-size: 0.86rem;">
                    Belum ada pengguna lain terdaftar di sistem.
                </div>
            @endforelse
        </div>

        <!-- Modal Footer -->
        <div style="padding: 14px 24px; border-top: 1px solid #e2e8f0; background: #ffffff; display: flex; align-items: center; justify-content: flex-end;">
            <button type="button" onclick="closeNewChatModal()" class="btn btn-outline" style="border-radius: 9999px; padding: 7px 18px; font-size: 0.82rem; font-weight: 600;">
                Tutup
            </button>
        </div>

    </div>
</div>

<style>
    .chat-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 1050;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .chat-modal-backdrop.show {
        display: flex !important;
        animation: fadeInBackdrop 0.2s ease forwards;
    }
    .chat-modal-dialog {
        background: #ffffff;
        width: 100%;
        max-width: 580px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.2);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: 85vh;
        animation: slideUpModal 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes fadeInBackdrop {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUpModal {
        from { opacity: 0; transform: translateY(16px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .user-row-card:hover {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .conv-item-link:hover {
        background: #f8fafc !important;
    }
    @media (max-width: 900px) {
        #adminChatGrid {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@push('scripts')
<script>
    // =========================================================================
    // MODAL BARU: PILIH & CARI PENGGUNA UNTUK MEMULAI CHAT
    // =========================================================================
    const modalNewChat = document.getElementById('modalNewChat');
    const modalSearchInput = document.getElementById('modalUserSearchInput');
    const btnClearModalSearch = document.getElementById('btnClearModalSearch');
    const usersContainer = document.getElementById('modalUsersListContainer');
    const searchResultsCount = document.getElementById('userSearchResultsCount');

    function openNewChatModal() {
        if (!modalNewChat) return;
        modalNewChat.classList.add('show');
        document.body.style.overflow = 'hidden';
        if (modalSearchInput) {
            modalSearchInput.value = '';
            if (btnClearModalSearch) btnClearModalSearch.style.display = 'none';
            setTimeout(() => modalSearchInput.focus(), 150);
            filterLocalUsers('');
        }
    }

    function openNewChatModalWithQuery() {
        const query = document.getElementById('conversationSearchInput')?.value || '';
        openNewChatModal();
        if (modalSearchInput && query) {
            modalSearchInput.value = query;
            handleModalSearchInput(query);
        }
    }

    function closeNewChatModal() {
        if (!modalNewChat) return;
        modalNewChat.classList.remove('show');
        document.body.style.overflow = '';
    }

    function handleModalBackdropClick(e) {
        if (e.target === modalNewChat) {
            closeNewChatModal();
        }
    }

    function clearModalSearch() {
        if (modalSearchInput) {
            modalSearchInput.value = '';
            handleModalSearchInput('');
            modalSearchInput.focus();
        }
    }

    // Debounce untuk AJAX Search
    let searchDebounceTimer = null;

    function handleModalSearchInput(val) {
        const q = val.trim().toLowerCase();
        if (btnClearModalSearch) {
            btnClearModalSearch.style.display = q ? 'block' : 'none';
        }

        // 1. Filter cepat secara lokal dulu
        const localMatchCount = filterLocalUsers(q);

        // 2. Debounce AJAX search jika query lebih dari 1 karakter
        clearTimeout(searchDebounceTimer);
        if (q.length >= 2) {
            searchDebounceTimer = setTimeout(() => {
                fetchUsersFromServer(q);
            }, 300);
        }
    }

    function filterLocalUsers(q) {
        const rows = document.querySelectorAll('.user-row-card');
        let count = 0;
        rows.forEach(row => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const phone = row.dataset.phone || '';
            const identity = row.dataset.identity || '';

            if (!q || name.includes(q) || email.includes(q) || phone.includes(q) || identity.includes(q)) {
                row.style.display = 'flex';
                count++;
            } else {
                row.style.display = 'none';
            }
        });

        if (searchResultsCount) {
            searchResultsCount.textContent = q ? `Ditemukan ${count} pengguna` : `Menampilkan ${count} pengguna`;
        }
        return count;
    }

    async function fetchUsersFromServer(q) {
        try {
            const res = await fetch(`/admin/chats/search-users?q=${encodeURIComponent(q)}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success && data.users) {
                renderServerUsers(data.users, q);
            }
        } catch (err) {
            console.error('Error fetching users:', err);
        }
    }

    function renderServerUsers(users, query) {
        if (!usersContainer) return;

        if (users.length === 0) {
            usersContainer.innerHTML = `
                <div style="text-align: center; color: #94a3b8; padding: 40px 10px; font-size: 0.86rem;">
                    <div style="font-weight: 700; color: #475569; margin-bottom: 4px;">Tidak ada pengguna yang cocok</div>
                    <p style="margin: 0; font-size: 0.8rem;">Tidak ditemukan pengguna dengan kata kunci "<strong>${escapeHtml(query)}</strong>".</p>
                </div>
            `;
            if (searchResultsCount) searchResultsCount.textContent = '0 pengguna ditemukan';
            return;
        }

        if (searchResultsCount) {
            searchResultsCount.textContent = `Ditemukan ${users.length} pengguna`;
        }

        usersContainer.innerHTML = users.map(user => {
            const avatarHtml = user.avatar
                ? `<img src="${user.avatar}" alt="${escapeHtml(user.name)}" style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; flex-shrink: 0;" referrerpolicy="no-referrer">`
                : `<div style="width: 42px; height: 42px; border-radius: 50%; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; flex-shrink: 0;">${user.name.charAt(0).toUpperCase()}</div>`;

            const hasConvBadge = user.has_conversation 
                ? `<span style="font-size: 0.68rem; font-weight: 700; background: #eff6ff; color: #1d4ed8; padding: 1px 7px; border-radius: 9999px;">💬 Ada Percakapan</span>`
                : '';

            return `
                <div class="user-row-card" 
                     data-name="${escapeHtml(user.name.toLowerCase())}"
                     data-email="${escapeHtml(user.email.toLowerCase())}"
                     data-phone="${escapeHtml((user.phone_number || '').toLowerCase())}"
                     style="display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; border-radius: 14px; margin-bottom: 6px; border: 1px solid #f1f5f9; transition: all 0.15s ease; background: #ffffff;">
                    
                    <div style="display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1;">
                        ${avatarHtml}
                        <div style="min-width: 0; flex: 1;">
                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <div style="font-weight: 700; font-size: 0.9rem; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ${escapeHtml(user.name)}
                                </div>
                                <span style="font-size: 0.68rem; font-weight: 700; padding: 1px 7px; border-radius: 9999px; background: #ecfdf5; color: #047857;">
                                    ${escapeHtml(user.role_label || 'Pelanggan')}
                                </span>
                                ${hasConvBadge}
                            </div>
                            <div style="font-size: 0.78rem; color: #64748b; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <span>${escapeHtml(user.email)}</span>
                                ${user.phone_number ? `<span>&bull; ${escapeHtml(user.phone_number)}</span>` : ''}
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            onclick="startChatWithUser(${user.id}, '${escapeHtml(user.name)}')" 
                            class="btn btn-primary btn-sm"
                            style="border-radius: 9999px; padding: 6px 16px; font-size: 0.78rem; font-weight: 700; flex-shrink: 0; margin-left: 10px;">
                        <span>Chat &rarr;</span>
                    </button>
                </div>
            `;
        }).join('');
    }

    // Eksekusi Memulai Chat dengan Pengguna Terpilih
    async function startChatWithUser(userId, userName) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch('{{ route("admin.chat.start") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ user_id: userId })
            });

            const data = await res.json();
            if (data.success && data.conversation_id) {
                closeNewChatModal();
                // Arahkan ke percakapan tersebut
                window.location.href = `/admin/chats?conversation_id=${data.conversation_id}`;
            } else {
                alert(data.message || 'Gagal memulai percakapan.');
            }
        } catch (err) {
            console.error('Error starting chat:', err);
            // Fallback reload query
            window.location.href = `/admin/chats?user_id=${userId}`;
        }
    }

    // =========================================================================
    // FILTER DAFTAR PERCAKAPAN DI SIDEBAR KIRI
    // =========================================================================
    function filterConversationList(query) {
        const q = query.trim().toLowerCase();
        const btnClear = document.getElementById('btnClearConvSearch');
        if (btnClear) btnClear.style.display = q ? 'block' : 'none';

        const items = document.querySelectorAll('.conv-item-link');
        const noMatchCard = document.getElementById('noConvMatchCard');
        const emptyState = document.getElementById('emptyConversationState');
        let matched = 0;

        items.forEach(item => {
            const name = item.dataset.name || '';
            const email = item.dataset.email || '';
            const phone = item.dataset.phone || '';

            if (!q || name.includes(q) || email.includes(q) || phone.includes(q)) {
                item.style.display = 'flex';
                matched++;
            } else {
                item.style.display = 'none';
            }
        });

        if (noMatchCard) {
            noMatchCard.style.display = (items.length > 0 && matched === 0) ? 'block' : 'none';
        }
    }

    function clearConversationSearch() {
        const input = document.getElementById('conversationSearchInput');
        if (input) {
            input.value = '';
            filterConversationList('');
            input.focus();
        }
    }

    // =========================================================================
    // THREAD CHAT AKTIF: SCROLL & BALAS PESAN
    // =========================================================================
    function scrollAdminChatToBottom() {
        const container = document.getElementById('adminMessagesContainer');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
    scrollAdminChatToBottom();

    // Auto-focus input balasan saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('adminMessageInput');
        if (input) setTimeout(() => input.focus(), 200);
    });

    async function handleAdminSendReply(e) {
        e.preventDefault();
        const input = document.getElementById('adminMessageInput');
        const convId = document.getElementById('conversationId').value;
        const msg = input.value.trim();
        if (!msg) return;

        input.disabled = true;
        const btn = document.getElementById('btnAdminSend');
        btn.disabled = true;

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch(`/admin/chats/${convId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ message: msg })
            });

            const data = await res.json();
            if (data.success) {
                input.value = '';
                appendAdminMessage(data.data);
            } else {
                alert(data.message || 'Gagal mengirim pesan.');
            }
        } catch (err) {
            console.error('Error sending reply:', err);
        } finally {
            input.disabled = false;
            btn.disabled = false;
            input.focus();
        }
    }

    function appendAdminMessage(msg) {
        const container = document.getElementById('adminMessagesContainer');
        if (!container) return;

        const div = document.createElement('div');
        div.style.cssText = 'display: flex; flex-direction: column; align-items: flex-end;';
        div.innerHTML = `
            <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                <span style="font-weight: 600;">Anda (${msg.sender_name})</span>
                <span>&bull;</span>
                <span>${msg.time}</span>
            </div>
            <div style="max-width: 75%; padding: 12px 18px; border-radius: 16px; font-size: 0.9rem; line-height: 1.5; word-break: break-word; background: #1a73e8; color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border-bottom-right-radius: 4px;">
                ${escapeHtml(msg.message)}
            </div>
        `;
        container.appendChild(div);
        scrollAdminChatToBottom();
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

    // Polling berkala untuk pesan baru (setiap 3.5 detik)
    @if($selectedConversation)
    setInterval(async () => {
        try {
            const convId = '{{ $selectedConversation->id }}';
            const res = await fetch(`/admin/chats/${convId}/messages`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success && data.messages) {
                renderAllAdminMessages(data.messages);
            }
        } catch (e) {
            // silent fail on network jitter
        }
    }, 3500);

    let lastMessageCount = {{ $messages->count() }};
    function renderAllAdminMessages(messages) {
        if (messages.length === lastMessageCount) return;
        lastMessageCount = messages.length;

        const container = document.getElementById('adminMessagesContainer');
        if (!container) return;

        container.innerHTML = messages.map(msg => {
            const isFromAdmin = msg.is_admin;
            return `
                <div style="display: flex; flex-direction: column; align-items: ${isFromAdmin ? 'flex-end' : 'flex-start'};">
                    <div style="font-size: 0.72rem; color: #64748b; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                        <span style="font-weight: 600;">${isFromAdmin ? 'Anda (' + msg.sender_name + ')' : msg.sender_name}</span>
                        <span>&bull;</span>
                        <span>${msg.time}</span>
                    </div>
                    <div style="max-width: 75%; padding: 12px 18px; border-radius: 16px; font-size: 0.9rem; line-height: 1.5; word-break: break-word; background: ${isFromAdmin ? '#1a73e8' : '#ffffff'}; color: ${isFromAdmin ? '#ffffff' : '#1e293b'}; border: ${isFromAdmin ? 'none' : '1px solid #e2e8f0'}; box-shadow: 0 1px 3px rgba(0,0,0,0.04); ${isFromAdmin ? 'border-bottom-right-radius: 4px;' : 'border-bottom-left-radius: 4px;'}">
                        ${escapeHtml(msg.message)}
                    </div>
                </div>
            `;
        }).join('');
        scrollAdminChatToBottom();
    }
    @endif
</script>
@endpush
@endsection
