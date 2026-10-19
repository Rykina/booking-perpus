<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * =========================================================================
     * PELANGGAN (CUSTOMER) ENDPOINTS
     * =========================================================================
     */

    /**
     * Ambil data percakapan aktif milik pelanggan yang sedang login.
     */
    public function getCustomerConversation(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cari atau buat percakapan aktif milik pengguna
        $conversation = ChatConversation::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['last_message_at' => now()]
        );

        // Ambil riwayat pesan
        $messages = $conversation->messages()
            ->with('sender:id,name,avatar,role')
            ->orderBy('created_at', 'asc')
            ->get();

        // Tandai pesan dari admin sebagai sudah dibaca oleh pelanggan
        $conversation->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', $user->id)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'messages' => $messages->map(function ($msg) use ($user) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_me' => ($msg->sender_id === $user->id),
                    'sender_name' => $msg->sender ? $msg->sender->name : 'Pengguna',
                    'sender_avatar' => $msg->sender ? $msg->sender->avatar : null,
                    'is_admin' => $msg->sender ? $msg->sender->isAdmin() : false,
                    'is_read' => $msg->is_read,
                    'time' => $msg->created_at->format('H:i'),
                    'date' => $msg->created_at->format('d M Y'),
                ];
            }),
        ]);
    }

    /**
     * Pelanggan mengirim pesan ke admin.
     */
    public function customerSendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ], [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $conversation = ChatConversation::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['last_message_at' => now()]
        );

        $chatMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil terkirim.',
            'data' => [
                'id' => $chatMessage->id,
                'message' => $chatMessage->message,
                'is_me' => true,
                'sender_name' => $user->name,
                'sender_avatar' => $user->avatar,
                'is_read' => false,
                'time' => $chatMessage->created_at->format('H:i'),
                'date' => $chatMessage->created_at->format('d M Y'),
            ],
        ]);
    }

    /**
     * =========================================================================
     * ADMIN ENDPOINTS (DILINDUNGI MIDDLEWARE ADMIN)
     * =========================================================================
     */

    /**
     * Tampilkan halaman utama kelola pesan chat pelanggan untuk admin.
     */
    public function adminIndex(Request $request)
    {
        $search = trim($request->get('search', ''));
        $conversationsQuery = ChatConversation::with(['user', 'latestMessage']);

        if (!empty($search)) {
            $conversationsQuery->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }

        $conversations = $conversationsQuery->orderBy('last_message_at', 'desc')->get();

        // Ambil daftar pengguna terdaftar untuk dipilih admin saat ingin chat baru
        $availableUsers = User::where('id', '!=', Auth::id())
            ->orderBy('name', 'asc')
            ->take(50)
            ->get();

        $userIds = $availableUsers->pluck('id')->toArray();
        $existingConversations = ChatConversation::whereIn('user_id', $userIds)
            ->pluck('id', 'user_id')
            ->toArray();

        // Jika admin memilih pengguna via query param user_id (memulai chat langsung)
        $targetUserId = $request->get('user_id');
        if ($targetUserId) {
            $targetUser = User::find($targetUserId);
            if ($targetUser) {
                $conv = ChatConversation::firstOrCreate(
                    ['user_id' => $targetUser->id, 'status' => 'active'],
                    ['last_message_at' => now()]
                );
                return redirect()->route('admin.chat.index', ['conversation_id' => $conv->id]);
            }
        }

        $activeConversationId = $request->get('conversation_id');
        $selectedConversation = null;
        $messages = collect();

        if ($activeConversationId) {
            $selectedConversation = ChatConversation::with('user')->find($activeConversationId);
            if ($selectedConversation) {
                // Tandai pesan dari customer sebagai sudah dibaca admin
                $selectedConversation->messages()
                    ->where('is_read', false)
                    ->where('sender_id', $selectedConversation->user_id)
                    ->update(['is_read' => true]);

                $messages = $selectedConversation->messages()
                    ->with('sender')
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        } elseif ($conversations->isNotEmpty()) {
            $selectedConversation = $conversations->first();
            $selectedConversation->messages()
                ->where('is_read', false)
                ->where('sender_id', $selectedConversation->user_id)
                ->update(['is_read' => true]);

            $messages = $selectedConversation->messages()
                ->with('sender')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'conversations' => $conversations,
                'selected_conversation' => $selectedConversation,
                'messages' => $messages,
            ]);
        }

        return view('admin.chat.index', compact(
            'conversations', 
            'selectedConversation', 
            'messages', 
            'availableUsers', 
            'existingConversations',
            'search'
        ));
    }

    /**
     * Cari pengguna terdaftar secara dinamis via AJAX (untuk modal Chat Baru).
     */
    public function adminSearchUsers(Request $request)
    {
        $q = trim($request->get('q', ''));
        $currentUserId = Auth::id();

        $query = User::where('id', '!=', $currentUserId);

        if (!empty($q)) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone_number', 'like', "%{$q}%")
                    ->orWhere('identity_number', 'like', "%{$q}%");
            });
        }

        $users = $query->orderBy('name', 'asc')->take(30)->get();
        $userIds = $users->pluck('id')->toArray();
        $existingConversations = ChatConversation::whereIn('user_id', $userIds)
            ->pluck('id', 'user_id')
            ->toArray();

        return response()->json([
            'success' => true,
            'users' => $users->map(function ($u) use ($existingConversations) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'phone_number' => $u->phone_number,
                    'identity_number' => $u->identity_number,
                    'role_label' => $u->role_label,
                    'avatar' => $u->avatar,
                    'has_conversation' => isset($existingConversations[$u->id]),
                    'conversation_id' => $existingConversations[$u->id] ?? null,
                ];
            }),
        ]);
    }

    /**
     * Memulai percakapan chat baru dari admin ke pengguna tertentu.
     */
    public function adminStartChat(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:2000',
        ], [
            'user_id.required' => 'Pilih pengguna terlebih dahulu.',
            'user_id.exists' => 'Pengguna tidak ditemukan dalam sistem.',
        ]);

        $user = User::findOrFail($request->user_id);
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        // Cari atau buat percakapan aktif dengan pengguna yang dipilih
        $conversation = ChatConversation::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'active'],
            ['last_message_at' => now()]
        );

        // Jika admin juga langsung menulis pesan pertama
        if ($request->filled('message')) {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $admin->id,
                'message' => trim($request->message),
                'is_read' => false,
            ]);
            $conversation->update(['last_message_at' => now()]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'conversation_id' => $conversation->id,
                'redirect_url' => route('admin.chat.index', ['conversation_id' => $conversation->id]),
                'message' => 'Percakapan berhasil dimulai.',
            ]);
        }

        return redirect()->route('admin.chat.index', ['conversation_id' => $conversation->id])
            ->with('success', 'Percakapan dengan ' . $user->name . ' telah dimulai.');
    }

    /**
     * Admin membalas pesan pelanggan pada percakapan tertentu.
     */
    public function adminReplyMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ], [
            'message.required' => 'Pesan balasan tidak boleh kosong.',
            'message.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        $conversation = ChatConversation::findOrFail($id);

        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        $chatMessage = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $admin->id,
            'message' => trim($request->message),
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil terkirim.',
                'data' => [
                    'id' => $chatMessage->id,
                    'message' => $chatMessage->message,
                    'is_me' => true,
                    'sender_name' => $admin->name,
                    'sender_avatar' => $admin->avatar,
                    'is_read' => false,
                    'time' => $chatMessage->created_at->format('H:i'),
                    'date' => $chatMessage->created_at->format('d M Y'),
                ],
            ]);
        }

        return redirect()->route('admin.chat.index', ['conversation_id' => $conversation->id])
            ->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Polling endpoint untuk admin mengecek pesan baru pada percakapan aktif.
     */
    public function adminFetchMessages(Request $request, $id)
    {
        $conversation = ChatConversation::with('user')->findOrFail($id);
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        // Tandai pesan dari customer sebagai terbaca
        $conversation->messages()
            ->where('is_read', false)
            ->where('sender_id', $conversation->user_id)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()
            ->with('sender:id,name,avatar,role')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'customer_name' => $conversation->user->name,
            'customer_email' => $conversation->user->email,
            'customer_avatar' => $conversation->user->avatar,
            'messages' => $messages->map(function ($msg) use ($admin) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'is_me' => ($msg->sender_id === $admin->id),
                    'sender_name' => $msg->sender ? $msg->sender->name : 'Pengguna',
                    'sender_avatar' => $msg->sender ? $msg->sender->avatar : null,
                    'is_admin' => $msg->sender ? $msg->sender->isAdmin() : false,
                    'is_read' => $msg->is_read,
                    'time' => $msg->created_at->format('H:i'),
                    'date' => $msg->created_at->format('d M Y'),
                ];
            }),
        ]);
    }
}

