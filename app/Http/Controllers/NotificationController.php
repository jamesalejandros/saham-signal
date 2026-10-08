<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->get();

        return view(
            'notifications.index',
            compact('notifications')
        );
    }

    public function open(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        $signalId = $notification->data['signal_id'] ?? null;

        if (!$signalId) {
            return redirect()
                ->route('notifications.index')
                ->with('error', 'Signal pada notification tidak ditemukan.');
        }

        return redirect()->route(
            'signals.show',
            $signalId
        );
    }

    public function read(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        return back();
    }
}
