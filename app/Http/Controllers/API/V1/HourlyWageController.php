<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\HourlyWageQuery;
use App\Http\Requests\HourlyWageRequest;
use App\Models\HourlyWage;

class HourlyWageController extends BaseController
{
    public function get(HourlyWageQuery $request)
    {
        $data = $request->validated();
        if (!empty($data['office_id']))
        {
            $hourlyWages = HourlyWage::where(['office_id' => $data['office_id']])->get();
        } else {
            $hourlyWages = HourlyWage::get();
        }
        return $this->sendResponse($hourlyWages);
    }
    public function create(HourlyWageRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        $hourlyWage = new HourlyWage;
        $hourlyWage->fill($data);
        $hourlyWage->create_user_id = $user->id;
        $hourlyWage->save();
        return $this->sendResponse($hourlyWage);
    }
    public function update(HourlyWage $hourlyWage, HourlyWageRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        $hourlyWage->fill($data);
        $hourlyWage->update_user_id = $user->id;
        $hourlyWage->save();
        return $this->sendResponse($hourlyWage);
    }
    public function delete(HourlyWage $hourlyWage)
    {
        $hourlyWage->delete();
        return $this->sendResponse();
    }
}
