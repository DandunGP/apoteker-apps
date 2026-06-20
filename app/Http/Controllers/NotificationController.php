<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Mark notification as read and redirect to its link.
     */
    public function readAndRedirect(Notification $notification)
    {
        // Safety check: ensure notification belongs to the logged-in user
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Mark as read if not already read
        if (is_null($notification->read_at)) {
            $notification->update([
                'read_at' => now(),
            ]);
        }

        $destination = $notification->link ?? '/dashboard';

        return redirect($destination);
    }

    /**
     * Display all notifications for the logged-in user.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }
}
