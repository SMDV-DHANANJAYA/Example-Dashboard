<?php

namespace App\Listeners;

use App\Events\NotificationProcess;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NotificationProcess  $event
     * @return void
     */
    public function handle(NotificationProcess $event)
    {
        $notification = new Notification();
        $notification->text = $event->message;
        $notification->read_state = Notification::NOTIFICATION_NEW;
        $notification->state = $event->type;
        $notification->save();
    }
}
