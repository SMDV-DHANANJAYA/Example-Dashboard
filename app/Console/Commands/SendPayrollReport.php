<?php

namespace App\Console\Commands;

use App\Events\NotificationProcess;
use App\Mail\UserPayrollReport;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\TelegramNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class SendPayrollReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll_report:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email user payroll report monthly';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Request $request)
    {
        $value = Setting::where('name',Setting::SEND_PAYROLL_REPORTS_AUTO)->first();

        try{
            $users = User::where('type',User::USER)->get();
            foreach ($users as $user){
                $request->merge(['user_id' => $user->id,'month' => Carbon::now()->subMonth()->format('F')]);
                $data = (new \App\Http\Controllers\DashboardController\PayrollController)->view($request,"download");
                if ($data['attendances'] != null){
                    $pdf = PDF::loadView('exports.attendances',$data);
                    $content = $pdf->download()->getOriginalContent();
                    $path = 'payroll-reports/'.$user->full_name.'.pdf';
                    Storage::put($path,$content);
                    Mail::to($user->email)->send(new UserPayrollReport($user,$path));
                }
            }
            Storage::deleteDirectory('payroll-reports');
            if($value->telegram_notification_state){
                Notification::route('telegram',config('config.telegram-chat-id'))->notify(new TelegramNotification("System Alert","All payroll reports successfully send (Monthly Task)."));
            }
            NotificationProcess::dispatch(\App\Models\Notification::SUCCESS,"All payroll reports successfully send (Monthly Task)");
            return Command::SUCCESS;
        }
        catch (\Throwable $e){
            Log::error($e->getMessage());
            if($value->telegram_notification_state){
                Notification::route('telegram',config('config.telegram-chat-id'))->notify(new TelegramNotification("System Alert","Payroll report send failed (Monthly Task)."));
            }
            NotificationProcess::dispatch(\App\Models\Notification::DANGER,"Payroll report send failed (Monthly Task)");
            return Command::FAILURE;
        }
    }
}
