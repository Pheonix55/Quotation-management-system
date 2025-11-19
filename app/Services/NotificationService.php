<?php

namespace App\Services;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BaseNotification;

class NotificationService
{
    public static function push($type, $message, $user = null, $data = [])
    {
        $user = $user ?? Auth::user();

        // Store as Laravel Database notification
        Notification::send($user, new BaseNotification($type, $message, $data));

        // Broadcast (real-time)
        broadcast(new \App\Events\AppNotificationEvent($type, $message, $data, $user->id));

        // UI (flash message)
        // session()->flash($type, $message);

        return true;
    }

    public static function success($message, $user = null, $data = [])
    {
        return self::push('success', $message, $user, $data);
    }

    public static function error($message, $user = null, $data = [])
    {
        return self::push('error', $message, $user, $data);
    }
}
