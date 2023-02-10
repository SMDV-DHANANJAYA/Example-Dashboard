<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    const DEACTIVE = 0;
    const ACTIVE = 1;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'address',
        'state',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'state',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'user_locations');
    }

    public function attendances(){
        return $this->hasMany(Attendance::class);
    }

    public function userLocations(){
        return $this->hasMany(UserLocations::class);
    }
}
