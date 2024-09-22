<?php

namespace App\Services\Processors;

use App\Models\Application;
use App\Models\ApplicationClass;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceApplicationProcessor
{
    protected $error;
    protected $attendancePreProcessor;

    public function __construct(AttendancePreProcessor $attendancePreProcessor)
    {
        $this->attendancePreProcessor = $attendancePreProcessor;
    }

    public function process(Attendance &$attendance, Application $application)
    {
        if (!$attendance->id) {
            $this->error = 'Invalid attendance';
            return false;
        }
        if ($application->status !== Application::STATUS_APPROVED) {
            return false;
        }
        if ($application->application_class_id === ApplicationClass::TYPE_BEHIND_TIME) {

            $field = 'commuting_time_';
        } else if ($application->application_class_id === ApplicationClass::TYPE_LEAVE_EARLY) {
            $field = 'leave_time_';
        } else {
            return true;
        }
        for ($i = 1; $i < 4; $i++)
        {
            $currentField = $field . $i;
            if ($this->isMatchedAtMinuteLevel($attendance->$currentField, $application->time_before_correction))
            {
                $hour = $application->time_after_correction->hour;
                $minute = $application->time_after_correction->minute;
                $time = $attendance->$currentField;
                $time->hour = $hour;
                $time->minute = $minute;
                $attendance->$currentField = $time;
                $this->attendancePreProcessor->process($attendance);
                $attendance->save();
                return true;
            }
        }
        $this->error = "No matched time";
        return false;
    }
    public function getError()
    {
        return $this->error;
    }

    public function isMatchedAtMinuteLevel($time1, $time2)
    {
        if (!$time1 || !$time2) return false;
        return $time1->hour === $time2->hour && $time1->minute === $time2->minute;
    }
}
