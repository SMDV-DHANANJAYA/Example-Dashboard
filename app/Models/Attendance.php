<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_name',
        'date',
        'location_start_time',
        'location_end_time',
        'user_start_time',
    ];

    protected $casts = [
        'date' => 'datetime',
        'location_start_time' => 'datetime',
        'location_end_time' => 'datetime',
        'user_start_time' => 'datetime',
        'user_end_time' => 'datetime',
    ];

    public function getIsCompleteAttribute(){
        if($this->user_end_time != null){
            return true;
        }
        else{
            return false;
        }
    }

    public function timeCount($start_time,$end_time){
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $interval = $start->diff($end);
        $temp = explode(":",$interval->format("%H:%i"));
        return sprintf('%02d (H) : %02d (M)', $temp[0], $temp[1]);
    }

    public function getLocationTimeCountAttribute(){
        return $this->timeCount($this->location_start_time,$this->location_end_time);
    }

    public function getUserTimeCountAttribute(){
        return $this->timeCount($this->user_start_time,$this->user_end_time);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }
}
