<?php

namespace App\Http\Controllers;

use App\Actions\Notification\MarkNotificationReadAction;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
        protected MarkNotificationReadAction $markNotificationReadAction
    ) {}

    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $notifications = $this->notificationService->getNotifications($user);
        $unreadCount = $this->notificationService->getUnreadNotifications($user)->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $notification = DatabaseNotification::find($id);
        if (! $notification) {
            return response()->json(['error' => 'Notification not found.'], 404);
        }

        $this->authorize('notify', $notification);

        $this->markNotificationReadAction->execute($user, $id);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read for the user.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $this->notificationService->markAllAsRead($user);

        return response()->json(['success' => true]);
    }
}
