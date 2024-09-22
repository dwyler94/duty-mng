<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\Year;
use App\Services\Processors\AttendanceApplicationProcessor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class ApplicationController extends BaseController
{
    public function create(ApplicationRequest $request)
    {
        $currentUser = auth()->user();

        $data = $request->validated();
        if (empty($data['attendance_id']) && empty($data['application_date'])) {
            return $this->sendError("Please input date or select attendance", [], 422);
        }

        if (
            (
                empty($data['time_before_correction']) &&
                !empty($data['time_after_correction'])
            )
            ||
            (
                !empty($data['time_before_correction']) &&
                empty($data['time_after_correction'])
            )
        ) {
            return $this->sendError("Invalid time correction", [], 422);
        }

        $attendance = null;
        if (!empty($data['attendance_id'])) {
            $attendance = Attendance::where(['id' => $data['attendance_id'], 'user_id' => $currentUser->id])->first();
        }
        if (!$attendance) {
            $applicationDate = Carbon::parse($data['application_date']);
            $monthValue = $applicationDate->year * 100 + $applicationDate->month;
            $year = Year::where([
                ['start', '<=', $monthValue],
                ['end', '>=', $monthValue]
            ])->first();
            $attendance = Attendance::where([
                'year_id'   =>  $year->id,
                'month'     =>  $applicationDate->month,
                'day'       =>  $applicationDate->day,
                'user_id'   =>  $currentUser->id
            ])->first();
            if (!$attendance) {
                $attendance = new Attendance([
                    'year_id'   =>  $year->id,
                    'month'     =>  $applicationDate->month,
                    'day'       =>  $applicationDate->day,
                    'day_of_week'=> $applicationDate->dayOfWeek
                ]);
                $attendance->user()->associate($currentUser);
                $attendance->save();
            }
        }

        $application = new Application([
            'attendance_id'         =>  $attendance->id,
            'application_class_id'  =>  $data['application_class_id']??null,
            'reason'                =>  $data['reason']??null,
            'time_before_correction'=>  $data['time_before_correction']??null,
            'time_after_correction' =>  $data['time_after_correction']??null,
        ]);
        $application->application_datetime = Carbon::now();
        $application->create_user_id = $currentUser->id;
        $application->user()->associate($currentUser);
        $application->save();
        return $this->sendResponse($application);
    }
    public function update(Application $application, ApplicationRequest $request)
    {
        $currentUser = auth()->user();
        if (!Gate::allows('update-application', $application)) {
            return abort(403, "You are not allowed");
        }
        $data = $request->validated();
        if ($application->attendance_id !== $data['attendance_id']) {
            return abort(403, 'attendance not matched');
        }
        $application->fill($request->validated());
        $application->update_user_id = $currentUser->id;
        $application->save();
        return $this->sendResponse($application);
    }
    public function approve(Application $application, AttendanceApplicationProcessor $applicationProcessor)
    {
        if (!Gate::allows('approve-application', $application)) {
            return abort(403, "You are not allowed");
        }
        $currentUser = auth()->user();
        if ($application->is_approved) return $this->sendError("Already approved", [], 404);
        if ($application->is_rejected) return $this->sendError("Already rejected", [], 404);

        if (!$application->attendance)
        {
            abort(404, "This application is not attached to the attendance");
        }
        if ($application->attendance->is_approved)
        {
            abort(404, "This item is already approved");
        }
        $attendance = $application->attendance;
        $application->approval_datetime = Carbon::now();
        $application->status = Application::STATUS_APPROVED;
        $application->update_user_id = $currentUser->id;

        if (!$applicationProcessor->process($attendance, $application))
        {
            abort(404, $applicationProcessor->getError());
        }
        $application->save();

        return $this->sendResponse($application);
    }

    public function reject(Application $application)
    {
        if (!Gate::allows('approve-application', $application)) {
            return abort(403, "You are not allowed");
        }
        $currentUser = auth()->user();
        if ($application->is_approved) return $this->sendError("Already approved", [], 404);
        if ($application->is_rejected) return $this->sendError("Already rejected", [], 404);
        $application->approval_datetime = Carbon::now();
        $application->status = Application::STATUS_REJECTED;
        $application->update_user_id = $currentUser->id;
        $application->save();

        return $this->sendResponse($application);
    }
}
