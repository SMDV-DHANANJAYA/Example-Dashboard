<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\User;
use App\Models\UserLocations;
use App\Notifications\PushNotification;
use App\Traits\CheckDate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTaskReminder extends Command
{

    use CheckDate;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind users before 15 minutes about the task';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $userLocations = UserLocations::where('user_locations.state',UserLocations::NOTSTART)
                ->where('users.state',User::ACTIVE)
                ->where('users.login_state',User::LOGIN)
                ->where('locations.state',Location::ACTIVE)
                ->whereTime('start_time', '>', Carbon::now()->format('H:i:s'))
                ->join('users', 'user_locations.user_id', '=', 'users.id')
                ->join('locations', 'user_locations.location_id', '=', 'locations.id')->get();
            foreach ($userLocations as $userLocation){
                if($this->checkDate($userLocation)){
                    if(Carbon::createFromFormat("H:i",$userLocation->start_time->format("H:i"))->equalTo(Carbon::now()->addMinutes(15)->format("H:i"))){
                        $userLocation->user->notify(new PushNotification("Task Reminder","Your next task in " . $userLocation->location->name . " starts at " . $userLocation->start_time->format("h:i A")));
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
