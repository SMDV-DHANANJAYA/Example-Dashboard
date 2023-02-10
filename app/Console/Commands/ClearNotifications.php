<?php

namespace App\Console\Commands;

use App\Events\NotificationProcess;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as Telegram;
use App\Notifications\TelegramNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ClearNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Notifications Daily';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $value = Setting::where('name',Setting::CLEAR_NOTIFICATIONS_AUTO)->first();

        try{
            Notification::where('read_state',Notification::NOTIFICATION_READ)->where('created_at','<',Carbon::now()->subDay())->delete();

            if($value->telegram_notification_state){
                Telegram::route('telegram',config('config.telegram-chat-id'))->notify(new TelegramNotification("System Alert","Old notifications delete successfully (Daily Task)."));
            }

            NotificationProcess::dispatch(Notification::SUCCESS,"Old notifications delete successfully (Daily Task)");

            return Command::SUCCESS;
        }
        catch (\Throwable $e){
            Log::error($e->getMessage());
            if ($value->telegram_notification_state){
                Telegram::route('telegram',config('config.telegram-chat-id'))->notify(new TelegramNotification("System Alert","Old notifications delete failed (Daily Task)."));
            }
            NotificationProcess::dispatch(Notification::DANGER,"Old notifications delete failed (Daily Task)");
            return Command::FAILURE;
        }
    }
}
