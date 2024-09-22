<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Year;
use App\Models\Office;
use App\Services\StampService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\WorkStatusCreateRequest;
use App\Http\Requests\WorkStatusUpdateRequest;
use App\Services\AttendancePipleline;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class WorkStatusController extends BaseController
{
    protected $stampService;
    protected $attendancePipleline;

    public function __construct(StampService $stampService, AttendancePipleline $attendancePipleline) {
        $this->stampService = $stampService;
        $this->attendancePipleline = $attendancePipleline;
    }
    public function get(Office $office, Request $request)
    {
        if (!Gate::allows('get-office-work-status', $office)) {
            abort(403, "You are not allowed");
        }
        $data = $request;

        $createTime = Carbon::parse($data['date']);

        $yearNumber = $createTime->year;
        $month = $createTime->month;
        $day = $createTime->day;
        $year = Year::where([
            ['start', '<=',  $yearNumber * 100 + $month],
            ['end', '>=', $yearNumber * 100 + $month]
        ])->first();

        $employeeQb = User::where('office_id', $office->id);
        $attends = Attendance::where('year_id', $year->id)
                                ->where('month', $month)
                                ->where('day', $day)
                                ->with('applications')
                                ->get()
                                ->groupBy('user_id');

        $employees = $employeeQb->get();
        $response = [];
        foreach($employees as $employee) {
            $row['user'] = $employee;
            if (empty($attends[$employee->id])) {
                $attend = [];
                $row['attend'] = $attend;
                $response[] = $row;
                continue;
            }

            $attend = [];
            $employeeAttends = $attends[$employee->id][0];
            $items = [];
            $attend = $employeeAttends;
            $row['attend'] = $attend;
            $response[] = $row;
        }
        return $this->sendResponse($response);
    }

    public function update(Attendance $attendance, WorkStatusUpdateRequest $request)
    {
        $currentUser = auth()->user();
        if (!$attendance->user) {
            abort(404, "Invalid attendance");
        }
        if (!Gate::allows('update-user-work-status', $attendance->user)) {
            abort(403, "You are not allowed");
        }
        $data = $request->validated();

        $attendance->commuting_time_1 = empty($data['commuting_time_1']) ? null : Carbon::parse($data['commuting_time_1']);
        $attendance->leave_time_1 = empty($data['leave_time_1']) ? null : Carbon::parse($data['leave_time_1']);
        $attendance->commuting_time_2 = empty($data['commuting_time_2']) ? null : Carbon::parse($data['commuting_time_2']);
        $attendance->leave_time_2 = empty($data['leave_time_2']) ? null : Carbon::parse($data['leave_time_2']);

        $this->stampService->matchShiftToAttendance($attendance);
        $this->attendancePipleline->process($attendance);

        $attendance->update_user_id = $currentUser->id;
        $attendance->save();
        return $this->sendResponse($attendance);
    }

    public function create(WorkStatusCreateRequest $request)
    {
        $currentUser = auth()->user();
        $data = $request->validated();
        $stamp = $data['date'];
        $userId = $data['user_id'];

        $user = User::find($userId);
        if (!$user) {
            abort(404, "No such user");
        }
        if (!Gate::allows('update-user-work-status', $user)) {
            abort(403, "You are not allowed");
        }

        $createTime = Carbon::parse($stamp);
        $yearNumber = $createTime->year;
        $month = $createTime->month;
        $day = $createTime->day;
        $year = Year::where([
            ['start', '<=',  $yearNumber * 100 + $month],
            ['end', '>=', $yearNumber * 100 + $month]
        ])->first();

        $attendance = Attendance::where([
            'year_id'   =>  $year->id,
            'month'     =>  $month,
            'day'       =>  $day,
            'user_id'   =>  $userId
        ])->first();

        if (!$attendance) {
            $attendance = new Attendance([
                'year_id'   =>  $year->id,
                'month'     =>  $month,
                'day'       =>  $day,
                'day_of_week'=> $createTime->dayOfWeek
            ]);
        }

        $attendance->user_id = $userId;
        $attendance->commuting_time_1 = empty($data['commuting_time_1']) ? null : Carbon::parse($data['commuting_time_1']);
        $attendance->leave_time_1 = empty($data['leave_time_1']) ? null : Carbon::parse($data['leave_time_1']);
        $attendance->commuting_time_2 = empty($data['commuting_time_2']) ? null : Carbon::parse($data['commuting_time_2']);
        $attendance->leave_time_2 = empty($data['leave_time_2']) ? null : Carbon::parse($data['leave_time_2']);


        $this->stampService->matchShiftToAttendance($attendance);
        $this->attendancePipleline->process($attendance);

        $attendance->create_user_id = $currentUser->id;
        $attendance->save();

        return $this->sendResponse($attendance);
    }
}
