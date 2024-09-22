<?php

namespace App\Services;

use App\Models\Attendance;
use App\Services\Processors\AttendancePreProcessor;

/**
 * calculate behind time, leave early based on the commuting and leave time
 */
class AttendancePipleline
{
    protected $error;

    protected $attendancePreProcessor;

    public function __construct(
        AttendancePreProcessor $attendancePreProcessor
    )
    {
        $this->attendancePreProcessor = $attendancePreProcessor;
    }

    public function process(Attendance &$attendance)
    {
        $this->attendancePreProcessor->process($attendance);
    }
}
