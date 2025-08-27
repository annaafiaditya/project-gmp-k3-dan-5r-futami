<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('is_read')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();
        return view('notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        return back();
    }

    public function markRead($id)
    {
        $notif = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notif->is_read = true;
        $notif->save();
        return redirect($notif->link_url ?? route('findings.index'));
    }

    public function markUnread($id)
    {
        $notif = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notif->is_read = false;
        $notif->save();
        return back();
    }

    public function destroy($id)
    {
        $notif = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notif->delete();
        return back();
    }
}


