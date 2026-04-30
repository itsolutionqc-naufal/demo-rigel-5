<?php

namespace App\View\Composers;

use App\Models\Notification;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (Auth::check()) {
            $unreadNotificationsCount = Notification::where('user_id', Auth::id())
                ->whereNull('read_at')
                ->count();
                
            $view->with('unreadNotificationsCount', $unreadNotificationsCount);
        } else {
            $view->with('unreadNotificationsCount', 0);
        }
    }
}