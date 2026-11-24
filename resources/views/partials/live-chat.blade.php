@auth
@php
    $currentUser = auth()->user();
    $isStaff = $currentUser->isChiefExaminer() || $currentUser->isAdmin();
    $isStudent = $currentUser->isStudent();
@endphp

<!-- ==========================================
     PASTEURION-STYLE LIVE CHAT SLIDE-OVER DRAWER
     ========================================== -->

<!-- Dark Backdrop -->
<div id="chat-backdrop" 
     onclick="toggleLiveChat(false)"
     class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-50 transition-opacity duration-300 opacity-0 pointer-events-none"
     aria-hidden="true"></div>

<!-- Slide-over Drawer Panel -->
<div id="chat-drawer" 
     class="fixed top-0 right-0 bottom-0 w-full sm:w-[420px] bg-white z-50 shadow-2xl flex flex-col border-l border-slate-200 transition-transform duration-300 ease-in-out translate-x-full"
     style="max-width: 100vw;"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="chat-title">

    <!-- Top Drawer Header (Matching Pasteurion Title & Close Button) -->
    <div class="px-4 py-3 border-b border-slate-200 bg-white flex items-center justify-between select-none">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-xl bg-brand-50 border border-brand-200 flex items-center justify-center text-brand-700">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div>
                <h3 id="chat-title" class="text-sm font-bold text-slate-900 tracking-tight">Messages</h3>
                <p class="text-[11px] text-slate-500 font-medium">USTED Live Resit &amp; Exam Inquiries</p>
            </div>
        </div>

        <button type="button" 
                onclick="toggleLiveChat(false)" 
                class="w-8 h-8 rounded-lg border border-slate-200 hover:border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-700 flex items-center justify-center transition"
                title="Close chat panel">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Active Conversation Sub-Header (Matching Pasteurion Doctor/Officer Bar) -->
    <div id="chat-partner-bar" class="px-4 py-2.5 bg-slate-50/80 border-b border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-3 min-w-0">
            @if($isStaff)
            <button type="button" 
                    id="chat-back-btn" 
                    onclick="showConversationsList()" 
                    class="p-1 rounded-lg text-slate-500 hover:text-brand-700 hover:bg-slate-200/60 transition"
                    title="Back to conversations">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            @endif

            <!-- Avatar -->
            <div class="relative flex-shrink-0">
                <div id="chat-partner-avatar" class="w-10 h-10 rounded-full bg-brand-700 text-white font-bold text-xs flex items-center justify-center shadow-xs border-2 border-white ring-1 ring-slate-200">
                    CE
                </div>
                <span id="chat-partner-status-dot" class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white"></span>
            </div>

            <!-- Name and Status -->
            <div class="min-w-0 flex-1">
                <h4 id="chat-partner-name" class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                    Chief Examination Officer
                </h4>
                <div class="flex items-center gap-1.5 text-[11px] text-slate-500 truncate">
                    <span id="chat-partner-role" class="truncate">Prof. Kwame Mensah</span>
                    <span>•</span>
                    <span id="chat-partner-status-text" class="text-emerald-600 font-semibold">Online</span>
                </div>
            </div>
        </div>

        <!-- Options Dropdown -->
        <div class="relative flex items-center gap-1">
            <button type="button" 
                    onclick="refreshActiveChat()" 
                    class="p-1.5 rounded-lg text-slate-400 hover:text-brand-700 hover:bg-slate-200/60 transition"
                    title="Refresh messages">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- STAFF ONLY: Conversations View (Search & List of Students) -->
    @if($isStaff)
    <div id="staff-conversations-view" class="hidden flex-1 flex-col overflow-hidden bg-slate-50">
        <!-- Search Bar -->
        <div class="p-3 bg-white border-b border-slate-200">
            <div class="relative">
                <input type="text" 
                       id="chat-student-search" 
                       placeholder="Search student by name or index #..." 
                       oninput="onSearchStudent(this.value)"
                       class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 bg-slate-50/50">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Conversations List -->
        <div id="conversations-list-container" class="flex-1 overflow-y-auto divide-y divide-slate-100 bg-white">
            <div class="p-6 text-center text-xs text-slate-400">Loading conversations...</div>
        </div>
    </div>
    @endif

    <!-- Main Message Thread Body -->
    <div id="chat-messages-container" class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/70">
        <!-- Message bubbles will be dynamically injected here -->
        <div class="flex items-center justify-center h-48">
            <div class="text-xs text-slate-400">Connecting to live chat...</div>
        </div>
    </div>

    <!-- Typing Indicator / System Notice (Subtle) -->
    <div id="chat-typing-status" class="hidden px-4 py-1 text-[11px] text-slate-500 bg-slate-50 border-t border-slate-100 flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-ping"></span>
        <span id="chat-typing-text">Connecting...</span>
    </div>

    <!-- Message Input Bar (Matching Pasteurion Input Box & Paper Plane Send) -->
    <div id="chat-input-bar" class="p-3 bg-white border-t border-slate-200">
        <form id="chat-send-form" onsubmit="handleChatSubmit(event)" class="flex items-end gap-2">
            @csrf
            <input type="hidden" id="chat-receiver-id" value="">

            <div class="flex-1 relative">
                <textarea id="chat-message-input" 
                          rows="1" 
                          placeholder="Write a message..." 
                          onkeydown="handleMessageKeydown(event)"
                          oninput="autoResizeTextarea(this)"
                          class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 resize-none max-h-28 bg-slate-50/60 leading-relaxed text-slate-800 placeholder-slate-400"></textarea>
            </div>

            <button type="submit" 
                    id="chat-send-btn"
                    class="h-10 w-10 flex-shrink-0 rounded-xl bg-brand-600 hover:bg-brand-700 text-white flex items-center justify-center shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed group"
                    title="Send message (Enter)">
                <svg class="w-4 h-4 transform rotate-45 -translate-x-0.5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                </svg>
            </button>
        </form>
        <div class="mt-1 flex items-center justify-between text-[10px] text-slate-400 px-1">
            <span>Press <kbd class="px-1 py-0.5 bg-slate-100 rounded border border-slate-200 font-mono text-[9px]">Enter</kbd> to send</span>
            <span>Shift + Enter for new line</span>
        </div>
    </div>
</div>

<!-- ==========================================
     FLOATING LIVE CHAT LAUNCHER BUTTON (FAB)
     ========================================== -->
<div class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-40">
    <button type="button" 
            onclick="toggleLiveChat()" 
            id="chat-fab-button"
            class="group flex items-center gap-2.5 px-4 py-3 rounded-full bg-brand-600 hover:bg-brand-700 text-white shadow-xl hover:shadow-2xl transition-all duration-200 transform hover:-translate-y-0.5 border border-brand-500/40 select-none">
        
        <!-- Chat Bubble Icon with Pulsing Online Badge -->
        <div class="relative">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <!-- Badge counter -->
            <span id="chat-unread-badge" 
                  class="hidden absolute -top-2 -right-2 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-black flex items-center justify-center border-2 border-brand-700 shadow-sm animate-bounce">
                0
            </span>
        </div>

        <span class="text-xs font-bold tracking-tight">
            {{ $isStaff ? 'Student Live Chat' : 'Chat Chief Examiner' }}
        </span>
    </button>
</div>

<!-- ==========================================
     LIVE CHAT ENGINE (JAVASCRIPT)
     ========================================== -->
<script>
    // Chat System State
    window.LiveChatState = {
        isOpen: false,
        currentUserRole: "{{ $currentUser->role }}",
        currentUserId: {{ $currentUser->id }},
        activePartnerId: null,
        activePartner: null,
        lastMessageId: 0,
        unreadCount: 0,
        pollTimer: null,
        unreadCheckTimer: null,
        renderedMessageIds: new Set(),
        isSending: false,
        isViewingConversationsList: false,
    };

    document.addEventListener('DOMContentLoaded', function () {
        // Initial fetch of unread count and setup
        fetchUnreadCount();
        
        // Background check for new unread messages every 15 seconds
        LiveChatState.unreadCheckTimer = setInterval(fetchUnreadCount, 15000);
    });

    /**
     * Toggle or explicitly open/close the Live Chat Drawer.
     */
    function toggleLiveChat(forceState) {
        const drawer = document.getElementById('chat-drawer');
        const backdrop = document.getElementById('chat-backdrop');
        const shouldOpen = (typeof forceState === 'boolean') ? forceState : !LiveChatState.isOpen;

        LiveChatState.isOpen = shouldOpen;

        if (shouldOpen) {
            // Show backdrop
            backdrop.classList.remove('pointer-events-none', 'opacity-0');
            backdrop.classList.add('opacity-100');

            // Slide in drawer
            drawer.classList.remove('translate-x-full');
            drawer.classList.add('translate-x-0');

            // Initialize chat or resume active partner
            if (!LiveChatState.activePartnerId) {
                initChatEngine();
            } else {
                fetchMessages(LiveChatState.activePartnerId);
            }

            // Start frequent polling (every 3.5 seconds) while drawer is open
            startChatPolling();

            // Focus message textarea
            setTimeout(function () {
                const input = document.getElementById('chat-message-input');
                if (input && !LiveChatState.isViewingConversationsList) {
                    input.focus();
                }
            }, 300);
        } else {
            // Hide backdrop
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            backdrop.classList.remove('opacity-100');

            // Slide out drawer
            drawer.classList.add('translate-x-full');
            drawer.classList.remove('translate-x-0');

            // Stop frequent polling
            stopChatPolling();
        }
    }

    /**
     * Shortcut to open live chat with a specific student (used from tables / dashboard buttons).
     */
    function openLiveChatWith(partnerId, partnerName, partnerRole) {
        LiveChatState.activePartnerId = partnerId;
        toggleLiveChat(true);

        if (LiveChatState.currentUserRole !== 'student') {
            showActiveChatView();
        }

        if (partnerName) {
            document.getElementById('chat-partner-name').textContent = partnerName;
            document.getElementById('chat-partner-role').textContent = partnerRole || 'Student';
            document.getElementById('chat-partner-avatar').textContent = getInitials(partnerName);
        }

        document.getElementById('chat-receiver-id').value = partnerId;
        LiveChatState.renderedMessageIds.clear();
        LiveChatState.lastMessageId = 0;
        fetchMessages(partnerId);
    }

    /**
     * Initialize chat partner or conversations list.
     */
    function initChatEngine() {
        fetch("{{ route('chat.init') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            updateUnreadBadge(data.unread_count || 0);

            if (data.role === 'student') {
                if (data.partner) {
                    setActivePartner(data.partner);
                    fetchMessages(data.partner.id);
                }
            } else {
                // Chief Examiner / Staff:
                renderStaffConversations(data.conversations || []);
                if (data.conversations && data.conversations.length > 0 && !LiveChatState.activePartnerId) {
                    // Open the most recent conversation by default
                    const firstConv = data.conversations[0];
                    setActivePartner(firstConv.partner);
                    showActiveChatView();
                    fetchMessages(firstConv.partner.id);
                } else if (!LiveChatState.activePartnerId) {
                    showConversationsList();
                }
            }
        })
        .catch(function (err) {
            console.error('Chat init error:', err);
        });
    }

    /**
     * Switch view on staff screen to Conversations list.
     */
    function showConversationsList() {
        LiveChatState.isViewingConversationsList = true;
        const convView = document.getElementById('staff-conversations-view');
        const msgContainer = document.getElementById('chat-messages-container');
        const inputBar = document.getElementById('chat-input-bar');
        const partnerBar = document.getElementById('chat-partner-bar');

        if (convView) convView.classList.remove('hidden');
        if (msgContainer) msgContainer.classList.add('hidden');
        if (inputBar) inputBar.classList.add('hidden');
        if (partnerBar) partnerBar.classList.add('hidden');

        fetchStaffConversationsList();
    }

    /**
     * Switch view on staff screen back to Active Chat view.
     */
    function showActiveChatView() {
        LiveChatState.isViewingConversationsList = false;
        const convView = document.getElementById('staff-conversations-view');
        const msgContainer = document.getElementById('chat-messages-container');
        const inputBar = document.getElementById('chat-input-bar');
        const partnerBar = document.getElementById('chat-partner-bar');

        if (convView) convView.classList.add('hidden');
        if (msgContainer) msgContainer.classList.remove('hidden');
        if (inputBar) inputBar.classList.remove('hidden');
        if (partnerBar) partnerBar.classList.remove('hidden');
    }

    /**
     * Set the active chat partner and update the sub-header info.
     */
    function setActivePartner(partner) {
        LiveChatState.activePartnerId = partner.id;
        LiveChatState.activePartner = partner;
        document.getElementById('chat-receiver-id').value = partner.id;
        document.getElementById('chat-partner-name').textContent = partner.full_name;
        document.getElementById('chat-partner-role').textContent = partner.role_title || partner.student_id_number || 'Academic';
        document.getElementById('chat-partner-avatar').textContent = partner.avatar_initials || getInitials(partner.full_name);
        document.getElementById('chat-partner-status-text').textContent = partner.status || 'Online';
    }

    /**
     * Fetch messages with partner, either all or incremental (if lastMessageId > 0).
     */
    function fetchMessages(partnerId) {
        if (!partnerId) return;

        const isIncremental = LiveChatState.lastMessageId > 0;
        const url = new URL("{{ route('chat.messages') }}", window.location.origin);
        url.searchParams.set('with_user_id', partnerId);
        if (isIncremental) {
            url.searchParams.set('last_message_id', LiveChatState.lastMessageId);
        }

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.partner) {
                setActivePartner(data.partner);
            }

            if (!isIncremental) {
                // Clear and render all messages
                renderAllMessages(data.messages || []);
            } else if (data.messages && data.messages.length > 0) {
                // Append only new messages
                appendNewMessages(data.messages);
            }

            if (typeof data.unread_count !== 'undefined') {
                updateUnreadBadge(data.unread_count);
            }
        })
        .catch(function (err) {
            console.error('Fetch messages error:', err);
        });
    }

    /**
     * Render entire messages array into the container.
     */
    function renderAllMessages(messages) {
        const container = document.getElementById('chat-messages-container');
        container.innerHTML = '';
        LiveChatState.renderedMessageIds.clear();

        if (!messages || messages.length === 0) {
            container.innerHTML = `
                <div class="p-6 text-center space-y-3 my-auto">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-50 text-brand-700 border border-brand-200 flex items-center justify-center">
                        <svg class="w-7 h-7 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h5 class="text-xs font-bold text-slate-800">Direct Live Chat Inquiry</h5>
                    <p class="text-[11px] text-slate-500 max-w-xs mx-auto leading-relaxed">
                        ${LiveChatState.currentUserRole === 'student' 
                            ? 'Welcome to the Chief Examiner direct desk. Send a message regarding your resit registration, timetables, course codes, or supplementary waivers.' 
                            : 'No messages yet with this student. Send a direct message below to assist them.'}
                    </p>
                </div>
            `;
            LiveChatState.lastMessageId = 0;
            return;
        }

        let currentDateGroup = null;

        messages.forEach(function (msg) {
            LiveChatState.renderedMessageIds.add(msg.id);
            if (msg.id > LiveChatState.lastMessageId) {
                LiveChatState.lastMessageId = msg.id;
            }

            // Insert date separator if date changed
            if (msg.date_group !== currentDateGroup) {
                currentDateGroup = msg.date_group;
                container.appendChild(createDatePill(currentDateGroup));
            }

            container.appendChild(createMessageBubble(msg));
        });

        scrollToBottom();
    }

    /**
     * Append incoming messages incrementally without resetting DOM.
     */
    function appendNewMessages(newMessages) {
        const container = document.getElementById('chat-messages-container');
        let shouldScroll = false;

        newMessages.forEach(function (msg) {
            if (LiveChatState.renderedMessageIds.has(msg.id)) return;

            LiveChatState.renderedMessageIds.add(msg.id);
            if (msg.id > LiveChatState.lastMessageId) {
                LiveChatState.lastMessageId = msg.id;
            }

            container.appendChild(createMessageBubble(msg));
            shouldScroll = true;
        });

        if (shouldScroll) {
            scrollToBottom();
        }
    }

    /**
     * Create date pill separator element.
     */
    function createDatePill(dateText) {
        const div = document.createElement('div');
        div.className = 'flex justify-center my-3 select-none';
        div.innerHTML = `<span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-200/80 text-slate-600 border border-slate-300/40">${escapeHtml(dateText)}</span>`;
        return div;
    }

    /**
     * Create message bubble element (Pasteurion Style).
     */
    function createMessageBubble(msg) {
        const div = document.createElement('div');
        div.id = 'msg-' + msg.id;

        if (msg.is_me) {
            // Sent by current user (Aligned right, deep wine background, white text)
            div.className = 'flex flex-col items-end space-y-1 ml-10';
            div.innerHTML = `
                <div class="px-3.5 py-2.5 rounded-2xl rounded-tr-xs bg-[#7a1326] text-white text-xs sm:text-[13px] leading-relaxed shadow-xs max-w-[85%] break-words">
                    ${escapeHtml(msg.message).replace(/\n/g, '<br>')}
                </div>
                <div class="flex items-center gap-1 text-[10px] text-slate-400 pr-1 select-none">
                    <span>${escapeHtml(msg.time)}</span>
                    <span class="text-brand-600 font-bold">✓✓</span>
                </div>
            `;
        } else {
            // Received message (Aligned left, white card with border, dark text)
            div.className = 'flex flex-col items-start space-y-1 mr-10';
            div.innerHTML = `
                <div class="px-3.5 py-2.5 rounded-2xl rounded-tl-xs bg-white text-slate-800 border border-slate-200/80 text-xs sm:text-[13px] leading-relaxed shadow-2xs max-w-[85%] break-words">
                    ${escapeHtml(msg.message).replace(/\n/g, '<br>')}
                </div>
                <div class="text-[10px] text-slate-400 pl-1 select-none">
                    ${escapeHtml(msg.time)}
                </div>
            `;
        }

        return div;
    }

    /**
     * Handle message form submission.
     */
    function handleChatSubmit(event) {
        if (event) event.preventDefault();
        if (LiveChatState.isSending) return;

        const input = document.getElementById('chat-message-input');
        const messageText = input.value.trim();
        const receiverId = document.getElementById('chat-receiver-id').value;

        if (!messageText || !receiverId) return;

        LiveChatState.isSending = true;
        const sendBtn = document.getElementById('chat-send-btn');
        sendBtn.disabled = true;

        // Clear input immediately for smooth responsive feel
        input.value = '';
        input.style.height = 'auto';

        // Optimistic UI insertion (temporary bubble)
        const tempId = 'temp-' + Date.now();
        const tempMsg = {
            id: tempId,
            is_me: true,
            message: messageText,
            time: 'Sending...',
            date_group: 'Today',
        };

        const container = document.getElementById('chat-messages-container');
        // If container has empty notice, clear it
        if (container.querySelector('.text-slate-400')) {
            container.innerHTML = '';
        }
        const tempElem = createMessageBubble(tempMsg);
        container.appendChild(tempElem);
        scrollToBottom();

        // Perform AJAX POST request
        const formData = new FormData();
        formData.append('receiver_id', receiverId);
        formData.append('message', messageText);

        fetch("{{ route('chat.send') }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(function (res) {
            if (!res.ok) throw new Error('Send failed');
            return res.json();
        })
        .then(function (data) {
            LiveChatState.isSending = false;
            sendBtn.disabled = false;

            // Remove temp element and append confirmed message
            if (tempElem && tempElem.parentNode) {
                tempElem.parentNode.removeChild(tempElem);
            }

            if (data.message) {
                LiveChatState.renderedMessageIds.add(data.message.id);
                if (data.message.id > LiveChatState.lastMessageId) {
                    LiveChatState.lastMessageId = data.message.id;
                }
                container.appendChild(createMessageBubble(data.message));
                scrollToBottom();
            }

            input.focus();
        })
        .catch(function (err) {
            console.error('Error sending message:', err);
            LiveChatState.isSending = false;
            sendBtn.disabled = false;
            if (tempElem) {
                tempElem.querySelector('.text-slate-400').innerHTML = '<span class="text-rose-500 font-semibold">Failed to send. Click to retry.</span>';
            }
        });
    }

    /**
     * Textarea auto-resize and Enter-to-send keyboard handling.
     */
    function handleMessageKeydown(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            handleChatSubmit();
        }
    }

    function autoResizeTextarea(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 110) + 'px';
    }

    function scrollToBottom() {
        const container = document.getElementById('chat-messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    function refreshActiveChat() {
        if (LiveChatState.activePartnerId) {
            fetchMessages(LiveChatState.activePartnerId);
        } else {
            initChatEngine();
        }
    }

    /**
     * Frequent polling while drawer is open.
     */
    function startChatPolling() {
        stopChatPolling();
        LiveChatState.pollTimer = setInterval(function () {
            if (LiveChatState.isOpen && LiveChatState.activePartnerId && !LiveChatState.isViewingConversationsList) {
                fetchMessages(LiveChatState.activePartnerId);
            }
        }, 3500);
    }

    function stopChatPolling() {
        if (LiveChatState.pollTimer) {
            clearInterval(LiveChatState.pollTimer);
            LiveChatState.pollTimer = null;
        }
    }

    /**
     * Global unread badge polling.
     */
    function fetchUnreadCount() {
        fetch("{{ route('chat.unread-count') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            updateUnreadBadge(data.unread_count || 0);
        })
        .catch(function (err) {
            // Silent error handling for background count
        });
    }

    function updateUnreadBadge(count) {
        LiveChatState.unreadCount = count;
        const fabBadge = document.getElementById('chat-unread-badge');
        const navBadge = document.getElementById('nav-chat-badge');
        const dashBadge = document.getElementById('dash-chat-badge');
        const mobileBadge = document.getElementById('mobile-chat-badge');

        if (count > 0) {
            const displayCount = count > 99 ? '99+' : count;
            if (fabBadge) {
                fabBadge.textContent = displayCount;
                fabBadge.classList.remove('hidden');
            }
            if (navBadge) {
                navBadge.textContent = displayCount;
                navBadge.classList.remove('hidden');
            }
            if (dashBadge) {
                dashBadge.textContent = displayCount;
                dashBadge.classList.remove('hidden');
            }
            if (mobileBadge) {
                mobileBadge.textContent = displayCount;
                mobileBadge.classList.remove('hidden');
            }
        } else {
            if (fabBadge) fabBadge.classList.add('hidden');
            if (navBadge) navBadge.classList.add('hidden');
            if (dashBadge) dashBadge.classList.add('hidden');
            if (mobileBadge) mobileBadge.classList.add('hidden');
        }
    }

    /**
     * Staff Conversations Fetch and Render.
     */
    function fetchStaffConversationsList() {
        fetch("{{ route('chat.conversations') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            renderStaffConversations(data.conversations || []);
        })
        .catch(function (err) {
            console.error('Error fetching staff conversations:', err);
        });
    }

    function renderStaffConversations(conversations) {
        const container = document.getElementById('conversations-list-container');
        if (!container) return;

        if (!conversations || conversations.length === 0) {
            container.innerHTML = `
                <div class="p-8 text-center text-xs text-slate-400 space-y-2">
                    <p class="font-semibold text-slate-600">No student conversations yet.</p>
                    <p class="text-[11px]">Use the search bar above to look up any student and start a chat.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = '';
        conversations.forEach(function (conv) {
            const p = conv.partner;
            const last = conv.last_message;
            const unread = conv.unread_count || 0;

            const item = document.createElement('div');
            item.className = 'p-3 hover:bg-slate-50 transition cursor-pointer flex items-center justify-between gap-3';
            item.onclick = function () {
                openLiveChatWith(p.id, p.full_name, 'Student • ' + (p.student_id_number || 'ID N/A'));
            };

            item.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-800 font-bold text-xs flex items-center justify-center flex-shrink-0">
                        ${escapeHtml(p.avatar_initials || getInitials(p.full_name))}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <h5 class="text-xs font-bold text-slate-900 truncate">${escapeHtml(p.full_name)}</h5>
                            ${last ? `<span class="text-[10px] text-slate-400 flex-shrink-0">${escapeHtml(last.created_at_human)}</span>` : ''}
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium truncate mt-0.5">
                            ${last ? (last.is_me ? '<span class="text-slate-400">You: </span>' : '') + escapeHtml(last.message) : '<span class="italic text-slate-400">Click to start conversation</span>'}
                        </p>
                    </div>
                </div>
                ${unread > 0 ? `
                    <span class="w-5 h-5 rounded-full bg-brand-600 text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 shadow-xs">
                        ${unread}
                    </span>
                ` : ''}
            `;

            container.appendChild(item);
        });
    }

    /**
     * Search student on Staff screen.
     */
    let searchDebounce = null;
    function onSearchStudent(query) {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(function () {
            fetch("{{ route('chat.search-students') }}?q=" + encodeURIComponent(query), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                renderStudentSearchResults(data.students || []);
            });
        }, 250);
    }

    function renderStudentSearchResults(students) {
        const container = document.getElementById('conversations-list-container');
        if (!container) return;

        if (!students || students.length === 0) {
            container.innerHTML = '<div class="p-6 text-center text-xs text-slate-400">No matching students found.</div>';
            return;
        }

        container.innerHTML = '';
        students.forEach(function (stu) {
            const item = document.createElement('div');
            item.className = 'p-3 hover:bg-brand-50/50 transition cursor-pointer flex items-center justify-between gap-3';
            item.onclick = function () {
                openLiveChatWith(stu.id, stu.full_name, 'Student • ' + stu.student_id_number);
            };

            item.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-800 font-bold text-xs flex items-center justify-center flex-shrink-0">
                        ${escapeHtml(stu.avatar_initials)}
                    </div>
                    <div class="min-w-0">
                        <h5 class="text-xs font-bold text-slate-900 truncate">${escapeHtml(stu.full_name)}</h5>
                        <p class="text-[11px] text-slate-500 font-mono">Index: ${escapeHtml(stu.student_id_number)}</p>
                    </div>
                </div>
                <button type="button" class="px-2.5 py-1 rounded-lg bg-brand-50 text-brand-700 text-[11px] font-bold border border-brand-200">
                    Chat
                </button>
            `;
            container.appendChild(item);
        });
    }

    /**
     * Utilities
     */
    function getInitials(name) {
        if (!name) return 'U';
        const parts = name.trim().split(/\s+/);
        let initials = '';
        for (let i = 0; i < parts.length && initials.length < 2; i++) {
            if (parts[i]) initials += parts[i][0];
        }
        return initials.toUpperCase() || 'U';
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
</script>
@endauth
