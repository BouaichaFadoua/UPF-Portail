<?php

namespace App\Http\Controllers;

use App\Models\NotificationUpf;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Polling endpoint – returns the latest 8 notifications + unread count.
     * Called every 30 seconds by JS.
     */
    public function poll()
    {
        $userId = auth()->id();

        $notifications = NotificationUpf::where('user_id', $userId)
            ->latest()
            ->take(8)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'titre'      => $n->titre,
                'message'    => $n->message,
                'lien'       => $n->lien,
                'type'       => $n->type,
                'lu'         => $n->lu,
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        $unread = NotificationUpf::where('user_id', $userId)->where('lu', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unread'        => $unread,
        ]);
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllRead()
    {
        NotificationUpf::where('user_id', auth()->id())->where('lu', false)->update(['lu' => true]);
        return response()->json(['ok' => true]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(NotificationUpf $notification)
    {
        if ($notification->user_id !== auth()->id()) abort(403);
        $notification->update(['lu' => true]);
        return response()->json(['ok' => true]);
    }
}
