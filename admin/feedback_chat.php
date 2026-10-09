<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Chat | Barangay Maruing</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../public/image/Maruing Logo 2.png">
    
    <!-- Google Fonts: Montserrat & Dancing Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../public/assets/css/style.css">
    
    <link rel="stylesheet" href="Admin CSS/admin.css?v=2">
    <style>
        /* Feedback Chat Specific Styles */
        .chat-container {
            display: flex;
            height: calc(100vh - 150px);
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        .chat-sidebar {
            width: 300px;
            border-right: 1px solid #e5e7eb;
            background-color: #f9fafb;
            display: flex;
            flex-direction: column;
        }
        .chat-sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            background-color: #fff;
        }
        .chat-sidebar-header h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #1f2937;
        }
        .chat-list {
            flex: 1;
            overflow-y: auto;
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .chat-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .chat-item:hover, .chat-item.active {
            background-color: #f3f4f6;
        }
        .chat-item h4 {
            margin: 0 0 5px 0;
            font-size: 0.95rem;
            color: #1f2937;
            display: flex;
            justify-content: space-between;
        }
        .chat-item h4 span {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: normal;
        }
        .chat-item p {
            margin: 0;
            font-size: 0.85rem;
            color: #6b7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chat-header h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #1f2937;
        }
        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f9fafb;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.95rem;
            line-height: 1.4;
        }
        .message.received {
            align-self: flex-start;
            background-color: #fff;
            border: 1px solid #e5e7eb;
            color: #1f2937;
            border-bottom-left-radius: 0;
        }
        .message.sent {
            align-self: flex-end;
            background-color: #137547;
            color: #fff;
            border-bottom-right-radius: 0;
        }
        .message-time {
            font-size: 0.7rem;
            margin-top: 5px;
            display: block;
            opacity: 0.8;
        }
        .chat-input {
            padding: 20px;
            background-color: #fff;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 10px;
        }
        .chat-input input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-family: inherit;
        }
        .chat-input input:focus {
            border-color: #137547;
        }
        .chat-input button {
            padding: 12px 24px;
            background-color: #137547;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .chat-input button:hover {
            background-color: #0f5c38;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../public/image/Maruing Logo 2.png" alt="Barangay Maruing Logo">
            <h3>Admin Panel</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a></li>
            <li><a href="about.php"><i class="bi bi-info-circle-fill"></i> About</a></li>
            <li class="has-submenu">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-people-fill"></i> Officials
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="barangayofficials.php">Barangay Officials</a></li>
                    <li><a href="SKofficials.php">SK Officials</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-geo-alt-fill"></i> Spot Map
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="legend.php">Legend</a></li>
                    <li><a href="purok 1.php">Purok 1</a></li>
                    <li><a href="purok 2.php">Purok 2</a></li>
                    <li><a href="purok 3.php">Purok 3</a></li>
                    <li><a href="purok 4.php">Purok 4</a></li>
                    <li><a href="purok 5.php">Purok 5</a></li>
                    <li><a href="purok 6.php">Purok 6</a></li>
                    <li><a href="purok 7.php">Purok 7</a></li>
                    <li><a href="purok 8.php">Purok 8</a></li>
                    <li><a href="purok 9.php">Purok 9</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="bi bi-house-door-fill"></i> Households
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu">
                    <li><a href="legends file.php">Legends File</a></li>
                    <li><a href="resident 1.php">Resident 1</a></li>
                    <li><a href="resident 2.php">Resident 2</a></li>
                    <li><a href="resident 3.php">Resident 3</a></li>
                    <li><a href="resident 4.php">Resident 4</a></li>
                    <li><a href="resident 5.php">Resident 5</a></li>
                    <li><a href="resident 6.php">Resident 6</a></li>
                    <li><a href="resident 7.php">Resident 7</a></li>
                    <li><a href="resident 8.php">Resident 8</a></li>
                    <li><a href="resident 9.php">Resident 9</a></li>
                </ul>
            </li>
            <li><a href="mediagallery.php"><i class="bi bi-image-fill"></i> Media Gallery</a></li>
            <li><a href="services.php"><i class="bi bi-card-list"></i> Services</a></li>
            <li><a href="announcements.php"><i class="bi bi-megaphone-fill"></i> Announcements</a></li>
            <li><a href="#"><i class="bi bi-telephone-fill"></i> Contact</a></li>
            <li><a href="feedback_chat.php" class="active"><i class="bi bi-chat-dots-fill"></i> Feedback chat</a></li>
            <li><a href="settings.php"><i class="bi bi-gear-fill"></i> Settings</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="login.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-title">
                <h2>Feedback Chat</h2>
            </div>
            <div class="topbar-user">
                <div class="user-info">
                    <span class="user-name">System Admin</span>
                    <span class="user-role">Administrator</span>
                </div>
                <div class="user-avatar">
                    A
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="dashboard-content">
            <div class="chat-container">
                <!-- Chat Sidebar -->
                <div class="chat-sidebar">
                    <div class="chat-sidebar-header">
                        <h3>Conversations</h3>
                    </div>
                    <ul class="chat-list" id="admin-thread-list">
                        <!-- Threads loaded dynamically via AJAX -->
                        <li style="padding: 15px; text-align: center; color: #6b7280;">Loading...</li>
                    </ul>
                </div>
                
                <!-- Chat Main -->
                <div class="chat-main" id="admin-chat-main" style="display: none;">
                    <div class="chat-header">
                        <h3 id="admin-chat-title">Select a conversation</h3>
                    </div>
                    <div class="chat-messages" id="admin-chat-messages">
                        <!-- Messages loaded dynamically via AJAX -->
                    </div>
                    <div class="chat-input">
                        <input type="text" id="admin-chat-input" placeholder="Type your reply here...">
                        <button type="button" id="admin-chat-send-btn"><i class="bi bi-send-fill"></i> Send</button>
                    </div>
                </div>
                <div class="chat-main" id="admin-chat-placeholder" style="justify-content: center; align-items: center;">
                    <div style="text-align: center; color: #6b7280;">
                        <i class="bi bi-chat-dots" style="font-size: 3rem; margin-bottom: 10px; display: block;"></i>
                        <p>Select a conversation from the sidebar to start chatting</p>
                    </div>
                </div>
            </div>
            
            <script>
            let currentThreadId = null;
            let currentThreadName = null;

            document.addEventListener('DOMContentLoaded', () => {
                fetchThreads();
                setInterval(fetchThreads, 5000); // Poll threads every 5s

                document.getElementById('admin-chat-send-btn').addEventListener('click', () => {
                    const input = document.getElementById('admin-chat-input');
                    if (input.value.trim() !== '' && currentThreadId) {
                        sendAdminMessage(input.value.trim(), () => {
                            input.value = '';
                            fetchMessages(currentThreadId);
                        });
                    }
                });
                
                document.getElementById('admin-chat-input').addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('admin-chat-send-btn').click();
                    }
                });
            });

            function fetchThreads() {
                fetch('../api/admin_fetch_threads.php')
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const list = document.getElementById('admin-thread-list');
                            list.innerHTML = '';
                            data.threads.forEach(thread => {
                                const li = document.createElement('li');
                                li.className = 'chat-item' + (thread.thread_id === currentThreadId ? ' active' : '');
                                li.innerHTML = `
                                    <h4>${thread.sender_name} <span>${thread.created_at}</span></h4>
                                    <p>${thread.sender_type === 'admin' ? 'You: ' : ''}${thread.last_message}</p>
                                `;
                                li.onclick = () => {
                                    currentThreadId = thread.thread_id;
                                    currentThreadName = thread.sender_name;
                                    document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('active'));
                                    li.classList.add('active');
                                    
                                    document.getElementById('admin-chat-placeholder').style.display = 'none';
                                    document.getElementById('admin-chat-main').style.display = 'flex';
                                    document.getElementById('admin-chat-title').innerText = thread.sender_name;
                                    
                                    fetchMessages(currentThreadId);
                                    // Start fast polling for this thread
                                    if (window.chatInterval) clearInterval(window.chatInterval);
                                    window.chatInterval = setInterval(() => {
                                        if(currentThreadId) fetchMessages(currentThreadId);
                                    }, 3000);
                                };
                                list.appendChild(li);
                            });
                            if (data.threads.length === 0) {
                                list.innerHTML = '<li style="padding: 15px; text-align: center; color: #6b7280;">No active conversations</li>';
                            }
                        }
                    });
            }

            function fetchMessages(threadId) {
                fetch(`../api/chat_fetch.php?thread_id=${threadId}&reader_type=admin`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const container = document.getElementById('admin-chat-messages');
                            container.innerHTML = '';
                            data.messages.forEach(msg => {
                                const isAdmin = msg.sender_type === 'admin';
                                const msgDiv = document.createElement('div');
                                msgDiv.className = 'message ' + (isAdmin ? 'sent' : 'received');
                                msgDiv.innerHTML = `${msg.message}<span class="message-time">${msg.created_at}</span>`;
                                container.appendChild(msgDiv);
                            });
                            container.scrollTop = container.scrollHeight;
                        }
                    });
            }

            function sendAdminMessage(message, callback) {
                const formData = new URLSearchParams();
                formData.append('thread_id', currentThreadId);
                formData.append('sender_name', 'Admin');
                formData.append('sender_type', 'admin');
                formData.append('message', message);

                fetch('../api/chat_send.php', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json()).then(res => {
                    if (res.status === 'success' && callback) callback();
                });
            }
            </script>
        </main>
    </div>
</body>
</html>
