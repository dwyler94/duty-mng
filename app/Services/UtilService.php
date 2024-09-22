<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use DateTime;

class UtilService
{
    public function bladeCompile($value, array $args = array())
    {
        $generated = \Blade::compileString($value);

        ob_start() and extract($args, EXTR_SKIP);
        // We'll include the view contents for parsing within a catcher
        // so we can avoid any WSOD errors. If an exception occurs we
        // will throw it out to the exception handler.
        try
        {
            eval('?>'.$generated);
        }

        // If we caught an exception, we'll silently flush the output
        // buffer so that no partially rendered views get thrown out
        // to the client and confuse the user with junk.
        catch (\Exception $e)
        {
            ob_get_clean(); throw $e;
        }

        $content = ob_get_clean();

        return $content;
    }

    public function templateCompile($value, $args)
    {
        $search = [];
        $replace = [];
        foreach($args as $s => $r)
        {
            // if (!$r) continue;
            $search[] = '__' . $s . '__';
            $replace[] = $r;
        }
        return Str::replace($search, $replace, $value);
    }

    /**
     * 日時の差分比較
     * $datetime
     */
    function diffTime($datetime) {
        $date_time = new DateTime($datetime);
        $date_time2 = new DateTime();
        $diff = $date_time->diff($date_time2);
        if (!empty($y = $diff->format('%y'))) {
            return $y . '年前';
        } else if (!empty($m = $diff->format('%m'))) {
            return $m . 'ヶ月前';
        } else if (!empty($d = $diff->format('%d'))) {
            return $d . '日前';
        } else if (!empty($h = $diff->format('%h'))) {
            return $h . '時間前';
        } else if (!empty($i= $diff->format('%i'))) {
            return $i . '分前';
        }
        return null;
    }

    /**
     * time型同士の加算（24時間以上表示対応）
     */
    function addTimeToTime($time1, $time2) {
        $total = '00:00';   // 秒は不要
        if (!empty($time1) && !empty($time2) && strpos($time1,':') !== false && strpos($time2,':') !== false) {
            $time1 = explode(':', $time1);
            $time2 = explode(':', $time2);
            $sum = (((int)$time1[0] * 60) + (int)$time1[1]) + (((int)$time2[0] * 60) + (int)$time2[1]);
            $total = sprintf('%02d', floor($sum / 60)) . ':' . sprintf('%02d', $sum % 60);

        } elseif (!empty($time2)) {
            $total = $time2;
        } elseif (!empty($time1)) {
            $total = $time1;
        }
        return $total;
    }

    /**
     * timeとtime型の減算
     */
    function subTimeToTime($time1, $time2) {
        $diff = false;
        if (!empty($time1) && !empty($time2)) {
            if (Carbon::parse($time1)->format('His') > Carbon::parse($time2)->format('His')) {
                $diff = Carbon::parse($time1)->subHours(Carbon::parse($time2)->format('H'))->subMinutes(Carbon::parse($time2)->format('i'))->format('H:i');
            }
        }
        return $diff;
    }
}
