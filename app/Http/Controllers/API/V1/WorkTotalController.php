<?php

namespace App\Http\Controllers\API\V1;

use App\Constants\Roles;
use App\Exports\AttendanceTotalExport;
use App\Exports\IndividualSummaryExport;
use App\Http\Requests\WorkTotal\IndividualCsvRequest;
use App\Http\Requests\WorkTotal\WorkTotalCsvRequest;
use App\Http\Requests\WorkTotal\WorkTotalQuery;
use App\Models\AttendanceTotal;
use App\Models\Office;
use App\Models\User;
use App\Models\Year;
use App\Services\AttendanceTotalService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\PersonalAccessToken;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class WorkTotalController extends BaseController
{
    public function get(WorkTotalQuery $request, AttendanceTotalService $attendanceTotalService)
    {
        $currentUser = auth()->user();
        $data = $request->validated();
        if (!empty($data['office_id']))
        {
            $office = Office::where(['id' => $data['office_id']])->first();
            if (!Gate::forUser($currentUser)->allows('get-office-work-total', $office)) {
                abort(403, "You are not allowed");
            }
        }

        if (isset($office))
        {
            $officeIds = [$office->id];
        } else {
            $qb = Office::whereRaw('1=1');
            if ($currentUser->role_id === Roles::REGION_MANAGER) {
                $qb->where(['region_id' => $currentUser->office->region->id]);
            } else if ($currentUser->role_id === Roles::OFFICE_MANAGER || $currentUser->role_id === Roles::USER_A) {
                $qb->where(['id'    =>  $currentUser->office->id]);
            } else if ($currentUser->role_id === Roles::USER_B) {
                abort(403, "You are not allowed");
            }
            $officeIds = $qb->get()->pluck('id')->toArray();
        }

        if (!empty($data['retire_included']))
        {
            $users = User::whereIn('office_id', $officeIds)->get();
        } else {
            $users = User::whereIn('office_id', $officeIds)->where(['enrolled' => true])->get();
        }

        $totals = [];
        foreach($users as $user)
        {
            [$attendances, $attendanceTotal, $attendanceMetaItems] = $attendanceTotalService->calculateAttendanceTotal($user, $data['month']);
            $item = $user->toArray();
            $item['total'] = $attendanceTotal;

            $totals[] = $item;
        }
        return $this->sendResponse($totals);
    }
    public function csvAvailable(WorkTotalCsvRequest $request)
    {
        $currentUser = auth()->user();

        $data = $request->validated();
        if (!empty($data['office_id']))
        {
            $office = Office::where(['id' => $data['office_id']])->first();
            if (!Gate::forUser($currentUser)->allows('get-office-work-total', $office)) {
                abort(403, "You are not allowed");
            }
        }

        if (isset($office))
        {
            $offices = [$office];
            $officeIds = [$office->id];
        } else {
            $qb = Office::whereRaw('1=1');
            if ($currentUser->role_id === Roles::REGION_MANAGER) {
                $qb->where(['region_id' => $currentUser->office->region->id]);
            } else if ($currentUser->role_id === Roles::OFFICE_MANAGER || $currentUser->role_id === Roles::USER_A) {
                $qb->where(['id'    =>  $currentUser->office->id]);
            } else if ($currentUser->role_id === Roles::USER_B) {
                abort(403, "You are not allowed");
            }
            $offices = $qb->get();
            $officeIds = $offices->pluck('id')->toArray();
        }

        if (!empty($data['retire_included']))
        {
            $users = User::whereIn('office_id', $officeIds)->get();
        } else {
            $users = User::whereIn('office_id', $officeIds)->where(['enrolled' => true])->get();
        }


        $start = $data['start'];
        $end = $data['end']??$start;

        $userIds = $users->pluck('id');
        $attendanceTotals = AttendanceTotal::where([
            ['month_num', '>=', $start],
            ['month_num', '<=', $end]
        ])
        ->whereIn('user_id', $userIds)
        ->orderBy('user_id')
        ->orderBy('month_num')
        ->get();

        // check if user's attendance totals are all approved
        foreach($users as $user)
        {
            $createdAt = Carbon::parse($user->created_at)->format('Ym');
            $startMonth = $createdAt > $start ? $createdAt : $start;
            if ($startMonth > $end) continue;
            $months = Carbon::parse($startMonth . '01')->floatDiffInMonths($startMonth . '01');

            $approvedAttendances = $attendanceTotals->where('user_id', $user->id)->count();
            if ($approvedAttendances < $months + 1) {
                return $this->sendError("月間集計が承認されていない事業所があるため全ての事業所の一括出力ができません。");
            }
        }
        return $this->sendResponse();
    }
    public function csv(WorkTotalCsvRequest $request)
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

        $data = $request->validated();
        if (!empty($data['office_id']))
        {
            $office = Office::where(['id' => $data['office_id']])->first();
            if (!Gate::forUser($currentUser)->allows('get-office-work-total', $office)) {
                abort(403, "You are not allowed");
            }
        }

        if (isset($office))
        {
            $offices = [$office];
            $officeIds = [$office->id];
        } else {
            $qb = Office::whereRaw('1=1');
            if ($currentUser->role_id === Roles::REGION_MANAGER) {
                $qb->where(['region_id' => $currentUser->office->region->id]);
            } else if ($currentUser->role_id === Roles::OFFICE_MANAGER || $currentUser->role_id === Roles::USER_A) {
                $qb->where(['id'    =>  $currentUser->office->id]);
            } else if ($currentUser->role_id === Roles::USER_B) {
                abort(403, "You are not allowed");
            }
            $offices = $qb->get();
            $officeIds = $offices->pluck('id')->toArray();
        }

        if (!empty($data['retire_included']))
        {
            $users = User::whereIn('office_id', $officeIds)->get();
        } else {
            $users = User::whereIn('office_id', $officeIds)->where(['enrolled' => true])->get();
        }


        $start = $data['start'];
        $end = $data['end']??$start;

        $userIds = $users->pluck('id');
        $attendanceTotals = AttendanceTotal::where([
            ['month_num', '>=', $start],
            ['month_num', '<=', $end]
        ])
        ->whereIn('user_id', $userIds)
        ->orderBy('user_id')
        ->orderBy('month_num')
        ->get();

        // check if user's attendance totals are all approved
        foreach($users as $user)
        {
            $createdAt = Carbon::parse($user->created_at)->format('Ym');
            $startMonth = $createdAt > $start ? $createdAt : $start;
            if ($startMonth > $end) continue;
            $months = Carbon::parse($startMonth . '01')->floatDiffInMonths($startMonth . '01');

            $approvedAttendances = $attendanceTotals->where('user_id', $user->id)->count();
            if ($approvedAttendances < $months + 1) {
                return abort(404, "Not yet Approved");
            }
        }


        $totals = [];
        foreach ($attendanceTotals as $attendanceTotal)
        {
            $item = $attendanceTotal->toArray();
            $user = $users->firstWhere('id', $attendanceTotal->user_id);
            if ($user)
            {
                $item['user'] = $user;
            }
            $totals[] = $item;
        }
        $startYear = floor($start / 100);
        $startMonth = $start % 100;
        if (count($offices))
        {
            $title = $offices[0]->name . ' ' . $startYear . '年' . $startMonth . '月　';
        } else {
            $title = $startYear . '年' . $startMonth . '月　';
        }
        if (!empty($data['end']))
        {
            $endYear = floor($data['end']);
            $endMonth = $data['end'] % 100;
            $title .= '~ ' . $endYear . '年' . $endMonth . '月　勤務集計';
        }
        return Excel::download(new AttendanceTotalExport($totals, $title), $title . '.xlsx');
    }

    public function exportIndividual(IndividualCsvRequest $request, AttendanceTotalService $attendanceTotalService)
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
        $data = $request->validated();
        $user = User::where(['id' => $data['user_id']])->first();
        $office = $user->office;
        if (!$office || !Gate::forUser($currentUser)->allows('get-office-work-total', $office)) {
            abort(403, "You are not allowed");
        }

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
        $ext = $data['type'] === 'excel' ? '.xlsx' : '.csv';
        return Excel::download(new IndividualSummaryExport($attendanceItems, $user, $office, $data['month']), $user->name . $ext, $data['type'] === 'excel' ? ExcelExcel::XLSX : ExcelExcel::CSV);
        //->download($user->name . $ext, $data['type'] === 'excel' ? ExcelExcel::XLSX : ExcelExcel::CSV);
    }
}
