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
    public function send(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string',
                'user_id' => 'nullable|exists:users,id', // Optional: specific user to notify
                'all_users' => 'nullable|boolean'        // Optional: notify all users
            ]);

            $sender = Auth::user();
            
            if (!$sender) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $notification = new RealtimeNotification($request->message, $sender->id);

            if ($request->all_users) {
                // Send to all users except the sender
                User::where('id', '!=', $sender->id)->each(function ($user) use ($notification) {
                    $user->notify($notification);
                });
                return response()->json(['message' => 'Notification sent to all users']);
            }

            if ($request->user_id) {
                // Send to specific user
                $recipient = User::find($request->user_id);
                if ($recipient && $recipient->id !== $sender->id) {
                    $recipient->notify($notification);
                    return response()->json(['message' => 'Notification sent to user']);
                }
                return response()->json(['error' => 'Invalid recipient'], 400);
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
                'error' => 'Failed to send notification',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getNotifications()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            return response()->json([
                'notifications' => $user->notifications,
                'unread_count' => $user->unreadNotifications->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting notifications: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Failed to get notifications',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
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
                'error' => 'Failed to mark notification as read',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
