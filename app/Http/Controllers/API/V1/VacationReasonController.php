<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\PageQuery;
use App\Http\Requests\ReasonForVacationEnableRequest;
use App\Http\Requests\ReasonForVacationRequest;
use App\Http\Requests\VacationReasonQuery;
use App\Models\ReasonForVacation;
use App\Models\User;

class VacationReasonController extends BaseController
{
    public function get(VacationReasonQuery $request)
    {
        $data = $request->validated();
        if (!empty($data['name']))
        {
            $reasons = ReasonForVacation::where('name', 'LIKE', '%' . $data['name'] . '%')->get();
        } else {
            $reasons = ReasonForVacation::get();
        }
        return $this->sendResponse($reasons);
    }

    public function getEnable()
    {
        $data = ReasonForVacation::where(['enable' => true])->get();
        return $this->sendResponse($data);
    }
    public function create(ReasonForVacationRequest $request)
    {
        $data = $request->validated();
        $name = $data['name'];
        if (ReasonForVacation::where(['name' => $name])->count() > 0)
        {
            return $this->sendError(trans("すでに使用されている名前です。"));
        }
        ReasonForVacation::create($data);
        return $this->sendResponse($data);
    }
    public function update(ReasonForVacation $reasonForVacation, ReasonForVacationRequest $request)
    {
        $data = $request->validated();
        $name = $data['name'];
        $existing = ReasonForVacation::where(['name' => $name])->first();
        if ($existing && $existing->id !== $reasonForVacation->id)
        {
            return $this->sendError(trans("すでに使用されている名前です。"));
        }
        $reasonForVacation->fill($data);
        $reasonForVacation->save();
        return $this->sendResponse($reasonForVacation);
    }
    public function updateStatus(ReasonForVacation $reasonForVacation, ReasonForVacationEnableRequest $request)
    {
        $data = $request->validated();
        $reasonForVacation->enable = $data['enable'];
        $reasonForVacation->save();
        return $this->sendResponse();
    }

    public function delete(ReasonForVacation $reasonForVacation)
    {
        $reasonForVacation->delete();
        return $this->sendResponse();
    }
}
