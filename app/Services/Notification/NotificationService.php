<?php

namespace App\Services\Notification;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotificationCollection;

class NotificationService
{
    /**
     * Get all notifications for the user.
     */
    public function getNotifications(User $user): DatabaseNotificationCollection
    {
        return $user->notifications;
    }

    /**
     * Get unread notifications for the user.
     */
    public function getUnreadNotifications(User $user): DatabaseNotificationCollection
    {
        return $user->unreadNotifications;
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(User $user, string $notificationId): void
    {
        $notification = $user->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }
}
