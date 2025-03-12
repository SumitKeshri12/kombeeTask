<!DOCTYPE html>
<html>
<head>
    <title>Realtime Notifications Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- jQuery -->
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Validation Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Other scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    <style>
        .notification-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .notification-badge {
            background-color: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8em;
        }
        .notification-list {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            max-height: 500px;
            overflow-y: auto;
        }
        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .notification-item:hover {
            background-color: #f5f5f5;
        }
        .notification-item .message {
            margin-bottom: 5px;
        }
        .notification-item .meta {
            font-size: 0.85em;
            color: #666;
        }
        .notification-item .sender {
            color: #2196F3;
        }
        .unread {
            background-color: #e8f4fd;
        }
        .send-form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        input[type="text"], select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .text-danger {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
            display: block;
        }
        .is-invalid {
            border-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="notification-container">
        <div class="notification-header">
            <h2>Realtime Notifications</h2>
            <span class="notification-badge" id="unread-count">0</span>
        </div>
        
        <form class="send-form" onsubmit="sendNotification(event)">
            <div style="margin-bottom: 10px;">
                <input type="text" id="message" name="message" placeholder="Enter notification message">
            </div>
            <div style="margin-bottom: 10px;">
                <select id="recipient" name="recipient">
                    <option value="">Send to all users</option>
                    @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Send Notification</button>
        </form>

        <div class="notification-list" id="notifications">
            <!-- Notifications will be displayed here -->
        </div>
    </div>

    <script>
        // Current user ID and token
        const userId = {{ $userId }};
        const token = "{{ auth()->user()->createToken('notification-token')->accessToken }}";
        let unreadCount = 0;
        const seenNotifications = new Set(); // Track seen notification IDs
        let lastNotificationTime = localStorage.getItem('lastNotificationTime') || 0;

        // Update unread count badge
        function updateUnreadCount(increment = true) {
            if (increment) {
                unreadCount++;
            } else {
                unreadCount = Math.max(0, unreadCount - 1);
            }
            document.getElementById('unread-count').textContent = unreadCount;
        }

        // Function to format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString();
        }

        // Function to add notification to the list
        function addNotification(notification, prepend = true) {
            const notificationId = notification.id;
            const notificationTime = new Date(notification.time || notification.data.time).getTime();
            
            // Skip if we've already seen this notification or if it's older than our last seen
            if (seenNotifications.has(notificationId) || notificationTime <= lastNotificationTime) {
                return;
            }
            seenNotifications.add(notificationId);
            
            // Update last notification time if this is newer
            if (notificationTime > lastNotificationTime) {
                lastNotificationTime = notificationTime;
                localStorage.setItem('lastNotificationTime', lastNotificationTime);
            }

            const container = document.getElementById('notifications');
            const element = document.createElement('div');
            element.className = 'notification-item unread';
            element.setAttribute('data-id', notificationId);
            element.onclick = () => markAsRead(notificationId);
            
            const data = notification.data || notification;
            const sender = data.sender ? `<span class="sender">${data.sender.name}</span>` : 'System';
            
            element.innerHTML = `
                <div class="message">
                    <strong>${data.message}</strong>
                </div>
                <div class="meta">
                    From: ${sender} • ${formatDate(data.time)}
                </div>
            `;
            
            if (prepend) {
                container.insertBefore(element, container.firstChild);
            } else {
                container.appendChild(element);
            }
            updateUnreadCount(true);
        }

        // Function to mark notification as read
        async function markAsRead(id) {
            console.log("Marking notification as read: ", id); // Debugging log
            try {
                await axiosInstance.post(`/api/notifications/${id}/mark-as-read`);
                const element = document.querySelector(`[data-id="${id}"]`);
                if (element && element.classList.contains('unread')) {
                    element.classList.remove('unread');
                    updateUnreadCount(false);
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }

        // Load existing notifications
        async function loadNotifications() {
            try {
                const response = await axiosInstance.get('/api/notifications');
                console.log('Loaded notifications:', response.data);
                unreadCount = 0;
                
                // Sort notifications by time, newest first
                const sortedNotifications = response.data.notifications.sort((a, b) => {
                    return new Date(b.data.time) - new Date(a.data.time);
                });
                
                sortedNotifications.forEach(notification => {
                    const notificationTime = new Date(notification.data.time).getTime();
                    
                    // Skip if we've already seen this notification or if it's older than our last seen
                    if (seenNotifications.has(notification.id) || notificationTime <= lastNotificationTime) {
                        return;
                    }
                    
                    const element = document.createElement('div');
                    element.className = `notification-item ${notification.read_at ? '' : 'unread'}`;
                    element.setAttribute('data-id', notification.id);
                    element.onclick = () => markAsRead(notification.id);
                    
                    // Add to seen notifications set
                    seenNotifications.add(notification.id);
                    
                    // Update last notification time if this is newer
                    if (notificationTime > lastNotificationTime) {
                        lastNotificationTime = notificationTime;
                        localStorage.setItem('lastNotificationTime', lastNotificationTime);
                    }
                    
                    const sender = notification.data.sender ? 
                        `<span class="sender">${notification.data.sender.name}</span>` : 'System';
                    
                    element.innerHTML = `
                        <div class="message">
                            <strong>${notification.data.message}</strong>
                        </div>
                        <div class="meta">
                            From: ${sender} • ${formatDate(notification.data.time)}
                        </div>
                    `;
                    
                    document.getElementById('notifications').appendChild(element);
                    if (!notification.read_at) {
                        updateUnreadCount(true);
                    }
                });
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        // Create axios instance with custom config
        const axiosInstance = axios.create({
            baseURL: '{{ url('/') }}',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        // Initialize Echo with Reverb
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config('broadcasting.connections.reverb.key') }}',
            wsHost: window.location.hostname,
            wsPort: {{ config('broadcasting.connections.reverb.port') }},
            cluster: 'mt1',
            forceTLS: false,
            encrypted: false,
            disableStats: true,
            enabledTransports: ['ws'],
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Authorization': `Bearer ${token}`
                }
            }
        });

        // Listen for notifications
        Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                // Skip if we've already seen this notification
                if (seenNotifications.has(notification.id)) {
                    return;
                }
                console.log('Received notification:', notification);
                addNotification(notification);
            });

        // Function to send notification
        async function sendNotification(event) {
            event.preventDefault();

            // Prevent multiple clicks
            const submitButton = $(event.target).find('button[type="submit"]');
            if (submitButton.prop('disabled')) return;

            submitButton.prop('disabled', true);

            const message = document.getElementById('message').value;
            const recipientId = document.getElementById('recipient').value;
            if (!message.trim()){
                submitButton.prop('disabled', false);
                return;
            }
            
            console.log('Sending notification...');
            try {
                const response = await axiosInstance.post('/api/notifications/send', { 
                    message,
                    user_id: recipientId || null,
                    all_users: !recipientId
                });
                console.log('Notification sent:', response.data);
                document.getElementById('message').value = '';
            } catch (error) {
                console.error('Error sending notification:', error.response || error);
                alert('Error sending notification. Check console for details.');
            } finally {
                submitButton.prop('disabled', false);
            }
        }

        // Load notifications when page loads
        loadNotifications();

        // Initialize form validation
        $(document).ready(function() {

            $('.send-form').validate({
                rules: {
                    message: {
                        required: true,
                        minlength: 2
                    }
                },
                messages: {
                    message: {
                        required: "Please enter a message",
                        minlength: "Message must be at least 2 characters long"
                    }
                },
                errorElement: 'span',
                errorClass: 'text-danger',
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form, event) {
                    event.preventDefault();

                    // Prevent multiple clicks
                    const submitButton = $(form).find('button[type="submit"]');
                    if (submitButton.prop('disabled')) return;

                    submitButton.prop('disabled', true);

                    sendNotification(event).finally(() => {
                        submitButton.prop('disabled', false);
                    });
                }
            });
        });
    </script>
</body>
</html> 