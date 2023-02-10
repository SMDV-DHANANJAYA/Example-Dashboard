<?php

namespace App\Http\Controllers\DashboardController;

use App\Events\LocationEvents\UpdateLocation;
use App\Events\UserLocationEvents\DeleteUserLocation;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserLocations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    /**
     * Show locations list
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function locationsList(Request $request){
        $q = $request->q;
        $locations = Location::where(function ($query) use($q){
                $query->where('name','LIKE','%'.$q.'%')
                    ->orWhere('latitude','LIKE','%'.$q.'%')
                    ->orWhere('longitude','LIKE','%'.$q.'%')
                    ->orWhere('address','LIKE','%'.$q.'%');
            })->orderBy('name');

        return view('location.locations',[
            'locations' => $locations->paginate(10)
        ]);
    }

    /**
     * Save Location
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveLocation(Request $request){
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'state' => 'required',
        ]);

        try{

            $location = new Location();
            $location->name = ucwords($request->name);
            $location->address = $request->address;
            $location->latitude = $request->latitude;
            $location->longitude = $request->longitude;
            $location->state = $request->state;
            $location->save();

            return redirect('locations')
                ->with('state',true)
                ->with('message','Location save successfully');
        }
        catch(\Throwable $e){
            return redirect()->back()
                ->with('state',false)
                ->with('message','Location save failed !!');
        }
    }

    /**
     * Delete Location
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteLocation(Request $request){
        $location = Location::find($request->id);
        if($location != null){

            $setting = Setting::where('name',Setting::DELETE_LOCATION_NOTIFICATIONS)->first();
            if($setting->value){
                $userLocations = UserLocations::where('location_id',$request->id)->get();
                foreach ($userLocations as $userLocation){
                    $data = array(
                        'user' => $userLocation->user,
                        'location_name' => $userLocation->location->name,
                    );

                    DeleteUserLocation::dispatch($data);
                }
            }

            $location->delete();

            return redirect('locations')
                ->with('state',true)
                ->with('message','Location delete successfully');
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','Location delete failed !!');
        }
    }

    /**
     * Change Location State
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLocationState(Request $request){
        $location = Location::find($request->id);
        if($location != null){
            $location->state = $location->state == Location::ACTIVE ? Location::DEACTIVE : Location::ACTIVE;
            $location->save();
            return redirect()->back()
                ->with('state',true)
                ->with('message',$location->state == Location::DEACTIVE ? 'Location disable successfully' : 'Location active successfully');
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','Location state change failed !!');
        }
    }

    /**
     * Show Update Location View
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function updateLocationView(Request $request){
        $location = Location::find($request->id);
        if($location != null){
            return view('location.update-location',[
                'location' => $location,
            ]);
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','Location not found !!');
        }
    }

    /**
     * Update Location
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLocation(Request $request){
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'state' => 'required',
        ]);

        $location = Location::find($request->id);
        if($location != null){
            try{

                $setting = Setting::where('name',Setting::UPDATE_LOCATION_NOTIFICATIONS)->first();
                if($setting->value){
                    $userLocations = UserLocations::where('location_id',$request->id)->get();
                    foreach ($userLocations as $userLocation){
                        $data = array(
                            'user' => $userLocation->user,
                            'location_name' => $userLocation->location->name,
                        );

                        UpdateLocation::dispatch($data);
                    }
                }

                $location->name = ucwords($request->name);
                $location->address = $request->address;
                $location->latitude = $request->latitude;
                $location->longitude = $request->longitude;
                $location->state = $request->state;
                $location->save();

                return redirect('locations')
                    ->with('state',true)
                    ->with('message','Location update successfully');
            }
            catch(\Throwable $e){
                return redirect()->back()
                    ->with('state',false)
                    ->with('message','Location update failed !!');
            }
        }
        else{
            return redirect('locations')
                ->with('state',false)
                ->with('message','Location not found !!');
        }
    }

    /**
     * View Location
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewLocation($id){
        $location = Location::find($id);
        if($location != null){
            $current_users = UserLocations::where('location_id',$id)->join('users', 'user_locations.user_id', '=', 'users.id')->select('user_locations.*')->orderBy('users.first_name')->paginate(10, ['*'],'current-users');
            $other_users = DB::table('users')
                ->whereNotIn('id',DB::table('user_locations')
                    ->where('location_id',$id)
                    ->select(['user_id']))->where('type',User::USER)->where('state',User::ACTIVE)->select('users.*')->orderBy('users.first_name')->paginate(10, ['*'],'new-users');
            return view('location.view-location',[
                'location' => $location,
                'current_users' => $current_users,
                'other_users' => $other_users
            ]);
        }
        else{
            return redirect()->back()
                ->with('state',false)
                ->with('message','Location not found !!');
        }
    }
}
