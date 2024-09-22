<?php

namespace App\Exports;

use App\Invoice;
use App\Models\EmploymentStatus;
use App\Models\ReasonForVacation;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IndividualSummaryExport implements FromArray, WithHeadings
{
    protected $attendances;
    protected $type;
    protected $month;
    protected $reasonForVacations;

    public function __construct($attendances, $user, $office, $month)
    {
        $this->attendances = $attendances;
        $isHeadquarter = Str::contains($office->name, '本社');
        if ($user->employment_status_id === EmploymentStatus::NORMAL && !$isHeadquarter) {
            $this->type = 'a';
        } else if ($user->employment_status_id === EmploymentStatus::NORMAL && $isHeadquarter) {
            $this->type = 'b';
        } else {
            $this->type = 'c';
        }
        if (!Str::contains($month, '-')) {
            $m = $month % 100;
            $y = (int)floor($month / 100);
            $this->month = $y . '-' . $m;
        } else {
            $this->month = $month;
        }
        $this->reasonForVacations = ReasonForVacation::get();
    }

    public function array(): array
    {

        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
        $res = [];
        if ($this->type === 'a')
        {
            foreach ($this->attendances as $day => $attendance)
            {
                $item = [$day . '日'];
                $date = Carbon::parse($this->month . '-' . $day);
                $item[] = $weekdays[$date->dayOfWeek];
                $item[] = $this->changeTimeFormat($attendance['commuting_time_1']??null);
                $item[] = $this->changeTimeFormat($attendance['leave_time_1']??null);
                $item[] = empty($attendance['behind_time_1']) ? '' : $attendance['behind_time_1'] . '分';
                $item[] = empty($attendance['leave_early_1']) ? '' : $attendance['leave_early_1'] . '分';
                $item[] = $this->changeTimeFormat($attendance['commuting_time_2']??null);
                $item[] = $this->changeTimeFormat($attendance['leave_time_2']??null);
                $item[] = empty($attendance['behind_time_2']) ? '' : $attendance['behind_time_2'] . '分';
                $item[] = empty($attendance['leave_early_2']) ? '' : $attendance['leave_early_2'] . '分';
                $item[] = !empty($attendance['workingHours']) ? number_format($attendance['workingHours'], 2) : '';
                $item[] = !empty($attendance['restHours'])? number_format($attendance['restHours'], 2) : '';
                $item[] = !empty($attendance['actualWorkingHours'])? number_format($attendance['actualWorkingHours'], 2) : '';
                $item[] = !empty($attendance['overtimeHoursStatutory'])? number_format($attendance['overtimeHoursStatutory'], 2) : '';
                $item[] = !empty($attendance['overtimeHoursNonStatutory'])? number_format($attendance['overtimeHoursNonStatutory'], 2) : '';
                $item[] = !empty($attendance['midnightOvertime'])? number_format($attendance['midnightOvertime'], 2) : '';
                $item[] = !empty($attendance['offShiftWorkingHours'])? number_format($attendance['offShiftWorkingHours'], 2) : '';
                $item[] = !empty($attendance['substituteTime'])? number_format($attendance['substituteTime'], 2) : '';
                $item[] = !empty($attendance['substituteDay']) ? Carbon::parse($this->month . '-' . $attendance['substituteDay'])->format('Y/m/d') : '';
                $item[] = '-';
                $item[] = !empty($attendance['annualPaidTime'])? number_format($attendance['annualPaidTime'], 2) : '';
                $item[] = !empty($attendance['annualPaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['specialPaidTime'])? number_format($attendance['specialPaidTime'], 2) : '';
                $item[] = !empty($attendance['specialPaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['specialUnpaidTime'])? number_format($attendance['specialUnpaidTime'], 2) : '';
                $item[] = !empty($attendance['specialUnpaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['otherUnpaidTime'])? number_format($attendance['otherUnpaidTime'], 2) : '';
                $item[] = !empty($attendance['otherUnpaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['absent']) ? 'true': '';
                $item[] = $this->getVacationName($attendance['reasonForVacationId']??null);
                $item[] = $attendance['remark']??'';
                $item[] = '';
                $res[] = $item;
            }
        } else if ($this->type === 'b') {
            foreach ($this->attendances as $day => $attendance)
            {
                $item = [$day . '日'];
                $date = Carbon::parse($this->month . '-' . $day);
                $item[] = $weekdays[$date->dayOfWeek];
                $item[] = $this->changeTimeFormat($attendance['commuting_time_1']??null);
                $item[] = $this->changeTimeFormat($attendance['leave_time_1']??null);
                $item[] = $this->changeTimeFormat($attendance['commuting_time_2']??null);
                $item[] = $this->changeTimeFormat($attendance['leave_time_2']??null);
                $item[] = !empty($attendance['workingHours']) ? number_format($attendance['workingHours'], 2) : '';
                $item[] = !empty($attendance['restHours'])? number_format($attendance['restHours'], 2) : '';
                $item[] = !empty($attendance['actualWorkingHours'])? number_format($attendance['actualWorkingHours'], 2) : '';
                $item[] = !empty($attendance['midnightOvertime'])? number_format($attendance['midnightOvertime'], 2) : '';
                $item[] = '-';
                $item[] = !empty($attendance['annualPaidTime'])? number_format($attendance['annualPaidTime'], 2) : '';
                $item[] = !empty($attendance['annualPaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['specialPaidTime'])? number_format($attendance['specialPaidTime'], 2) : '';
                $item[] = !empty($attendance['specialPaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['specialUnpaidTime'])? number_format($attendance['specialUnpaidTime'], 2) : '';
                $item[] = !empty($attendance['specialUnpaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['otherUnpaidTime'])? number_format($attendance['otherUnpaidTime'], 2) : '';
                $item[] = !empty($attendance['otherUnpaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['absent']) ? 'true': '';
                $item[] = $this->getVacationName($attendance['reasonForVacationId']??null);
                $item[] = $attendance['remark']??'';
                $item[] = '';
                $res[] = $item;
            }
        } else {
            foreach ($this->attendances as $day => $attendance)
            {
                $item = [$day . '日'];
                $date = Carbon::parse($this->month . '-' . $day);
                $item[] = $weekdays[$date->dayOfWeek];
                $item[] = $this->changeTimeFormat($attendance['commuting_time_1']??null);
                $item[] = $this->changeTimeFormat($attendance['leave_time_1']??null);
                $item[] = empty($attendance['behind_time_1']) ? '' : $attendance['behind_time_1'] . '分';
                $item[] = empty($attendance['leave_early_1']) ? '' : $attendance['leave_early_1'] . '分';
                $item[] = $this->changeTimeFormat($attendance['commuting_time_2']??null);
                $item[] = $this->changeTimeFormat($attendance['leave_time_2']??null);
                $item[] = empty($attendance['behind_time_2']) ? '' : $attendance['behind_time_2'] . '分';
                $item[] = empty($attendance['leave_early_2']) ? '' : $attendance['leave_early_2'] . '分';
                $item[] = !empty($attendance['workingHours']) ? number_format($attendance['workingHours'], 2) : '';
                $item[] = !empty($attendance['restHours'])? number_format($attendance['restHours'], 2) : '';
                $item[] = !empty($attendance['actualWorkingHours'])? number_format($attendance['actualWorkingHours'], 2) : '';
                $item[] = !empty($attendance['overtimeWorkingHours'])? number_format($attendance['overtimeWorkingHours'], 2) : '';
                $item[] = !empty($attendance['midnightOvertime'])? number_format($attendance['midnightOvertime'], 2) : '';
                $item[] = '-';
                $item[] = !empty($attendance['annualPaidTime'])? number_format($attendance['annualPaidTime'], 2) : '';
                $item[] = !empty($attendance['annualPaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['specialPaidTime'])? number_format($attendance['specialPaidTime'], 2) : '';
                $item[] = !empty($attendance['specialPaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['specialUnpaidTime'])? number_format($attendance['specialUnpaidTime'], 2) : '';
                $item[] = !empty($attendance['specialUnpaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['otherUnpaidTime'])? number_format($attendance['otherUnpaidTime'], 2) : '';
                $item[] = !empty($attendance['otherUnpaidTime']) ? 'true' : '';
                $item[] = !empty($attendance['absent']) ? 'true': '';
                $item[] = $this->getVacationName($attendance['reasonForVacationId']??null);
                $item[] = $attendance['remark']??'';
                $item[] = '';
                $res[] = $item;
            }
        }
        return $res;
    }
    public function headings(): array
    {
        if ($this->type === 'a')
        {
            $heading = [
                '日付',
                '曜日',
                '出勤①',
                '退勤①',
                '遅刻',
                '早退',
                '出勤②',
                '退勤②',
                '遅刻',
                '早退',
                '実働時間',
                '休憩時間',
                '残業外労働時間',
                '法定内残業時間',
                '法定外残業時間',
                '深夜時間',
                'シフト外出勤',
                '代休日(時間)',
                '代休日(日付)',
                '連勤日',
                '年次有休(時間)',
                '年次有休(日付)',
                '特休有給(時間)',
                '特休有給(日付)',
                '特休無給(時間)',
                '特休無給(日付)',
                'その他無給(時間)',
                'その他無給(日付)',
                '欠勤日',
                '休暇・欠勤理由',
                '備考',
                '確認',
            ];
        } else if ($this->type === 'b') {
            $heading = [
                '日付',
                '曜日',
                '出勤①',
                '退勤①',
                '出勤②',
                '退勤②',
                '実働時間',
                '休憩時間',
                '残業外労働時間',
                '深夜時間',
                'シフト外出勤',
                '連勤日',
                '年次有休(時間)',
                '年次有休(日付)',
                '特休有給(時間)',
                '特休有給(日付)',
                '特休無給(時間)',
                '特休無給(日付)',
                'その他無給(時間)',
                'その他無給(日付)',
                '欠勤日',
                '休暇・欠勤理由',
                '備考',
                '確認',
            ];
        } else {
            $heading = [
                '日付',
                '曜日',
                '出勤①',
                '退勤①',
                '遅刻',
                '早退',
                '出勤②',
                '退勤②',
                '遅刻',
                '早退',
                '実働時間',
                '休憩時間',
                '残業外労働時間',
                '残業時間',
                '深夜時間',
                '連勤日',
                '年次有休(時間)',
                '年次有休(日付)',
                '特休有給(時間)',
                '特休有給(日付)',
                '特休無給(時間)',
                '特休無給(日付)',
                'その他無給(時間)',
                'その他無給(日付)',
                '欠勤日',
                '休暇・欠勤理由',
                '備考',
                '確認',
            ];
        }
        return $heading;
    }

    private function changeTimeFormat($date)
    {
        if (!$date) return '';
        $day = Carbon::parse($date);
        $day->setTimezone('Asia/Tokyo');
        return $day->format('H:i');
    }
    private function getVacationName($vacationId)
    {
        if (!$vacationId) return '';
        $vacation = $this->reasonForVacations->first(function ($value, $key) use($vacationId) {
            return $value->id === $vacationId;
        });
        if ($vacation) return $vacation->name;
        return '';
    }
}
