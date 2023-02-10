<?php

namespace App\Http\Controllers\DashboardController;

use App\Events\UserLocationEvents\AddUserLocation;
use App\Events\UserLocationEvents\DeleteUserLocation;
use App\Events\UserLocationEvents\UpdateUserLocation;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\UserLocations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserLocationController extends Controller
{
    /**
     * Validate Form
     * @param $request
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    public function validateFrom($request){
        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'area' => 'required|numeric|min:0|not_in:0',
        ],[
            'end_time.after' => 'The end time must be a time after start time.',
        ]);

        return $validator;
    }

    /**
     * Add new users to location
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addUserLocation(Request $request){
        $validator = $this->validateFrom($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error',true)
                ->with('message',$validator->messages()->first());
        }

        $userLocation = new UserLocations();
        $userLocation->user_id = $request->user_id;
        $userLocation->location_id = $request->location_id;
        $userLocation->start_time = $request->start_time;
        $userLocation->end_time = $request->end_time;
        $userLocation->area = $request->area;
        if ($request->has('one_day')){
            $userLocation->date = $request->date;
            $userLocation->type = UserLocations::ONETIME;
        }
        if ($request->has('custom_day')){
            if(count($request->dates) != 7){
                $dates = "";
                foreach ($request->dates as $date){
                    $dates .= $date . "|";
                }
                $userLocation->date = substr($dates, 0, -1);
                $userLocation->type = UserLocations::CUSTOMDAYS;
            }
            else{
                $userLocation->date = $request->date;
                $userLocation->type = UserLocations::EVERYDAY;
            }
        }
        if ($request->has('every_day')){
            $userLocation->date = $request->date;
            $userLocation->type = UserLocations::EVERYDAY;
        }
        $userLocation->save();

        $setting = Setting::where('name',Setting::TASK_ASSIGN_NOTIFICATIONS)->first();
        if($setting->value){
            AddUserLocation::dispatch($userLocation->user);
        }

        return redirect()->back()
            ->with('state',true)
            ->with('message','User assign to location successfully');
    }

    /**
     * Update User Location
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserLocation(Request $request){
        $validator = $this->validateFrom($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error',true)
                ->with('message',$validator->messages()->first());
        }

        $userLocation = UserLocations::find($request->id);
        if($userLocation != null){
            $userLocation->start_time = $request->start_time;
            $userLocation->end_time = $request->end_time;
            $userLocation->area = $request->area;
            if ($request->has('one_day')){
                $userLocation->date = $request->date;
                $userLocation->type = UserLocations::ONETIME;
            }
            if ($request->has('custom_day')){
                if(count($request->dates) != 7){
                    $dates = "";
                    foreach ($request->dates as $date){
                        $dates .= $date . "|";
                    }
                    $userLocation->date = substr($dates, 0, -1);
                    $userLocation->type = UserLocations::CUSTOMDAYS;
                }
                else{
                    $userLocation->date = $request->date;
                    $userLocation->type = UserLocations::EVERYDAY;
                }
            }
            if ($request->has('every_day')){
                $userLocation->date = $request->date;
                $userLocation->type = UserLocations::EVERYDAY;
            }
            $userLocation->save();

            $setting = Setting::where('name',Setting::TASK_UPDATE_NOTIFICATIONS)->first();
            if($setting->value){
                $data = array(
                    'user' => $userLocation->user,
                    'location_name' => $userLocation->location->name,
                );

                UpdateUserLocation::dispatch($data);
            }

            return redirect()->back()
                ->with('state',true)
                ->with('message','User location Update successfully');
        }
        else{
            return redirect()->back()
                ->with('error',true)
                ->with('message','Location not found !!');
        }
    }

    /**
     * Delete User Location
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUserLocation(Request $request){
        $userLocation = UserLocations::find($request->id);
        if($userLocation != null){

            $setting = Setting::where('name',Setting::TASK_DELETE_NOTIFICATIONS)->first();
            if($setting->value){
                $data = array(
                    'user' => $userLocation->user,
                    'location_name' => $userLocation->location->name,
                );

                DeleteUserLocation::dispatch($data);
            }

            $userLocation->delete();
            return redirect()->back()
                ->with('state',true)
                ->with('message','User Location delete successfully');
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','User Location delete failed !!');
        }
    }
}
