<?php

namespace App\Models;

use App\Traits\CheckDate;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserLocations extends Model
{
    use HasFactory;
    use CheckDate;

    const NOTSTART = 0;
    const START = 1;
    const END = 2;

    const ONETIME = 1;
    const CUSTOMDAYS = 2;
    const EVERYDAY = 3;

    protected $fillable = [
        'user_id',
        'location_id',
        'day',
        'start_time',
        'end_time',
        'area',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'state',
        'type',
        'created_at',
        'updated_at',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'area' => 'string',
        'id' => 'string',
        'user_id' => 'string',
        'location_id' => 'string',
        'attendance_id' => 'string',
    ];

    protected $appends = [
        'work_start_time',
        'work_end_time',
        'job_status'
    ];

    public function getJobStatusAttribute(){
        if($this->checkDate($this) && $this->attendance_id == null){
            if(Carbon::createFromTimeString($this->start_time)->isPast()){
                return "late";
            }
        }
        return "default";
    }

    public function getWorkStartTimeAttribute(){
        return date_format(new DateTime($this->start_time),'h:i A');
    }

    public function getWorkEndTimeAttribute(){
        return date_format(new DateTime($this->end_time),'h:i A');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }
}
