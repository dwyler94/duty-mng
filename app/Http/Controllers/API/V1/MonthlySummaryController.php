<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Requests\MonthlySummary\AttendanceApproveRequest;
use App\Http\Requests\MonthlySummary\AttendanceRequest;
use App\Http\Requests\MonthlySummary\MonthlySummaryQuery;
use App\Mail\MonthlySummaryApprove;
use App\Models\Attendance;
use App\Models\AttendanceTotal;
use App\Models\ScheduledWorking;
use App\Models\User;
use App\Models\Year;
use App\Services\AttendancePipleline;
use App\Services\AttendanceTotalService;
use App\Services\StampService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use App\Constants\Roles;

class MonthlySummaryController extends BaseController
{
    public function get(MonthlySummaryQuery $request, User $user, AttendanceTotalService $attendanceTotalService)
    {
        $currentUser = auth()->user();
        if (!Gate::forUser($currentUser)->allows('get-user-summary', $user)) {
            abort(403, "You are not allowed");
        }
        $data = $request->validated();

        [$attendances, $attendanceTotal, $attendanceMetaItems] = $attendanceTotalService->calculateAttendanceTotal($user, $data['month']);

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

        $userMeta = [];
        if ($user->office)
        {
            $monthValue = $data['month'];
            $year = Year::where([
                ['start', '<=', $monthValue],
                ['end', '>=', $monthValue]
            ])->first();

            $month = $monthValue % 100;
            $scheduledWorking = ScheduledWorking::where(['year_id' => $year->id, 'office_id' => $user->office->id, 'month' => $month])->first();
            if ($scheduledWorking)
            {
                $userMeta['scheduled_days'] = $scheduledWorking->days;
            }
        }

        return $this->sendResponse([
            'attendance'    =>  $attendanceItems,
            'total'         =>  $attendanceTotal,
            'user_meta'          =>  $userMeta
        ]);
    }
    public function saveAttendance(AttendanceRequest $request, StampService $stampService, AttendancePipleline $attendancePipleline)
    {
        $currentUser = auth()->user();
        $data = $request->validated();
        $userId = $data['user_id'];
        $user = User::where(['id' => $userId])->first();

        if (!Gate::forUser($currentUser)->allows('update-user-work-status', $user))
        {
            abort(403, "You are not allowed");
        }
        $date = Carbon::parse($data['date']);
        $monthValue = $date->year * 100 + $date->month;
        $year = Year::where([
            ['start', '<=', $monthValue],
            ['end', '>=', $monthValue]
        ])->first();
        $attendance = Attendance::where([
            'year_id'   =>  $year->id,
            'month'     =>  $date->month,
            'day'       =>  $date->day,
            'user_id'   =>  $user->id
        ])->first();
        if (!$attendance) {
            $attendance = new Attendance([
                'year_id'   =>  $year->id,
                'month'     =>  $date->month,
                'day'       =>  $date->day,
                'day_of_week'=> $date->dayOfWeek
            ]);
            $attendance->user()->associate($user);
            $attendance->create_user_id = $currentUser->id;
        } else {
            if ($attendance->is_approved) {
                abort(404, "This item is already approved");
            }
        }

        if (!empty($data['commuting_time_1']))
        {
            $commuting_time_1 = Carbon::parse($data['date'] . ' ' .$data['commuting_time_1'] . ':00');
            $attendance->commuting_time_1 = $commuting_time_1;
        }
        if (!empty($data['commuting_time_2']))
        {
            $commuting_time_2 = Carbon::parse($data['date'] . ' ' .$data['commuting_time_2'] . ':00');
            $attendance->commuting_time_2 = $commuting_time_2;
        }
        if (!empty($data['leave_time_1']))
        {
            $leave_time_1 = Carbon::parse($data['date'] . ' ' .$data['leave_time_1'] . ':00');
            $attendance->leave_time_1 = $leave_time_1;
        }
        if (!empty($data['leave_time_2']))
        {
            $leave_time_2 = Carbon::parse($data['date'] . ' ' .$data['leave_time_2'] . ':00');
            $attendance->leave_time_2 = $leave_time_2;
        }
        $attendance->substitute_time = $data['substitute_time']??null;
        $attendance->annual_paid_time = $data['annual_paid_time']??null;
        $attendance->special_paid_time = $data['special_paid_time']??null;
        $attendance->special_unpaid_time = $data['special_unpaid_time']??null;
        $attendance->other_unpaid_time = $data['other_unpaid_time']??null;
        $attendance->reason_for_vacation_id = $data['reason_for_vacation_id']??null;
        $attendance->substitute_day = $data['substitute_day']??null;
        $attendance->remark = $data['remark']??null;
        $attendance->update_user_id = $currentUser->id;

        $stampService->matchShiftToAttendance($attendance);
        $attendancePipleline->process($attendance);
        $attendance->save();

        return $this->sendResponse($attendance);
    }
    public function approve(AttendanceApproveRequest $request, AttendanceTotalService $attendanceTotalService)
    {
        $currentUser = auth()->user();
        $data = $request->validated();
        $userId = $data['user_id'];
        $user = User::where(['id' => $userId])->first();
        if (!$user)
        {
            abort(404);
        }
        if ($currentUser->id === $user->id && ($currentUser->role_id === Roles::USER_A || $currentUser->role_id === Roles::USER_B)) {
            abort(403, "You are not allowed");
        } else if (!Gate::forUser($currentUser)->allows('update-user-work-status', $user))
        {
            abort(403, "You are not allowed");
        }

        $monthValue = $data['month'];
        $year = Year::where([
            ['start', '<=', $monthValue],
            ['end', '>=', $monthValue]
        ])->first();

        if (!$year)
        {
            abort(422, "Invalid month");
        }
        $month = $monthValue % 100;

        $days = $data['days'];

        $attendances = Attendance::where('user_id', $user->id)
                ->where(['year_id' => $year->id, 'month' => $month])
                ->whereIn('day', $days)
                ->whereNull('approved_at')
                ->get();
        // if ($attendances->count() === 0)
        // {
        //     return $this->sendResponse();
        // }
        $now = Carbon::now();

        $savedDays = [];
        foreach ($attendances as $attendance)
        {
            $savedDays[] = $attendance->day;
            if ($attendance->is_approved) continue;
            $attendance->approved_at = $now;
            $attendance->approve_user_id = $currentUser->id;
            $attendance->save();
        }

        foreach ($days as $day)
        {
            if (in_array($day, $savedDays))
            {
                continue;
            }
            $attendance = new Attendance([
                'year_id'   =>  $year->id,
                'month'     =>  $month,
                'day'       =>  $day,
            ]);
            $attendance->day_of_week = $attendance->date->dayOfWeek;
            $attendance->user()->associate($user);
            $attendance->approved_at = $now;
            $attendance->approve_user_id = $currentUser->id;
            $attendance->save();
        }

        $attendances = Attendance::where('user_id', $user->id)
                ->where(['year_id' => $year->id, 'month' => $month])
                ->whereNull('approved_at')
                ->get();
        if (!$attendances->count())
        {
            // save attendance total
            $attendanceTotalExisting = AttendanceTotal::where([
                'user_id'   =>  $user->id,
                'year_id'   =>  $year->id,
                'month'     =>  $month
            ])->first();

            [$attendanceItems, $attendanceTotal, $attendanceMetaItems] = $attendanceTotalService->calculateAttendanceTotal($user, $monthValue);

            if ($attendanceTotalExisting)
            {
                $attendanceTotalExisting->fill($attendanceTotal->toArray());
                $attendanceTotalExisting->save();
            } else {
                $attendanceTotal->save();
            }

            // notify to the administrators' mail
            $reportMails = config('lateral.month_report_to');
            $env = config('app.env');
            $mails = $reportMails[$env];

            $officeName = $user->office->name??'';
            $userName = $user->name;
            foreach ($mails as $mail)
            {
                Mail::to($mail)->queue(new MonthlySummaryApprove($officeName, $userName, (int)floor($monthValue / 100), $monthValue % 100));
            }
        }
        return $this->sendResponse();
    }
}
