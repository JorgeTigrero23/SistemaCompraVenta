<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function get()
    {
        //return Notification::all();
        $unreadNotifications = Auth::user()->unreadNotifications;

        foreach ($unreadNotifications as $notification) {
            $now = date('Y-m-d');
            if($now != $notification->created_at->toDateString()){
                $notification->markAsRead();
            }
        }
        return Auth::user()->unreadNotifications;
    }
}
