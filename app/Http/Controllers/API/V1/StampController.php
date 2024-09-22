<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Attendance;
use App\Models\StampLog;
use App\Services\AttendancePipleline;
use App\Services\StampService;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StampController extends BaseController
{
    protected $stampService;
    protected $attendancePipeline;

    public function __construct(
        StampService $stampService,
        AttendancePipleline $attendancePipeline
    ) {
        $this->stampService = $stampService;
        $this->attendancePipeline = $attendancePipeline;
    }
    public function commute(Request $request)
    {
        $user = auth()->user();
        if (config('app.env') === 'local' && $request->has('stamp')) {
            $stamp = Carbon::parse($request->input('stamp'));
        } else {
            $stamp = Carbon::now();
        }
        if (!$this->stampService->checkStampRate($user, $stamp)) {
            return $this->sendError("Stamp rate limited");
        }

        Log::info("[user " . $user->id . "] trying to commute stamp");
        $stampLog = StampLog::create([
            'user_id'   =>  $user->id,
            'type'      =>  Attendance::TYPE_COMMUTE
        ]);

        $result = $this->stampService->commute($user, $stamp);
        if (!$result) {
            $error = $this->stampService->getError();
            Log::error("[user " . $user->id . "] commute stamp error : " . $error);
            $stampLog->result = StampLog::RESULT_FAILED;
            $stampLog->message = $error;
            $stampLog->save();
            return $this->sendError($error);
        }
        $attendance = $result['attendance'];
        $number = $result['number'];
        Log::info("[user " . $user->id . "] commute stamp number : " . $number);

        if (!$attendance->id) {
            $attendance->create_user_id = $user->id;
        } else {
            $attendance->update_user_id = $user->id;
        }
        $this->attendancePipeline->process($attendance);
        $attendance->save();
        $stampLog->result = StampLog::RESULT_SUCCESS;
        $stampLog->save();
        return $this->sendResponse();
    }

    public function leave(Request $request)
    {
        $user = auth()->user();
        if (config('app.env') === 'local' && $request->has('stamp')) {
            $stamp = Carbon::parse($request->input('stamp'));
        } else {
            $stamp = Carbon::now();
        }

        if (!$this->stampService->checkStampRate($user, $stamp)) {
            return $this->sendError("Stamp rate limited");
        }
        Log::info("[user " . $user->id . "] trying to leave stamp");

        $stampLog = StampLog::create([
            'user_id'   =>  $user->id,
            'type'      =>  Attendance::TYPE_LEAVE
        ]);

        $attendance = $this->stampService->leave($user, $stamp);
        if (!$attendance) {
            $error = $this->stampService->getError();
            Log::error("[user " . $user->id . "] commute stamp error : " . $error);
            $stampLog->result = StampLog::RESULT_FAILED;
            $stampLog->message = $error;
            $stampLog->save();
            return $this->sendError($error);
        }
        $this->attendancePipeline->process($attendance);
        $attendance->save();
        return $this->sendResponse();
    }
    public function status(Request $request)
    {
        $user = auth()->user();
        if (config('app.env') === 'local' && $request->has('stamp')) {
            $stamp = Carbon::parse($request->input('stamp'));
        } else {
            $stamp = Carbon::now();
        }
        $commuteEnabled = true;
        $result = $this->stampService->commute($user, $stamp);
        if (!$result) {
            $commuteEnabled = false;
        }

        $leaveEnabled = true;
        $result = $this->stampService->leave($user, $stamp);
        if (!$result) {
            $leaveEnabled = false;
        }
        return $this->sendResponse([
            'commuteEnabled'    =>  $commuteEnabled,
            'leaveEnabled'      =>  $leaveEnabled
        ]);
    }
}
