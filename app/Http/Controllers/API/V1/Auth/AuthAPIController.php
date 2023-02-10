<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\Devices;
use App\Models\User;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Traits\GetUserByAccessToken;
use Illuminate\Support\Facades\Validator;

class AuthAPIController extends Controller
{
    use ApiResponseHelpers;
    use GetUserByAccessToken;

    /**
     * Login User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginUser(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'fcm_token' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->respondError();
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password, 'state' => User::ACTIVE, 'type' => User::USER])) {
            return $this->respondError("Unauthorized");
        }

        $user = \auth()->user();

        try{
            $access_token = $user->createToken($request->email,['user'])->plainTextToken;

            $device = new Devices();
            $device->user_id = $user->id;
            $device->fcm_token = $request->fcm_token;
            $device->access_token = $access_token;
            $device->save();

            $user->login_state = User::LOGIN;
            $user->save();

            return $this->respondWithSuccess(['data' => $user, 'access_token' => $access_token]);
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError("error");
        }
    }

    /**
     * Login Admin
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginAdmin(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'fcm_token' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->respondError();
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password, 'state' => User::ACTIVE])) {
            return $this->respondError("Unauthorized");
        }
        else{
            if(Auth::user()->type == User::USER){
                Auth::logout();
                return $this->respondError("Unauthorized");
            }
        }

        $user = \auth()->user();

        try{
            $access_token = $user->createToken($request->email,['*'])->plainTextToken;

            $device = new Devices();
            $device->user_id = $user->id;
            $device->fcm_token = $request->fcm_token;
            $device->access_token = $access_token;
            $device->save();

            return $this->respondWithSuccess(['data' => $user, 'access_token' => $access_token]);
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError("error");
        }
    }

    /**
     * Log out user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request){
        try{
            $user = $request->user();
            $user->currentAccessToken()->delete();
            $user->login_state = User::LOGOUT;
            $user->save();
            Devices::where('access_token',$request->bearerToken())->delete();
            return $this->respondWithSuccess(["data" => "Logged out successfully","state" => true]);
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError("error");
        }
    }

    /**
     * Change user fcm token
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeFCMToken(Request $request){
        try{
            $user = self::getUserByToken();
            Devices::where('access_token',$request->bearerToken())->where('user_id',$user->id)->update([
                'fcm_token' => $request->fcm_token,
            ]);
            return $this->respondWithSuccess(["data" => "Change fcm token successfully","state" => true]);
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError("error");
        }
    }
}
