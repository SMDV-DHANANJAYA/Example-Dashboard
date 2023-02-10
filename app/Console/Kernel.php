<?php

namespace App\Console;

use App\Events\NotificationProcess;
use App\Models\Setting;
use App\Notifications\TelegramNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();
        $schedule->command('queue:prune-failed')->daily()->runInBackground();
        $schedule->command('queue:retry all')->everyMinute()->runInBackground()->withoutOverlapping();

        $schedule->command('telescope:prune')->daily()->runInBackground();

        $schedule->command('attendance:clear')->quarterly()->runInBackground()->when(function () {
            $value = Setting::where('name',Setting::CLEAR_ATTENDANCES_AUTO)->first();
            if($value->value){
                return true;
            }
            else{
                return false;
            }
        });

        $schedule->command('notifications:clear')->daily()->runInBackground()->when(function () {
            $value = Setting::where('name',Setting::CLEAR_NOTIFICATIONS_AUTO)->first();
            if($value->value){
                return true;
            }
            else{
                return false;
            }
        });

        $schedule->command('log:delete')->monthly()->runInBackground();

        $schedule->command('backup:clean')->daily()->at('01:00')->after(function () {
            Artisan::call('backup:run --only-db');
        })->runInBackground()
            ->onSuccess(function () {
                $value = Setting::where('name',Setting::BACKUP_DATABASE_AUTO)->first();
                if($value->telegram_notification_state){
                    Notification::route('telegram',config('config.telegram-chat-id'))
                        ->notify(new TelegramNotification("System Alert","Database backup successfully (Daily Task)."));
                }
                NotificationProcess::dispatch(\App\Models\Notification::SUCCESS,"Database backup successfully (Daily Task)");
            })
            ->onFailure(function () {
                $value = Setting::where('name',Setting::BACKUP_DATABASE_AUTO)->first();
                if($value->telegram_notification_state){
                    Notification::route('telegram',config('config.telegram-chat-id'))
                        ->notify(new TelegramNotification("System Alert","Database backup failed (Daily Task)."));
                }
                NotificationProcess::dispatch(\App\Models\Notification::DANGER,"Database backup failed (Daily Task)");
            })
            ->when(function () {
                $value = Setting::where('name',Setting::BACKUP_DATABASE_AUTO)->first();
                if($value->value){
                    return true;
                }
                else{
                    return false;
                }
            });

        $schedule->command('task:delay')->everyMinute()->runInBackground()->withoutOverlapping()->when(function () {
            $value = Setting::where('name',Setting::START_WORK_DELAY_NOTIFICATIONS)->first();
            if($value->value){
                return true;
            }
            else{
                return false;
            }
        });

        $schedule->command('task:reminder')->everyMinute()->runInBackground()->withoutOverlapping()->when(function () {
            $value = Setting::where('name',Setting::TASK_REMINDER_NOTIFICATIONS)->first();
            if($value->value){
                return true;
            }
            else{
                return false;
            }
        });

        $schedule->command('task:clear')->daily()->runInBackground();

        $schedule->command('payroll_report:send')->monthly()->runInBackground()->when(function () {
            $value = Setting::where('name',Setting::SEND_PAYROLL_REPORTS_AUTO)->first();
            if($value->value){
                return true;
            }
            else{
                return false;
            }
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
