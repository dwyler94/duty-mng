<?php

namespace App\Jobs;

use App\Models\Child;
use App\Models\ChildcarePlan;
use App\Models\ChildcarePlanDay;
use App\Services\Child\PlanService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PlanDayCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $childId;
    protected $userId;
    protected $initial;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($childId, $userId, $initial)
    {
        //
        $this->childId = $childId;
        $this->userId = $userId;
        $this->initial = $initial;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PlanService $planService)
    {
        //
        $child = Child::where(['id' => $this->childId])->first();
        if (!$child) return;
        $plans = ChildcarePlan::where(['child_id' => $child->id])->get();
        if ($plans->count() === 0) return;

        $planService->setWeeklyPlan($plans);
        if ($this->initial)
        {
            if (!$child->admission_date || Carbon::now()->greaterThan(Carbon::parse($child->admission_date))) {
                $date = Carbon::now();
            } else {
                $date = Carbon::parse($child->admission_date);
            }
        } else {
            $date = Carbon::now();
            $date->addMonth(1);
        }

        $availablePlans = [];
        for ($i = 0; $i < 72; $i++)
        {
            $month = $date->format('Y-m');
            $child->dailyPlans()->whereMonth('date', $date->month)->whereYear('date', $date->year)->delete();
            $dPlans = $planService->createPlanDaysFromWeeklyPlan($child, $month, $this->userId);
            foreach ($dPlans as $dPlan)
            {
                if (!$dPlan->start_time && !$dPlan->end_time && $dPlan->absent_id === null)
                {
                    continue;
                }
                $availablePlans[] = $dPlan;
            }
            $date->addMonth(1);
        }
        if (count($availablePlans)) {
            $child->dailyPlans()->saveMany($availablePlans);
        }
    }
}
