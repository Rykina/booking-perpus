@extends('layouts.admin')

@section('title', 'Inbox Chat Pelanggan - Admin Perpustakaan & Galeri Kota Bogor')

@section('content')
<div class="admin-chat-container" style="background: #f8fafc; min-height: 85vh; padding: 24px 0;">
    <div class="container" style="max-width: 1300px; margin: 0 auto; padding: 0 16px;">
        
        <!-- Header Info -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div>
                <div style="display: inline-flex; align-items: center; gap: 8px; background: #eff6ff; color: #1d4ed8; padding: 4px 12px; border-radius: 9999px; font-size: 0.78rem; font-weight: 700; margin-bottom: 6px;">
                    <span style="width: 8px; height: 8px; background: #2563eb; border-radius: 50%;"></span>
                    PANEL ADMINISTRATOR & PETUGAS
                </div>
                <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin: 0;">Konsultasi & Layanan Chat Pelanggan</h1>
                <p style="color: #64748b; font-size: 0.88rem; margin: 4px 0 0 0;">Kelola dan tanggapi pertanyaan, verifikasi berkas, serta konsultasi peminjam secara terpusat.</p>
            </div>
            <div>
                <a href="{{ route('booking.index') }}" class="btn btn-outline" style="border-radius: 9999px; padding: 7px 16px; font-size: 0.82rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
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
        <div style="display: grid; grid-template-columns: 340px 1fr; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05); min-height: 640px; overflow: hidden;" id="adminChatGrid">
            
            <!-- Left Sidebar: Conversations List -->
            <div style="border-right: 1px solid #e2e8f0; background: #ffffff; display: flex; flex-direction: column;">
                
                <div style="padding: 18px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                    <div style="font-weight: 700; color: #0f172a; font-size: 0.95rem;">
                        Percakapan Masuk ({{ $conversations->count() }})
                    </div>
                    <span style="font-size: 0.75rem; background: #f1f5f9; color: #64748b; padding: 2px 8px; border-radius: 9999px;">Real-time</span>
                </div>

                <!-- Conversation Items -->
                <div style="overflow-y: auto; flex: 1; max-height: 570px;" id="adminConversationList">
                    @forelse($conversations as $conv)
                        @php
                            $isSelected = $selectedConversation && $selectedConversation->id === $conv->id;
                            $unreadCount = $conv->unreadCountForAdmin();
                            $lastMsg = $conv->latestMessage;
                        @endphp
                        <a href="{{ route('admin.chat.index', ['conversation_id' => $conv->id]) }}" 
                           style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-bottom: 1px solid #f8fafc; text-decoration: none; color: inherit; transition: all 0.15s ease; background: {{ $isSelected ? '#eff6ff' : 'transparent' }}; border-left: {{ $isSelected ? '4px solid #1a73e8' : '4px solid transparent' }};">
                            
                            <!-- Customer Avatar -->
                            <div style="position: relative; flex-shrink: 0;">
                                @if($conv->user && $conv->user->avatar)
                                    <img src="{{ $conv->user->avatar }}" alt="{{ $conv->user->name }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;" referrerpolicy="no-referrer">
                                @else
                                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #e0e7ff; color: #3730a3; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem;">
                                        {{ strtoupper(substr($conv->user ? $conv->user->name : 'P', 0, 1)) }}
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
                                        {{ $conv->user ? $conv->user->name : 'Pelanggan #' . $conv->id }}
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
                        <div style="padding: 40px 20px; text-align: center; color: #94a3b8; font-size: 0.86rem;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.8" style="margin-bottom: 8px;">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            <p style="margin: 0;">Belum ada percakapan pelanggan yang masuk.</p>
                        </div>
                    @endforelse
                </div>

            </div>

            <!-- Right Main: Current Thread -->
            <div style="display: flex; flex-direction: column; background: #ffffff;">
                
                @if($selectedConversation)
                    <!-- Thread Header -->
                    <div style="padding: 16px 24px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; background: #ffffff;">
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
                                <div style="font-size: 0.8rem; color: #64748b; margin-top: 2px;">
                                    {{ $selectedConversation->user ? $selectedConversation->user->email : '-' }}
                                    @if($selectedConversation->user && $selectedConversation->user->phone_number)
                                        &bull; WA: {{ $selectedConversation->user->phone_number }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.78rem; color: #10b981; font-weight: 600;">
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
                            <div style="text-align: center; color: #94a3b8; font-size: 0.88rem; padding: 40px 0;">
                                Belum ada riwayat pesan dalam percakapan ini.
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
                                       style="flex: 1; padding: 12px 18px; border-radius: 9999px; border: 1.5px solid #cbd5e1; outline: none; font-size: 0.9rem; transition: border-color 0.2s ease;">
                                
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
                    <div style="flex: 1; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #94a3b8; padding: 40px;">
                        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.8" style="margin-bottom: 16px;">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <h3 style="font-size: 1.1rem; color: #475569; margin: 0 0 6px 0;">Pilih Percakapan Pelanggan</h3>
                        <p style="font-size: 0.88rem; margin: 0;">Pilih salah satu percakapan di kolom kiri untuk melihat pesan dan mengirim balasan.</p>
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
    // Scroll pesan ke bagian paling bawah
    function scrollAdminChatToBottom() {
        const container = document.getElementById('adminMessagesContainer');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
    scrollAdminChatToBottom();

    // Kirim pesan via AJAX tanpa reload halaman
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
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Polling berkala untuk mengecek balasan baru dari customer (setiap 4 detik)
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
    }, 4000);

    let lastMessageCount = {{ $messages->count() }};
    function renderAllAdminMessages(messages) {
        if (messages.length === lastMessageCount) return; // tidak ada pesan baru
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

