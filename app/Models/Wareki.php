<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Wareki extends Model
{
    use HasFactory;

    /** @var collection */
    private static $eraNames;

    /** @var int */
    public $year;  // 年
    public $fiscalYear;  // 年度
    public $month;
    public $day;

    /** @var EraName */
    public $eraName;

    public static function setEraNames() {
        if (is_null(self::$eraNames)) self::$eraNames = EraName::all();
    }

    /**
     * @param Carbon|CarbonImmutable
     */
    public function __construct($ymd) {
        $this->eraName = self::$eraNames->first(function ($eraName) use ($ymd) {
            return $eraName->start_date->startOfDay() <= $ymd && $ymd <= $eraName->end_date->endOfDay();
        });

        $this->year = $ymd->year - $this->eraName->start_date->year + 1;
        $this->fiscalYear = $this->year - ((1 <= $ymd->month && $ymd->month <= 3) ? 1 : 0);
        $this->month = $ymd->month;
        $this->day = $ymd->day;
    }

    /**
     * @return Carbon|CarbonImmutable
     */
    public function toYmd() {
        return Date::createMidnightDate(
            $this->eraName->start_date->year + $this->year - 1,
            $this->month ?? 1,
            $this->day ?? 1,
        );
    }

    /**
     * @param string $format
     * @return string
     */
    public function format(string $format) {
        $ret = '';
        foreach (mb_str_split($format) as $str) {
            if ($str === 'Y') $ret .= sprintf('%s%02d', $this->eraName->name, $this->year);
            else if ($str === 'S') $ret .= sprintf('%s%02d', $this->eraName->short_name, $this->year);
            else if ($str === 'y') $ret .= sprintf('%s%02d', $this->eraName->name, $this->fiscalYear);
            else if ($str === 's') $ret .= sprintf('%s%02d', $this->eraName->short_name, $this->fiscalYear);
            else if ($str === 'm') $ret .= sprintf('%02d', $this->month);
            else if ($str === 'd') $ret .= sprintf('%02d', $this->day);
            else if ($str === 'w') $ret .= $this->eraName->name . $this->year;
            else if ($str === 'n') $ret .= $this->month;
            else if ($str === 'j') $ret .= $this->day;
            else if ($str === 'F') $ret .= sprintf('%02d', $this->fiscalYear);
            else if ($str === 'f') $ret .= $this->fiscalYear;
            else if ($str === 'c'){
                switch ($this->eraName->name){
                    case '令和':
                        $ret .= sprintf('5 %02d', $this->year);
                        break;
                    case '平成':
                        $ret .= sprintf('4 %02d', $this->year);
                        break;
                    case '昭和':
                        $ret .= sprintf('3 %02d', $this->year);
                        break;
                    case '大正':
                        $ret .= sprintf('2 %02d', $this->year);
                        break;
                }
            }
            else $ret .= $str;
        }

        return $ret;
    }
}
