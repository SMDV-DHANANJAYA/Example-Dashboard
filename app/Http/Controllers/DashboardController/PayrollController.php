<?php

namespace App\Http\Controllers\DashboardController;

use App\Exports\AttendancesExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PayrollController extends Controller
{

    /**
     * Convert Time
     * @param $time
     * @return string
     */
    public function convertTime($time) {
        $hours = $time >= 60 ? floor($time / 60) : 0;
        $minutes = ($time % 60);
        return sprintf('%02d (H) : %02d (M)', $hours, $minutes);
    }

    /**
     * Calculate Time
     * @param $start_time
     * @param $end_time
     * @return float|int|string
     */
    public function calculateTime($start_time,$end_time){
        $temp_start_time = explode(":",$start_time);
        $temp_start_time = ((int)$temp_start_time[0] * 60) + (int)$temp_start_time[1];

        $temp_end_time = explode(":",$end_time);
        $temp_end_time = ((int)$temp_end_time[0] * 60) + (int)$temp_end_time[1];

        return $temp_end_time - $temp_start_time;
    }

    /**
     * View or return attendance for download
     * @param Request $request
     * @param $state
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view(Request $request,$state = "view"){
        $attendances = null;
        $assignFullTimeCount = 0;
        $workingFullTimeCount = 0;

        if ($request->filled('user_id')) {
            $attendances = Attendance::orderBy('created_at','DESC');
            $attendances = $attendances->where('user_id',$request->user_id);
        }

        if ($request->filled('month')) {
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
        elseif ($attendances != null){
            $attendances = $attendances->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        }

        if($attendances != null){
            $attendances = $attendances->get();

            foreach ($attendances as $attendance){
                $assignFullTimeCount = $assignFullTimeCount + $this->calculateTime($attendance->location_start_time->format('H:i'),$attendance->location_end_time->format('H:i'));
                if($attendance->is_complete){
                    $workingFullTimeCount = $workingFullTimeCount + $this->calculateTime($attendance->user_start_time->format('H:i'),$attendance->user_end_time->format('H:i'));
                }
            }

            $assignFullTimeCount = $this->convertTime($assignFullTimeCount);
            $workingFullTimeCount = $this->convertTime($workingFullTimeCount);

            if(!count($attendances)){
                $attendances = null;
            }
        }

        if($state == "view"){
            $users = User::where('type',User::USER)->orderBy('first_name')->get();

            return view('payroll.payrolls',[
                'users' => $users,
                'attendances' => $attendances,
                'assignFullTimeCount' => $assignFullTimeCount,
                'workingFullTimeCount' => $workingFullTimeCount
            ]);
        }
        else{
            return [
                'attendances' => $attendances,
                'assignFullTimeCount' => $assignFullTimeCount,
                'workingFullTimeCount' => $workingFullTimeCount
            ];
        }
    }

    /**
     * Download Excel File
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadExcel(Request $request){
        try{
            $data = $this->view($request,"download");
            return Excel::download(new AttendancesExport($data),$data['attendances'][0]->user->full_name.'-'.$request->month.'-payroll.xlsx');
        }
        catch(\Throwable $e){
            return redirect()->back()
                ->with('state',false)
                ->with('message','Payroll download failed !!');
        }
    }

    /**
     * Download PDF File
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function downloadPdf(Request $request){
        try{
            $data = $this->view($request,"download");
            $pdf = PDF::loadView('exports.attendances',$data);
            return $pdf->download($data['attendances'][0]->user->full_name.'-'.$request->month.'-payroll.pdf');
        }
        catch(\Throwable $e){
            Log::error($e->getMessage());
            return redirect()->back()
                ->with('state',false)
                ->with('message','Payroll download failed !!');
        }
    }
}
