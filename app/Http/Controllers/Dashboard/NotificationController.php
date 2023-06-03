<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function notification()
    {
        $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->paginate(15);
        $unread = Auth::user()->unreadNotifications->count();

        return view('dashboard.notification')->with([
            'notifications' => $notifications,
            'unread' => $unread,
        ]);
    }

    public function notificationRead()
    {
        $id = request()->get('go');
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if (empty($id) || empty($notification)) {
            return redirect()->route('dashboard.index')->withError('Notifikasi tidak ditemukan');
        }
        $notification->markAsRead();

        return redirect($notification->data['url']);
    }

    public function markAsRead()
    {
        $user = Auth::user();
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        return redirect()->back();
    }
}
