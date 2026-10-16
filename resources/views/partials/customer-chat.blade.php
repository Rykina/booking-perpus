<!-- ========================================================================= -->
<!-- FLOATING CUSTOMER CHAT WIDGET -->
<!-- ========================================================================= -->
<div id="customerChatWidgetWrap">
    
    <!-- Floating Launcher Button -->
    <button type="button" 
            id="btnCustomerChatTrigger" 
            onclick="toggleCustomerChat()" 
            aria-label="Buka Chat Petugas Perpustakaan"
            title="Chat dengan Petugas Perpustakaan Bogor"
            style="position: fixed; bottom: 26px; right: 26px; z-index: 995; width: 56px; height: 56px; border-radius: 50%; background: #1a73e8; color: #ffffff; border: none; box-shadow: 0 4px 20px rgba(26, 115, 232, 0.45); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);">
        
        <!-- Chat Icon -->
        <svg id="chatIconOpen" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>

        <!-- Close Icon (Hidden by default) -->
        <svg id="chatIconClose" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>

        <!-- Unread Badge -->
        <span id="chatUnreadBadge" style="display: none; position: absolute; top: -3px; right: -3px; background: #ef4444; color: #ffffff; font-size: 0.7rem; font-weight: 800; min-width: 20px; height: 20px; border-radius: 9999px; border: 2px solid #ffffff; align-items: center; justify-content: center; padding: 0 4px;">
            0
        </span>
    </button>

    <!-- Floating Chat Window -->
    <div id="customerChatBox" 
         style="display: none; position: fixed; bottom: 94px; right: 26px; z-index: 996; width: 370px; max-width: calc(100vw - 32px); height: 520px; max-height: calc(100vh - 120px); background: #ffffff; border-radius: 20px; box-shadow: 0 12px 36px rgba(15, 23, 42, 0.16); border: 1px solid #e2e8f0; overflow: hidden; flex-direction: column;">
        
        <!-- Window Header -->
        <div style="background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); padding: 16px 18px; color: #ffffff; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="position: relative;">
                    <div style="width: 38px; height: 38px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2">
                            <path d="M3 21h18"></path>
                            <path d="M5 21V10"></path>
                            <path d="M19 21V10"></path>
                            <path d="M9 21V10"></path>
                            <path d="M15 21V10"></path>
                            <path d="M2 10l10-6 10 6"></path>
                        </svg>
                    </div>
                    <span style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #22c55e; border-radius: 50%; border: 2px solid #1a73e8;"></span>
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: #ffffff;">Layanan Chat Perpustakaan</h4>
                    <span style="font-size: 0.74rem; color: #e0e7ff; display: inline-flex; align-items: center; gap: 4px;">
                        <span>Petugas siap melayani</span>
                    </span>
                </div>
            </div>
            <button type="button" 
                    onclick="toggleCustomerChat()" 
                    aria-label="Tutup Chat" 
                    style="background: transparent; border: none; color: #ffffff; cursor: pointer; padding: 4px; display: flex; opacity: 0.85; transition: opacity 0.2s ease;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Chat Stream Body -->
        <div id="customerChatStream" 
             style="flex: 1; padding: 18px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 12px;">
            
            @guest
                <!-- Guest notice prompt to login -->
                <div style="text-align: center; margin: auto 0; padding: 20px 10px;">
                    <div style="width: 50px; height: 50px; border-radius: 50%; background: #eff6ff; color: #1a73e8; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px auto;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                    </div>
                    <h5 style="margin: 0 0 6px 0; font-size: 0.98rem; font-weight: 700; color: #0f172a;">Masuk untuk Memulai Chat</h5>
                    <p style="font-size: 0.82rem; color: #64748b; line-height: 1.45; margin: 0 0 18px 0;">
                        Silakan login dengan akun pelanggan Anda agar riwayat percakapan tersimpan dan dapat ditindaklanjuti oleh petugas perpustakaan.
                    </p>
                    <a href="{{ route('pelanggan.login') }}" 
                       style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: #1a73e8; border: 1.5px solid #1a73e8; color: #ffffff; padding: 10px 22px; border-radius: 9999px; font-weight: 700; font-size: 0.86rem; text-decoration: none; box-shadow: 0 3px 10px rgba(26, 115, 232, 0.3); transition: all 0.2s ease;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                        <span>Masuk ke Akun Pelanggan</span>
                    </a>
                    <div style="margin-top: 12px; font-size: 0.78rem; color: #64748b;">
                        Belum punya akun? <a href="{{ route('pelanggan.register') }}" style="color: #1a73e8; font-weight: 700; text-decoration: none;">Daftar di sini</a>
                    </div>
                </div>
            @else
                <!-- Loading indicator while messages load -->
                <div id="customerChatLoading" style="text-align: center; color: #94a3b8; font-size: 0.84rem; padding: 20px 0;">
                    Memuat riwayat percakapan...
                </div>
            @endguest

        </div>

        @auth
        <!-- Input Form Footer -->
        <div style="padding: 12px 14px; background: #ffffff; border-top: 1px solid #e2e8f0;">
            <form id="customerChatForm" onsubmit="handleCustomerSendChat(event)" style="display: flex; gap: 8px; align-items: center;">
                <input type="text" 
                       id="customerChatInput" 
                       placeholder="Tulis pesan atau pertanyaan..." 
                       required
                       style="flex: 1; padding: 10px 14px; border-radius: 9999px; border: 1.5px solid #cbd5e1; outline: none; font-size: 0.86rem; transition: border-color 0.2s ease;">
                
                <button type="submit" 
                        id="btnCustomerChatSend" 
                        aria-label="Kirim Pesan"
                        style="width: 40px; height: 40px; border-radius: 50%; background: #1a73e8; color: #ffffff; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; box-shadow: 0 2px 8px rgba(26, 115, 232, 0.35);">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
        </div>
        @endauth

    </div>

</div>

<!-- Customer Chat JavaScript Logic -->
<script>
    let isCustomerChatOpen = false;
    let customerChatPollInterval = null;
    let currentCustomerMsgCount = 0;

    function toggleCustomerChat() {
        const box = document.getElementById('customerChatBox');
        const iconOpen = document.getElementById('chatIconOpen');
        const iconClose = document.getElementById('chatIconClose');

        if (!box) return;

        isCustomerChatOpen = !isCustomerChatOpen;

        if (isCustomerChatOpen) {
            box.style.display = 'flex';
            iconOpen.style.display = 'none';
            iconClose.style.display = 'block';

            // Clear unread badge
            const badge = document.getElementById('chatUnreadBadge');
            if (badge) {
                badge.style.display = 'none';
                badge.textContent = '0';
            }

            @auth
            loadCustomerMessages(true);
            if (!customerChatPollInterval) {
                customerChatPollInterval = setInterval(() => {
                    loadCustomerMessages(false);
                }, 3500);
            }
            @endauth
        } else {
            box.style.display = 'none';
            iconOpen.style.display = 'block';
            iconClose.style.display = 'none';

            if (customerChatPollInterval) {
                clearInterval(customerChatPollInterval);
                customerChatPollInterval = null;
            }
        }
    }

    @auth
    async function loadCustomerMessages(isFirstLoad = false) {
        try {
            const res = await fetch('{{ route("chat.conversation") }}', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.success && data.messages) {
                const stream = document.getElementById('customerChatStream');
                const loading = document.getElementById('customerChatLoading');
                if (loading) loading.style.display = 'none';

                if (data.messages.length === 0) {
                    if (isFirstLoad) {
                        stream.innerHTML = `
                            <div style="text-align: center; color: #94a3b8; font-size: 0.82rem; margin: auto 0; padding: 20px 10px;">
                                <div style="font-size: 1.4rem; margin-bottom: 6px;">👋</div>
                                <strong>Halo {{ Auth::user()->name }}!</strong><br>
                                Ada yang bisa kami bantu seputar reservasi fasilitas atau penayangan videotron? Tuliskan pesan Anda di bawah.
                            </div>
                        `;
                    }
                    return;
                }

                if (data.messages.length !== currentCustomerMsgCount) {
                    currentCustomerMsgCount = data.messages.length;
                    renderCustomerMessages(data.messages);
                }
            }
        } catch (err) {
            console.error('Error fetching chat conversation:', err);
        }
    }

    function renderCustomerMessages(messages) {
        const stream = document.getElementById('customerChatStream');
        if (!stream) return;

        stream.innerHTML = messages.map(msg => {
            const isMe = msg.is_me;
            return `
                <div style="display: flex; flex-direction: column; align-items: ${isMe ? 'flex-end' : 'flex-start'};">
                    <div style="font-size: 0.68rem; color: #64748b; margin-bottom: 3px; display: flex; align-items: center; gap: 4px;">
                        <span>${isMe ? 'Anda' : (msg.is_admin ? 'Petugas Perpustakaan' : msg.sender_name)}</span>
                        <span>&bull;</span>
                        <span>${msg.time}</span>
                    </div>
                    <div style="max-width: 82%; padding: 10px 14px; border-radius: 16px; font-size: 0.86rem; line-height: 1.45; word-break: break-word; background: ${isMe ? '#1a73e8' : '#ffffff'}; color: ${isMe ? '#ffffff' : '#1e293b'}; border: ${isMe ? 'none' : '1px solid #e2e8f0'}; box-shadow: 0 1px 3px rgba(0,0,0,0.04); ${isMe ? 'border-bottom-right-radius: 4px;' : 'border-bottom-left-radius: 4px;'}">
                        ${escapeCustomerChatHtml(msg.message)}
                    </div>
                </div>
            `;
        }).join('');

        stream.scrollTop = stream.scrollHeight;
    }

    async function handleCustomerSendChat(e) {
        e.preventDefault();
        const input = document.getElementById('customerChatInput');
        const btn = document.getElementById('btnCustomerChatSend');
        const msg = input.value.trim();
        if (!msg) return;

        input.disabled = true;
        btn.disabled = true;

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const res = await fetch('{{ route("chat.send") }}', {
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
                appendSingleCustomerMessage(data.data);
            } else {
                alert(data.message || 'Gagal mengirim pesan.');
            }
        } catch (err) {
            console.error('Error sending message:', err);
        } finally {
            input.disabled = false;
            btn.disabled = false;
            input.focus();
        }
    }

    function appendSingleCustomerMessage(msg) {
        const stream = document.getElementById('customerChatStream');
        if (!stream) return;

        const div = document.createElement('div');
        div.style.cssText = 'display: flex; flex-direction: column; align-items: flex-end;';
        div.innerHTML = `
            <div style="font-size: 0.68rem; color: #64748b; margin-bottom: 3px; display: flex; align-items: center; gap: 4px;">
                <span>Anda</span>
                <span>&bull;</span>
                <span>${msg.time}</span>
            </div>
            <div style="max-width: 82%; padding: 10px 14px; border-radius: 16px; font-size: 0.86rem; line-height: 1.45; word-break: break-word; background: #1a73e8; color: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border-bottom-right-radius: 4px;">
                ${escapeCustomerChatHtml(msg.message)}
            </div>
        `;
        stream.appendChild(div);
        stream.scrollTop = stream.scrollHeight;
        currentCustomerMsgCount++;
    }

    function escapeCustomerChatHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
    @endauth
</script>

