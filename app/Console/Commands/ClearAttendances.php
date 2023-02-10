<?php

namespace App\Console\Commands;

use App\Events\NotificationProcess;
use App\Models\Attendance;
use App\Models\Setting;
use App\Notifications\TelegramNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ClearAttendances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Attendance Quarterly';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $value = Setting::where('name',Setting::CLEAR_ATTENDANCES_AUTO)->first();

        try{
            Attendance::where('created_at','<',Carbon::now()->subMonths(Carbon::MONTHS_PER_QUARTER))->delete();

            if($value->telegram_notification_state){
                Notification::route('telegram',config('config.telegram-chat-id'))->notify(new TelegramNotification("System Alert","Old attendance delete successfully (Quarterly Task)."));
            }
            NotificationProcess::dispatch(\App\Models\Notification::SUCCESS,"Old attendance delete successfully (Quarterly Task)");
            return Command::SUCCESS;
        }
        catch (\Throwable $e){
            Log::error($e->getMessage());
            if($value->telegram_notification_state){
                Notification::route('telegram',config('config.telegram-chat-id'))->notify(new TelegramNotification("System Alert","Old attendance delete failed (Quarterly Task)."));
            }
            NotificationProcess::dispatch(\App\Models\Notification::DANGER,"Old attendance delete failed (Quarterly Task)");
            return Command::FAILURE;
        }
    }
}
