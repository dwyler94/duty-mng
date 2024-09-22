<?php

namespace App\Http\Controllers\API\V1;

use App\Constants\CodeGroups;
use App\Exports\ChildApplicationTableExport;
use App\Http\Requests\ChildApplicationTableQuery;
use App\Http\Resources\OfficeResource;
use App\Models\Child;
use App\Models\ChildInformation;
use App\Models\ChildrenAttendence;
use App\Models\ChildrenClass;
use App\Models\Code;
use App\Models\Office;
use App\Models\ReasonForAbsence;
use App\Models\User;
use App\Services\Child\AttendanceService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\PersonalAccessToken;
use Maatwebsite\Excel\Facades\Excel;

class ChildApplicationTableController extends BaseController
{
    protected $childTypes;
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->childTypes = Code::where(['group' => CodeGroups::CHILD_TYPE])->get();
        $this->attendanceService = $attendanceService;
    }
    public function get(Office $office, ChildApplicationTableQuery $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        if (!Gate::forUser($user)->allows('get-child-application-table', $office))
        {
            abort(403, trans('You are not allowed'));
        }
        $table = $this->getTable($office, $data['month']);
        $table['office'] = new OfficeResource($office);
        return $this->sendResponse($table);
    }

    public function exportExcel(Office $office, ChildApplicationTableQuery $request)
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
        $currentUser = $token->tokenable;
        if (!$currentUser) {
            abort(403, "You are not allowed");
        }

        if (!Gate::forUser($currentUser)->allows('get-child-application-table', $office))
        {
            abort(403, trans('You are not allowed'));
        }

        $data = $request->validated();

        $table = $this->getTable($office, $data['month']);
        $title = $office->name . '_' . $data['month'];
        return Excel::download(new ChildApplicationTableExport($table, $office, $data['month']), $title . '.xlsx');
    }

    private function getTable($office, $date)
    {
        $data = [
            'office_name'   =>  $office->name,
            'employee_count'    =>  $office->users()->count(),
            'capacity'      =>  $office->getCapacityByMonth($date)
        ];
        [$year, $month] = explode('-', $date);

        $baseDay = Carbon::parse($date . '-01');
        $daysInMonth = $baseDay->daysInMonth;

        $children = Child::where(function($query) use ($baseDay) {
                $query->whereNull('exit_date')
                    ->orWhere('exit_date', '>=', $baseDay->format('Y-m-d'));
            })
            ->where(function($query) use ($baseDay, $daysInMonth) {
                $query->where('admission_date', '<=', $baseDay->day($daysInMonth)->format('Y-m-d'))
                    ->orWhere('admission_date', '=', null);
            })
            ->where(['office_id' => $office->id])
            ->get();

        // クラスは年度開始日(4月1日)の実年齢に対応するクラスとする
        $classYear = Carbon::parse($date)->subMonthsNoOverflow(3)->format('Y');
        $firstOfYear = Carbon::parse($classYear . '-04-01');

        foreach ($children as $child) {
            if (!empty($child->birthday)) {
                $child->class_id = Carbon::parse($child->birthday)->diffInYears($firstOfYear) + 1;
            }
        }

        $data['children_stat'] = [
            ChildrenClass::AGE_0 => $children->where('class_id', ChildrenClass::AGE_0)->count(),
            ChildrenClass::AGE_1 => $children->where('class_id', ChildrenClass::AGE_1)->count(),
            ChildrenClass::AGE_2 => $children->where('class_id', ChildrenClass::AGE_2)->count(),
            ChildrenClass::AGE_3 => $children->where('class_id', ChildrenClass::AGE_3)->count(),
            ChildrenClass::AGE_4 => $children->where('class_id', ChildrenClass::AGE_4)->count(),
            ChildrenClass::AGE_5 => $children->where('class_id', ChildrenClass::AGE_5)->count(),
        ];
        $childEmployeeQuota = $children->filter(function ($value, $key) use ($date) {
            $child_info = $value->getChildInfoByMonthForApplication($date);
            return $child_info && ($child_info->type === 1 || $child_info->type === 2);
        })->count();
        $childRegional = $children->filter(function ($value, $key) use ($date) {
            $child_info = $value->getChildInfoByMonthForApplication($date);
            return $child_info && ($child_info->type === 3 || $child_info->type === 4);
        })->count();
//        $totalCount = $children->count();
//        if (!$totalCount) {
//            $employeeQuotaRatio = 0;
//            $regionalRatio = 0;
//        } else {
//            $employeeQuotaRatio = round($childEmployeeQuota * 100 / $totalCount);
//            $regionalRatio = round($childRegional * 100 / $totalCount);
//        }
        if (!$data['capacity']) {
            $employeeQuotaRatio = 0;
            $regionalRatio = 0;
        } else {
            $employeeQuotaRatio = round($childEmployeeQuota * 100 / $data['capacity']);
            $regionalRatio = round($childRegional * 100 / $data['capacity']);
        }

        $data['children_type_stat'] = [
            'employee_quota'    =>  $childEmployeeQuota,
            'regional'          =>  $childRegional,
            'employee_quota_ratio'=> $employeeQuotaRatio,
            'regional_ratio'    =>  $regionalRatio
        ];


        $attendances = ChildrenAttendence::with('child')
            ->whereHas('child', function ($query) use ($office) {
                $query->where(['office_id' => $office->id]);
            })
            ->where(['month' => $month])
            ->whereYear('date', $year)
            ->get();


        $data['children_stat']['extension_stat'] = [];
        $data['children_stat']['absent_stat'] = [];

        $sumA = 0; $sumB = 0; $sumC = 0; $sumD = 0; $sumE = 0;

        $sumAbsentCorona = 0; $sumAbsentPrivate = 0; $sumAbsentKibiki = 0; $sumAbsentSick = 0; $sumAbsentSuspension = 0; $sumAbsentVacation = 0; $sumAbsentDisaster = 0;

        for ($i = 1; $i <= $daysInMonth; $i++)
        {
            $targetDate = $baseDay->day($i)->format('Y-m-d');
            $a = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && !$value->extension_in_minute && !$value->reason_for_absence_id;
            })->count();
            $b = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->extension_in_minute && $value->extension_in_minute <= 30;
            })->count();
            $c = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->extension_in_minute && $value->extension_in_minute > 30 && $value->extension_in_minute <= 60;
            })->count();
            $d = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->extension_in_minute && $value->extension_in_minute > 60 && $value->extension_in_minute <= 90;
            })->count();
            $e = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->extension_in_minute && $value->extension_in_minute > 90;
            })->count();
            $data['children_stat']['extension_stat'][$i] = compact('a', 'b', 'c', 'd', 'e');

            $sumA += $a; $sumB += $b; $sumC += $c; $sumD += $d; $sumE += $e;

            $sumAbsentCorona = 0; $sumAbsentPrivate = 0; $sumAbsentKibiki = 0; $sumAbsentSick = 0; $sumAbsentSuspension = 0; $sumAbsentVacation = 0;

            $absentCorona = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->reason_for_absence_id === ReasonForAbsence::REASON_CORONA;
            })->count();
            $absentPrivate = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->reason_for_absence_id === ReasonForAbsence::REASON_PRIVATE;
            })->count();
            $absentKibiki = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->reason_for_absence_id === ReasonForAbsence::REASON_KIBIKI;
            })->count();
            $absentSick = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->reason_for_absence_id === ReasonForAbsence::REASON_SICK;
            })->count();
            $absentSuspension = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->reason_for_absence_id === ReasonForAbsence::REASON_SUSPENSION;
            })->count();
            $absentVacation = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->reason_for_absence_id === ReasonForAbsence::REASON_VACATION;
            })->count();
            $absentDisaster = $attendances->filter(function ($value, $key) use ($targetDate) {
                return $value->date === $targetDate && $value->reason_for_absence_id === ReasonForAbsence::REASON_DISASTER;
            })->count();

            $data['children_stat']['absent_stat'][$i] = [
                ReasonForAbsence::REASON_CORONA =>  $absentCorona,
                ReasonForAbsence::REASON_PRIVATE    =>  $absentPrivate,
                ReasonForAbsence::REASON_KIBIKI =>  $absentKibiki,
                ReasonForAbsence::REASON_SICK   =>  $absentSick,
                ReasonForAbsence::REASON_SUSPENSION =>  $absentSuspension,
                ReasonForAbsence::REASON_VACATION   =>  $absentVacation,
                ReasonForAbsence::REASON_DISASTER   =>  $absentDisaster
            ];
            $sumAbsentCorona += $absentCorona;
            $sumAbsentPrivate += $absentPrivate;
            $sumAbsentKibiki += $absentKibiki;
            $sumAbsentSick += $absentSick;
            $sumAbsentSuspension += $absentSuspension;
            $sumAbsentVacation += $absentVacation;
            $sumAbsentDisaster += $absentDisaster;

        }

        $data['children_stat']['extension_stat_sum'] = [
            'a' =>  $sumA,
            'b' =>  $sumB,
            'c' =>  $sumC,
            'd' =>  $sumD,
            'e' =>  $sumE,
        ];

        $data['children_stat']['absent_stat_sum'] = [
            ReasonForAbsence::REASON_CORONA =>  $sumAbsentCorona,
            ReasonForAbsence::REASON_PRIVATE    =>  $sumAbsentPrivate,
            ReasonForAbsence::REASON_KIBIKI =>  $sumAbsentKibiki,
            ReasonForAbsence::REASON_SICK   =>  $sumAbsentSick,
            ReasonForAbsence::REASON_SUSPENSION =>  $sumAbsentSuspension,
            ReasonForAbsence::REASON_VACATION   =>  $sumAbsentVacation,
            ReasonForAbsence::REASON_DISASTER   =>  $sumAbsentDisaster,
        ];

        $childrenClasses = ChildrenClass::get();

        $childTable = [];
        foreach ($childrenClasses as $childrenClass)
        {
            $classChildren = $children->filter(function ($value, $key) use ($childrenClass) {
                return $value->class_id === $childrenClass->id;
            });
            $childTable[$childrenClass->id] = [];
            foreach ($classChildren as $child)
            {
                [$attendCount, $absentCount] = $this->attendanceService->countMonthlyAttend($child, $date);
                $exitDate = Carbon::parse($child->exit_date);
                if ($child->exit_date && $exitDate->format('Y-m') === $date)
                {
                    $currentExitDate = $exitDate->format('Y/m/d');
                } else {
                    $currentExitDate = '';
                }
                $child_info = $child->getChildInfoByMonthForApplication($date);

                $childItem = [
                    'number'    =>  $child->number,
                    'name'      =>  $child_info->name ?? $child->name,
                    'birthday'  =>  $child->birthday ? Carbon::parse($child->birthday)->format('Y-m-d') : '',
                    'type'      =>  $this->getChildTypeLabel($child_info, $date),
                    'admission_date'    =>  $child->admission_date,
                    'company_name'  =>  $child_info->company_name??'',
                    'free_of_charge' => ($child_info->free_of_charge ?? null) ? '対象' : '対象外',
                    'certificate_of_payment'    =>  ($child_info->certificate_of_payment ?? null) ? '有り' : '無し',
                    'certificate_expiration_date'=> ($child_info->certificate_expiration_date ?? null) ? Carbon::parse($child_info->certificate_expiration_date)->format('Y-m-d') : '' ,
                    'tax_exempt_household'      =>  ($child_info->tax_exempt_household ?? '') ? '〇' : '',
                    'attend_count'              =>  $attendCount,
                    'absent_count'              =>  $absentCount,
                    'exit_date'  => $currentExitDate,
                    'remarks'   =>  $child_info->remarks ?? ''
                ];
                $childAttendances = $attendances->filter(function ($value, $key) use ($child){
                    return $value->child_id === $child->id;
                });

                $extensionState = [];
                $absentState = [
                    ReasonForAbsence::REASON_CORONA =>  0,
                    ReasonForAbsence::REASON_PRIVATE    =>  0,
                    ReasonForAbsence::REASON_KIBIKI =>  0,
                    ReasonForAbsence::REASON_SICK   =>  0,
                    ReasonForAbsence::REASON_SUSPENSION =>  0,
                    ReasonForAbsence::REASON_VACATION   =>  0,
                    ReasonForAbsence::REASON_HOLIDAY   =>  0,
                    ReasonForAbsence::REASON_DISASTER   =>  0,
                ];

                $excludes_absent_reasons = [
                    ReasonForAbsence::REASON_CORONA,
                    ReasonForAbsence::REASON_SICK,
                    ReasonForAbsence::REASON_DISASTER
                ];

                $exclude_absent_count = 0;

                $regulation_days = 0;
                for ($i = 1; $i <= $daysInMonth; $i++)
                {
                    $attendance = $childAttendances->firstWhere('day', $i);
                    if ($attendance)
                    {
                        if ($attendance->extension_in_minute > 90) $extensionState[$i] = 'E';
                        else if ($attendance->extension_in_minute > 60) $extensionState[$i] = 'D';
                        else if ($attendance->extension_in_minute > 30) $extensionState[$i] = 'C';
                        else if ($attendance->extension_in_minute > 0) $extensionState[$i] = 'B';
                        else $extensionState[$i] = 'A';
                        $exclude_absent = in_array($attendance->reason_for_absence_id, $excludes_absent_reasons);
                        if ($attendance->reason_for_absence_id)
                        {
                            $extensionState[$i] = $attendance->reason_for_absence->ruby;
                            $absentState[$attendance->reason_for_absence_id]++;
                        }
                        if ($attendance->commuting_time || $exclude_absent)
                        {
                            $regulation_days++;
                        }
                        if ($exclude_absent || $attendance->reason_for_absence_id === ReasonForAbsence::REASON_HOLIDAY)
                        {
                            $exclude_absent_count++;
                        }

                    } else {
                        $extensionState[$i] = '';
                    }
                }
                $childItem['extension_state'] = $extensionState;
                $childItem['absent_state'] = $absentState;
                $childItem['regulation_days'] = $regulation_days;
                $childItem['attend_count'] = $attendCount;
                $childItem['absent_count'] = $absentCount - $exclude_absent_count;

                $childTable[$childrenClass->id][] = $childItem;
            }
        }
        $data['children_table'] = $childTable;


        return $data;

    }
    private function getChildTypeLabel($child_info)
    {
        if (!$child_info) return '';
        if (!$child_info->type) return '';
        $type = $child_info->type;

        $childType = $this->childTypes->first(function ($value, $key) use ($type){
            return $value->key == $type;
        });
        if (!$childType) return '';
        return $childType->value;
    }

}
