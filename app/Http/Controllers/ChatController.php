<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ChatController extends Controller
{
    /**
     * Ensure chat_messages database table exists on any environment (including InfinityFree).
     */
    protected function ensureTableExists(): void
    {
        try {
            if (! Schema::hasTable('chat_messages')) {
                Artisan::call('migrate', ['--force' => true]);
            }
        } catch (\Throwable $e) {
            // Silently recover if migration table locked or already running
        }
    }

    /**
     * Get initial chat partner and unread summary.
     */
    public function initChat(): JsonResponse
    {
        $this->ensureTableExists();
        $user = Auth::user();

        if ($user->isStudent()) {
            // Student always chats with the Chief Examiner
            $examiner = User::where('role', 'chief_examiner')
                ->where('is_active', true)
                ->first();

            if (! $examiner) {
                // Fallback to any chief examiner or admin
                $examiner = User::whereIn('role', ['chief_examiner', 'admin'])->first();
            }

            if (! $examiner) {
                return response()->json([
                    'error' => 'Chief Examiner account not found.',
                ], 404);
            }

            return response()->json([
                'role' => 'student',
                'partner' => [
                    'id' => $examiner->id,
                    'full_name' => $examiner->full_name,
                    'role_title' => 'Chief Examination Officer',
                    'avatar_initials' => $this->getInitials($examiner->full_name),
                    'status' => 'Online',
                ],
                'unread_count' => $user->unreadChatMessagesCount(),
            ]);
        }

        // Staff / Chief Examiner view: returns recent conversations
        return response()->json([
            'role' => $user->role,
            'conversations' => $this->fetchStaffConversations($user->id),
            'unread_count' => $user->unreadChatMessagesCount(),
        ]);
    }

    /**
     * Get staff conversations list with latest message and unread count per student.
     */
    public function getConversations(): JsonResponse
    {
        $this->ensureTableExists();
        $user = Auth::user();

        if ($user->isStudent()) {
            return $this->initChat();
        }

        return response()->json([
            'conversations' => $this->fetchStaffConversations($user->id),
            'unread_count' => $user->unreadChatMessagesCount(),
        ]);
    }

    /**
     * Get messages with a specific partner, optionally starting after last_message_id.
     */
    public function getMessages(Request $request): JsonResponse
    {
        $this->ensureTableExists();
        $request->validate([
            'with_user_id' => 'required|integer|exists:users,id',
            'last_message_id' => 'nullable|integer',
        ]);

        $userId = Auth::id();
        $partnerId = (int) $request->with_user_id;
        $lastMessageId = $request->last_message_id ? (int) $request->last_message_id : null;

        $partner = User::find($partnerId);
        if (! $partner) {
            return response()->json(['error' => 'Partner not found.'], 404);
        }

        $query = ChatMessage::betweenUsers($userId, $partnerId);

        if ($lastMessageId) {
            $query->where('id', '>', $lastMessageId);
        } else {
            // Load latest 100 messages if initial load
            $query->latest('id')->take(100);
        }

        $rawMessages = $query->get();

        if (! $lastMessageId) {
            $rawMessages = $rawMessages->sortBy('id')->values();
        }

        // Mark incoming messages as read
        ChatMessage::where('sender_id', $partnerId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $formattedMessages = $rawMessages->map(function ($msg) use ($userId) {
            return $this->formatMessage($msg, $userId);
        });

        $roleTitle = $partner->isChiefExaminer()
            ? 'Chief Examination Officer'
            : ($partner->isAdmin() ? 'System Administrator' : ($partner->student_id_number ? 'Student • ' . $partner->student_id_number : 'Student'));

        return response()->json([
            'messages' => $formattedMessages,
            'partner' => [
                'id' => $partner->id,
                'full_name' => $partner->full_name,
                'role_title' => $roleTitle,
                'avatar_initials' => $this->getInitials($partner->full_name),
                'status' => 'Online',
            ],
            'unread_count' => Auth::user()->unreadChatMessagesCount(),
        ]);
    }

    /**
     * Send a new message.
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $this->ensureTableExists();
        $request->validate([
            'receiver_id' => 'required|integer|exists:users,id',
            'message' => 'required|string|max:3000',
        ]);

        $sender = Auth::user();
        $receiverId = (int) $request->receiver_id;
        $cleanMessage = trim($request->message);

        if ($cleanMessage === '') {
            return response()->json(['error' => 'Message cannot be empty.'], 422);
        }

        $message = ChatMessage::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'message' => $cleanMessage,
            'is_read' => false,
        ]);

        return response()->json([
            'message' => $this->formatMessage($message, $sender->id),
            'unread_count' => $sender->unreadChatMessagesCount(),
        ], 201);
    }

    /**
     * Mark all unread messages from a sender as read.
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'sender_id' => 'required|integer|exists:users,id',
        ]);

        $userId = Auth::id();
        $senderId = (int) $request->sender_id;

        ChatMessage::where('sender_id', $senderId)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'unread_count' => Auth::user()->unreadChatMessagesCount(),
        ]);
    }

    /**
     * Get global unread messages count.
     */
    public function getUnreadCount(): JsonResponse
    {
        $this->ensureTableExists();
        return response()->json([
            'unread_count' => Auth::user()->unreadChatMessagesCount(),
        ]);
    }

    /**
     * Search students for Chief Examiner to initiate or find chat.
     */
    public function searchStudents(Request $request): JsonResponse
    {
        $query = trim($request->get('q', ''));

        if ($query === '') {
            $students = User::where('role', 'student')
                ->where('is_active', true)
                ->orderBy('full_name')
                ->take(15)
                ->get();
        } else {
            $students = User::where('role', 'student')
                ->where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('full_name', 'like', "%{$query}%")
                      ->orWhere('student_id_number', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->orderBy('full_name')
                ->take(15)
                ->get();
        }

        $formatted = $students->map(function ($stu) {
            return [
                'id' => $stu->id,
                'full_name' => $stu->full_name,
                'student_id_number' => $stu->student_id_number ?? 'N/A',
                'avatar_initials' => $this->getInitials($stu->full_name),
            ];
        });

        return response()->json(['students' => $formatted]);
    }

    /**
     * Helper to retrieve all student conversations for staff.
     */
    protected function fetchStaffConversations(int $staffId): array
    {
        // Get all unique users who have exchanged messages with this staff member
        $partnerIds = ChatMessage::where('sender_id', $staffId)
            ->orWhere('receiver_id', $staffId)
            ->selectRaw('CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as partner_id', [$staffId])
            ->distinct()
            ->pluck('partner_id')
            ->toArray();

        // Also include any students with registered resits to make them readily accessible
        $partners = User::whereIn('id', $partnerIds)
            ->get()
            ->keyBy('id');

        $conversations = [];

        foreach ($partners as $partnerId => $partner) {
            $lastMessage = ChatMessage::betweenUsers($staffId, $partnerId)
                ->latest('id')
                ->first();

            $unreadCount = ChatMessage::where('sender_id', $partnerId)
                ->where('receiver_id', $staffId)
                ->where('is_read', false)
                ->count();

            $conversations[] = [
                'partner' => [
                    'id' => $partner->id,
                    'full_name' => $partner->full_name,
                    'student_id_number' => $partner->student_id_number ?? 'ID N/A',
                    'avatar_initials' => $this->getInitials($partner->full_name),
                    'role' => $partner->role,
                ],
                'last_message' => $lastMessage ? [
                    'id' => $lastMessage->id,
                    'message' => mb_strimwidth($lastMessage->message, 0, 48, '...'),
                    'is_me' => $lastMessage->sender_id === $staffId,
                    'created_at_human' => $lastMessage->created_at->diffForHumans(null, true, true),
                    'timestamp' => $lastMessage->created_at->timestamp,
                ] : null,
                'unread_count' => $unreadCount,
            ];
        }

        // Sort by latest message timestamp descending
        usort($conversations, function ($a, $b) {
            $timeA = $a['last_message']['timestamp'] ?? 0;
            $timeB = $b['last_message']['timestamp'] ?? 0;
            return $timeB <=> $timeA;
        });

        return $conversations;
    }

    /**
     * Format a message model for JSON response.
     */
    protected function formatMessage(ChatMessage $msg, int $currentUserId): array
    {
        $isMe = $msg->sender_id === $currentUserId;
        $created = $msg->created_at ?? now();

        return [
            'id' => $msg->id,
            'sender_id' => $msg->sender_id,
            'receiver_id' => $msg->receiver_id,
            'is_me' => $isMe,
            'message' => $msg->message,
            'time' => $created->format('h:i A'),
            'date_group' => $created->isToday() ? 'Today' : ($created->isYesterday() ? 'Yesterday' : $created->format('M d, Y')),
            'is_read' => (bool) $msg->is_read,
            'created_at_human' => $created->diffForHumans(),
        ];
    }

    /**
     * Compute 2-letter uppercase initials from full name.
     */
    protected function getInitials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));
        $initials = '';

        foreach ($words as $w) {
            if ($w !== '') {
                $initials .= mb_substr($w, 0, 1);
            }
            if (mb_strlen($initials) >= 2) {
                break;
            }
        }

        return strtoupper($initials ?: 'U');
    }
}
