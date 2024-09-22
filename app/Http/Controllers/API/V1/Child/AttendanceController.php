<?php

namespace App\Http\Controllers\API\V1\Child;

use App\Exports\MonthlyAttendanceExport;
use App\Exports\MonthlyAttendanceOfficeExport;
use App\Http\Controllers\API\V1\BaseController;
use App\Http\Requests\Child\AttendanceDailyStatQuery;
use App\Http\Requests\Child\AttendanceMonthlyQuery;
use App\Http\Requests\Child\AttendanceQuery;
use App\Http\Requests\Child\AttendanceRequest;
use App\Http\Resources\ChildAttendanceResource;
use App\Http\Requests\Shift\ShiftQuery;
use App\Models\Child;
use App\Models\ChildcarePlanDay;
use App\Models\ChildInformation;
use App\Models\ChildrenAttendence;
use App\Models\ChildrenClassPeriod;
use App\Models\ContactBook;
use App\Models\Holiday;
use App\Models\Notification;
use App\Models\Office;
use App\Models\OfficeInformation;
use App\Models\Year;
use App\Services\Child\AttendanceService;
use App\Services\UtilService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\PersonalAccessToken;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelExcel;

class AttendanceController extends BaseController
{
    use ChildcareAuthUserTrait;

    public function save(Child $child, AttendanceRequest $request)
    {
        $data = $request->validated();

        [$year, $month, $day] = explode('-', $data['date']);
        $monthValue = $year * 100 + $month;
        $Year = Year::where([
            ['start', '<=', $monthValue],
            ['end', '>=', $monthValue]
        ])->first();
        $attendance = ChildrenAttendence::where([
            'year_id'   =>  $Year->id,
            'month'     =>  (int)$month,
            'day'       =>  (int)$day,
            'child_id'  => $child->id
        ])->first();

        if (!$attendance) {
            $attendance = new ChildrenAttendence([
                'year_id'   =>  $Year->id,
                'month'     =>  $month,
                'day'       =>  $day,
                'child_id'  =>  $child->id
            ]);
        }

        if (!empty($data['commuting_time'])) {
            $data['previous_extension'] = (new AttendanceService)->getPreviousExtension($child, $data['date'], $data['commuting_time']);
        }
        if (!empty($data['leave_time'])) {
            $data['extension'] = (new AttendanceService)->getExtension($child, $data['date'], $data['leave_time']);
        }

        $attendance->fill([
            'year_id'   =>  $Year->id,
            'month'     =>  $month,
            'day'       =>  $day,
            'date'      =>  $data['date'],
            'commuting_time'    =>  !empty($data['commuting_time']) ? Carbon::parse($data['date'] . ' ' .  $data['commuting_time']) : null,
            'leave_time' =>  !empty($data['leave_time']) ? Carbon::parse($data['date'] . ' ' .  $data['leave_time']) : null,
            'reason_for_absence_id' =>  $data['reason_for_absence_id'] ?? null,
            'behind_time' => $data['behind_time'] ?? null,
            'extension'  => $data['extension'] ?? null,
            'previous_extension'  => $data['previous_extension'] ?? null
        ]);
        $attendance->save();

        Notification::where('date', $data['date'])->where('child_id', $child->id)->update([
            'process_flag' => true, 'process_datetime' => date('YmdHis'), 'process_user_id' => \Auth::id()
        ]);

        return $this->sendResponse($attendance);
    }
    public function list(AttendanceQuery $request)
    {
        $user = auth()->user();

        $data = $request->validated();
        [$year, $month, $day] = explode('-', $data['date']);
        $monthValue = $year * 100 + $month;
        $Year = Year::where([
            ['start', '<=', $monthValue],
            ['end', '>=', $monthValue]
        ])->first();

        $childrenAttendences = DB::table('children')->where(['office_id' => $user->office_id])
            ->whereNull('canceled_at')
            ->leftJoin('children_attendences', function ($join) use ($Year, $month, $day) {
                $join->on('children_attendences.child_id', '=', 'children.id')
                    ->where([
                        'children_attendences.year_id'  =>  $Year->id,
                        'children_attendences.month'  =>  (int)$month,
                        'children_attendences.day'  =>  (int)$day,
                    ]);
            })
            ->leftJoin('childcare_plan_days', function ($join) use ($data)  {
                $join->on('childcare_plan_days.child_id', '=', 'children.id')
                    ->where(['childcare_plan_days.date' => $data['date']])
                    ->select('childcare_plan_days.absent_id');
            })
            ->leftJoin('notifications', function ($join) use ($data) {
                $join->on('notifications.child_id', '=', 'children.id')
                    ->where(['notifications.date'  =>  $data['date'], 'notifications.deleted_at' => null])
                    ->select('notifications.child_id')->groupBy('notifications.child_id', 'notifications.date');
            })
            ->where(function ($query) use ($data) {
                $query->where('children.exit_date', '>=', $data['date'])
                    ->orWhere('children.exit_date', '=', null);
            })
            ->where(function ($query) use ($data) {
                $query->where('children.admission_date', '<=', $data['date'])
                    ->orWhere('children.admission_date', '=', null);
            })
            ->select(
                'children_attendences.*',
                'children.id',
                'children.name',
                'children.number',
                'childcare_plan_days.absent_id as plan_absent_id',
                'notifications.child_id as notification_child_id',
                'notifications.message as notification_message',
                'notifications.process_flag'
            )
            ->selectSub(ChildrenClassPeriod::latestClassIDSubClosure($data['date']), 'class_id')
            ->get();

        return $this->sendResponse(ChildAttendanceResource::collection($childrenAttendences));
    }
    public function monthly(AttendanceQuery $request)
    {
        $user = auth()->user();

        $data = $request->validated();
        [$year, $month, $day] = explode('-', $data['date']);
        $monthValue = $year * 100 + $month;
        $Year = Year::where([
            ['start', '<=', $monthValue],
            ['end', '>=', $monthValue]
        ])->first();
        $classId = $data['class'] ?? null;
        $sort = ['children.id', 'asc'];
        if (!empty($data['sort'])) {
            $sort = ['children.number', $data['sort']];
        }

        $allAttendences = [];
        $end = (int) Carbon::parse($data['date'])->format('t');
        for ($day=1; $day <= $end; $day++) {
            $date = Carbon::create($year, $month, $day, 0, 0, 0)->format('Y-m-d');
            $childrenAttendences = DB::table('children')->where(['office_id' => $user->office_id])
                ->whereNull('canceled_at')
                ->leftJoin('children_attendences', function ($join) use ($Year, $month, $day) {
                    $join->on('children_attendences.child_id', '=', 'children.id')
                        ->where([
                            'children_attendences.year_id'  =>  $Year->id,
                            'children_attendences.month'  =>  (int)$month,
                            'children_attendences.day'  =>  (int)$day,
                        ]);
                })
                ->leftJoin('childcare_plan_days', function ($join) use ($date)  {
                    $join->on('childcare_plan_days.child_id', '=', 'children.id')
                        ->where(['childcare_plan_days.date' => $date])
                        ->select('childcare_plan_days.absent_id');
                })
                ->leftJoin('notifications', function ($join) use ($date) {
                    $join->on('notifications.child_id', '=', 'children.id')
                        ->where(['notifications.date'  =>  $date, 'notifications.deleted_at' => null])
                        ->select('notifications.child_id')->groupBy('notifications.child_id', 'notifications.date');
                })
                ->where(function ($query) use ($date) {
                    $query->where('children.exit_date', '>=', $date)
                        ->orWhere('children.exit_date', '=', null);
                })
                ->where(function ($query) use ($date) {
                    $query->where('children.admission_date', '<=', $date)
                        ->orWhere('children.admission_date', '=', null);
                })
                ->where(function ($query) use ($classId) {
                    if (!empty($classId) && (int)$classId > 0) {
                        $query->where('children.class_id', '=', $classId);
                    }
                })
                ->select(
                    'children_attendences.*',
                    'children.id',
                    'children.id as child_id',
                    'children.name',
                    'children.number',
                    \DB::raw("'{$date}' AS date"),
                    \DB::raw("{$month} AS month"),
                    \DB::raw("{$day} AS day"),
                    'childcare_plan_days.absent_id as plan_absent_id',
                    'notifications.child_id as notification_child_id',
                    'notifications.message as notification_message',
                    'notifications.process_flag'
                )
                ->selectSub(ChildrenClassPeriod::latestClassIDSubClosure($date), 'class_id')
                ->orderBy($sort[0], $sort[1])
                ->get()->toArray();

            $allAttendences = array_merge_recursive($allAttendences, $childrenAttendences);
        }

        $sumAttendences = [];
        $classId = null;
        foreach ($allAttendences as $item) {
            if (!empty($item->class_id) && !empty($item->child_id)) {
                if ($classId != $item->class_id) {
                    $classId = $item->class_id;
                }
                if (empty($sumAttendences[$classId][$item->child_id])) {
                    $clone = clone $item;
                    $clone->day = 32;
                    if (!empty($clone->previous_extension)) {
                        $clone->previous_extension = Carbon::parse($clone->previous_extension)->format('H:i');
                    }
                    if (!empty($clone->extension)) {
                        $clone->extension = Carbon::parse($clone->extension)->format('H:i');
                    }
                    $sumAttendences[$classId][$item->child_id] = $clone;

                } else {
                    if (!empty($item->previous_extension)) {
                        if (!empty($sumAttendences[$classId][$item->child_id]->previous_extension)) {
                            $sumAttendences[$classId][$item->child_id]->previous_extension = (new UtilService)->addTimeToTime($sumAttendences[$classId][$item->child_id]->previous_extension, $item->previous_extension);
                        } else {
                            $sumAttendences[$classId][$item->child_id]->previous_extension = Carbon::parse($item->previous_extension)->format('H:i');
                        }
                    }
                    if (!empty($item->extension)) {
                        if (!empty($sumAttendences[$classId][$item->child_id]->extension)) {
                            $sumAttendences[$classId][$item->child_id]->extension = (new UtilService)->addTimeToTime($sumAttendences[$classId][$item->child_id]->extension, $item->extension);
                        } else {
                            $sumAttendences[$classId][$item->child_id]->extension = Carbon::parse($item->extension)->format('H:i');
                        }
                    }

                }
            }
        }
        foreach ($sumAttendences as $classItem) {
            foreach ($classItem as $childItem) {
                if (!empty($childItem)) {
                    $allAttendences[] = $childItem;
                }
            }
        }

        return $this->sendResponse(ChildAttendanceResource::collection($allAttendences));
    }

    public function monthlyList(Child $child, AttendanceMonthlyQuery $request)
    {
        $user = $this->getUser();
        if (!Gate::forUser($user)->allows('handle-child', $child)) {
            abort(403, trans('You are not allowed'));
        }
        $data = $request->validated();
        [$year, $month] = explode('-', $data['month']);
        $attendances = ChildrenAttendence::where(['child_id' => $child->id, 'month' => $month])
            ->whereYear('date', $year)
            ->get();
        $planDays = ChildcarePlanDay::where(['child_id' => $child->id])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();
        $baseDate = Carbon::parse($data['month'] . '-01');
        $daysInMonth = $baseDate->daysInMonth;
        $result = [];
        for ($i = 0; $i < $daysInMonth; $i++) {
            $baseDate->day($i + 1);
            $item = [
                'id' => null,
                'commuting_time' => null,
                'leave_time' => null,
                'reason_for_absence_id' => null,
                'extension' => '',
                'no_schedule'   =>  false,
                'day'   =>  $i + 1,
            ];

            $attendance = $attendances->firstWhere('date', $baseDate->format('Y-m-d'));
            $plan = $planDays->first(function ($value) use ($baseDate) {
                return $value->date->timestamp == $baseDate->timestamp;
            });
            if ($attendance) {
                $item = $attendance->toArray();
            }
            // if ((!$plan || $plan->absent_id || !$plan->start_time || !$plan->end_time) && !$item['reason_for_absence_id']) {
            //     $item['no_schedule'] = true;
            // }
            // if (!$item['commuting_time'] && !$item['reason_for_absence_id'] && $plan && $plan->absent_id)
            // {
            //     $item['reason_for_absence_id'] = $plan->absent_id;
            // }
            if ($plan && $plan->absent_id) {
                $item['no_schedule'] = true;
                $item['reason_for_absence_id'] = $plan->absent_id;
            }

            $contact_status = ContactBook::STATUS_INCOMPLETE;
            $contactBook = ContactBook::whereDate('date', $baseDate->format('Y-m-d'))->where(['child_id' => $child->id])->first();
            if ($contactBook) {
                $contact_status = $contactBook->status;
            }
            $item['contact_status'] = $contact_status;

            /** exclude before admission date and after exit_date */
            if ($child->admission_date && $baseDate->format('Y-m-d') < $child->admission_date) {
                $item['exclude'] = true;
            }

            if ($child->exit_date && $baseDate->format('Y-m-d') > $child->exit_date) {
                $item['exclude'] = true;
            }

            $result[] = $item;
        }
        return $this->sendResponse($result);
    }
    public function monthlyListCsv(Child $child, AttendanceMonthlyQuery $request)
    {
        if (!$request->has('token')) {
            abort(403, "You are not allowed");
        }
        $token = $request->input('token');
        $token = PersonalAccessToken::findToken($token);

        if (!$token) {
            abort(403, "You are not allowed");
        }
        $currentUser = $token->tokenable;
        if (!$currentUser) {
            abort(403, "You are not allowed");
        }
        if (!Gate::forUser($currentUser)->allows('handle-child', $child)) {
            abort(403, trans('You are not allowed'));
        }
        $data = $request->validated();
        [$year, $month] = explode('-', $data['month']);
        $attendances = ChildrenAttendence::where(['child_id' => $child->id, 'month' => $month])
            ->whereYear('date', $year)
            ->get();
        $fileName = $child->name . '_' . $data['month'] . '登降園管理';
        return Excel::download(new MonthlyAttendanceExport($attendances, $data['month']), $fileName . '.csv', ExcelExcel::CSV);
    }

    public function dailyStat(AttendanceDailyStatQuery $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        $office_id = $user->office_id;

        $childQb = Child::where(['office_id' => $office_id])
            ->where(function ($query) use ($data) {
                $query->whereNull('exit_date')
                    ->orWhere('exit_date', '>=', $data['date']);
            })
            ->where(function ($query) use ($data) {
                $query->where('admission_date', '<=', $data['date'])
                    ->orWhere('admission_date', '=', null);
            });
        if (!empty($data['children_class_id'])) {
            $childQb->where(ChildrenClassPeriod::latestClassIDSubClosure($data['date']), $data['children_class_id']);
        }
        $children = $childQb->get();
        $allCount = $children->count();

        $attendanceCount = ChildrenAttendence::where(['date' => $data['date']])
            ->whereIn('child_id', $children->pluck('id')->toArray())
            ->whereNotNull('commuting_time')
            ->count();

        $absentCount = $allCount - $attendanceCount;

        return $this->sendResponse([
            'all'   =>  $allCount,
            'attend' =>  $attendanceCount,
            'absent' =>  $absentCount
        ]);
    }

    public function excel(Office $office, ShiftQuery $request)
    {
        if (!$request->has('token'))
        {
            abort(403, "You are not allowed");
        }
        $token = $request->input('token');
        $token = PersonalAccessToken::findToken($token);

        if (!$token) {
            abort(403, "You are not allowed");
        }
        $user = $token->tokenable;
        if (!$user) {
            abort(403, "You are not allowed");
        }

        $data = $request->validated();

        $fileName = $office->name . "_" . $data['month'] . '.xlsx';

        $month = (int)$data['month'];
        $year = (int)floor($month / 100);
        $month = $month % 100;
        $carbon = Carbon::create($year, $month, 1, 0, 0, 0);
        $holidays = Holiday::where('date', 'like', $carbon->format('Y-m-') . '%')
            ->select('name', 'date')->pluck('name', 'date')->toArray();

        $attendanceData = $this->getAttendanceData($office, $year, $month);
        ksort($attendanceData);

        return Excel::download(new MonthlyAttendanceOfficeExport($office, $attendanceData, $year, $month, $holidays), $fileName);
    }

    private function getAttendanceData(Office $office, $year, $month)
    {
        $monthValue = $year * 100 + $month;
        $Year = Year::where([
            ['start', '<=', $monthValue],
            ['end', '>=', $monthValue]
        ])->first();

        $allAttendences = [];
        $end = (int) $date = Carbon::create($year, $month, 1, 0, 0, 0)->format('t');
        for ($day=1; $day <= $end; $day++) {
            $date = Carbon::create($year, $month, $day, 0, 0, 0)->format('Y-m-d');
            $childrenAttendences = DB::table('children')->where(['office_id' => $office->id])
                ->whereNull('canceled_at')
                ->leftJoin('children_attendences', function ($join) use ($Year, $month, $day) {
                    $join->on('children_attendences.child_id', '=', 'children.id')
                        ->where([
                            'children_attendences.year_id'  =>  $Year->id,
                            'children_attendences.month'  =>  (int)$month,
                            'children_attendences.day'  =>  (int)$day,
                        ]);
                })
                ->leftJoin('childcare_plan_days', function ($join) use ($date)  {
                    $join->on('childcare_plan_days.child_id', '=', 'children.id')
                        ->where(['childcare_plan_days.date' => $date])
                        ->select('childcare_plan_days.absent_id');
                })
                ->leftJoin('reason_for_absences', 'reason_for_absences.id', '=', 'children_attendences.reason_for_absence_id')
                ->leftJoin('reason_for_absences as reason_for_absences_plan', 'reason_for_absences_plan.id', '=', 'childcare_plan_days.absent_id')
                ->where(function ($query) use ($date) {
                    $query->where('children.exit_date', '>=', $date)
                        ->orWhere('children.exit_date', '=', null);
                })
                ->where(function ($query) use ($date) {
                    $query->where('children.admission_date', '<=', $date)
                        ->orWhere('children.admission_date', '=', null);
                })
                ->select(
                    'children_attendences.*',
                    'children.id',
                    'children.id as child_id',
                    'children.name',
                    'children.number',
                    DB::raw("{$date} AS date"),
                    DB::raw("{$month} AS month"),
                    DB::raw("{$day} AS day"),
                    'children_attendences.reason_for_absence_id',
                    'childcare_plan_days.absent_id as plan_absent_id',
                    'reason_for_absences.ruby as reason_for_absence_ruby',
                    'reason_for_absences_plan.ruby as reason_for_absences_plan_ruby',
                )
                ->selectSub(ChildrenClassPeriod::latestClassIDSubClosure($date), 'class_id')
                ->get()->toArray();

            $allAttendences = array_merge_recursive($allAttendences, $childrenAttendences);
        }

        $response = [];
        $classId = null;
        foreach ($allAttendences as $attendance) {
            if (!empty($attendance->class_id) && !empty($attendance->child_id) && !empty($attendance->day)) {
                if ($classId != $attendance->class_id) {
                    $classId = $attendance->class_id;
                }
                $response[$classId][$attendance->child_id][$attendance->day] = (array) $attendance;
                if (!empty($attendance->previous_extension)) {
                    if (!empty($response[$classId][$attendance->child_id][32]['previous_extension'])) {
                        $response[$classId][$attendance->child_id][32]['previous_extension'] = (new UtilService)->addTimeToTime($response[$classId][$attendance->child_id][32]['previous_extension'], $attendance->previous_extension);
                    } else {
                        $response[$classId][$attendance->child_id][32]['previous_extension'] = Carbon::parse($attendance->previous_extension)->format('H:i');
                    }
                }
                if (!empty($attendance->extension)) {
                    if (!empty($response[$classId][$attendance->child_id][32]['extension'])) {
                        $response[$classId][$attendance->child_id][32]['extension'] = (new UtilService)->addTimeToTime($response[$classId][$attendance->child_id][32]['extension'], $attendance->extension);
                    } else {
                        $response[$classId][$attendance->child_id][32]['extension'] = Carbon::parse($attendance->extension)->format('H:i');
                    }
                }
            }
        }
        return $response;
    }
}
