<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyAttendanceExport implements FromArray, WithHeadings
{
    protected $attendances;
    protected $month;

    public function __construct($attendances, $month)
    {
        $this->attendances = $attendances;
        $this->month = $month;
    }

    public function array(): array
    {
        $baseDate = Carbon::parse($this->month . '-01');
        $daysInMonth = $baseDate->daysInMonth;
        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
        $res = [];
        for ($i = 1; $i <= $daysInMonth; $i++)
        {
            $baseDate->day($i);
            $item = [$i, $weekdays[$baseDate->dayOfWeek]];
            $attendance = $this->attendances->firstWhere('day', $i);
            if ($attendance)
            {
                $item[] = $this->getTimeLabel($attendance->commuting_time);
                $item[] = $this->getTimeLabel($attendance->leave_time);
                $item[] = $attendance->extension;
            } else {
                $item[] = '';$item[] = '';$item[] = '';
            }
            $res[] = $item;
        }
        return $res;
    }
    public function headings(): array
    {
        return ['日付',	'曜日',	'登園時間',	'降園時間',	'延長'];
    }
    public function getTimeLabel($time)
    {
        if (!$time) return null;
        return Carbon::parse($time)->format('H:i');
    }
    private function zeroToString($value)
    {
        if (!$value) return '';
        return $value;
    }
}
