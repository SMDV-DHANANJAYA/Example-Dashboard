<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\User;
use App\Models\UserLocations;
use F9Web\ApiResponseHelpers;
use Illuminate\Support\Facades\Log;

class LocationsAPIController extends Controller
{
    use ApiResponseHelpers;

    /**
     * Get locations to admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function locations(){
        try{
            $locations = Location::where('state',Location::ACTIVE)->get();
            if(count($locations)){
                return $this->respondWithSuccess(['data' => $locations]);
            }
            else{
                return $this->respondNoContent();
            }
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError();
        }
    }

    /**
     * Get users related to a location
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function locationUsers($id){
        try{
            $userLocations = UserLocations::where('location_id',$id)->with('user')->get();
            if(count($userLocations)){
                return $this->respondWithSuccess(['data' => $userLocations]);
            }
            else{
                return $this->respondNoContent();
            }
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError();
        }
    }
}
