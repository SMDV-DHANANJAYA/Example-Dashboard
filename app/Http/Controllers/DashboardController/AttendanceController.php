<?php

namespace App\Http\Controllers\DashboardController;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;

class AttendanceController extends Controller
{

    /**
     * View Users Attendance
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function userAttendance(Request $request){
        $users = User::where('type',User::USER)->orderBy('first_name')->get();
        $locations = Location::orderBy('name')->get();
        $attendances = Attendance::orderBy('created_at','DESC');

        if ($request->filled('user_id') && ($request->user_id != 0)) {
            $attendances = $attendances->where('user_id',$request->user_id);
        }

        if ($request->filled('location_name') && ($request->location_name != "all")) {
            $attendances = $attendances->Where('location_name',$request->location_name);
        }

        if ($request->filled('month') && ($request->month != "all")) {
            switch ($request->month){
                case Carbon::now()->format('F'):
                    $attendances = $attendances->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    break;
                case Carbon::now()->subMonth()->format('F'):
                    $attendances = $attendances->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]);
                    break;
                case Carbon::now()->subMonths(2)->format('F'):
                    $attendances = $attendances->whereBetween('created_at', [Carbon::now()->subMonths(2)->startOfMonth(), Carbon::now()->subMonths(2)->endOfMonth()]);
                    break;
            }
        }

        return view('attendance.attendances',[
            'users' => $users,
            'locations' => $locations,
            'attendances' => $attendances->paginate(50),
        ]);
    }

    /**
     * Delete Old Attendance
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteOldAttendance(){
        try{
            Artisan::call('attendance:delete');

            return redirect()->back()
                ->with('state',true)
                ->with('message','Attendance delete process started in background');
        }
        catch(\Throwable $e){
            return redirect()->back()
                ->with('state',false)
                ->with('message','Attendance delete failed !!');
        }
    }
}
