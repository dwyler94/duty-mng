<?php

namespace App\Services\Child;

use App\Models\Child;
use App\Models\ChildcarePlan;
use App\Models\ChildcarePlanDay;
use App\Models\ReasonForAbsence;
use Illuminate\Support\Carbon;

class PlanService
{
    protected $holidayService;

    protected $wPlans;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    public function setWeeklyPlan($wPlans)
    {
        $this->wPlans = $wPlans;
    }
    public function createPlanDaysFromWeeklyPlan(Child $child, $month, $createUserId = null)
    {
        $this->holidayService->spanHolidaysInMonth($month);

        if (!$this->wPlans) {
            $wPlans = ChildcarePlan::where(['child_id' => $child->id])->get();
        } else {
            $wPlans = $this->wPlans;
        }
        $date = Carbon::parse($month . '-01');
        $daysInMonth = $date->daysInMonth;

        $dPlans = [];
        for ($i = 1; $i <= $daysInMonth; $i++)
        {
            $date->day($i);
            $dPlan = new ChildcarePlanDay(['date' => $date->format('Y-m-d'), 'child_id' => $child->id]);
            if ($createUserId)
            {
                $dPlan->create_user_id = $createUserId;
            }
            $dayOfWeek = $date->dayOfWeek;
            // check if holiday
            $isHoliday = $this->holidayService->checkIfHoliday($date);

            $wPlan = $wPlans->first(function($value, $key) use ($dayOfWeek, $isHoliday){
                if ($isHoliday) {
                    return in_array($dayOfWeek, $value->day_of_weeks) && !$value->excluding_holidays;
                }
                return  in_array($dayOfWeek, $value->day_of_weeks);
            });
            if ($wPlan)
            {
                $dPlan->start_time = $wPlan->start_time;
                $dPlan->end_time = $wPlan->end_time;
                $dPlan->absent_id = null;
            } else if ($isHoliday || $dayOfWeek === 0) {
                $dPlan->absent_id = ReasonForAbsence::REASON_HOLIDAY;
            }
            $dPlans[] = $dPlan;
        }
        return $dPlans;
    }
}
