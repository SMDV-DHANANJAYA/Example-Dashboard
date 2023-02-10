<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const DE_ACTIVE = 0;
    const ACTIVE = 1;

    const LOGOUT = 0;
    const LOGIN = 1;

    const SUPER_ADMIN = 1;
    const ADMIN = 2;
    const USER = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'type',
        'state',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'state',
        'type',
        'email_verified_at',
        'created_at',
        'updated_at',
        'photo_id_path',
        'police_check_path',
        'wwcc_path',
        'login_state'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
        'birthday' => 'datetime:Y-m-d',
        'complete_step' => 'integer',
    ];

    protected $appends = [
        'full_name',
        'photo_id_url',
        'police_check_url',
        'wwcc_url',
        'complete_step'
    ];

    public function getFullNameAttribute(){
        return $this->first_name . ' '. $this->last_name;
    }

    /**
     * Check User profile complete steps
     * @return int
     */
    public function getCompleteStepAttribute(){
        $complete_step = 0;
        if($this->type == User::USER){
            if(($this->first_name != null) || ($this->last_name != null)){
                $complete_step = $complete_step + 10;
            }
            if($this->email != null){
                $complete_step = $complete_step + 10;
            }
            if($this->mobile != null){
                $complete_step = $complete_step + 10;
            }
            if($this->birthday != null){
                $complete_step = $complete_step + 10;
            }
            if($this->address != null){
                $complete_step = $complete_step + 10;
            }
            if($this->emergency_contact_number != null){
                $complete_step = $complete_step + 10;
            }
            if($this->emergency_contact_relationship != null){
                $complete_step = $complete_step + 10;
            }
            if($this->photo_id_path != null){
                $complete_step = $complete_step + 10;
            }
            if($this->police_check_path != null){
                $complete_step = $complete_step + 10;
            }
            if($this->wwcc_path != null){
                $complete_step = $complete_step + 10;
            }
        }
        return $complete_step;
    }

    public function getPhotoIdUrlAttribute(){
        if($this->photo_id_path == null){
            return null;
        }
        return asset('storage/'.$this->photo_id_path);
    }

    public function getPoliceCheckUrlAttribute(){
        if($this->police_check_path == null){
            return null;
        }
        return asset('storage/'.$this->police_check_path);
    }

    public function getWwccUrlAttribute(){
        if($this->wwcc_path == null){
            return null;
        }
        return asset('storage/'.$this->wwcc_path);
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public function locations(){
        return $this->belongsToMany(Location::class, 'user_locations');
    }

    public function userLocations(){
        return $this->hasMany(UserLocations::class);
    }

    public function devices(){
        return $this->hasMany(Devices::class);
    }

    /**
     * Get user working days
     * @param $value
     * @return string
     */
    public static function getWorkingDays($value){
        $values = explode("|",$value);
        $days = "";
        foreach ($values as $value){
            $days .= $value . " | ";
        }
        return substr($days, 0, -3);
    }

    public function routeNotificationForFcm()
    {
        return $this->devices()->pluck('fcm_token')->toArray();
    }
}
