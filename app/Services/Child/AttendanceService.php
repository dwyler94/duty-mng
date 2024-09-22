<?php

namespace App\Services\Child;

use App\Models\Child;
use App\Models\ChildcarePlanDay;
use App\Models\ChildInformation;
use App\Models\ChildrenAttendence;
use App\Models\OfficeInformation;
use App\Services\UtilService;

class AttendanceService
{
    /**
     * @param Child $child
     * @param string $month 'Y-m'
     * @return [monthlyAttendCount, monthlyAbsentCount]
     */
    public function countMonthlyAttend(Child $child, $month)
    {
        [$year, $month] = explode('-', $month);
        // $plans = ChildcarePlanDay::where(['child_id' => $child->id])
        //         ->whereMonth('date', $month)
        //         ->whereYear('date', $year)->whereNull('absent_id')
        //         ->get();
        $attends = ChildrenAttendence::where(['child_id' => $child->id])
                ->where(['month' => $month])
                ->whereYear('date', $year)
                ->whereNull('reason_for_absence_id')
                ->get();
        $attendCount = $attends->count();

        $absentCount = ChildrenAttendence::where(['child_id' => $child->id])
            ->where(['month' => $month])
            ->whereYear('date', $year)
            ->whereNotNull('reason_for_absence_id')
            ->count();
        // foreach ($plans as $plan)
        // {
        //     if (!$attends->where('date', $plan->date)->count())
        //     {
        //         $absentCount++;
        //     }
        // }
        return [$attendCount, $absentCount];
    }

    public function getExtension($child, $date, $timeF) {
        $office = OfficeInformation::where('office_id', $child->office_id)
            ->where(function($query) use ($date) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $date);
            })
            ->where(function($query) use ($date) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $date);
            })->first();
        $childInformation = ChildInformation::where(['child_id' => $child->id])
            ->where(function($query) use ($date) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $date);
            })
            ->where(function($query) use ($date) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $date);
            })->first();
        $close_time = $office->close_time;
        if (!empty($childInformation->certification_type)) {
            $close_time = $office->close_time_short;
        }
        $extension = (new UtilService)->subTimeToTime($timeF, $close_time);
        $extension = !empty($extension) ? $extension : NULL;
        return $extension;
    }

    public function getPreviousExtension($child, $date, $timeF) {
        $office = OfficeInformation::where('office_id', $child->office_id)
            ->where(function($query) use ($date) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $date);
            })
            ->where(function($query) use ($date) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $date);
            })->first();
        $childInformation = ChildInformation::where(['child_id' => $child->id])
            ->where(function($query) use ($date) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', $date);
            })
            ->where(function($query) use ($date) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', $date);
            })->first();
        $open_time = $office->open_time;
        if (!empty($childInformation->certification_type)) {
            $open_time = $office->open_time_short;
        }
        $previous_extension = (new UtilService)->subTimeToTime($open_time, $timeF);
        $previous_extension = !empty($previous_extension) ? $previous_extension : NULL;
        return $previous_extension;
    }
}
