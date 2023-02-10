<?php

namespace App\Listeners;

use App\Events\UserEvents\UserEndWork;
use App\Events\UserEvents\UserRegister;
use App\Events\UserEvents\UserStartWork;
use App\Mail\UserRegister as UserRegisterMail;
use App\Models\User;
use App\Notifications\PushNotification;
use App\Notifications\TelegramNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class UserEventSubscriber implements ShouldQueue
{

    /**
     * Handle user start work events.
     */
    public function handleUserStartWork($event) {
        $setting = $event->data['setting'];
        if ($setting->push_notification_state){
            $admins = User::where('type','<>',User::USER)->where('state',User::ACTIVE)->get();
            foreach ($admins as $admin){
                $admin->notify(new PushNotification("Start Work Alert",$event->data['user_name'] . " started work at " . $event->data['location_name']));
            }
        }

        if ($setting->telegram_notification_state){
            Notification::route('telegram',config('config.telegram-chat-id'))->notify(new TelegramNotification("Start Work Alert",$event->data['user_name'] . " started work at " . $event->data['location_name']));
        }
    }

    /**
     * Handle user end work events.
     */
    public function handleUserEndWork($event) {
        $setting = $event->data['setting'];
        if ($setting->push_notification_state){
            $admins = User::where('type','<>',User::USER)->where('state',User::ACTIVE)->get();
            foreach ($admins as $admin){
                $admin->notify(new PushNotification("Complete Work Alert",$event->data['user_name'] . " completed work at " . $event->data['location_name']));
            }
        }

        if ($setting->telegram_notification_state){
            Notification::route('telegram',config('config.telegram-chat-id'))->notify(new TelegramNotification("Complete Work Alert",$event->data['user_name'] . " completed work at " . $event->data['location_name']));
        }
    }

    /**
     * Handle user registration.
     */
    public function handleUserRegistration($event) {
        Mail::to($event->data['user']['email'])->queue(new UserRegisterMail($event->data['type'],$event->data['user']));
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
            UserStartWork::class => 'handleUserStartWork',
            UserEndWork::class => 'handleUserEndWork',
            UserRegister::class => 'handleUserRegistration',
        ];
    }
}
