<?php

namespace App\Listeners;

use App\Events\LocationEvents\UpdateLocation;
use App\Notifications\PushNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LocationEventSubscriber implements ShouldQueue
{
    /**
     * Handle location update events.
     */
    public function handleLocationUpdate($event) {
        $event->data['user']->notify(new PushNotification("Location Update Alert", "The " . $event->data['location_name'] . " job location has been updated. Please check the location again"));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {
        return [
            UpdateLocation::class => 'handleLocationUpdate',
        ];
    }
}
