<?php

namespace App\Listeners;

use App\Events\UserLocationEvents\AddUserLocation;
use App\Events\UserLocationEvents\DeleteUserLocation;
use App\Events\UserLocationEvents\UpdateUserLocation;
use App\Notifications\PushNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserLocationEventSubscriber implements ShouldQueue
{
    /**
     * Handle add user location events.
     */
    public function handleAddUserLocation($event) {
        $event->user->notify(new PushNotification("Job Assign Alert", "A new work location has been assigned to you. Please check your location list"));
    }

    /**
     * Handle delete user location events.
     */
    public function handleDeleteUserLocation($event) {
        $event->data['user']->notify(new PushNotification("Job Delete Alert", "The " . $event->data['location_name'] . " job location has been removed from your assigned locations"));
    }

    /**
     * Handle update user location events.
     */
    public function handleUpdateUserLocation($event) {
        $event->data['user']->notify(new PushNotification("Job Update Alert","The " . $event->data['location_name'] . " location job has been updated. Please check the job again"));
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
            AddUserLocation::class => 'handleAddUserLocation',
            DeleteUserLocation::class => 'handleDeleteUserLocation',
            UpdateUserLocation::class => 'handleUpdateUserLocation',
        ];
    }
}
