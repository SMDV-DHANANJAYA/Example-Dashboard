<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AttendancesExport implements FromView
{

    private $attendances;
    private $assignFullTimeCount;
    private $workingFullTimeCount;

    public function __construct($data)
    {
        $this->attendances = $data["attendances"];
        $this->assignFullTimeCount = $data["assignFullTimeCount"];
        $this->workingFullTimeCount = $data["workingFullTimeCount"];
    }

    public function view(): View
    {
        return view('exports.attendances', [
            'attendances' => $this->attendances,
            'assignFullTimeCount' => $this->assignFullTimeCount,
            'workingFullTimeCount' => $this->workingFullTimeCount
        ]);
    }
}
