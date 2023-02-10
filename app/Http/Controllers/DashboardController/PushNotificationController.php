<?php

namespace App\Http\Controllers\DashboardController;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\User;
use App\Notifications\PushNotification;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{

    /**
     * Push notification view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function pushMessageView(){
        $users = User::where('type',User::USER)->where('state',User::ACTIVE)->where('login_state',User::LOGIN)->orderBy('first_name')->get();
        $locations = Location::where('state',Location::ACTIVE)->orderBy('name')->get();

        return view('push-message.push-message',[
            'users' => $users,
            'locations' => $locations,
        ]);
    }

    /**
     * Push notification send
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendPushMessage(Request $request){
        $request->validate([
            'user' => 'required',
            'title' => 'required|max:50',
            'body' => 'required|max:150'
        ]);

        try{

            if ($request->user != 0){
                $user = User::find($request->user);
                $user->notify(new PushNotification($request->title, $request->body));
            }
            else{
                $users = User::where('type',User::USER)->where('state',User::ACTIVE)->where('login_state',User::LOGIN)->get();
                foreach ($users as $user){
                    $user->notify(new PushNotification($request->title, $request->body));
                }
            }

            return redirect()->back()
                ->with('state',true)
                ->with('message','Push notification send successfully');
        }
        catch(\Throwable $e){
            return redirect()->back()
                ->with('state',false)
                ->with('message','Push Notification send failed !!');
        }
    }
}
