<?php

namespace App\Console\Commands;

use App\Models\UserLocations;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearCompletedTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set all user location state as 0 for next day and delete completed one day task';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $userLocations = UserLocations::where('type',UserLocations::ONETIME)->get();
            foreach ($userLocations as $userLocation){
                if(Carbon::createFromFormat("Y-m-d",$userLocation->date)->isBefore(Carbon::today())){
                    $userLocation->delete();
                }
            }
            UserLocations::query()->update([
                'state' => UserLocations::NOTSTART,
                'attendance_id' => null,
            ]);
            return Command::SUCCESS;
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
