<?php

namespace App\Http\Controllers\DashboardController;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Artisan;

class NotificationController extends Controller
{

    /**
     * View Notifications
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewAll(){
        Notification::where('read_state',Notification::NOTIFICATION_NEW)->update(['read_state' => Notification::NOTIFICATION_READ]);
        $notifications = Notification::orderBy('created_at','desc')->paginate(50);

        return view('notification.notifications',[
            'notifications' => $notifications
        ]);
    }

    /**
     * Get Notifications Part
     * @return array
     */
    public function viewShort(){
        $newNotificationsCount = Notification::where('read_state',Notification::NOTIFICATION_NEW)->count();
        $notifications = Notification::orderBy('created_at','desc')->take(5)->get();

        return [
            'count' => $newNotificationsCount,
            'notifications' => $notifications
        ];
    }

    /**
     * Delete Old Notification
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteOldNotification(){
        try{
            Artisan::call('notifications:delete');

            return redirect()->back()
                ->with('state',true)
                ->with('message','Notifications delete successfully');
        }
        catch(\Throwable $e){
            return redirect()->back()
                ->with('state',false)
                ->with('message','Notifications delete failed !!');
        }
    }

    /**
     * Delete All Notifications
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAllNotification(){
        try{
            Notification::truncate();

            return redirect()->back()
                ->with('state',true)
                ->with('message','Notifications delete successfully');
        }
        catch(\Throwable $e){
            return redirect()->back()
                ->with('state',false)
                ->with('message','Notifications delete failed !!');
        }
    }
}
