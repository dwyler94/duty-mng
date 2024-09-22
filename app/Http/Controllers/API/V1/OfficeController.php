<?php

namespace App\Http\Controllers\API\V1;

use App\Constants\Roles;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OfficeMasterRequest;
use App\Http\Requests\OfficeQuery;
use App\Http\Requests\ScheduledWorkingRequest;
use App\Http\Resources\OfficeResource;
use App\Models\Office;
use App\Models\OfficeInformation;
use App\Models\ScheduledWorking;
use App\Models\User;
use App\Models\Year;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class OfficeController extends BaseController
{
    public function get(OfficeQuery $request)
    {
        $data = $request->validated();

        $qb = Office::with('office_information')->whereRaw('1=1');
        if (!empty($data['region_id']))
        {
            $qb->where(['region_id' => $data['region_id']]);
        }
        if (!empty($data['name']))
        {
            $qb->where('name', 'LIKE', '%' . $data['name'] . '%');
        }

        if (!empty($data['page']))
        {
            $size = $data['size']??100;
            $offices = $qb->paginate($size);
            return $this->sendResponse([
                'current_page'  =>  $offices->currentPage(),
                'per_page'      =>  $offices->perPage(),
                'total'         =>  $offices->total(),
                'data'          =>  OfficeResource::collection($offices->items())
            ]);
        } else {
            $offices = $qb->get();
            return $this->sendResponse(OfficeResource::collection($offices));
        }
    }
    public function create(OfficeMasterRequest $request)
    {
        $data = $request->validated();
        $number = $data['number'];
        $name = $data['name'];

        $existing = Office::where(['number' => $number])->orWhere(['name' => $name])->first();
        if ($existing) {
            return abort(422, "事業所No又は事業所名が既に存在しています。");
        }


        $office = Office::create($data);

        if ($office->type === Office::TYPE_NURSERY)
        {
            $officeInformation = new OfficeInformation($data);
            $officeInformation->start_date = Carbon::now();
            $office->office_information()->save($officeInformation);
        }
        return $this->sendResponse(new OfficeResource($office));
    }

    public function update(Office $office, OfficeMasterRequest $request)
    {
        $data = $request->validated();
        $number = $data['number'];
        $name = $data['name'];
        $existing = Office::where(['number' => $number])->orWhere(['name' => $name])->first();
        if ($existing && $existing->id !== $office->id) {
            return abort(422, "事業所No又は事業所名が既に存在しています。");
        }

        $office->fill($data);
        $office->save();

        if ($office->type === Office::TYPE_NURSERY)
        {
            $infoChanged = $this->isOfficeInformationChanged($office->office_information, $data);
            if ($office->office_information && $infoChanged)
            {
                $office->office_information->end_date = Carbon::now()->subDays(1);
                $office->office_information->save();
            }
            if ($infoChanged)
            {
                $officeInformation = new OfficeInformation();
                $officeInformation->fill($data);
                $officeInformation->start_date = Carbon::now();
                $office->office_information()->save($officeInformation);
            }
        }
        return $this->sendResponse(new OfficeResource($office));
    }

    private function isOfficeInformationChanged($officeInformation, $data)
    {
        if (!$officeInformation) return true;
        $open_time = !empty($data['open_time']) ? ($data['open_time'] . ':00') : null;
        $close_time = !empty($data['close_time']) ? ($data['close_time'] . ':00') : null;
        $open_time_short = !empty($data['open_time_short']) ? ($data['open_time_short'] . ':00') : null;
        $close_time_short = !empty($data['close_time_short']) ? ($data['close_time_short'] . ':00') : null;

        if ($officeInformation->open_time !== $open_time) return true;
        if ($officeInformation->close_time !== $close_time) return true;
        if ($officeInformation->open_time_short !== $open_time_short) return true;
        if ($officeInformation->close_time_short !== $close_time_short) return true;
        if ($officeInformation->capacity !== ($data['capacity']??null)) return true;
        if ($officeInformation->appropriate_number_0 !== ($data['appropriate_number_0']??null)) return true;
        if ($officeInformation->appropriate_number_1 !== ($data['appropriate_number_1']??null)) return true;
        if ($officeInformation->appropriate_number_2 !== ($data['appropriate_number_2']??null)) return true;
        if ($officeInformation->appropriate_number_3 !== ($data['appropriate_number_3']??null)) return true;
        if ($officeInformation->appropriate_number_4 !== ($data['appropriate_number_4']??null)) return true;
        if ($officeInformation->appropriate_number_5 !== ($data['appropriate_number_5']??null)) return true;
        if ($officeInformation->business_type_id !== ($data['business_type_id']??null)) return true;
        return false;
    }

    public function getNurseryOffices()
    {
        return $this->sendResponse(Office::where(['type' => Office::TYPE_NURSERY])->get());
    }

    public function delete(Office $office)
    {
        $office->delete();
        return $this->sendResponse();
    }

    public function getScheduleWorkings(Office $office)
    {
        $now = Carbon::now();
        $currentYearValue = $now->year * 100 + $now->month;
        $nextYearValue = $currentYearValue + 100;

        $currentYear = Year::where([
            ['start', '<=', $currentYearValue],
            ['end', '>=', $currentYearValue]
        ])->first();
        $nextYear = Year::where([
            ['start', '<=', $nextYearValue],
            ['end', '>=', $nextYearValue]
        ])->first();
        $currentYearScheduleValues = ScheduledWorking::where([
            ['year_id', '=', $currentYear->id],
            ['office_id', '=',  $office->id]
        ])->orderBy('month')->get()->toArray();
        $nextYearScheduleValues = ScheduledWorking::where([
            ['year_id' , '=', $nextYear->id],
            ['office_id', '=',  $office->id]
        ])->get()->toArray();

        $currentYearSchedules = $this->formatScheduleWorkings($currentYearScheduleValues, $currentYear);
        $nextYearSchedules = $this->formatScheduleWorkings($nextYearScheduleValues, $nextYear);

        return $this->sendResponse([
            'current'   =>  $currentYearSchedules,
            'next'      =>  $nextYearSchedules
        ]);
    }

    public function getScheduledWorkingMonth(Office $office, Request $request)
    {
        $currentUser = auth()->user();
        if (!Gate::forUser($currentUser)->allows('get-scheduled-working-office', $office)) {
            abort(403);
        }

        $monthReq = $request->input('month');
        if (!$monthReq) {
            return abort(422, 'Month is required');
        }
        $monthReq = (int) $monthReq;
        $year = Year::where([
            ['start', '<=', $monthReq],
            ['end', '>=', $monthReq]
        ])->first();

        if (!$year) {
            return abort(404, 'Year data is empty');
        }

        $month = $monthReq % 100;
        $scheduledWorking = ScheduledWorking::where(['office_id' => $office->id, 'year_id' => $year->id, 'month' => $month])->first();
        return $this->sendResponse($scheduledWorking);
    }
    public function saveScheduleWorking(Office $office, ScheduledWorkingRequest $request)
    {
        $data = $request->validated();
        $scheduledWorkings = ScheduledWorking::where([
            ['year_id', '=', $data['year_id']],
            ['office_id', '=', $office->id]
        ])->get();

        $schedules = $data['schedules'];
        foreach ($schedules as $schedule)
        {
            $scheduleWorking = Arr::first($scheduledWorkings, function($value, $key) use ($schedule) {
                return $value->month === $schedule['month'];
            });
            if ($scheduleWorking) {
                $scheduleWorking->days = $schedule['days']??null;
                $scheduleWorking->save();
            } else {
                $scheduleWorking = ScheduledWorking::create([
                    'year_id'   =>  $data['year_id'],
                    'month'     =>  $schedule['month'],
                    'days'      =>  $schedule['days']??null,
                    'office_id' =>  $office->id,
                ]);
            }
        }
        return $this->sendResponse();
    }

    public function getUserCabableOffices()
    {
        $currentUser = auth()->user();
        if (!Gate::forUser($currentUser)->allows('get-offices')) {
            abort(403);
        }
        $qb = Office::whereRaw('1=1');
        if ($currentUser->role_id === Roles::REGION_MANAGER) {
            $qb->where(['region_id' => $currentUser->office->region->id]);
        } else if ($currentUser->role_id === Roles::OFFICE_MANAGER || $currentUser->role_id === Roles::USER_A) {
            $qb->where(['id'    =>  $currentUser->office->id]);
        }
        $offices = $qb->get();
        return $this->sendResponse($offices);
    }

    private function formatScheduleWorkings($scheduleWorkingValues, $year)
    {
        $yearStartMonth = $year->start % 100;
        $yearStartYear = floor($year->start / 100);
        $yearEndYear = floor($year->end / 100);

        $yearSchedules = [];
        for ($i = 0; $i < 12; $i++)
        {
            $month = ($yearStartMonth + $i - 1) % 12 + 1;
            $scheduleWorking = Arr::first($scheduleWorkingValues, function($value, $key) use ($month) {
                return $value['month'] === $month;
            });
            if (!$scheduleWorking) {
                $scheduleWorking = [
                    'year_id'   =>  $year->id,
                    'month'     =>  $month,
                ];
            }
            if ($month >= $yearStartMonth) {
                $scheduleWorking['year'] = $yearStartYear;
            } else {
                $scheduleWorking['year'] = $yearEndYear;
            }

            $yearSchedules[] = $scheduleWorking;
        }
        return $yearSchedules;
    }
}
