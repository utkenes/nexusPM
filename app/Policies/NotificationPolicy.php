<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    /**
     * Determine whether the user can manage the notification.
     */
    public function notify(User $user, DatabaseNotification $notification): bool
    {
        return (string) $notification->notifiable_id === (string) $user->id;
    }
}
