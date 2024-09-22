<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\AttendanceStatus\AttendanceStatusQuery;
use App\Models\Attendance;
use App\Models\Year;
use App\Services\AttendanceTotalService;

class AttendanceStatusController extends BaseController
{
    public function index(AttendanceStatusQuery $request, AttendanceTotalService $service)
    {
        $currentUser = auth()->user();

        $data = $request->validated();
        [$attendances, $attendanceTotal, $attendanceMetaItems] = $service->calculateAttendanceTotal($currentUser, $data['month']);

        $attendanceItems = [];
        foreach($attendances as $i => $attendance)
        {
            if (!is_array($attendance))
            {
                $item = $attendance->toArray();
            } else {
                $item = $attendance;
            }
            $attendanceItems[$i] = array_merge($item, $attendanceMetaItems[$i]);
        }

        return $this->sendResponse([
            'attendance'    =>  $attendanceItems,
            'total'         =>  $attendanceTotal
        ]);
    }
}
