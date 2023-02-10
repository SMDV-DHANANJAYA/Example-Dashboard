<?php

namespace App\Http\Controllers\API\V1;

use App\Events\NotificationProcess;
use App\Events\UserEvents\UserEndWork;
use App\Events\UserEvents\UserStartWork;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserLocations;
use App\Traits\CheckDate;
use App\Traits\GetUserByAccessToken;
use Carbon\Carbon;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersAPIController extends Controller
{

    use ApiResponseHelpers;
    use CheckDate;
    use GetUserByAccessToken;

    /**
     * Get User Data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(){
        try{
            $user = self::getUserByToken();
            if(($user->state == User::ACTIVE) && ($user != null)){
                return $this->respondWithSuccess(['data' => $user]);
            }
            return $this->respondNoContent();
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError();
        }
    }

    /**
     * Get User Locations
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocations(){
        try{
            $user = self::getUserByToken();
            $userLocations = UserLocations::where('user_id',$user->id)->where('state','<>',UserLocations::END)->with('location')->get();
            $userLocations = $userLocations->filter(function ($userLocation) {
                if($this->checkDate($userLocation) && ($userLocation->location->state == Location::ACTIVE)){
                    return $userLocation;
                }
            });
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

    /**
     * Update User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request){

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'birthday' => 'required|date_format:Y-m-d|before:today',
            'address' => 'required',
            'emergency_contact_number' => 'required',
            'emergency_contact_relationship' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondError();
        }

        try{
            $user = self::getUserByToken();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->birthday = $request->birthday;
            $user->address = $request->address;
            $user->emergency_contact_number = $request->emergency_contact_number;
            $user->emergency_contact_relationship = $request->emergency_contact_relationship;
            $user->save();
            return $this->respondWithSuccess(['data' => $user]);
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError();
        }
    }

    /**
     * Upload user image
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function uploadImage(Request $request){

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:2048',
            'type' => ['required', Rule::in(['photo_id','police_check','wwcc'])],
        ]);

        if ($validator->fails()) {
            return $this->respondError();
        }

        try{
            $user = self::getUserByToken();

            $storage = Storage::disk('public');
            $path = null;
            switch($request->type){
                case "photo_id":
                    $path = $user->photo_id_path;
                    break;
                case "police_check":
                    $path = $user->police_check_path;
                    break;
                case "wwcc":
                    $path = $user->wwcc_path;
                    break;
            }
            if($path != null && $storage->exists($path)){
                $storage->delete($path);
            }

            $extension = $request->file('image')->extension();
            $url = $request->file('image')->storeAs("user-documents/".$user->id,$user->id."_".$request->type.".".$extension);
            switch($request->type){
                case "photo_id":
                    $user->photo_id_path = $url;
                    break;
                case "police_check":
                    $user->police_check_path = $url;
                    break;
                case "wwcc":
                    $user->wwcc_path = $url;
                    break;
            }
            $user->save();

            return $this->respondWithSuccess(['data' => $user]);
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError();
        }
    }

    /**
     * Check work state
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkWorkState(Request $request){
        try{
            $userLocation = UserLocations::find($request->id);
            if($userLocation->state == UserLocations::START){
                return $this->respondWithSuccess(["data" => "started","state" => true]);
            }
            else{
                return $this->respondWithSuccess(["data" => "not started","state" => false]);
            }
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError();
        }
    }

    /**
     * Calculate distance
     * @param $latitudeFrom
     * @param $longitudeFrom
     * @param $latitudeTo
     * @param $longitudeTo
     * @param $earthRadius
     * @return float|int
     */
    function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    /**
     * Start work
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function startWorkLocation(Request $request){

        $validator = Validator::make($request->all(), [
            'user_location_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondError();
        }

        try{
            $userLocation = UserLocations::find($request->user_location_id);
            $location = $userLocation->location;

            $distance = $this->calculateDistance($request->latitude,$request->longitude,$location->latitude,$location->longitude);
            if($distance > $userLocation->area){
                return $this->respondFailedValidation("error");
            }

            $user = self::getUserByToken();

            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->location_name = $location->name;
            $attendance->date = Carbon::now()->format("Y-m-d");
            $attendance->location_start_time = $userLocation->start_time;
            $attendance->location_end_time = $userLocation->end_time;
            $attendance->user_start_time = Carbon::now()->format("H:i:s");
            $attendance->save();

            $userLocation->state = UserLocations::START;
            $userLocation->attendance_id = $attendance->id;
            $userLocation->save();

            $setting = Setting::where('name',Setting::START_WORK_NOTIFICATIONS)->first();
            if($setting->value){
                $data = array(
                    'setting' => $setting,
                    'user_name' => $user->full_name,
                    'location_name' => $location->name,
                );

                UserStartWork::dispatch($data);
            }

            NotificationProcess::dispatch(Notification::SUCCESS,$user->full_name . " started work at " . $location->name);

            return $this->respondWithSuccess(["data" => "Started successfully","state" => true]);
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError();
        }
    }

    /**
     * End work
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function endWorkLocation(Request $request){

        $validator = Validator::make($request->all(), [
            'user_location_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondError();
        }

        try{
            $userLocation = UserLocations::find($request->user_location_id);
            $location = $userLocation->location;

            $distance = $this->calculateDistance($request->latitude,$request->longitude,$location->latitude,$location->longitude);
            if($distance > $userLocation->area){
                return $this->respondFailedValidation("error");
            }

            $attendance = Attendance::find($userLocation->attendance_id);
            $attendance->user_end_time = Carbon::now()->format("H:i:s");
            $attendance->save();

            if($userLocation->type == UserLocations::ONETIME){
                $userLocation->delete();
            }
            else{
                $userLocation->state = UserLocations::END;
                $userLocation->save();
            }

            $user = self::getUserByToken();

            $setting = Setting::where('name',Setting::COMPLETED_WORK_NOTIFICATIONS)->first();
            if($setting->value){
                $data = array(
                    'setting' => $setting,
                    'user_name' => $user->full_name,
                    'location_name' => $location->name,
                );

                UserEndWork::dispatch($data);
            }

            NotificationProcess::dispatch(Notification::SUCCESS,$user->full_name . " completed work at " . $location->name);

            return $this->respondWithSuccess(["data" => "Completed successfully","state" => true]);
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError();
        }
    }
}
