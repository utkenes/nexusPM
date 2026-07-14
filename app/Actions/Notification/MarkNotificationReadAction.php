<?php

namespace App\Actions\Notification;

use App\Models\User;
use App\Services\Notification\NotificationService;

class MarkNotificationReadAction
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    /**
     * Execute the action to mark a notification as read.
     */
    public function execute(User $user, string $notificationId): void
    {
        $this->notificationService->markAsRead($user, $notificationId);
    }
}
