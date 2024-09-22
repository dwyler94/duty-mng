<?php

namespace App\Http\Controllers\API\V1\Child;

use App\Exports\ChildDiaryExport;
use App\Http\Controllers\API\V1\BaseController;
use App\Http\Requests\Child\ChildcareDiaryQuery;
use App\Http\Requests\Child\ChildcareDiaryRequest;
use App\Models\Child;
use App\Models\ChildcareDiary;
use App\Models\ChildrenAttendence;
use App\Models\ChildrenClass;
use App\Models\ChildrenClassPeriod;
use Laravel\Sanctum\PersonalAccessToken;
use Maatwebsite\Excel\Facades\Excel;

class ChildcareDiaryController extends BaseController
{
    public function save(ChildcareDiaryRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        $diary = ChildcareDiary::where([
            'office_id'         =>  $user->office_id,
            'children_class_id' => $data['children_class_id'],
            'date'              =>  $data['date']
        ])->first();

        if (!$diary)
        {
            $diary = new ChildcareDiary(['office_id' => $user->office_id]);
            $diary->create_user_id = $user->id;
        } else {
            $diary->update_user_id = $user->id;
        }
        $diary->fill($data);
        $diary->save();

        return $this->sendResponse($diary);
    }
    public function retrieve(ChildcareDiaryQuery $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        $diary = ChildcareDiary::where([
            'office_id'     =>  $user->office_id,
            'children_class_id'=> $data['children_class_id'],
            'date'          =>  $data['date']
        ])->first();

        if (!$diary)
        {
            $diary = new ChildcareDiary();
        }
        return $this->sendResponse($diary);
    }

    public function excel(ChildcareDiaryQuery $request)
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
        $diary = ChildcareDiary::where([
            'office_id'     =>  $currentUser->office_id,
            'children_class_id'=> $data['children_class_id'],
            'date'          =>  $data['date']
        ])->first();

        if (!$diary)
        {
            $diary = new ChildcareDiary();
        }


        $childQb = Child::where(['office_id' => $currentUser->office_id])
            ->where(function($query) use ($data) {
            $query->whereNull('exit_date')
                ->orWhere('exit_date', '>=', $data['date']);
            })
            ->where(function($query) use ($data) {
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
        $stat = [
            'all'   =>  $allCount,
            'attend'=>  $attendanceCount,
            'absent'=>  $absentCount
        ];
        $title = $currentUser->office->name . '_保育日誌_' . $data['children_class_id'] . '_' . $data['date'];
        return Excel::download(new ChildDiaryExport($currentUser->office, $diary, $data['children_class_id'], $stat), $title . '.xlsx');
    }
}
