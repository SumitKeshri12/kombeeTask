<?php

namespace App\Http\Controllers;

use App\Notifications\RealtimeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Models\User;

class NotificationController extends Controller
{
    const NOTIFICATION_TYPES = [
        'unauthorized_access' => 'User not authenticated',
        'invalid_recipient' => 'Invalid recipient',
        'failed_to_send' => 'Failed to send notification',
        'failed_to_get' => 'Failed to get notifications',
        'failed_to_mark' => 'Failed to mark notification as read',
    ];    
    
    public function send(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
                'recipient_id' => 'nullable|exists:users,id'
            ]);

            $sender = Auth::user();
            
            if (!$sender) {
                return response()->json(['error' => self::NOTIFICATION_TYPES['unauthorized_access']], 401);
            }

            $notification = new RealtimeNotification($request->message, $sender);

            if ($request->recipient_id) {
                // Send to specific user
                $recipient = User::find($request->recipient_id);
                if ($recipient && $recipient->id !== $sender->id) {
                    $recipient->notify($notification);
                    return response()->json(['message' => 'Notification sent to user']);
                }
                return response()->json(['error' => self::NOTIFICATION_TYPES['invalid_recipient']], 400);
            }

            // Default: Send to all users except sender
            User::where('id', '!=', $sender->id)->each(function ($user) use ($notification) {
                $user->notify($notification);
            });

            return response()->json(['message' => 'Notification sent successfully']);
        } catch (\Exception $e) {
            Log::error('Error sending notification: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
                'message' => $request->message ?? null
            ]);
            
            return response()->json([
                'error' => self::NOTIFICATION_TYPES['failed_to_send'],
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getNotifications()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => self::NOTIFICATION_TYPES['unauthorized_access']], 401);
            }

            $notifications = $user->notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->data['message'],
                    'sender' => [
                        'id' => $notification->data['sender']['id'],
                        'name' => $notification->data['sender']['name']
                    ],
                    'created_at' => $notification->created_at,
                    'read_at' => $notification->read_at
                ];
            });

            return response()->json([
                'data' => $notifications,
                'unread_count' => $user->unreadNotifications->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting notifications: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => self::NOTIFICATION_TYPES['failed_to_get'],
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => self::NOTIFICATION_TYPES['unauthorized_access']], 401);
            }

            $notification = $user->notifications()->findOrFail($id);
            $notification->markAsRead();

            return response()->json(['message' => 'Notification marked as read']);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id(),
                'notification_id' => $id
            ]);
            
            return response()->json([
                'error' => self::NOTIFICATION_TYPES['failed_to_mark'],
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
