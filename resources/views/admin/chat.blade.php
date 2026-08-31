@extends('layouts.admin')

@section('styles')
    <style>
        .chat-container {
            display: flex;
            height: calc(100vh - 210px);
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
        }

        /* Sidebar - Session List */
        .chat-sidebar {
            width: 320px;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            background: rgba(15, 23, 42, 0.4);
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--text-main);
        }

        .session-list {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .session-item {
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 5px;
            transition: background 0.2s;
            border: 1px solid transparent;
        }

        .session-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .session-item.active {
            background: rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .session-name {
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
        }

        .session-status {
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 10px;
        }

        .status-open {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .status-closed {
            background: rgba(100, 116, 139, 0.2);
            color: #94a3b8;
        }

        .session-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* Main Chat Area */
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--bg-card);
        }

        .chat-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .msg-guest {
            align-self: flex-start;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 2px;
        }

        .msg-admin {
            align-self: flex-end;
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 2px;
        }

        .msg-time {
            font-size: 0.7rem;
            opacity: 0.7;
            margin-top: 4px;
            text-align: right;
        }

        /* Chat Input */
        .chat-input-area {
            padding: 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 10px;
            background: rgba(15, 23, 42, 0.4);
        }

        .chat-input {
            flex: 1;
            background: var(--bg-dark);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 16px;
            color: var(--text-main);
            outline: none;
            transition: border-color 0.2s;
        }

        .chat-input:focus {
            border-color: var(--primary);
        }

        .btn-send {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-send:hover {
            background: var(--primary-hover);
        }

        .no-chat-selected {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 1.1rem;
        }
    </style>
@endsection

@section('content')
    <div class="header-section" style="margin-bottom: 20px;">
        <div>
            <h1 class="page-title">Live Chat</h1>
            <p class="page-subtitle">Real-time support requests from the landing page</p>
        </div>
    </div>

    <div class="chat-container">
        <!-- Sidebar -->
        <div class="chat-sidebar">
            <div class="sidebar-header">
                Active Chats
            </div>
            <div class="session-list" id="sessionList">
                <!-- Sessions injected here via JS -->
                <div style="text-align:center; padding:20px; color:#64748b;">Loading...</div>
            </div>
        </div>

        <!-- Main Chat -->
        <div class="chat-main" id="chatMain" style="display: none;">
            <div class="chat-header">
                <div>
                    <h3 id="chatGuestName" style="margin:0; font-size:1.1rem; font-weight:600;">Guest</h3>
                    <div id="chatGuestEmail" style="font-size:0.8rem; color:var(--text-muted);"></div>
                </div>
                <button class="btn-danger" style="padding: 6px 12px; font-size: 0.8rem;" onclick="closeSession()">Close
                    Chat</button>
            </div>
            <div class="chat-messages" id="chatMessages">
                <!-- Messages injected here -->
            </div>
            <div class="chat-input-area">
                <input type="text" id="messageInput" class="chat-input" placeholder="Type your reply..."
                    onkeypress="handleEnter(event)">
                <button class="btn-send" onclick="sendMessage()">Send</button>
            </div>
        </div>

        <div id="noChatSelected" class="no-chat-selected">
            Select a conversation from the left to start chatting
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let activeSessionId = null;
        let lastMessageId = 0;
        let pollInterval = null;

        // Fetch sessions periodically
        async function fetchSessions() {
            try {
                const res = await fetch('{{ route("admin.chat.sessions") }}');
                const data = await res.json();

                const list = document.getElementById('sessionList');
                list.innerHTML = '';

                if (data.sessions.length === 0) {
                    list.innerHTML = '<div style="text-align:center; padding:20px; color:#64748b;">No chats yet.</div>';
                    return;
                }

                data.sessions.forEach(session => {
                    const el = document.createElement('div');
                    el.className = 'session-item' + (session.id === activeSessionId ? ' active' : '');
                    el.onclick = () => loadSession(session);

                    const time = new Date(session.last_activity_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    const statusCls = session.status === 'open' ? 'status-open' : 'status-closed';

                    el.innerHTML = `
                                    <div class="session-name">
                                        ${session.guest_name || 'Guest'}
                                        <span class="session-status ${statusCls}">${session.status}</span>
                                    </div>
                                    <div class="session-meta">
                                        ${session.guest_email || 'No email'} &bull; ${time}
                                    </div>
                                `;
                    list.appendChild(el);
                });
            } catch (e) {
                console.error(e);
            }
        }

        async function loadSession(session) {
            activeSessionId = session.id;
            lastMessageId = 0;

            document.getElementById('noChatSelected').style.display = 'none';
            document.getElementById('chatMain').style.display = 'flex';

            document.getElementById('chatGuestName').innerText = session.guest_name || 'Guest';
            document.getElementById('chatGuestEmail').innerText = session.guest_email || '';
            document.getElementById('chatMessages').innerHTML = '';

            // Update active class immediately
            document.querySelectorAll('.session-item').forEach(el => el.classList.remove('active'));
            fetchSessions(); // Refresh list to set active class properly

            await fetchMessages();
        }

        async function fetchMessages() {
            if (!activeSessionId) return;
            try {
                const res = await fetch(`/admin/api/chat/${activeSessionId}/messages?after=${lastMessageId}`);
                if (!res.ok) return;
                const data = await res.json();

                const container = document.getElementById('chatMessages');
                let shouldScroll = (container.scrollTop + container.clientHeight) >= (container.scrollHeight - 50);

                if (data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        const el = document.createElement('div');
                        el.className = 'message ' + (msg.sender_type === 'admin' ? 'msg-admin' : 'msg-guest');

                        const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                        el.innerHTML = `
                                        <div>${msg.message.replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>
                                        <div class="msg-time">${time}</div>
                                    `;
                        container.appendChild(el);
                        lastMessageId = msg.id;
                    });

                    // Auto scroll
                    container.scrollTop = container.scrollHeight;
                }
            } catch (e) {
                console.error(e);
            }
        }

        async function sendMessage() {
            if (!activeSessionId) return;

            const input = document.getElementById('messageInput');
            const text = input.value.trim();
            if (!text) return;

            input.value = '';
            input.focus();

            try {
                const res = await fetch(`/admin/api/chat/${activeSessionId}/message`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: text })
                });

                if (res.ok) {
                    await fetchMessages();
                    fetchSessions();
                }
            } catch (e) {
                console.error(e);
            }
        }

        async function closeSession() {
            if (!activeSessionId) return;
            if (!confirm('Are you sure you want to close this chat?')) return;

            try {
                const res = await fetch(`/admin/api/chat/${activeSessionId}/close`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (res.ok) {
                    activeSessionId = null;
                    document.getElementById('noChatSelected').style.display = 'flex';
                    document.getElementById('chatMain').style.display = 'none';
                    fetchSessions();
                }
            } catch (e) {
                console.error(e);
            }
        }

        function handleEnter(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        }

        // Polling loops
        setInterval(fetchSessions, 5000);
        setInterval(fetchMessages, 3000);

        // Initial fetch
        fetchSessions();
    </script>
@endsection