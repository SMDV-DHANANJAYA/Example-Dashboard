<?php

namespace App\Console\Commands;

use App\Events\NotificationProcess;
use App\Models\Location;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserLocations;
use App\Notifications\PushNotification;
use App\Notifications\TelegramNotification;
use App\Traits\CheckDate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendTaskDelay extends Command
{

    use CheckDate;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:delay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notification to admin about delay task';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $value = Setting::where('name',Setting::START_WORK_DELAY_NOTIFICATIONS)->first();

            $userLocations = UserLocations::where('user_locations.state',UserLocations::NOTSTART)
                ->where('users.state',User::ACTIVE)
                ->where('users.login_state',User::LOGIN)
                ->where('locations.state',Location::ACTIVE)
                ->whereTime('start_time', '<', Carbon::now()->format('H:i:s'))
                ->join('users', 'user_locations.user_id', '=', 'users.id')
                ->join('locations', 'user_locations.location_id', '=', 'locations.id')->get();

            $admins = User::where('type','<>',User::USER)->where('state',User::ACTIVE)->get();

            foreach ($userLocations as $userLocation){
                if($this->checkDate($userLocation)){
                    if(Carbon::createFromFormat("H:i",$userLocation->start_time->format("H:i"))->equalTo(Carbon::now()->subMinutes(10)->format("H:i"))){
                        if($value->push_notification_state){
                            foreach ($admins as $admin){
                                $admin->notify(new PushNotification("Delay Work Alert",$userLocation->user->full_name . " has not yet started work at " . $userLocation->location->name));
                            }
                        }
                        if($value->telegram_notification_state){
                            Notification::route('telegram',config('config.telegram-chat-id'))->notify(new TelegramNotification("Delay Work Alert", $userLocation->user->full_name . " has not yet started work at " . $userLocation->location->name));
                        }
                        NotificationProcess::dispatch(\App\Models\Notification::SUCCESS,$userLocation->user->full_name . " has not yet started work at " . $userLocation->location->name);
                    }
                }
            }
            return Command::SUCCESS;
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
