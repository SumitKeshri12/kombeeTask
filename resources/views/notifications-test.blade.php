@extends('layouts.app')

@section('content')
    <div class="notification-container">
        <div class="notification-header">
            <h2>Realtime Notifications</h2>
            <span class="notification-badge" id="unread-count">0</span>
        </div>
        
        <form class="send-form" id="notification-form">
            @csrf
            <div style="margin-bottom: 10px;">
                <input type="text" id="message" name="message" placeholder="Enter notification message" class="form-control">
            </div>
            <div style="margin-bottom: 10px;">
                <select id="recipient" name="recipient" class="form-control select2">
                    <option value="">Send to all users</option>
                    @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Notification</button>
        </form>

        <div class="notification-list" id="notifications">
            <!-- Notifications will be displayed here -->
        </div>
    </div>
@endsection

@push('styles')
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
</style>
@endpush

@push('scripts')
<script>
    // Wait for jQuery and Echo to be loaded
    window.addEventListener('load', function() {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded');
            return;
        }

        // Initialize Select2
        $(document).ready(function() {
            if (typeof $.fn.select2 !== 'undefined') {
                $('#recipient').select2({
                    placeholder: 'Select recipient(s)',
                    allowClear: true
                });
            } else {
                console.error('Select2 is not loaded');
            }
        });

        // Function to generate a unique idempotency key
        function generateIdempotencyKey() {
            return `${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
        }

        // Current user ID and token
        const userId = @json(auth()->id());
        const token = @json(auth()->user()->createToken('notification-token')->accessToken);
        let unreadCount = 0;
        const seenNotifications = new Set(); // Track seen notification IDs
        let lastNotificationTime = localStorage.getItem('lastNotificationTime') || 0;

        // Common headers for API requests
        function getRequestHeaders() {
            return {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Idempotency-Key': generateIdempotencyKey()
            };
        }

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
            const notificationTime = new Date(notification.created_at || notification.time).getTime();
            
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
                    From: ${sender} • ${formatDate(notification.created_at || data.time)}
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
        function markAsRead(notificationId) {
            const element = document.querySelector(`[data-id="${notificationId}"]`);
            if (element && element.classList.contains('unread')) {
                element.classList.remove('unread');
                updateUnreadCount(false);
                
                // Send read status to server
                axios.post(`/api/notifications/${notificationId}/mark-as-read`, {}, {
                    headers: getRequestHeaders()
                }).catch(error => console.error('Error marking notification as read:', error));
            }
        }

        // Function to send notification
        document.getElementById('notification-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const messageInput = document.getElementById('message');
            const recipientInput = document.getElementById('recipient');
            
            const data = {
                message: messageInput.value,
                recipient_id: recipientInput.value || null
            };
            
            axios.post('/api/notifications/send', data, {
                headers: getRequestHeaders()
            })
            .then(response => {
                messageInput.value = '';
                if (typeof $.fn.select2 !== 'undefined') {
                    $('#recipient').val(null).trigger('change');
                } else {
                    recipientInput.value = '';
                }
                toastr.success('Notification sent successfully');
            })
            .catch(error => {
                console.error('Error sending notification:', error);
                toastr.error(error.response?.data?.error || 'Error sending notification. Please try again.');
            });
        });

        // Load existing notifications
        function loadNotifications() {
            axios.get('/api/notifications', {
                headers: getRequestHeaders()
            })
            .then(response => {
                const notifications = response.data.data || response.data || [];
                if (Array.isArray(notifications)) {
                    notifications.forEach(notification => addNotification(notification, false));
                }
            })
            .catch(error => console.error('Error loading notifications:', error));
        }

        // Initialize Echo for real-time notifications
        if (window.Echo) {
            Echo.private(`App.Models.User.${userId}`)
                .notification((notification) => {
                    addNotification(notification, true);
                    toastr.info(notification.data.message, 'New Notification');
                });
        } else {
            console.error('Laravel Echo is not initialized');
        }

        // Initialize
        loadNotifications();
    });
</script>
@endpush 