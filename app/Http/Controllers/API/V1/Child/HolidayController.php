<?php

namespace App\Http\Controllers\API\V1\Child;

use App\Http\Controllers\API\V1\BaseController;
use App\Http\Requests\Child\HolidayQuery;
use App\Models\Holiday;

class HolidayController extends BaseController
{
    public function get(HolidayQuery $request)
    {
        [$year, $month] = explode('-', $request->validated()['month']);

        return $this->sendResponse(Holiday::whereYear('date', $year)->whereMonth('date', $month)->get());
    }
}
