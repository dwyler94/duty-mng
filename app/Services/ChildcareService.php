<?php

namespace App\Services;

use App\Models\Child;
use App\Models\ChildcarePlanDay;
use App\Models\ChildrenClass;
use App\Models\Office;
use Illuminate\Support\Carbon;

class ChildcareService
{
    const TIME_PERIODS = [
        '07:00:00', '07:30:00','08:00:00','08:30:00','09:00:00','09:30:00','10:00:00','10:30:00','11:00:00','11:30:00',
        '12:00:00','12:30:00','13:00:00','13:30:00','14:00:00','14:30:00','15:00:00','15:30:00','16:00:00','16:30:00',
        '17:00:00','17:30:00','18:00:00','18:30:00','19:00:00','19:30:00','20:00:00','20:30:00','21:00:00','21:30:00', '22:00:00'
    ];

    public function calcWorkerNumberPerPeriod($shifts, $date)
    {
        $timePeriods = self::TIME_PERIODS;
        $len = count($timePeriods);

        // $dateString = "2021-11-08";
        $dateString = $date->format('Y-m-d');
        $result = [];
        for ($i = 0; $i < $len - 1; $i++)
        {
            $count = 0;
            foreach ($shifts as $shift)
            {
                if (!$shift->start_time || !$shift->end_time)
                {
                    continue;
                }
                $overlapped = calcOverlappedPeriod(
                    Carbon::parse($dateString . 'T' . $timePeriods[$i]),
                    Carbon::parse($dateString . 'T' . $timePeriods[$i + 1]),
                    Carbon::parse($shift->start_date_time),
                    Carbon::parse($shift->end_date_time),
                );
                if ($overlapped)
                {
                    $count++;
                }
            }
            $result[] = $count;
        }
        return $result;
    }

    public function getChildSchedule(Office $office, Carbon $date)
    {
        $children = Child::where(['office_id' => $office->id])
            ->where(function($query) use ($date) {
                $query->whereNull('exit_date')
                    ->orWhere('exit_date', '>=', $date);
            })
            ->where(function($query) use ($date) {
                $query->where('admission_date', '<=', $date)
                    ->orWhere('admission_date', '=', null);
            })
            ->get();

        $childrenPlans = [];
        foreach($children as $child)
        {
            $plan = ChildcarePlanDay::where(['child_id' => $child->id, 'date' => $date])->first();
            if ($plan && $plan->start_time && $plan->end_time)
            {
                $childrenPlans[] = [
                    'class_id'  =>  $child->class_id,
                    'plan' => $plan
                ];
            }
        }

        // calc overlap in 7:00 ~ 22:00
        $baseDate = clone $date;
        $baseDate->hour = 7;
        $baseDate->minute = 0;
        $baseDate->second = 0;

        $baseDateString = $baseDate->format('Y-m-d');

        $children0 = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $children1 = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $children2 = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $children3 = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $children4 = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $children5 = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        for($i = 0; $i < 30; $i++)
        {
            foreach($childrenPlans as $childPlan)
            {
                $plan = $childPlan['plan'];
                $class_id = $childPlan['class_id'];

                $plan_start_time = Carbon::parse($plan->start_date_time);
                $plan_end_time = Carbon::parse($plan->end_date_time);
                $baseEndDate = clone $baseDate;
                $baseEndDate->addMinutes(30);

                $overlapped = calcOverlappedPeriod($baseDate, $baseEndDate, $plan_start_time, $plan_end_time);
                if ($overlapped)
                {
                    switch($class_id)
                    {
                        case ChildrenClass::AGE_0: $children0[$i]++; break;
                        case ChildrenClass::AGE_1: $children1[$i]++; break;
                        case ChildrenClass::AGE_2: $children2[$i]++; break;
                        case ChildrenClass::AGE_3: $children3[$i]++; break;
                        case ChildrenClass::AGE_4: $children4[$i]++; break;
                        case ChildrenClass::AGE_5: $children5[$i]++; break;
                    }
                }
            }
            $baseDate->addMinutes(30);
        }

        // $children0 =
        //     [ 0, 6, 6, 6, 6, 6, 6, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 6, 6, 6, 6, 6, 6, 6, 0, 0, 0, 0];
        // $children1 =
        //     [ 0, 0, 0, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 0];
        // $children2 =
        //     [ 0, 2, 2, 2, 0, 0, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 0, 0, 4, 4, 4, 4, 4, 4, 4, 0, 0, 0, 0, 0];
        // $children3 =
        //     [ 0, 0, 0, 0, 0, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 0, 0, 0, 0];
        // $children4 =
        //     [ 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 2, 2, 2, 2, 2, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        // $children5 =
        //     [ 0, 0, 0, 3, 3, 3, 3, 3, 3, 3, 3, 0, 0, 0, 0, 0, 4, 4, 4, 4, 4, 4, 0, 0, 0, 0, 0, 0, 0, 0];

        $nurseRate = [
            $office->office_information->appropriate_number_0 ? $office->office_information->appropriate_number_0 : 3,
            $office->office_information->appropriate_number_1 ? $office->office_information->appropriate_number_1 : 6,
            $office->office_information->appropriate_number_2 ? $office->office_information->appropriate_number_2 : 7,
            $office->office_information->appropriate_number_3 ? $office->office_information->appropriate_number_3 : 7,
            $office->office_information->appropriate_number_4 ? $office->office_information->appropriate_number_4 : 7,
            $office->office_information->appropriate_number_5 ? $office->office_information->appropriate_number_5 : 7,
        ];

        $timePeriods = self::TIME_PERIODS;
        $len = count($timePeriods);
        $neededNurse0 = [];
        $neededNurse1 = [];
        $neededNurse2 = [];
        $neededNurse3 = [];
        $neededNurse4 = [];
        $neededNurse5 = [];

        $sumPrecise = [];
        $sumRound = [];

        for ($i = 0; $i < $len - 1; $i++)
        {
            $needed0 = round($children0[$i] / $nurseRate[0], 1);
            $needed1 = round($children1[$i] / $nurseRate[1], 1);
            $needed2 = round($children2[$i] / $nurseRate[2], 1);
            $needed3 = round($children3[$i] / $nurseRate[3], 1);
            $needed4 = round($children4[$i] / $nurseRate[4], 1);
            $needed5 = round($children5[$i] / $nurseRate[5], 1);

            $precise =  $needed0 + $needed1 + $needed2 + $needed3 + $needed4 + $needed5 ;
            $round = ceil($precise);

            $sumPrecise[] = $precise;
            $sumRound[] = $round;
            $neededNurse0[] = $needed0;
            $neededNurse1[] = $needed1;
            $neededNurse2[] = $needed2;
            $neededNurse3[] = $needed3;
            $neededNurse4[] = $needed4;
            $neededNurse5[] = $needed5;
        }

        return [
            'children0'     =>  $children0,
            'neededNurse0'  => $neededNurse0,
            'children1'     =>  $children1,
            'neededNurse1'  => $neededNurse1,
            'children2'     =>  $children2,
            'neededNurse2'  => $neededNurse2,
            'children3'     =>  $children3,
            'neededNurse3'  => $neededNurse3,
            'children4'     =>  $children4,
            'neededNurse4'  => $neededNurse4,
            'children5'     =>  $children5,
            'neededNurse5'  => $neededNurse5,
            'sumPrecise'    =>  $sumPrecise,
            'sumRound'      =>  $sumRound,
            'totalChildren' =>  count($childrenPlans)
        ];
    }
}
