<?php
namespace App\Traits;

use App\Models\Devices;

trait GetUserByAccessToken {

    /**
     * Get user from access token
     * @return mixed
     */
    public static function getUserByToken(){
        $device =  Devices::where('access_token',request()->bearerToken())->first();
        return $device->user;
    }
}
