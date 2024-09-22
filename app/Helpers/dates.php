<?php

use Illuminate\Support\Carbon;

function diffBetweenTwoTimes(string $time1, string $time2, $absolute = false)
{
    // $today = Carbon::now()->format('Y-m-d');

    $time1 = Carbon::parse($time1);
    $time2 = Carbon::parse($time2);

    return $time1->floatDiffInMinutes($time2, $absolute);
}

function diffBetweenTimeAndCarbonTime(string $time, Carbon $carbonTime, $absolute = false)
{
    $date = $carbonTime->format('Y-m-d');
    $time = Carbon::parse($date . ' ' . $time);
    return $time->floatDiffInMinutes($carbonTime, $absolute);
}
function diffBetweenCarbonTimeAndTime(Carbon $carbonTime, string $time, $absolute = false)
{
    $date = $carbonTime->format('Y-m-d');
    $time = Carbon::parse($date . ' ' . $time);
    return $carbonTime->floatDiffInMinutes($time, $absolute);
}
function calcOverlappedPeriod(Carbon $start1, Carbon $end1, Carbon $start2, Carbon $end2)
{
    if ($start1->gt($end1)) {
        $tmp = $end1;
        $end1 = $start1;
        $start1 = $tmp;
    }
    if ($start2->gt($end2)) {
        $tmp = $end2;
        $end2 = $start2;
        $start2 = $tmp;
    }
    $start = $start1->lte($start2) ? $start2 : $start1;
    $end = $end1->lte($end2) ? $end1 : $end2;

    if ($start->gte($end)) {
        return 0;
    }
    return $start->floatDiffInMinutes($end);
}
