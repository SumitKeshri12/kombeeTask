<?php

namespace App\Helpers;

use App\Models\Notification;

class Notifications
{
    public static function getUnreadCount()
    {
        $user = auth()->user();
        return $user->unreadNotifications->count();
    }
}
