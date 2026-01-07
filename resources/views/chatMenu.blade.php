@extends('layout.main')

@section('main_contents')
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                
                <style>
                    /* Scoped Variables */
                    .chat-app-wrapper {
                        --chat-bg: #ffffff;
                        --chat-area-bg: #f9fafb;
                        --chat-hover: #f3f4f6;
                        --chat-active: #e5e7eb;
                        --chat-border: #e5e7eb;
                        --chat-text-main: #1f2937;
                        --chat-text-sub: #6b7280;
                        --chat-accent: #2563eb;
                        --chat-accent-hover: #1d4ed8;
                        --chat-danger: #ef4444;
                        --chat-bubble-sent: #2563eb;
                        --chat-bubble-received: #e5e7eb;
                    }

                    /* Main Container */
                    .chat-container {
                        width: 100%;
                        height: calc(100vh - 200px); 
                        min-height: 600px;
                        background: var(--chat-bg);
                        border-radius: 12px;
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                        border: 1px solid var(--chat-border);
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                        position: relative; 
                    }

                    /* --- VIEW 1: FULL WIDTH LIST (SIDEBAR) --- */
                    .chat-list-view {
                        width: 100%;
                        height: 100%;
                        display: flex;
                        flex-direction: column;
                        background: #fff;
                    }

                    .list-header {
                        padding: 20px 30px;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        border-bottom: 1px solid var(--chat-border);
                    }

                    .list-title {
                        font-size: 20px;
                        font-weight: 700;
                        color: var(--chat-text-main);
                    }

                    .search-box-wrapper {
                        padding: 20px 30px;
                        border-bottom: 1px solid var(--chat-border);
                        background-color: #fff;
                    }

                    .search-input {
                        width: 100%;
                        padding: 12px 20px;
                        border-radius: 8px;
                        border: 1px solid var(--chat-border);
                        background-color: var(--chat-hover);
                        outline: none;
                        font-size: 15px;
                    }
                    .search-input:focus { border-color: var(--chat-accent); background: #fff; }

                    .chat-list-items {
                        flex: 1;
                        overflow-y: auto;
                        list-style: none;
                        padding: 0;
                        margin: 0;
                    }

                    .chat-item {
                        display: flex;
                        align-items: center;
                        padding: 20px 30px; 
                        cursor: pointer;
                        border-bottom: 1px solid var(--chat-border);
                        transition: 0.2s;
                    }
                    .chat-item:hover { background-color: var(--chat-hover); }
                    
                    /* Avatars */
                    .avatar {
                        width: 55px; 
                        height: 55px;
                        border-radius: 50%;
                        background-color: #ddd;
                        margin-right: 20px;
                        background-size: cover;
                        background-position: center;
                        position: relative;
                        flex-shrink: 0;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: 600;
                        font-size: 20px;
                        color: white;
                        text-transform: uppercase;
                    }
                    .avatar.online::after {
                        content: ''; position: absolute; bottom: 2px; right: 2px;
                        width: 12px; height: 12px; background: #10b981;
                        border-radius: 50%; border: 2px solid #fff;
                    }

                    .user-info { flex: 1; min-width: 0; }
                    .user-header-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
                    .user-name { font-weight: 600; color: var(--chat-text-main); font-size: 16px; }
                    .user-last-msg { color: var(--chat-text-sub); font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 90%; }
                    .msg-time { font-size: 13px; color: var(--chat-text-sub); }
                    .badge-count {
                        background: var(--chat-danger); color: white;
                        font-size: 11px; padding: 2px 8px; border-radius: 10px; font-weight: bold;
                    }

                    /* --- NEW CHAT OVERLAY --- */
                    .new-chat-overlay {
                        position: absolute;
                        inset: 0;
                        background: rgba(17, 24, 39, 0.55);
                        display: none;
                        align-items: center;
                        justify-content: center;
                        z-index: 10;
                        padding: 20px;
                    }

                    .new-chat-card {
                        width: 100%;
                        max-width: 520px;
                        background: #fff;
                        border-radius: 14px;
                        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
                        padding: 22px;
                        border: 1px solid var(--chat-border);
                        display: flex;
                        flex-direction: column;
                        gap: 14px;
                    }

                    .new-chat-card h4 {
                        margin: 0;
                        color: var(--chat-text-main);
                        font-weight: 700;
                        font-size: 18px;
                    }

                    .field-label {
                        font-size: 13px;
                        color: var(--chat-text-sub);
                        margin-bottom: 6px;
                    }

                    .new-chat-input {
                        width: 100%;
                        padding: 12px 14px;
                        border-radius: 10px;
                        border: 1px solid var(--chat-border);
                        background: var(--chat-hover);
                        outline: none;
                        font-size: 14px;
                    }
                    .new-chat-input:focus { border-color: var(--chat-accent); background: #fff; }

                    .suggestions-list {
                        list-style: none;
                        margin: 6px 0 0;
                        padding: 0;
                        max-height: 180px;
                        overflow-y: auto;
                        border: 1px solid var(--chat-border);
                        border-radius: 10px;
                        background: #fff;
                    }

                    .suggestion-item {
                        padding: 10px 12px;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        cursor: pointer;
                        transition: background 0.15s ease;
                    }
                    .suggestion-item:hover { background: var(--chat-hover); }

                    .new-chat-actions {
                        display: flex;
                        justify-content: flex-end;
                        gap: 10px;
                        margin-top: 6px;
                    }

                    .btn-secondary, .btn-primary {
                        border: none;
                        border-radius: 10px;
                        padding: 10px 16px;
                        font-weight: 600;
                        cursor: pointer;
                        font-size: 14px;
                    }
                    .btn-secondary { background: var(--chat-hover); color: var(--chat-text-main); }
                    .btn-secondary:hover { background: #e5e7eb; }
                    .btn-primary { background: var(--chat-accent); color: #fff; }
                    .btn-primary:hover { background: var(--chat-accent-hover); }

                    /* --- VIEW 2: CHAT WINDOW (Initially Hidden) --- */
                    .chat-window-view {
                        width: 100%;
                        height: 100%;
                        display: none; /* Hidden by default */
                        flex-direction: column;
                        background: var(--chat-area-bg);
                    }

                    /* Window Header */
                    .chat-window-header {
                        padding: 15px 25px;
                        background: #fff;
                        border-bottom: 1px solid var(--chat-border);
                        display: flex;
                        align-items: center;
                    }

                    .back-btn {
                        background: none; border: none; cursor: pointer; color: var(--chat-text-main);
                        margin-right: 15px; padding: 5px; display: flex; align-items: center;
                        border-radius: 50%; transition: 0.2s;
                    }
                    .back-btn:hover { background-color: var(--chat-hover); }

                    .header-user { display: flex; align-items: center; flex: 1; }
                    .header-info { margin-left: 15px; }
                    .header-name { font-weight: 700; color: var(--chat-text-main); font-size: 16px; }
                    .header-status { font-size: 12px; color: #10b981; }

                    .header-actions button {
                        background: none; border: none; cursor: pointer; color: var(--chat-text-sub); margin-left: 15px;
                    }
                    .header-actions button:hover { color: var(--chat-accent); }

                    /* Messages Area */
                    .messages-content {
                        flex: 1;
                        padding: 30px;
                        overflow-y: auto;
                        display: flex;
                        flex-direction: column;
                        gap: 20px;
                    }

                    .message-bubble {
                        max-width: 60%;
                        padding: 15px 20px;
                        border-radius: 12px;
                        font-size: 15px;
                        line-height: 1.5;
                        position: relative;
                        word-wrap: break-word;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
                    }

                    .message-sent {
                        align-self: flex-end;
                        background-color: var(--chat-bubble-sent);
                        color: white;
                        border-bottom-right-radius: 2px;
                    }

                    .message-received {
                        align-self: flex-start;
                        background-color: #fff;
                        color: var(--chat-text-main);
                        border-bottom-left-radius: 2px;
                    }

                    .msg-timestamp {
                        font-size: 11px;
                        margin-top: 5px;
                        opacity: 0.7;
                        text-align: right;
                        display: block;
                    }

                    /* Input Area */
                    .chat-input-area {
                        padding: 20px 30px;
                        background: #fff;
                        border-top: 1px solid var(--chat-border);
                        display: flex;
                        align-items: center;
                        gap: 15px;
                    }

                    .attach-btn { background: none; border: none; cursor: pointer; color: var(--chat-text-sub); padding: 5px; }
                    .attach-btn:hover { color: var(--chat-text-main); }

                    .message-input {
                        flex: 1;
                        padding: 15px 20px;
                        border: 1px solid var(--chat-border);
                        border-radius: 30px;
                        outline: none;
                        font-size: 15px;
                        background: var(--chat-hover);
                    }
                    .message-input:focus { border-color: var(--chat-accent); background: #fff; }

                    .send-btn {
                        width: 45px; height: 45px;
                        background: var(--chat-accent);
                        color: white;
                        border: none;
                        border-radius: 50%;
                        cursor: pointer;
                        display: flex; align-items: center; justify-content: center;
                        transition: 0.2s;
                        box-shadow: 0 2px 5px rgba(37, 99, 235, 0.3);
                    }
                    .send-btn:hover { background: var(--chat-accent-hover); transform: translateY(-1px); }
                </style>

                <div class="chat-app-wrapper">
                    <div class="chat-container">
                        
                        <div class="chat-list-view" id="chatListView">
                            <div class="list-header">
                                <span class="list-title">All Conversations</span>
                                <button class="attach-btn" title="New Chat" onclick="toggleNewChat(true)">
                                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 5C10.5523 5 11 5.44772 11 6V9H14C14.5523 9 15 9.44772 15 10C15 10.5523 14.5523 11 14 11H11V14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14V11H6C5.44772 11 5 10.5523 5 10C5 9.44772 5.44772 9 6 9H9V6C9 5.44772 9.44772 5 10 5Z" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="search-box-wrapper">
                                <input id="chatSearchInput" type="text" class="search-input" placeholder="Search for messages or users...">
                            </div>

                            <ul class="chat-list-items">
                                @forelse($chats ?? [] as $chat)
                                <li class="chat-item" 
                                    data-name="{{ $chat['name'] }}" 
                                    data-avatar="{{ $chat['avatar'] }}" 
                                    data-online="{{ $chat['is_online'] ? '1' : '0' }}" 
                                    data-chat-id="{{ $chat['chat_id'] }}"
                                    data-user-id="{{ $chat['user_id'] ?? '' }}"
                                    onclick="openChatFromElement(this)">
                                    <div class="avatar {{ $chat['is_online'] ? 'online' : '' }}"></div>
                                    <div class="user-info">
                                        <div class="user-header-row">
                                            <span class="user-name">{{ $chat['name'] ?? 'Unknown' }}</span>
                                            @if(($chat['unread_count'] ?? 0) > 0)
                                                <div style="display: flex; gap: 10px; align-items: center;">
                                                    <span class="badge-count">{{ $chat['unread_count'] }}</span>
                                                    <span class="msg-time">{{ $chat['time'] ?? '' }}</span>
                                                </div>
                                            @else
                                                <span class="msg-time">{{ $chat['time'] ?? '' }}</span>
                                            @endif
                                        </div>
                                        <div class="user-last-msg" @if(($chat['unread_count'] ?? 0) > 0) style="font-weight: bold; color: var(--chat-text-main);" @endif>{{ $chat['last_message'] ?? 'No messages' }}</div>
                                    </div>
                                </li>
                                @empty
                                <li style="padding: 40px; text-align: center; color: var(--chat-text-sub);">
                                    <p>No conversations yet. Start chatting with someone!</p>
                                </li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="chat-window-view" id="chatWindowView">
                            <div class="chat-window-header">
                                <button class="back-btn" onclick="closeChat()">
                                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7071 5.29289C13.0976 5.68342 13.0976 6.31658 12.7071 6.70711L9.41421 10L12.7071 13.2929C13.0976 13.6834 13.0976 14.3166 12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L7.29289 10.7071C6.90237 10.3166 6.90237 9.68342 7.29289 9.29289L11.2929 5.29289C11.6834 4.90237 12.3166 4.90237 12.7071 5.29289Z" />
                                    </svg>
                                </button>
                                <div class="header-user">
                                    <div id="chatAvatar" class="avatar" style="width: 40px; height: 40px; margin-right: 15px;"></div>
                                    <div class="header-info">
                                        <div id="chatName" class="header-name">User</div>
                                        <div id="chatStatus" class="header-status">Online</div>
                                    </div>
                                </div>
                            </div>

                            <div class="messages-content">
                                </div>

                            <div class="chat-input-area">
                                <button class="attach-btn" title="Attach File">
                                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4C5.79086 4 4 5.79086 4 8V14C4 16.2091 5.79086 18 8 18C10.2091 18 12 16.2091 12 14V6C12 4.89543 11.1046 4 10 4C8.89543 4 8 4.89543 8 6V14C8 14.5523 8.44772 15 9 15C9.55228 15 10 14.5523 10 14V8C10 7.44772 10.4477 7 11 7C11.5523 7 12 7.44772 12 8V14C12 16.2091 10.2091 18 8 18C5.79086 18 4 16.2091 4 14V6C4 3.79086 5.79086 2 8 2C10.2091 2 12 3.79086 12 6V7C12 7.55228 11.5523 8 11 8C10.4477 8 10 7.55228 10 7V6C10 4.89543 9.10457 4 8 4Z" />
                                    </svg>
                                </button>
                                <input id="chatMessageInput" type="text" class="message-input" placeholder="Type your message...">
                                <button class="send-btn" onclick="sendCurrentMessage()">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.558V11a1 1 0 112 0v4.558a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                    </svg>
                                </button>
                            </div>

                        </div>

                        <div id="newChatOverlay" class="new-chat-overlay">
                            <div class="new-chat-card">
                                <h4>Start a new chat</h4>
                                <div>
                                    <div class="field-label">Search username</div>
                                    <input id="newChatUser" type="text" class="new-chat-input" placeholder="Type a username..." autocomplete="off" oninput="renderUserSuggestions(this.value)">
                                    <ul id="userSuggestions" class="suggestions-list" style="display:none;"></ul>
                                </div>
                                <div>
                                    <div class="field-label">First message</div>
                                    <input id="newChatMessage" type="text" class="new-chat-input" placeholder="Say hello...">
                                </div>
                                <div class="new-chat-actions">
                                    <button class="btn-secondary" onclick="toggleNewChat(false)">Cancel</button>
                                    <button class="btn-primary" onclick="startNewChat()">Start chat</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Safely serialize PHP data to JS
        const availableUsers = {!! json_encode(isset($users) ? $users : []) !!};  //ini bukan error ges emg gini aga cacad
        
        const startChatUrl = "{{ route('chat.start') }}";
        const messagesUrlTemplate = "{{ url('/chat') }}/:chatId/messages";
        const sendMessageUrlTemplate = "{{ url('/chat') }}/:chatId/message";
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Current user ID as a JS primitive (number or null)
        const currentUserId = {!! json_encode(optional(auth()->user())->id) !!};  //ini bukan error ges emg gini aga cacad

        function openChatFromElement(element) {
            const name = element.dataset.name;
            const imageUrl = element.dataset.avatar;
            const isOnline = element.dataset.online === '1';
            const chatId = element.dataset.chatId;
            
            openChat(name, imageUrl, isOnline, chatId);
        }
        
        function openChat(name, imageUrl, isOnline, chatId) {
            document.getElementById('chatListView').style.display = 'none';
            document.getElementById('chatWindowView').style.display = 'flex';

            document.getElementById('chatName').innerText = name;
            
            const avatar = document.getElementById('chatAvatar');
            setupAvatar(avatar, imageUrl, name);
            const statusText = document.getElementById('chatStatus');
            
            if(isOnline) {
                avatar.classList.add('online');
                statusText.innerText = 'Online';
                statusText.style.color = '#10b981';
            } else {
                avatar.classList.remove('online');
                statusText.innerText = 'Offline';
                statusText.style.color = '#6b7280';
            }
            
            document.getElementById('chatWindowView').setAttribute('data-chat-id', chatId);
            // Load messages, then clear unread UI for this chat on success
            loadMessages(chatId)
                .then(() => {
                    const li = document.querySelector(`.chat-item[data-chat-id="${chatId}"]`);
                    if (!li) return;
                    // Remove unread badge if present
                    const badge = li.querySelector('.badge-count');
                    if (badge) {
                        badge.remove();
                    }
                    // Normalize last message styling if it was bold for unread
                    const lastMsg = li.querySelector('.user-last-msg');
                    if (lastMsg) {
                        lastMsg.style.fontWeight = 'normal';
                        lastMsg.style.color = 'var(--chat-text-sub)';
                    }
                })
                .catch(() => { /* ignore, loadMessages already surfaced error */ });
        }

        function closeChat() {
            window.location.href = "{{ route('chat') }}";
        }

        function toggleNewChat(show) {
            const overlay = document.getElementById('newChatOverlay');
            overlay.style.display = show ? 'flex' : 'none';
            if (show) {
                const input = document.getElementById('newChatUser');
                input.focus();
                renderUserSuggestions(input.value);
            }
        }

        function renderUserSuggestions(query) {
            const list = document.getElementById('userSuggestions');
            const normalized = (query || '').toLowerCase();
            const matches = availableUsers
                .filter(u => ((u.name || '').toLowerCase().includes(normalized) || (u.username || '').toLowerCase().includes(normalized)))
                .slice(0, 8);

            list.innerHTML = '';

            if (!matches.length) {
                list.style.display = 'none';
                return;
            }

            matches.forEach(function(u) {
                const li = document.createElement('li');
                li.className = 'suggestion-item';
                li.dataset.userId = u.id;
                li.dataset.name = u.name || 'Unknown';
                li.dataset.avatar = assetPath(u.avatar || '');

                const avatarDiv = document.createElement('div');
                avatarDiv.className = 'avatar';
                avatarDiv.style.width = '32px';
                avatarDiv.style.height = '32px';
                avatarDiv.style.fontSize = '14px';
                setupAvatar(avatarDiv, li.dataset.avatar, li.dataset.name);

                const nameSpan = document.createElement('span');
                nameSpan.textContent = li.dataset.name;

                li.appendChild(avatarDiv);
                li.appendChild(nameSpan);

                li.addEventListener('click', function() {
                    selectSuggestedUser(li.dataset.userId, li.dataset.name, li.dataset.avatar);
                });

                list.appendChild(li);
            });

            list.style.display = 'block';
        }

        function selectSuggestedUser(userId, name, avatar) {
            const input = document.getElementById('newChatUser');
            input.value = name;
            input.setAttribute('data-user-id', userId);
            input.setAttribute('data-avatar', avatar || '');
            document.getElementById('userSuggestions').style.display = 'none';
        }

        function startNewChat() {
            const usernameInput = document.getElementById('newChatUser');
            const messageInput = document.getElementById('newChatMessage');
            const username = usernameInput.value.trim();
            const message = messageInput.value.trim();

            if (!username) {
                alert('Please enter a username to start chatting.');
                usernameInput.focus();
                return;
            }

            let selectedUserId = usernameInput.getAttribute('data-user-id');
            let avatar = usernameInput.getAttribute('data-avatar') || '';

            if (!selectedUserId) {
                const match = availableUsers.find(u => (u.name || '').toLowerCase() === username.toLowerCase());
                if (match) {
                    selectedUserId = match.id;
                    avatar = assetPath(match.avatar || '');
                    usernameInput.setAttribute('data-user-id', selectedUserId);
                    usernameInput.setAttribute('data-avatar', avatar);
                }
            }

            if (!selectedUserId) {
                alert('Please pick a valid user from the list.');
                return;
            }

            fetch(startChatUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    user_id: selectedUserId,
                    message: message,
                }),
            })
            .then(async (res) => {
                const data = await res.json();
                if (!res.ok || !data.success) {
                    throw new Error(data.message || 'Failed to start chat');
                }
                return data.chat;
            })
            .then((chat) => {
                const list = document.querySelector('.chat-list-items');
                const existing = list.querySelector(`[data-chat-id="${chat.chat_id}"]`);
                if (existing) {
                    existing.remove();
                }

                const li = document.createElement('li');
                li.className = 'chat-item';
                li.dataset.name = chat.name;
                li.dataset.avatar = assetPath(chat.avatar || '');
                li.dataset.online = chat.is_online ? '1' : '0';
                li.dataset.chatId = chat.chat_id;
                li.dataset.userId = chat.user_id;
                li.onclick = function() { openChatFromElement(li); };

                const avatarDiv = document.createElement('div');
                avatarDiv.className = 'avatar';
                if (chat.is_online) { avatarDiv.classList.add('online'); }
                setupAvatar(avatarDiv, li.dataset.avatar, chat.name);

                const userInfo = document.createElement('div');
                userInfo.className = 'user-info';

                const headerRow = document.createElement('div');
                headerRow.className = 'user-header-row';
                const nameSpan = document.createElement('span');
                nameSpan.className = 'user-name';
                nameSpan.textContent = chat.name;
                const timeSpan = document.createElement('span');
                timeSpan.className = 'msg-time';
                timeSpan.textContent = formatTime(chat.time) || 'Just now';
                headerRow.appendChild(nameSpan);
                headerRow.appendChild(timeSpan);

                const lastMsg = document.createElement('div');
                lastMsg.className = 'user-last-msg';
                lastMsg.textContent = chat.last_message || 'No messages yet';

                userInfo.appendChild(headerRow);
                userInfo.appendChild(lastMsg);

                // Build the list item contents before adding to the DOM
                li.appendChild(avatarDiv);
                li.appendChild(userInfo);

                list.prepend(li);

                openChat(chat.name, li.dataset.avatar, chat.is_online, chat.chat_id);
                toggleNewChat(false);

                usernameInput.value = '';
                usernameInput.removeAttribute('data-avatar');
                usernameInput.removeAttribute('data-user-id');
                messageInput.value = '';
                document.getElementById('userSuggestions').style.display = 'none';
            })
            .catch((err) => {
                alert(err.message || 'Unable to start chat');
            });
        }

        function loadMessages(chatId) {
            const messagesArea = document.querySelector('.messages-content');
            if (messagesArea) { messagesArea.innerHTML = '<p style="color:#6b7280;">Loading messages...</p>'; }
            const url = messagesUrlTemplate.replace(':chatId', chatId);
            return fetch(url, { headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Failed to load messages');
                    const msgs = data.messages || [];
                    renderMessages(msgs);
                    // Update chat list preview to the latest message regardless of sender
                    const last = msgs.length ? msgs[msgs.length - 1] : null;
                    if (last) updateChatPreview(chatId, last.content, last.sent_at);
                    return msgs;
                })
                .catch(err => {
                    if (messagesArea) {
                        messagesArea.innerHTML = `<p style="color:#ef4444;">${err.message}</p>`;
                    }
                    throw err;
                });
        }

        function renderMessages(messages) {
            const messagesArea = document.querySelector('.messages-content');
            if (!messagesArea) return;
            messagesArea.innerHTML = '';
            messages.forEach(m => {
                // Sender ID comparison now works correctly (Number vs Number)
                const isMe = m.sender_id === currentUserId;
                const bubble = document.createElement('div');
                bubble.className = 'message-bubble ' + (isMe ? 'message-sent' : 'message-received');
                bubble.innerHTML = `${escapeHtml(m.content)}<span class="msg-timestamp">${formatTime(m.sent_at)}</span>`;
                messagesArea.appendChild(bubble);
            });
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }

        function sendCurrentMessage() {
            const chatView = document.getElementById('chatWindowView');
            const chatId = chatView.getAttribute('data-chat-id');
            const input = document.getElementById('chatMessageInput');
            const content = input.value.trim();
            if (!chatId || !content) return;

            const url = sendMessageUrlTemplate.replace(':chatId', chatId);
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ content }),
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) throw new Error(data.message || 'Failed to send message');
                const m = data.message;
                const messagesArea = document.querySelector('.messages-content');
                if (messagesArea) {
                    const bubble = document.createElement('div');
                    bubble.className = 'message-bubble message-sent';
                    bubble.innerHTML = `${escapeHtml(m.content)}<span class="msg-timestamp">${formatTime(m.sent_at)}</span>`;
                    messagesArea.appendChild(bubble);
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                }
                // Update chat list preview and time, move item to top
                updateChatPreview(chatId, m.content, m.sent_at);
                input.value = '';
            })
            .catch(err => alert(err.message || 'Unable to send message'));
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text ?? '';
            return div.innerHTML;
        }

        function formatTime(dateString) {
            if (!dateString) return '';
            let d;
            try {
                // Prefer ISO 8601 with timezone; fallback for "YYYY-MM-DD HH:mm:ss"
                if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/.test(dateString)) {
                    // Treat backend naive timestamps as UTC to avoid local misinterpretation
                    d = new Date(dateString.replace(' ', 'T') + 'Z');
                } else {
                    d = new Date(dateString);
                }
            } catch (_) {
                return '';
            }
            if (isNaN(d.getTime())) return '';
            return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        function updateChatPreview(chatId, content, sentAt) {
            const li = document.querySelector(`.chat-item[data-chat-id="${chatId}"]`);
            if (!li) return;
            const lastEl = li.querySelector('.user-last-msg');
            if (lastEl) lastEl.textContent = content || 'No messages';
            const timeEl = li.querySelector('.msg-time');
            if (timeEl) timeEl.textContent = formatTime(sentAt) || 'Just now';
            const ul = li.parentElement;
            if (ul) { li.remove(); ul.prepend(li); }
        }

        function assetPath(path) {
            return path.startsWith('http') ? path : `{{ asset('') }}${path}`;
        }

        // Get initials from name (first letter of first two words)
        function getInitials(name) {
            if (!name) return '?';
            const words = name.trim().split(/\s+/);
            if (words.length === 1) return words[0].charAt(0).toUpperCase();
            return (words[0].charAt(0) + words[words.length - 1].charAt(0)).toUpperCase();
        }

        // Generate consistent color from name using hash
        function getColorForName(name) {
            const colors = [
                '#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8',
                '#F7DC6F', '#BB8FCE', '#85C1E2', '#F8B739', '#52B788',
                '#FF8551', '#6C5CE7', '#00B894', '#FDCB6E', '#E17055',
                '#A29BFE', '#00CEC9', '#FF7675', '#74B9FF', '#55EFC4'
            ];
            let hash = 0;
            for (let i = 0; i < (name || '').length; i++) {
                hash = name.charCodeAt(i) + ((hash << 5) - hash);
            }
            return colors[Math.abs(hash) % colors.length];
        }

        // Setup avatar: show image or fallback to initials
        function setupAvatar(avatarElement, imageUrl, name) {
            const img = new Image();
            img.onload = function() {
                avatarElement.style.backgroundImage = `url('${imageUrl}')`;
                avatarElement.textContent = '';
            };
            img.onerror = function() {
                // Fallback to initials
                avatarElement.style.backgroundImage = 'none';
                avatarElement.style.backgroundColor = getColorForName(name);
                avatarElement.textContent = getInitials(name);
            };
            // Check if URL is valid and not default placeholder
            if (imageUrl && imageUrl.trim() && !imageUrl.includes('pravatar.cc')) {
                img.src = imageUrl;
            } else {
                // No valid image, use initials immediately
                avatarElement.style.backgroundImage = 'none';
                avatarElement.style.backgroundColor = getColorForName(name);
                avatarElement.textContent = getInitials(name);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.chat-item').forEach(function(item) {
                const avatarUrl = item.dataset.avatar;
                const name = item.dataset.name;
                const avatarDiv = item.querySelector('.avatar');
                if(avatarDiv) {
                    setupAvatar(avatarDiv, avatarUrl, name);
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    toggleNewChat(false);
                }
            });

            const searchInput = document.getElementById('chatSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    filterChats(searchInput.value);
                });
            }

            const messageInput = document.getElementById('chatMessageInput');
            if (messageInput) {
                messageInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        sendCurrentMessage();
                    }
                });
            }
        });

        function filterChats(term) {
            const normalized = (term || '').toLowerCase();
            document.querySelectorAll('.chat-item').forEach(function(li) {
                const name = (li.dataset.name || '').toLowerCase();
                const lastMsg = (li.querySelector('.user-last-msg')?.textContent || '').toLowerCase();
                const match = name.includes(normalized) || lastMsg.includes(normalized);
                li.style.display = match ? '' : 'none';
            });
        }
    </script>
@endsection