<?php

namespace App\Http\Controllers\DashboardController;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * View Settings
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view(){
        $settings = Setting::all();
        return view('setting.settings',[
            'settings' => $settings
        ]);
    }

    /**
     * Save Application Settings
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){

        $request->validate([
            Setting::ANDROID_VERSION => 'required',
            Setting::IOS_VERSION => 'required'
        ],[
            Setting::ANDROID_VERSION.'.required' => 'The android version field is required.',
            Setting::IOS_VERSION.'.required' => 'The ios version field is required.',
        ]);

        $settings = Setting::all();

        try{
            foreach ($settings as $setting){
                if ($setting->name == Setting::ANDROID_VERSION || $setting->name == Setting::IOS_VERSION){
                    $setting->value = $request->get($setting->name);
                }
                else{
                    if($request->has($setting->name)){
                        $setting->value = Setting::ON;
                        if($request->has($setting->name.'_PUSH')){
                            $setting->push_notification_state = Setting::ON;
                        }
                        else{
                            if($setting->push_notification_state != null){
                                $setting->push_notification_state = Setting::OFF;
                            }
                        }
                        if($request->has($setting->name.'_TELEGRAM')){
                            $setting->telegram_notification_state = Setting::ON;
                        }
                        else{
                            if ($setting->telegram_notification_state != null){
                                $setting->telegram_notification_state = Setting::OFF;
                            }
                        }
                    }
                    else{
                        $setting->value = Setting::OFF;
                        if($setting->push_notification_state != null){
                            $setting->push_notification_state = Setting::OFF;
                        }
                        if ($setting->telegram_notification_state != null){
                            $setting->telegram_notification_state = Setting::OFF;
                        }
                    }
                }
                $setting->save();
            }

            return redirect()->back()
                ->with('state',true)
                ->with('message','Settings save successfully');
        }
        catch(\Throwable $e){
            return redirect()->back()
                ->with('state',false)
                ->with('message','Settings save failed !!');
        }
    }
}
