<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\WorkingHourQuery;
use App\Http\Requests\WorkingHourRequest;
use App\Http\Requests\WorkingHourStatusRequest;
use App\Models\User;
use App\Models\WorkingHour;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class WorkingHourController extends BaseController
{
    public function get(WorkingHourQuery $query)
    {
        $data = $query->validated();

        $qb = WorkingHour::whereRaw('1=1');
        if (!empty($data['employment_status_id'])) {
            $qb->where([
                ['employment_status_id', '=', $data['employment_status_id']]
            ]);
        }
        if (!empty($data['office_id'])) {
            $qb->where(['office_id' => $data['office_id']]);
        }
        $workingHours = $qb->get();
        return $this->sendResponse($workingHours);
    }
    public function create(WorkingHourRequest $request)
    {
        $data = $request->validated();
        $startTime = $data['start_time'];
        $endTime = $data['end_time'];

        if ($startTime > $endTime) {
            return $this->sendError(trans('working_hours.start_gt_end'));
        }
        $workingHour = WorkingHour::create($data);
        return $this->sendResponse($workingHour);
    }
    public function update(WorkingHour $workingHour, WorkingHourRequest $request)
    {
        $data = $request->validated();
        $startTime = $data['start_time'];
        $endTime = $data['end_time'];

        if ($startTime > $endTime) {
            return $this->sendError(trans('working_hours.start_gt_end'));
        }
        $workingHour->fill($data);
        $workingHour->save();
        return $this->sendResponse($workingHour);
    }
    public function updateStatus(WorkingHour $workingHour, WorkingHourStatusRequest $request)
    {
        $data = $request->validated();
        $enable = $data['enable'];
        $workingHour->enable = $enable;
        $workingHour->save();
        return $this->sendResponse($workingHour);
    }
    public function delete(WorkingHour $workingHour)
    {
        $workingHour->delete();
        return $this->sendResponse();
    }


    public function getWorkingHourWithUser(User $user)
    {
        $currentUser = auth()->user();
        if (!Gate::forUser($currentUser)->allows('get-user-working-hours', $user)) {
            abort(403, trans("You are note allowed"));
        }
        $workingHours = WorkingHour::where([
            'office_id'             =>  $user->office->id,
            'employment_status_id'  =>  $user->employment_status_id,
            'enable'                =>  true
        ])->get();
        return $this->sendResponse($workingHours);
    }
}
