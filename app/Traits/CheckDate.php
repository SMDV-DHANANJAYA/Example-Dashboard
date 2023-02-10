<?php
namespace App\Traits;

use App\Models\UserLocations;
use Carbon\Carbon;
use Illuminate\Support\Str;

trait CheckDate {

    /**
     * Check Date
     * @param $userLocation
     * @return bool|void
     */
    function checkDate($userLocation){
        switch ($userLocation->type){
            case UserLocations::ONETIME:
                if(Carbon::createFromFormat("Y-m-d",$userLocation->date)->isSameDay()){
                    return true;
                }
                return false;
            case UserLocations::CUSTOMDAYS:
                if(Str::contains($userLocation->date,Str::substr(Carbon::today()->dayName,0,3))){
                    return true;
                }
                return false;
            case UserLocations::EVERYDAY:
                return true;
        }
    }
}
