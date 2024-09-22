<?php

namespace App\Services\Child;

use App\Models\Holiday;
use Illuminate\Support\Carbon;

class HolidayService
{
    protected $monthHolidays;

    public function spanHolidaysInMonth($month)
    {
        [$year, $month] = explode('-', $month);
        $this->monthHolidays = Holiday::whereYear('date', $year)->whereMonth('date', $month)->get();
    }

    public function checkIfHoliday(Carbon $date)
    {
        $holiday = $this->monthHolidays->firstWhere('date', $date->format('Y-m-d'));
        return !empty($holiday);
    }
}
