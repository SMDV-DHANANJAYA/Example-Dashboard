<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommonAPIController extends Controller
{
    use ApiResponseHelpers;

    /**
     * Check Mobile App Version
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUpdate(Request $request){
        try{
            $temp = strtolower($request->type) == "android" ? Setting::ANDROID_VERSION : Setting::IOS_VERSION;
            $setting = Setting::where('name',$temp)->first();
            return $this->respondWithSuccess(['data' => $setting->value]);
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return $this->respondError();
        }
    }
}
